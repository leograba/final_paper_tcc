<?php
    error_reporting(-1); ini_set("display_errors", "On");//debug on
    
    //echo '{"0":"true"}';
    if(isset($_POST["url"])){//workaround because Express doesn't set http_host and request_uri
        echo generateHeader();
    }
    
    function generateHeader(){
        if(isset($_POST["url"])){//workaround because Express doesn't set http_host and request_uri
            $url = parse_url($_POST["url"]);
            $short_url = $url["path"];
        }
        else{
            $url = parse_url($_SERVER["HTTP_HOST"]);//needed because of the different port used for startrecipe
            $short_url = $_SERVER["REQUEST_URI"];
        }
        $links = array( "' href='http://$url[host]:8587/restricted/listareceita.php'",
                        "' href='http://$url[host]:8587/startrecipe.php'",
                        "' href='http://$url[host]:8587/restricted/teste.php'",
                        "' href='http://$url[host]:8587/settings.php'",
                        "' href='http://$url[host]:8587/index2.php'");
        $selected = "style='background:#595450;' href='#'";
        
        switch($short_url){//highlight div and disable click to current page
            case "/restricted/listareceita.php":
                $links[0] = $selected;
                break;
            case "/startrecipe.php":
                $links[1] = $selected;
                break;
            case "/restricted/teste.php":
                $links[2] = $selected;
                break;
            case "/settings.php":
                $links[3] = $selected;
                break;
            case "/index2.php":
                $links[4] = $selected;
                break;
        }
        // Navbar structure
        return "
        <div class='myheader'>
            <div class='headercell'>
                <a class='headerlink' $links[0]>receitas</a>
            </div>
            <div class='headercell'>
                <a class='headerlink' $links[1]>brassagem</a>
            </div>
            <div class='headercell'>
                <a class='headerlink' $links[2]>estatísticas</a>
            </div>
            <div class='headercell'>
                <a class='headerlink' $links[3]>configurações</a>
            </div>
            <div class='headercell'>
                <a class='headerlink' $links[4]>sobre</a>
            </div>
        </div>";
    }
?>