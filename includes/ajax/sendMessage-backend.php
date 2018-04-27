<?php 
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
if(!isset($_SESSION['loggedIn']))
	$_SESSION['loggedIn'] = false;
require_once('../config.inc.php');

$classes= ['Core','Mail', 'User', 'Client', 'Contract',  'Feedback', 'Freelancer', 'Job', 'Proposal', 'Misc'];

foreach($classes as $class){
	include_once("../../classes/$class.php");
}

$core = new Core($Config["Host"], $Config["User"], "", $Config["dbName"]);
$Misc = Misc::Singleton(); // the only instance we need! YES. THIS IS OUR SINGLETON!
if(!$core)exit;

// print_r($_POST);
	if($Misc->fieldsBlank($_POST['message'], $_POST['FreelancerUserID'], $_POST['clientUserID'], $_POST['jobID'], $_POST['proposalID'])){
		$Misc->displayErrorMsg('emptyfields');
	}else{
		$msg = $Misc->escape_string($_POST['message']);
		$FID = $Misc->escape_string((int)$_POST['FreelancerUserID']);
		$CID = $Misc->escape_string((int)$_POST['clientUserID']);
		$JID = $Misc->escape_string((int)$_POST['jobID']);
		$PID = $Misc->escape_string((int)$_POST['proposalID']);

		$user = new User;
		if($user->sendMsg($CID, $FID, $msg, $JID, $PID)){
			$Misc->displaySuccessMsg('msgSent');
		}else{
			$Misc->displayErrorMsg('msgnotsent', '');
		}

	}
	

?>
	
	

