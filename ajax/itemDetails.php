<?php
//DEPRECATED: TO REMOVE
//To check whether single or multi item- check if options empty
require_once "../_config.php";

$sql="SELECT * FROM Inventory WHERE itemId = {$_GET["item"]}";
$result = $link->query($sql);
while($row = $result->fetch_assoc()) {
	if ($result->num_rows== 0) echo "No such item!";
	else{
		$items = json_decode($row["items"], true);
		$options = json_decode($row["options"], true);
		
		$get_return = array(
			'itemId' => $row["itemId"],
			'itemName' => $row["itemName"],
			'description' => $row["description"],
			'options' => $options,
			'items' => $items,
		);
	}
}
echo json_encode($get_return);
mysqli_close($link);
?>