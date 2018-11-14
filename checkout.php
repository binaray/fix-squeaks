<?php
session_start();

if(isset($_GET["action"])){
	if (!isset($_SESSION['email'])) header("location: /logon/login?redirect=/checkout");
	
	if($_GET["action"]=="reset"){
		unset($_SESSION["listedCart"]);
		unset($_SESSION["cart"]);
	}	
	if(isset($_SESSION["cart"]) && $_GET["action"]=="purchase"){
		require_once "_config.php";
		$sql = "SELECT userId FROM Users WHERE email = '{$_SESSION['email']}'";
		$result = $link->query($sql);
		while($row = $result->fetch_assoc()) {
			$userId=$row["userId"];
		}
		
		if (isset($userId)){			
			$itemsBought = array();
			foreach ($_SESSION["cart"] as $item){
				$item = json_decode($item,true);
				$stockError = false;
				
				//fetch item
				$sql="SELECT itemName, items FROM Inventory WHERE itemId = {$item["itemId"]}";
				$result = $link->query($sql);
				while($row = $result->fetch_assoc()) {
					if ($result->num_rows== 0) echo "No such item!";
					$itemName = $row["itemName"];
					$stock = json_decode($row["items"], true);
				}
				
				//get based on specific property
				if (isset($item["properties"])){
					$properties = json_encode($item["properties"]);
					$item["price"] = $item["price"];

					if ($stock[$properties]["quantity"]<$item["quantity"])
					{
						$stockError=true;
						echo "Sorry, we have insufficient stock of {$itemName} {$properties}!";
					}
					else 
						$stock[$properties]["quantity"] -= $item["quantity"];
				}else{
					//reassignment in case of abuse or (!) Generate warning for updated prices
					$item["price"] = $stock["price"];
					if ($stock["quantity"]<$item["quantity"]){
						$stockError=true;
						echo "{$itemName} is out of stock!";
					}
					else 
						$stock["quantity"] -= $item["quantity"];
				}
				
				//update b-c stock
				$updatedItems = json_encode($stock,true);
				$sql = "UPDATE Inventory SET items=? WHERE itemId=?";
				$stmt = $link->prepare($sql);

				$stmt->bind_param('ss', $updatedItems, $item["itemId"]);
				$stmt->execute();

				if ($stmt->errno) {
					echo "Error updating stock! " . $stmt->error;
				}
				else if(!$stockError){
					array_push($itemsBought,$item);
				}
				$stmt->close();
			}
			
			if (!empty($itemsBought)){
				$itemsBought = json_encode($itemsBought);
				$status = "PENDING";	
				$sql = "INSERT INTO Orders (userId, itemsBought, status) VALUES (?, ?, ?)";	
				if($stmt = mysqli_prepare($link, $sql)){
					// Bind variables to the prepared statement as parameters
					mysqli_stmt_bind_param($stmt, "sss", $userId, $itemsBought, $status);
					
					// Attempt to execute the prepared statement
					if(mysqli_stmt_execute($stmt)){
						echo "Successfully added to orders!\n";
						unset($_SESSION["cart"]);
						mysqli_stmt_close($stmt);
						mysqli_close($link);
						header("location: orders?buy=success");
					} else{
						echo "Something went wrong. Please try again later.";
					}
				}
				mysqli_stmt_close($stmt);
			}
		}
		mysqli_close($link);
	}
}
if(isset($_GET["removeListed"])){
	unset($_SESSION["listedCart"][$_GET["removeListed"]]);
}
if(isset($_GET["buyListed"])){
	if (!isset($_SESSION['email'])) header("location: /logon/login?redirect=/checkout");
	require_once "_config.php";		
	$sql = "SELECT userId FROM Users WHERE email = '{$_SESSION['email']}'";
	$result = $link->query($sql);
	while($row = $result->fetch_assoc()) {
		$userId=$row["userId"];
	}
	
	if (isset($userId)){
		$listedItem = json_decode($_SESSION["listedCart"][$_GET["buyListed"]],true);
		
		$listingId = $listedItem["listingId"];
		$buyerId = $userId;
		$quantity = $listedItem["quantity"];
		$status = "PENDING";

		$sql="SELECT quantity FROM Listings WHERE listingId = {$listingId}";
		$result = $link->query($sql);
		while($row = $result->fetch_assoc()) {
			if ($result->num_rows== 0) echo "No such item!";
			else if($quantity>$row["quantity"]) $stockError=true;
			else $updatedStock=$row["quantity"]-$quantity;
		}
		
		
		if(isset($stockError)) echo "Sorry, someone just bought the last stock!";
		else{
			//update stock
			$sql = "UPDATE Listings SET quantity=? WHERE listingId=?";
			$stmt = $link->prepare($sql);
			$stmt->bind_param('is', $updatedStock, $listingId);
			$stmt->execute();

			if ($stmt->errno) {
			  echo "Error updating stock! " . $stmt->error;
			}
			$stmt->close();
			
			//add to user(listing)orders
			$sql = "INSERT INTO UserOrders (listingId, buyerId, quantity, status) VALUES (?, ?, ?, ?)";	
			if($stmt = mysqli_prepare($link, $sql)){
				mysqli_stmt_bind_param($stmt, "ssss", $listingId, $buyerId, $quantity, $status);
				
				if(mysqli_stmt_execute($stmt)){
					echo "Successfully added!\n";
					unset($_SESSION["listedCart"][$_GET["buyListed"]]);
					// header("location: orders?buy=success");
				} else{
					echo "Something went wrong. Please try again later.";
				}
				mysqli_stmt_close($stmt);
			}
		}
	}
	mysqli_close($link);
}
?>

<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
	<link rel="stylesheet" href="css/pipsqueaks.css">
    <title>fixsqueaks</title>
  </head>
  
  <body>
	<?php include "header.php";?>
	<div class="container">
		<h3>Check-out</h3>
		
	<?php
	if (isset($_SESSION["listedCart"])){
		echo "<h5>Listed Items in Cart</h5>
		<div class='row itemHeader'>
			<div class='col-5'>Item</div>
			<div class='col-1'>Cost</div>
			<div class='col-1'>Qty</div>
			<div class='col-1'>Total</div>
		</div>";
		
		foreach ($_SESSION["listedCart"] as $index => $item){
			$item = json_decode($item,true);
			
			$properties_html="<br>";
			if (isset($item["properties"])){
				foreach ($item["properties"] as $property){
					$properties_html.="<span class='property'>{$property} </span>";
				}
			}
			$total = $item["quantity"]*$item["price"];
			
			echo "<div class='row cart_item'>
						<div class='col-5'>".$item["itemName"].$properties_html."</div>
						<div class='col-1'>".$item["price"]."</div>
						<div class='col-1'>".$item["quantity"]."</div>
						<div class='col-1'>".$total."</div>
						<div class='col-4 form-inline'>
							<a type='button' class='btn btn-outline-danger' href='".htmlspecialchars($_SERVER["PHP_SELF"])."?removeListed=".$index."'>Remove order</a>
							<a type='button' class='btn btn-outline-primary' href='".htmlspecialchars($_SERVER["PHP_SELF"])."?buyListed=".$index."'>Confirm order</a>
						</div>
					</div>";
		}
		echo "<br>";
	}
	
	if (isset($_SESSION["cart"])){
		echo "<h5>Retail Items Ordered</h5>
		<div class='row itemHeader'>
			<div class='col-6'>Item</div>
			<div class='col-2 text-right'>Cost</div>
			<div class='col-2 text-right'>Qty</div>
			<div class='col-2 text-right'>Total</div>
		</div>";
		$grandTotal=0.00;
		
		foreach ($_SESSION["cart"] as $item){
			$item = json_decode($item,true);
			
			$properties_html="<br>";
			if (isset($item["properties"])){
				foreach ($item["properties"] as $property){
					$properties_html.="<span class='property'>{$property} </span>";
				}
			}
			$total = $item["quantity"]*$item["price"];
			
			echo "<div class='row cart_item'>
						<div class='col-6'>".$item["itemName"].$properties_html."</div>
						<div class='col-2 text-right'>".number_format($item["price"],2)."</div>
						<div class='col-2 text-right'>".$item["quantity"]."</div>
						<div class='col-2 text-right'>".number_format($total,2)."</div>
					</div>";
			$grandTotal+=$total;
		}
		
		echo "<div class='row itemHeader'>
				<div class='col-10 text-right'>Grand total:</div>
				<div class='col-2 text-right'>".number_format($grandTotal,2)."</div>
			</div>";
		
		if (!isset($_SESSION['email'])){
			echo '<div class="float-right">
					<a id="button_reset" type="button" class="btn btn-outline-danger" href="'.htmlspecialchars($_SERVER["PHP_SELF"]).'?action=reset">Clear cart</a>
					<a id="button_confirmPurchase" type="button" class="btn btn-outline-primary" href="logon/register?redirect=/checkout">Confirm order</a>
				</div>';
		}
		else{
			echo '<div class="float-right">
					<a id="button_reset" type="button" class="btn btn-outline-danger" href="'.htmlspecialchars($_SERVER["PHP_SELF"]).'?action=reset">Clear cart</a>
					<a id="button_confirmPurchase" type="button" class="btn btn-outline-primary" href="'.htmlspecialchars($_SERVER["PHP_SELF"]).'?action=purchase">Confirm order</a>
				</div>';
		}
	}
	if (!isset($_SESSION["cart"])&&!isset($_SESSION["listedCart"])){
		echo "No items added";
	}
	?>
    </div>    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>
	<script src="js/logon.js"></script>
	<script type="text/javascript">
	'use strict'
	let log = console.log.bind(console);
	</script>
</body>
</html>	
