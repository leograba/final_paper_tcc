<?php
    function requireCarrega($path){
        //funcao que carrega os valores salvos na receita
        //$path="./recipes/".$_GET["name"].".recipe";
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
        if (!$result){
            echo "Erro ao carregar receita!";
            return ;
        }
        return $variables;
    }

    if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["name"])){//check if recipe name was passed through URL
        $filename = "../recipes/".$_POST["name"].".recipe";//mount the filename with relative PATH
        if (file_exists($filename)){//if it is the first time loading and file exists
            echo json_encode(requireCarrega($filename));//then load the file values and return to server
        }
        else{//not sure if anything should be done here
            echo json_encode("Recipe ".$_POST["name"]." not available");//return to server that recipe is not available
        }
    }
?>