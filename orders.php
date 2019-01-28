<?php
session_start();

if (!isset($_SESSION['email']))
{
	header("Location: logon/login.php?redirect=/orders");
}

require_once "_config.php";


$sql = "SELECT userId FROM Users WHERE email = '{$_SESSION['email']}'";
$result = $link->query($sql);
while($row = $result->fetch_assoc()) {
	$userId=$row["userId"];
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
    <title>Pipsqueak Marketplace</title>
  </head>
  
  <body>
	<?php include "header.php";?>	
	
	<div id="main_body">
	<div class="container">
		<h3>Orders</h3>
		<p>Here is the list of all your successful orders with us. We will contact and send you the item (as well as the receipt) after processing.</p>
		<div class='row itemHeader'>
			<div class='col-2'>Order ID</div>			
			<div class='col-8'>Items</div>
			<div class='col-2'>Status</div>
		</div>
		
		<?php
		if (isset($userId)){
			$sql = "SELECT * FROM Orders WHERE userId = '{$userId}'";
			$result = $link->query($sql);
			while($row = $result->fetch_assoc()) {
				$itemsBought=json_decode($row["itemsBought"],true);
				
				echo "<div class='row pb-2 pt-2'>
							<div class='col-2 orderId'>".$row["orderId"]."</div>
							<div class='col-8 itemsBought'>";
				
				foreach ($itemsBought as &$item){
					if(isset($item["properties"])){
						$item["itemName"].=" (";
						for ($i=0; $i<sizeof($item["properties"]); $i++){
							if ($i==sizeof($item["properties"])-1) $item["itemName"].=$item["properties"][$i].")";
							else $item["itemName"].=$item["properties"][$i].", ";
						}
					}
					echo "<div>".$item["itemName"]." x".$item["quantity"]."</div>";
				}
				echo		"</div>
							<div class='col-2 status'>".$row["status"]."</div>
						</div>";
			}
		}
		?>
		
		
		<!--h3>Listing Orders</h3>
		<p>Here is the list of your orders with other people. We will contact and send you the item after contacting the seller.</p>
		<div class='row itemHeader'>
			<div class='col-2'>Order ID</div>			
			<div class='col-8'>Items</div>
			<div class='col-2'>Status</div>
		</div-->
		<?php
		if (isset($userId)){
			$sql = "SELECT * FROM UserOrders WHERE buyerId = '{$userId}'";
			$result = $link->query($sql);
			while($row = $result->fetch_assoc()) {
				$listingId = $row["listingId"];
				$orderId = $row["orderId"];
				$quantity =$row["quantity"];
				$status = $row["status"];
				if (isset($listingId)){
					$sql = "SELECT Listings.properties, Listings.price, Inventory.itemName FROM Listings INNER JOIN Inventory ON Listings.itemId = Inventory.itemId WHERE Listings.listingId = '{$listingId}'";
					$nested_result = $link->query($sql);
					while($nested_row = $nested_result->fetch_assoc()) {
						echo "<div class='row pb-2 pt-2'>
									<div class='col-2 orderId'>".$orderId."</div>
									<div class='col-8 itemsBought'>";
						echo 		"{$nested_row["itemName"]} {$nested_row["properties"]} x{$quantity}";
						echo		"</div>
									<div class='col-2 status'>".$status."</div>
								</div>";
					}
				}
			}
			
			
		}
		?>
	</div>
	</div>
	<?php $link->close();?>
	<?php include "footer.php";?>
	
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>	
	<script src="js/nav.js"></script>
  </body>
</html>