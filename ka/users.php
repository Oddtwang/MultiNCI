<?php

require_once 'db.php';
header('Content-Type: text/html; charset=utf-8');

$users = array();
$prefix = "nctti_ka_";

$tbl = $prefix."users";
$stmt = $pdo->prepare("SELECT * FROM $tbl");
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);


if($result){
    foreach ($result as $user) {
      //$users[$user['anotador']] = $user['name'] . " " . $user['surname'];
      $users[$user['anotador']] = $user['name'];
    }
}

?>
