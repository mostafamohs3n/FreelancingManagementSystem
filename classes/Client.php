<?php

Class Client extends User{

	private $userID, $core;

	function __construct(){
		
		$this->core = Core::getConnection();
	}

	public function populateClient($userID){
		$this->userID = $userID;
	}
	public function addClient(){
		$res = FALSE;
		$checkQuery = "SELECT EXISTS(SELECT :userID FROM client WHERE user_id = :userID)";
		$checkResult = $this->core->prepare($checkQuery)->execute([':userID' => $this->getUserID()]);
		if($checkResult){
			$saveQuery = "INSERT INTO client (user_id) VALUES (:userID)";
			$stmt = $this->core->prepare($saveQuery);
			$res = $stmt->execute([':userID' => $this->getUserID()]);
		}
		return $this->core->lastInsertId('client_id');
	}

	public function getClient($clientID){
		$query = "SELECT * FROM client WHERE client_id = :clientID";
		$stmt = $this->core->prepare($query);
		$stmt->execute([':clientID' => $clientID]);
		$res = $stmt->fetch();
		return $res;
	}
	public function getUserClientID($userID){
		$query = "SELECT client_id from client where user_id = :userID ";
		$stmt = $this->core->prepare($query);
		$stmt->execute([':userID'=> $userID]);
		return $stmt->fetch()[0];
	}
	public function getClientUserID($clientID){
		$query = "SELECT user_id from client where client_id = :clientID";
		$stmt = $this->core->prepare($query);
		$stmt->execute([':clientID'=> $clientID]);
		return $stmt->fetch()[0];	

	}



	// Getter and Setter
	public function getUserID(){
		return $this->userID;
	}
	
	public function setUserID($userID){
		$this->userID = $userID;
	}

	function getClientID(){
		return $this->clientID;
	}

	function setClientID($clientID){
		$this->clientID = $clientID;
	}

}