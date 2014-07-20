// Load modules
var b = require('bonescript');

// I/O pins
var led = {id:"USR1", state:b.LOW};

// Pin configuration
	b.pinMode(led.id, b.OUTPUT, 7);//everyone is output


//initial state, everyone LOW
	b.digitalWrite(led.id, led.state);

//test to see if the ports work
var timeLed = 1000;
var timeOn = process.argv[2];
//setInterval(ioState, timeLed);//PWM using setTimeout
setInterval(pwm2, timeLed);//PWM ONTHERACE

function pwm2(){
	led.state = b.HIGH;
	b.digitalWrite(led.id, led.state);
	setTimeout(function(){
                led.state = b.LOW;
                b.digitalWrite(led.id, led.state);
        },timeOn);
}

function ioState(){
	if(led.state == b.HIGH){//if HIGH
		setTimeout(function(){
			led.state = b.LOW;
			b.digitalWrite(led.id, led.state);
		},timeOn);
	}
	else{
		setTimeout(function(){
			led.state = b.HIGH;
			b.digitalWrite(led.id, led.state);
		},timeLed-timeOn);
	}
}
