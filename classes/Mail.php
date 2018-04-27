<?php
include('PHPMailer/PHPMailer.php');
include('PHPMailer/Exception.php');
include('PHPMailer/SMTP.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
class Mail{
	private $mail = null;
	public function __construct(){
		$this->mail = new PHPMailer(true);
		$this->mail->SMTPDebug=0;
		$this->mail->isSMTP();
		$this->mail->Host = 'smtp.gmail.com';
		$this->mail->SMTPAuth = true;
		$this->mail->Username = 'softwareengineering1.429@gmail.com'; 
		$this->mail->Password = 'Test12345!'; 
		$this->mail->Port = 587; 
		$this->mail->setFrom('sw1@fcih.com', 'Freelancer Website');
		$this->mail->isHTML(true); 
		$this->mail->SMTPSecure = 'tls';  
		$this->mail->addReplyTo('info@fcih.com', 'Information'); 
	}

	public function sendMail($to, $subject, $body ){
		try {                                

		    $this->mail->addAddress($to); 

		    $this->mail->Subject = $subject;
		    $this->mail->Body    = $body;

		    $this->mail->send();
		    	return 1;
			} catch (Exception $e) {
			    return 0;
			}
	}
}