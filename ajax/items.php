<?php
//USAGE: Returns -all- items in json format
//GET PARAMETERS (OPTIONAL): category
require_once "../_config.php";

if(isset($_GET["category"])){
	$category = $_GET["category"];
	$sql="SELECT itemId, itemName, imageUrl, options, items, category FROM Inventory WHERE category='{$category}' ORDER BY itemId DESC";
}
else
	$sql="SELECT itemId, itemName, imageUrl, options, items, category FROM Inventory ORDER BY itemId DESC";

$result = $link->query($sql);

if ($result->num_rows > 0) {
	
	$output = array();
	
	while($row = $result->fetch_assoc()) {

		$output_item = array();
		$imageUrl = (empty($row["imageUrl"])) ? "https://via.placeholder.com/150x150" : $row["imageUrl"];
		
		//single item type
		if(empty($row["options"])){	
			
			$item = json_decode($row["items"], true);
			$price = (empty($item["price"])) ? "Price unavailable" : "$".$item["price"];
			
			$output_item["itemId"] = $row["itemId"];
			$output_item["imageUrl"] = $imageUrl;
			$output_item["itemName"] = $row["itemName"];
			$output_item["category"] = $row["category"];
			$output_item["price"] = $price;
		}
		
		//item multi type
		else{
			
			$items = json_decode($row["items"], true);
			$avg_price = 0;
			$count=0;
			
			foreach ($items as $item){
				$avg_price += $item["price"];
				$count++;
			}
			$avg_price /= $count;
			$avg_price = (empty($avg_price)) ? "Price unavailable" : "$".$avg_price;

			$output_item["itemId"] = $row["itemId"];
			$output_item["imageUrl"] = $imageUrl;
			$output_item["itemName"] = $row["itemName"];
			$output_item["category"] = $row["category"];
			$output_item["price"] = $avg_price;
		}
			
		array_push($output, $output_item);
	}
	
	echo json_encode($output);
	
} else {
	echo "0 results";
}
$link->close();

?>