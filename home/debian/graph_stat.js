var fs = require('fs');
var sorted = [];

fs.readFile('./log.log', 'UTF-8', function(err, data){
    var line = {timestamp:'', cpu:'', ram:'', net_input:'', net_output:''};
    //var sorted = [];
    var parsed = data.split('\n');
    parsed.splice(0,2);
    //console.log(parsed[0]);
    for(var i = 0; i < parsed.length; i+=9){
        line.timestamp = parsed[i].substring(0,8);
//        console.log('cpu');
//        console.log(parsed[i]);
//        console.log(+(100.00-(+parsed[i+1].substring(103))).toFixed(2));
        line.cpu = +(100.00-(+parsed[i+1].substring(103))).toFixed(2);
//        console.log('ram');
//        console.log(parsed[i+3]);
//        console.log(+parsed[i+4].substring(35,43));
        line.ram = +parsed[i+4].substring(35,43);
//        console.log('net');
//        console.log(parsed[i+6]);
//        console.log(parsed[i+7]);
//        console.log(+parsed[i+7].substring(13,21));
//        console.log(+parsed[i+7].substring(43,51));
        line.net_input = +parsed[i+7].substring(13,21);
        line.net_output = +parsed[i+7].substring(43,51);
        //sorted.push(line);
        //console.log(line);
        //console.log(sorted[i]);
        (function(linha, nlinha){            
            sorted[nlinha] = JSON.stringify(linha);
//            console.log(linha);
//            console.log(sorted[nlinha]);
//            fs.writeFile('./json_log.json', JSON.stringify(linha)+'\n', function(err){
  //              if (err) throw err;
    //        });
        })(line,i/9)
    }
//    setTimeout(function(){
    console.log(sorted);
    fs.writeFile('./json_log.json', sorted, function(err){
        if (err) throw err;
    });
//    },10000);
    console.log(sorted.length);
    
});
