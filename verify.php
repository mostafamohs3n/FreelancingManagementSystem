<?php 
$pageTitle ="Verify User";
include("includes/header.inc.php"); 
echo "<div class=\"container\">";
if(!isset($_GET['key']) || !isset($_GET['secret'])) {exit;}
else{
	echo "<h1>Verify your account</h1>";
	$email = $Misc->escape_string(stripslashes($_GET['secret']));
	$key = $Misc->escape_string(stripslashes($_GET['key']));
	$user = new User;
	if($user->isVerifiable($email)){
		if($user->verifyUser($email, $key)){
			$Misc->displaySuccessMsg('verified');
			header( "refresh:5;url=index.php" );
			exit;
		}else{
			$Misc->displayErrorMsg('usercantverify');
		}
	}else{
			$Misc->displayWarningMsg("You have already been verified. or you have followed the wrong link.<br>Please try logging in or check your email again!");
	}
}
echo "</div>";
include("includes/footer.inc.php"); 