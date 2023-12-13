<?php
require_once 'db.php';
//header('Content-Type: text/html; charset=utf-8');
if(!isset($_COOKIE["annotator"])){
    header("location:index.php");
}
$prefix = "nctti_ka_";
$MAXANNOT = "100";
$GOAL=6;

$tbl_mwes = $prefix."mwes";
$tbl_respostas = $prefix."responses";
$tbl_anotacao = $prefix."annotations";

/******************************************************************************/

function get_random_mwe_id($anno, $pdo){
    global $MAXANNOT;
    global $prefix;
    global $tbl_mwes;
    global $tbl_respostas;
    # Select MWE IDs that : are not annotated by current annotator and do not have MAXANNOT annotations yet
    $stmt = $pdo->prepare("SELECT nan.id FROM (SELECT m.id, COUNT(m.id) as cid FROM ($tbl_mwes as m LEFT JOIN $tbl_respostas AS r ON m.id = r.idMWE ) WHERE id NOT IN (SELECT idMWE FROM $tbl_respostas WHERE anotador = :anotador) GROUP BY m.id) AS nan WHERE cid <= :maxannot");
    $stmt->execute(array(':anotador' => $anno, ':maxannot' => $MAXANNOT));
    $results = $stmt->fetch(PDO::FETCH_NUM);
    $lstIDs = array();
    if($results){
        foreach ($results as $ID) {
          array_push($lstIDs, $ID);
        }
    }
    if ($lstIDs) {
       $tent = $lstIDs[array_rand($lstIDs,1)];
       return $tent;
    }
    else{
                echo ("<h1>თუ მოახერხეთ ყველა შესიტყვების ანოტირება, უღრმესი მადლობა! :-)</h1>");
    }
}

/******************************************************************************/

function store_previous_answer($ans1, $ans2, $ans3, $comments, $equivalents, $anno, $pdo){
    $idMWE = $_POST['idMWE'];
    $idSent= $_POST['idSent'];
    global $prefix;
    global $tbl_respostas;
    global $tbl_anotacao;
    $check = $pdo->prepare("SELECT * FROM $tbl_respostas WHERE idMWE = :idMWE AND anotador = :anno");
    $check->execute(array(':idMWE' => $idMWE, ':anno' => $anno));
    $test = $check->fetch(PDO::FETCH_NUM);
    if ( ! $test){
      $stmt = $pdo->prepare("INSERT INTO $tbl_respostas (idMWE, idSent, anotador, resp1, resp2, resp3, comments) VALUES (:idMWE, :idSent, :anotador, :ans1, :ans2, :ans3, :comments)");
      $stmt->execute(array(':idMWE'  => $idMWE, ':idSent' => $idSent, ':anotador' => $anno, ':ans1' => $ans1, ':ans2' => $ans2, ':ans3'=> $ans3, ':comments' => $comments));
      for($i=0; $i < count($equivalents); $i++){
        $stmt = $pdo->prepare("INSERT INTO $tbl_anotacao (idmwe, idsent, idanno, word) VALUES (:idMWE, :idSent, :idAnno, :word)");
        $stmt->execute(array(':idMWE' => $idMWE, ':idSent' => $idSent, ':idAnno' =>$anno, ':word' => $equivalents[$i]));
      }
    }
}

/******************************************************************************/

//sleep(2); Test sending button disabled

$anno = $_COOKIE["annotator"];
// User skipped previous question, store this decision
if(isset($_POST['btt_pular'])){
    store_previous_answer(-1,-1,-1,"pulou",array(),$anno, $pdo);
}
// User submitted last question, store the answers
if(isset($_POST['btt_next'])){
    $equivalents = $_POST['values'];
    $ans1 = $_POST['Qhead'];
    $ans2 = $_POST['Qmodifier'];
    $ans3 = $_POST['Qheadmodifier'];
    $comments = $_POST['comments'];
    store_previous_answer($ans1, $ans2, $ans3, $comments, $equivalents,$anno, $pdo);
}
// Generate next question
$idMWE = get_random_mwe_id($anno, $pdo);
if($idMWE) { // else no new question available, all annotated or problem
    //Retrieve all information about the compound
    $stmt = $pdo->prepare("SELECT * FROM $tbl_mwes WHERE id = :id");
    $stmt->execute(array(':id'=> $idMWE));
    $mweinfo = $stmt->fetch(PDO::FETCH_ASSOC);
    // Retrieve information about user
    $stmt2 = $pdo->prepare("SELECT count(*) FROM $tbl_respostas WHERE anotador = :anno");
    $stmt2->execute(array(':anno' => $anno));
    $result = $stmt2->fetch(PDO::FETCH_ASSOC);
    $done = $result['count(*)'];
    $percent = min(round((($done*100.0)/$GOAL)),100);

    // Add regex - replace double spaces with single
    echo preg_replace('/\s+/', ' ', 
'<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="ka">
  <head>
      <meta http-equiv="content-type" content="text/html; charset=utf-8">
      <title>სახელური შესიტყვებების ინტერპრეტაცია</title>
      <meta name="generator" content="Bootply" />
      <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
      <link href="css/bootstrap.min.css" rel="stylesheet">
      <!--[if lt IE 9]>
        <script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
      <![endif]-->
      <link href="css/styles.css" rel="stylesheet">
      <link href="css/mturk.css" rel="stylesheet">
  </head>
  <body>
  <div class="idandprogress">
    <p>სისტემას უკავშირდებით, როგორც <strong>: '.$anno.'</strong>. უკვე მოახდინეთ '.$done.' შესიტყვების ანოტირება. გასაკეთებელია '.$GOAL.' შესიტყვება (დარჩენილია'.max(0,$GOAL-$done).')</p>
    <div class="progress">
      <div class="progress-bar" role="progressbar" aria-valuenow="'.$percent.'" aria-valuemin="0" aria-valuemax="100" style="width:'.$percent.'%">'.$percent.'%</div>
    </div>
    <div class="panel panel-primary">
    <div class="panel-heading"><strong>ინსტრუქციები (შეხსენება)</strong></div>
      <div class="panel-body">
        <p>წაიკითხეთ გამოთქმა ქართულად. მიუთითეთ მინიმუმ 1 სინონიმი და შეაფასეთ, თუ როგორია თითოეული სიტყვის ინდივიდუალური წვლილი გამოთქმის საერთო მნიშვნელობის შექმნაში, სკალაზე 0-დან 5-მდე.</p>
        <ul>
        <li>თითოეულ გამოთქმას მაქსიმუმ 1-2 წუთი დაუთმეთ და არა მეტი.</li>
        <li>თუ გამოთქმის ან მისი შემცველი წინადადებების მნიშვნელობა არ გესმით, გამოტოვეთ.</li>
        <li>თუ გამოთქმის მნიშვნელობის ახსნა სხვადასხვანაირად შეიძლება, განიხილეთ მხოლოდ ის მნიშვნელობა, რომელიც მესამე (3) წინადადებაშია წარმოდგენილი.</li>
        <li>გამოთქმის ანოტირება შესაძლებელია მხოლოდ ერთხელ. უკან დაბრუნება შეუძლებელია.</li>
        <li>დიდ დროს შეკითხვებს ნუ უთმობთ, რადგანაც თითოეულ კითხვაზე რამდენიმე პასუხი არსებობს.</li>
        </ul>
      </div>
    </div>
  </div>
  <form action="pagina.php" method="POST" onsubmit="return checkValid()">
    <INPUT TYPE="hidden" name="idMWE" VALUE="'. $idMWE . '">
    <INPUT TYPE="hidden" name="idSent" VALUE="'. $idMWE . '">
    <!-- script references -->
      <script src="js/jquery-2.1.1.min.js"></script>
      <script src="js/bootstrap.min.js"></script>
      <script src="js/suggestion_processing.js"></script>
      <div class="container-full">
        <div class="col-md-8">

          <fieldset>
            <label>1. წაიკითხეთ შემდეგი გამოთქმა:</label>
            <br/>
            <span class="indentation"></span><span style="font-size: 20pt"><em>' . $mweinfo['compound'] . '</em></span>
          </fieldset>

          <br/><!--=====================================================-->

          <fieldset>
            <label>2. წაიკითხეთ წინადადებები, რომლებშიც გამოყენებულია <em>' . $mweinfo['compound'] . '</em> გამოთქმა:</label>
            <br/>
            <ul>
              <li>' . $mweinfo["examplesent1"] . '</li>
              <li>' . $mweinfo["examplesent2"] . '</li>
              <li>' . $mweinfo["examplesent3"] . '</li>
            </ul>
            <hr/>
            <em> ამ წინადადებებში &#8594; გამოთქმის მნიშვნელობა არ მესმის </em>
            <button onclick="setValidoParaPular()" class="btn btn-default" type="submit" name="btt_pular" value="skipPage" id="bttPular"> გამოტოვეთ გამოთქმა </button>
          </fieldset>

          <br/><!--=====================================================-->

          <fieldset>
            <label>3. ჩაწერეთ 2 ან 3 სინონიმი (სიტყვა ან გამოთქმა), რომლებიც შესაბამისობაში იქნება წინადადებაში წარმოდგენილ <em>' . $mweinfo['compound'] . '</em> გამოთქმასთან:</label>
            <br/>
            <br/>
            <div class="input-group" style="width:400px;text-align:center;margin:0 auto;">
                <input id="inputWord" class="form-control input-lg" title="უპირატესობა მიანიჭეთ მოკლე პასუხებს (1-3 სიტყვა), შემდეგი სიტყვების გამოყენებით &quot;' . $mweinfo['head'] . '&quot; and/or &quot;' . $mweinfo['modifier'] . '&quot;. ნუ შეიყვანთ სრულ წინადადებებს ან სალექსიკონო განმარტებებს." placeholder="შემდეგი გამოთქმის სინონიმია'. $mweinfo['compound'] . '..." type="text">
                <span class="input-group-btn"><button id="submitWord" onclick="addSuggestion()" class="btn btn-lg btn-primary" type="button">შეყვანა</button></span>
            </div>
            <br/>
            <select id="candidateList" class="form-control" multiple="multiple" style="width:400px;margin:0 auto;" name="values[ ]"></select>
            <br/>
            <center>
                <button onclick="removeSelected()" class="btn btn-default" style="width:200px;" type="button">წაშლა</button>
                <button onclick="clearAll()" class="btn btn-default" style="width:200px;" type="button">ყველაფრის წაშლა</button>
            </center>
          </fieldset>

          <br/><!--=====================================================-->

          <fieldset>
            <label>4. თქვენი აზრით, ' . $mweinfo['be'] . " " . $mweinfo['undefdet_compound'] . ' <em>' . $mweinfo['compound'] . '</em> ყოველთვის სიტყვასიტყვით ' . $mweinfo['undefdet_head'] . ' <em>' . $mweinfo['head'] . '</em>? </label>
            <br/>
            <br/>
            <table class="radio-table">
              <tbody>
                <tr><td class="bigno" rowspan="2">არა</td> <td class="number">0</td> <td class="number">1</td> <td class="number">2</td> <td class="number">3</td> <td class="number">4</td> <td class="number">5</td> <td class="bigyes" rowspan="2">დიახ</td></tr>
                <tr>
                  <td class="tooltippy"><input id="questio11" name="Qhead" required="" type="radio" value="0" />
                        <div class="ttip">საერთოდ არა &mdash; ' . $mweinfo['undefdet_compound'] . ' <em>' . $mweinfo['compound'] . '</em> ' . $mweinfo['have'] . ' <u>არაფერია</u> გასაკეთებელი' . $mweinfo['undefdet_head'] . '-თან <em>' . $mweinfo['head'] . '</em></div>
                        </td>
                        <td class="tooltippy"><input id="questio12" name="Qhead" required="" type="radio" value="1" />
                        <div class="ttip">არა &mdash; ვხედავ მხოლოდ <u>ბუნდოვან კავშირს</u> ' . $mweinfo['undefdet_compound'] . ' <em>' . $mweinfo['compound'] . '</em> და ' . $mweinfo['undefdet_head'] . ' <em>' . $mweinfo['head'] . ' შორის </em></div>
                        </td>
                        <td class="tooltippy"><input id="questio13" name="Qhead" required="" type="radio" value="2" />
                        <div class="ttip">ნამდვილად არა &mdash; შემდეგი გამოთქმის მნიშვნელობა <em>' . $mweinfo['compound'] . '</em>  <u>ასოცირდება</u> <em>' . $mweinfo['head'] . '-თან </em>, თუმცა <u>არაპირდაპირი გზით</u></div>
                        </td>
                        <td class="tooltippy"><input id="questio14" name="Qhead" required="" type="radio" value="3" />
                        <div class="ttip">ერთგვარი მსგავსება &mdash; შემდეგი გამოთქმის მნიშვნელობა <em>' . $mweinfo['compound'] . '</em> <u>პირდაპირ ასოცირდება</u> <em>' . $mweinfo['head'] . '-თან </em>, მაშინაც კი, როცა აღნიშნული მნიშვნელობები არ არის იდენტური</div>
                        </td>
                        <td class="tooltippy"><input id="questio15" name="Qhead" required="" type="radio" value="4" />
                        <div class="ttip">დიახ &mdash; ' . $mweinfo['undefdet_compound'] . ' <em>' . $mweinfo['compound'] . '</em> <u>' . $mweinfo['be'] . ' რეალურად </u> ' . $mweinfo['undefdet_head'] . ' <em>' . $mweinfo['head'] . '</em>, სიტყვის იშვიათი მნიშვნელობისათვის <em>' . $mweinfo['head'] . '</em></div>
                        </td>
                        <td class="tooltippy"><input id="questio16" name="Qhead" required="" type="radio" value="5" />
                        <div class="ttip">ზუსტია! &mdash; ' . $mweinfo['undefdet_compound'] . ' <em>' . $mweinfo['compound'] . '</em> ' . $mweinfo['be'] . ' <u>ყოველთვის სიტყვასიტყვითია</u> ' . $mweinfo['undefdet_head'] . ' <em>' . $mweinfo['head'] . '</em></div>
                        </td>
                </tr>
              </tbody>
            </table>
          </fieldset>

          <br/><!--=====================================================-->

          <fieldset>
            <label>5. თქვენი აზრით, '. $mweinfo['be'].' შემდეგი გამოთქმის მნიშვნელობა '. $mweinfo['undefdet_compound'].' <em>'.$mweinfo['compound'].'</em> ყოველთვის სიტყვასიტყვით უკავშირდება '.$mweinfo['something_modifier'].' <em>'. $mweinfo['modifier'].'</em>? </label>
            <br/>
            <br/>
            <table class="radio-table">
              <tbody>
                <tr><td class="bigno" rowspan="2">არა</td> <td class="number">0</td> <td class="number">1</td> <td class="number">2</td> <td class="number">3</td> <td class="number">4</td> <td class="number">5</td> <td class="bigyes" rowspan="2">დიახ</td></tr>
                <tr>
                  	<td class="tooltippy"><input id="questio21" name="Qmodifier" required="" type="radio" value="0" />
                    <div class="ttip">საერთოდ არა &mdash; ' . $mweinfo['undefdet_compound'] . ' <em>' . $mweinfo['compound'] . '</em> ' . $mweinfo['have'] . ' <u>არაფერია</u> გასაკეთებელი ' . $mweinfo['something_modifier'] . '-თან <em>' . $mweinfo['modifier'] . '</em></div>
                    </td>
                    <td class="tooltippy"><input id="questio22" name="Qmodifier" required="" type="radio" value="1" />
                    <div class="ttip">არა &mdash; ვხედავ მხოლოდ <u>ბუნდოვან კავშირს</u> ' . $mweinfo['undefdet_compound'] . ' <em>' . $mweinfo['compound'] . '</em> და ' . $mweinfo['something_modifier'] . ' <em>' . $mweinfo['modifier'] . ' შორის </em></div>
                    </td>
                    <td class="tooltippy"><input id="questio23" name="Qmodifier" required="" type="radio" value="2" />
                    <div class="ttip">ნამდვილად არა &mdash; შემდეგი გამოთქმის მნიშვნელობა  <em>' . $mweinfo['compound'] . '</em>  <u>ასოცირდება</u> ' . $mweinfo['something_modifier'] . ' <em>' . $mweinfo['modifier'] . '-თან </em>, თუმცა <u>არაპირდაპირი გზით</u></div>
                    </td>
                    <td class="tooltippy"><input id="questio24" name="Qmodifier" required="" type="radio" value="3" />
                    <div class="ttip">ერთგვარი მსგავსება &mdash; შემდეგი გამოთქმის მნიშვნელობა <em>' . $mweinfo['compound'] . '</em> <u>პირდაპირ ასოცირდება</u> ' . $mweinfo['something_modifier'] . '-თან <em>' . $mweinfo['modifier'] . '</em>, მაშინაც კი, როცა აღნიშნული მნიშვნელობები არ არის იდენტური</div>
                    </td>					
		            <td class="tooltippy"><input id="questio25" name="Qmodifier" required="" type="radio" value="4" />
                    <div class="ttip">დიახ &mdash; შემდეგი გამოთქმის მნიშვნელობა <em>' . $mweinfo['compound'] . '</em> <u>რეალურად</u> ნიშნავს ' . $mweinfo['something_modifier'] . ' <em>' . $mweinfo['modifier'] . '</em>, სიტყვის იშვიათი მნიშვნელობისათვის <em>' . $mweinfo['modifier'] . '</em></div>
                    </td>
                    <td class="tooltippy"><input id="questio26" name="Qmodifier" required="" type="radio" value="5" />
                    <div class="ttip">ზუსტია! &mdash; შემდეგი გამოთქმის მნიშვნელობა <em>' . $mweinfo['compound'] . '</em> <u>ყოველთვის სიტყვასიტყვითია</u> და ნიშნავს ' . $mweinfo['something_modifier'] . ' <em>' . $mweinfo['modifier'] . '</em></div>
                    </td>
                </tr>
              </tbody>
            </table>
          </fieldset>

          <br/><!--=====================================================-->
          <fieldset>
            <label>6. თქვენს პასუხებზე დაყრდნობით, იტყვით თუ არა რომ ' . $mweinfo['undefdet_compound'] . ' <em>' . $mweinfo['compound'] . '</em> ' . $mweinfo['be'] . ' ყოველთვის სიტყვასიტყვითია ' . $mweinfo['undefdet_head'] . ' <em>' . $mweinfo['head'] . '</em> რომელიც ' . $mweinfo['be'] . ' ' . $mweinfo['relatedto_modifier'] . ' <em>' . $mweinfo['modifier'] . '</em>? </label>
            <br/>
            <br/>
            <table class="radio-table">
              <tbody>
                <tr><td class="bigno" rowspan="2">არა</td> <td class="number">0</td> <td class="number">1</td> <td class="number">2</td> <td class="number">3</td> <td class="number">4</td> <td class="number">5</td> <td class="bigyes" rowspan="2">დიახ</td></tr>
                <tr>
                  <td class="tooltippy"><input id="questio31" name="Qheadmodifier" required="" type="radio" value="0" />
                  <div class="ttip">საერთოდ არა &mdash; ამ გამოთქმას <u>არა აქვს გადატანითი მნიშვნელობა</u>' . $mweinfo['undefdet_head'] . ' <em>' . $mweinfo['head'] . '</em> რომელიც ' . $mweinfo['be'] . ' ' . $mweinfo['relatedto_modifier'] . ' <em>' . $mweinfo['modifier'] . '</em></div>
                  </td>
                  <td class="tooltippy"><input id="questio32" name="Qheadmodifier" required="" type="radio" value="1" />
                  <div class="ttip">არა &mdash; ეს გამოთქმა წარმოსადგენად <u>უცნაურია</u> ' . $mweinfo['undefdet_head'] . ' <em>' . $mweinfo['head'] . '</em> რომელიც ' . $mweinfo['be'] . ' ' . $mweinfo['relatedto_modifier'] . ' <em>' . $mweinfo['modifier'] . '</em>, მაშინაც კი, როცა მნიშვნელობა გასაგებია</div>
                  </td>
                  <td class="tooltippy"><input id="questio33" name="Qheadmodifier" required="" type="radio" value="2" />
                  <div class="ttip">ნამდვილად არა &mdash; გამოთქმის მნიშვნელობა <em>' . $mweinfo['compound'] . '</em>  <u>ასოცირდება</u>  ' . $mweinfo['undefdet_head'] . ' <em>' . $mweinfo['head'] . '-თან </em> და ' . $mweinfo['something_modifier'] . ' <em>' . $mweinfo['modifier'] . '-თან </em>, თუმცა არაპირდაპირი გზით</div>
                  </td>				  
                  <td class="tooltippy"><input id="questio34" name="Qheadmodifier" required="" type="radio" value="3" />
                  <div class="ttip">ერთგვარი მსგავსება &mdash; შემდეგი გამოთქმის მნიშვნელობა <em>' . $mweinfo['compound'] . '</em> <u>პირდაპირ ასოცირდება</u> with ' . $mweinfo['undefdet_head'] . ' <em>' . $mweinfo['head'] . '</em> and ' . $mweinfo['something_modifier'] . ' <em>' . $mweinfo['modifier'] . '-თან </em>, მაშინაც კი, როცა აღნიშნული მნიშვნელობები არ არის იდენტური</div>
                  </td>
                  <td class="tooltippy"><input id="questio35" name="Qheadmodifier" required="" type="radio" value="4" />
                  <div class="ttip">დიახ &mdash; ' . $mweinfo['undefdet_compound'] . ' <em>' . $mweinfo['compound'] . '</em> <u>' . $mweinfo['be'] . ' რეალურად </u> ' . $mweinfo['undefdet_head'] . ' <em>' . $mweinfo['head'] . '</em> რომელიც ' . $mweinfo['be'] . ' ' . $mweinfo['relatedto_modifier'] . ' <em>' . $mweinfo['modifier'] . '</em></div>
                  </td>
                  <td class="tooltippy"><input id="questio36" name="Qheadmodifier" required="" type="radio" value="5" />
                  <div class="ttip">ზუსტია! &mdash; ' . $mweinfo['undefdet_compound'] . ' <em>' . $mweinfo['compound'] . '</em> ' . $mweinfo['be'] . ' <u>ყოველთვის სიტყვასიტყვითია</u> ' . $mweinfo['undefdet_head'] . ' <em>' . $mweinfo['head'] . '</em> რომელიც ' . $mweinfo['be'] . ' ' . $mweinfo['relatedto_modifier'] . ' <em>' . $mweinfo['modifier'] . '</em></div>
                  </td>
                </tr>
              </tbody>
            </table>
          </fieldset>

          <br/><!--=====================================================-->

          <fieldset>
            <label>დაურთეთ ნებისმიერი კომენტარი, რომელიც გექნებათ (სურვილის შემთხვევაში): </label><br/>
            <textarea cols="40" rows="5" name="comments"></textarea>
          </fieldset>

          <br/><!--=====================================================-->

          <button class="btn btn-default" style="width:100px;float: right; margin-bottom:20px;" type="submit" name="btt_next" value="nextPage" id="bttNext">Next</button>
        </div>
      </div>
    </form>
  </body>
</html>');
}
?>