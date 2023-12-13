<?php
    // Landing page - language selection dropdown, redirecting to the appropriate subfolder & scripts
?>

<html lang="en">
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8">
		<title>Noun compound interpretation</title>
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
    <!-- Selection form - could populate from database but not worth the effort! ><!-->
    <h4>Select language:</h4>
    <h4>შეარჩიეთ ენა:</h4>
    <h4>Selecteaza limba:</h4>


    <form action="" method="post">
        <select name="formLanguage">
            <option value = "">Select...</option>
            <option value = "en">English</option>
            <option value = "ka">ქართული ენა</option>
            <option value = "ro">limba română</option>
        </select>
        <input type="submit" name="formSubmit" value="OK" />
    </form>

    <?php
    // Check valid selection on form/button submission
    if(isset($_POST['formSubmit']))
    {
        $langSelected = $_POST['formLanguage'];
        $errorMessage = "";

        // Redirect to appropriate page
        chdir($langSelected);
        echo("Selected " . $langSelected);
        header("Location: " . $langSelected . "/" . $langSelected . ".php"); exit;
    }

    if(!isset($_POST['formLanguage'])) 
    {
    $errorMessage .= "<li>Please select a language.</li>";
    }
    ?>


    </body>
</html>


