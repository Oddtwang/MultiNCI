<?php

        $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $actual_link = str_replace("http://wprojs-php/~","https://idiom-annot.shef.ac.uk",$actual_link);
	      $actual_link = str_replace("index.php","",$actual_link);
        echo "<META http-equiv='refresh' content='0;URL=" . $actual_link . "en.php'> ";
        echo "If you are not redirected automatically, <a href='" . $actual_link ."'>click here</a>";

?>
