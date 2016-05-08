<!DOCTYPE HTML>
<html>
	<head>
	    <meta charset="UTF-8">
		<title>Controle</title>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
		<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
		<link rel="stylesheet" type="text/css" href="./css/config.css">
		<link rel="stylesheet" type="text/css" href="./css/buttons.css">
		<link rel="icon" type="image/png" href="./img/beer2.png">
	    <script type="text/javascript" src="./lib/header.js"></script>
	    <script type="text/javascript" src="./lib/listrecipe.js"></script>
		<script>
			checkRecipeInProgress(recipeInProgress);//it may be done before page is loaded, no worries
            $(function main(){//when document is fully loaded
                headerPHP("./lib/header.php");
            	getAvailableRecipes("#recipeSel");
    			
    			$("input[type='button'][value='iniciar']").click(startRecipeRequest);
            });
            
            function checkRecipeInProgress(callback){
            	$.post("/startrecipe", {command:"inProgress"}, recipeInProgress,"json");
            }
            
            function recipeInProgress(data, status){
            	if(status == "success"){//if server responds ok
					if(data.resp == "true"){//if the recipes are successfully recieved
						window.location.replace("http://beaglebrewing.servebeer.com:8587/control.php");
					}
				}
            	
            }
            
            function startRecipeRequest(){
            	var recipeName = $("#recipeSel").val().replace(/ /g, "_");
				$("#previewData").attr("href","?=" + recipeName);//replace spaces with underlines
			    recipePreview($("#previewData"));//pass its name (through the current element)
			    setTimeout(function(){
				    $.post("/startrecipe", {command:"startRequest", recipe:recipeName}, function(data, status){//ask the server for the recipe names
						if(status == "success"){//if server responds ok
							if(data.resp == "success"){//if the recipes are successfully recieved
								console.log(data);
								if(errorWarningHandler(data, "#errors", "#warnings", "#messages")){//if recipe can be started
									startRecipe(recipeName);
									//window.location.replace("http://beaglebrewing.servebeer.com:8587/control.php?start=true");
								}
							}
						}
					},"json");
			    },1000);
            }
            
            function startRecipe(recipeName){
            	console.log("Recipe name from startRecipe: " + recipeName);
            	$.post("/startrecipe", {command:"startRecipe", recipe:recipeName}, function(data, status){
            		if(status == "success"){//if server responds ok
            			console.log(data);
            			if(data.resp == "success"){//if the recipes are successfully recieved
            				console.log("starting the requested recipe...");
            				window.location.replace("http://beaglebrewing.servebeer.com:8587/control.php");
            			}
            		}
            	},"json");
            }
            
            function getAvailableRecipes(recipeSelDivId){
            	$.post("/startrecipe", {command:"getRecipes"}, function(data, status){//ask the server for the recipe names
    				if(status == "success"){//if server responds ok
    					if(data.resp == "success"){//if the recipes are successfully recieved
    					    for (var i = 0; i < data.recipes.length; i++){//create one option for each recipe in the dropdown list
    					    	//console.log(data.recipes[i]);
    					        $(recipeSelDivId).append("<option value='" + data.recipes[i] + "'>" + data.recipes[i] + "</option>"); 
    					    }
    					}
    					else if(data.resp == "error"){
    					    
    					}
    					//console.log(data);
    				}
    			},"json");
            }
            
            function errorWarningHandler(data, errDivId, warnDivId, msgDivId){
            	if(data.err && data.warn){//if there are errors and warnings to give
            		$(errDivId).text("Esenciais: " + data.err).show();
            		$(warnDivId).text("Facultativos: " + data.warn).show();
            		$(msgDivId).show();
            		return 0;//don't start the recipe
            	}
            	else if(data.err){//if there are only erros
            		$(errDivId).text("Esenciais: " + data.err).show();
            		$(msgDivId).show();
            		return 0;//don't start the recipe
            	}
				else if(data.warn){//if there are only warnings
					$(warnDivId).text("Facultativos: " + data.warn).show();
					$(msgDivId).text("Alguns itens da receita não foram preenchidos."
							+"Deseja continuar mesmo assim?").show();
					if (confirm("Alguns itens da receita não foram preenchidos."
						+" Deseja realmente iniciar?") == true) {
						return 1;//start the recipe
				    }
				    else{
				        return 0;//don't start the recipe
					}
				}
				else{//if everything is right, start the recipe
					return 1;//recipe ready to start
				}
            }
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
        
        <div id="preStart">
	        <p id="messages" style="display:none;">Alguns itens da receita não foram preenchidos. Preencha os
	        	obrigatórios antes de continuar.</p>
	        <p id="warnings" style="display:none;"></p>
	        <p id="errors" style="display:none;"></p>
	        
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
		</div>
		
        <a id="previewData" style="display:none;"></a>
        
        <h2>Controle do sistema</h2>
        <p>Ao iniciar uma receita você será automaticamente redirecionado para o controle.</p>
        <p>É possível acessar os controles a qualquer momento:</p>
        <input class="leftselect" type="button" value="controle" onClick="window.location='./control.php'" />
        
	</body>
	
</html>
