<?php
require_once 'db.php';
//header('Content-Type: text/html; charset=utf-8');
if(!isset($_COOKIE["annotator"])){
    header("location:index.php");
}
$prefix = "nctti_en_";
$MAXANNOT = "307";
$GOAL=307;

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
        echo ("<h1>You have annotated all compounds, thanks! :-)</h1>");
    }
}

/******************************************************************************/

function store_previous_answer($ans1, $ans2, $ans3, $lit, $comments, $equivalents, $anno, $pdo){
    $idMWE = $_POST['idMWE'];
    $idSent= $_POST['idSent'];
    global $prefix;
    global $tbl_respostas;
    global $tbl_anotacao;
    $check = $pdo->prepare("SELECT * FROM $tbl_respostas WHERE idMWE = :idMWE AND anotador = :anno");
    $check->execute(array(':idMWE' => $idMWE, ':anno' => $anno));
    $test = $check->fetch(PDO::FETCH_NUM);
    if ( ! $test){
      $stmt = $pdo->prepare("INSERT INTO $tbl_respostas (idMWE, idSent, anotador, resp1, resp2, resp3, literality, comments) VALUES (:idMWE, :idSent, :anotador, :ans1, :ans2, :ans3, :lit, :comments)");
      $stmt->execute(array(':idMWE'  => $idMWE, ':idSent' => $idSent, ':anotador' => $anno, ':ans1' => $ans1, ':ans2' => $ans2, ':ans3'=> $ans3, ':lit'=> $lit, ':comments' => $comments));      for($i=0; $i < count($equivalents); $i++){
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
    store_previous_answer(-1,-1,-1,-1,"pulou",array(),$anno, $pdo);
}
// User submitted last question, store the answers
if(isset($_POST['btt_next'])){
    $equivalents = $_POST['values'];
    $ans1 = $_POST['Qhead'];
    $ans2 = $_POST['Qmodifier'];
    $ans3 = $_POST['Qheadmodifier'];
    $lit = $_POST['Qliterality'];
    $comments = $_POST['comments'];
    store_previous_answer($ans1, $ans2, $ans3, $lit, $comments, $equivalents,$anno, $pdo);
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
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
  <head>
      <meta http-equiv="content-type" content="text/html; charset=utf8">
      <title>Interpretation of noun compounds</title>
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
    <p>You are connected as <strong>: '.$anno.'</strong>, you have annotated '.$done.' expressions, your goal is '.$GOAL.' expressions ('.max(0,$GOAL-$done).' to go)</p>
    <div class="progress">
      <div class="progress-bar" role="progressbar" aria-valuenow="'.$percent.'" aria-valuemin="0" aria-valuemax="100" style="width:'.$percent.'%">'.$percent.'%</div>
    </div>
    <div class="panel panel-primary">
    <div class="panel-heading"><strong>Instructions (reminder)</strong></div>
      <div class="panel-body">
        <p>You will read an English expression. Then, you will provide 2-3 synonyms and evaluate what the individual contribution of each word is to the global meaning of the expression, on a scale from 0 to 5.</p>
        <ul>
        <li>Each expression should take 1 to 2 minutes to complete, no more.</li>
        <li>If you do not understand the expression or the sentences containing it, you should skip the compound.</li>
        <li>If the expression sounds ambiguous to you, consider ONLY the meaning that is reflected in the 3 sentences.</li>
        <li>You can only annotate an expression once, it is not possible to come back</li>
        <li>Do not think for too long about each question, there are several possible answers</li>
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
            <label>1. Read the following expression:</label>
            <br/>
            <span class="indentation"></span><span style="font-size: 20pt"><em>' . $mweinfo['compound'] . '</em></span>
          </fieldset>

          <br/><!--=====================================================-->

          <fieldset>
            <label>2. Read the following sentences containing the expression <em>' . $mweinfo['compound'] . '</em>:</label>
            <br/>
            <ul>
              <li>' . $mweinfo["examplesent1"] . '</li>
              <li>' . $mweinfo["examplesent2"] . '</li>
              <li>' . $mweinfo["examplesent3"] . '</li>
            </ul>
            <hr/>
            <em> I did not understand the meaning of the expression in these sentences &#8594; </em>
            <button onclick="setValidoParaPular()" class="btn btn-default" type="submit" name="btt_pular" value="skipPage" id="bttPular"> Skip this expression </button>
          </fieldset>

          <br/><!--=====================================================-->

          <fieldset>
            <label>3. Type in 2 to 3 synonyms, that is, words or expressions that are equivalent to <em>' . $mweinfo['compound'] . '</em>:</label>
            <br/>
            <br/>
            <div class="input-group" style="width:400px;text-align:center;margin:0 auto;">
                <input id="inputWord" class="form-control input-lg" title="Prefer short answers (1-3 words), possibly using the words &quot;' . $mweinfo['head'] . '&quot; and/or &quot;' . $mweinfo['modifier'] . '&quot;. Do not enter full sentences or dictionary definitions." placeholder="Synonyms of '. $mweinfo['compound'] . '..." type="text">
                <span class="input-group-btn"><button id="submitWord" onclick="addSuggestion()" class="btn btn-lg btn-primary" type="button">enter</button></span>
            </div>
            <br/>
            <select id="candidateList" class="form-control" multiple="multiple" style="width:400px;margin:0 auto;" name="values[ ]"></select>
            <br/>
            <center>
                <button onclick="removeSelected()" class="btn btn-default" style="width:200px;" type="button">Delete</button>
                <button onclick="clearAll()" class="btn btn-default" style="width:200px;" type="button">Delete all</button>
            </center>
          </fieldset>

          <br/><!--=====================================================-->

          <fieldset>
            <label>4. In your opinion, ' . $mweinfo['be'] . " " . $mweinfo['undefdet_compound'] . ' <em>' . $mweinfo['compound'] . '</em> always literally ' . $mweinfo['undefdet_head'] . ' <em>' . $mweinfo['head'] . '</em>? </label>
            <br/>
            <br/>
            <table class="radio-table">
              <tbody>
                <tr><td class="bigno" rowspan="2">NO</td> <td class="number">0</td> <td class="number">1</td> <td class="number">2</td> <td class="number">3</td> <td class="number">4</td> <td class="number">5</td> <td class="bigyes" rowspan="2">YES</td></tr>
                <tr>
                  <td class="tooltippy"><input id="questio11" name="Qhead" required="" type="radio" value="0" />
                        <div class="ttip">Absolutely not &mdash; ' . $mweinfo['undefdet_compound'] . ' <em>' . $mweinfo['compound'] . '</em> ' . $mweinfo['have'] . ' <u>nothing to do</u> with ' . $mweinfo['undefdet_head'] . ' <em>' . $mweinfo['head'] . '</em></div>
                        </td>
                        <td class="tooltippy"><input id="questio12" name="Qhead" required="" type="radio" value="1" />
                        <div class="ttip">No &mdash; I see only a <u>vague relation</u> between ' . $mweinfo['undefdet_compound'] . ' <em>' . $mweinfo['compound'] . '</em> and ' . $mweinfo['undefdet_head'] . ' <em>' . $mweinfo['head'] . '</em></div>
                        </td>
                        <td class="tooltippy"><input id="questio13" name="Qhead" required="" type="radio" value="2" />
                        <div class="ttip">Not really &mdash; the meaning of <em>' . $mweinfo['compound'] . '</em> is <u>associated</u> with <em>' . $mweinfo['head'] . '</em>, but only <u>indirectly</u></div>
                        </td>
                        <td class="tooltippy"><input id="questio14" name="Qhead" required="" type="radio" value="3" />
                        <div class="ttip">Sort of &mdash; the meaning of <em>' . $mweinfo['compound'] . '</em> is <u>directly associated</u> to <em>' . $mweinfo['head'] . '</em>, even if these meanings are not identical</div>
                        </td>
                        <td class="tooltippy"><input id="questio15" name="Qhead" required="" type="radio" value="4" />
                        <div class="ttip">Yes &mdash; ' . $mweinfo['undefdet_compound'] . ' <em>' . $mweinfo['compound'] . '</em> <u>' . $mweinfo['be'] . ' actually</u> ' . $mweinfo['undefdet_head'] . ' <em>' . $mweinfo['head'] . '</em>, for an uncommon sense of the word <em>' . $mweinfo['head'] . '</em></div>
                        </td>
                        <td class="tooltippy"><input id="questio16" name="Qhead" required="" type="radio" value="5" />
                        <div class="ttip">Exactly! &mdash; ' . $mweinfo['undefdet_compound'] . ' <em>' . $mweinfo['compound'] . '</em> ' . $mweinfo['be'] . ' <u>always literally</u> ' . $mweinfo['undefdet_head'] . ' <em>' . $mweinfo['head'] . '</em></div>
                        </td>
                </tr>
              </tbody>
            </table>
          </fieldset>

          <br/><!--=====================================================-->

          <fieldset>
            <label>5. In your opinion, is the meaning of '. $mweinfo['undefdet_compound'].' <em>'.$mweinfo['compound'].'</em> always literally related to '.$mweinfo['something_modifier'].' <em>'. $mweinfo['modifier'].'</em>? </label>
            <br/>
            <br/>
            <table class="radio-table">
              <tbody>
                <tr><td class="bigno" rowspan="2">NO</td> <td class="number">0</td> <td class="number">1</td> <td class="number">2</td> <td class="number">3</td> <td class="number">4</td> <td class="number">5</td> <td class="bigyes" rowspan="2">YES</td></tr>
                <tr>
                  	<td class="tooltippy"><input id="questio21" name="Qmodifier" required="" type="radio" value="0" />
                    <div class="ttip">Absolutely not &mdash; ' . $mweinfo['undefdet_compound'] . ' <em>' . $mweinfo['compound'] . '</em> ' . $mweinfo['have'] . ' <u>nothing</u> to do with ' . $mweinfo['something_modifier'] . ' <em>' . $mweinfo['modifier'] . '</em></div>
                    </td>
                    <td class="tooltippy"><input id="questio22" name="Qmodifier" required="" type="radio" value="1" />
                    <div class="ttip">No &mdash; I see only a <u>vague relation</u> between ' . $mweinfo['undefdet_compound'] . ' <em>' . $mweinfo['compound'] . '</em> and ' . $mweinfo['something_modifier'] . ' <em>' . $mweinfo['modifier'] . '</em></div>
                    </td>
                    <td class="tooltippy"><input id="questio23" name="Qmodifier" required="" type="radio" value="2" />
                    <div class="ttip">Not really &mdash; the meaning of <em>' . $mweinfo['compound'] . '</em> is <u>associated</u> with ' . $mweinfo['something_modifier'] . ' <em>' . $mweinfo['modifier'] . '</em>, but only <u>indirectly</u></div>
                    </td>
                    <td class="tooltippy"><input id="questio24" name="Qmodifier" required="" type="radio" value="3" />
                    <div class="ttip">Sort of &mdash; the meaning of <em>' . $mweinfo['compound'] . '</em> is <u>directly associated</u> with ' . $mweinfo['something_modifier'] . ' <em>' . $mweinfo['modifier'] . '</em>, even if these meanings are not identical</div>
                    </td>
                    <td class="tooltippy"><input id="questio25" name="Qmodifier" required="" type="radio" value="4" />
                    <div class="ttip">Yes &mdash; the expression <em>' . $mweinfo['compound'] . '</em> <u>actually</u> means ' . $mweinfo['something_modifier'] . ' <em>' . $mweinfo['modifier'] . '</em>, for an uncommon sense of the word <em>' . $mweinfo['modifier'] . '</em></div>
                    </td>
                    <td class="tooltippy"><input id="questio26" name="Qmodifier" required="" type="radio" value="5" />
                    <div class="ttip">Exactly! &mdash; the expression <em>' . $mweinfo['compound'] . '</em> <u>always literally</u> means ' . $mweinfo['something_modifier'] . ' <em>' . $mweinfo['modifier'] . '</em></div>
                    </td>
                </tr>
              </tbody>
            </table>
          </fieldset>

          <br/><!--=====================================================-->
          <fieldset>
            <label>6. Given your previous replies, would you say that ' . $mweinfo['undefdet_compound'] . ' <em>' . $mweinfo['compound'] . '</em> ' . $mweinfo['be'] . ' always literally ' . $mweinfo['undefdet_head'] . ' <em>' . $mweinfo['head'] . '</em> which ' . $mweinfo['be'] . ' ' . $mweinfo['relatedto_modifier'] . ' <em>' . $mweinfo['modifier'] . '</em>? </label>
            <br/>
            <br/>
            <table class="radio-table">
              <tbody>
                <tr><td class="bigno" rowspan="2">NO</td> <td class="number">0</td> <td class="number">1</td> <td class="number">2</td> <td class="number">3</td> <td class="number">4</td> <td class="number">5</td> <td class="bigyes" rowspan="2">YES</td></tr>
                <tr>
                  <td class="tooltippy"><input id="questio31" name="Qheadmodifier" required="" type="radio" value="0" />
                  <div class="ttip">Absolutely not &mdash; it <u>doesn&#39;t make any sense</u> to imagine ' . $mweinfo['undefdet_head'] . ' <em>' . $mweinfo['head'] . '</em> which ' . $mweinfo['be'] . ' ' . $mweinfo['relatedto_modifier'] . ' <em>' . $mweinfo['modifier'] . '</em></div>
                  </td>
                  <td class="tooltippy"><input id="questio32" name="Qheadmodifier" required="" type="radio" value="1" />
                  <div class="ttip">No &mdash; it is <u>weird</u> to imagine ' . $mweinfo['undefdet_head'] . ' <em>' . $mweinfo['head'] . '</em> which ' . $mweinfo['be'] . ' ' . $mweinfo['relatedto_modifier'] . ' <em>' . $mweinfo['modifier'] . '</em>, even if the meaning is understandable</div>
                  </td>
                  <td class="tooltippy"><input id="questio33" name="Qheadmodifier" required="" type="radio" value="2" />
                  <div class="ttip">Not really &mdash; the meaning of <em>' . $mweinfo['compound'] . '</em> is <u>associated</u> with ' . $mweinfo['undefdet_head'] . ' <em>' . $mweinfo['head'] . '</em> and with ' . $mweinfo['something_modifier'] . ' <em>' . $mweinfo['modifier'] . '</em>, but only in an indirect manner</div>
                  </td>
                  <td class="tooltippy"><input id="questio34" name="Qheadmodifier" required="" type="radio" value="3" />
                  <div class="ttip">Sort of &mdash; the meaning of <em>' . $mweinfo['compound'] . '</em> is <u>directly associated</u> with ' . $mweinfo['undefdet_head'] . ' <em>' . $mweinfo['head'] . '</em> and ' . $mweinfo['something_modifier'] . ' <em>' . $mweinfo['modifier'] . '</em>, even if these meanings are not identical</div>
                  </td>
                  <td class="tooltippy"><input id="questio35" name="Qheadmodifier" required="" type="radio" value="4" />
                  <div class="ttip">Yes &mdash; ' . $mweinfo['undefdet_compound'] . ' <em>' . $mweinfo['compound'] . '</em> <u>' . $mweinfo['be'] . ' actually</u> ' . $mweinfo['undefdet_head'] . ' <em>' . $mweinfo['head'] . '</em> which ' . $mweinfo['be'] . ' ' . $mweinfo['relatedto_modifier'] . ' <em>' . $mweinfo['modifier'] . '</em></div>
                  </td>
                  <td class="tooltippy"><input id="questio36" name="Qheadmodifier" required="" type="radio" value="5" />
                  <div class="ttip">Exactly! &mdash; ' . $mweinfo['undefdet_compound'] . ' <em>' . $mweinfo['compound'] . '</em> ' . $mweinfo['be'] . ' <u>always literally</u> ' . $mweinfo['undefdet_head'] . ' <em>' . $mweinfo['head'] . '</em> which ' . $mweinfo['be'] . ' ' . $mweinfo['relatedto_modifier'] . ' <em>' . $mweinfo['modifier'] . '</em></div>
                  </td>
                </tr>
              </tbody>
            </table>
          </fieldset>

          <br/><!--=====================================================-->
          <fieldset>
          <label>7. Now, please consider a literal interpretation of <em>' . $mweinfo['compound'] . '</em>. How plausible is it that <em>' . $mweinfo['compound'] . '</em> might be used literally? </label>
          <br/>
          <br/>
          <table class="radio-table">
            <tbody>
            <tr><td class="bigno" rowspan="2">IMPLAUSIBLE</td> <td class="number">0</td> <td class="number">1</td> <td class="number">2</td> <td class="number">3</td> <td class="number">4</td> <td class="number">5</td> <td class="bigyes" rowspan="2">PLAUSIBLE</td></tr>
            <tr>
            <td class="tooltippy"><input id="questio37" name="Qliterality" required="" type="radio" value="0" />
            <div class="ttip">Completely implausible &mdash; no possible literal interpretation of <em>'. $mweinfo['compound'] . '</em> exists.</div>
            </td>
            <td class="tooltippy"><input id="questio38" name="Qliterality" required="" type="radio" value="1" />
            <div class="ttip">Implausible &mdash; it is very difficult to imagine a literal interpretation of <em>'. $mweinfo['compound'] . '</em>.</div>
            </td>
            <td class="tooltippy"><input id="questio39" name="Qliterality" required="" type="radio" value="2" />
            <div class="ttip">Somewhat implausible &mdash; I can imagine a literal intepretation of <em>'. $mweinfo['compound'] . '</em>, but I don&#39;t think anyone would ever use it.</div>
            </td>
            <td class="tooltippy"><input id="questio40" name="Qliterality" required="" type="radio" value="3" />
            <div class="ttip">Somewhat plausible &mdash; the literal interpretation of <em>'. $mweinfo['compound'] . '</em> is clear, but it&#39;s difficult to imagine it being used.</div>
            </td>
            <td class="tooltippy"><input id="questio41" name="Qliterality" required="" type="radio" value="4" />
            <div class="ttip">Plausible &mdash; the literal interpretation of <em>'. $mweinfo['compound'] . '</em> is clear, and I can imagine it being used, albeit infrequently.</div>
            </td>
            <td class="tooltippy"><input id="questio42" name="Qliterality" required="" type="radio" value="5" />
            <div class="ttip">Very plausible &mdash; the literal interpretation of <em>'. $mweinfo['compound'] . '</em> is clear, and I can readily imagine it being used.</div>
            </td>
            </tr>
            </tbody>
          </table>
        </fieldset>

          <fieldset>
            <label>You can type here any comments you may have (optional): </label><br/>
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
