<?php
Class Misc{
	private $core;
	private function __construct(){
		
	}
	public static function Singleton(){
		
		static $misc = null;
		if(is_null($misc)){
			$misc = new Misc();
			$misc->core = Core::getConnection();
		}
		return $misc;
	}
	public function getVisitorLocation(){
		$Result = null;
		$resource = file_get_contents('https://mylocation.org/');
	    $containerDiscriminator = "<div class=\"info\" title=";
	    $containerStart = strpos($resource,$containerDiscriminator);
	    $detailsTableStart = strpos($resource,"<table>",$containerStart);
	    $detailsTableEnd = strpos($resource,"</table>",$detailsTableStart);
	    $details = substr($resource,$detailsTableStart,$detailsTableEnd-$detailsTableStart);
	    $details = str_replace(array("<table>","<b>","</b>"),'',$details); 
	    $details = preg_replace("/\s+/", '', $details);
	    $details = str_replace("<tr><td>",'&&',$details);
	    $details = str_replace("</td><td>",'=',$details);
	    $details = str_replace("</td></tr>",'',$details);
	    $details = substr($details,2);
	    parse_str($details,$Result);
	    return $Result;
	}

	public function ListAllCountries(){
		$res = null;
		$sql = "SELECT name from `countries`";
		$stmt = $this->core->query($sql);
		return $stmt->fetchAll();
	}
	public function ListAllLanguages(){
		$res = null;
		$sql = "SELECT name, code from `languages`";
		$stmt = $this->core->query($sql);
		return $stmt->fetchAll();
	}

	public function ListAllSkills(){
		$res = null;
		$sql = "SELECT name from `skills`";
		$stmt = $this->core->query($sql);
		return $stmt->fetchAll();
	}

	public function generateVerifyKey(){
		return substr(md5(rand(0,10000)), 0,20);

	}

	public function fieldsBlank(...$args){
		if(count($args) > 0){
			foreach($args as $arg){
				if(empty($arg)) return 1;
			}
		}else{
			if(empty($args)) return 1;
		}
		return 0;
	}
	// Added By {Youssef}
	public function allFieldsBlank(...$args){
		if(count($args) > 0){
			foreach ($args as $arg) {
				if(!empty($arg)){
					return 0;
				}
			}
			return 1;
		}else{
			return 1;
		}
	}
	public function isClientJobExist($jobID, $jobsObj){
		foreach($jobsObj as $job){
		if($job['job_id'] == $jobID)return true;
		}
		return false;
	}
	public function isFreelancerContractExist($contractID, $contractObj){
		foreach($contractObj as $contract){
		if($contract['contract_id'] == $contractID)return true;
		}
		return false;
	}
	function escape_string($data){
        if (!isset($data) or empty($data))
            return '';
        if (is_numeric($data))
            return $data;
        
        $non_displayables = array(
            '/%0[0-8bcef]/', // url encoded 00-08, 11, 12, 14, 15
            '/%1[0-9a-f]/', // url encoded 16-31
            '/[\x00-\x08]/', // 00-08
            '/\x0b/', // 11
            '/\x0c/', // 12
            '/[\x0e-\x1f]/' // 14-31
        );
        foreach ($non_displayables as $regex)
            $data = preg_replace($regex, '', $data);
        $data = str_replace("'", "''", $data);
        return $data;
    }

	public function displayErrorMsg($type, $URL = 'javascript: history.go(-1)'){
		$msg ="Error! Something is wrong.";
		if($type == 'emptyfields') $msg = "You left some fields blank. Please fill all the fields.";
		else if($type == 'noaction') $msg ="Couldn't process the action. Please try again or contact the Administrator.";
		else if($type == 'wronglink') $msg = "Looks like you have followed the wrong link. please go back to <a href='/'>Homepage</a>";
		else if($type == 'usernotexist') $msg = "User does not exist. Forget password or create a new account!";
		else if($type == 'usernotverified') $msg = "User is still not verified. Please check your email for a verification link.";
		else if($type == 'usercantverify') $msg = "We haven't been able to verify your account.<br>Please make sure you have followed the right link. or contact the Administrator.";
		else if($type == 'emailnotexist') $msg = "Email does not exist. Please try again or create a new account!";
		else if($type == 'forgotpw-wrongAnswer') $msg = "Incorrect info.<br>Wrong Security question and/or answer.";
		else if($type == 'forgotpw-pwmismatch') $msg = "Password mismatch.<br>Please make sure the 2 password fields are identical.";
		else if($type == 'job-not-posted') $msg = "Job hasn't been posted.<br>Please try again or contact the administrators.";
		// AddY
		else if($type == 'notupdated') $msg = "Can not update the information right now, Please try againg later";
		else if($type == 'notmatched') $msg = "Please make sure that fields are matched";
		else if($type == 'wrongpw') $msg = "Old password is not correct!";
		//Added by Mohamed
		else if($type == 'register-picnotuploaded') $msg = "Profile Picture wasn't uploaded<br>Please make sure it's only JPG, JPEG or PNG.";
		//Added by Mohsen
		else if($type == 'proposalexists') $msg = "You have already sent a proposal to this job.";
		else if($type == 'msgnotsent') $msg = "Your message hasn't been sent.<br>Please contact the administrator";

		echo '<div class="alert material-alert material-alert_danger">
		 ' . $msg .'
		 <a href="'. $URL .'" class="close material-alert__close">×</a> 
		</div>';
	}
	public function displayWarningMsg($msg){
		echo '<div class="alert material-alert material-alert_warning">' . $msg. '</div>';
	}
	public function displaySuccessMsg($type){
		global $Config;
		$msg = "Success! Everything is going great!";
		switch($type){
			case 'register':
				$msg = "You have successfully registered!<br>An email has been sent to you with the verification link to complete your registration!<br> Welcome to ". $Config['WebsiteName'] ."!";
			break;

			case 'loggedin':
				$msg = "You have successfully logged in! Redirecting you now..";
			break;

			case 'verified':
				$msg = "You have been verified!<br>Welcome to {$Config['WebsiteName']}.<br> You can now login and start using our site!<br>";
			break;

			case 'forgotpw-done':
				$msg = "Correct info!<br> An email has been sent with a link to reset your password.";
			break;
			case 'forgotpw-changedone':
				$msg = "Done!<br>Your password have been changed.";
			break;
			case 'jobadded':
				$msg = "Your job has been submitted successfully.<br>Wait for top freelancers to apply to your job and get your things done!";
			break;
			// Added By {Youssef}
			case 'updated':
				$msg = "Your Information has been changed successfully";
			break;
			// Added By {Youssef}
			case 'createContract':
				$msg = "Contract Started Successfully";
			break;
			// Added by Mohsen
			case 'proposalSent':
				$msg = "Your Proposal has been sent Successfully";
			break;
			case 'msgSent':
				$msg = "Your Message has been sent Successfully";
			break;
			case 'proposalDeleted':
				$msg = "Your Proposal has been deleted Successfully";
			break;
			case 'contractEnded':
				$msg = "Your Contract has been ended Successfully";
			break;

		}
		echo '<div class="alert material-alert material-alert_success">' . $msg. '</div>';
	}


}
?>