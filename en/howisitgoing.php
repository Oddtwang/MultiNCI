<?php

require_once 'db.php';

echo '<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="pt">
  <head>
      <meta http-equiv="content-type" content="text/html; charset="ISO-8859-1">
      <title>Interpreta��o de substantivos compostos</title>
      <meta name="generator" content="Bootply" />
      <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
      <link href="css/bootstrap.min.css" rel="stylesheet">
      <!--[if lt IE 9]>
        <script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
      <![endif]-->
      <link href="css/styles.css" rel="stylesheet">
  </head>
  <body>
    <div class="container">
    <h1>Compounds annotation: database snapshot</h1>

    <p>'.date(DATE_COOKIE).'

    <table class="table table-hover table-bordered">
      <tr><th>annotator</th><th>ID</th><th>compound</th><th>answer-head</th><th>answer-modifier</th><th>answer-headModifier</th><th>equivalents</th></tr>';
  $stmt = $pdo->prepare("SELECT id, compound, anotador, AVG(resp1), AVG(resp2), AVG(resp3), AVG(literality), GROUP_CONCAT(word SEPARATOR ' - ') AS equivalents FROM (SELECT id, compound, anotador, resp1, resp2, resp3 FROM mturk_en_mwes AS m LEFT JOIN mturk_en_respostas AS r ON m.id = r.idMWE WHERE resp1 != -1) AS rm LEFT JOIN mturk_en_anotacao AS a ON rm.id = a.idMWE AND rm.anotador = a.idanno GROUP BY anotador, id ORDER BY anotador, id");
  $stmt->execute();
  $result = $stmt->fetchAll(PDO::FETCH_BOTH);
  if($result){
    $prevannot = "NONE";
    $rowcolor = "";
    foreach($result as $ar) {
      if($ar[2] != $prevannot){
        if($rowcolor == "") $rowcolor = "warning";
        else $rowcolor = "";
      }
      echo '<tr class="'.$rowcolor.'"><td>'.htmlentities($ar[2]).'</td><td>'.htmlentities($ar[0]).'</td><td>'.htmlentities($ar[1]).'</td><td>'.intval(htmlentities($ar[3])).'</td><td>'.intval(htmlentities($ar[4])).'</td><td>'.intval(htmlentities($ar[5])).'</td><td>'.htmlentities($ar[6]).'</td></tr>';
      $prevannot = $ar[2];
    }
  }
  else{
    echo "</table><h2>Error connecting with DB</h2>";
  }
  echo '</table>
  </div>
  </body>
</html>';
