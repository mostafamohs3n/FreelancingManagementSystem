<?php
$pageTitle ="Forgot Password";
include("includes/header.inc.php"); 
echo "<div class=\"container\">";
?>
<h1>Forgot Password</h1>
<?php if(!isset($_POST['forgotpw']) && !isset($_GET['verify']) && !isset($_GET['acc'])){ ?>
	<form method="POST" action="forgot.php">
		<div class="form-group materail-input-block materail-input-block_primary materail-input_slide-line">
			<input name="email" type="email" class="form-control materail-input" placeholder="Email.." required>
			<span class="materail-input-block__line"></span>
		</div>
		<div class="form-group">
			<div class="col-md-6">
				<select class="form-control" name="securityQuestion" required>
					<option value="What was your childhood nickname?">What was your childhood nickname?</option>
					<option value="What is the name of your favorite childhood friend?">What is the name of your favorite childhood friend? </option>
					<option value="What school did you attend for sixth grade?">What school did you attend for sixth grade?</option>
					<option value="In what city or town did your mother and father meet? ">In what city or town did your mother and father meet? </option>
					<option value="What is the name of a college you applied to but didn't attend?">What is the name of a college you applied to but didn't attend?</option>
					<option value="What was the last name of your third grade teacher?">What was the last name of your third grade teacher?</option>
					</select>
				</div>
			<div class="col-md-6">
				 <div class="form-group materail-input-block materail-input-block_primary materail-input_slide-line">
					<input name="securityAnswer" type="text" class="form-control materail-input" placeholder="Security Answer"  required>
					<span class="materail-input-block__line"></span>
				</div>
			</div>
		</div>
		<div class="clearfix"></div>
			<div class="form-group text-center"><br>
				<input type="submit" style='padding: 12px 40px;' name="forgotpw" value="Submit" class="btn material-btn material-btn_success material-btn_lg">
		</div>
	</form>
<?php 
	}else{
		if(!isset($_GET['verify']) && !isset($_GET['acc'])){
		if($Misc->fieldsBlank($_POST['email'], $_POST['securityQuestion'], $_POST['securityAnswer'])){
			$Misc->displayErrorMsg("emptyfields", "javascript: history.go(-1)");
		}else{
			$email = $Misc->escape_string($_POST['email']);
			$securityQuestion = $Misc->escape_string($_POST['securityQuestion']);
			$securityAnswer = $Misc->escape_string($_POST['securityAnswer']);
			$user = new User;
			if($userSecurityQuestionAnswer = $user->resetPasswordVerify($email)){
				$userQA = json_decode($userSecurityQuestionAnswer, true);
				$userQ = array_keys($userQA)[0];
				$userA = array_values($userQA)[0];
				if($userQ === $securityQuestion){
					if($userA === $securityAnswer){
						if($genKey = $user->resetPasswordUpdate($email)){
							if($user->resetPasswordProcess($email, $genKey)){
								$Misc->displaySuccessMsg('forgotpw-done');
							}else{
								$Misc->displayErrorMsg('noaction');
							}
						}else{
							$Misc->displayErrorMsg('noaction');
						}
					}else{
						$Misc->displayErrorMsg('forgotpw-wrongAnswer');
					}
				}else{
						$Misc->displayErrorMsg('forgotpw-wrongAnswer');
				}
			}else{
				$Misc->displayErrorMsg('emailnotexist');
			}

		}
	}
}
if(isset($_GET['verify']) && isset($_GET['acc'])){
	$key = $Misc->escape_string($_GET['verify']);
	$realAcc = $Misc->escape_string(base64_decode($_GET['acc']));
	$acc = $Misc->escape_string($_GET['acc']);
	// echo $key;
	// echo $acc;
	$user = new User;
	if($user->resetPasswordActionVerify($key, $realAcc)){
		//showform
?>
<?php if(!isset($_POST['forgotchangepw'])){ ?>
<p>Enter a new password for your account</p>
		<form method="POST" action="forgot.php?verify=<?=$key;?>&acc=<?=$acc;?>">
			<div class="form-group materail-input-block materail-input-block_primary materail-input_slide-line">
				<input name="password1" type="password" class="form-control materail-input" placeholder="Password.." required>
				<span class="materail-input-block__line"></span>
			</div>
			
			<div class="form-group materail-input-block materail-input-block_primary materail-input_slide-line">
				<input name="password2" type="password" class="form-control materail-input" placeholder="Confirm Password.." required>
				<span class="materail-input-block__line"></span>
			</div>

			<div class="clearfix"></div>
			<div class="form-group text-center"><br>
				<input type="submit" style='padding: 12px 40px;' name="forgotchangepw" value="Submit" class="btn material-btn material-btn_success material-btn_lg">
			</div>

		</form>
		<?php 
	}else{
		if($Misc->fieldsBlank($_GET['acc'], $_POST['password1'], $_POST['password2'])){
			$Misc->displayErrorMsg("emptyfields", "javascript: history.go(-1)");

		}else{
			$email = $Misc->escape_string(base64_decode($_GET['acc']));
			$password1 = $Misc->escape_string(md5($_POST['password1']));
			$password2 = $Misc->escape_string(md5($_POST['password2']));
			// echo $email;
			if($password1 != $password2){
				$Misc->displayErrorMsg('forgotpw-pwmismatch');
			}else{
				$user = new User;
				if($user->changePasswordAction($email, $password2)){
					$Misc->displaySuccessMsg('forgotpw-changedone');
				}
			}

		}

	}
	?>
	<?php
	}else{
		$Misc->displayErrorMsg('wronglink');
	}


}
?>

<?php
echo "</div>";
include("includes/footer.inc.php");
?>