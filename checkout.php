<?php
session_start();
// if (!isset($_SESSION['email'])){
	// header("location: ../");
// }
//Shopping cart options
//---------------------------
//Reset
if (isset($_GET['reset'])){
	if ($_GET["reset"] == 'true'){
		unset($_SESSION["cart"]); 
	}
}

//---------------------------
//Add
if (isset($_GET["add"])){
	$_SESSION["cart"][$i] = $i;
}

//---------------------------
//Delete
if (isset($_GET["delete"])){
	$i = $_GET["delete"];
	$qty = $_SESSION["qty"][$i];
	$qty--;
	$_SESSION["qty"][$i] = $qty;
	//remove item if quantity is zero
	if ($qty == 0){
		$_SESSION["amounts"][$i] = 0;
		unset($_SESSION["cart"][$i]);
	}
	else{
		$_SESSION["amounts"][$i] = $amounts[$i] * $qty;
	}
}

if (isset($_SESSION["cart"])){
	foreach ($_SESSION["cart"] as $item){
		$item = json_decode($item,true);
		var_dump($item);
	}
}


?>