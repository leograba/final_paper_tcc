<!DOCTYPE HTML>
<html>
	<head>
	    <meta charset="UTF-8">
		<title>Controle</title>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
		<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
		<link rel="stylesheet" type="text/css" href="./config.css">
		<link rel="stylesheet" type="text/css" href="./buttons.css">
		<link rel="icon" type="image/png" href="./figuras/beer2.png">
	    <script type="text/javascript" src="./header.js"></script>
		<script>
            $(function(){//when document is fully loaded
                $.post("/startrecipe", {command:"getRecipes"}, function(data, status){//ask the server for the recipe names
    				if(status == "success"){//if server responds ok
    					if(data.resp == "success"){//if the recipes are successfully recieved
    					    for (var i = 0; i < data.recipes.length; i++){//create one option for each recipe in the dropdown list
    					        $("#recipeSel").append("<option value='" + data.recipes[i] + "'>" + data.recipes[i] + "</option>"); 
    					    }
    					    $("#recipeSel").append("<option value='opt5'>Bla</option>");
    					}
    					else if(data.resp == "error"){
    					    
    					}
    					console.log(data);
    				}
    			},"json");
    			
                gambiarraHeaderPHP("./header.php");
            });
		</script>
	</head>

	<body style="display:none;">
        <h1>Iniciar Brassagem</h1>
        <div class="warning">
            <div ><i class="material-icons custom">warning</i></div>
            <p class="warning">Antes de iniciar uma receita, certifique-se de que o equipamento está
                devidamente limpo e sanitizado, e de que a água e ingredientes estão a postos para a produção.
            </p>
        </div>
        <select id="recipeSel"></select>
        
        <h2>Controle do sistema</h2>
        <p>Em breve a tela de controle será ajustada para acompanhamento e ajustes da brassagem:</p>
        <input class="leftselect" type="button" value="controle" onClick="window.location='http://143.107.235.59:8587/controle.php'" />
        
	</body>
	
</html>
