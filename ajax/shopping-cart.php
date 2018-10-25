<?php
require_once "../_config.php";
session_start();

if(isset($_POST["item"])){
	if (!isset($_SESSION["cart"])) $_SESSION["cart"]=array();
	array_push($_SESSION["cart"],$_POST["item"]);
}

if(isset($_POST["listedItem"])){
	if (!isset($_SESSION["listedCart"])) $_SESSION["listedCart"]=array();
	array_push($_SESSION["listedCart"],$_POST["listedItem"]);
}

print_r($_SESSION);
?>