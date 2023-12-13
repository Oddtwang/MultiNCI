<?php
require_once 'db.php';
?>

<html lang="en">
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf8">
		<title>English noun compound interpretation</title>
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
        <h1>English noun compound interpretation</h1>
          <br/>
          	<?php
            if( isset($anno)){
                echo '<h3 style="color:red">The login '.$anno.' could not be found.</h2>';
            }
            if( isset($_POST['create_id'])) {
                if( strtolower($_POST['passphrase'])=="bigcheesebigpicture"){ // HARDCODED PASSPHRASE!
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
                      echo '<h3><span style="color:red">We could not create a random login for you. Are you sure you don\'t have an account?</span></h3>';
                      unset($annotid);
                    }
                    else {
                      echo '<h3>Your login is <span style="color:red">'.$annotid.'</span> - Please save it somewhere because you will not receive a confirmation email.</h2>';
											$stmt = $pdo->prepare("INSERT INTO $tbl (anotador, name) VALUES (:anno, :uname)");
											$result = $stmt->execute(array(':anno' => $annotid, ':uname' => $uname));
                    }
                }
                else{
                    echo '<h3 style="color:red">The access phrase is wrong. <br/>Please check the access phrase in your invitation email.</h2>';
                }
            }

	        ?>

          <br/>
          <h4>I already have a login:</h4>
          <form class="col-lg-12" method="POST">
            <div class="input-group" style="width:340px;text-align:center;margin:0 auto;">
            <input class="form-control input-lg" title="Your data will not be shared with third parties." placeholder="Your login" type="text" name="annotator" <?php echo (isset($annotid)?'value="'.$annotid.'"':''); ?> >
              <span class="input-group-btn">
		<button class="btn btn-lg btn-primary" type="submit" name="ok" value="ok">Log in</button></span>
            </div>
          </form>
        </div>

      </div> <!-- /row -->

</div>

<div class="container">
	<br/>
	<br/>
	<center><h4>I do not have a login yet:</h4></center>

<h2>1. Read the instructions</h2>

<ul>
 <li>You will read expressions in English and evaluate if and how much the individual meanings of the words in these expression contribute to the meaning of the expression as a whole.</li>
  <li>We are interested in how expressions are interpreted by a speaker of English in normal daily speech.  For each expression, you will be asked to read 3 sentences where it appears. If you don't understand the expression, just skip the compound.</li>
  <li>If the expression has more than one meaning, consider <em>ONLY</em> those in the example sentences.</li>
  <li>Some sentences may contain typos, since they are from the Internet. If that is the case just ignore the typos. </li>
  <li>You will answer 2 questions about the meaning of the individual words of the expression. For each question, simply click on how much you think the individual words contribute to the meaning of the expression on a scale from 0 (<em>No, it doesn't contribute at all</em>) to 5 (<em>Yes, it totally contributes</em>). The intermediate values can be used for nuance. </li>
  <li>Finally you will be asked to input 3 alternative synonyms for the expression that could be used instead of the expression and have the same meaning. </li>
  <li>Don't think about each expression too much, as there are no right or wrong answers. This is not a memory or intelligence test. We really only want to better understand expressions in English and how they are interpreted by speakers.</li>
  <li>If you have any problems, comments or suggestions, don't hesitate to note them at the bottom of the page.</li>
</ul>

<!----------------------------------------------------------------------------->
<hl/>

<h2>2. Read the examples</h2>

  <h2>MONKEY BUSINESS</h2>

<strong>Sentence : </strong> <em>The nanny thought that there had been some <u>monkey business</u> going on while she was out in the garden.</em>
<br/>
<strong>Question :</strong>Now enter 3 synonyms for <em>monkey business</em>
<br/>
<strong>Expected Answer : </strong>
<ul>
  <li><em>mischief</em></li>
  <li><em>misbehavior</em></li>
  <li><em>dishonesty</em></li>
</ul>
<br/>
<strong>Explanation</strong> : monkey business refers to mischievous or deceitful behaviour. The alternatives proposed have similar meanings.

  <h2>IVORY TOWER</h2>
<strong>Sentence :</strong> <em>Academics sitting in <u>ivory towers</u> have no understanding of what is important for people like us.</em>
<br/>
<strong>Question :</strong> Is an <em>ivory tower</em> literally <em>made of ivory</em> ?
<br/>
<strong>Expected Answer : </strong> <img src="img/answer-0.png"> <!-- 0 -->
<br/>
<strong>Explanation</strong> : To be in an <em>ivory tower</em> can be understood as not to know about the ordinary things that happen in people's lives. It doesn't have any relation to the words, as it doesn't mean to be in a place made of ivory, or to be in a tower.

<!----------------------------------------------------------------------------->

  <h2>ROCKET SCIENCE</h2>

<strong>Sentence : </strong> <em>Don't worry, it's only a crossword, it's not  <u>rocket science</u>.</em>
<br/>
<strong>Question :</strong> Is <em>rocket science</em>  truly/literally <em>science</em> ?
<br/>
<strong>Expected Answer : </strong> <img src="img/answer-1.png"> <!-- 1 -->
<br/>
<strong>Explanation</strong> : it is used for something that is not seen as difficult to do or understand. It doesn't really refer to a scientific activity, or has anything to do with rockets.

<!----------------------------------------------------------------------------->

  <h2>CLIMATE CHANGE</h2>

<strong>Sentence : </strong> <em>Policies designed to encourage adaptation to <u>climate change</u> may conflict with regulation aimed at protecting the environment. </em>
<br/>
<strong>Question :</strong> Is <em>climate change</em> truly/literally a <em>change</em> in <em>climate</em> ?
<br/>
<strong>Expected Answer : </strong> <img src="img/answer-5.png"> <!-- 5 -->
<br/>
<strong>Explanation</strong> : climate change refers to the way the world's weather is changing.


<!----------------------------------------------------------------------------->
<hl/>
          <h2>3. Fill in the registration form</h2>
          <strong>Note</strong>: Avoid re-using a username you use elsewhere, for security.
      <div class="row">
        <div class="col-lg-12 text-center v-center">
          <form class="col-lg-12" method="POST">
            <div class="input-group" style="width:380px;text-align:center;margin:0 auto;">
            <!-- <input required class="form-control input-lg" title="Tell us your first name" placeholder="First name (e.g. John)" type="text" name="name"/>-->
            <!-- <input required class="form-control input-lg" title="Tell us your last/family name" placeholder="Last name (e.g. Smith)" type="text" name="surname"/>-->
            <!-- <input required class="form-control input-lg" title="Tell us your age" placeholder="Age (e.g. 25)" type="text" name="age">-->
            <!-- <input required class="form-control input-lg" title="Onde voc� mora?" placeholder="Country where you live (e.g. UK, Bulgaria...)" type="text" name="country">-->
            <input required class="form-control input-lg" title="Desired username" placeholder="Username" type="text" name="name"/>
            <input required class="form-control input-lg" title="Access phrase" placeholder="Secret access phrase received by email" type="text" name="passphrase">
            <!-- <p><input required class="" title="If you do not fulfull this criterion, please do not participate in the experiment" type="checkbox" name="native"/> I certify that I am an English speaker.</p>-->
		        <button class="btn btn-lg btn-primary" type="submit" name="create_id" value="create_id">Create my login</button>
            </div>
          </form>
        </div>

      </div> <!-- /row -->

</div>

	</body>
</html>
