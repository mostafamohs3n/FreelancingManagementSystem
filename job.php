
<?php
$pageTitle = "Job";
include ("includes/header.inc.php");

if (!isset($_SESSION['userType'])) exit;
echo "<div class=\"container\" id=\"register-container\">";

if ($_SESSION['userType'] == 'client') {
	if (isset($_GET['action'])) {
		$actionType = $_GET['action'];
		if ($actionType == 'add') {
?>
	<h1>Add Job</h1>
<!-- Propeller textbox -->
<link href="http://propeller.in/components/textfield/css/textfield.css" type="text/css" rel="stylesheet"/>
<link href="assets/css/select2.min.css" rel="stylesheet">
<link href="assets/css/select2-bootstrap.css" rel="stylesheet">
<link href="assets/css/pmd-select2.css" rel="stylesheet">
<!-- Select2 js-->
<script type="text/javascript" src="assets/js/select2.full.js"></script>
<script type="text/javascript" src="assets/js/pmd-select2.js"></script>
<script src="assets/js/bootstrap-datepicker.min.js"></script>
<?php
			if (!isset($_POST['addjob'])) { ?>
	<form method="POST" action="job.php?action=add">
		
		<div class="form-group materail-input-block materail-input-block_primary materail-input_slide-line">
			<input name="jobTitle" type="text" class="form-control materail-input" placeholder="Job Title" required>
			<span class="materail-input-block__line"></span>
		</div>
		
		<div class="form-group materail-input-block materail-input-block_primary materail-input_slide-line">
			<textarea rows="5" name="jobDescription" class="form-control materail-input material-textarea" placeholder="Job Description"></textarea>
			<span class="materail-input-block__line"></span>
		</div>


		<div class="form-group">
				<div class="col-md-6">
					<div class="form-group materail-input-block materail-input-block_primary materail-input_slide-line">
						<input name="jobPrice" type="number" min="1" class="form-control materail-input" placeholder="Job Price" required>
						<span class="materail-input-block__line"></span>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group materail-input-block materail-input-block_primary materail-input_slide-line">
						<input name="amountHours" type="number" min="1" class="form-control materail-input" placeholder="Amount of Hours" required>
						<span class="materail-input-block__line"></span>
					</div>
				</div>
		</div>
		<div class="clearfix"></div>
		<div class="form-group pmd-textfield pmd-textfield-floating-label">
			<label>Skills Needed</label>
			<select class="form-control select-add-tags pmd-select2-tags" name="skills[]" multiple>
			<?php
				foreach(($Misc->ListAllSkills()) as $skill) {
					echo "<option value=\"$skill[0]\">$skill[0]</option>";
				} ?>
			</select>
			<script>
			$(".select-add-tags").select2({
				tags: true,
				theme: "bootstrap",
			})
			</script>
		</div>

		<div class="form-group materail-input-block materail-input-block_primary materail-input_slide-line">
			<input name="dueDate" type="text" class="form-control materail-input dueDate" placeholder="Due Date"  required>
			<span class="materail-input-block__line"></span>
		</div>
		<div class="listOfItems">
     		<h4>Milestones</h4>
     		<div class="entry-items">
		    	<div class="form-group entry-item">
		     		<div class="col-md-4 nopadleft">
			     		<div class="form-group materail-input-block materail-input-block_primary materail-input_slide-line">
							<input name="milestoneTitle[]" type="text" class="form-control materail-input" placeholder="Milestone Title" required>
							<span class="materail-input-block__line"></span>
						</div>
					</div>
			     	<div class="col-md-4">
					    <div class="form-group materail-input-block materail-input-block_primary materail-input_slide-line">
							<input name="milestoneDate[]" type="text" class="form-control materail-input dueDate" placeholder="Due Date"  required>
							<span class="materail-input-block__line"></span>
						</div>
					</div>
					<div class="col-md-4 nopadright">
						<div class="form-group materail-input-block materail-input-block_primary materail-input_slide-line">
							<input name="milestonePrice[]" type="nnumber" class="form-control materail-input" placeholder="Milestone Price" required>
							<span class="materail-input-block__line"></span>
						</div>
					</div>
					<div class="clearfix"></div>
		     	</div>
		    </div>
			<div class="text-right"><a href="javascript:void(0)" class="addMoreItems btn material-btn material-btn_primary material-btn_md">Add More</a></div>
		     				
		</div>
		<script>
			$('#register-container .dueDate').datepicker({
				format : 'yyyy-mm-dd'
			});
		    $(".addMoreItems").on('click', function(){
		    	$(".listOfItems .entry-items").append("<div class=\"form-group entry-item\">" + $(".listOfItems .form-group.entry-item").html() + "</div>");
		    	$('#register-container .dueDate').datepicker({
					format : 'yyyy-mm-dd'
				});
			}); 				
		</script>
		<div class="clearfix"></div>
			<div class="form-group text-center"><br />
				<input type="submit" style='padding: 12px 40px;' name="addjob" value="Post Job" class="btn material-btn material-btn_success material-btn_lg">
		</div>
	</form>
<?php
			}
			else {
				if ($Misc->fieldsBlank($_POST['jobTitle'], $_POST['jobDescription'], $_POST['jobPrice'], $_POST['amountHours'], $_POST['skills'], $_POST['dueDate'], $_POST['milestoneTitle'], $_POST['milestoneDate'], $_POST['milestonePrice'])) {
					$Misc->displayErrorMsg('emptyfields');
				}
				else {
					$userID = $Misc->escape_string($_SESSION['userID']);
					$jobTitle = $Misc->escape_string($_POST['jobTitle']);
					$jobDescription = $Misc->escape_string($_POST['jobDescription']);
					$jobPrice = $Misc->escape_string($_POST['jobPrice']);
					$amountHours = $Misc->escape_string($_POST['amountHours']);
					$skills = $Misc->escape_string($_POST['skills']);
					$skills = implode(",", $skills);
					$dueDate = $Misc->escape_string($_POST['dueDate']);
					$milestoneTitle = $Misc->escape_string($_POST['milestoneTitle']);
					$milestoneDate = $Misc->escape_string($_POST['milestoneDate']);
					$milestonePrice = $Misc->escape_string($_POST['milestonePrice']);
					$milestones = json_encode([$milestoneTitle, $milestoneDate, $milestonePrice]);
					$creationDate = Date('Y-m-d');
					$client = new Client;
					$clientID = $client->getUserClientID($userID);
					$job = new Job;
					$job->populateObject($clientID, $jobTitle, $jobDescription, $jobPrice, $amountHours, $skills, $creationDate, $dueDate, $milestones, 0, 1);
					//var_dump($job);
					if ($job->addJob()) {
						$Misc->displaySuccessMsg('jobadded');
						// added by mohamed
						$user = new User;
						$msg = $_SESSION['email'].' add new job called '.$jobTitle;
						echo $user->notify($userID,0,$msg);
					}
					else {
						$Misc->displayErrorMsg('job-not-posted');
					}
				}
			}
		}
	elseif ($actionType == 'list') {
		$client = new Client;
		$job = new Job;
		$jobsByClient = $job->getJobsByClient($client->getUserClientID($_SESSION['userID']));
		//$propObj = new Proposal;
		//$proposal = $propObj->getProposalByJobID($_SESSION['userID']);
		//print_r($proposal);
		// echo "<pre>";
		foreach($jobsByClient as $job){
			/**
				Note to read
			**/
			// Not Complete {Trying to hide jobs that already have been Hired}
			//$checkHired = $proposal->getisHired();
			$jobID = $job['job_id'];
			$jobTitle = $job['title'];
			$jobDesc = $job['description'];
			$jobSkills = $job['skills_needed'];
			$jobDate = $job['creation_date'];
			$jobSkills = explode(",", $jobSkills);
			$jobPrice = $job['price'];
			$jobType = $job['payment_type'];
			?>
			<div class="panel panel-default material-panel material-panel_primary">
			 <a href="proposal.php?jobid=<?=$jobID;?>"><h5 class="panel-heading material-panel__heading"><?=$jobTitle;?></h5></a>
			 <div class="panel-body material-panel__body">
			 	<p class="text-right">Date Created: <?php echo $jobDate; ?></p>
			 	<span class="label label-info material-label material-label_info">$<?php echo $jobPrice; ?></span>
				  <span class="label label-warning material-label material-label_warning"><?php echo ($jobType == 0) ? "Fixed" : "Hourly"; ?> Price</span>
				  
				  <hr />
				    
			 	<?=$jobDesc;?><hr />
			 	<?php foreach($jobSkills as $skill){
			 		echo'<span class="label label-default material-labe">' .$skill . '</span> ';
			 	}
			 	?>
			 </div>
			</div>
					
		<?php
		}
		// echo "</pre>";
	}
	}
}
elseif ($_SESSION['userType'] == 'freelancer') {
	$job = new Job;
	if (!$_GET['jobid']) exit;
	if (!($currentJob = $job->getJob((int)$_GET['jobid']))) exit;
	$jobType = $currentJob->getPayment_type();
?>
<script src="assets/js/bootstrap-datepicker.min.js"></script>
<div class="col-md-9">
<div class="thumbnail material-card">

 <div class="material-card__content">
  <h3 class="material-card__title"><?php echo $currentJob->getTitle(); ?></h3>
  <p class="text-right">Date Created: <?php echo $currentJob->getCreation_date(); ?></p>
  <p><?php echo $currentJob->getDescription(); ?></p>
  <hr />
  <span class="label label-info material-label material-label_info">$<?php echo $currentJob->getPrice(); ?></span>
  <span class="label label-warning material-label material-label_warning"><?php echo ($jobType == 0) ? "Fixed" : "Hourly"; ?> Price</span>
  <span class="label label-danger material-label material-label_danger">Due date: <?php echo $currentJob->getDuedate(); ?></span>
  <hr />
  <h5>Milestones:</h5>
  <?php
	$milestones = json_decode($currentJob->getMilestones() , false);
	$projectName = $milestones[0];
	$DueDate = $milestones[1];
	$priceMile = $milestones[2];

	for($i = 0;$i < count($projectName) ; $i++){ ?>
		Milestone <?php echo ($i+1) ;?><br>
		<?php echo "Project Title : "."<span class = 'size_font'>".$projectName[$i]."</span>"; ?>
		<?php echo "Due Date : "."<span class = 'size_font'>".$DueDate[$i]."</span>"; ?>
		<?php echo "Price : "."<span class = 'size_font'>".$priceMile[$i]." $"."</span>"; ?>
		<hr>
	<?php } ?>

  <hr />
  <?php
	$skills = $currentJob->getSkills_needed();
	$skills = explode(",", $skills);
	foreach($skills as $skill) {
		echo '<span class="label label-default material-label">' . $skill . '</span> ';
	}

?>
 </div>
 <footer class="material-card__footer">
  	<?php
  		$freelancer = new Freelancer;
  		$freelancerID = $freelancer->getUserFreelancerID($_SESSION['userID']);
  		 $prop = new Proposal;
  		 $num = $prop->selectProposal($freelancerID,$_GET['jobid']); // job id 
  		 if ($num == 0) {
  		  	echo '<a href="javascript:void(0)" data-toggle="modal" data-target="#freelancerSendProposal" class="btn material-btn material-btn_success material-btn_sm material-card__showmore material-card__showmore_pos-right">
		  		Send Proposal 
		  		</a>';
  		 }else{
  		 	$propID = $prop->selectProposalID($freelancerID,$_GET['jobid']);?>
  		 	<form id="delete">
  		 		<input type='hidden' name="custom-delete" value='<?php echo $propID['proposal_id'] ?>'>
  		 	</form>
  		 	<?php echo '<a class="btn material-btn material-btn_danger material-btn_sm material-card__showmore material-card__showmore_pos-right" id="deleteprop">
		  		Delete Proposal 
		  		</a>';
  		 }

  	?>
	

<div id="mohamed-test">

</div>





</footer>
</div>
</div>
<?php
	// Added By {Youssef}
	$userObj = new User;
	$user = $userObj->getUserByID($_SESSION['userID']);
	$country = $user->getCountry();
	$joinDate = $user->getJoinDate();
	$jobObj = new Job;
	$job = $jobObj->getJob($_GET['jobid']);
	$clientID = $job->getClient_id();
	$numJob = count($job->getJobsByClient($clientID));
?>
<div class="col-md-3">
	<div class="thumbnail material-card">
	 <div class="material-card__content">
	  <h3 class="material-card__title">Client Info</h3>
	  <p>Country: <?= $country?> <br>JoinDate: <?= $joinDate?> <br>Number of jobs: <?= $numJob?> <br></p>
	 </div>
	 
	</div>
	</div>
	<div class="modal material-modal material-modal_primary fade" id="freelancerSendProposal">
 <div class="modal-dialog ">
  <div class="modal-content material-modal__content">
   <div class="modal-header material-modal__header">
    <button class="close material-modal__close" data-dismiss="modal">×</button>
    <h4 class="modal-title material-modal__title">Proposal: <?php echo $currentJob->getTitle(); ?></h4>
   </div>
   <div class="modal-body material-modal__body">
    <form id="sendProposalForm">
    	<input required type="hidden" value="<?php echo (int)$_GET['jobid']; ?>" name="jobID">
   	<div class="form-group materail-input-block materail-input-block_primary materail-input_slide-line">
	 <textarea name="proposalMessage" class="form-control materail-input material-textarea" placeholder="Message Proposal"></textarea>
	 <span class="materail-input-block__line"></span>
	</div>

	<div class="form-group materail-input-block materail-input-block_primary materail-input_slide-line">
	 <input type="number" name="jobPrice" required value="<?php echo $currentJob->getPrice(); ?>" class="form-control materail-input" placeholder="Price<?php echo ($jobType == 0 ? "" : "/hour") ?>">
	 <span class="materail-input-block__line"></span>
	</div>

	<div class="form-group materail-input-block materail-input-block_primary materail-input_slide-line">
	 <input type="number" name="jobHourAmount" required value="<?php echo $currentJob->getHour_amount(); ?>" class="form-control materail-input" placeholder="Amount of Hours">
	 <span class="materail-input-block__line"></span>
	</div>
	<hr />
	<div class="listOfItems">
     		<div class="entry-items">
	<h4>Proposed Milestones</h4>
	<?php
	for ($i = 0; $i < count($milestones[0]); $i++) {
?>
		    	<div class="form-group entry-item">
		     		<div class="col-md-4 nopadleft">
			     		<div class="form-group materail-input-block materail-input-block_primary materail-input_slide-line">
							<input value="<?php echo $milestones[0][$i] ?>" name="milestoneTitle[]" type="text" class="form-control materail-input" required>
							<span class="materail-input-block__line"></span>
						</div>
					</div>
			     	<div class="col-md-4">
					    <div class="form-group materail-input-block materail-input-block_primary materail-input_slide-line">
							<input value="<?php echo $milestones[1][$i] ?>" name="milestoneDate[]" type="text" class="form-control materail-input dueDate"  required>
							<span class="materail-input-block__line"></span>
						</div>
					</div>
					<div class="col-md-4 nopadright">
						<div class="form-group materail-input-block materail-input-block_primary materail-input_slide-line">
							<input value="<?php echo $milestones[2][$i] ?>" name="milestonePrice[]" type="number" class="form-control materail-input"  required>
							<span class="materail-input-block__line"></span>
						</div>
					</div>
					<div class="clearfix"></div>
		     	</div>
		
	<?php
	}

?>
		    </div>
	<div class="text-right"><a href="javascript:void(0)" class="addMoreItems btn material-btn material-btn_primary material-btn_md">Add More</a></div>
		    
		<script>
			$('#register-container .dueDate').datepicker({
				format : 'yyyy-mm-dd'
			});
		    $(".addMoreItems").on('click', function(){
		    	$(".listOfItems .entry-items").append("<div class=\"form-group entry-item\">" + $(".listOfItems .form-group.entry-item").html() + "</div>");
		    	$('#register-container .dueDate').datepicker({
					format : 'yyyy-mm-dd'
				});
			}); 				
		</script>
		<div class="clearfix"></div>	
		</div>

	</form>
	<div class="form-response">
	</div>
   	</div>
   <div class="modal-footer material-modal__footer">
    <button class="btn material-btn material-btn" data-dismiss="modal">Close</button>
    <button class="btn btn-primary material-btn material-btn_success" id="sendProposalBtn">Send Proposal</button>
   </div>
  </div>
 </div>
</div>
<script>
	$( document ).ready(function() {
		$("#sendProposalBtn").on('click', function(){

			// alert("FUNCTION");
		// alert("test");

			 $.ajax({
		        url: "includes/ajax/sendProposal-backend.php",
		        type: "POST",
		        data: $('#sendProposalForm').serialize() ,
		        success: function (response) {
		           $('#freelancerSendProposal .modal-body .form-response').html(response);    

		        }


		    });
		});

		$("#deleteprop").on('click', function(){
			// alert("FUNCTION");
		// alert("test");
			 $.ajax({
		        url: "includes/ajax/deleteProp-backend.php",
		        type: "POST",
		        data: $('#delete').serialize() ,
		        success: function (response) {
		           $('#mohamed-test').html(response);   
		           window.setTimeout(function(){location.reload()},1000)
		        }
		    });
		});

	});
</script>
<?php
} ?>
<?php
echo "</div>";
include ("includes/footer.inc.php");

?>

