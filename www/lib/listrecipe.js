function deleteRecipe(element){//if any del/undo button is clicked
	//var recipeId = $(this).parent().attr("id");//save the parent ID so it can be used in callback if necessary
	var btnId = $(element).attr("id");//save the button ID too
	var whatToDo = "undo";//variable points which action should take, by default undo
	if($(element).attr("value") == "X"){//if intends to delete the recipe
		whatToDo = "delete";//points it should be deleted
	}
	//http POST request, to file deleterecipe.php, sends the recipe to be deleted info, and callback on success
	$.post("./lib/deleterecipe.php", {recipe:btnId, stat:whatToDo}, function(data, status){
		if(data == 0 && whatToDo == "delete"){//check if recipe was successfully deleted
			$("#" + btnId).attr("value","desfazer");//the user have the option to undelete the recipe
		}
		else if(data == 0 && whatToDo == "undo"){//check if recipe was successfully deleted
			$("#" + btnId).attr("value","X");//the user have the option to undelete the recipe
		}
		else{//tell the user there was some error
			$("#status_message").children("p").text("Receita não foi deletada. Erro " + data);//place status message in HTML hidden paragraph
			$("#status_message").show(500);//shows the paragraph with the status message with fade-in effect
		}
	});
}

function recipePreview(element){//when user put mouse into recipe name
	var fileName = $(element).attr("href").split("?")[1].split("=")[1];//get recipe name
	$("#preview").show().css("display","inline-block");
	$.post("./lib/previewrecipe.php", {name:fileName}, function(data, status){//request recipe contents to server
		if(status == "success"){
			$("#preview").children().each(function setNameInHTML(){
				var idOfElement = $(this).attr("id");
				$(this).text(data[idOfElement]);//put content inside corresponding HTML element
				if(idOfElement == "maltes"){//put all malts in the same div
					$(this).text("");//empty the text inside MALTES
					if(data["malte" + 1]){//if property is not empty
						$(this).append(data["malte" + 1].replace(" ", "&nbsp;"));//append content to MALTES
					}
					for (var i = 2; i < 8; i++){//iterate though all malts
						if(data["malte" + i]){//if property is not empty
							$(this).append(",&nbsp;" + data["malte" + i].replace(" ", "&nbsp;"));//append content to MALTES
						}
					}
				}
				if(idOfElement == "lupulos"){//put all malts in the same div
					$(this).text("");//empty the text inside LUPULOS
					if(data["lupulo" + 1]){//if property is not empty
						$(this).append(data["lupulo" + 1].replace(" ", "&nbsp;"));//append content to LUPULOS
					}
					for (i = 2; i < 8; i++){//iterate though all malts
						if(data["lupulo" + i]){//if property is not empty
							$(this).append(",&nbsp;" + data["lupulo" + i].replace(" ", "&nbsp;"));//append content to LUPULOS
						}
					}
				}
			});
		}
		else{
			console.log("oops failed!");
		}
	},"json");//server return data as JSON encoded
}