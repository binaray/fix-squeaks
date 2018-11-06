<?php
require_once "../_config.php";

$additional_result_text="";

$itemId = $_GET["item"];
if (isset($_GET["properties"])) $properties = $_GET["properties"];
$quantity = $_GET["quantity"];
$telegramId = $_GET["telegramId"];

//check if user exists otherwise add new user
$sql = "SELECT userId, email FROM Users WHERE telegramId = {$telegramId}";
$result = $link->query($sql);

if ($result->num_rows == 0) {
	echo "creating new user";
	//create new user with only telegramId
	$param_telegramId = $telegramId;
	$sql = "INSERT INTO Users (telegramId) VALUES (?)";		
	
	mysqli_query($link, "INSERT INTO Users (telegramId) VALUES ('{$telegramId}}')");
	$userId = mysqli_insert_id($link);
	
	$additional_result_text .= "To receive receipts you need to register your email at this url: \n";
} 
else {
	while($row = $result->fetch_assoc()) {	
		$userId = $row["userId"];
		$email = $row["email"];
		if (empty($email)){
			$additional_result_text .= "To receive receipts you need to complete registration at the link below. Once your order has been processed, the receipt will be emailed to you. \n"
			.'<a href="../logon/register?telegramId='.$telegramId.'">Register your account here</a>';
		}
	}
}

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
}
mysqli_close($link);
?>