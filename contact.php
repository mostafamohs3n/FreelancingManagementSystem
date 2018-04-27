<?php $pageTitle ="Contact Us ! "; include("includes/header.inc.php");?>
<link rel="stylesheet" href="http://propeller.in/components/button/css/button.css">
<script type="text/javascript" src="http://propeller.in/components/button/js/ripple-effect.js"></script>
<div class="container">
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
        <span class="label label-info material-label material-label_info" style="margin-bottom:20px;" >Send Us A Message</span>
        <div class="form-group materail-input-block materail-input-block_primary materail-input_slide-line">
         <textarea name="msg" class="form-control materail-input material-textarea" placeholder="Write Your Message Here" style="margin-bottom:40px;"></textarea>
         <span class="materail-input-block__line"></span>
            <button class="btn pmd-btn-flat btn-primary btn-block pmd-ripple-effect btn-lg" type="button" id="submit">Send</button>
        </div>
        <input id="submitInput" type="submit" hidden>
    </form>
<script>document.getElementById('submit').onclick=function(){document.getElementById('submitInput').click();}</script>
<?php
if($_SERVER['REQUEST_METHOD']=="POST")
{        
    if(preg_replace("/\s+/", '', $_POST['msg'])===""){$Misc->displayErrorMsg('emptyfields');}
    else
    {
        //$usrObj = (new User)->getUserByID($_SESSION['userID']);
        $userID = isset($_SESSION['userID']) ? $_SESSION['userID'] : "";
        $mail = new Mail;$mail->sendMail("mostafa.mohsen73@gmail.com","Feedback from user ".$userID,$_POST['msg']);
        $Misc->displaySuccessMsg("msgSent");
    }
}
echo "</div>";
include("includes/footer.inc.php");
?>