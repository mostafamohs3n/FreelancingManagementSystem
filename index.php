<?php
	$pageTitle = "Home";
	include("includes/header.inc.php"); 
	if(!$_SESSION['loggedIn']){
	include("includes/home.inc.php"); 
	}else{
		echo "<div class=\"container\">";
		$userType = $_SESSION['userType'];
		$userEmail = $_SESSION['email'];
		if($userType == 'freelancer'){
			// echo "Hello, Freelancer: $userEmail";
			include("includes/freelancer.inc.php");

		}elseif($userType =='client'){
			// echo "Hello, Client: $userEmail";
			include("includes/client.inc.php");
		}
		echo "</div>";
	}
    include("includes/footer.inc.php"); 
?>
    