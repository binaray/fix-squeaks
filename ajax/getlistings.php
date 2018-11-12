<?php
require_once "../_config.php";

if (isset($_GET["listingId"])){
	$sql = "SELECT Listings.listingId, Listings.properties, Listings.price, Listings.quantity, Inventory.itemName, Users.telegramId FROM Listings INNER JOIN Inventory ON Listings.itemId = Inventory.itemId INNER JOIN Users ON Listings.userId = Users.userId WHERE Listings.listingId = '{$_GET["listingId"]}'";
	$result = $link->query($sql);

	while($row = $result->fetch_assoc()) {
		if ($result->num_rows== 0) echo "No listings yet!";
		else {
			$listing = array();
			$listing["listingId"] = $row["listingId"];
			$listing["sellerTelegramId"] = $row["telegramId"];
			$listing["itemName"] = $row["itemName"];
			$listing["properties"] = $row["properties"];
			$listing["price"] = $row["price"];
			$listing["quantity"] = $row["quantity"];
		}
	}
	mysqli_close($link);
	echo json_encode($listing);
}
else{
	$sql = "SELECT Listings.listingId, Listings.properties, Listings.price, Listings.quantity, Inventory.itemName, Users.telegramId FROM Listings INNER JOIN Inventory ON Listings.itemId = Inventory.itemId INNER JOIN Users ON Listings.userId = Users.userId WHERE Listings.itemId = '{$_GET["item"]}'";
	//$sql="SELECT * FROM Listings WHERE itemId = {$_GET["item"]} ORDER BY price";
	$result = $link->query($sql);

	$listings = array();
	while($row = $result->fetch_assoc()) {
		if ($result->num_rows== 0) echo "No listings yet!";
		else {
			$listing = array();
			$listing["listingId"] = $row["listingId"];
			$listing["sellerTelegramId"] = $row["telegramId"];
			$listing["itemName"] = $row["itemName"];
			$listing["properties"] = $row["properties"];
			$listing["price"] = $row["price"];
			$listing["quantity"] = $row["quantity"];
			if (isset($_GET["properties"])){
				if ($listing["properties"]==$_GET["properties"])
					array_push($listings, $listing);
			}
			else array_push($listings, $listing);
		}
	}
	mysqli_close($link);
	echo json_encode($listings);
}
?>