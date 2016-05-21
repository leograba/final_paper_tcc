function fieldsToRearrange(lastActive){
  if(lastActive.indexOf("mlt") > 0){//if it was a malt field that changed
    return "#mlt";//return malt id as string
  }
  else if(lastActive.indexOf("lup") > 0){//if it was a hop field that changed
    return "#lup";//return hop id as string
  }
  else{//if it was any other field that changed
    return "#tpo";//return temperature id string
  }
}

function rearrange(catg, line){//rearrange from LINE passed to the last line
  //the first line should be passed as value zero!
  var nextLineEmpty = 0;//zero if all next fields are empty
  var firstLineEmpty =0;//zero if first line is empty, 2 if fully filled
  if(typeof line === "undefined") {line = 0;}//optional parameter, defaults to zero
  if(typeof line === "undefined") {return;}//mandatory parameter, should be passed
  console.log("catg: " + catg + "; line: " + line);
  line++;//the first line isn't zero indexed
  console.log("starting line: " + line);
  /*if(line == -1){//if last call was the last needed call
    console.log("nothing more to do - the end " + line);
    return -1;//return success, nothing more to do
  }*/
  for(var i = line; i < 8; i++){//do it for all the lines, except the last
    $(catg + (i)).children("input").each(function checkIfEmpty(){
      if($(this).val()){//if input isn't empty
        if(i == line){//if it is the first line
          firstLineEmpty++;//tells it isn't empty
        }
        else{//if it is one of the next lines
          if(!nextLineEmpty){//if it is still empty
            nextLineEmpty = i;//variable non-zero tells next non-empty line
          }
        }
      }
    });
  }
  console.log("First line - " + firstLineEmpty);
  console.log("Next line - " + nextLineEmpty);
  if(!nextLineEmpty){//if all next lines are empty
    if(!firstLineEmpty){//if a line that wasn't the last filled line is deleted
      line--;//correct the index, so it points to the last filled line
      console.log("égua doido1: " + $(catg + (line+1)).children().length/2);
    }
    else if(firstLineEmpty != $(catg + (line+1)).children().length/2){//if last filled line is incomplete
      console.log("égua doido2: " + $(catg + (line+1)).children().length/2);
      $(catg + (line+1)).hide();//do not show the next empty line
    }
    else{//but if last filled line is fully filled
      console.log("égua doido3: " + $(catg + (line+1)).children().length/2);
      $(catg + (line+1)).show();//show the next line to the user
    }
    for(i = 1; i <= line; i++){//show the non-empty lines
      $(catg + i).show();
    }
    for(i = (line+2); i <= 8; i++){//hide the other empty lines
      $(catg + i).hide();
    }
    console.log("nothing more to do - " + line);
    return -1;//success, do nothing more
  }
  else{//if there are next line(s) not empty 
    if(!firstLineEmpty){//and first line is empty
      //copy next line value and erase from next line
      $("#nmlt" + line).val($("#nmlt" + (nextLineEmpty)).val());
      $("#nmlt" + (nextLineEmpty)).val("");
      $("#qmlt" + line).val($("#qmlt" + (nextLineEmpty)).val());
      $("#qmlt" + (nextLineEmpty)).val("");
      console.log("Current line empty rearranged, try next line");
      rearrange(catg, line);//do it again for the next line
    }
    else{//if first line have contents already
      console.log("Current line not empty, try next line");
      rearrange(catg, line);//try rearranging next line
    }
  }
}

function rearrangeAll(){
  rearrange("#mlt");
  rearrange("#lup");
  rearrange("#tpo");
}

function getNameFromURL(){
  var sPageURL = window.location.search.substring(1);//get URL
  return sPageURL.split('=')[1];
}

function saveOnDemand(element){
  console.log($(element));
  //var dataInput = $(this).serialize();
  var dataRaw = $(element).serializeArray();
  var dataInput = {};
  $.each(dataRaw, function(index, value){//transform to object with pair key:value
    //console.log("nome: " + value.name + " valor: " + value.value);
    dataInput[value.name] = value.value;
  });
  $("#statusMsg").text("salvando receita...");//tell the user that the recipe is being saved
  $.post("./lib/newrecipe.php", dataInput, function(data, status){//send form data to php
    console.log(data);
    if(status == "success"){//if could contact server
      if(data.status == 0){//if successfully saved
        $("#statusMsg").text(data.msg);
      }
      else{//otherwise
        $("#statusMsg").text(data.msg);
      }
    }
    else{
      $("#statusMsg").text("erro ao contatar servidor");
    }
  },"json");
}