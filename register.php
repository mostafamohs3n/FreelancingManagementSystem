<?php $pageTitle = "Register"; include("includes/header.inc.php"); ?>	
<!-- Propeller textbox -->
<link href="http://propeller.in/components/textfield/css/textfield.css" type="text/css" rel="stylesheet"/>
<link href="assets/css/select2.min.css" rel="stylesheet">
<link href="assets/css/select2-bootstrap.css" rel="stylesheet">
<link href="assets/css/pmd-select2.css" rel="stylesheet">
<!-- Select2 js-->
<script type="text/javascript" src="assets/js/select2.full.js"></script>
<script type="text/javascript" src="assets/js/pmd-select2.js"></script>
<script src="assets/js/bootstrap-datepicker.min.js"></script>

<div class="container" id="register-container">
<h1>Register</h1>
<?php if(!isset($_GET['type']) || (isset($_GET['action']) && !isset($_POST['registerFreelancer']))){ ?>
<div class="text-center">
	<a href="register.php?type=client" class="btn material-btn material-btn_success material-btn_lg">Join as Client</a> 
	<a href="register.php?type=freelancer" class="btn material-btn material-btn_success material-btn_lg">Join as Freelancer</a>
</div>
<?php }else{
	$type = $_GET['type'];
	if($type == 'client'){
		echo "<h2>Joining as a client</h2>";
	}else if($type == 'freelancer'){
		echo "<h2>Joining as a Freelancer</h2>";
	}else{
		$Misc->displayErrorMsg('wronglink');
		echo "</div>";
		include("includes/footer.inc.php");
		exit;
	}
?>
<?php if(!isset($_POST['registerUser']) && !isset($_GET['action'])){ ?>
		<form method="POST" enctype="multipart/form-data" action="register.php?type=<?=$type;?>">

			<div class="form-group materail-input-block materail-input-block_primary materail-input_slide-line">
			 <input name="fullName" type="text" class="form-control materail-input" placeholder="Full Name" required>
			 <span class="materail-input-block__line"></span>
			</div>
			
			<div class="form-group materail-input-block materail-input-block_primary materail-input_slide-line">
			 <input name="email" type="email" class="form-control materail-input" placeholder="Email" required>
			 <span class="materail-input-block__line"></span>
			</div>

			<div class="form-group materail-input-block materail-input-block_primary materail-input_slide-line">
			 <input name="password" type="password" class="form-control materail-input" placeholder="Password" required>
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
			<div class="form-group">
				<div class="col-md-6">
					<select class="form-control" name="gender"  required>
						<option value="M">Male</option>
						<option value="F">Female</option>
					</select>
				</div>
				 <div class="col-md-6">
				 	<div class="form-group materail-input-block materail-input-block_primary materail-input_slide-line">
						<input name="dateOfBirth" type="text" class="form-control materail-input dateOfBirth" placeholder="Date of Birth"  required>
						<span class="materail-input-block__line"></span>
					</div>
				</div>
			</div>
			<script>
				$('#register-container .dateOfBirth').datepicker({
					format : 'yyyy-mm-dd'
				});
			</script>
			<div class="clearfix"></div>
			<div class="form-group">
				<div class="col-md-6">
					<select class="form-control" name="country" required>
					<?php 
					$m = $Misc->getVisitorLocation();
					$m = $m['Country'];
					$allCountries = $Misc->ListAllCountries();
					?>
					<?php
					foreach ($allCountries as $country){
					?>
						<option value="<?=$country[0];?>" <?=($m == $country[0] ? "selected" : "") ; ?>  title="<?=$country[0];?>"><?=$country[0];?></option>
					<?php
					}
					?>
					</select>
				</div>
				<div class="col-md-6">
					<div class="form-group materail-input-block materail-input-block_primary materail-input_slide-line">
						<input name="profilePicture" type="file" class="form-control materail-input" placeholder="File" required>
						<span class="materail-input-block__line"></span>
					</div>
				</div>
			</div>

			<div class="form-group">
				<!--TODO ADD CAPTCHA -->
			</div>
			<div class="clearfix"></div>
			<div class="form-group text-center"><br>
				<input type="submit" name="registerUser" value="Register" class="btn material-btn material-btn_success material-btn_lg">
			</div>

		</form>
<?php } else{
	if($type=='client'){
		if($Misc->fieldsBlank($_POST['fullName'], $_POST['email'], $_POST['password'], $_POST['securityQuestion'],
		 $_POST['securityAnswer'], $_POST['gender'], $_POST['dateOfBirth'], $_FILES['profilePicture'], $_POST['country'])){
			$Misc->displayErrorMsg("emptyfields", "javascript: history.go(-1)");
		}else{
			$fullName = $Misc->escape_string($_POST['fullName']);
			$email = $Misc->escape_string($_POST['email']);
			$password = $Misc->escape_string(md5($_POST['password']));
			$securityQuestion = $Misc->escape_string($_POST['securityQuestion']);
			$securityAnswer = $Misc->escape_string($_POST['securityAnswer']);
			$gender = $Misc->escape_string($_POST['gender']);
			$dateOfBirth = $Misc->escape_string($_POST['dateOfBirth']);
			//$profilePicture = $Misc->escape_string($_POST['profilePicture']); // TODO: HANDLE FILE UPLOAD.
			// {{--added by  Mohamed--}} 
 			@$image = $_FILES['profilePicture'];
			$dir = 'profile_picture/';
			$path = $image['tmp_name'];
			$profilePicture = date('ymdhis').$image['name'];
			$size = $image['size'];
			$type = $image['type'];
			$error = $image['error'];
			$mimesType = ['image/jpg', 'image/png','image/jpeg'];
			if (!$error && is_uploaded_file($path) && in_array($type, $mimesType)) {
				move_uploaded_file($path, $dir . $profilePicture); 
			}else{
				$Misc->displayErrorMsg('register-picnotuploaded');
			}


			$country = $Misc->escape_string($_POST['country']);

			if(!empty($_SERVER['HTTP_CLIENT_IP'])){
				$ip = $_SERVER['HTTP_CLIENT_IP'];
			}elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
				$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
			}else{
				$ip = $_SERVER['REMOTE_ADDR'];
			}
			$joinDate = Date('Y-m-d');
			$last_logged = $joinDate . " " . Date('h:i:s');
			$securityQuestionAnswer = json_encode([$securityQuestion => $securityAnswer]);

			/* As long as the generated key exists in DB, Generate a new one */
			
			do{
				$generatedKey = $Misc->generateVerifyKey();
			}while((new User)->keyExists($generatedKey));

			$user = new User;
			$user->populateUser($fullName, $password, $email, $securityQuestionAnswer, $gender, $dateOfBirth, $profilePicture, $ip, $country, $joinDate, $last_logged, $generatedKey);
			if($insertedUser = $user->addUser()){
				//$userID = $user->getUser($email);
				$client = new Client;
				$client->populateClient($insertedUser);
				if($client->addClient()){
					$Misc->displaySuccessMsg('register');
					$user->sendVerificationLink($email, $fullName, $generatedKey);
				}else{
					$Misc->displayErrorMsg("noaction", "javascript: history.go(-1)");
				}
			}else{
				$Misc->displayErrorMsg("noaction", "javascript: history.go(-1)");
			}


		}
	}
?>
	<?php if($type =='freelancer' && isset($_POST['registerUser'])){

		if($Misc->fieldsBlank($_POST['fullName'], $_POST['email'], $_POST['password'], $_POST['securityQuestion'],
		 $_POST['securityAnswer'], $_POST['gender'], $_POST['dateOfBirth'], $_FILES['profilePicture'], $_POST['country'])){
			$Misc->displayErrorMsg("emptyfields", "javascript: history.go(-1)");
		}else{
			// add escape_string function
			$fullName = $Misc->escape_string($_POST['fullName']);
			$email = $Misc->escape_string($_POST['email']);
			$password = $Misc->escape_string(md5($_POST['password']));
			$securityQuestion = $Misc->escape_string($_POST['securityQuestion']);
			$securityAnswer = $Misc->escape_string($_POST['securityAnswer']);
			$gender = $Misc->escape_string($_POST['gender']);
			$dateOfBirth = $Misc->escape_string($_POST['dateOfBirth']);
			//$profilePicture = $Misc->escape_string($_POST['profilePicture']); // TODO: HANDLE FILE UPLOAD.
				//{{added by mohamed}}
			// {{--added by  Mohamed--}} 
 			@$image = $_FILES['profilePicture'];
			$dir = 'profile_picture/';
			$path = $image['tmp_name'];
			$profilePicture = date('y-m-d-h-i-s').$image['name'];
			$size = $image['size'];
			$type = $image['type'];
			$error = $image['error'];
			$mimesType = ['image/jpg', 'image/png','image/jpeg'];
			if (!$error && is_uploaded_file($path) && in_array($type, $mimesType)) {
				move_uploaded_file($path, $dir . $profilePicture); 
			}else{
				$Misc->displayErrorMsg('register-picnotuploaded');
			}



			// {{added by Mohamed}}

			$country = $Misc->escape_string($_POST['country']);
			if(!empty($_SERVER['HTTP_CLIENT_IP'])){
				$ip = $_SERVER['HTTP_CLIENT_IP'];
			}elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
				$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
			}else{
				$ip = $_SERVER['REMOTE_ADDR'];
			}
			$joinDate = Date('Y-m-d');
			$last_logged = $joinDate . " " . Date('h:i:s');
			$securityQuestionAnswer = json_encode([$securityQuestion => $securityAnswer]);
			do{
				$generatedKey = $Misc->generateVerifyKey();
			}while((new User)->keyExists($generatedKey));

			$user = new User;
			$user->populateUser($fullName, $password, $email, $securityQuestionAnswer, $gender, $dateOfBirth, $profilePicture, $ip, $country, $joinDate, $last_logged, $generatedKey);
			// if($insertedUser = $user->addUser()){
				//$userID = $user->getUser($email);
				/** Here goes the form for step 2 **/
				if(!isset($_POST['registerFreelancer'])){
				?>
				<form method="POST" action="register.php?type=freelancer&action=addfreelancer">
					<input type="hidden" name="fullName" value="<?=$fullName;?>">
					<input type="hidden" name="email" value="<?=$email;?>">
					<input type="hidden" name="password" value="<?=$password;?>">
					<input type="hidden" name="securityQuestion" value="<?=$securityQuestion;?>">
					<input type="hidden" name="securityAnswer" value="<?=$securityAnswer;?>">
					<input type="hidden" name="gender" value="<?=$gender;?>">
					<input type="hidden" name="dateOfBirth" value="<?=$dateOfBirth;?>">
					<input type="hidden" name="profilePicture" value="<?=$profilePicture;?>">
					<input type="hidden" name="country" value="<?=$country;?>">
					<input type="hidden" name="ip" value="<?=$ip;?>">
					<input type="hidden" name="joinDate" value="<?=$joinDate;?>">
					<input type="hidden" name="last_logged" value="<?=$last_logged;?>">
					<input type="hidden" name="key" value="<?=$generatedKey;?>">
					
					<div class="form-group materail-input-block materail-input-block_primary materail-input_slide-line">
					 <input name="pricePerHour" type="number" class="form-control materail-input" placeholder="Price Per Hour" required>
					 <span class="materail-input-block__line"></span>
					</div>
					
					<div class="form-group materail-input-block materail-input-block_primary materail-input_slide-line">
					 <textarea class="form-control materail-input material-textarea" placeholder="Review(Talk about yourself, your story." name="review"></textarea>
					 <span class="materail-input-block__line"></span>
					</div>
					<div class="form-group">
						<div class="col-md-6">
							<div class="form-group materail-input-block materail-input-block_primary materail-input_slide-line">
							 <input name="educationName" type="text" class="form-control materail-input" placeholder="Education e.g. Computers and Information Systems - Helwan University" required>
							 <span class="materail-input-block__line"></span>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
							 <select name="educationFrom" class="form-control" required>
							 	<?php for($i = 1900; $i<= (int)date('Y');$i++){
							 		echo "<option value=\"$i\">$i</option>";
							 	}
							 	?>
							 </select>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
							 <select name="educationTo" class="form-control" required>
							 	<?php for($i = 1900; $i<= (int)date('Y');$i++){
							 		echo "<option value=\"$i\">$i</option>";
							 	}
							 	?>
							 </select>
							</div>
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-6">
							<div class="form-group pmd-textfield pmd-textfield-floating-label">
								<label>Languages</label>
								<select class="form-control select-add-tags pmd-select2-tags" name="languages[]" multiple>
									<?php 
									foreach(($Misc->ListAllLanguages()) as $lang ){
										echo "<option value=\"$lang[1]\">$lang[0]</option>";
									}?>
								</select>
							</div>


						</div>
						<div class="col-md-6">
							<div class="form-group pmd-textfield pmd-textfield-floating-label">

							<label>Skills</label>
							<select class="form-control select-add-tags pmd-select2-tags" name="skills[]" multiple>
								<?php 
									foreach(($Misc->ListAllSkills()) as $skill ){
										echo "<option value=\"$skill[0]\">$skill[0]</option>";
									}?>
							</select>
							</div>
						</div>
		     				<div class="clearfix"></div>
					</div>
							
							<script>
								$(".select-add-tags").select2({
									tags: true,
									theme: "bootstrap",
								})
     
     						</script>
     						<div class="listOfItems">
     							<h4>Portfolio</h4>
     						<div class="entry-items">
		     				<div class="form-group entry-item">
		     					<div class="col-md-3">
			     					<div class="form-group materail-input-block materail-input-block_primary materail-input_slide-line">
									 <input name="portfolioTitle[]" type="text" class="form-control materail-input" placeholder="Project Title" required>
									 <span class="materail-input-block__line"></span>
									</div>
								</div>
		     					<div class="col-md-6">
			     					<div class="form-group materail-input-block materail-input-block_primary materail-input_slide-line">
									 <input name="portfolioDesc[]" type="text" class="form-control materail-input" placeholder="Project Description" required>
									 <span class="materail-input-block__line"></span>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group materail-input-block materail-input-block_primary materail-input_slide-line">
									 <input name="portfolioLink[]" type="text" class="form-control materail-input" placeholder="Project Link" required>
									 <span class="materail-input-block__line"></span>
									</div>
								</div>
								<div class="clearfix"></div>
		     				</div>
		     				</div>
		     				<div class="text-right"><a href="javascript:void(0)" class="addMoreItems btn material-btn material-btn_primary material-btn_md">Add More</a></div>
		     				
     						</div>
     						<script>
		     					$(".addMoreItems").on('click', function(){
		     						$(".listOfItems .entry-items").append("<div class=\"form-group entry-item\">" + $(".listOfItems .form-group.entry-item").html() + "</div>");

		     					});
		     				</script>
     						<div class="clearfix"></div>
							<div class="form-group text-center"><br>
								<input type="submit" name="registerFreelancer" value="Register" class="btn material-btn material-btn_success material-btn_lg">
							</div>
				</form>
			<?php
				}
			  /** Form step 2 end **/
			// }else{
			// 	$Misc->displayErrorMsg("noaction", "register.php?type=freelancer");
			// }

			 
		}

	}

}
}
 if(isset($_GET['action']) && $_GET['action'] == 'addfreelancer' && isset($_POST['registerFreelancer'])){

			  	if($Misc->fieldsBlank($_POST['fullName'], $_POST['email'], $_POST['password'], $_POST['securityQuestion'],
			  	$_POST['gender'], $_POST['dateOfBirth'], $_POST['profilePicture'], $_POST['country'], $_POST['ip'], $_POST['joinDate'],
			  	$_POST['last_logged'], $_POST['key'], $_POST['pricePerHour'], $_POST['review'], $_POST['educationName'], $_POST['educationFrom'],
			  	$_POST['educationTo'], $_POST['languages'], $_POST['skills'], $_POST['portfolioTitle'], $_POST['portfolioDesc'], $_POST['portfolioLink'])){
			  		$Misc->displayErrorMsg('emptyfields', "javascript: history.go(-1)");
			  	}else{
			  		$fullName = $Misc->escape_string($_POST['fullName']);
					$email = $Misc->escape_string($_POST['email']);
					$password = $Misc->escape_string($_POST['password']);
					$securityQuestion = $Misc->escape_string($_POST['securityQuestion']);
					$securityAnswer = $Misc->escape_string($_POST['securityAnswer']);
					$gender = $Misc->escape_string($_POST['gender']);
					$dateOfBirth = $Misc->escape_string($_POST['dateOfBirth']);
					$profilePicture = $Misc->escape_string($_POST['profilePicture']); // TODO: HANDLE FILE UPLOAD.
					$country = $Misc->escape_string($_POST['country']);
					$ip = $Misc->escape_string($_POST['ip']);
					$joinDate = $Misc->escape_string($_POST['joinDate']);
					$last_logged = $Misc->escape_string($_POST['last_logged']);
					$key = $Misc->escape_string($_POST['key']);
					$pricePerHour = $Misc->escape_string($_POST['pricePerHour']);
					$review = $Misc->escape_string($_POST['review']);
					$educationName = $Misc->escape_string($_POST['educationName']);
					$educationFrom = $Misc->escape_string($_POST['educationFrom']);
					$educationTo = $Misc->escape_string($_POST['educationTo']);
					$languages = $Misc->escape_string($_POST['languages']);
					$languages = implode(",", $languages);
					$skills = $Misc->escape_string($_POST['skills']);
					$skills = implode(",", $skills);
					$portfolioTitle = $Misc->escape_string($_POST['portfolioTitle']);
					$portfolioDesc = $Misc->escape_string($_POST['portfolioDesc']);
					$portfolioLink = $Misc->escape_string($_POST['portfolioLink']);
					$securityQuestionAnswer = json_encode([$securityQuestion => $securityAnswer]);
					$education = json_encode([$educationName, $educationFrom, $educationTo]);
					$portfolio = json_encode([$portfolioTitle, $portfolioDesc, $portfolioLink]);
					$user = new User;
					$user->populateUser($fullName, $password, $email, $securityQuestionAnswer, $gender, $dateOfBirth, $profilePicture, $ip, $country, $joinDate, $last_logged, $key);
					if($insertedUser = $user->addUser()){
						$freelancer = new Freelancer;
						$freelancer->populateFreelancer($insertedUser, NULL, $pricePerHour, $review, $education, $languages, NULL, $portfolio, $skills);
						if($freelancer->addFreelancer()){
							$Misc->displaySuccessMsg('register');
							$user->sendVerificationLink($email, $fullName, $key);
						}else{
							$Misc->displayErrorMsg('noaction', "javascript: history.go(-1)");
						}
					}else{
						$Misc->displayErrorMsg('noaction', "javascript: history.go(-1)");
					}


			  	}
}

?> 
</div>
<?php include("includes/footer.inc.php"); ?>
    