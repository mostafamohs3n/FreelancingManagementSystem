<?php

	class Statistics{
		private $core;
		function __construct(){
			$this->core = Core::getConnection();
		}
		public function staticfreelancer(){
		    $query = "SELECT * FROM freelancer";
		    $stmt = $this->core->prepare($query);
		    $stmt->execute();
		    return $stmt->rowCount();
	  	}

	  	public function staticclient(){
		    $query = "SELECT * FROM client";
		    $stmt = $this->core->prepare($query);
		    $stmt->execute();
		    return $stmt->rowCount();
	  	}


	  	public function staticjob(){
		    $query = "SELECT * FROM job";
		    $stmt = $this->core->prepare($query);
		    $stmt->execute();
		    return $stmt->rowCount();
	  	}

	  	public function staticcontract(){
		    $query = "SELECT * FROM contract";
		    $stmt = $this->core->prepare($query);
		    $stmt->execute();
		    return $stmt->rowCount();
	  	}

	  	public function staticproposal(){
		    $query = "SELECT * FROM proposal";
		    $stmt = $this->core->prepare($query);
		    $stmt->execute();
		    return $stmt->rowCount();
	  	}


	  	public function staticfeedback(){
		    $query = "SELECT * FROM feedback";
		    $stmt = $this->core->prepare($query);
		    $stmt->execute();
		    return $stmt->rowCount();
	  	}


	  	public function staticmessage(){
		    $query = "SELECT * FROM message";
		    $stmt = $this->core->prepare($query);
		    $stmt->execute();
		    return $stmt->rowCount();
	  	}
	


	}

?>