<!DOCTYPE HTML>
<html>
	<head>
		<title>Controle</title>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
		<link rel="stylesheet" type="text/css" href="./config.css">
		<link rel="icon" type="image/png" href="./figuras/beer2.png">
		<script>
		
			$(function(){//when document is fully loaded
				$(".btn").click(function(){
					$(this).changeBtnStatus();
				});
			});
			
			(function ( $ ) {
				$.fn.changeBtnStatus = function(bla){
					var btstatus = {id:"",status:""};
					if(this[0].checked){//if button is turned on. I didn't really understand the index, but it works
						$(this).siblings().html("Ligado").css("color","green");//changes its label to Ligado
						btstatus.id = $(this).attr("id");
						btstatus.status = "on";
					}
					else{	
						$(this).siblings().html("Desligado").css("color","red");//changes its label to Desligado
						btstatus.id = $(this).attr("id");
						btstatus.status = "off";
					}
					console.log(btstatus);//data to send to server-side application
					//send info to server through AJAX
					/*$.post("../deleterecipe.php", btstatus, function(data, status){
						$("#status_message").text(data);//place status message in HTML hidden paragraph
						$("#status_message").show();//shows the paragraph with the status message
					});*/
					return this;
				};
			}( jQuery ));
			

		</script>
	</head>

	<body>
		<h1>Controle do Sistema</h1>
		<form>
    	    <input type="checkbox" class="btn" id="btn1" style="margin-left:30px;">
    	    <label for="btn1" id="btnl-0" style="color:red;">Desligado</label>
    	</form>
		<a href="./menu_receitas.html">Voltar</a>
	</body>
	
</html>
