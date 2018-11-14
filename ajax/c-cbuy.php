<?php
require_once "../_config.php";

$listingId = $_GET["listingId"];
$quantity = $_GET["quantity"];
$telegramId = $_GET["telegramId"];

require_once "telegramcheck.php";

//check stock
$sql="SELECT quantity FROM Listings WHERE listingId = {$listingId}";
$result = $link->query($sql);
while($row = $result->fetch_assoc()) {
	if ($result->num_rows== 0) echo "No such item!";
	else if($quantity>$row["quantity"]) $stockError=true;
	else $updatedStock=$row["quantity"]-$quantity;
}

if (isset($stockError)) echo "Item out of stock!";
else {
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
	$sql = "UPDATE Listings SET quantity=? WHERE listingId=?";
	$stmt = $link->prepare($sql);

	$stmt->bind_param('is', $updatedStock, $listingId);
	$stmt->execute();

	if ($stmt->errno) {
	  echo "Error updating stock! " . $stmt->error;
	}
	$stmt->close();
}
mysqli_close($link);
?>