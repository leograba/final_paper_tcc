"use strict";
//load modules
var b = require('octalbonescript');
var bodyParser = require('body-parser');
var express = require("express");
var app = express();
var debug = require('debug')('controle');
var pru = require("node-pru-extended");
var fs = require("fs");
var exec = require("child_process").exec;
var spawn = require("child_process").spawn;
var phpExpress = require('php-express')({  // assumes php is in your PATH
// must specify options hash even if no options provided!
  binPath: 'php'
});

//Load modules written for this application
var gpioCfg = require('./my_node_modules/gpio_cfg.js');

//Global variables that should also be saved to a backup file periodically
var environmentVariables = {
	msg: "",//holds some explanatory message
	warn: "",//if not empty holds some warning message
	logTimestamp: "", //holds the epoch time of the current variables state
	recipe: "", //recipe name
	okToStart: false, //true if a recipe is ok enough to start a production
	auto: true, //whether the process is running automatically or there is human intervention
	processFail: false,//flag is set if the process fails irreversibly
	code: "",//tells the same as msg, but as an index, easier to check programatically
	tmpMT: "",//mash tun temperature
	tmpMTsetp: "",//mash tun current setpoint
	tmpBK: "",//brewing kettle temperature, also the "hot liquor tank" for sparging
	tmpBKsetp: "",//brewing kettle/hot liquor tank current setpoint
	ioStatus: gpioCfg.all_io//also records the IO status
};
var temperatureLogHandler ;//variable to handle the python "log.py" script
/*
//Trying to access and/or use the PRU
console.log(pru);
pru.init();//initialize the communications
//pru.loadDatafile(0,"/var/www/myPRUcodes/data.bin");
pru.execute(0,"/var/www/myPRUcodes/pisca_text.bin",0);
//console.log(pru.getSharedRAM());
pru.exit(0);//force the PRU code to terminate
*/

// Pin configuration
debug(gpioCfg.ioStatus.total + " pins being used: ", gpioCfg.all_io_pins.toString());
debug("Configuring pins...");
gpioCfg.pinsConfiguration();

//Using Express to create a server
app.use(bodyParser.urlencoded({//to support URL-encoded bodies, MUST come before routing
  extended: true
}));
 
// set view engine to php-express
app.set('views', './');
app.engine('php', phpExpress.engine);
app.set('view engine', 'php');
 
// routing all .php file to php-express
app.all(/.+\.php$/, phpExpress.router);

app.use(express.static(__dirname));//add the directory where HTML and CSS files are
var server = app.listen(8587, "192.168.1.155", function () {//listen at the port and address
	var host = server.address().address;
	var port = server.address().port;
	var family = server.address().family;
	debug('Express server listening at http://%s:%s %s', host, port, family);
});

app.route('/controle')//used to unite all the requst types for the same route
.post(function (req, res) {
	var serverResponse = {command:req.body.command, btn:req.body.btn, val:req.body.val};
	var command = req.body.command, pin = req.body.btn, val = req.body.val;
	
	if(command == "pinSwitch"){//if command passed by client is to switch a pin configuration
		gpioCfg.changeStatusIO(pin, val);//change the IO status according to the data recieved
		res.send(serverResponse);//echo the recieved data to the server
	}
	else if(command == "getStatus"){
		//debug(gpioCfg.getSystemStatus());
		//res.send(gpioCfg.getSystemStatus());
		environmentVariables.ioStatus = gpioCfg.all_io;//all of the pins status
		res.send(environmentVariables);
	}
});

app.route('/startrecipe')//used to unite all the requst types for the same route
.post(function (req, res) {
	var serverResponse = {resp:"success"};
	var command = req.body.command;
	debug("Command: " + command);
	var recipesPath = "./recipes";//path to the recipes directory
	var bkpFile = "./datalog/backup.log";//path to the backup logs file 
	var lockFile = "./datalog/lockfile";//file that tells if recipe is running
	var recipeName ;
	if(command == "getRecipes"){//if command passed by client is to get the recipe names
		sendRecipeNames(recipesPath, res);//get it and return to the client
	}
	else if(command == "startRequest"){//if the client wants to start a recipe
		recipeName = req.body.recipe + ".recipe";//get the recipe name
		checkRecipeIntegrity(recipeName, recipesPath, res);
	}
	else if(command == "startRecipe"){//start the recipe; should only be requested after the "startRequest" command
		recipeName = req.body.recipe + ".recipe";//recipe name sent from the client
		startMashingProcess(recipeName, res, lockFile, recipesPath, function(err, nextStep){
			if(err){
				debug("Ops, something wrong. It's so annoying that the error isn't explained here, isn't it?");
				return;
			}
			debug(nextStep);//here the next step, i.e. controlling the mash steps, should be called
		});//start the production
	}
	else if(command == "inProgress"){//checks if there is a recipe running
		fs.readFile(lockFile, function(err, data){
			if(err){
				serverResponse.resp = "false";//assumes the error means no recipe is in progress
				res.send(serverResponse);
			}
			else{
				if(+data == 1){//if there is a recipe in progress
					serverResponse.resp = "true";
					fs.readFile(bkpFile, "utf-8", function(err, data){
						if(err){//if contents of log could not be retrieved
							serverResponse.recipe = "unknown";//not the best thing to do
							res.send(serverResponse);
						}
						else{
							var lines = data.trim().split('\n');//separate line by line
							debug(lines.slice(-1)[0]);
							var lastLine = lines.slice(-1)[0];//get the last log line
							var properties = JSON.parse(lastLine);//JSON to object
							properties.recipe = properties.recipe.replace(".recipe", "");//remove the file extension
							properties.recipe = properties.recipe.replace(/_/g, " ");//replace underlines with spaces
							serverResponse.recipe = properties.recipe;//send recipe name to the client
							debug(serverResponse);
							res.send(serverResponse);
						}
					});
				}
				else{
					serverResponse.resp = "false";
					res.send(serverResponse);
				}
			}
		});
	}
	else{
		debug("command not found, doing nothing");
		serverResponse.resp = "fail";
		res.send(serverResponse);
	}
});

app.route('/config')//used to unite all the requst types for the same route
.post(function (req, res) {
	var request = req.body.request;
	var nd = req.body.newdate;
	var serverResponse = {datetime:""};
	if(request == "datetime"){//send the system time/date to the client
		serverResponse.datetime = Date();
		res.send(serverResponse);
	}
	else if(request == "setdatetime"){//change the system time/date
		exec('sudo date -s ' + nd + ' | hwclock -w',//execute shell command
		function(error, stdout, stderr) {
			debug('stdout: ' + stdout);
			debug('stderr: ' + stderr);
			if (error !== null) {//if new date wasn't correctly set
				debug('exec error: ' + error);//print error
				serverResponse.datetime = "error";//send fail to client
				res.send(serverResponse);
			}
			else{//if date was correctly set, send ok to client
				debug("date changed to: " + Date(nd));//print
				serverResponse.datetime = "ok";
				res.send(serverResponse);
			}
		});
	}
});

function startMashingProcess(recipe, res, lockFile, recipesPath, callback){
	/*Starts the mashing process, checking if everything is in order, starting
	the temperature logging and the heating of the mash water.
	The callback function is passed as callback to the heatMashWater function*/
	var serverResponse = {resp:"success"};
	var recipeContents ;
	if(environmentVariables.okToStart){//first check is if the recipe is ok to start
		if(recipe == environmentVariables.recipe){//recipe name should match also
			fs.writeFile(lockFile, 1, function(err){//write to lockfile telling there is a recipe in progress
				if(err){
					serverResponse.resp = "could not write to lockfile";
					res.send(serverResponse);
				}
				else{
					logToFile("production starting", 1);//log to file
					fs.readFile(recipesPath + "/" + environmentVariables.recipe, function(err, data){
						if(err){//if file contents could not be retrieved
							serverResponse.resp = "couldn't read recipe file";//tells the client
							res.send(serverResponse);
						}
						else{///if file was successfully read
							startTemperatureLogging();
							recipeContents = data.toString("UTF8").split("\n");//split contents to array
							for(var i = 0; i < recipeContents.length; i++){//get only the relevant data
								recipeContents[i] = recipeContents[i].split('"')[1];
							}
							serverResponse.resp = "success";//tells the client everything went alright
							res.send(serverResponse);
							heatMashWater(recipeContents[7], recipeContents[5]);//start to heat the mash water
							callback("fuckme!","Run, baby, run!\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n");
						}
					});
				}
			});
		}
		else{//if recipe name doesn't match the one from "startRequest"
			serverResponse.resp = "failed";
			res.send(serverResponse);
		}
	}
	else{//if recipe isn't ok to start
		serverResponse.resp = "failed";
		res.send(serverResponse);
	}
}

function startTemperatureLogging(){
	//starts the python script that logs temperature to file
	//sudo kill $(ps aux | grep log.py | grep -v grep | awk '{print $2}') //to stop the process from terminal
	fs.unlink("./datalog/instant.csv", function(err){//try to delete the old last saved value
		if(err){//this may happen if the old log was already deleted
			debug("file could not be deleted!");//probably nothing to worry about
		}
		//start the logging process anyway
		temperatureLogHandler = spawn("python", ["/home/debian/brewing/log.py"]);//starts to log
		debug("Temperature logging started!");
		temperatureLogHandler.on('close', function(code, signal){
			debug("Temperature logging stopped!");
		});
		//temperatureLogHandler.kill('SIGHUP');//kill the process and stop logging
	});
}

function heatMashWater(mashSetpoint, spargeSetpoint){
	//heats the mashing water to the starting setpoint
	var retStatus = false;//false means fail, true means success
	var heatingPower = 2;
	var instantPath = "./datalog/instant.csv";
	var lockFile = "./datalog/lockfile";//file that tells if recipe is running
	var errorCount = 0;//counts the file reading errors
	var lastReadingsTimestamp = [0, 0, 0, 0, 0];//last 5 timestamps, to check if readings are going ok
	var lastValidTemperature = 0;//the last valid temperature reading (defaults to zero, not the better solution)
	var reachedSetpoint = false;//var set to true the first time the checkpoint is reached
	environmentVariables.tmpMTsetp = mashSetpoint;//stores the setpoints
	environmentVariables.tmpBKsetp = spargeSetpoint;
	gpioCfg.changeStatusIO("mash_pump", "true");//turn the recirculation pump on
	
	var logTimer = setInterval(function(){//logs the heating process every ~5s
		logToFile("heating mash water", 2);//log to file
	}, 5000);
	var readTmpTimer = setInterval(function(){//read temperature every second
		fs.readFile(instantPath, "utf-8" ,function(err, data){//gets the most recent temperature reading
		var parsedData ;//variable to hold the relevant data (temperature and its timestamp)
			if(err){//could not read temperature
				errorCount++;//increment the errorCount variable
				if(errorCount >= 180){//180 arbitrarily chosen - it is 5% of 1 hour logging readings
					//oh my god thigs are bad! User, you must take over from here
					retStatus = false;//return error
					debug("Too many temperature reading errors, something may be going awry!");
				}
			}
			else{//if temperature reading from file was successful
				parsedData = data.trim().split('\n').slice(-1)[0].split(',');
				if((parsedData[0] == "temperature") || (typeof parsedData[0] != "string") || isNaN(+parsedData[0])){//wrong reading beacuse of wrong logging
					debug("Wrong temperature reading: " + parsedData[0]);
					parsedData[0] = lastValidTemperature;//then use the last valid temperature reading
					errorCount++;//increment the errorCount variable
					if(errorCount >= 180){//180 arbitrarily chosen - it is 5% of 1 hour logging readings
						//oh my god thigs are bad! User, you must take over from here
						retStatus = false;//return error
						debug("Too many temperature reading errors, something may be going awry!");
					}
				}
				else{
					parsedData[0] = +parsedData[0];//string to number
					lastValidTemperature = +parsedData[0];//save the last valid temperature reading
				}
				lastReadingsTimestamp[4] = parsedData[1];//updates last temperature reading timestamp
				for(var i = 0; i < 4; i++){//updates the older timestamps also
					lastReadingsTimestamp[i] = lastReadingsTimestamp[i+1];
				}
				if(lastReadingsTimestamp[0] >= lastReadingsTimestamp[4]){//if the reading is the same within 4s
					debug("Log not updating, check temperature probe connection!");
					errorCount++;//increment the errorCount variable
					if(errorCount >= 180){//180 arbitrarily chosen - it is 5% of 1 hour logging readings
						//oh my god thigs are bad! User, you must take over from here
						retStatus = false;//return error
						debug("Too many temperature reading errors, something may be going awry!");
					}
					//hey user check this, because something may be going awry!
				}
				else{//then we can act in order to get to the temperature setpoint
					environmentVariables.tmpMT = parsedData[0];//update the mash tun temperature
					debug(typeof parsedData[0]);
					if(parsedData[0] < 0.7*mashSetpoint){//if temperature is less then 0.7 of the setpoint
						gpioCfg.changeStatusIO("mash_heat", "true");//give 100% power to the heating resistor
					}
					else if(parsedData[0] < 0.9*mashSetpoint){//if temperature between 0.7 and 0.9 of the setpoint 
						if(heatingPower == 0){//give 66% power to the heating resistor
							gpioCfg.changeStatusIO("mash_heat", "false");// 1/3 of the time off
							heatingPower = 2;// heatingPower goes from 0 to 2
						}
						else{
							gpioCfg.changeStatusIO("mash_heat", "true");// 2/3 of the time on
							heatingPower--;
							
						}
					}
					else if(parsedData[0] < mashSetpoint){//if temperature between 0.9 and 1.0 of the setpoint 
						if(heatingPower == 0){//give 33% power to the heating resistor
							gpioCfg.changeStatusIO("mash_heat", "true");// 1/3 of the time on
							heatingPower = 2;// heatingPower goes from 0 to 2
						}
						else{
							gpioCfg.changeStatusIO("mash_heat", "false");// 2/3 of the time off
							heatingPower--;
							
						}
					}
					else{//in this case, the temperature got to the setpoint
						//should everything be turned off or should it try to keep the temperature?
						if(!reachedSetpoint){//if it is the first time the setpoint is reached
							reachedSetpoint = true;//holds this information
							clearInterval(logTimer);//stop the logging
							logToFile("waiting for grains", 3);//log to file at least once
							logTimer = setInterval(function(){//logs that system is waiting for user input
								logToFile("waiting for grains", 3);//log to file
							}, 5000);
							temperatureLogHandler.kill('SIGHUP');//kill the process and stop logging (for tests only)
							//send message to the user and wait for him to add the grains
						}
						//just do the next things after the user added the grains
						clearInterval(logTimer);//stop the "waiting for grains" logging (just for testing purposes)
						fs.writeFile(lockFile, 0, function(err){//release the lock (just for testing purposes)
							if(err){
								//serverResponse.resp = "could not write to lockfile";
							}
						});
						gpioCfg.changeStatusIO("mash_pump", "false");//turn the recirculation pump off
						gpioCfg.changeStatusIO("mash_heat", "false");//turn the heating element off
						//clearInterval(readTmpTimer);//stop the temperature adjusting loop
						//temperatureLogHandler.kill('SIGHUP');//kill the process and stop logging (for tests only)
						debug(	"    Heating of the mash water finished\n"
								+ "            Temperature = " + parsedData[0]
								+ "            Reading errors = " + errorCount);
						//tell the user he must add the grains to the water
						//return happily ever after
						clearInterval(readTmpTimer);//do it only after user acknowlegde about adding the grains (return anyway for testing purposes)
						retStatus = true;//do it only after user acknowlegde about adding the grains (return anyway for testing purposes)
					}
				}
			}
		});
	},1000);
	return retStatus;
}

function checkRecipeIntegrity(recipe, path, res){
	var serverResponse = {resp: "success", warn: "", err: ""};//tells the client if everything is ok
	var recipeContents ;
	environmentVariables.recipe = recipe;//save the recipe name
	fs.readFile(path + "/" + recipe, function(err, recipeFileContents){
		var okToStartFlag = 1;
		if(err){//if file contents could not be retrieved
			serverResponse.resp = "couldntReadFile";//tells the client
			res.send(serverResponse);
		}
		else{///if file was successfully read
			fs.readFile("./datalog/lockfile", function(err, lockFileContents){
				if(err){//if lockfile could not be read for some reason
					serverResponse.resp = err;
					//res.send(serverResponse);
					okToStartFlag = 0;//unable to start the recipe
					debug("couldn't read lockfile, unable to start recipe.");
					return;
				}
				if(+lockFileContents == 0){//if there is no recipe in progress
					debug("ready to rock!");
					recipeContents = recipeFileContents.toString("UTF8").split("\n");//split contents to array
					for(var i = 0; i < recipeContents.length; i++){//get only the relevant data
						recipeContents[i] = recipeContents[i].split('"')[1];
						if(!recipeContents[i]){//if some recipe line is blank
							switch(i){//and this line is important, then:
								case 0://recipe name not set (warning)
									serverResponse.warn += "nome da receita; ";
									break;
								case 1://beer style not set (warning)
									serverResponse.warn += "estilo; ";
									break;
								case 2://beer yeast not set (warning)
									serverResponse.warn += "levedura; ";
									break;
								case 3://mash water not set (error)
									serverResponse.err += "água de brassagem; ";
									environmentVariables.okToStart = false;
									okToStartFlag = 0;
									break;
								case 4://sparging water not set (warning)
									serverResponse.warn += "água de sparging; ";
									break;
								case 5://sparging water temperature not set (warning)
									serverResponse.warn += "temperatura de sparging; ";
									break;
								case 6://boiling time not set (error)
									serverResponse.err += "tempo da fervura; ";
									environmentVariables.okToStart = false;
									okToStartFlag = 0;
									break;
								case 7://mash initial temperature not set (error)
									serverResponse.err += "temperatura inicial de brassagem; ";
									environmentVariables.okToStart = false;
									okToStartFlag = 0;
									break;
								case 8://mash first step not set (error)
									serverResponse.err += "primeiro degrau de temperatura; ";
									environmentVariables.okToStart = false;
									okToStartFlag = 0;
									break;
								case 9://mash first step time not set (error)
									serverResponse.err += "tempo do primeiro degrau; ";
									environmentVariables.okToStart = false;
									okToStartFlag = 0;
									break;
								case 24://malt 1 not set (error)
									serverResponse.err += "malte 1; ";
									environmentVariables.okToStart = false;
									okToStartFlag = 0;
									break;
								case 25://malt 1 quantity not set (error)
									serverResponse.err += "quantidade do malte 1; ";
									environmentVariables.okToStart = false;
									okToStartFlag = 0;
									break;
								case 40://hop 1 not set (error)
									serverResponse.err += "lúpulo 1; ";
									environmentVariables.okToStart = false;
									okToStartFlag = 0;
									break;
								case 41://hop 1 quantity not set (error)
									serverResponse.err += "quantidade do lúpulo 1; ";
									environmentVariables.okToStart = false;
									okToStartFlag = 0;
									break;
								case 42://hop 1 adding time not set (error)
									serverResponse.err += "tempo de adição do lúpulo 1; ";
									environmentVariables.okToStart = false;
									okToStartFlag = 0;
									break;
							}
						}
					}
					if(okToStartFlag){//if recipe can be started
						environmentVariables.okToStart = true;//add this info to the global variables
					}
					logToFile("request to start production", 0);
					res.send(serverResponse);//answer to the client
					debug(serverResponse);
				}
			});
		}
	});
}

function logToFile(message, code){
	var d = new Date();
	var dataToSave ;
	var logFile = "./datalog/backup.log";//path to the backup logs file
	environmentVariables.msg = message;//explanatory message to be logged
	environmentVariables.code = code;//code referring to the message
	environmentVariables.logTimestamp = d.getTime();//logs the request to start recipe timestamp
	environmentVariables.ioStatus = gpioCfg.all_io;//all of the pins status
	dataToSave = JSON.stringify(environmentVariables) + "\n";
	debug(dataToSave);
	if(code == 0){//if it is the first line to be logged, overwrite log file
		fs.writeFile(logFile, dataToSave, function(err){//overrwite previous backup
			if(err){//if was unable to log to the file
				debug("could not write to the backup file!");//well my friend, you're on your own!
			}
			else{
				debug("backup log started!");
			}
		});
	}
	else{//otherwise, just append the data
		fs.appendFile(logFile, dataToSave, function(err){//overrwite previous backup
			if(err){//if was unable to log to the file
				debug("could not write to the backup file!");//well my friend, you're on your own!
			}
			else{
				debug("backup log updated!");
			}
		});
	}
}

function sendRecipeNames(path, res){//try to read files in directory
	fs.readdir(path, function(err,files){
		var deletedIndexes = new Array();// variable that holds the indexes of the deleted recipes
		var serverResponse = {resp:"success"};
		if(err){//if something is wrong
			debug(err);//print the error
			serverResponse.resp = "error";
			serverResponse.recipes = err;
		}
		else{
			for(var i = 0; i < files.length; i++){//iterate the array of names
				if(files[i].indexOf(".del") > 0){//if the server reads a deleted file
					deletedIndexes.push(i);
				}
				files[i] = files[i].replace(".recipe", "");//remove the file extension
				files[i] = files[i].replace(/_/g, " ");//replace underlines with spaces
			}
			for(i = (deletedIndexes.length)-1; i >= 0;  i--){//iterate the array of deleted recipes
				//debug("index: " + deletedIndexes[i]);
				files.splice(deletedIndexes[i],1);//deletes the file name from the array
			}
			debug("deleted recipes indexes: " + deletedIndexes);
			serverResponse.recipes = files;
		}
		res.send(serverResponse);//send the recipes if successful, otherwise sends the error
	});//get it and return to the client
}