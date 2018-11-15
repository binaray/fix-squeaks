<?php
if(isset($_GET["page"])){
	$page=trim($_GET["page"]);
	$offset=($page-1)*ITEMS_PER_PAGE;
	if(isset($_GET["category"])){
		$category = $_GET["category"];
		$sql="SELECT itemId, itemName, imageUrl, options, items FROM Inventory WHERE category='{$category}' ORDER BY itemId DESC LIMIT ".ITEMS_PER_PAGE." OFFSET {$offset}";
	}
	else{
		$sql="SELECT itemId, itemName, imageUrl, options, items FROM Inventory ORDER BY itemId DESC LIMIT ".ITEMS_PER_PAGE." OFFSET {$offset}";
	}
}
else{
	$page=1;
	if(isset($_GET["category"])){
		$category = $_GET["category"];
		$sql="SELECT itemId, itemName, imageUrl, options, items FROM Inventory WHERE category='{$category}' ORDER BY itemId DESC LIMIT ".ITEMS_PER_PAGE;
	}
	else
		$sql="SELECT itemId, itemName, imageUrl, options, items FROM Inventory ORDER BY itemId DESC LIMIT ".ITEMS_PER_PAGE;
}

$result = $link->query($sql);
?>

<div class="container">
	<div class="row header_category" style="border-color: <?=(isset($category) ? BORDER_COLOUR["$category"] : 'grey')?>;">
		<h6 class="title_category"><?=(isset($category)?$category:"All items")?></h6>
	</div>
	<div class="row mb-5 mt-3">
	<?php
	if ($result->num_rows > 0) {
		// output data of each row
		while($row = $result->fetch_assoc()) {
			
			$imageUrl = $row["imageUrl"];
			
			//single item type
			if(empty($row["options"])){
				$item = json_decode($row["items"], true);
				$price = (empty($item["price"])) ? "Price unavailable" : "$".number_format($item["price"],2);
				
				echo 
					'<a href="?item='.$row["itemId"].'" class="col-6 col-sm-4 col-md-3 col-lg-3 col-xl-2">
						<div class="img-thumbnail item">
							<img src="image?upload='.$imageUrl.'" alt="'.$row["itemName"].'" width="100%" height="150">
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
							<img src="image?upload='.$imageUrl.'" alt="'.$row["itemName"].'" width="100%" height="150">
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
	?>
	</div>

	<?php
	$page_query="?page=";
	if (isset($category)) {
		$total_pages_sql = "SELECT COUNT(*) FROM Inventory WHERE category='{$category}'";
		$page_query="?category={$category}&page=";
	}
	else $total_pages_sql = "SELECT COUNT(*) FROM Inventory";
	$result = mysqli_query($link,$total_pages_sql);
	$total_rows = mysqli_fetch_array($result)[0];
	$total_pages = ceil($total_rows / ITEMS_PER_PAGE);
	?>
	
	<nav aria-label="Page navigation">
	  <ul class="pagination justify-content-center">
		<li class="page-item"><a class="page-link" href="<?=$page_query?>1">First</a></li>
		<li class="page-item <?php if($page <= 1) echo 'disabled'; ?>">
		  <a class="page-link" href="<?php if($page <= 1) echo '#'; else echo $page_query.($page - 1); ?>" tabindex="-1">Previous</a>
		</li>
		<li class="page-item <?php if($page >= $total_pages) echo 'disabled'; ?>">
		  <a class="page-link" href="<?php if($page >= $total_pages) echo '#'; else echo $page_query.($page + 1); ?>">Next</a>
		</li>
		<li class="page-item"><a class="page-link" href="<?=$page_query.$total_pages?>">Last</a></li>
	  </ul>
	</nav>

</div>

<?php $link->close();?>