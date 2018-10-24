<?php
//USAGE: Returns -all- items in json format
//GET PARAMETERS (OPTIONAL): category
require_once "../_config.php";

session_start();

// if (isset($_SESSION['email'])){
	if (!isset($_SESSION["cart"])) $_SESSION["cart"]=array();
	array_push($_SESSION["cart"],$_POST["item"]);
	print_r($_SESSION);
// }
// else {
	// echo "Please login!";
// }
?>