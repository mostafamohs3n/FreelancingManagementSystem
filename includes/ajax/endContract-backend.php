<?php 
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
if(!isset($_SESSION['loggedIn']))
	$_SESSION['loggedIn'] = false;
require_once('../config.inc.php');

$classes= ['Core','Mail', 'User', 'Client', 'Contract',  'Feedback', 'Freelancer', 'Job', 'Proposal', 'Misc', 'pdf'];

foreach($classes as $class){
	include_once("../../classes/$class.php");
}

$core = new Core($Config["Host"], $Config["User"], "", $Config["dbName"]);
$Misc = Misc::Singleton(); // the only instance we need! YES. THIS IS OUR SINGLETON!
if(!$core)exit;
if($Misc->fieldsBlank($_POST['contractID'], $_POST['jobID'])){
	$Misc->displayErrorMsg('emptyfields');
}else{
	$contractID = $Misc->escape_string($_POST['contractID']);
	$jobID = $Misc->escape_string($_POST['jobID']);
	$contract = new Contract;
	$clientID = (new Client)->getUserClientID($_SESSION['userID']);
	$user = new User;
	$user = $user->getUserByID($_SESSION['userID']);
	if(!$contract->isEndedContract($clientID, $contractID) && $contract->endContract($clientID, $contractID)){
		$thisContract = $contract->getContract($contractID);
		$jobDesc = (new Job)->getJobByContract($thisContract[0]['contract_id'])[0];
		/*sendPDF(
			$Config['WebsiteName'],
			'180426021055download.jpg',
			$user->getEmail(),
			$user->getName(),
			$user->getDateOfBirth(),
			$user->getCountry(),
			$jobDesc[2],
			$jobDesc[3],
			$jobDesc[4],
			$jobDesc[5]
		);
		*/
		//echo "<script>location.href='pdfoutput.php';</script>";
		$Misc->displaySuccessMsg('contractEnded');
	}else{
		 $Misc->displayErrorMsg('noaction');
	}

}
	
	

?>
	
	

