<?php
if(isset($_GET["page"])){
	$offset=$_GET["page"]*ITEMS_PER_PAGE;
	if(isset($_GET["category"])){
		$category = $_GET["category"];
		$sql="SELECT itemId, itemName, imageUrl, options, items FROM Inventory WHERE category='{$category}' ORDER BY itemId DESC LIMIT 24 OFFSET {$offset}";
	}
	else
		$sql="SELECT itemId, itemName, imageUrl, options, items FROM Inventory ORDER BY itemId DESC LIMIT 24 OFFSET {$offset}";
}
else{
	if(isset($_GET["category"])){
		$category = $_GET["category"];
		$sql="SELECT itemId, itemName, imageUrl, options, items FROM Inventory WHERE category='{$category}' ORDER BY itemId DESC LIMIT 24";
	}
	else
		$sql="SELECT itemId, itemName, imageUrl, options, items FROM Inventory ORDER BY itemId DESC LIMIT 24";
}

$result = $link->query($sql);
?>

<div class="container">
	<div class="row">
	<?php
	if ($result->num_rows > 0) {
		// output data of each row
		while($row = $result->fetch_assoc()) {
			
			$imageUrl = (empty($row["imageUrl"])) ? "https://via.placeholder.com/150x150" : $row["imageUrl"];
			
			//single item type
			if(empty($row["options"])){
				$item = json_decode($row["items"], true);
				$price = (empty($item["price"])) ? "Price unavailable" : "$".number_format($item["price"],2);
				
				echo 
					'<a href="?item='.$row["itemId"].'" class="col-6 col-sm-4 col-md-3 col-lg-3 col-xl-2">
						<div class="img-thumbnail item">
							<img src="'.$imageUrl.'" alt="'.$row["itemName"].'" width="100%" height="150">
							<div class="caption">
								<div align="center" class="text_item">'.$row["itemName"].'</div>
								<div align="center" class="text_price">'.$price.'</div>
							</div>
						</div>
					</a>';
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
				$avg_price = (empty($avg_price)) ? "Price unavailable" : "$".number_format($avg_price,2);
				
				echo 
					'<a href="?item='.$row["itemId"].'" class="col-6 col-sm-4 col-md-3 col-lg-3 col-xl-2">
						<div class="img-thumbnail item">
							<img src="'.$imageUrl.'" alt="'.$row["itemName"].'" width="100%" height="150">
							<div class="caption">
								<div align="center" class="text_item">'.$row["itemName"].'</div>
								<div align="center" class="text_price">'.$avg_price.'</div>
							</div>
						</div>
					</a>';
			}
		}
	} else {
		echo "0 results";
	}
	$link->close();
	?>
	</div>
</div>