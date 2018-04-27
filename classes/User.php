<?php
// * Todo: Set database table names dynamically*
class User{
	private
	$name, $password, $email, $securityQuestion, $gender,
	$dateOfBirth, $profilePicture, $IP, $country, $joinDate, $lastLogged, $isVerified;
	
	private $core;
	function __construct(){
		$this->core = Core::getConnection();/*Important;Global for all classes*/
	}
	public function getUser($email){
		$user = False;
		$query = "SELECT * FROM `user` where email = :email";
		$stmt = $this->core->prepare($query);
		$stmt->execute([':email' => $email]);
		// print_r([0]);
		if($stmt->rowCount() > 0){
			$res = $stmt->fetchAll()[0];
			$user = $this->populateData($res);
		}
		return $user;
	}
	public function getUserByID($id){
		$user = False;
		$query = "SELECT * FROM `user` where user_id = :id";
		$stmt = $this->core->prepare($query);
		$stmt->execute([':id' => $id]);
		// print_r([0]);
		if($stmt->rowCount() > 0){
			$res = $stmt->fetchAll()[0];
			$user = $this->populateData($res);
		}
		return $user;
	}
	private function populateData($res){
		$user = new User;
		$user->setName($res[1]);
		$user->setPassword($res[2]);
		$user->setSecurityQuestion($res[4]);
		$user->setProfilePicture($res[7]);
		$user->setCountry($res[8]);
		$user->setJoinDate($res[10]);
		$user->setIsVerified($res[12]);
		return $user;
	}

	public function keyExists($key){
		$query = "SELECT `is_verified` from `user` where `is_verified` = :key";
		$stmt = $this->core->prepare($query);
		$stmt->execute([
			':key' => $key
		]);
		if($stmt->rowCount() > 0){
			return 1;
		}else{ 
			return 0;
		}
	}

	public function populateUser($name, $password, $email, $securityQuestion, $gender, $dateOfBirth, $profilePicture, $IP, $country, $joinDate, $lastLogged, $isVerified){
		$this->name = $name;
		$this->password = $password;
		$this->email = $email;
		$this->securityQuestion = $securityQuestion;
		$this->gender = $gender;
		$this->dateOfBirth = $dateOfBirth;
		$this->profilePicture = $profilePicture;
		$this->IP = $IP;
		$this->country = $country;
		$this->joinDate = $joinDate;
		$this->lastLogged = $lastLogged;
		$this->isVerified = $isVerified;
	}
	public function addUser(){
		$insertedID = FALSE;
		$query = "INSERT INTO user (fullname, password, email, security_question, gender, date_of_birth, profile_picture, ip, country, joindate, last_logged, is_verified)
				  VALUES (:fullname, :password, :email, :security_question, :gender, :date_of_birth, :profile_picture, :ip, :country, :joindate, :last_logged, :is_verified)
		";
		$stmt = $this->core->prepare($query);
		// var_dump($stmt);

		$res = $stmt->execute([
			':fullname' => $this->getName(),
			':password' => $this->getPassword(),
			':email' => $this->getEmail(),
			':security_question' => $this->getSecurityQuestion(),
			':gender' => $this->getGender(),
			':date_of_birth' => $this->getDateOfBirth(),
			':profile_picture' => $this->getProfilePicture(),
			':ip' => $this->getIP(),
			':country' => $this->getCountry(),
			':joindate' => $this->getJoinDate(),
			':last_logged' => $this->getLastLogged(),
			':is_verified' => $this->getIsVerified(),
		]);
		return $this->core->lastInsertId('user_id');
	}

	public function sendVerificationLink($to,$name, $verificationCode){
		global $Config;
		$mail = new Mail;
		$subject = "{$Config['WebsiteName']} - Registration Verification";
		$msg  = "Hello, ". ucwords($name) ."<br>";
		$msg .= "Welcome to {$Config['WebsiteName']} - In order to complete your registration please enter the following link<br>";
		$msg .= "<a href=\"http://{$Config['WebsiteLink']}/verify.php?key=$verificationCode&secret=" .base64_encode($to) . "\">http://{$Config['WebsiteLink']}/verify.php?key=$verificationCode&secret=" . base64_encode($to) . "</a><br>";
		$msg .= "<br>Thanks for joining us in {$Config['WebsiteName']}!";
		return $mail->sendMail($to, $subject, $msg);
	}
	public function isVerifiable($email){
		$mail = base64_decode($email);
		$user = $this->getUser($mail);
		if(!$user) return 0;
		return ($user->getIsVerified() != "1");
	}

	public function verifyUser($mail, $key){
		$mail = base64_decode($mail);
		$query = "SELECT email, is_verified from user where email =:mail and is_verified = :verifyKey ";
		$stmt = $this->core->prepare($query);
		$stmt->execute([':mail' => $mail, ':verifyKey' => $key]);
		if($stmt->rowCount() > 0){
			$q = "UPDATE user set is_verified = 1 where email = :mail";
			$st = $this->core->prepare($q);
			$st->execute([':mail' => $mail]);
			return (Boolean) $st->rowCount();
		}
		return 0;
	}

	public function resetPasswordVerify($email){
		$user = $this->getUser($email);
		if(!$user)return 0;
		return $user->getSecurityQuestion();
	}
	public function resetPasswordUpdate($email){
		global $Misc;
		$generatedKey = $Misc->generateVerifyKey();
		$query = "UPDATE user set resetpw = :key WHERE email = :email AND resetpw IS NULL";
		$stmt = $this->core->prepare($query);
		$stmt->execute([':email'=>$email, ':key' => $generatedKey]);
		// var_dump($stmt);
		if($stmt->rowCount() > 0){
			return $generatedKey;
		}else{
			return 0;
		}
	}
	public function resetPasswordProcess($email, $key){
		global $Config;
		$mail = new Mail;
		$subject = "{$Config['WebsiteName']} - Account Password Reset";
		$msg  = "Hello,<br>";
		$msg .= "Someone requested a password reset for your account, if it was you please enter the link below.<br>";
		$msg .= "<a href=\"http://{$Config['WebsiteLink']}/forgot.php?verify=$key&acc=" . base64_encode($email) . "\">http://{$Config['WebsiteLink']}/forgot.php?verify=$key&acc=". base64_encode($email) . "</a>";
		$msg .= "<br>Thanks for using {$Config['WebsiteName']}!";
		return $mail->sendMail($email, $subject, $msg);
	}

	public function resetPasswordActionVerify($key, $email){
		$query = "SELECT email from user where email = :email and resetpw = :key";
		$stmt = $this->core->prepare($query);
		$stmt->execute([':email' => $email, ':key' => $key]);
		return (Boolean) $stmt->rowCount();
	}

	public function changePasswordAction($email, $password){
		$query = "UPDATE user set password = :password, resetpw = NULL WHERE email = :email ";
		$stmt = $this->core->prepare($query);
		$stmt->execute([':password' => $password, ':email' => $email]);
		return (Boolean) $stmt->rowCount();
	}
	// Added By {Youssef}
	public function updateUser($email, $userArray){
		$res = False;
		foreach ($userArray as $col => $data) {
			if(!empty($data)){
				$query = "UPDATE user SET $col = :data WHERE email = :email";
				$stmt = $this->core->prepare($query);
				$res = $stmt->execute([':data' => $data, ':email' => $email]);
			}
		}
		return $res;
	}
	
	/**
	Return values: 0 for user doesn't exist, 1 for logged in successfully, -1 for user is not activated yet
	**/
	public function login($email, $password){
		$user = $this->getUser($email);
		if(!$user)return 0;
		if($user->getIsVerified() != 1){
			//user is not activated yet
			return -1;
		}else{
			$query = "SELECT user_id, email, password from `user` where email = :email and password = :password";
			$stmt = $this->core->prepare($query);
			$stmt->execute([
				':email' => $email,
				':password' => $password
			]);
			if($stmt->rowCount() > 0){
				// log in and create sessions and set them
				return ($stmt->fetchAll()[0][0]);
			}else{
				//user doesnt exist, try again 
				return 0;
			}
		}
		//see if is verified
		//else
		//check if username and password exists
		//create sessions and set them
	}
	public function logout(){
		unset($_SESSION['userType']);
		unset($_SESSION['email']);
		unset($_SESSION['userID']);
		session_destroy();
		header("Location: index.php");
	}
	// added by mohamed
	public function sendMsg($sender, $receiver, $msg, $job, $prop){
		$query = "INSERT INTO message (sender, reciever, content, job, proposal,message_date)
				  VALUES (:sender, :reciever, :content, :job, :proposal,now())
		";
		
		$stmt = $this->core->prepare($query);
		$res = $stmt->execute([
			':sender' => $sender,
			':reciever' => $receiver,
			':content' => $msg,
			':job' => $job,
			':proposal' => $prop
		]);
		return (Boolean) $stmt->rowCount();
	}
	public function getUserMessages($userID){
		$query = "SELECT * FROM message where sender = :userID or reciever = :userID order by message_date ASC";
		$stmt = $this->core->prepare($query);
		$stmt->execute([':userID' => $userID]);
		return $stmt->fetchAll();
	}
	public function getUserMessagesByJob($userID, $jobID){
		$query = "SELECT * FROM message where job = :jobID and (sender = :userID or reciever = :userID) order by message_date ASC";
		$stmt = $this->core->prepare($query);
		$stmt->execute([':userID' => $userID, ':jobID'=>$jobID]);
		return $stmt->fetchAll();
	}
	public function getUserMessagesJob($userID){
		$query = "SELECT * FROM message where sender = :userID or reciever = :userID group by job order by message_date ASC";
		$stmt = $this->core->prepare($query);
		$stmt->execute([':userID' => $userID]);
		return $stmt->fetchAll();
	}

	// added by mohamed
	public function numnotify($receiver){
		$query = "SELECT notify.*,user.* from notify inner join user on user.user_id=notify.sender where notify.reciever = :reciever order by notify_date DESC";
		$stmt = $this->core->prepare($query);
		$stmt->execute([':reciever' => $receiver]);
		return $stmt->rowCount();
	}


	// added by mohamed
	public function shownotify($receiver){
		

		$query = "SELECT notify.*,user.* from notify inner join user on user.user_id=notify.sender where notify.reciever = :reciever order by notify_date DESC";
		$stmt = $this->core->prepare($query);
		$stmt->execute([':reciever' => $receiver]);
		return $stmt->fetchAll();
	}

	// added by mohamed
	public function notify($sender, $receiver, $msg){
		//later
		if ($receiver == 0) {
			$freelancer = new Freelancer;
			$freelancers = $freelancer->getallfree();
			foreach ($freelancers as $f) {
				$query = "INSERT INTO notify (sender, reciever, content,notify_date)
					  VALUES (:sender, :reciever, :content,now())
				";
				$stmt = $this->core->prepare($query);
				$res = $stmt->execute([
					':sender' => $sender,
					':reciever' => $f['user_id'],
					':content' => $msg
				]);
			}
		}else{
			$query = "INSERT INTO notify (sender, reciever, content,notify_date)
					  VALUES (:sender, :reciever, :content,now())
			";
			$stmt = $this->core->prepare($query);
			$res = $stmt->execute([
				':sender' => $sender,
				':reciever' => $receiver,
				':content' => $msg
			]);
			return (Boolean) $stmt->rowCount();
		}
	}

	

	/***getters and setters**/
	public function getUserID(){
		return $this->userID;
	}
	public function getName(){
		return $this->name;
	}
	public function setName($name){
		$this->name = $name;
	}
	public function getPassword(){
		return $this->password;
	}
	public function setPassword($password){
		$this->password = $password;
	}
	public function getEmail(){
		return $this->email;
	}
	public function setEmail($email){
		$this->email = $email;
	}
	public function getSecurityQuestion(){
		return $this->securityQuestion;
	}
	public function setSecurityQuestion($securityQuestion){
		$this->securityQuestion = $securityQuestion;
	}
	public function getGender(){
		return $this->gender;
	}
	public function setGender($gender){
		$this->gender = $gender;
	}
	public function getDateOfBirth(){
		return $this->dateOfBirth;
	}
	public function setDateOfBirth($dateOfBirth){
		$this->dateOfBirth = $dateOfBirth;
	}
	public function getProfilePicture(){
		return $this->profilePicture;
	}
	public function setProfilePicture($profilePicture){
		$this->profilePicture = $profilePicture;
	}
	public function getIP(){
		return $this->IP;
	}
	public function setIP($IP){
		$this->IP = $IP;
	}
	public function getCountry(){
		return $this->country;
	}
	public function setCountry($country){
		$this->country = $country;
	}
	public function getJoinDate(){
		return $this->joinDate;
	}
	public function setJoinDate($joinDate){
		$this->joinDate = $joinDate;
	}
	public function getLastLogged(){
		return $this->lastLogged;
	}
	public function setLastLogged($lastLogged){
		$this->lastLogged = $lastLogged;
	}
	public function getIsVerified(){
		return $this->isVerified;
	}
	public function setIsVerified($isVerified){
		$this->isVerified = $isVerified;
	}
	
}
