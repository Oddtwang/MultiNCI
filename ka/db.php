<?php
$pdo = new PDO('mysql:host=localhost;port=8889;dbname=nctti',
   'nctti_web_user', 'EagerB3averPandaCar');
// See the "errors" folder for details...
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>
