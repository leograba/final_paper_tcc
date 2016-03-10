<!DOCTYPE HTML>
<html>
	<head>
		<meta charset="UTF-8">
		<title>Controle</title>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
		<link rel="stylesheet" type="text/css" href="./css/config.css">
		<link rel="icon" type="image/png" href="./img/beer2.png">
		<script type="text/javascript" src="./lib/header.js"></script>
		<script>
			$(function(){//when document is fully loaded
				gambiarraHeaderPHP("./lib/header.php");//add the header
				refreshSystemStatus();//get the status fo the GPIO, PWM, etc
				
				$(".btn").click(function valveToggle(){//whenever button is clicked
					//Post the button ID and VALUE
					$.post("/controle", {command:"pinSwitch", btn:$(this).attr("id"), val:$(this).is(":checked")}, function(data, status){
						if(status == "success"){//if server responds ok
							console.log("Server - ID: " + data.btn + "; VALUE: " + data.val);
						}
					},"json");
				});
				
				//Display the slider value dinamically
				$(".slider").on("input",function(){//whenever user is changing value
					$(".slider-value").html($(this).val()+"°");//change the display value
				});
				$(".slider").on("change",function(){
					$.post("/controle", {command:"pinSwitch", btn:$(this).attr("id"), val:$(this).val()}, function(data, status){
						if(status == "success"){//if server responds ok
							console.log("Server - ID: " + data.btn + "; VALUE: " + data.val);
						}
					},"json");
				});
			});
			
			function refreshSystemStatus(){
				$.post("/controle", {command:"getStatus"}, function(data, status){//ask for the server status
					var degreeAngle;//var to temporarily store the servo_motor angle in degree
					if(status == "success"){//if server responds ok
						console.log(data);
						for(var obj in data){//iterate though all object keys
							if(data[obj] == 1 && obj != "servo_pwm"){//if it is set and isn't the pwm
								$("#" + obj).prop("checked", true);//check the corresponding checkbox
							}
							else if(obj == "servo_pwm"){//if it is the pwm
								degreeAngle = Math.round((data[obj].duty - 0.0325)*180/0.0775);//calculate the pwm angle from duty to degree
								$("#" + obj).val(degreeAngle);//set the slider position by setting its value
								$(".slider-value").html(degreeAngle + "°");//refresh the indicator value
								$("#pwm_slider").show();//then show the div correctly set
							}
						}
					}
				},"json");
			}
		</script>
	</head>

	<body style="display:none;">
		<h1>Controle do Sistema</h1>
		<form>
    	    <div class="slideThree">	
				<input type="checkbox" class="btn" value="None" id="led" name="check" />
				<label for="led"></label>
				<span>LED PLACA</span>
			</div>
			<div class="slideThree">	
				<input type="checkbox" class="btn" value="None" id="mash_pump" name="check" />
				<label for="mash_pump"></label>
				<span>BOMBA DO MOSTO</span>
			</div>
			<div class="slideThree">	
				<input type="checkbox" class="btn" value="None" id="boil_pump" name="check" />
				<label for="boil_pump"></label>
				<span>BOMBA DE FERVURA</span>
			</div>
			<div class="slideThree">	
				<input type="checkbox" class="btn" value="None" id="mash_valve" name="check" />
				<label for="mash_valve"></label>
				<span>VÁLVULA DO MOSTO</span>
			</div>
			<div class="slideThree">	
				<input type="checkbox" class="btn" value="None" id="boil_valve" name="check" />
				<label for="boil_valve"></label>
				<span>VÁLVULA DA FERVURA</span>
			</div><div class="slideThree">	
				<input type="checkbox" class="btn" value="None" id="chill_valve" name="check" />
				<label for="chill_valve"></label>
				<span>VÁLVULA DO CHILLER</span>
			</div>
			<div class="slideThree">	
				<input type="checkbox" class="btn" value="None" id="water_valve" name="check" />
				<label for="water_valve"></label>
				<span>VÁLVULA DA ÁGUA</span>
			</div>
			<div class="slideThree">	
				<input type="checkbox" class="btn" value="None" id="mash_heat" name="check" />
				<label for="mash_heat"></label>
				<span>AQUECEDOR DO MOSTO</span>
			</div>
			<div class="slideThree">	
				<input type="checkbox" class="btn" value="None" id="boil_heat" name="check" />
				<label for="boil_heat"></label>
				<span>AQUECEDOR DA FERVURA</span>
			</div><br>
			<div id="pwm_slider" style="display:none;">
	    	    <input type="range" class="slider" id="servo_pwm" name="servo_range" min="0" max="180" value="0" step="1"/>
	    	    <span class="slider-value"></span>
	    	    <label class="pwm" for="servo_pwm">SERVO-MOTOR</label>
    		</div>
    	</form><br>
	</body>
</html>
