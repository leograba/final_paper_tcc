<!DOCTYPE HTML>
<html>
<head>
	<meta charset="UTF-8">
	<title>Configurações</title>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	<link rel="stylesheet" type="text/css" href="./config.css">
	<link rel="stylesheet" type="text/css" href="./buttons.css">
	<link rel="icon" type="image/png" href="./figuras/beer2.png">
	<script type="text/javascript" src="../header.js"></script>
	<script>
	    $(function(){
	        setInterval(function refreshDate(){//get server-side date/time
	            $.post("/config", {request:"datetime",}, function(data, status){
	                if(status == "success"){//if server responds ok
	                    $("#datahora").html("data/hora: " + data.datetime);
	                }
	            },"json");
	        },1000);
	        
	        $(".okbtn").click(function requestSetDate(){
	            var newdate = $("#get_time_date").val();
	            if(newdate){
	                console.log($("#get_time_date").val());
	            }
	            else{
	                console.log("empty value");
	            }
	        });
	        
	        gambiarraHeaderPHP("../header.php");//add header
	    }); 
    </script>
	</head>

<body style="display:none;">
    <h1>Configurações e ajustes</h1>
    <form class="myform" id="form" name="form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <h3 id="datahora"></h3>
        <div>
            <label class="myform" for="get_time_date">Ajuste:</label>
            <input class="myform long" type="datetime-local" id="get_time_date" name="bdaytime">
            <input class="okbtn" type="button" value="ok">
        </div>
    </form>
</body>
</html>