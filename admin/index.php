<?php
require_once "../_config.php";
session_start();
if (!isset($_SESSION['admin'])) header("location: ../");
if (isset($_GET['logout'])){
	if ($_GET['logout']) unset($_SESSION['admin']);
	header("location: ../");
}

//insert code/html here
?>

<a href="?logout=true">Logout</a>