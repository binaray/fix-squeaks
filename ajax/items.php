<?php
//USAGE: Returns -all- items in json format
const ITEMS_PER_PAGE = 5;
require_once "../_config.php";

if(isset($_GET["category"])){
	if(isset($_GET["page"])){
		$offset=$_GET["page"]*ITEMS_PER_PAGE;
		$sql="SELECT itemId, itemName, imageUrl, options, items FROM Inventory WHERE category='{$category}' ORDER BY itemId DESC LIMIT ".ITEMS_PER_PAGE." OFFSET {$offset}";
	}
	$sql="SELECT itemId, itemName, imageUrl, options, items, category FROM Inventory WHERE category='{$_GET["category"]}' ORDER BY itemId DESC LIMIT ".ITEMS_PER_PAGE;
}
else if(isset($_GET["item"]))
	$sql="SELECT * FROM Inventory WHERE itemId = {$_GET["item"]}";
else
	$sql="SELECT itemId, itemName, imageUrl, options, items, category FROM Inventory ORDER BY itemId DESC LIMIT ".ITEMS_PER_PAGE;

$result = $link->query($sql);
if ($result->num_rows > 0) {
	
	if(isset($_GET["item"])){
		while($row = $result->fetch_assoc()) {
			$items = json_decode($row["items"], true);
			$options = json_decode($row["options"], true);
			
			$output = array(
				'itemId' => $row["itemId"],
				'itemName' => $row["itemName"],
				'description' => $row["description"],
				'options' => $options,
				'items' => $items,
			);
		}
	}
	else{
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
	}	
	echo json_encode($output);
} else {
	echo "No results";
}
$link->close();

?>