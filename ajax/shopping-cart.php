<?php
require_once "../_config.php";
session_start();

if (!isset($_SESSION["cart"])) $_SESSION["cart"]=array();
array_push($_SESSION["cart"],$_POST["item"]);
print_r($_SESSION);
?>