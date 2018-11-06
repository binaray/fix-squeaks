<?php
//sample query: ajax/c-csell?item=52&properties=[%22Spontaneously%20combusts%22,%22Small%22]&price=6.90&quantity=2&telegramId=1 (item w/ property type)
// ajax/c-csell?item=29&price=5&quantity=100&telegramId=1 (single item type)
require_once "../_config.php";

$itemId = $_GET["item"];
if (isset($_GET["properties"])) $properties = $_GET["properties"];
else $properties = null;
$price = $_GET["price"];
$quantity = $_GET["quantity"];
$telegramId = $_GET["telegramId"];

require_once "telegramcheck.php";

if (isset($userId)){
	$sql = "INSERT INTO Listings (userId, itemId, properties, price, quantity) VALUES (?, ?, ?, ?, ?)";

	if($stmt = mysqli_prepare($link, $sql)){
		// Bind variables to the prepared statement as parameters
		mysqli_stmt_bind_param($stmt, "sssss", $userId, $itemId, $properties, $price, $quantity);
		
		// Attempt to execute the prepared statement
		if(mysqli_stmt_execute($stmt)){
			echo "Item successfully listed";
		} else{
			echo "Something went wrong. Please try again later.";
		}
	}
	mysqli_stmt_close($stmt);
}

mysqli_close($link);
?>