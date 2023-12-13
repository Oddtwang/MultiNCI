<?php
require_once 'db.php';
require_once 'users.php';

$prefix = "nctti_en_";

if(isset($_POST['ok'])){
    $anno = strtolower(htmlentities($_POST['annotator']));
    if(isset($users[$anno])){
        setcookie("annotator", $anno);
        $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $actual_link = str_replace("http://wprojs-php/~","https://idiom-annot.shef.ac.uk",$actual_link);
	      $actual_link = str_replace("en.php","",$actual_link);
        echo "<META http-equiv='refresh' content='0;URL=" . $actual_link . "pagina.php'> ";
        echo "If you are not redirected automatically, <a href='" . $actual_link ."'>click here</a>";
    }else{
        require('login.php');
    }
}else{
    require('login.php');
}

?>
