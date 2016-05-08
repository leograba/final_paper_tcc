<!DOCTYPE HTML>
<html>
<head>
	<meta charset="UTF-8">
	<title>Configurações</title>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	<link rel="stylesheet" type="text/css" href="./css/config.css">
	<link rel="stylesheet" type="text/css" href="./css/buttons.css">
	<link rel="icon" type="image/png" href="./img/beer2.png">
	<script type="text/javascript" src="./lib/header.js"></script>
	<script>
	    $(function(){
	    	headerPHP("./lib/header.php");//first thing is to add the header
	    	
	        setInterval(refreshDate,1000);//get the server date/time every 1s
	        
	        $(".okbtn").click(requestSetDate);//update the server date/time on click
	        
	        function refreshDate(){//get server-side date/time
	            $.post("/config", {request:"datetime",}, function(data, status){
	                if(status == "success"){//if server responds ok
	                    $("#datahora").html("data/hora: " + data.datetime);
	                }
	            },"json");
	        }
	        
	        function requestSetDate(){
	            var newdateval = $("#get_time_date").val();//get user input for new time/date
	            console.log(newdateval);
	            if(newdateval){//if the user set correctly the input
	                $.post("/config", {request:"setdatetime", newdate:newdateval}, function(data, status){
	                	if(status == "success" && data.datetime == "ok"){//if server responds ok
	                		console.log($("#get_time_date").val());
	                	}
	                },"json");
	            }
	            else{//if some input field is missing
	                $("#get_label").fadeOut(10,function(){//tell the user to fill all the fields
	                	$(this).html("Preencha data e hora completa: ").fadeIn(100);
	                });
	            }
	        }
	    }); 
    </script>
	</head>

<body style="display:none;">
    <h1>Configurações e ajustes</h1>
    <form class="myform" id="form" name="form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <h3 id="datahora"></h3>
        <div>
            <label class="myform" id="get_label" for="get_time_date">Ajuste:</label>
            <input class="myform long" type="datetime-local" id="get_time_date" name="bdaytime">
            <input class="okbtn" type="button" value="ok">
        </div>
    </form>
</body>
</html>