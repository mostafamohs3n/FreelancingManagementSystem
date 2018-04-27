<?php
class Freelancer extends User{

  private
  $userID, $username, $skills, $portfolio, $review, $languages,
  $pricePerHour, $rating, $Points, $balance, $education;
  private $core;



  function __construct(){
		$this->core = Core::getConnection();
	}

  public function populateFreelancer($userID, $username, $pricePerHour, $review, $education, $languages, $rating, $portfolio, $skills){
    $this->userID = $userID;
    $this->username = $username;
    $this->skills = $skills;
    $this->portfolio = $portfolio;
    $this->review = $review;
    $this->pricePerHour = $pricePerHour;
    $this->education = $education;
    $this->languages = $languages;
    $this->rating = $rating;
  }
    // Added By {Youssef}
  private function populateData($res){
    $freelancer = new Freelancer;
    $freelancer->setUsername($res[2]);
    $freelancer->setUser_id($res[1]);
    return $freelancer;
  }
  // added by mohamed
  public function getallfree(){
    $query = "SELECT * FROM freelancer";
    $stmt = $this->core->prepare($query);
    $stmt->execute();
    return $stmt->fetchall();
  }

  // Added By {Youssef}
  public function getFreelancer($fID){
    $freelancer = False;
    $query = "SELECT * FROM freelancer WHERE freelancer_id = :fID";
    $stmt = $this->core->prepare($query);
    $stmt->execute([':fID' => $fID]);
    if($stmt->rowCount() > 0){
      $res = $stmt->fetchall()[0];
      $freelancer = $this->populateData($res);
    }
    return $freelancer;
  }
  
  public function addFreelancer(){
    $res = FALSE;
    $checkQuery = "SELECT EXISTS(SELECT :userID FROM freelancer WHERE user_id = :userID)";
    $checkResult = $this->core->prepare($checkQuery)->execute([':userID' => $this->getUserID()]);
    if($checkResult){
      $saveQuery = "INSERT INTO freelancer (user_id, username, price_per_hour, review, education, languages, over_all_rating, portfolio, skills)
       VALUES (:userID, :username, :pricePerHour, :review, :education, :languages, :rating, :portfolio, :skills)";
      $stmt = $this->core->prepare($saveQuery);
      $res = $stmt->execute(
        [
          ':userID' => $this->getUserID(),
          ':username' => $this->getUsername(),
          ':pricePerHour' => $this->getPricePerHour(),
          ':review' => $this->getReview(),
          ':education' => $this->getEducation(), 
          ':languages' => $this->getLanguages(),
          ':rating' => $this->getRating(),
          ':portfolio' => $this->getPortfolio(),
          ':skills' => $this->getSkills()
        ]);
    }
    if($res){
      $query = "INSERT INTO freelancer_balance(freelancer_id, balance) VALUES (:freelancerID, 0)";
      $stmt = $this->core->prepare($query);
      $stmt->execute([':freelancerID' => Core::getLastInsertedRow()]);
    }
    return $res;
  }
  public function isFreelancer($email){
    $query = "SELECT * FROM freelancer WHERE user_id = (SELECT user_id from user where email = :email)";
    $stmt = $this->core->prepare($query);
    $res = $stmt->execute([':email' => $email]);
    return (Boolean) $stmt->rowCount();
  }

  public function getUserFreelancerID($userID){
    $query = "SELECT freelancer_id from freelancer where user_id = :userID ";
    $stmt = $this->core->prepare($query);
    $stmt->execute([':userID'=> $userID]);
    return $stmt->fetch()[0];
  }
  public function getFreelancerUserID($freelancerID){
    $query = "SELECT user_id from freelancer where freelancer_id = :freelancerID";
    $stmt = $this->core->prepare($query);
    $stmt->execute([':freelancerID'=> $freelancerID]);
    return $stmt->fetch()[0]; 

  }
  public function UpdateContract(){

  }
  public function saveData(){

  }

  public function startWorking(){
    // update start_working_date to current date
  }
  public function pauseWorking(){
    // calc =  current date - start_working_date

  }

  public function IsfreelancerExist(){
    $query = "SELECT * FROM freelancer WHERE freelancer_id = ?";
    $stmt = $this->core->prepare($query);
    $res = $stmt->execute(array($this->getfID()));
    $number = $stmt->rowCount();
    return (Boolean) $number;
  }



  public function getUserID(){
    return $this->userID;
  }
  public function setUser_id($user_id){
  $this->user_id = $user_id;
  }
  public function getUsername(){
  return $this->username;
  }
  public function setUsername($username){
  $this->username = $username;
  }
  public function getSkills(){
  return $this->skills;
  }
  public function setSkills($skills){
  $this->skills = $skills;
  }
  public function getPortfolio(){
  return $this->portfolio;
  }
  public function setPortfolio($portfolio){
  $this->portfolio = $portfolio;
  }
  public function getReview(){
  return $this->review;
  }
  public function setReview($review){
  $this->review = $review;
  }
  public function getLanguages(){
  return $this->languages;
  }
  public function setLanguages($languages){
  $this->languages = $languages;
  }
  public function getPricePerHour(){
  return $this->pricePerHour;
  }
  public function setPricePerHour($pricePerHour){
  $this->pricePerHour = $pricePerHour;
  }
  public function getRating(){
  return $this->rating;
  }
  public function setRating($rating){
  $this->rating = $rating;
  }
  public function getPoints(){
  return $this->Points;
  }
  public function setPoints($Points){
  $this->Points = $Points;
  }
  public function getBalance(){
  return $this->balance;
  }
  public function setBalance($balance){
  $this->balance = $balance;
  }
  public function getEducation(){
  return $this->education;
  }
  public function setEducation($education){
  $this->education = $education;
  }






















}
