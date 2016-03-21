"use strict";
//load modules
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
//load modules written for this application
var gpioCfg = require('./my_node_modules/gpio_cfg.js');
var ctrl = require('./my_node_modules/ctrl.js');
var log = require('./my_node_modules/log_check_misc.js');
var routes = require('./my_node_modules/routes.js');

//global variables that should also be saved to a backup file periodically
global.environmentVariables = {
	msg: "",//holds some explanatory message
	warn: "",//if not empty holds some warning message
	logTimestamp: "", //holds the epoch time of the current variables state
	recipe: "", //recipe name
	okToStart: false, //true if a recipe is ok enough to start a production
	auto: true, //whether the process is running automatically or there is human intervention
	processFail: false,//flag is set if the process fails irreversibly
	readyForNextStep: false,//set whenever the system is ready for the next step
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
.post(routes.controleRoute);

app.route('/startrecipe')//used to unite all the requst types for the same route
.post(routes.startrecipeRoute);

app.route('/config')//used to unite all the requst types for the same route
.post(routes.configRoute);

app.route('/clientrequest')//used to unite all the requst types for the same route
.post(routes.clientrequestRoute);