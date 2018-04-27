<?php 
class Job{
	private
	$job_id, $client_id, $title, $description, $price, $hour_amount, $skills_needed, 
	$creation_date, $duedate, $milestones, $payment_type, $isOpen;

	private $core; 
	public function __construct(){
		
		$this->core = Core::getConnection();
	}
	public function populateObject($client_id, $title, $description, $price, $hour_amount, $skills_needed, $creation_date, $duedate, $milestones ,$payment_type, $isOpen){
		$this->client_id = $client_id;
		$this->title = $title;
		$this->description = $description;
		$this->price = $price;
		$this->hour_amount = $hour_amount;
		$this->skills_needed = $skills_needed;
		$this->creation_date = $creation_date;
		$this->duedate = $duedate;
		$this->milestones = $milestones;
		$this->payment_type = $payment_type;
		$this->isOpen = $isOpen;
	}
	public function getJob($job_id){
		$query = "SELECT * FROM `job` where job_id = :job_id";
		$stmt = $this->core->prepare($query);
		$stmt->execute(['job_id' => $job_id]);
		$res = $stmt->fetchAll()[0];
		$job = $this->populateData($res);
		// var_dump($res);
		return $job;
	}

	public function getJobsByClient($clientID){
		$query = "SELECT * FROM `job` where client_id = :clientID and isOpen=1";
		$stmt = $this->core->prepare($query);
		$stmt->execute(['clientID' => $clientID]);
		$res = $stmt->fetchAll();
		return $res;
	}
	public function getJobByContract($contractID){
		$query = "SELECT * FROM `job` where job_id in(SELECT job_id from proposal where proposal_id in(SELECT proposal_id from contract where contract_id =:contractID)) ";
		$stmt = $this->core->prepare($query);
		$stmt->execute(['contractID' => $contractID]);
		$res = $stmt->fetchAll();
		return $res;
	}
	private function populateData($res){
		// echo "<pre>";
		// print_r($res);
		// echo "</pre>";
		$job = new Job;
		$job->populateObject($res[1], $res[2], $res[3], $res[4], $res[5], $res[6], $res[7], $res[8], $res[9], $res[10], $res[11]);
		$job->setJob_id($res[0]);
		//var_dump($job);
		return $job;
	}

	public function addJob(){
		$query = "INSERT INTO job (client_id, title, description, price, hour_amount, skills_needed, creation_date, duedate, milestones, payment_type, isOpen)
				  VALUES ( :client_id, :title, :description, :price, :hour_amount, :skills_needed, :creation_date, :duedate, :milestones, :payment_type, :isOpen)
		";
		$stmt = $this->core->prepare($query);
		//var_dump($stmt);

		$res = $stmt->execute([
			':client_id' => $this->getClient_id(),
			':title' => $this->getTitle(),
			':description' => $this->getDescription(),
			':price' => $this->getPrice(),
			':hour_amount' => $this->getHour_amount(),
			':skills_needed' => $this->getSkills_needed(),
			':creation_date' => $this->getCreation_date(),
			':duedate' => $this->getDuedate(),
			':milestones' => $this->getMilestones(),
			':payment_type' => $this->getPayment_type(),
			':isOpen' => $this->getIsOpen(),
		]);
		return $res;
	}

	//added by mohamed
	public function searchJobs($value, $sort, $List){
		$sortType = "ASC"; $ListType = "price";
		/** Modified Code **/
		switch($sort){
			case "ASC":
				$sortType = "ASC";
			break;

			case "DESC":
				$sortType = "DESC";
			break;
		}

		switch($List){
			case "Price":
				$ListType = "price";
			break;

			case "CreationDate":
				$ListType = "creation_date";
			break;
		}

		$sql = "SELECT * FROM `job` where `isOpen` = 1 AND (	`title` Like :value OR `price` Like :value OR `hour_amount` Like :value OR `skills_needed` Like :value ) ORDER BY `$ListType` $sortType" ;
		$stmt = $this->core->prepare($sql);
		//$values = "%".$value;
		$stmt->execute([':value' => "%".$value. "%"]);
		// $stmt = $this->core->query($sql);
		return $stmt->fetchAll();
	}



	// add by zoka
	public function getAllJob($Sort, $List){
		$sortType = "ASC"; $ListType = "price";
		/** Modified Code **/
		switch($Sort){
			case "ASC":
				$sortType = "ASC";
			break;

			case "DESC":
				$sortType = "DESC";
			break;
		}

		switch($List){
			case "Price":
				$ListType = "price";
			break;

			case "CreationDate":
				$ListType = "creation_date";
			break;
		}
		$sql = "SELECT * FROM `job` where `isOpen` = 1 ORDER BY `$ListType` $sortType";
		$stmt = $this->core->query($sql);
		return $stmt->fetchAll();
		/** End - Modified Code **/
		/** Zoka Code **
		if($Sort == "ACS" && $List == "Price"){
			$sql = "SELECT * FROM `job` WHERE `isOpen` = 1 ORDER BY `price` ASC";
			$stmt = $this->core->query($sql);
			return $stmt->fetchAll();

		}else if($Sort == "ACS" && $List == "CreationDate"){
			$sql = "SELECT * FROM `job` WHERE `isOpen` = 1 ORDER BY `creation_date` ASC";
			$stmt = $this->core->query($sql);
			return $stmt->fetchAll();
		}else if($Sort == "DECS" && $List == "Price"){
			$sql = "SELECT * FROM `job` WHERE `isOpen` = 1 ORDER BY `price` DESC";
			$stmt = $this->core->query($sql);
			return $stmt->fetchAll();
		}else if($Sort == "DECS" && $List == "CreationDate"){
			$sql = "SELECT * FROM `job` WHERE `isOpen` = 1 ORDER BY `creation_date` DESC";
			$stmt = $this->core->query($sql);
			return $stmt->fetchAll();
		}
		* End Zoka Code **/
		
	}

	//end added

	public function CloseJob($jobID){
		$query = "UPDATE `job` SET `isOpen` = '0' WHERE job_id = :job_id";
		$stmt = $this->core->prepare($query);
		$stmt->execute([':job_id' => $this->job_id]);
	}


	public function getJob_id(){
		return $this->job_id;
	}
	public function setJob_id($job_id){
		$this->job_id = $job_id;
	}
	public function getClient_id(){
		return $this->client_id;
	}
	public function setClient_id($client_id){
		$this->client_id = $client_id;
	}
	public function getTitle(){
		return $this->title;
	}
	public function setTitle($title){
		$this->title = $title;
	}
	public function getDescription(){
		return $this->description;
	}
	public function setDescription($description){
		$this->description = $description;
	}
	public function getPrice(){
		return $this->price;
	}
	public function setPrice($price){
		$this->price = $price;
	}
	public function getHour_amount(){
		return $this->hour_amount;
	}
	public function setHour_amount($hour_amount){
		$this->hour_amount = $hour_amount;
	}
	public function getSkills_needed(){
		return $this->skills_needed;
	}
	public function setSkills_needed($skills_needed){
		$this->skills_needed = $skills_needed;
	}
	public function getCreation_date(){
		return $this->creation_date;
	}
	public function setCreation_date($creation_date){
		$this->creation_date = $creation_date;
	}
	public function getDuedate(){
		return $this->duedate;
	}
	public function setDuedate($duedate){
		$this->duedate = $duedate;
	}
	public function getMilestones(){
		return $this->milestones;
	}
	public function setMilestones($milestones){
		$this->milestones = $milestones;
	}
	public function getPayment_type(){
		return $this->payment_type;
	}
	public function setPayment_type($payment_type){
		$this->payment_type = $payment_type;
	}
	public function getIsOpen(){
		return $this->isOpen;
	}
	public function setIsOpen($isOpen){
		$this->isOpen = $isOpen;
	}
}
?>