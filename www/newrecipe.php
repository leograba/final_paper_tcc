<!DOCTYPE HTML>
<html>
<head>
  <meta charset="UTF-8">
  <title>Nova Receita</title>
  <link rel="stylesheet" type="text/css" href="./css/config.css">
  <link rel="icon" type="image/png" href="./img/beer2.png">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <script type="text/javascript" src="./lib/newrecipe.js"></script>
  <script type="text/javascript" src="./lib/header.js"></script>
  <script>
    $(function(){//wait for the page to load
      headerPHP("./lib/header.php");//add the header
    
      rearrangeAll();//show all filled fields
      $("#name").val(getNameFromURL().replace(/_/g, " "));//set the recipe name got from GET
      $(".myform").focusout(function(){//everytime the form focus change
        rearrange(fieldsToRearrange(event.target.id));//rearrange the part of the form that was changed
        saveOnDemand($(this));//save the form
      });
      
    });
  </script>
</head>

<body style="display:none">
<h1>Crie ou edite sua receita!</h1>
<p id="statusMsg" style="text-align:right;">receita salva</p>


<?php
  error_reporting(-1);//set debug options on
  ini_set("display_errors", "On");//set debug options on
  require './lib/newrecipe.php';//file that does the magic, i.e. form handling, file saving, etc
  $all_variables = loadFormData();//load recipe if there is a GET name
?>

<form class="myform" id="form" name="form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
  <div>
    <label class="myform long" for="name">Nome da Receita:</label>
    <input class="myform long" type="text" id="name" maxlength="50" name="nome_da_receita" readonly>
  </div>
  <div>
    <label class="myform long" for="beerStyle">Estilo da Cerveja:</label>
    <input class="myform long" type="text" id="beerStyle" maxlength="50" name="estilo" value="<?php echo $all_variables["estilo"];?>"><span class="error">* <?php echo $estiloErr;?></span><br>
  </div>
  <div>
    <label class="myform long" for="yeast">Levedura:</label>
    <input class="myform long" type="text" id="yeast" maxlength="50" name="levedura" value="<?php echo $all_variables["levedura"];?>"><span class="error">* <?php echo $leveduraErr;?></span><br>
  </div>
  <div>
    <label class="myform long" for="mash_water">Água - mosturação(ℓ):</label>
    <input class="myform long" type="number" id="mash_water" min="0" max="50" step="0.5" name="mosto" value="<?php echo $all_variables["mosto"];?>"><span class="error">* <?php echo $mostoErr;?></span><br>
  </div>
  <div>
    <label class="myform long" for="sparge_water">Água - <i>sparging</i> (ℓ):</label>
    <input class="myform long" type="number" id="sparge_water" min="0" max="50" step="0.5" name="lavagem" value="<?php echo $all_variables["lavagem"];?>">
    <label class="myform short" for="sparge_temp">Temperatura:</label> 
    <input class="myform short" type="number" id="sparge_temp" min="0" max="99" step="0.5" name="tlavagem" value="<?php echo $all_variables["tlavagem"];?>"><span class="error">* <?php echo $lavagemErr;?></span><br>
  </div>
  <div>
    <label class="myform long" for="boil_time">Fervura do mosto(min):</label>
    <input class="myform long" type="number" id="boil_time" min="0" max="300" step="1" name="fervura" value="<?php echo $all_variables["fervura"];?>"><span class="error">* <?php echo $fervuraErr;?></span><br>
  </div>

  
  <h4>Maltes</h4>
  <div id="mlt1">
    <label class="myform long" for="nmlt1">Malte 1:</label> 
    <input class="myform long" type="text" id="nmlt1" maxlength="25" name="malte1" value="<?php echo $all_variables["malte1"];?>">
    <label class="myform short" for="qmlt1">Quantidade(kg):</label>
    <input class="myform short" type="number" id="qmlt1" min="0" max="100" step="0.01" name="qtd1" value="<?php echo $all_variables["qtd1"];?>"><span class="error">* <?php echo $malte1Err;?></span>
  </div>
  <div id="mlt2" style="display:none;">
    <label class="myform long" for="nmlt2">Malte 2:</label>
    <input class="myform long" type="text" id="nmlt2" maxlength="25" name="malte2" value="<?php echo $all_variables["malte2"];?>">
    <label class="myform short" for="">Quantidade(kg):</label>
    <input class="myform short" type="number" id="qmlt2" min="0" max="100" step="0.01" name="qtd2" value="<?php echo $all_variables["qtd2"];?>">
  </div>
  <div id="mlt3" style="display:none;">
    <label class="myform long" for="nmlt3">Malte 3:</label>
    <input class="myform long" type="text" id="nmlt3" maxlength="25" name="malte3" value="<?php echo $all_variables["malte3"];?>">
    <label class="myform short" for="qmlt3">Quantidade(kg):</label>
    <input class="myform short" type="number" id="qmlt3" min="0" max="100" step="0.01" name="qtd3" value="<?php echo $all_variables["qtd3"];?>">
  </div>
  <div id="mlt4" style="display:none;">
    <label class="myform long" for="nmlt4">Malte 4:</label>
    <input class="myform long" type="text" id="nmlt4" maxlength="25" name="malte4" value="<?php echo $all_variables["malte4"];?>">
    <label class="myform short" for="qmlt4">Quantidade(kg):</label>
    <input class="myform short" type="number" id="qmlt4" min="0" max="100" step="0.01" name="qtd4" value="<?php echo $all_variables["qtd4"];?>">
  </div>
  <div id="mlt5" style="display:none;">
    <label class="myform long" for="nmlt5">Malte 5:</label>
    <input class="myform long" type="text" id="nmlt5" maxlength="25" name="malte5" value="<?php echo $all_variables["malte5"];?>">
    <label class="myform short" for="qmlt5">Quantidade(kg):</label>
    <input class="myform short" type="number" id="qmlt5" min="0" max="100" step="0.01" name="qtd5" value="<?php echo $all_variables["qtd5"];?>">
  </div>
  <div id="mlt6" style="display:none;">
    <label class="myform long" for="nmlt6">Malte 6:</label>
    <input class="myform long" type="text" id="nmlt6" maxlength="25" name="malte6" value="<?php echo $all_variables["malte6"];?>">
    <label class="myform short" for="qmlt6">Quantidade(kg):</label>
    <input class="myform short" type="number" id="qmlt6" min="0" max="100" step="0.01" name="qtd6" value="<?php echo $all_variables["qtd6"];?>">
  </div>
  <div id="mlt7" style="display:none;">
    <label class="myform long" for="nmlt7">Malte 7:</label>
    <input class="myform long" type="text" id="nmlt7" maxlength="25" name="malte7" value="<?php echo $all_variables["malte7"];?>">
    <label class="myform short" for="qmlt7">Quantidade(kg):</label>
    <input class="myform short" type="number" id="qmlt7" min="0" max="100" step="0.01" name="qtd7" value="<?php echo $all_variables["qtd7"];?>">
  </div>
  <div id="mlt8" style="display:none;">
    <label class="myform long" for="nmlt8">Malte 8:</label>
    <input class="myform long" type="text" id="nmlt8" maxlength="25" name="malte8" value="<?php echo $all_variables["malte8"];?>">
    <label class="myform short" for="qmlt8">Quantidade(kg):</label>
    <input class="myform short" type="number" id="qmlt8" min="0" max="100" step="0.01" name="qtd8" value="<?php echo $all_variables["qtd8"];?>">
  </div>
  
  <h4>Lúpulos</h4>
  <div id="lup1">
    <label class="myform long" for="nlup1">Lupulo 1:</label>
    <input class="myform long" type="text" id="nlup1" maxlength="25" name="lupulo1" value="<?php echo $all_variables["lupulo1"];?>">
    <label class="myform short" for="qlup1">Quantidade(g):</label>
    <input class="myform short" type="number" id="qlup1" min="0" max="10000" step="1" name="lqtd1" value="<?php echo $all_variables["lqtd1"];?>">
    <label class="myform long" for="tlup1">Tempo de adicao(min):</label>
    <input class="myform short" type="number" id="tlup1" min="0" max="300" step="1" name="tlupulo1" value="<?php echo $all_variables["tlupulo1"];?>"><span class="error">* <?php echo $lupulo1Err;?></span>
  </div>
  <div id="lup2" style="display:none;">
    <label class="myform long" for="nlup2">Lupulo 2:</label>
    <input class="myform long" type="text" id="nlup2" maxlength="25" name="lupulo2" value="<?php echo $all_variables["lupulo2"];?>">
    <label class="myform short" for="qlup2">Quantidade(g):</label>
    <input class="myform short" type="number" id="qlup2" min="0" max="10000" step="1" name="lqtd2" value="<?php echo $all_variables["lqtd2"];?>">
    <label class="myform long" for="tlup2">Tempo de adicao(min):</label>
    <input class="myform short" type="number" id="tlup2" min="0" max="300" step="1" name="tlupulo2" value="<?php echo $all_variables["tlupulo2"];?>">
  </div>
  <div id="lup3" style="display:none;">
    <label class="myform long" for="nlup3">Lupulo 3:</label>
    <input class="myform long" type="text" id="nlup3" maxlength="25" name="lupulo3" value="<?php echo $all_variables["lupulo3"];?>">
    <label class="myform short" for="qlup3">Quantidade(g):</label>
    <input class="myform short" type="number" id="qlup3" min="0" max="10000" step="1" name="lqtd3" value="<?php echo $all_variables["lqtd3"];?>">
    <label class="myform long" for="tlup3">Tempo de adicao(min):</label>
    <input class="myform short" type="number" id="tlup3" min="0" max="300" step="1" name="tlupulo3" value="<?php echo $all_variables["tlupulo3"];?>">
  </div>
  <div id="lup4" style="display:none;">
    <label class="myform long" for="nlup4">Lupulo 4:</label>
    <input class="myform long" type="text" id="nlup4" maxlength="25" name="lupulo4" value="<?php echo $all_variables["lupulo4"];?>">
    <label class="myform short" for="qlup4">Quantidade(g):</label>
    <input class="myform short" type="number" id="qlup4" min="0" max="10000" step="1" name="lqtd4" value="<?php echo $all_variables["lqtd4"];?>">
    <label class="myform long" for="tlup4">Tempo de adicao(min):</label>
    <input class="myform short" type="number" id="tlup4" min="0" max="300" step="1" name="tlupulo4" value="<?php echo $all_variables["tlupulo4"];?>">
  </div>
  <div id="lup5" style="display:none;">
    <label class="myform long" for="nlup5">Lupulo 5:</label>
    <input class="myform long" type="text" id="nlup5" maxlength="25" name="lupulo5" value="<?php echo $all_variables["lupulo5"];?>">
    <label class="myform short" for="qlup5">Quantidade(g):</label>
    <input class="myform short" type="number" id="qlup5" min="0" max="10000" step="1" name="lqtd5" value="<?php echo $all_variables["lqtd5"];?>">
    <label class="myform long" for="tlup5">Tempo de adicao(min):</label>
    <input class="myform short" type="number" id="tlup5" min="0" max="300" step="1" name="tlupulo5" value="<?php echo $all_variables["tlupulo5"];?>">
  </div>
  <div id="lup6" style="display:none;">
    <label class="myform long" for="nlup6">Lupulo 6:</label>
    <input class="myform long" type="text" id="nlup6" maxlength="25" name="lupulo6" value="<?php echo $all_variables["lupulo6"];?>">
    <label class="myform short" for="qlup6">Quantidade(g):</label>
    <input class="myform short" type="number" id="qlup6" min="0" max="10000" step="1" name="lqtd6" value="<?php echo $all_variables["lqtd6"];?>">
    <label class="myform long" for="tlup6">Tempo de adicao(min):</label>
    <input class="myform short" type="number" id="tlup6" min="0" max="300" step="1" name="tlupulo6" value="<?php echo $all_variables["tlupulo6"];?>">
  </div>
  <div id="lup7" style="display:none;">
    <label class="myform long" for="nlup7">Lupulo 7:</label>
    <input class="myform long" type="text" id="nlup7" maxlength="25" name="lupulo7" value="<?php echo $all_variables["lupulo7"];?>">
    <label class="myform short" for="qlup7">Quantidade(g):</label>
    <input class="myform short" type="number" id="qlup7" min="0" max="10000" step="1" name="lqtd7" value="<?php echo $all_variables["lqtd7"];?>">
    <label class="myform long" for="tlup7">Tempo de adicao(min):</label>
    <input class="myform short" type="number" id="tlup7" min="0" max="300" step="1" name="tlupulo7" value="<?php echo $all_variables["tlupulo7"];?>">
  </div>
  <div id="lup8" style="display:none;">
    <label class="myform long" for="nlup8">Lupulo 8:</label>
    <input class="myform long" type="text" id="nlup8" maxlength="25" name="lupulo8" value="<?php echo $all_variables["lupulo8"];?>">
    <label class="myform short" for="qlup8">Quantidade(g):</label>
    <input class="myform short" type="number" id="qlup8" min="0" max="10000" step="1" name="lqtd8" value="<?php echo $all_variables["lqtd8"];?>">
    <label class="myform long" for="tlup8">Tempo de adicao(min):</label>
    <input class="myform short" type="number" id="tlup8" min="0" max="300" step="1" name="tlupulo8" value="<?php echo $all_variables["tlupulo8"];?>">
  </div>
  
  <h4>Cozimento do mosto</h4>
  <div id="tpo0">
    <label class="myform long" for="tperaturaini">Temperatura inicial:</label>
    <input class="myform short" type="number" id="tperaturaini" name="temperaturaini" min="0" max="99" step="0.5" value="<?php echo $all_variables["temperaturaini"];?>"><span class="error">* <?php echo $tiniErr;?></span>
  </div>
  <div id="tpo1">
    <label class="myform long" for="tperatura1">Temperatura 1:</label>
    <input class="myform short" type="number" id="tperatura1" name="temperatura1" min="0" max="99" step="0.5" value="<?php echo $all_variables["temperatura1"];?>">
    <label class="myform short" for="tmpo1">Tempo(min):</label>
    <input class="myform short" type="number" id="tmpo1" min="0" step="1" name="tempo1" value="<?php echo $all_variables["tempo1"];?>"><span class="error">* <?php echo $tempErr;?></span>
  </div>
  <div id="tpo2" style="display:none;">
    <label class="myform long" for="tperatura2">Temperatura 2:</label>
    <input class="myform short" type="number" id="tperatura2" name="temperatura2" min="0" max="99" step="0.5" value="<?php echo $all_variables["temperatura2"];?>">
    <label class="myform short" for="tmpo2">Tempo(min):</label>
    <input class="myform short" type="number" id="tmpo2" min="0" step="1" name="tempo2" value="<?php echo $all_variables["tempo2"];?>">
  </div>
  <div id="tpo3" style="display:none;">
    <label class="myform long" for="tperatura3">Temperatura 3:</label>
    <input class="myform short" type="number" id="tperatura3" name="temperatura3" min="0" max="99" step="0.5" value="<?php echo $all_variables["temperatura3"];?>">
    <label class="myform short" for="tmpo3">Tempo(min):</label>
    <input class="myform short" type="number" id="tmpo3" min="0" step="1" name="tempo3" value="<?php echo $all_variables["tempo3"];?>">
  </div>
  <div id="tpo4" style="display:none;">
    <label class="myform long" for="tperatura4">Temperatura 4:</label>
    <input class="myform short" type="number" id="tperatura4" name="temperatura4" min="0" max="99" step="0.5" value="<?php echo $all_variables["temperatura4"];?>">
    <label class="myform short" for="tmpo4">Tempo(min):</label>
    <input class="myform short" type="number" id="tmpo4" min="0" step="1" name="tempo4" value="<?php echo $all_variables["tempo4"];?>">
  </div>
  <div id="tpo5" style="display:none;">
    <label class="myform long" for="tperatura5">Temperatura 5:</label>
    <input class="myform short" type="number" id="tperatura5" name="temperatura5" min="0" max="99" step="0.5" value="<?php echo $all_variables["temperatura5"];?>">
    <label class="myform short" for="tmpo5">Tempo(min):</label>
    <input class="myform short" type="number" id="tmpo5" min="0" step="1" name="tempo5" value="<?php echo $all_variables["tempo5"];?>">
  </div>
  <div id="tpo6" style="display:none;">
    <label class="myform long" for="tperatura6">Temperatura 6:</label>
    <input class="myform short" type="number" id="tperatura6" name="temperatura6" min="0" max="99" step="0.5" value="<?php echo $all_variables["temperatura6"];?>">
    <label class="myform short" for="tmpo6">Tempo(min):</label>
    <input class="myform short" type="number" id="tmpo6" min="0" step="1" name="tempo6" value="<?php echo $all_variables["tempo6"];?>">
  </div>
  <div id="tpo7" style="display:none;">
    <label class="myform long" for="tperatura7">Temperatura 7:</label>
    <input class="myform short" type="number" id="tperatura7" name="temperatura7" min="0" max="99" step="0.5" value="<?php echo $all_variables["temperatura7"];?>">
    <label class="myform short" for="tmpo7">Tempo(min):</label>
    <input class="myform short" type="number" id="tmpo7" min="0" step="1" name="tempo7" value="<?php echo $all_variables["tempo7"];?>">
  </div>
  <div id="tpo8" style="display:none;">
    <label class="myform long" for="tperatura8">Temperatura 8:</label>
    <input class="myform short" type="number" id="tperatura8" name="temperatura8" min="0" max="99" step="0.5" value="<?php echo $all_variables["temperatura8"];?>">
    <label class="myform short" for="tmpo8">Tempo(min):</label>
    <input class="myform short" type="number" id="tmpo8" min="0" step="1" name="tempo8" value="<?php echo $all_variables["tempo8"];?>">
  </div>
  <br><br>
  
  <input class="myform" type="button" value="Voltar" onClick="window.location='./listrecipe.php'">
</form>
</body>
</html>
