<?php 
$pageTitle = "Proposal";
include("includes/header.inc.php");

if(!isset($_SESSION['userType']) || !$_SESSION['userType'] == 'freelancer') exit;
$clientID = (new Client)->getUserClientID($_SESSION['userID']);
$p = new Proposal();
$j = new Job();
$jobs = $j->getJobsByClient($clientID);
if(!$Misc->isClientJobExist($Misc->escape_string($_GET['jobid']), $jobs)) exit;
//$Proposal = $p->listClientJobProposal($_SESSION['userID']);
$proposals = $p->listClientJobProposal((int)$Misc->escape_string($_GET['jobid']), $clientID);
?>
<div class="container">
	<h1>Proposals for JobID: <?=$_GET['jobid'];?></h1>
	<?php
	foreach($proposals as $proposal){
		$FUserID = (new Freelancer)->getFreelancerUserID($proposal['freelancer_id']);
		$freelancer = new User;

		echo '<div class="thumbnail material-card">
 <div class="material-card__content">
  <h3 class="material-card__title">'.$freelancer->getUserByID($FUserID)->getName().'</h3>
  <p>' .$proposal["message"]. '</p>
  <hr />
  ';?>
  <?php
   	$milestones = json_decode($proposal['milestones'] , false);
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

    <?php echo '<br><br>
  <span class="label label-info material-label material-label_info">$'.$proposal['price'].'</span>
 </div>

 <footer class="material-card__footer">
  <a href="contract.php?pid=' .$proposal['proposal_id']. '&jobid='.$proposal['job_id'].'" class="btn material-btn material-btn_success material-btn_sm material-card__showmore material-card__showmore_pos-right">Start Contract</a>
 <a data-toggle="modal" data-target="#sendMessageFID'.$proposal['freelancer_id'].'"  class="btn material-btn material-btn_primary material-btn_sm material-card__showmore material-card__showmore_pos-right">Send Message</a>
  </footer>
</div>';
	echo'
<div class="modal material-modal material-modal_primary fade" id="sendMessageFID'.$proposal['freelancer_id'].'">
 <div class="modal-dialog ">
  <div class="modal-content material-modal__content">
   <div class="modal-header material-modal__header">
    <button class="close material-modal__close" data-dismiss="modal">×</button>
    <h4 class="modal-title material-modal__title">Send Message to '.$freelancer->getUserByID($FUserID)->getName() . ': </h4>
   </div>
   <form class="proposalForm">
   <div class="modal-body material-modal__body">
    <div class="form-group materail-input-block materail-input-block_primary materail-input_slide-line">
	 <textarea class="form-control materail-input material-textarea" name="message" required placeholder="Message"></textarea>
	 <span class="materail-input-block__line"></span>
	</div>
	<input type="hidden" name="FreelancerUserID" value="'.$FUserID.'">
	<input type="hidden" name="clientUserID" value="'.$_SESSION['userID'].'">
	<input type="hidden" name="jobID" value="'.$_GET['jobid'].'">
	<input type="hidden" name="proposalID" value="'.$proposal['proposal_id'].'">
	</form>
	<div class="form-response"></div>
   </div>
   <div class="modal-footer material-modal__footer">
    <button class="btn material-btn material-btn" data-dismiss="modal">Close</button>
    <button class="btn btn-primary material-btn material-btn_primary sendMessageFreelancer">Send</button>
   </div>
  </div>
 </div>
</div>
	';
	?>
<script>
	$( document ).ready(function() {
		$(".sendMessageFreelancer").on('click', function(){
			 $.ajax({
		        url: "includes/ajax/sendMessage-backend.php",
		        type: "POST",
		        data: $('.proposalForm').serialize() ,
		        success: function (response) {
		           $(".form-response").html(response);

		        }

		    });
		});
		});
</script>
	<?php
	}
	?>



</div>
<?php
include("includes/footer.inc.php"); 

?>