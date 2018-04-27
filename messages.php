<?php
$pageTitle = "Messages";
include ("includes/header.inc.php");
if(!isset($_SESSION['userType']) || !isset($_SESSION['loggedIn']))exit;
?>
<div class="container">
<?php
	if(!isset($_GET['jobid'])){
 $messages = (new User)->getUserMessagesJob($_SESSION['userID']);
 if($messages){
	foreach($messages as $message){
		echo'<div class="panel panel-default material-panel material-panel_primary">
	 <a href="messages.php?jobid='.$message['job'].'"><h5 class="panel-heading material-panel__heading">'.(new Job)->getJob($message['job'])->getTitle().'</h5></a>
	</div>';
	}
}else{?>
	<div class="container text-center">
		<span class="label label-default material-labe material-label_lg">There is no messages to show</span>
	</div>
<?php }
 ?>
<?php }else{ ?>
<div id="messageConv" style="max-height:400px;overflow-y:auto;">
<?php
$userType = $_SESSION['userType'];
$user = new User;
if($userType =='freelancer'){
	$clientID = null;
	$proposalID = null;
	$jobID = null;
	$messages = (new User)->getUserMessagesByJob($_SESSION['userID'], $_GET['jobid']);
	foreach($messages as $message){
		if($message['sender'] != $_SESSION['userID']) $clientID = $message['sender'];
		$proposalID = $message['proposal'];
		$jobID = $message['job'];
		$userS = $user->getUserByID($message['sender']);
		$userR = $user->getUserByID($message['reciever']);
?>

<div class="media material-media <?=$message['sender']==$_SESSION['userID'] ? "text-right" : "";?>">
 <div class="media-left <?=$message['sender']==$_SESSION['userID'] ? "pull-right" : "";?>">
  <a href="#0">
  	<img class="media-object material-media__object"  alt="avatar" src="profile_picture/<?=$userS->getProfilePicture();?>"/>
  </a>
 </div>
 <div class="media-body">
  <h4 class="media-heading material-media__title"><?=$userS->getName();?></h4>
  <p><?=$message['content'];?></p>
 </div>
</div>
<?php } ?>
<br>
</div>
<div class="message">
	<form class="proposalForm" method="post">
   <div class="modal-body material-modal__body">
    <div class="form-group materail-input-block materail-input-block_primary materail-input_slide-line">
	 <textarea class="form-control materail-input material-textarea" name="message" required placeholder="Message"></textarea>
	 <span class="materail-input-block__line"></span>
	</div>
	<input type="hidden" name="FreelancerUserID" value="<?=$clientID;?>">
	<input type="hidden" name="clientUserID" value="<?=$_SESSION['userID'];?>">
	<input type="hidden" name="jobID" value="<?=$jobID;?>">
	<input type="hidden" name="proposalID" value="<?=$proposalID;?>">
	</form>
	<div class="form-response"></div>
	<div class="text-center"><button class="btn btn-primary material-btn material-btn_primary sendMessageFreelancer">Send</button></div>
  
</div>
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
<?php }elseif($userType =='client'){
	$clientID = null;
	$proposalID = null;
	$jobID = null;
	$messages = (new User)->getUserMessagesByJob($_SESSION['userID'], $_GET['jobid']);
	foreach($messages as $message){
		if($message['sender'] != $_SESSION['userID']) $clientID = $message['sender'];
		if($message['reciever'] != $_SESSION['userID']) $clientID = $message['reciever'];
		$proposalID = $message['proposal'];
		$jobID = $message['job'];
		$userS = $user->getUserByID($message['sender']);
		$userR = $user->getUserByID($message['reciever']);
?>

<div class="media material-media <?=$message['sender']==$_SESSION['userID'] ? "text-right" : "";?>">
 <div class="media-left <?=$message['sender']==$_SESSION['userID'] ? "pull-right" : "";?>">
  <a href="#0">
  	<img class="media-object material-media__object"  alt="avatar" src="profile_picture/<?=$userS->getProfilePicture();?>"/>
  </a>
 </div>
 <div class="media-body">
  <h4 class="media-heading material-media__title"><?=$userS->getName();?></h4>
  <p><?=$message['content'];?></p>
 </div>
</div>
<?php } ?>
<br>
</div>
<div class="message">
	<form class="proposalForm" method="post">
   <div class="modal-body material-modal__body">
    <div class="form-group materail-input-block materail-input-block_primary materail-input_slide-line">
	 <textarea class="form-control materail-input material-textarea" name="message" required placeholder="Message"></textarea>
	 <span class="materail-input-block__line"></span>
	</div>
	<input type="hidden" name="FreelancerUserID" value="<?=$clientID;?>">
	<input type="hidden" name="clientUserID" value="<?=$_SESSION['userID'];?>">
	<input type="hidden" name="jobID" value="<?=$jobID;?>">
	<input type="hidden" name="proposalID" value="<?=$proposalID;?>">
	</form>
	<div class="form-response"></div>
	<div class="text-center"><button class="btn btn-primary material-btn material-btn_primary sendMessageFreelancer">Send</button></div>
  
</div>
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
}
?>
</div>


<?php
include("includes/footer.inc.php");
?>