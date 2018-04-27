<?php

class Contract{

	private $clientID, $contractID, $proposalID, $startDate, $isFinished, $FStartDate,$totalHoursWorked, $core;

	function __construct(){
		

		$this->core = Core::getConnection();
	}
	public function populateObject($clientID, $proposalID, $startDate, $isFinished, $FStartDate, $totalHoursWorked){
		$this->clientID = $clientID;
		$this->proposalID = $proposalID;
		$this->startDate = $startDate;
		$this->isFinished = $isFinished;
		$this->FStartDate = $FStartDate;
		$this->totalHoursWorked = $totalHoursWorked;
	}

	public function addContract(){
		$res = FALSE;
			$query = "INSERT INTO contract (client_id, proposal_id, start_date, is_finished, start_working_date, total_hours_worked) 
							 VALUES (:clientID, :proposalID, :startDate, :isFinished,  :FStartDate, NULL)";
			$stmt = $this->core->prepare($query);
			$res = $stmt->execute([
					':clientID' => $this->getClientID(),
					':proposalID' => $this->getProposalID(),
					':startDate'  => $this->getStartDate(),
					':isFinished' => 0,
					':FStartDate' => $this->getFStartDate()
				]);
		return $res;
	}

	public function endContract($clientID, $contractID){
		$res = FALSE;
		$query = "UPDATE contract SET `is_finished` = 1 WHERE contract_id = :contractID and client_id = :clientID";
		$stmt = $this->core->prepare($query);
		$res = $stmt->execute([':contractID' => $contractID, ':clientID' => $clientID]);
		// TO DO:: ADD FREELANCER BALANCE
		if($res){
			$query2 = "SELECT price, hour_amount from proposal where proposal_id =(SELECT proposal_id from contract where contract_id = :contractID)";
			$stmt2 = $this->core->prepare($query2);
			$stmt2->execute([':contractID' => $contractID]);
			$result = $stmt2->fetchAll()[0];
			//var_dump($result);
			$price = $result[0];
			$hour_amount = $result[1];
			$total = $price * $hour_amount *0.15;
			// echo $total;
			$query3 = "UPDATE freelancer_balance set balance = balance + :total where freelancer_id IN(SELECT freelancer_id from proposal where proposal_id in(SELECT proposal_id from contract where contract_id =:contractID ))";
			$stmt3 = $this->core->prepare($query3);
			$stmt3->execute([':total' => $total, ':contractID' => $contractID]);
			//$query = "UPDATE freelancer_balance set balance = balance + 
		}
		return $res;
	}
	public function isEndedContract($clientID, $contractID){
		$query ="SELECT is_finished from contract where client_id=:clientID and contract_id=:contractID";
		$stmt = $this->core->prepare($query);
		$res = $stmt->execute([':clientID'=>$clientID, ':contractID' => $contractID]);
		return $stmt->fetch()[0];
	}
	public function getContract($contractID){
		$query = "SELECT * FROM contract WHERE contract_id = :contractID";
		$stmt = $this->core->prepare($query);
		$stmt->execute([':contractID' => $contractID]);
		$res = $stmt->fetchAll();
		return $res;
	}
	public function getFreelancerContracts($freelancerID){
		$query = "SELECT * FROM contract where proposal_id in(SELECT proposal_id from proposal where freelancer_id =:freelancerID)";
		$stmt = $this->core->prepare($query);
		$stmt->execute([':freelancerID'=>$freelancerID]);
		return $stmt->fetchAll();
	}
	public function getClientContracts($clientID){
		$query = "SELECT * FROM contract where client_id =:clientID";
		$stmt = $this->core->prepare($query);
		$stmt->execute([':clientID'=>$clientID]);
		return $stmt->fetchAll();
	}
	//counter status 1 = started, 0 = stopped
	public function getCounterStatus($contractID){
		$query = "SELECT counter_status from contract where contract_id = :contractID ";
		$stmt = $this->core->prepare($query);
		$stmt->execute([':contractID' => $contractID]);
		return $stmt->fetch()[0];
	}
	public function toggleCounter($contractID){
		$query ="SELECT counter_status, start_working_date, total_hours_worked FROM contract where contract_id = :contractID";
		$stmt = $this->core->prepare($query);
		$stmt->execute([':contractID' => $contractID]);
		$res = $stmt->fetchAll();
		$dateNow = date('Y-m-d h:i:s');
		$startWorkingDate = $res[0][1];
		$totalHoursWorked = $res[0][2] ? $res[0][2] : 0;
		if($res[0][0] == 0){
			// update start_working_date + update counter status
			$query2 = "UPDATE contract set start_working_date = :dateNow, counter_status=1 where contract_id = :contractID";
			$stmt = $this->core->prepare($query2);
			return  $stmt->execute([':dateNow'=>$dateNow, ':contractID' =>$contractID]);
		}else{

			$hourdiff = round((strtotime($dateNow) - strtotime($startWorkingDate))/3600, 1);
			$query2 = "UPDATE contract set counter_status=0, total_hours_worked = total_hours_worked +  :hoursWorked where contract_id = :contractID";
			$stmt = $this->core->prepare($query2);
			return  $stmt->execute([':hoursWorked'=>$hourdiff, ':contractID' =>$contractID]);
			// get start_working_date - dateNOW + update counter status + total_hours_worked

		}
		// var_dump($res[0]);
	}
	/*
	public function getAllContracts(){ // add $freelancer ID
		$query = "SELECT * FROM contract";
		$stmt = $this->core->prepare($query);
		$stmt->execute();
		$res = $stmt->fetchAll();
		return $res;
	}
	*/
	


	// Getter and Setter
	function getClientID(){
		return $this->clientID;
	}

	function setClientID($clientID){
		$this->clientID = $clientID;
	}

	function getContractID(){
		return $this->contractID;
	}

	function setContractID($contractID){
		$this->contractID = $contractID;
	}

	function getProposalID(){
		return $this->proposalID;
	}

	function setProposalID($proposalID){
		$this->proposalID = $proposalID;
	}

	function getStartDate(){
		return $this->startDate;
	}

	function setStartDate($startDate){
		$this->startDate = $startDate;
	}

	function getIsFinished(){
		return $this->isFinished;
	}

	function setIsFinished($isFinished){
		$this->isFinished = $isFinished;
	}

	function getFStartDate(){
		return $this->FStartDate;
	}

	function setFStartDate($FStartDate){
		$this->FStartDate = $FStartDate;
	}

	function getFeedbackID(){
		return $this->feedbackID;
	}

	function setFeedbackID($feedbackID){
		$this->feedbackID = $feedbackID;
	}
}