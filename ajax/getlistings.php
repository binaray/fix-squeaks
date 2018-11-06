<?php
require_once "../_config.php";

$sql="SELECT * FROM Listings WHERE itemId = {$_GET["item"]} ORDER BY price";
$result = $link->query($sql);

$listings = array();
while($row = $result->fetch_assoc()) {
	if ($result->num_rows== 0) echo "No listings yet!";
	else {
		$listing = array();
		$listing["listingId"] = $row["listingId"];
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
?>