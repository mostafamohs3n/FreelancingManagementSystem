<?php
$pageTitle = 'Contract';
include("includes/header.inc.php");
if((!isset($_SESSION['userType']))) exit;
$userType = $_SESSION['userType'];
echo "<div class=\"container\">";
if($userType == 'freelancer'){
	if(!isset($_GET['jobid']) || !isset($_GET['contractid'])){
		$freelancerID = (new Freelancer)->getUserFreelancerID($_SESSION['userID']);
		$contracts = (new Contract)->getFreelancerContracts($freelancerID);
		$job = new Job;
		foreach($contracts as $contract){
			//print_r($contract);
			$jobDesc = $job->getJobByContract($contract['contract_id'])[0];
			$jobSkills = explode(",", $jobDesc['skills_needed']);
			echo '<div class="thumbnail material-card">
  <div class="material-card__content">
  <h3 class="material-card__title">'.$jobDesc['title'].'</h3>
  <p>'.$jobDesc['description'].'</p>
  <hr />';
  foreach($jobSkills as $skill){
	echo'<span class="label label-default material-labe">' .$skill . '</span> ';
  }
			 	
  echo '<br><br>
  <span class="label label-info material-label material-label_info">$'.$jobDesc['price'] . '</span>
				  <span class="label label-warning material-label material-label_warning">';
				  echo ($jobDesc['payment_type'] == 0) ? 'Fixed' : 'Hourly'; echo' Price</span>
 </div>
 <footer class="material-card__footer">
  <a href="contract.php?jobid='.$jobDesc['job_id'].'&contractid='.$contract['contract_id'].'" class="btn material-btn material-btn_primary material-btn_sm material-card__showmore material-card__showmore_pos-right">
  Show Contract
  </a>
  </footer>
</div>';
		}



	}else{
		if(isset($_GET['jobid']) && isset($_GET['contractid'])){
			$jobID = (int) $Misc->escape_string($_GET['jobid']);
			$contractID = (int) $Misc->escape_string($_GET['contractid']);
			$contract = new Contract;
			$job = new Job;
			$contracts = $contract->getFreelancerContracts((new Freelancer)->getUserFreelancerID($_SESSION['userID']));
			if(!$Misc->isFreelancerContractExist($contractID, $contracts)){$Misc->displayErrorMsg('noaction');exit;}
			$currentContract = $contract->getContract($contractID)[0];
			$currentJob = $job->getJob($jobID);
			$clientID = $currentContract['client_id'];
			
			$totalWorkedHours = 0;
			$totalWorkedHours = $currentContract['total_hours_worked'] ? $currentContract['total_hours_worked'] : 0;
			$lastWorkingDate = $currentContract['start_working_date'] ? $currentContract['start_working_date'] : 'N/A';
			?>

<div class="panel panel-default material-panel material-panel_primary">
 <h5 class="panel-heading material-panel__heading">Contract: <?=$currentJob->getTitle();?></h5>
 <div class="panel-body material-panel__body"><b>Job Description:</b> <?=$currentJob->getDescription();?><br>
 	<b>Skills needed:</b> <?=$currentJob->getSkills_needed();?><br>
 	<b>Contract Start Date:</b> <?=$currentContract['start_date']?><br>
 	<b>Contract Status:</b> <?=($currentContract['is_finished'] == 0 ? "In progress" : "Finished");?><br>
 	<b>Total Hours Worked:</b> <?=$totalWorkedHours;?><br>
 	<b>Last Working Date:</b> <?= $lastWorkingDate; ?><br>
 	<br>
 	<form class="contract-form">
 	<input type="hidden" value="<?=$contractID;?>" name="contractID">
 	<input type="hidden" value="<?=$currentJob->getJob_id();?>" name="jobID">
 	</form>
 	<?php if($contract->isEndedContract($clientID, $contractID)){ ?>
	<button class="btn material-btn material-btn_lg material-btn_<?=$contract->getCounterStatus($contractID) ? "danger" : "success";?>" id="toggleCounter"><?=$contract->getCounterStatus($contractID) ? "Stop" : "Start";?> Counter</button>
	<div class="form-response"></div>
	<?php }else{ ?>
	<button disabled class="btn material-btn material-btn_md" id="endContract">Contract Ended</button>
	<?php } ?>
 </div>
</div>
<script>
	$( document ).ready(function() {
		$("#toggleCounter").on('click', function(){
			 $.ajax({
		        url: "includes/ajax/toggleCounter-backend.php",
		        type: "POST",
		        data: $('.contract-form').serialize() ,
		        success: function (response) {
		           $(".form-response").html(response);
		          location.reload();
		        }

		    });
		});
		});
</script>

			<?php
		}


	}


echo "</div>";
}elseif($userType =='client'){
	if(!isset($_GET['pid']) && !isset($_GET['jobid'])){
		$client = new Client;
		$clientID = $client->getUserClientID($_SESSION['userID']);
		$contracts = (new Contract)->getClientContracts($clientID);
		$job = new Job;
		foreach($contracts as $contract){
			//print_r($contract);
			$jobDesc = $job->getJobByContract($contract['contract_id'])[0];
			$jobSkills = explode(",", $jobDesc['skills_needed']);
			echo '<div class="thumbnail material-card">
  <div class="material-card__content">
  <h3 class="material-card__title">'.$jobDesc['title'].'</h3>
  <p>'.$jobDesc['description'].'</p>
  <hr />';
  foreach($jobSkills as $skill){
	echo'<span class="label label-default material-labe">' .$skill . '</span> ';
  }
			 	
  echo '<br><br>
  <span class="label label-info material-label material-label_info">$'.$jobDesc['price'] . '</span>
				  <span class="label label-warning material-label material-label_warning">';
				  echo ($jobDesc['payment_type'] == 0) ? 'Fixed' : 'Hourly'; echo' Price</span>
 </div>
 <footer class="material-card__footer">
  <a href="contract.php?jobid='.$jobDesc['job_id'].'&contractid='.$contract['contract_id'].'&show=1" class="btn material-btn material-btn_primary material-btn_sm material-card__showmore material-card__showmore_pos-right">
  Show Contract
  </a>
  </footer>
</div>';
		}

	}elseif(isset($_GET['show']) && isset($_GET['jobid']) && isset($_GET['contractid'])){
				$jobID = (int) $Misc->escape_string($_GET['jobid']);
			$contractID = (int) $Misc->escape_string($_GET['contractid']);
			$contract = new Contract;
			$job = new Job;
			$currentContract = $contract->getContract($contractID)[0];
			$currentJob = $job->getJob($jobID);
			// var_dump($currentContract);
			$totalWorkedHours = 0;
			$totalWorkedHours = $currentContract['total_hours_worked'] ? $currentContract['total_hours_worked'] : 0;
			$lastWorkingDate = $currentContract['start_working_date'] ? $currentContract['start_working_date'] : 'N/A';
				?>
				<div class="panel panel-default material-panel material-panel_primary">
 <h5 class="panel-heading material-panel__heading">Contract: <?=$currentJob->getTitle();?></h5>
 <div class="panel-body material-panel__body"><b>Job Description:</b> <?=$currentJob->getDescription();?><br>
 	<b>Skills needed:</b> <?=$currentJob->getSkills_needed();?><br>
 	<b>Contract Start Date:</b> <?=$currentContract['start_date']?><br>
 	<b>Contract Status:</b> <?=($currentContract['is_finished'] == 0 ? "In progress" : "Finished");?><br>
 	<b>Total Hours Worked:</b> <?=$totalWorkedHours;?><br>
 	<b>Last Working Date:</b> <?= $lastWorkingDate; ?><br>
 	<br>
 	<form class="contract-form">
 	<input type="hidden" value="<?=$contractID;?>" name="contractID">
 	<input type="hidden" value="<?=$currentJob->getJob_id();?>" name="jobID">
 	</form>
 	<?php
 	$clientID = (new Client)->getUserClientID($_SESSION['userID']);
 	if(!$contract->isEndedContract($clientID, $contractID)){ ?>
	<button class="btn material-btn material-btn_lg material-btn_success" id="endContract">End Contract</button>
	<?php }else{ ?>
	<button disabled class="btn material-btn material-btn_md" id="endContract">Contract Ended</button>
	<?php }?>
	<div class="form-response"></div>
 </div>
</div>
<script>
	$( document ).ready(function() {
		$("#endContract").on('click', function(){
			 $.ajax({
		        url: "includes/ajax/endContract-backend.php",
		        type: "POST",
		        data: $('.contract-form').serialize() ,
		        success: function (response) {
		           $(".form-response").html(response);
		           // location.reload();
		        }

		    });
		});
		});
</script>
<?php
	}elseif(isset($_GET['pid']) && isset($_GET['jobid']) && !isset($_GET['show'])){
?>

<?php
	$pID = $_GET['pid'];
	$jobID = $_GET['jobid'];
	$proposalObj = new Proposal;
	$freelancerObj = new Freelancer;
	$jobObj = new Job;
	$proposal = $proposalObj->getProposal($pID);
	$checkHired = $proposal->getIsHired();
	$freelancer = $freelancerObj->getFreelancer($proposal->getfID());
	$userObj = new User;
	$userID = $freelancerObj->getFreelancerUserID($proposal->getfID());
	$user = $userObj->getUserByID($userID);
	$userName = $user->getName();
	$job = $jobObj->getJob($jobID);
	$dueDate = $job->getDueDate();
	$startDate = date('Y-m-d h:i:s');
	$price = $job->getPrice();
	$hourAmount = $job->getHour_amount();
?>

<div class="container" id="contract-container">
	<h1>Contract</h1>
	<!-- Propeller textbox -->
	<link href="http://propeller.in/components/textfield/css/textfield.css" type="text/css" rel="stylesheet"/>
	<link href="assets/css/select2.min.css" rel="stylesheet">
	<link href="assets/css/select2-bootstrap.css" rel="stylesheet">
	<link href="assets/css/pmd-select2.css" rel="stylesheet">
	<!-- Select2 js-->
	<script type="text/javascript" src="assets/js/select2.full.js"></script>
	<script type="text/javascript" src="assets/js/pmd-select2.js"></script>
	<script src="assets/js/bootstrap-datepicker.min.js"></script>

	<?php if(!isset($_POST['startContract']) && !$checkHired){?>
		<form method="POST" id="contract-form" action="contract.php?pid=<?php echo $pID?>&jobid=<?php echo $jobID?>">
			<!-- Freelancer name -->
			<div class="form-group">
				<label for="freelancerId">Freelancer Name</label>
				<div class="form-group materail-input-block">
				 	<input class="form-control materail-input" value="<?php echo $userName?>" placeholder="Freelancer name" disabled >
				 	<span class="materail-input-block__line"></span>
				</div>
			</div>

			<!-- Due date -->
			<div class="form-group">
				<label for="dueDate">Due Date</label>
				<div class="form-group materail-input-block">
				 	<input class="form-control materail-input" value="<?php echo $dueDate ?>" placeholder="Due Date" disabled>
				 	<span class="materail-input-block__line"></span>
				</div>
			</div>
		
			<!-- Start date -->
			<div class="form-group">
				<label for="startDate">Start Date</label>
				<div class="form-group materail-input-block">
				 	<input class="form-control materail-input" value="<?php echo $startDate; ?>" placeholder="Start Date" disabled >
				 	<span class="materail-input-block__line"></span>
				</div>
			</div>

			<!-- Price -->
			<div class="form-group">
				<label for="price">Price</label><br>
				<span class="label label-info material-label material-label_info"> <?php if($hourAmount > 0) echo '$' . $price . '/' . $hourAmount . 'h'; else '$' . $price; ?> </span>
			</div>			
			<!-- Start Contract -->
			<div class="text-center">
				<input type="submit" name="startContract" value="Start" class="btn material-btn material-btn_success material-btn_lg">
			</div>
		</form>
	<?php }else{
			$contract = new Contract;
			$contract->populateObject($job->getClient_id(), $pID, $startDate, 0, NULL, 0);
			if(!$checkHired){
				if($contract->addContract()){
					$Misc->displaySuccessMsg('createContract');
					$proposalObj = new Proposal;
					$proposal = $proposalObj->getProposal($_GET['pid']);
					$propID = $proposal->getpropID();
					$proposal->setHired($propID);
				}
				//header('Location: index.php', 10);
		}else{
				$Misc->displayErrorMsg('noaction');

				
			
			}
	}

	?>
	<?php }} ?>