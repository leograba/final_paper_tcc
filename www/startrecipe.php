<!DOCTYPE HTML>
<html>
	<head>
	    <meta charset="UTF-8">
		<title>Controle</title>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
		<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
		<link rel="stylesheet" type="text/css" href="./css/config.css">
		<link rel="stylesheet" type="text/css" href="./css/buttons.css">
		<link rel="icon" type="image/png" href="./figuras/beer2.png">
	    <script type="text/javascript" src="./lib/header.js"></script>
	    <script type="text/javascript" src="./lib/listrecipe.js"></script>
		<script>
            $(function(){//when document is fully loaded
                gambiarraHeaderPHP("./lib/header.php");
            
                $.post("/startrecipe", {command:"getRecipes"}, function(data, status){//ask the server for the recipe names
    				if(status == "success"){//if server responds ok
    					if(data.resp == "success"){//if the recipes are successfully recieved
    					    for (var i = 0; i < data.recipes.length; i++){//create one option for each recipe in the dropdown list
    					        $("#recipeSel").append("<option value='" + data.recipes[i] + "'>" + data.recipes[i] + "</option>"); 
    					    }
    					    //$("#recipeSel").append("<option value='opt5'>Bla</option>");
    					}
    					else if(data.resp == "error"){
    					    
    					}
    					console.log(data);
    				}
    			},"json");
    			
    			$("input[type='button'][value='iniciar']").click(function(){//loads the recipe preview
    				$("#recipeSel").attr("href","?=Exemplo_Pilsen");
    				console.log($("#recipeSel").attr("href"));
    			    recipePreview($("#recipeSel"));//pass its name (through the current element)
    			});
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
        <select id="recipeSel"></select><br><br>
        <input class="leftselect" type="button" value="iniciar"/>
        
        <div id="preview" style="display:none; width:50%; float:left; background:#595450; border-radius:10px;">
			<p class="prevhead">Nome da Receita:</p><p class="prev" id="nome_da_receita"></p><br>
			<p class="prevhead">Estilo:</p><p class="prev" id="estilo"></p><br>
			<p class="prevhead">Levedura:</p><p class="prev" id="levedura"></p><br>
			<p class="prevhead">Água de mosturação (ℓ):</p><p class="prev" id="mosto"></p><br>
			<p class="prevhead">Água de lavagem (ℓ):</p><p class="prev" id="lavagem"></p><br>
			<p class="prevhead">Tempo de fervura (min):</p><p class="prev" id="fervura"></p><br>
			<p class="prevhead">Maltes:</p><p class="prev" id="maltes"></p><br>
			<p class="prevhead">Lúpulos</p><p class="prev" id="lupulos"></p>
		</div>
        
        <h2>Controle do sistema</h2>
        <p>Em breve a tela de controle será ajustada para acompanhamento e ajustes da brassagem:</p>
        <input class="leftselect" type="button" value="controle" onClick="window.location='./control.php'" />
        
	</body>
	
</html>
