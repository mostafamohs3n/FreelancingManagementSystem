<?php

class Proposal {

  private
  $propID, $jobID, $fID, $price, $milestones, $propMessage,
  $hourAmount, $isHired, $isViewed, $isAvailable;
  private $core;


  function __construct(){
		

		$this->core = Core::getConnection();
	}
  public function populateObject($FID, $jobID, $price, $milestones, $propMessage, $hourAmount, $isHired, $isViewed, $isAvailable){
    $this->fID = $FID;
    $this->jobID = $jobID;
    $this->price = $price;
    $this->milestones = $milestones;
    $this->propMessage = $propMessage;
    $this->hourAmount = $hourAmount;
    $this->isHired = $isHired;
    $this->isViewed = $isViewed;
    $this->isAvailable = $isAvailable;
  }
  // Added By {Youssef}
  private function populateData($res){
    $proposal = new Proposal;
    $proposal->setpropID($res[0]);
    $proposal->setfID($res[1]);
    $proposal->setjobID($res[2]);
    $proposal->setisHired($res[6]);
    return $proposal;
  }

  // Added By {Youssef}
  public function getProposal($propID){
    $proposal = False;
    $query = "SELECT * FROM `proposal` WHERE proposal_id = :propID";
    $stmt = $this->core->prepare($query);
    $stmt->execute([':propID' => $propID]);
    $stmt->execute();
    if($stmt->rowCount() > 0){
        $res = $stmt->fetchall()[0];
        $proposal = $this->populateData($res);
    }
    return $proposal;
  }
  // Added By {Youssef}
  public function setHired($propID){
    $query = "UPDATE proposal SET is_hired = 1 WHERE proposal_id = :propID";
    $stmt = $this->core->prepare($query);
    $res = $stmt->execute([':propID' => $propID]);
    return $res;
  }

  // Added By {Youssef}
  public function getProposalByJobID($jobID){
    $proposal = False;
    $query = "SELECT * FROM proposal WHERE job_id = :jobID";
    $stmt = $this->core->prepare($query);
    $stmt->execute([':jobID' => $jobID]);
    if($stmt->rowCount() > 0){
      $res = $stmt->fetchall()[0];
      $proposal = $this->populateData($res);
    }
    return $proposal; 
  }
  
  //Added by zoka
  public function ListProposal($ID){
    $sql = "SELECT freelancer_id from `freelancer` WHERE user_id = ".$ID;
    $stmt = $this->core->query($sql);
    $a= $stmt->fetchAll();

    $sql = "SELECT * FROM `proposal` WHERE `freelancer_id` =".$a[0][0];
    $stmt = $this->core->query($sql);
    return $stmt->fetchAll();
  }
  public function listClientJobProposal($jobID, $clientID){
    $sql = "SELECT * from proposal where job_id = :jobID AND job_id in(SELECT job_id from job where client_id = :clientID) AND available = 1";
    $stmt = $this->core->prepare($sql);
    $stmt->execute([':jobID' => $jobID, ':clientID'=>$clientID]);
    return $stmt->fetchAll();

  }
  //added by Mina
  public function notifyClientwProposal($jobID,$freelancerID,$proposalMsg){
    global $Config;
        $query = "SELECT * FROM job WHERE job.job_id = :jobID";
        $stmt = $this->core->prepare($query);
        $stmt->execute([':jobID'=>$jobID]);
        $assoArray = $stmt->fetchAll()[0];
        
        //$clientID = $assoArray['client_id'];// this not intialized !!!!!
        $hold = (int)(new Client)->getUserClientID($assoArray['client_id']);
        $query2 = "SELECT * FROM user join client  Where user.user_id = client.user_id and client.user_id = :hold";
        $stmt2 = $this->core->prepare($query2);$stmt2->execute([':hold'=>$hold]);
        $clientAssoArray = $stmt2->fetchAll()[0];
        
        
        
        $query3 = "SELECT * FROM user join freelancer  Where user.user_id = freelancer.user_id and freelancer.freelancer_id =:freelancerID";
        $stmt3 = $this->core->prepare($query3);$stmt3->execute([':freelancerID'=>$freelancerID]);
        $freelancerAssoArray = $stmt3->fetchAll()[0];        
        
        
        $jobTitle         = $assoArray['title'];
        $jobPage          = "http://{$Config['WebsiteLink']}/job.php?jobid=".$jobID."";
        $clientName       = $clientAssoArray['fullname']; 
        $clientEmail      = $clientAssoArray['email'];
        $freelancerName   = $freelancerAssoArray['fullname']; 
        $freelancerEmail  = $freelancerAssoArray['email'];
        $freelancerGender = $freelancerAssoArray['gender'];
        $callOn           = $freelancerGender=='M'? "his":"her";
        
    $mail = new Mail;
    $subject = "{$Config['WebsiteName']} - Proposal Notification";
    $msg  = "Hello, ". ucwords($clientName) ."<br>";
        $msg .= "Freelancer ". $freelancerName ."has just proposed to your Job < " . $jobTitle." ><br>";
        $msg .= "Saying ".$proposalMsg;
    $msg .= "<br>You can check the terms of ".$callOn." proposal in your job page -->  <a href='".$jobPage."' >".$jobTitle."</a><br>";
        $msg .= "if you want to intiate any further negotiations with ".$callOn." Use ".$callOn." Email ---->".$freelancerEmail;
    
    return $mail->sendMail($clientEmail, $subject, $msg);   
    }
  public function addProposal(){

    $query = "INSERT INTO proposal (freelancer_id, job_id, message, milestones, is_viewed, is_hired, hour_amount, price, available)
				  VALUES (:fID, :jobID, :propMessage, :milestones, :isViewed, :isHired, :hourAmount, :price, :isAvailable)";

		$stmt = $this->core->prepare($query);
    
		$res = $stmt->execute([
			':jobID' => $this->getjobID(),
			':fID' => $this->getfID(),
			':milestones' => $this->getmilestones(),
			':propMessage' => $this->getpropMessage(),
			':isViewed' => $this->getisViewed(),
      ':isHired' => $this->getisHired(),
      ':hourAmount' => $this->gethourAmount(),
      ':price' => $this->getprice(),
      ':isAvailable' => $this->getisAvailable()
		]);
		return $res;

  }
  public function proposalExists($freelancerID, $jobID){
    $res = FALSE;
    $checkQuery = "SELECT * from proposal where freelancer_id = :freelancerID and job_id = :jobID";
    $stmt = $this->core->prepare($checkQuery);
    $stmt->execute(
      [
        ':freelancerID' => $freelancerID,
        ':jobID' => $jobID
      ]);
    return (Boolean) $stmt->rowCount();
  }
  // added by mohamed
  public function selectProposal($id,$job){
      $query = "SELECT * FROM proposal WHERE freelancer_id = ? AND job_id = ? AND available = 1";
      $stmt = $this->core->prepare($query);
      $stmt->execute(array($id,$job));
      $num = $stmt->rowCount();
      return $num;

  }
  // added by mohamed
  public function selectProposalID($id,$job){
      $query = "SELECT * FROM proposal WHERE freelancer_id = ? AND job_id = ? AND available = 1";
      $stmt = $this->core->prepare($query);
      $stmt->execute(array($id,$job));
      $prop = $stmt->fetch();
      return $prop;

  }
  public function deleteProposal($id){
    $query = "UPDATE proposal set `available` = 0 WHERE proposal_id = ?";
    $stmt = $this->core->prepare($query);
    $res = $stmt->execute(array($id));
    return $res;
  }

  //getter & setter
  public function getfID(){
    return $this->fID;
  }
  public function setfID($fID){
    $this->fID = $fID;
  }
  public function getpropID(){
    return $this->propID;
  }
  public function setpropID($propID){
    $this->propID = $propID;
  }
  public function getjobID(){
    return $this->jobID;
  }
  public function setjobID($jobID){
    $this->jobID = $jobID;
  }
  public function getprice(){
    return $this->price;
  }
  public function setprice($price){
    $this->price = $price;
  }
  public function getmilestones(){
    return $this->milestones;
  }
  public function setmilestones($milestones){
    $this->milestones = $milestones;
  }
  public function getpropMessage(){
    return $this->propMessage;
  }
  public function setpropMessage($propMessage){
    $this->propMessage = $propMessage;
  }
  public function gethourAmount(){
    return $this->hourAmount;
  }
  public function sethourAmount($hourAmount){
    $this->hourAmount = $hourAmount;
  }
  public function getisHired(){
    return $this->isHired;
  }
  public function setisHired($isHired){
    $this->isHired = $isHired;
  }
  public function getisViewed(){
    return $this->isViewed;
  }
  public function setisViewed($isViewed){
    $this->isViewed = $isViewed;
  }
  public function getisAvailable(){
    return $this->isAvailable;
  }
  public function setisAvailable($isAvailable){
    $this->isAvailable = $isAvailable;
  }


}
