<?php

    error_reporting(-1); ini_set("display_errors", "On");//debug on
    function handleRecipe($recipe){
        //recipename comes as string with underline as first chacarcter
        if(!empty($recipe["recipe"])){//check if string is not empty
            $filename = "/var/www/recipes/".$recipe["recipe"].".recipe";//absolute path
            if($recipe["stat"] == "delete"){//if recipe is to be deleted
                $copyErr = rename($filename,$filename.".del");//copy from .recipe to .recipe.del
            }
            else{//if recipe is to be recovered
                $copyErr = rename($filename.".del",$filename);//copy from .recipe.del to .recipe
            }
            if(!$copyErr){//if rename went wrong
                return 2;//return non-zero error 2 defined here as "could not backup file"
            }
            else{//if backup is ok
                return 0;
            }
        }
        else{//if string is empty return an error
            return 1;//return non-zero error 1 defined here as "name string is empty"
        }
    }

    echo handleRecipe($_POST);//handle the request and return status to client-side

?>