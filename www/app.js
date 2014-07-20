//Include necessary node packages
    var http = require('http');
    var io = require('./node_modules/socket.io').listen(8587),
//var io = require('/mnt/sd/apache/node_modules/socket.io').listen(3000),
//ds18b20 = require('/usr/local/lib/node_modules/ds18b20');
    var ds18b20 = require('./node_modules/ds18b20');
    var fs = require('fs');//filesystem para trabalhar com arquivos

var server = http.createServer(function(req, res) {
  res.writeHead(200);
  res.end('Hello, world!');
});
    server.listen(8587, '143.107.235.59');

var interval = 1000; //enter the time between sensor queries here (in milliseconds)
var bt1status = "off";//status do botao 1

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
