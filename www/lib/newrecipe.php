<?php
  error_reporting(-1);
  ini_set("display_errors", "On");

  function test_input($data){
  //verifica integridade dos dados{
    $data = trim($data);//exclui caracteres desnecessarios
    $data = stripslashes($data);//exclui backslashes "\"
    $data = htmlspecialchars($data);//evita receber codigo malicioso
    return $data;
  }

  function requireSalva($variables){
  //funcao criada soh para poder passar os valores para salvareceita.php
    //require 'salvareceita.php';
    if(!empty($variables["nome_da_receita"])){//check if recipe has a name
      $filename = "/var/www/recipes/";//absolute PATH to the recipe
      $filename .= str_replace(" ","_",$variables["nome_da_receita"]);//mount the name
      $filename .= ".recipe";//add file extension
    }
    else{//if there is no name
      return '{"status":"1","msg":"Erro ao salvar receita! Sem nome!"}';//echo error
    }
    $s="";
    foreach($variables as $v){//put one content in each line
    	$s .= serialize($v).PHP_EOL;
    }
    
    $result = file_put_contents($filename,$s);
    if ($result)
    {return '{"status":"0","msg":"Receita salva!"}';}
    else {return '{"status":"1","msg":"Erro ao salvar receita! Não deu pra gravar!"}';}
  }

  function requireCarrega(){
  //funcao que carrega os valores salvos na receita
    $path="./recipes/".$_GET["name"].".recipe";
    $variables = array( "nome_da_receita" => "", "estilo" => "", "levedura" => "",
      "mosto" => "", "lavagem" => "", "tlavagem" => "", "fervura" => "", "temperaturaini" => "",
      "temperatura1" => "", "tempo1" => "", "temperatura2" => "", "tempo2" => "",
      "temperatura3" => "", "tempo3" => "", "temperatura4" => "", "tempo4" => "",
      "temperatura5" => "", "tempo5" => "", "temperatura6" => "", "tempo6" => "",
      "temperatura7" => "", "tempo7" => "", "temperatura8" => "", "tempo8" => "",
      "malte1" => "", "qtd1" => "", "malte2" => "", "qtd2" => "",
      "malte3" => "", "qtd3" => "", "malte4" => "", "qtd4" => "",
      "malte5" => "", "qtd5" => "", "malte6" => "", "qtd6" => "",
      "malte7" => "", "qtd7" => "", "malte8" => "", "qtd8" => "",
      "lupulo1" => "", "lqtd1" => "", "tlupulo1" => "",
      "lupulo2" => "", "lqtd2" => "", "tlupulo2" => "",
      "lupulo3" => "", "lqtd3" => "", "tlupulo3" => "",
      "lupulo4" => "", "lqtd4" => "", "tlupulo4" => "",
      "lupulo5" => "", "lqtd5" => "", "tlupulo5" => "",
      "lupulo6" => "", "lqtd6" => "", "tlupulo6" => "",
      "lupulo7" => "", "lqtd7" => "", "tlupulo7" => "",
      "lupulo8" => "", "lqtd8" => "", "tlupulo8" => "",
    );
    //require 'carregareceita.php';
    if(!empty($path)){
    	$file=fopen($path,"r");//abre arquivo da receita
    }
    else{
      echo "Erro ao carregar receita!";
      return ;
    }
    $s = file($path, FILE_IGNORE_NEW_LINES);//le arquivo da receita salva
    $count = 0;//variavel de iteracao
    foreach($variables as &$v){//apesar de variables ter key => value, usar soh value
    		$v = unserialize($s[$count]);//copia um array no outro
    		$count++;
    }
    
    $result = fclose($file);
    if (!$result)
    {echo "Erro ao carregar receita!";}
    return $variables;
  }

  function loadFormData(){
    if(isset($_GET["name"])){//check if recipe name was passed through URL
      $filename = "recipes/".$_GET["name"].".recipe";//mount the filename with relative PATH
      if (file_exists($filename)){//if it is the first time loading and file exists
        return requireCarrega();//then load the file values
      }
      else{//not sure if anything should be done here
        
      }
    }
  }

  // define variaveis para guardar os dados recebidos e verificados
  $all_variables = array( "nome_da_receita" => "", "estilo" => "", "levedura" => "",
    "mosto" => "", "lavagem" => "", "tlavagem" => "", "fervura" => "", "temperaturaini" => "",
    "temperatura1" => "", "tempo1" => "", "temperatura2" => "", "tempo2" => "",
    "temperatura3" => "", "tempo3" => "", "temperatura4" => "", "tempo4" => "",
    "temperatura5" => "", "tempo5" => "", "temperatura6" => "", "tempo6" => "",
    "temperatura7" => "", "tempo7" => "", "temperatura8" => "", "tempo8" => "",
    "malte1" => "", "qtd1" => "", "malte2" => "", "qtd2" => "",
    "malte3" => "", "qtd3" => "", "malte4" => "", "qtd4" => "",
    "malte5" => "", "qtd5" => "", "malte6" => "", "qtd6" => "",
    "malte7" => "", "qtd7" => "", "malte8" => "", "qtd8" => "",
    "lupulo1" => "", "lqtd1" => "", "tlupulo1" => "",
    "lupulo2" => "", "lqtd2" => "", "tlupulo2" => "",
    "lupulo3" => "", "lqtd3" => "", "tlupulo3" => "",
    "lupulo4" => "", "lqtd4" => "", "tlupulo4" => "",
    "lupulo5" => "", "lqtd5" => "", "tlupulo5" => "",
    "lupulo6" => "", "lqtd6" => "", "tlupulo6" => "",
    "lupulo7" => "", "lqtd7" => "", "tlupulo7" => "",
    "lupulo8" => "", "lqtd8" => "", "tlupulo8" => "",
  );
  $nameErr = $estiloErr = $malte1Err = $lupulo1Err = $tempErr = $tiniErr = "";
  $leveduraErr = $mostoErr = $lavagemErr = $fervuraErr = "";

  if ($_SERVER["REQUEST_METHOD"] == "POST")//se recebeu formulario
  {
    //echo json_encode($_POST);//echo content recieved for debug/verification
    if (empty($_POST["nome_da_receita"])){//verifica campo vazio
      $nameErr = "Nome eh necessario";//string indicando o erro
    }
    else{//se campo nao esta vazio
      $all_variables["nome_da_receita"] = test_input($_POST["nome_da_receita"]);//testa a integridade do dado
      if (!preg_match("/^[a-zA-Z 0-9]*$/",$all_variables["nome_da_receita"])){//varifica se eh valido
        $nameErr = "Somente letras e numeros";//string indicando erro
      }
      //else {$nameErr = "";}//livre de erro
    }
  
    if (empty($_POST["estilo"])){
      $estiloErr = "Estilo eh necessario";
    }
    else{
      $all_variables["estilo"] = test_input($_POST["estilo"]);
      if (!preg_match("/^[a-zA-Z ]*$/",$all_variables["estilo"])){
        $estiloErr = "Somente letras";
      }
      //else {$estiloErr = "";}
    }
  
    if (empty($_POST["levedura"])){
      $leveduraErr = "Levedura eh necessario";
    }
    else{
      $all_variables["levedura"] = test_input($_POST["levedura"]);
      if (!preg_match("/^[a-zA-Z 0-9]*$/",$all_variables["levedura"])){
        $leveduraErr = "Somente letras e numeros";
      }
      //else {$leveduraErr = "";}
    }
  
    if(empty($_POST["mosto"])){
      $mostoErr = "Adicione a quantidade de agua do mosto";}
    else{
      $all_variables["mosto"] = test_input($_POST["mosto"]);
      if (!preg_match("/^[0-9.]*$/",$all_variables["mosto"])){
        $mostoErr = "Somente numeros e virgula";
      }
      //else {$mostoErr = "";}
    }
  
    if(empty($_POST["lavagem"])){
      $lavagemErr = "Adicione a quantidade de agua";
    }
    else{
      $all_variables["lavagem"] = test_input($_POST["lavagem"]);
      if (!preg_match("/^[0-9.]*$/",$all_variables["lavagem"])){
        $lavagemErr = "Somente numeros e virgula";
      }
      //else {$lavagemErr = "";}
    }
  
    if(empty($_POST["tlavagem"])){
      $lavagemErr = "Temperatura eh necessario";
    }
    else{
      $all_variables["tlavagem"] = test_input($_POST["tlavagem"]);
      if (!preg_match("/^[0-9.]*$/",$all_variables["tlavagem"])){
        $lavagemErr = "Somente numeros e virgula";
      }
      //else {$lavagemErr = "";}
    }
  
    if(empty($_POST["fervura"])){
      $fervuraErr = "Informe o tempo de fervura do mosto";
    }
    else{
      $all_variables["fervura"] = test_input($_POST["fervura"]);
      if (!preg_match("/^[0-9.]*$/",$all_variables["fervura"])){
        $fervuraErr = "Somente numeros e virgula";
        }
      //else {$fervuraErr = "";}
    }
    
    if(empty($_POST["malte1"]))
      {$malte1Err = "Adicione, no minimo, Malte 1";}
    else
      {$all_variables["malte1"] = test_input($_POST["malte1"]);
      if (!preg_match("/^[a-zA-Z 0-9]*$/",$all_variables["malte1"]))
        {$malte1Err = "Somente letras e numeros";}
     //else {$malte1Err = "";}
    }
    
    if(empty($_POST["qtd1"]))
     {$malte1Err = "Adicione, no minimo, Malte 1";}
    else
      {$all_variables["qtd1"] = test_input($_POST["qtd1"]);
      if (!preg_match("/^[0-9.]*$/",$all_variables["qtd1"]))
        {$malte1Err = "Somente numeros e virgula";}
      //else {$malte1Err = "";}
    }
    
    $all_variables["malte2"] = test_input($_POST["malte2"]);
    if (!preg_match("/^[a-zA-Z 0-9]*$/",$all_variables["malte2"]))
      {$malte1Err="Somente letras e numeros";}
    //else {$malte1Err = "";}
    $all_variables["qtd2"] = test_input($_POST["qtd2"]);
    if (!preg_match("/^[0-9.]*$/",$all_variables["qtd2"]))
      {$malte1Err="Somente numeros e virgula";}
    //else {$malte1Err = "";}
    
    $all_variables["malte3"] = test_input($_POST["malte3"]);
    if (!preg_match("/^[a-zA-Z 0-9]*$/",$all_variables["malte3"]))
      {$malte1Err="Somente letras e numeros";}
    //else {$malte1Err = "";}
    $all_variables["qtd3"] = test_input($_POST["qtd3"]);
    if (!preg_match("/^[0-9.]*$/",$all_variables["qtd3"]))
      {$malte1Err="Somente numeros e virgula";}
    //else {$malte1Err = "";}
    
    $all_variables["malte4"] = test_input($_POST["malte4"]);
    if (!preg_match("/^[a-zA-Z 0-9]*$/",$all_variables["malte4"]))
      {$malte1Err="Somente letras e numeros";}
    //else {$malte1Err = "";}
    $all_variables["qtd4"] = test_input($_POST["qtd4"]);
    if (!preg_match("/^[0-9.]*$/",$all_variables["qtd4"]))
      {$malte1Err="Somente numeros e virgula";}
    //else {$malte1Err = "";}
    
    $all_variables["malte5"] = test_input($_POST["malte5"]);
    if (!preg_match("/^[a-zA-Z 0-9]*$/",$all_variables["malte5"]))
      {$malte1Err="Somente letras e numeros";}
    //else {$malte1Err = "";}
    $all_variables["qtd5"] = test_input($_POST["qtd5"]);
    if (!preg_match("/^[0-9.]*$/",$all_variables["qtd5"]))
      {$malte1Err="Somente numeros e virgula";}
    //else {$malte1Err = "";}
    
    $all_variables["malte6"] = test_input($_POST["malte6"]);
    if (!preg_match("/^[a-zA-Z 0-9]*$/",$all_variables["malte6"]))
      {$malte1Err="Somente letras e numeros";}
    //else {$malte1Err = "";}
    $all_variables["qtd6"] = test_input($_POST["qtd6"]);
    if (!preg_match("/^[0-9.]*$/",$all_variables["qtd6"]))
      {$malte1Err="Somente numeros e virgula";}
    //else {$malte1Err = "";}
    
    $all_variables["malte7"] = test_input($_POST["malte7"]);
    if (!preg_match("/^[a-zA-Z 0-9]*$/",$all_variables["malte7"]))
      {$malte1Err="Somente letras e numeros";}
    //else {$malte1Err = "";}
    $all_variables["qtd7"] = test_input($_POST["qtd7"]);
    if (!preg_match("/^[0-9.]*$/",$all_variables["qtd7"]))
      {$malte1Err="Somente numeros e virgula";}
    //else {$malte1Err = "";}
    
    $all_variables["malte8"] = test_input($_POST["malte8"]);
    if (!preg_match("/^[a-zA-Z 0-9]*$/",$all_variables["malte8"]))
      {$malte1Err="Somente letras e numeros";}
    //else {$malte1Err = "";}
    $all_variables["qtd8"] = test_input($_POST["qtd8"]);
    if (!preg_match("/^[0-9.]*$/",$all_variables["qtd8"]))
      {$malte1Err="Somente numeros e virgula";}
    //else {$malte1Err = "";}
    
    if(empty($_POST["lupulo1"]))
      {$lupulo1Err = "Adicione, no minimo, Lupulo 1";}
    else
      {$all_variables["lupulo1"] = test_input($_POST["lupulo1"]);
      if (!preg_match("/^[a-zA-Z 0-9]*$/",$all_variables["lupulo1"]))
        {$lupulo1Err = "Somente letras e numeros";}
      //else {$lupulo1Err = "";}
    }
    
    if(empty($_POST["lqtd1"]))
      {$lupulo1Err = "Adicione, no minimo, Lupulo 1";}
    else
      {$all_variables["lqtd1"] = test_input($_POST["lqtd1"]);
      if (!preg_match("/^[0-9.]*$/",$all_variables["lqtd1"]))
        {$lupulo1Err="Somente numeros e virgula";}
      //else {$lupulo1Err = "";}
    }
    
    if(empty($_POST["tlupulo1"]))
      {$lupulo1Err = "Adicione, no minimo, Lupulo 1";}
    else
      {$all_variables["tlupulo1"] = test_input($_POST["tlupulo1"]);
      if (!preg_match("/^[0-9]*$/",$all_variables["tlupulo1"]))
        {$lupulo1Err="Somente numeros";}
      //else {$lupulo1Err = "";}
    }
    
    $all_variables["lupulo2"] = test_input($_POST["lupulo2"]);
    if (!preg_match("/^[a-zA-Z 0-9]*$/",$all_variables["lupulo2"]))
      {$lupulo1Err="Somente letras e numeros";}
    //else {$lupulo1Err = "";}
    $all_variables["lqtd2"] = test_input($_POST["lqtd2"]);
    if (!preg_match("/^[0-9.]*$/",$all_variables["lqtd2"]))
      {$lupulo1Err="Somente numeros e virgula";}
    //else {$lupulo1Err = "";}
    $all_variables["tlupulo2"] = test_input($_POST["tlupulo2"]);
    if (!preg_match("/^[0-9]*$/",$all_variables["tlupulo2"]))
      {$lupulo1Err="Somente numeros";}
    //else {$lupulo1Err = "";}
    
    $all_variables["lupulo3"] = test_input($_POST["lupulo3"]);
    if (!preg_match("/^[a-zA-Z 0-9]*$/",$all_variables["lupulo3"]))
      {$lupulo1Err="Somente letras e numeros";}
    //else {$lupulo1Err = "";}
    $all_variables["lqtd3"] = test_input($_POST["lqtd3"]);
    if (!preg_match("/^[0-9.]*$/",$all_variables["lqtd3"]))
      {$lupulo1Err="Somente numeros e virgula";}
    //else {$lupulo1Err = "";}
    $all_variables["tlupulo3"] = test_input($_POST["tlupulo3"]);
    if (!preg_match("/^[0-9]*$/",$all_variables["tlupulo3"]))
      {$lupulo1Err="Somente numeros";}
    //else {$lupulo1Err = "";}
    
    $all_variables["lupulo4"] = test_input($_POST["lupulo4"]);
    if (!preg_match("/^[a-zA-Z 0-9]*$/",$all_variables["lupulo4"]))
      {$lupulo1Err="Somente letras e numeros";}
    //else {$lupulo1Err = "";}
    $all_variables["lqtd4"] = test_input($_POST["lqtd4"]);
    if (!preg_match("/^[0-9.]*$/",$all_variables["lqtd4"]))
      {$lupulo1Err="Somente numeros e virgula";}
    //else {$lupulo1Err = "";}
    $all_variables["tlupulo4"] = test_input($_POST["tlupulo4"]);
    if (!preg_match("/^[0-9]*$/",$all_variables["tlupulo4"]))
      {$lupulo1Err="Somente numeros";}
    //else {$lupulo1Err = "";}
    
    $all_variables["lupulo5"] = test_input($_POST["lupulo5"]);
    if (!preg_match("/^[a-zA-Z 0-9]*$/",$all_variables["lupulo5"]))
      {$lupulo1Err="Somente letras e numeros";}
    //else {$lupulo1Err = "";}
    $all_variables["lqtd5"] = test_input($_POST["lqtd5"]);
    if (!preg_match("/^[0-9.]*$/",$all_variables["lqtd5"]))
      {$lupulo1Err="Somente numeros e virgula";}
    //else {$lupulo1Err = "";}
    $all_variables["tlupulo5"] = test_input($_POST["tlupulo5"]);
    if (!preg_match("/^[0-9]*$/",$all_variables["tlupulo5"]))
      {$lupulo1Err="Somente numeros";}
    //else {$lupulo1Err = "";}
    
    $all_variables["lupulo6"] = test_input($_POST["lupulo6"]);
    if (!preg_match("/^[a-zA-Z 0-9]*$/",$all_variables["lupulo6"]))
      {$lupulo1Err="Somente letras e numeros";}
    //else {$lupulo1Err = "";}
    $all_variables["lqtd6"] = test_input($_POST["lqtd6"]);
    if (!preg_match("/^[0-9.]*$/",$all_variables["lqtd6"]))
      {$lupulo1Err="Somente numeros e virgula";}
    //else {$lupulo1Err = "";}
    $all_variables["tlupulo6"] = test_input($_POST["tlupulo6"]);
    if (!preg_match("/^[0-9]*$/",$all_variables["tlupulo6"]))
      {$lupulo1Err="Somente numeros";}
    //else {$lupulo1Err = "";}
    
    $all_variables["lupulo7"] = test_input($_POST["lupulo7"]);
    if (!preg_match("/^[a-zA-Z 0-9]*$/",$all_variables["lupulo7"]))
      {$lupulo1Err="Somente letras e numeros";}
    //else {$lupulo1Err = "";}
    $all_variables["lqtd7"] = test_input($_POST["lqtd7"]);
    if (!preg_match("/^[0-9.]*$/",$all_variables["lqtd7"]))
      {$lupulo1Err="Somente numeros e virgula";}
    //else {$lupulo1Err = "";}
    $all_variables["tlupulo7"] = test_input($_POST["tlupulo7"]);
    if (!preg_match("/^[0-9]*$/",$all_variables["tlupulo7"]))
      {$lupulo1Err="Somente numeros";}
    //else {$lupulo1Err = "";}
    
    $all_variables["lupulo8"] = test_input($_POST["lupulo8"]);
    if (!preg_match("/^[a-zA-Z 0-9]*$/",$all_variables["lupulo8"]))
      {$lupulo1Err="Somente letras e numeros";}
    //else {$lupulo1Err = "";}
    $all_variables["lqtd8"] = test_input($_POST["lqtd8"]);
    if (!preg_match("/^[0-9.]*$/",$all_variables["lqtd8"]))
      {$lupulo1Err="Somente numeros e virgula";}
    //else {$lupulo1Err = "";}
    $all_variables["tlupulo8"] = test_input($_POST["tlupulo8"]);
    if (!preg_match("/^[0-9]*$/",$all_variables["tlupulo8"]))
      {$lupulo1Err="Somente numeros";}
    //else {$lupulo1Err = "";}
    
    if(empty($_POST["temperaturaini"]))
      {$tiniErr = "Informe a temperatura inicial de cozimento do mosto";}
    else
      {$all_variables["temperaturaini"] = test_input($_POST["temperaturaini"]);
      if (!preg_match("/^[0-9.]*$/",$all_variables["temperaturaini"]))
        {$tiniErr = "Somente numeros e virgula";}
      //else {$tiniErr = "";}
    }
    
    if(empty($_POST["temperatura1"]))
      {$tempErr = "Adicione, no minimo, Temperatura 1";}
    else
      {$all_variables["temperatura1"] = test_input($_POST["temperatura1"]);
      if (!preg_match("/^[0-9.]*$/",$all_variables["temperatura1"]))
        {$tempErr = "Somente numeros e virgula";}
      //else {$tempErr = "";}
    }
    
    if(empty($_POST["tempo1"]))
      {$tempErr = "Adicione, no minimo, Temperatura 1";}
    else
      {$all_variables["tempo1"] = test_input($_POST["tempo1"]);
      if (!preg_match("/^[0-9]*$/",$all_variables["tempo1"]))
        {$tempErr = "Somente numeros";}
      //else {$tempErr = "";}
    }
    
    $all_variables["temperatura2"] = test_input($_POST["temperatura2"]);
    if (!preg_match("/^[0-9.]*$/",$all_variables["temperatura2"]))
      {$tempErr="Somente numeros e virgula";}
    //else {$tempErr = "";}
    $all_variables["tempo2"] = test_input($_POST["tempo2"]);
    if (!preg_match("/^[0-9]*$/",$all_variables["tempo2"]))
      {$tempErr="Somente numeros";}
    //else {$tempErr = "";}
    
    $all_variables["temperatura3"] = test_input($_POST["temperatura3"]);
    if (!preg_match("/^[0-9.]*$/",$all_variables["temperatura3"]))
      {$tempErr="Somente numeros e virgula";}
    //else {$tempErr = "";}
    $all_variables["tempo3"] = test_input($_POST["tempo3"]);
    if (!preg_match("/^[0-9]*$/",$all_variables["tempo3"]))
      {$tempErr="Somente numeros";}
    //else {$tempErr = "";}
    
    $all_variables["temperatura4"] = test_input($_POST["temperatura4"]);
    if (!preg_match("/^[0-9.]*$/",$all_variables["temperatura4"]))
      {$tempErr="Somente numeros e virgula";}
    //else {$tempErr = "";}
    $all_variables["tempo4"] = test_input($_POST["tempo4"]);
    if (!preg_match("/^[0-9]*$/",$all_variables["tempo4"]))
      {$tempErr="Somente numeros";}
    //else {$tempErr = "";}
    
    $all_variables["temperatura5"] = test_input($_POST["temperatura5"]);
    if (!preg_match("/^[0-9.]*$/",$all_variables["temperatura5"]))
      {$tempErr="Somente numeros e virgula";}
    //else {$tempErr = "";}
    $all_variables["tempo5"] = test_input($_POST["tempo5"]);
    if (!preg_match("/^[0-9]*$/",$all_variables["tempo5"]))
      {$tempErr="Somente numeros";}
    //else {$tempErr = "";}
    
    $all_variables["temperatura6"] = test_input($_POST["temperatura6"]);
    if (!preg_match("/^[0-9.]*$/",$all_variables["temperatura6"]))
      {$tempErr="Somente numeros e virgula";}
    //else {$tempErr = "";}
    $all_variables["tempo6"] = test_input($_POST["tempo6"]);
    if (!preg_match("/^[0-9]*$/",$all_variables["tempo6"]))
      {$tempErr="Somente numeros";}
    //else {$tempErr = "";}
    
    $all_variables["temperatura7"] = test_input($_POST["temperatura7"]);
    if (!preg_match("/^[0-9.]*$/",$all_variables["temperatura7"]))
      {$tempErr="Somente numeros e virgula";}
    //else {$tempErr = "";}
    $all_variables["tempo7"] = test_input($_POST["tempo7"]);
    if (!preg_match("/^[0-9]*$/",$all_variables["tempo7"]))
      {$tempErr="Somente numeros";}
    //else {$tempErr = "";}
    
    $all_variables["temperatura8"] = test_input($_POST["temperatura8"]);
    if (!preg_match("/^[0-9.]*$/",$all_variables["temperatura8"]))
      {$tempErr="Somente numeros e virgula";}
    //else {$tempErr = "";}
    $all_variables["tempo8"] = test_input($_POST["tempo8"]);
    if (!preg_match("/^[0-9]*$/",$all_variables["tempo8"]))
      {$tempErr="Somente numeros";}
    //else {$tempErr = "";}
    
    
    //if(($nameErr === "") and ($estiloErr === "") and ($malte1Err === "") and ($lupulo1Err === "") and ($tempErr === "") and ($tiniErr === "") and ($leveduraErr === "") and ($mostoErr === "") and ($lavagemErr === "") and ($fervuraErr === "")){
    if($nameErr === ""){//só não salva se o nome estiver errado, se for outra coisa, salva mesmo assim
      $sts = json_decode(requireSalva($all_variables),true);//se nao tem nenhum erro, salva
      if($estiloErr || $malte1Err || $lupulo1Err || $tempErr || $tiniErr || $leveduraErr || $mostoErr || $lavagemErr || $fervuraErr){
        $sts["msg"] = "Receita salva, mas há campos necessários não preenchidos.";
      }
      echo json_encode($sts);
    }
    else{
      echo "Falha ao salvar, verifique possíveis erros!\n";
      echo "$nameErr, $estiloErr, $malte1Err, $lupulo1Err, $tempErr, $tiniErr, $leveduraErr, $mostoErr, $lavagemErr, $fervuraErr";
    }
  }

?>