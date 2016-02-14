<!DOCTYPE HTML>
<html>
	<head>
		<meta charset="UTF-8">
		<title>Receitas</title>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
		<link rel="stylesheet" type="text/css" href="../config.css">
		<link rel="icon" type="image/png" href="../figuras/beer2.png">
		<?php
			error_reporting(-1); ini_set("display_errors", "On");//debug on
			
			function listFiles(){
				$receitas = scandir("./recipes/");//read all recipes
				$fileId=0;//help variables
				foreach ($receitas as $valor){//iterates over array, with auto-index
					if(strpos($valor,".del") == false){//if file is not a backup
						if(strpos($valor,".recipe") !== false){//if current file extension is compatible
							//$filePath="./recipes/".$valor;//caminho ate a receita
							//edit the name to human readable:
							$valor = str_replace(".recipe","",$valor);//exclude extension
		            		$nome = str_replace("_"," ",$valor);//all underlines -> spaces
							echo "<div id='recipe$fileId'><a class='fileList big' href=novareceita.php?name=$valor>$nome<br></a> ";//echo the links to the recipes inside identifiable DIV
							//echo "<input id='_$valor' class='del' type='button' value='X' onClick='deleteRecipe(this);'></div>";//create identifiable delete button
							echo "<input id='$valor' class='del' type='button' value='X'></div>";//create identifiable delete button with recipe name as ID
							$fileId++;//increment to set id number of next recipe
						}
					}
				}
			}
			
		?>
		<script type="text/javascript" src="listrecipe.js"></script>
		<script type="text/javascript" src="../header.js"></script>
		<script type="text/javascript">
		// POST request using AJAX
		$(function(){ //wait for the page to load
			//WANT TO DELETE OR UNDELETE RECIPE
			$(".del").click(function(){//if any del/undo button is clicked
				deleteRecipe($(this));//delete or recover recipe
			});
			//WANT TO CREATE NEW RECIPE
			$("#new_add").click(function addRecipeName(){//if want to create recipe
				$(this).hide();//hide the button to add new recipe
				$(this).siblings().show(200).css("display","inline-block");//show the text input and add button
			});
			$("#new_done").click(function addRecipe(){//if name was put and want to create recipe
				if($("#new_name").val().trim()){//if name is not empty
					window.location.href = "novareceita.php?name="+$("#new_name").val();
				}
				else{//if name is empty or whitespaces
					
				}
			});
			//RECIPE PREVIEW
			$(".fileList").mouseover(function(){//if needs to load the recipe
				recipePreview($(this));//pass its name (through the current element)
			}); 
			$(".fileList").mouseout(function(){//when mouse leaves recipe name
				$("#preview").hide();
			});
			
			gambiarraHeaderPHP("../header.php");//add header
		});
		</script>
	</head>

	<body style="display:none;">
		<h1>Gerenciador de Receitas</h1>
		<div>
			<input id="new_add" class="add" type="button" value="Criar nova receita"/>
			<input id="new_name" class="long" type="text" maxlength="50" placeholder="Escreva o nome da sua receita" style="display:none;"/>
			<input id="new_done" class="enter" type="button" value="+" style="display:none;"/>
		</div>
		<div id=status_message style="display:none;">
			<p style="width:30%;display:inline-block;">Teste</p>
		</div>
		<section>
			<div id="recipeList" style="display:inline-block; width:50%; float:left;">
				<p>Receitas Cadastradas:<br></p>
				<?php listFiles();?>
				<br><br><br><br>
			</div
			><div id="preview" style="display:none; width:50%; float:left; background:#595450; border-radius:10px;">
				<p class="prevhead">Nome da Receita:</p><p class="prev" id="nome_da_receita"></p><br>
				<p class="prevhead">Estilo:</p><p class="prev" id="estilo"></p><br>
				<p class="prevhead">Levedura:</p><p class="prev" id="levedura"></p><br>
				<p class="prevhead">Água de mosturação (ℓ):</p><p class="prev" id="mosto"></p><br>
				<p class="prevhead">Água de lavagem (ℓ):</p><p class="prev" id="lavagem"></p><br>
				<p class="prevhead">Tempo de fervura (min):</p><p class="prev" id="fervura"></p><br>
				<p class="prevhead">Maltes:</p><p class="prev" id="maltes"></p><br>
				<p class="prevhead">Lúpulos</p><p class="prev" id="lupulos"></p>
			</div>
		</section>
	</body>
	
</html>
