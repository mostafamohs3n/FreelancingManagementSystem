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
$freelancer = new Freelancer();
echo "<br>";
//TO DO CHECK IF THE PROPOSAL IS ALREADY SENT.
if($Misc->fieldsBlank($_POST['proposalMessage'], $_POST['jobPrice'], $_POST['jobHourAmount'], $_POST['milestoneTitle'], $_POST['milestoneDate'], $_POST['milestonePrice'], $_POST['jobID'])){
	$Misc->displayErrorMsg('emptyfields', "#");
}else{
	$freelancerID = $freelancer->getUserFreelancerID($_SESSION['userID']);
	$jobID = $Misc->escape_string((int)$_POST['jobID']);
	$proposalMessage = $Misc->escape_string($_POST['proposalMessage']);
	$jobPrice = $Misc->escape_string((int) preg_replace('/[^0-9]/','',$_POST['jobPrice']));
	$jobHourAmount = $Misc->escape_string($_POST['jobHourAmount']);
	$milestoneTitle = $Misc->escape_string($_POST['milestoneTitle']);
	$milestoneDate = $Misc->escape_string($_POST['milestoneDate']);
	$milestonePrice = $Misc->escape_string($_POST['milestonePrice']);
	$milestones = json_encode([$milestoneTitle, $milestoneDate, $milestonePrice]);
	$proposal = new Proposal;
	if($proposal->proposalExists($freelancerID, $jobID)){
		$Misc->displayErrorMsg('proposalexists');
	}else{
		$proposal->populateObject($freelancerID, $jobID, $jobPrice, $milestones, $proposalMessage, $jobHourAmount, 0, 0, 1);
		// (new Client)->notifyClientwProposal($jobID, $freelancerID);
		if($proposal->addProposal()){
			$Misc->displaySuccessMsg('proposalSent');
			$proposal->notifyClientwProposal($jobID, $freelancerID, $proposalMessage);
				
			// added by mohamed			
			$user = new User;
			$job = new Job;
			$job = $job->getJob((int)$_POST['jobID']);
			$client = new Client;
			$res = $client->getClient($job->getClient_id());
			$msg = $_SESSION['email'].' add new proposal on your job called '.$job->getTitle();
			echo $user->notify($_SESSION['userID'],$res['user_id'],$msg);
		
		}else{
			$Misc->displayErrorMsg('noaction', "#");
		}
	}

}
?>
	
	

