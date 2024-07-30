<?php
require_once 'db.php';
?>

<html lang="pt">
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf8">
		<title>Interpreta��o de substantivos compostos</title>
		<meta name="generator" content="Bootply" />
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<link href="css/bootstrap.min.css" rel="stylesheet">
		<link href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.min.css" rel="stylesheet">
		<!--[if lt IE 9]>
			<script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->
		<link href="css/styles.css" rel="stylesheet">
	</head>
	<body>
	
<div class="container">
      <div class="row">       
        <div class="col-lg-12 text-center v-center"> 
        <h1>Interpreta��o de substantivos compostos</h1>
          <br/>
          	<?php          	
            if( isset($anno)){
                echo '<h3 style="color:red">O identificador '.$anno.' n�o foi encontrado.</h2>';
            }            
            if( isset($_POST['create_id'])) {
                if( strtolower($_POST['passphrase'])=="macacovelhomacaco"){ // HARDCODED PASSPHRASE!
                    $uname = $_POST['name'];
                    //$surname = $_POST['surname'];
                    //$age = $_POST['age'];
                    //$country = $_POST['country'];
                    $new = true;
                    $tries = 0;
                    $tbl = $prefix."users";
                    while( $new and $tries <= 10 ) {
                      // Personal data removed - necessary items to be captured elsewhere
                      //$prefixid = strtolower(str_replace(' ', '-', $surname . $firstname . "user" )); // Replaces all spaces with hyphens.
                      $prefixid = strtolower(str_replace(' ', '-', $uname . "user" )); // Replaces all spaces with hyphens.
                      // Amended to handle non-Latin word characters
                      //$prefixid = preg_replace('/[^a-z0-9\-]/', '', $prefixid); // Remove special chars
                      $prefixid = preg_replace('/[^\p{L}0-9\-]/u', '', $prefixid); // Remove special chars
                      $annotid =  mb_substr($prefixid , 0, 8, "utf-8").rand(100000 , 999999);
											$stmt = $pdo->prepare("SELECT anotador FROM $tbl WHERE anotador = :anno");
                      $result = $stmt->execute(array(':anno' => $annotid));
											if ($result){
												$new = false;
											}
                      $tries++;               
                    }
                    if( $tries >= 10 ){
                      echo '<h3><span style="color:red">Imposs�vel criar identificador. Tem certeza que j� n�o possui uma conta?</span></h3>';
                      unset($annotid);
                    }
                    else {
                      echo '<h3>Seu identificador � <span style="color:red">'.$annotid.'</span> - Anote-o, pois n�o receber� nenhum email de confirma��o.</h2>';
											$stmt = $pdo->prepare("INSERT INTO $tbl (anotador, name) VALUES (:anno, :uname)");
											$result = $stmt->execute(array(':anno' => $annotid, ':uname' => $uname));
                    }
                }
                else{
                    echo '<h3 style="color:red">O c�digo de acesso est� errado. <br/>Consulte o c�digo no email de convite que recebeu.</h2>';
                }
            }
            
	        ?>

          <br/>
          <h4>J� tenho um identificador:</h4>
          <form class="col-lg-12" method="POST">
            <div class="input-group" style="width:340px;text-align:center;margin:0 auto;">
            <input class="form-control input-lg" title="Seus dados n�o ser�o disponibilizados a terceiros." placeholder="Informe seu identificador" type="text" name="annotator" <?php echo (isset($annotid)?'value="'.$annotid.'"':''); ?> >
              <span class="input-group-btn">
		<button class="btn btn-lg btn-primary" type="submit" name="ok" value="ok">Conectar</button></span>
            </div>
          </form>
        </div>
        
      </div> <!-- /row -->
  
</div>	

<div class="container">
	<br/>
	<br/>
	<center><h4>Ainda n�o tenho um identificador:</h4></center>	

<h2>1. Leia as instru��es</h2>

<ul>
	<li>Estamos interessados em entender como as express�es s�o interpretadas por um falante nativo do portugu�s no dia-a-dia.</li>
	<li>Voc� vai ler uma express�o. Em seguida, ir� avaliar qual a contribui��o do sentido individual de cada palavra para o sentido da express�o como um todo.</li>
	<li> Para cada express�o, voc� ler� uma frase em que a express�o aparece. Se voc� n�o entender a express�o, simplesmente pule para a pr�xima quest�o.</li>
	<li>Algumas frases podem conter erros de digita��o, uma vez que s�o da Internet. Se for esse o caso, basta ignorar esses erros de digita��o.</li>
	<li>Em seguida, voc� vai responder 3 perguntas sobre o significado das palavras individuais da express�o. Para cada pergunta, basta clicar na op��o que reflete o quanto voc� acha que as palavras individuais contribuem para o significado da express�o em uma escala de 0 (<em>N�o, a palavra n�o contribui em nada para o significado</em>) a 5 (<em>Sim, a palavra contribui muito para o significado</em>). Os valores intermedi�rios tamb�m devem ser utilizados para graduar o seu julgamento.</li>	
	<li>N�o pense demais para responder as perguntas. N�o existem respostas certas ou erradas (desde que voc� siga as instru��es).</li>
	<li>Cada express�o s� pode ser avaliada uma vez, voc� n�o poder� voltar atr�s nas suas respostas uma vez que elas forem enviadas</li>
	<li>Este n�o � um teste de mem�ria ou de intelig�ncia. N�s realmente s� queremos compreender melhor o uso de express�es em portugu�s.</li><!-- e como eles s�o interpretados por falantes nativos.</li>-->
	<li>Se voc� tiver algum problema, coment�rio, d�vida ou sugest�o, fale conosco usando o campo opcional de coment�rios no final da p�gina.</li>
</ul>

<!----------------------------------------------------------------------------->
<hl/>

<h2>2. Leia os exemplos</h2>

  <h2>CABE�A DURA</h2>

<strong>Senten�a : </strong> <em>Jo�o foi <u>cabe�a dura</u> e n�o seguiu o conselho dos diretores da empresa.</em>
<br/>
<strong>Pergunta :</strong>Liste no m�nimo 2 a 3 express�es equivalentes ou similares a <em>cabe�a dura</em>
<br/>
<strong>Resposta Esperada : </strong>
<ul>
  <li><em>teimoso</em></li>
  <li><em>pessoa teimosa</em></li>
  <li><em>insistente</em></li>  
  <li><em>...</em></li>
</ul>
<br/>
<strong>Explica��o</strong> : Se refere a indiv�duo que � teimoso, que n�o aceita opini�es dos outros. Privilegie formula��es curtas e cujo sentido � muito pr�ximo de "cabe�a dura". Evite frases longas e defini��es como "refere-se a uma pessoa que � teimosa ou insistente".


  <h2>INFERNO ASTRAL</h2>
  
<strong>Senten�a :</strong> <em>  O Jo�o est� vivendo o seu <u>inferno astral</u> e tudo que acontecer de negativo est� relacionado a isso.</em>
<br/>
<strong>Pergunta : </strong><em>Inferno astral</em> � realmente/literalmente um <em>inferno</em>?
<br/>
<strong>Resposta Esperada : </strong> <img src="img/answer-1.png">
<br/>
<strong>Explica��o</strong> : A express�o <em>inferno astral</em> se refere a um per�odo negativo na vida de uma pessoa. N�o � literalmente um inferno, e apenas tem em comum com essa palavra o fato de ser algo negativo.

<!----------------------------------------------------------------------------->
<hl/>

  <h2>CABRA-CEGA</h2>
<strong>Senten�a :</strong> <em>As crian�as da rua adoram brincar de <u>cabra-cega</u>.</em>
<br/>
<strong>Pergunta : </strong><em>Cabra-cega</em> � realmente/literalmente <em>cega</em>?
<br/>
<strong>Resposta Esperada : </strong> <img src="img/answer-0.png">
<br/>
<strong>Explica��o</strong> : A express�o <em>cabra-cega</em> se refere a um jogo em que um dos participantes est� de olhos vendados e tenta encontrar os outros. N�o existe ningu�m literalmente cego nesse jogo.


<!----------------------------------------------------------------------------->
<hl/>

  <h2>ARM�RIO EMBUTIDO</h2>

<strong>Senten�a : </strong> <em>Recomendamos a empresa para quem quer um <u>arm�rio embutido</u> ideal em seu quarto. </em>
<br/>
<strong>Pergunta :</strong> Um <em>arm�rio embutido</em> � realmente/literalmente um <em>arm�rio</em> que est� <em>embutido</em>?
<br/>
<strong>Resposta Esperada : </strong> <img src="img/answer-5.png">
<br/>
<strong>Explica��o</strong> : A express�o <em>arm�rio embutido</em> se refere a um arm�rio constru�do sob medida junto das paredes de um ambiente. Ele � literalmente embutido na parede.


<!----------------------------------------------------------------------------->
<hl/>
          <h2>3. Preencha o formul�rio abaixo</h2>
      <div class="row">       
        <div class="col-lg-12 text-center v-center">
          <form class="col-lg-12" method="POST">
            <div class="input-group" style="width:380px;text-align:center;margin:0 auto;">
            <!-- <input required class="form-control input-lg" title="Digite aqui seu nome" placeholder="Nome (e.g. Jo�o)" type="text" name="name"/> -->
            <!-- <input required class="form-control input-lg" title="Digite aqui seu sobrenome" placeholder="Sobrenome (e.g. Silva)" type="text" name="surname"/> -->
            <!-- <input required class="form-control input-lg" title="Digite aqui sua idade" placeholder="Idade (e.g. 25)" type="text" name="age"> -->
            <!-- <input required class="form-control input-lg" title="Onde voc� mora?" placeholder="Pa�s de resid�ncia (e.g. Brasil, Portugal...)" type="text" name="country"> -->
            <input required class="form-control input-lg" title="Nome de usuário desejado" placeholder="Nome de usuário" type="text" name="name"/>
            <input required class="form-control input-lg" title="C�digo de acesso" placeholder="C�digo recebido por email" type="text" name="passphrase">
            <!-- <p><input required class="" title="Se voc� n�o preenche esse crit�rio, por favor n�o participe do nosso experimento" type="checkbox" name="native"/> Certifico que vivi no Brasil at� os 13 anos de idade e que meus pais falaram portugu�s comigo durante esse per�odo.</p> -->
		        <button class="btn btn-lg btn-primary" type="submit" name="create_id" value="create_id">Criar meu identificador</button>
            </div>
          </form>
        </div>
        
      </div> <!-- /row -->
  
</div>

	</body>
</html>
