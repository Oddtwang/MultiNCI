<?php

require_once 'db.php';
header('Content-Type: text/html; charset=UTF-8');

$users = array();
$prefix = "nctti_ro_";

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
