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
//load modules written for this application
var gpioCfg = require('./my_node_modules/gpio_cfg.js');
var ctrl = require('./my_node_modules/ctrl.js');
var log = require('./my_node_modules/log_check_misc.js');

//global variables that should also be saved to a backup file periodically
global.environmentVariables = {
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
		global.environmentVariables.ioStatus = gpioCfg.all_io;//all of the pins status
		res.send(global.environmentVariables);
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
		log.sendRecipeNames(recipesPath, res);//get it and return to the client
	}
	else if(command == "startRequest"){//if the client wants to start a recipe
		recipeName = req.body.recipe + ".recipe";//get the recipe name
		log.checkRecipeIntegrity(recipeName, recipesPath, res);
	}
	else if(command == "startRecipe"){//start the recipe; should only be requested after the "startRequest" command
		recipeName = req.body.recipe + ".recipe";//recipe name sent from the client
		ctrl.startMashingProcess(recipeName, res, lockFile, recipesPath, function(err, nextStep){
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