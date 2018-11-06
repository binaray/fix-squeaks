<?php
require_once "../_config.php";

$additional_result_text="";

$itemId = $_GET["item"];
if (isset($_GET["properties"])) $properties = $_GET["properties"];
$quantity = $_GET["quantity"];
$telegramId = $_GET["telegramId"];

require_once "telegramcheck.php";

if (isset($userId)){
	$itemsBought = array();
	$item = array();
	
	$sql="SELECT * FROM Inventory WHERE itemId = {$_GET["item"]}";
	$result = $link->query($sql);
	while($row = $result->fetch_assoc()) {
		if ($result->num_rows== 0) echo "No such item!";
		
		$itemName = $row["itemName"];
		$items = json_decode($row["items"], true);
	}
	
	$item["itemId"] = $itemId;
	$item["itemName"] = $itemName;
	if (isset($_GET["properties"])) 
		$item["properties"] = json_decode($properties);
	$item["quantity"] = $quantity;
	if (isset($_GET["properties"])) 
		$item["price"] = $items[$properties]["price"];
	else 
		$item["price"] = $items["price"];
	
	array_push($itemsBought,$item);
	
	$itemsBought = json_encode($itemsBought);
	$status = "PENDING";
	
	$sql = "INSERT INTO Orders (userId, itemsBought, status) VALUES (?, ?, ?)";	
	if($stmt = mysqli_prepare($link, $sql)){
		mysqli_stmt_bind_param($stmt, "sss", $userId, $itemsBought, $status);
		
		if(mysqli_stmt_execute($stmt)){
			echo "Successfully ordered!\n".$additional_result_text;
		} else{
			echo "Something went wrong. Please try again later.";
		}
	}
	mysqli_stmt_close($stmt);
	
	//update b-c stock
}
mysqli_close($link);
?>