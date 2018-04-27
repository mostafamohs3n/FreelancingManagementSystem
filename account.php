<?php
	$pageTitle = "Change Account Settings";
	include("includes/header.inc.php");
	if(!isset($_SESSION['loggedIn']) || !$_SESSION['loggedIn'])exit;
?>
<?php
	$fullName = "";
	$profilePicture = "";
	$securityQuestion = "";
	if(isset($_SESSION['email'])){
		$userObj = new User;
		$user = $userObj->getUser($_SESSION['email']);
		$fullName = $user->getName();
		$securityQuestion = key(json_decode($user->getSecurityQuestion(), false));
		$profilePicture = $user->getProfilePicture();
	}
 ?>
<div class="container" id="register-container">
	<!-- Added By {Youssef} -->
	<!-- ****** Note To Read *****
				
			   replace the action in the form
	 -->
	<h1>Change Account Settings</h1>
	<form method="POST" action="account.php">
		<!-- Name -->
		<div class="form-group materail-input-block materail-input-block_primary materail-input_slide-line">
		 <input name="changeFullName" type="text" value = "<?php echo $fullName ?>" class="form-control materail-input" placeholder="Change Full Name" required>
		 <span class="materail-input-block__line"></span>
		</div>
		<!-- old Password -->
		<div class="form-group materail-input-block materail-input-block_primary materail-input_slide-line">
		 <input name="oldPassword" type="password" class="form-control materail-input" placeholder="Old Password" required>
		 <span class="materail-input-block__line"></span>
		</div>
		<br>
		<div class="form-group">
			<div class="col-md-6">
				<!-- new Password -->
				<div class="form-group materail-input-block materail-input-block_primary materail-input_slide-line">
				 <input name="newPassword" type="password" class="form-control materail-input" placeholder="New Password">
				 <span class="materail-input-block__line"></span>
				</div>
			</div>
			<div class="col-md-6">
				<!-- retype new Passowrd -->
				<div class="form-group materail-input-block materail-input-block_primary materail-input_slide-line">
				 <input name="reNewPassword" type="password" class="form-control materail-input" placeholder="Confirm New Password">
				 <span class="materail-input-block__line"></span>
				</div>
			</div>
		</div>
		<!-- Security Question -->
		<div class="form-group">
			<div class="col-md-6">

				<select class="form-control" name="changeSecurityQuestion" required>
					<option value="What was your childhood nickname?" <?php if($securityQuestion == "What was your childhood nickname?") echo "selected" ?> >What was your childhood nickname?</option>

					<option value="What is the name of your favorite childhood friend?" <?php if($securityQuestion == "What is the name of your favorite childhood friend?") echo "selected" ?> >What is the name of your favorite childhood friend? </option>

					<option value="What school did you attend for sixth grade?" <?php if($securityQuestion == "What school did you attend for sixth grade?") echo "selected" ?> >What school did you attend for sixth grade?</option>

					<option value="In what city or town did your mother and father meet?" <?php if($securityQuestion == "In what city or town did your mother and father meet?") echo "selected" ?> >In what city or town did your mother and father meet? </option>

					<option value="What is the name of a college you applied to but didn't attend?" <?php if($securityQuestion == "What is the name of a college you applied to but didn't attend?") echo "selected" ?> >What is the name of a college you applied to but didn't attend?</option>

					<option value="What was the last name of your third grade teacher?" <?php if($securityQuestion == "What was the last name of your third grade teacher?") echo "selected" ?> >What was the last name of your third grade teacher?</option>
				</select>
			</div>
			<!-- Security Answer -->
			<div class="col-md-6">
				<div class="form-group materail-input-block materail-input-block_primary materail-input_slide-line">
				 	<input name="changeSecurityAnswer" type="text" class="form-control materail-input" placeholder="Security Answer">
				 	<span class="materail-input-block__line"></span>
				</div>
			</div>
		</div>

		<div class="clearfix"></div>
		<!-- Profile Picture -->
		<div class="form-group materail-input-block materail-input-block_primary materail-input_slide-line">
				<input name="changeProfilePicture" type="file" class="form-control materail-input" placeholder="File">
				<span class="materail-input-block__line"></span>
		</div>
		<!-- Submit -->
		<div class="text-center">
			<input type="submit" name="editUser" value="Edit" class="btn material-btn material-btn_success material-btn_lg">
		</div>

	</form>
	<br>
<?php if (isset($_POST['editUser'])){
			if($user->getPassword() != md5($_POST['oldPassword'])){
				$Misc->displayErrorMsg('wrongpw', 'javascript: history.go(-1)');
			}else if($Misc->allFieldsBlank($_POST['newPassword'], $_POST['reNewPassword'], $_POST['changeSecurityAnswer'], 	$_POST['changeProfilePicture'])){
				 $Misc->displayWarningMsg("All fields are blank, You did not update anything");
			}else{
				$fullName = $Misc->escape_string($_POST['changeFullName']);
				$oldPassword = $Misc->escape_string(md5($_POST['oldPassword']));
				$newPassword = $Misc->escape_string(md5($_POST['newPassword']));
				$reNewPassword = $Misc->escape_string(md5($_POST['reNewPassword']));
				$securityQuestion = $Misc->escape_string($_POST['changeSecurityQuestion']);
				$securityAnswer = $Misc->escape_string($_POST['changeSecurityAnswer']);
				$profilePicture = $Misc->escape_string($_POST['changeProfilePicture']);
				if(!empty($_POST['newPassword'])){
					if($newPassword !== $reNewPassword){
						$Misc->displayErrorMsg('notmatched', 'javascript: history.go(-1)');
					}else{
						if(empty($securityAnswer)){
							if(isset($_SESSION['email'])){
								$userArray = [
									'fullname' => $fullName,
									'password' => $newPassword,
									'profile_picture' => $profilePicture
								];
							}else{
								$Misc->displayErrorMsg("noaction", "javascript: history.go(-1)");
							}		
						}else{
							$securityQuesitonAnswer = json_encode([$securityQuestion => $securityAnswer]);
							if(isset($_SESSION['email'])){
								$userArray = [
									'fullname' => $fullName,
									'password' => $newPassword,
									'security_question' => $securityQuesitonAnswer,
									'profile_picture' => $profilePicture
								];
							}else{
								$Misc->displayErrorMsg("noaction", "javascript: history.go(-1)");
							}		
						}
						if($user->updateUser($_SESSION['email'],$userArray)){
							$Misc->displaySuccessMsg('updated');
						}else{
							$Misc->displayErrorMsg("notupdated", "javascript: history.go(-1)");
						}
					}
				}else{
					if(empty($securityAnswer)){
						if(isset($_SESSION['email'])){
							$userArray = [
								'fullname' => $fullName,
								'password' => $oldPassword,
								'profile_picture' => $profilePicture
							];
						}else{
							$Misc->displayErrorMsg("noaction", "javascript: history.go(-1)");
						}		
					}else{
						$securityQuesitonAnswer = json_encode([$securityQuestion => $securityAnswer]);
						if(isset($_SESSION['email'])){
							$userArray = [
								'fullname' => $fullName,
								'password' => $oldPassword,
								'security_question' => $securityQuesitonAnswer,
								'profile_picture' => $profilePicture
							];
						}else{
							$Misc->displayErrorMsg("noaction", "javascript: history.go(-1)");
						}		
					}
					if($user->updateUser($_SESSION['email'],$userArray)){
							$Misc->displaySuccessMsg('updated');
					}else{
						$Misc->displayErrorMsg("notupdated", "javascript: history.go(-1)");
					}
				}
			}
		}	
?>
</div>
<?php include("includes/footer.inc.php");