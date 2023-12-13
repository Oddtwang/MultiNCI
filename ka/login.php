<?php
require_once 'db.php';
?>

<html lang="ka">
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf8">
		<title>ქართული სახელური შესიტყვებების ინტერპრეტაცია</title>
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
        <h1>ქართული სახელური შესიტყვებების ინტერპრეტაცია</h1>
          <br/>
          	<?php
            if( isset($anno)){
              echo '<h3 style="color:red">რეგისტრაცია '.$anno.' დადასტურებული არ არის.</h2>';
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
                      echo '<h3><span style="color:red">შემთხვევითი რეგისტრაცია შეუძლებელია. დარწმუნებული ხართ, რომ არ გაქვთ ანგარიში?</span></h3>';
                      unset($annotid);
                    }
                    else {
                      echo '<h3>თქვენი კოდია <span style="color:red">'.$annotid.'</span> - გთხოვთ, შეინახოთ, რადგანაც დასტურის წერილს მეილზე არ მიიღებთ.</h2>';
											$stmt = $pdo->prepare("INSERT INTO $tbl (anotador, name) VALUES (:anno, :uname)");
											$result = $stmt->execute(array(':anno' => $annotid, ':uname' => $uname));
                    }
                }
                else{
                  echo '<h3 style="color:red">კოდი არასწორია. <br/>გთხოვთ, გადაამოწმოთ მოწვევის წერილში.</h2>';
                }
            }

	        ?>

          <br/>
          <h4>შემოსული ვარ:</h4>
          <form class="col-lg-12" method="POST">
            <div class="input-group" style="width:340px;text-align:center;margin:0 auto;">
            <input class="form-control input-lg" title="თქვენი მონაცემების გაზიარება მესამე პირთათვის არ მოხდება." placeholder="სისტემაში შესვლა" type="text" name="annotator" <?php echo (isset($annotid)?'value="'.$annotid.'"':''); ?> >
              <span class="input-group-btn">
		<button class="btn btn-lg btn-primary" type="submit" name="ok" value="ok">სისტემაში შესვლა</button></span>
            </div>
          </form>
        </div>

      </div> <!-- /row -->

</div>

<div class="container">
	<br/>
	<br/>
	<center><h4>კოდი ჯერ არ მაქვს:</h4></center>

<h2>1. წაიკითხეთ ინსტრუქციები</h2>

<ul>
 <li>წაიკითხეთ გამოთქმები ქართულად და შეაფასეთ შეესაბამება თუ არა გამოთქმაში წარმოდგენილი სიტყვების ცალკეული მნიშვნელობები გამოთქმის მთლიან მნიშვნელობას.</li>
  <li>საინტერესოა თუ, როგორ განმარტავს ქართულად მოსაუბრე ადამიანი აღნიშნულ გამოთქმებს ჩვეულებრივ ყოველდღიურ მეტყველებაში. თითოეული გამოთქმის მნიშვნელობის გასაგებად, მოგიწევთ 3 წინადადების წაკითხვა. თუ გამოთქმის მნიშვნელობა არ გესმით, უბრალოდ გამოტოვეთ.<li>
  <li>თუ გამოთქმას ერთზე მეტი მნიშვნელობა აქვს, <em>მხოლოდ</em> ის მნიშვნელობა განმარტეთ, რომელსაც სამაგალითო წინადადებაში ნახავთ.</li>
  <li>იმის გათვალისწინებით, რომ წინადადებები გადმოწერილია ინტერნეტიდან, ზოგიერთ წინადადებაში შეიძლება იყოს გრამატიკული შეცდომები. ამიტომაც, მსგავს უზუსტობებს ყურადღებას ნუ მიაქცევთ. </li>
  <li>გაეცით 2 შეკითხვას პასუხი გამოთქმის ცალკეული სიტყვების მნიშვნელობების შესახებ. თითოეულ კითხვაზე პასუხის გაცემისას, უბრალოდ, იმ სიტყვას დააწკაპუნეთ, რომელიც, თქვენი აზრით, განაპირობებს გამოთქმის მნიშვნელობას სკალაზე 0-დან (<em>არა, საერთოდ არ განაპირობებს</em>) 5-მდე  (<em>დიახ, მთლიანად განაპირობებს</em>). შუალედური მნიშვნელობები შეიძლება გამოიყენებოდეს ნიუანსების გადმოსაცემად. </li>
  <li>ბოლოს საჭირო იქნება გამოთქმის მნიშვნელობის შესაბამისი ალტერნატიული სინონიმ(ებ)ის შემოთავაზება, რომლ(ებ)ის გამოყენება შესაძლებელია გამოთქმის ნაცვლად და რომელ(ებ)საც მსგავსი მნიშვნელობა ექნება(თ). </li>
  <li>მეტისმეტად ნუ ჩაუღრმავდებით თითოეული გამოთქმის მნიშვნელობას, რადგან სწორი ან არასწორი პასუხი არ არსებობს. ხოლო ტესტი არ ემსახურება მეხსიერების ან ინტელექტის შემოწმებას. ერთადერთი რაც გვჭირდება - გამოთქმის მნიშვნელობის ამოცნობა და მისი ინტერპრეტაციის შესწავლა.</li>
  <li>თუ მუშაობის დროს რაიმე პრობლემას წააწყდებით, შეტყობინებას მიიღებთ ან მოგინდებათ კომენტარის დართვა, ნუ მოგერიდებათ კომენტარი გვერდის ბოლოს დაურთეთ. </li>
</ul>

<!----------------------------------------------------------------------------->
<hl/>

<h2>2. წაიკითხეთ მაგალითები</h2>

  <h2>თავებზე მონადირე</h2> <!-- head hunter -->

<strong>წინადადება: </strong> <em>ისეთი <u>თავებზე მონადირე</u> არ ვარ, რომელიც მხრებს თავს გასართობად ან გამორჩენის მიზნით წააცლის.</em>
<br/>
<strong>კითხვა: </strong>შეიყვანეთ <em>თავებზე მონადირის</em> 3 სინონიმი.
<br/>
<strong>მოსალოდნელი პასუხი: </strong>
<ul>
  <li><em>მკვლელი</em></li>
  <li><em>ვინც მტრებზე ნადირობს</em></li>
  <li><em>ბოროტმოქმედი</em></li>
</ul>
<br/>
<strong>განმარტება</strong>: თავებზე მონადირე საზოგადოების წევრია, რომელიც მტრებს კლავს და მტრების თავებს აგროვებს. შემოთავაზებულ ვარიანტებს მსგავსი მნიშვნელობა აქვთ.

  <h2>ტვინების გადინება</h2> <!-- brain drain -->
<strong>წინადადება:</strong> <em>ამას ჰქვია <u>ტვინების გადინება	</u> და ერის გამოტვინება.</em>
<br/>
<strong>კითხვა:</strong> უკავშირდება თუ არა <em>ტვინების გადინება</em> სიტყვასიტყვით <em>ტვინს</em> ?
<br/>
<strong>მოსალოდნელი პასუხი: </strong> <img src="img/answer-0.png"> <!-- 0 -->
<br/>
<strong>განმარტება</strong>: <em>ტვინების გადინება</em>	ეწოდება წარმატებული მეცნიერების, სტუდენტებისა და პროფესიონალების ემიგრაციას. გამოთქმის მნიშვნელობა არ უკავშირდება ტვინს ან დინებას.

<!----------------------------------------------------------------------------->

  <h2>სარაკეტო მეცნიერება</h2> <!-- rocket science -->

<strong>წინადადება: </strong> <em>ეს არ არის <u>სარაკეტო მეცნიერება</u>, თუ მათ სურთ გახდნენ ევროპული ქვეყნის ჯგუფის წევრები, ამისთვის საჭიროა თავისუფალი ხალხი, რომელსაც აქვს წვდომა ისეთ თავისუფლებებზე, როგორიცაა პრესისი თავისუფლება, შეკრების თავისუფლება, სიტყვის თავისუფლებაა. </em>
<br/>
<strong>კითხვა:</strong>  <em>სარაკეტო მეცნიერება</em>  არის თუ არა რეალურად <em>მეცნიერება</em> ?
<br/>
<strong>მოსალოდნელი პასუხი: </strong> <img src="img/answer-1.png"> <!-- 1 -->
<br/>
<strong>განმარტება</strong>: გამოიყენება იმ შემთხვევაში, როცა რაღაც არ ჩანს იმდენად რთული გაკეთების ან გაგების თვალსაზრისით. არ უკავშირდება არც სამეცნიერო საქმიანობას და არც რაკეტებს. 

<!----------------------------------------------------------------------------->

  <h2>კლიმატის ცვლილება</h2> <!-- climate change -->

<strong>წინადადება: </strong> <em>თავდაპირველად ამ სამიტის თემა <u>კლიმატის ცვლილება</u> იყო.</em>
<br/>
<strong>კითხვა: </strong> ნიშნავს თუ არა <em>კლიმატის ცვლილება</em> სიტყვასიტყვით <em>გარემოს</em> <em>ცვლილებას</em> ?
<br/>
<strong>მოსალოდნელი პასუხი: </strong> <img src="img/answer-5.png"> <!-- 5 -->
<br/>
<strong>განმარტება</strong>: კლიმატის ცვლილება ნიშნავს ტემპერატურისა და ამინდის გრძელვადიან ცვლას.

<!----------------------------------------------------------------------------->
<hl/>

          <h2>3. შეავსეთ სარეგისტრაციო ფორმა</h2>
          <strong>შენიშვნა</strong>: უსაფრთხოების მიზნით, მოერიდეთ მომხმარებლის სახელის განმეორებით სხვაგან გამოყენებას.
      <div class="row">
        <div class="col-lg-12 text-center v-center">
          <form class="col-lg-12" method="POST">
            <div class="input-group" style="width:380px;text-align:center;margin:0 auto;">
            <!-- <input required class="form-control input-lg" title="ჩაწერეთ სახელი" placeholder="სახელი (მაგ. ჯონი)" type="text" name="name"/> -->
            <!-- <input required class="form-control input-lg" title="ჩაწერეთ გვარი" placeholder="გვარი (მაგ. სმიტი)" type="text" name="surname"/> -->
            <!-- <input required class="form-control input-lg" title="ჩაწერეთ ასაკი" placeholder="ასაკი (მაგ. 25)" type="text" name="age"> -->
            <!-- <input required class="form-control input-lg" title="რომელი ქვეყნიდან ხართ?" placeholder="ქვეყანა (მაგ. დიდი ბრიტანეთი, ბულგარეთი...)" type="text" name="country"> -->
            <input required class="form-control input-lg" title="სასურველი მომხმარებლის სახელი" placeholder="მომხმარებლის სახელი" type="text" name="name"/>
            <input required class="form-control input-lg" title="კოდური სიტყვა" placeholder="საიდუმლო კოდური სიტყვა, რომელიც მიღებული გაქვთ ელ. ფოსტით" type="text" name="passphrase">
            <!-- <p><input required class="" title="თუ ამ კრიტერიუმს არ აკმაყოფილებთ, გთხოვთ, ნუ მიიღებთ ექსპერიმენტში მონაწილეობას" type="checkbox" name="native"/> ვადასტურებ, რომ ვფლობ ქართულს როგორც მშობლიურს.</p> -->
		        <button class="btn btn-lg btn-primary" type="submit" name="create_id" value="create_id">რეგისტრაციის გავლა</button>
            </div>
          </form>
        </div>

      </div> <!-- /row -->

</div>

	</body>
</html>
