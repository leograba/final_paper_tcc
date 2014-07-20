//Include necessary node packages
//ds18b20 = require('/usr/local/lib/node_modules/ds18b20');
    var ds18b20 = require('./node_modules/ds18b20');
    var fs = require('fs');//filesystem para trabalhar com arquivos
    var http = require('http');
    var path = require('path');


var interval = 1000; //enter the time between sensor queries here (in milliseconds)
var bt1status = "off";//status do botao 1

// Initialize the server on port 8586
var server = http.createServer(function (req, res) {
    // requesting files
    var file = '.'+((req.url=='/')?'/index.html':req.url);
    var fileExtension = path.extname(file);
    var contentType = 'text/html';
    // Uncoment if you want to add css to your web page
    /*
    if(fileExtension == '.css'){
        contentType = 'text/css';
    }*/
    fs.exists(file, function(exists){
        if(exists){
            fs.readFile(file, function(error, content){
                if(!error){
                    // Page found, write content
                    res.writeHead(200,{'content-type':contentType});
                    res.end(content);
                }
            })
        }
        else{
            // Page not found
            res.writeHead(404);
            res.end('Page not found');
        }
    })
}).listen(8587);

// Loading socket io module
var io = require('socket.io').listen(server);

//when a client connects
io.sockets.on('connection', function (socket) {

    var sensorId = [];
    //fetch array containing each ds18b20 sensor's ID
    ds18b20.sensors(function (err, id) {
        sensorId = id;
        socket.emit('sensors', id); //send sensor ID's to clients
    });

    //initiate interval timer
    setInterval(function () {
        //loop through each sensor id
        sensorId.forEach(function (id) {

            ds18b20.temperature(id, function (err, value) {
		var d = new Date();
		var horas = d.getTime();

                //send temperature reading and unix time out to connected clients
                socket.emit('temps', {'id': id, 'value': value, 'horas':horas});
		//log no arquivo se o botao esta ligado
		if(bt1status == "on"){
			console.log(value+','+horas);
			fs.appendFileSync('./lognode.csv',value.toFixed(4)+','+horas+'\n');
		}
            });
        });

    }, interval);

	socket.on('pressbt1',function(data){
		//console.log(data.bt1);
		bt1status = data.bt1;
	});
});
