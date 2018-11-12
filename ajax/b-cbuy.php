<?php
//LOCK THIS THREAD TO PREVENT POTENTIAL CONFLICTS
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
	$stockError = false;
	
	if (isset($_GET["properties"])){
		//$properties is decoded as it will be re-encoded again TO BE FURTHER DISCUSSED
		$item["properties"] = json_decode($properties);
		$item["price"] = $items[$properties]["price"];
		
		if ($items[$properties]["quantity"]<$quantity)
		{
			//echo $items[$properties]["price"];
			$stockError=true;
		}
		else 
			$items[$properties]["quantity"] -= $quantity;
	}else{
		$item["price"] = $items["price"];
		if ($items["quantity"]<$quantity)
			$stockError=true;
		else 
			$items["quantity"] -= $quantity;
	}
	$item["quantity"] = $quantity;
	
	array_push($itemsBought,$item);
	
	$itemsBought = json_encode($itemsBought);
	$status = "PENDING";
	
	if ($stockError) echo "Error: Insufficient stock!";
	else{
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
		$updatedItems = json_encode($items,true);
		$sql = "UPDATE Inventory SET items=? WHERE itemId=?";
		$stmt = $link->prepare($sql);

		$stmt->bind_param('ss', $updatedItems, $_GET["item"]);
		$stmt->execute();

		if ($stmt->errno) {
		  echo "Error updating stock! " . $stmt->error;
		}
		$stmt->close();
		
	}
}
mysqli_close($link);
?>