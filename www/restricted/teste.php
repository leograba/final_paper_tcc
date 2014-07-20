<html>
<head>
<meta charset="utf-8"/>
	<meta http-equiv="cache-control" content="max-age=0" />
        <meta http-equiv="cache-control" content="no-cache" />
        <meta http-equiv="expires" content="0" />
        <meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT" />
        <meta http-equiv="pragma" content="no-cache" />
<title>Temperatura</title>
<link rel="stylesheet" type="text/css" href="../config.css">
<link rel="stylesheet" type="text/css" href="../buttons.css">
<link rel="icon" type="image/png" href="../figuras/beer2.png">
</head>

<body>
<input type="button" value="voltar" onClick="history.go(-1);return false;" />
<div id="mytemp">
	<h2 style="display:inline-block;">Gr&aacutefico Din&acircmico / Temperatura Agora: </h2>
	<h2 id="tnow" style="display:inline-block;"></h3>
</div>
<iframe id="realtime_graph" src="../dyn_graph2.html" width="100%" height="100%" frameborder="0"></iframe>
<h2>Gr&aacutefico do hist&oacuterico de temperatura</h2>
<img id="static_graph" src="/tplot.png" alt="ErrorImg" width="1300" height="577" usemap="#plot" title="graph-temp.png">
<map name="plot">
	<area shape="rect" coords="0,0,1300,577" href="/tplot.png" alt="?tplot.png">
</map>
<br>
<a href="#" onClick="history.go(-1);return false;">Voltar</a>
</body>

<script>
	//acesso ao iframe
	//var myFrame = document.getElementById("realtime_graph");
	//var insideMyFrame = myFrame.contentDocument || myFrame.contentWindow.document;
	var temperatura = document.getElementById("tnow");
	var grafico = document.getElementById("static_graph");

	setInterval(function(d){//atualiza temperatura a cada 1s
		myFrame = document.getElementById("realtime_graph");
		insideMyFrame = myFrame.contentDocument || myFrame.contentWindow.document;
		d = insideMyFrame.getElementById("temp_now").innerHTML;
		console.log("teste.php - lendo iframe:"+d);
		console.log(insideMyFrame.getElementById("temp_now").innerHTML);
		temperatura.innerHTML = d;
	},1000);//repete a cada 1000ms

	setInterval(function(){//atualiza grafico estatico a cada 2,5min
		grafico.src = "/tplot.png?" + new Date().getTime();
	},150000);//repete a cada 2,5min

</script>
</html>

