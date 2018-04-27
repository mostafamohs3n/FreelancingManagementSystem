<?php
$pageTitle = 'Contract';
include("includes/header.inc.php");
if((!isset($_GET['pid']) && !isset($_GET['jobid'])) || (!isset($_SESSION['userType']) && !($_SESSION['userType']=='client'))) exit;
?>

<?php
	$pID = $_GET['pid'];
	$jobID = $_GET['jobid'];
	//$pID = 5;
	//$jobID = 17;
	$proposalObj = new Proposal;
	$freelancerObj = new Freelancer;
	$jobObj = new Job;
	$proposal = $proposalObj->getProposal($pID);
	$freelancer = $freelancerObj->getFreelancer($proposal->getfID());
	$freelancerName = $freelancer->getUsername();
	echo $freelancerName;
	$job = $jobObj->getJob($jobID);
	$dueDate = $job->getDueDate();
	$startDate = date('Y-m-d');
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

	<?php if(!isset($_POST['startContract'])){?>
		<form method="POST" action="startContract.php?pid=<?php echo $pID?>&jobid=<?php echo $jobID?>">
			<!-- Freelancer name -->
			<div class="form-group">
				<label for="freelancerId">Freelancer Name</label>
				<div class="form-group materail-input-block">
				 	<input class="form-control materail-input" value="<?php echo$freelancerName?>" placeholder="Freelancer name" disabled >
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
			echo 'Hello';
			$contract = new Contract;
			$contract->populateObject($job->getClient_id(), $pID, $startDate, 0, $startDate, 312);
			if($contract->addContract()){
				$Misc->displaySuccessMsg('createContract');
			}else{
				$Misc->displayErrorMsg('noaction');
			}
	}

	?>