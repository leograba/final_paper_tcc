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
var phpExpress = require('php-express')({  // assumes php is in your PATH
// must specify options hash even if no options provided!
  binPath: 'php'
});

/*
//Trying to access and/or use the PRU
console.log(pru);
pru.init();//initialize the communications
//pru.loadDatafile(0,"/var/www/myPRUcodes/data.bin");
pru.execute(0,"/var/www/myPRUcodes/pisca_text.bin",0);
//console.log(pru.getSharedRAM());
pru.exit(0);//force the PRU code to terminate
*/

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
app.route('/controle')//used to unite all the requst types for the same route
/*.get(function (req, res) {
  //res.send('Hello World!');
  //res.sendFile(__dirname + "/controle.html");//load HTML page
})*/
.post(function (req, res) {
	var serverResponse = {command:req.body.command, btn:req.body.btn, val:req.body.val};
	var command = req.body.command, pin = req.body.btn, val = req.body.val;
	
	if(command == "pinSwitch"){//if command passed by client is to switch a pin configuration
		changeStatusIO(pin, val);//change the IO status according to the data recieved
		res.send(serverResponse);//echo the recieved data to the server
	}
	else if(command == "getStatus"){
		//console.log(getStatusIO());
		res.send(getStatusIO());
	}
});

app.route('/startrecipe')//used to unite all the requst types for the same route
.post(function (req, res) {
	var serverResponse = {resp:"success"};
	var command = req.body.command; 
	var recipesPath = "./recipes";//path to the recipes directory
	var deletedIndexes = new Array();
	
	if(command == "getRecipes"){//if command passed by client is to get the recipe names
		fs.readdir(recipesPath, function sendRecipeNames(err, files){//try to read files in directory
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
				for(var i = (deletedIndexes.length)-1; i >= 0;  i--){//iterate the array of deleted recipes
					debug("index: " + deletedIndexes[i]);
					files.splice(deletedIndexes[i],1);//deletes the file name from the array
				}
				debug("deleted indexes: " + deletedIndexes);
				serverResponse.recipes = files;
			}
			res.send(serverResponse);//send the recipes if successful, otherwise sends the error
		});
	}
	else{
		console.log("fez");
		res.send(req);
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

function changeStatusIO(pin, val){
	if(val == "true"){//if button is checked
		all_io[pin].state = b.HIGH;//turn corresponding pin HIGH
		b.digitalWriteSync(all_io[pin].id, all_io[pin].state);
		debug("Pin " + pin + " turned HIGH");
	}
	else if(val == "false"){//if button is unchecked
		all_io[pin].state = b.LOW;//turn corresponding pin LOW
		b.digitalWriteSync(all_io[pin].id, all_io[pin].state);
		debug("Pin " + pin + " turned LOW");
	}
	else{//if it is not a button
		if(pin == "servo_pwm"){//check if this is the PWM
			servo_pwm.state.duty = 0.0325 + (0.0775/180)*val;//turn degree to duty-cycle
			b.analogWrite(servo_pwm.id,servo_pwm.state.duty,servo_pwm.state.freq, function(err_wr){//set PWM
				if(err_wr){//if anything goes wrong
					debug(err_wr);//print the error
				}
				else{//otherwise
					debug("Servo angle set to: " + val);//print the angle the PWM was set to
				}
			});
		}
	}
}

function getStatusIO(){
	var all_io_status = {};//object with all the pin pairs key:value
	for(i = 0; i < all_io_objects.length; i++){//get the pair key:value pin by pin
		all_io_status[all_io_objects[i]] = all_io[all_io_objects[i]].state;
	}
	return(all_io_status);
}

var server = app.listen(8587, "192.168.1.155", function () {//listen at the port and address
	var host = server.address().address;
	var port = server.address().port;
	var family = server.address().family;
	debug('Express server listening at http://%s:%s %s', host, port, family);
});

// I/O pins
function PinObjectIO(pinId){//function to create pin object, should recieve pin ID
	if(pinId){//if the variable is passed to function or not empty
		this.id = pinId; this.state = b.LOW; this.cfg = b.OUTPUT;
	}
	else{//if no variable is passed or passed empty
		debug("No variable passed to create pin object");
		process.exit(1);//exit process with error code
	}
}
var led = new PinObjectIO("USR1");

var mash_pump = new PinObjectIO("P8_07");
var boil_pump = new PinObjectIO("P8_08");
var pumps = {mash_pump:mash_pump, boil_pump:boil_pump};

var mash_valve = new PinObjectIO("P8_09");
var boil_valve = new PinObjectIO("P8_10");
var chill_valve = new PinObjectIO("P8_11");
var water_valve = new PinObjectIO("P8_12");
var valves = {	mash_valve:mash_valve, boil_valve:boil_valve,
				chill_valve:chill_valve, water_valve:water_valve};

var mash_heat = new PinObjectIO("P8_13");
var boil_heat = new PinObjectIO("P8_14");
var heaters = {mash_heat:mash_heat, boil_heat:boil_heat};

		//for servo, use 0.0325 < duty < 0.11 to protect servo integrity
var servo_pwm = {id:"P8_19", state:{duty:0.11, freq:50}, cfg:b.ANALOG_OUTPUT};//state of PWM is duty-cycle and frequency
//var servo_pwm = {id:"P8_19", state:{duty:0.11, freq:60}};//this is for the SSR test

var all_io = collect(pumps,valves,heaters,{led:led},{servo_pwm:servo_pwm});
var all_io_objects = Object.keys(all_io);//get all the keys, because cannot access object by index, e.g all_io[2]
var all_io_pins = [];//all the pins used as an array
for (var i = 0; i < all_io_objects.length; i++){//get the pins one by one
	all_io_pins[i] = all_io[all_io_objects[i]].id;//and add to the array
}

var ioStatus =	{cfgok:0, gpio:0, pwm:0, analog:0, interrupt:0, total:all_io_objects.length, //helping object
				//some functions just to exercise the use of methods
				newGpio:		function(){	this.cfgok++; this.gpio++;},
				newPwm:			function(){	this.cfgok++; this.pwm++;},
				newAnalog:		function(){	this.cfgok++; this.analog++;},
				newInterrupt:	function(){	this.cfgok++; this.analog++;},
				gpio2pwm:		function(){ this.gpio--; this.pwm++;},
				gpio2interrupt:	function(){ this.gpio--; this.interrupt++;},
				pwm2gpio:		function(){ this.pwm--; this.gpio++;},
				pwm2interrupt:	function(){ this.pwm--; this.interrupt++;},
				interrupt2gpio:	function(){ this.interrupt--; this.gpio++;},
				interrupt2pwm:	function(){ this.interrupt--; this.pwm++;}
};
//helping variables
debug(ioStatus.total + " pins being used: ", all_io_pins.toString());

// Pin configuration
debug("Configuring pins...");
for (var i = 0; i < all_io_objects.length; i++){//set all pins as outputs
	(function (pinIndex){//need to create a scope for the current pin variable, because it is asynchronous
		//debug("pin passed is " + this + "; pin index passed is " + pinIndex);//"this" is the first parameter passed -> the current pin
		b.pinMode(this, all_io[all_io_objects[pinIndex]].cfg, function(err, pin){//configure and callback function
			if(err)//if by the end of executing pinMode function, there is an error
				console.error(err.message);//then the error is printed
			else{//initial state, everyone LOW because HIGH state means ON
				if(all_io[all_io_objects[pinIndex]].cfg == b.OUTPUT){//if pin is configured as digital output
					debug('    pin ' + pin + ' ready[OUTPUT], ' + (ioStatus.cfgok+1) + "/" + ioStatus.total + "pins configured");
					b.digitalWriteSync(pin, all_io[all_io_objects[pinIndex]].state);//state is LOW because of initial values
					//ioStatus.cfgok++;
					ioStatus.newGpio();//indicates one more pin is configured as gpio
					if(ioStatus.cfgok == ioStatus.total){//if all pins are already configured
						//interval = setInterval(function(){ioTest()}, 5000);//then start the blinking test very slow
						debug(ioStatus.gpio + " pins as GPIO, " + ioStatus.pwm + " as PWM, " + 
							ioStatus.analog + " as ANALOG, " + ioStatus.interrupt + " as INTERRUPT");
					}
				}
				else if(all_io[all_io_objects[pinIndex]].cfg == b.ANALOG_OUTPUT){//if pin is configured as PWM
					debug('    pin ' + pin + ' ready[PWM], ' + (ioStatus.cfgok+1) + "/" + ioStatus.total + "pins configured");
					b.analogWrite(servo_pwm.id,servo_pwm.state.duty,servo_pwm.state.freq, function(err_wr){//try to configure
						if(err_wr){//if error
							debug(err_wr);
						}
						else{//if ok
							ioStatus.newPwm();//indicates one more pin is configured as PWM
							if(ioStatus.cfgok == ioStatus.total){//if all pins are already configured
								//interval = setInterval(function(){ioTest()}, 5000);//then start the blinking test very slow
								debug(ioStatus.gpio + " pins as GPIO, " + ioStatus.pwm + " as PWM, " + 
									ioStatus.analog + " as ANALOG, " + ioStatus.interrupt + " as INTERRUPT");
							}
							/*debug(ioStatus.gpio + " pins as GPIO, " + ioStatus.pwm + " as PWM, " + 
									ioStatus.analog + " as ANALOG, " + ioStatus.interrupt + " as INTERRUPT");*/
						}
					});
				}
			}
				
		});//everyone is output
	//get all the object keys as vector and use it to access all the object keys
	}).call(all_io[all_io_objects[i]].id,i);//passes pin as "this" and index as pinIndex
}
	
//control variables
//var 

//Brewing control
/*var temp_wort, temp_sparge, time_window;//variables to control temperatures and time window
turnOnPID(temp_wort, temp_sparge, time_window);//pass the variables to the PID and turn it ON
if(pru.getSharedRAM() == "done"){//if the heating of the mash water is done
	mashControl();
}

function mashControl(){//function that controls the mash process 
	//add the malts to the water
	turnOnPID(temp_wort, temp_sparge, time_window);//turn on the temperature control
	mash_pump.state = b.HIGH;//mash pump status is updated
	b.digitalWriteSync(mash_pump.id, mash_pump.state);//turn the mash pump ON
	pru.getSharedRAM();//get the current temperature to display to client
	
}*/

//test to see if the ports work
var interval;
//var mySetInterval = setInterval(ioTest, process.argv[2]);
debug("Switching time: " + interval);
//setInterval(ioTest, interval);//period = interval*IOpins = 500ms*9 = 4.5s

function ioTest(){
	var on_count = 0;//number of io turned b.HIGH
	var last_on; //number of last io b.HIGH
	
	for(i = 0; i < all_io_objects.length; i++){//check all
		if(all_io[all_io_objects[i]].state == b.HIGH){//if HIGH
			on_count++;//one more io HIGH
			last_on = i;//this is the last io HIGH
		}
	}
	if(on_count == 1){//if only one IO HIGH
		if(last_on == 8){//if last pin is the one HIGH
			//debug('Activating pin 0');
			all_io[all_io_objects[last_on]].state = b.LOW;//turn it LOW
			all_io[all_io_objects[0]].state = b.HIGH;//and next one HIGH, which means the first
			b.digitalWriteSync(all_io[all_io_objects[last_on]].id, all_io[all_io_objects[last_on]].state);
			b.digitalWriteSync(all_io[all_io_objects[0]].id, all_io[all_io_objects[0]].state);
		}
		else{//if any pin but the last is the one HIGH
			//debug('Activating pin ' + (last_on+1));
			all_io[all_io_objects[last_on]].state = b.LOW;//turn it LOW
			all_io[all_io_objects[last_on+1]].state = b.HIGH;//and next one HIGH
			b.digitalWriteSync(all_io[all_io_objects[last_on]].id, all_io[all_io_objects[last_on]].state);
			b.digitalWriteSync(all_io[all_io_objects[last_on+1]].id, all_io[all_io_objects[last_on+1]].state);
			}
	}
	else{//if there is more than one IO HIGH
		for(i = 1; i < all_io_objects.length; i++){
			all_io[all_io_objects[i]].state = b.LOW;//turn all but first LOW
			b.digitalWriteSync(all_io[all_io_objects[i]].id, all_io[all_io_objects[i]].state);
		}
		all_io[all_io_objects[0]].state = b.HIGH;//turn first HIGH
		b.digitalWriteSync(all_io[all_io_objects[0]].id, all_io[all_io_objects[0]].state);
	}
}

function collect() {//function concat objects
	var ret = {};//the new object
	var len = arguments.length;//the total number of objects passed to collect
	for (var i=0; i<len; i++) {//do it for every object passed
		for (var p in arguments[i]) {//iterate the i-eth object passed
			if (arguments[i].hasOwnProperty(p)) {//whenever there is a property
				ret[p] = arguments[i][p];//add the property to the new object
			}
		}
	}
	return ret;
}