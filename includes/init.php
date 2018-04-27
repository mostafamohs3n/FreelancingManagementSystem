<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
if(!isset($_SESSION['loggedIn']))
	$_SESSION['loggedIn'] = false;
require_once('config.inc.php');

$classes= ['Core','Mail', 'User', 'Client', 'Contract',  'Feedback', 'Freelancer', 'Job', 'Proposal', 'Misc', 'Statistics', 'pdf'];

foreach($classes as $class){
	include_once("classes/$class.php");
}

$core = new Core($Config["Host"], $Config["User"], "", $Config["dbName"]);
$Misc = Misc::Singleton(); // the only instance we need! YES. THIS IS OUR SINGLETON!
if(!$core)exit;