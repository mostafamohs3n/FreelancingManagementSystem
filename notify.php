<?php
$pageTitle = "Notification";
include ("includes/header.inc.php");
if(!isset($_SESSION['userType']) || !isset($_SESSION['loggedIn']))exit;
?>
<div class="container">
	<div class="notifys" style="margin-top: 60px;">
		
	
	<?php
		$user = new User;
		$notifications = $user->shownotify($_SESSION['userID']);
		foreach ($notifications as $n) {
			echo "<div class='notify'>";
				echo "<div>";
					echo "<p class='pull-left'>".$n['fullname']."</p>";
					echo "<p class='pull-right'>".$n['notify_date']."</p>";
				echo "</div>";
				echo "<div>";
					echo "<img class='pull-left' style='width: 100px;height: 100px;border-radius: 50%;' src='profile_picture/".$n['profile_picture']."'>";
					echo $n['content'];
				echo "</div>";
				
			echo "</div>";
		}
	?>
	</div>
</div>

<?php
include("includes/footer.inc.php");
?>