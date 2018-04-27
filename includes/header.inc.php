<?php
include_once("includes/init.php");
?>
<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title><?=$Config["WebsiteName"];?> | <?=$pageTitle;?></title>
      <!-- Bootstrap -->
      <link href="assets/css/bootstrap.min.css" rel="stylesheet">
      <link href="assets/css/material_theme.css" rel="stylesheet">
      <link href="assets/css/bootstrap-datepicker.min.css" rel="stylesheet">
      <link href="assets/css/main.css" rel="stylesheet">

     <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
     <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
     <!-- Include all compiled plugins (below), or include individual files as needed -->
     <script src="assets/js/bootstrap.min.js"></script>

   </head>
   <body>
      <nav class="navbar material-navbar material-navbar_primary navbar-fixed-top">
   <div class="container">
      <div class="navbar-header material-navbar__header">
         <button class="navbar-toggle materail-navbar__toogle collapsed" data-toggle="collapse" data-target="#navbar-navbar_primary">
         <span class="icon-bar materail-navbar__icon-bar"></span>
         <span class="icon-bar materail-navbar__icon-bar"></span>
         <span class="icon-bar materail-navbar__icon-bar"></span>
         </button>
         <a class="navbar-brand material-navbar__brand" href="index.php"><?=$Config['WebsiteName'];?></a>
      </div>
      <div class="collapse navbar-collapse materil-navbar__collapse" id="navbar-navbar_primary">
         <ul class="nav navbar-nav navbar-right material-navbar__nav">
          <?php if($_SESSION['loggedIn'] == false){?>
            <li><a href="register.php" class="material-navbar__link">Register</a></li>
            <li><a href="login.php" class="material-navbar__link">Login</a></li>
            
            <li><a href="contact.php" class="material-navbar__link">Contact Us</a></li>
            <?php }else if($_SESSION['userType'] == 'freelancer' || $_SESSION['userType'] == 'client'){ 
              $userType = $_SESSION['userType'];
              if($userType == 'freelancer'){
            ?>
            <?php }elseif($userType == 'client'){ ?>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle material-navbar__link" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Jobs <span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li><a href="job.php?action=list">List Jobs</a></li>
                <li><a href="job.php?action=add">Add Jobs</a></li>
                <!-- <li><a href="#">Action</a></li> -->
              </ul>
            </li>

            <?php }
            ?>
            <li><a href="notify.php" class="material-navbar__link">Notification<span style="background: #f00;
                  padding-left: 5px;padding-right: 5px;font-weight: bold;position: absolute;top: 8px;"><?php 
              $user = new User;
              echo $user->numnotify($_SESSION['userID']);
            ?></span></a></li>
            <li><a href="contract.php" class="material-navbar__link">Contracts</a></li>
            <li><a href="messages.php" class="material-navbar__link">Messages</a></li>
            <li><a href="account.php" class="material-navbar__link">Account</a></li>
            <li><a href="logout.php" class="material-navbar__link">Logout</a></li>
            <?php
             }?>
         </ul>
      </div>
   </div>
</nav>