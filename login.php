<?php $pageTitle = "Login"; include("includes/header.inc.php"); ?>
	
<div class="container">
	<form method="POST" action="login.php">
		<div class="form-group materail-input-block materail-input-block_primary materail-input_slide-line">
			<input name="email" type="text" class="form-control materail-input" placeholder="Email" required>
			<span class="materail-input-block__line"></span>
		</div>
        
		<div class="form-group materail-input-block materail-input-block_primary materail-input_slide-line">
			<input name="password" type="password" class="form-control materail-input" placeholder="Password" required>
			<span class="materail-input-block__line"></span>
		</div>

		<div class="form-group text-center"><br>
			<input type="submit" name="login" value="Login" class="btn material-btn material-btn_success material-btn_lg">
		</div>
    </form>
    <?php if(isset($_POST['login'])){
    	if($Misc->fieldsBlank($_POST['email'], $_POST['password'])){
    		$Misc->displayErrorMsg('emptyfields');
    	}else{
    		$email = $Misc->escape_string($_POST['email']);
    		$password = $Misc->escape_string(md5($_POST['password']));

    		$user = new User;
    		$logTrial = $user->login($email, $password);
    		// var_dump($logTrial);
    		if($logTrial == -1){
    			$Misc->displayWarningMsg('User was not verified. Please check your email for a verification link.');
    		}else if($logTrial){
    			$Misc->displaySuccessMsg('loggedin');	
    			$freelancer = new Freelancer;
    			$_SESSION['loggedIn'] = true;
    			$_SESSION['email'] = $email;
    			$_SESSION['userID'] = $logTrial;
    			if($freelancer->isFreelancer($email)){
    				// it's a freelancer
    				$_SESSION['userType'] = 'freelancer';
    			}else{
    				// it's a client
    				$_SESSION['userType'] = 'client';
    				// echo "C";
    			}
    			header("Location: index.php");
    			exit;
    		}else{
    			$Misc->displayErrorMsg('usernotexist');
    		}

    	}

    }
    ?>
</div>
      <?php include("includes/footer.inc.php"); ?>
    