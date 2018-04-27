<?php
class Feedback
{
    /*Start Attributes*/
    private $contractID, $clientID, $freelancerID, $amountAgreed, $testimonial, $rating;

    private $core; 
    /*End Attributes*/
    /*Start Constructor*/
    public function __construct()
    {    
       

        $this->core = Core::getConnection();
    }
    /*End Constructor*/
    /*Start Setter And Getters*/
    public function populateObject($contractID, $clientID,$freelancerID,$amountAgreed,$testimonial, $rating){
        $this->contractID = $contractID;
        $this->clientID = $clientID;
        $this->freelancerID = $freelancerID;
        $this->amountAgreed = $amountAgreed;
        $this->testimonial = $testimonial;
        $this->rating = $rating;
    }
    public function getContractID(){return $this->contractID;}
    public function setContractID($contractID){$this->contractID = $contractID;}
    public function getClientID(){return $this->ClientID;}
    public function setClientID($ClientID){$this->ClientID = $ClientID;}
    public function getFreelancerID(){return $this->freelancerID;}
    public function setFreelancerID($freelancerID){$this->freelancerID = $freelancerID;}
    public function getAmountAgreed(){return $this->amountAgreed;}
    public function setAmountAgreed($amountAgreed){$this->amountAgreed = $amountAgreed;}
    public function getTestimonial(){return $this->testimonial;}
    public function setTestimonial($testimonial){$this->testimonial = $testimonial;}
    public function getRating(){return $this->rating;}
    public function setRating($rating){$this->rating = $rating;}
    /*End Setter And Getters*/

    /*Start Functions that Interact With the Database*/
    public function addFeedback()
    {
        $res = False;
        $myAddQuery = "INSERT INTO Feedback (contract_id, client_id, freelancer_id, amount_agreed, testimonial, rating) VALUES(:contract_id, :client_id, :freelancer_id, :amount_agreed, :testimonial, :rating)";
        $stmt = $this->core->prepare($myAddQuery);
        $res = $stmt->execute([
            ':contract_id' => $this->getContractID(),
            ':client_id' => $this->getClientID(),
            ':freelancer_id' => $this->getFreelancerID(),
            ':amount_agreed' => $this->getAmountAgreed(),
            ':testimonial' => $this->getTestimonial(),
            ':rating' => $this->getRating()
        ]);
        
        return $res;
    }
    public function getFeedbacks($freelancerId)
    {
        $myQuery   = "SELECT * FROM Feeback WHERE freelancer_id = :freelancerID";
        $stmt      = $this->core->prepare($myQuery);
        $stmt->execute([':freelancerID' => $freelancerId]);
        $FeedbacksRecords = $stmt->fetchAll();
        $FeedbacksObjs = array();
        foreach($FeedbacksRecords as $FeedbackRecord) 
        {
            $FeedbackObject = new Feedback(
                $FeedbackRecord['id'],
                $freelancerId,
                $FeedbackRecord['$contractId'],
                $FeedbackRecord['$testimonial'],
                $FeedbackRecord['$amountAgreed']
            );
            array_push($FeedbacksObjs,$FeedbackObject);
        }
        return $FeedbacksObjs;
    }
    /*End Functions that Interact With the Database*/
}
?>