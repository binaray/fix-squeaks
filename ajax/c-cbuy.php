<?php
require_once "../_config.php";

$listingId = $_GET["listingId"];
$quantity = $_GET["quantity"];
$telegramId = $_GET["telegramId"];

require_once "telegramcheck.php";

//add to users orders
$sql = "INSERT INTO UserOrders (listingId, buyerId, quantity, status) VALUES (?, ?, ?, ?)";	
if($stmt = mysqli_prepare($link, $sql)){
	// Bind variables to the prepared statement as parameters
	mysqli_stmt_bind_param($stmt, "ssss", $listingId, $buyerId, $quantity, $status);
	
	$buyerId = $userId;
	$status = "PENDING";
	
	if(mysqli_stmt_execute($stmt)){
		echo "Successfully ordered!\n";
	} else{
		echo "Something went wrong. Please try again later.";
	}
	mysqli_stmt_close($stmt);
}

//update stock

mysqli_close($link);
?>