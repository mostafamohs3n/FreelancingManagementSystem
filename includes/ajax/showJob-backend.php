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
if (isset($_GET['typeshow'])) {
	// Added By {Youssef}
	?>
		<div class="container" id="listJob-container">
				<?php if($_GET['typeshow'] == 'specialShowall'){
					$job = new Job;
					$jobs = $job->searchJobs($Misc->escape_string($_GET['handle']),$_GET['sort'],$_GET['list']);
					foreach($jobs as $job){
						$jobTitle = $job['title'];
						$jobDesc = $job['description'];
						$jobPrice = $job['price'];
						$jobHourAmount = $job['hour_amount'];
						$jobSkills = explode(',', $job['skills_needed']);
						$milestones = json_decode($job['milestones'], false);
						$innerCounter = -1;
					?>
				<div class="panel panel-default material-panel material-panel_primary">
					 <h5 class="panel-heading material-panel__heading"><?=$jobTitle ?></h5>
				 	<div class="panel-body material-panel__body">
					 	<p><?=$jobDesc; ?></p>
					 	<?php
					 	foreach($jobSkills as $skill){?>
					 		<span class="label label-default material-labe"><?=$skill ?></span>
					 	<?php } ?>
					 	<div class="text-right">
					 	<span class="label label-success material-label material-label_success material-label_sm"><?php echo '$'.$jobPrice ?></span>
					 	<span class="label label-success material-label material-label_success material-label_sm"><?php echo $jobHourAmount.'h' ?></span>
					 	</div>
					 	<hr />
					 	<?php foreach($milestones as $milestone){
					 			if(count($milestone) > 1){
					 				if(count($milestone) > $innerCounter+1){
						 				foreach($milestone as $m){
							 				$innerCounter++;
							 				$projectName = $milestones[0][$innerCounter];
							 				$dueDate = $milestones[1][$innerCounter];
							 				$priceMile = $milestones[2][$innerCounter];
					 		?>
						 	<span class="label label-primary material-label material-label_primary mile"><?=$projectName?></span>
						 	<span class="label label-primary material-label material-label_primary mile"><?=$dueDate?></span>
						 	<span class="label label-primary material-label material-label_primary mile"><?=$priceMile?></span>
						 		<?php
						 		echo "<br>";
						 	}
						 	}else{
						 		continue;
						 	}
								}else{ ?>
									<span class="label label-primary material-label material-label_primary"><?=$milestone[0]?></span>
								<?php
								}
						}
							?>
							
							<div class="text-center">
							<input type="button" class="btn material-btn material-btn_success startJob-btn" value="Start Job" onclick="location.href = 'job.php?jobid=<?=$job['job_id'] ?>';">
							</div>
					 </div>
				</div>
					<div class="clearfix"></div>
					<?php 
				}?>	
		</div>
<?php }  } ?>