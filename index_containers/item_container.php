<?php
if (!is_numeric($_GET["item"])) {
	echo "Invalid Item!";	
}
else{
	$sql="SELECT * FROM Inventory WHERE itemId = {$_GET["item"]}";
	$result = $link->query($sql);
	while($row = $result->fetch_assoc()) {
		if ($result->num_rows== 0) echo "No such item!";
		
		$spinner_html="";
		$add_description="";
		
		$itemName = $row["itemName"];
		$imageUrl = $row["imageUrl"];
		$description = $row["description"];
		$options = $row["options"];
		$items_json = $row["items"];
		$items = json_decode($items_json, true);
	}
	
	//----------------------------------multi item------------------------------------------//
	if(!empty($options)){
		$multi_item_disp=true;
		$options=json_decode($options,true);
		$option_count=0;
		
		foreach ($options as $option => $properties){
			$spinner_html.='<label id="label_type'.$option_count.'">'.$option.'</label>
			<input name="type'.$option_count.'" style="display: none; value="'.$option.'">
			<select id="property'.$option_count.'" name="property'.$option_count.'" class="form-control input_spinner">';
			
			foreach ($properties as &$selection){
				$spinner_html.='<option>'.$selection.'</option>';
			}	
			$spinner_html.='</select>';
			$option_count++;
		}
													
		$price = (empty($items[0]["price"])) ? "Currently unavailable" : "Brand new at: $".number_format($items[0]["price"],2);
		$add_description = "<div id='text_itemDescription'>".$items[0]["description"]."</div>";
		$button_order = ($items[0]["availability"]) ? '<button id="button_addToCart" type="button" class="btn btn-outline-primary">Add to cart</button>' : '<button type="button" class="btn btn-outline-primary">No stock</button>';
	}
	//----------------------------------single item------------------------------------------//
	else{
		$multi_item_disp=false;
		$price = (empty($items["price"])) ? "Currently unavailable" : "Brand new at: $".number_format($items["price"],2);
		$button_order = ($items["availability"]) ? '<button id="button_addToCart" type="button" class="btn btn-outline-primary">Add to cart</button>' : '<button type="button" class="btn btn-outline-primary">No stock</button>';
	}
}
?>

<div class="container">
	<div class="row"><h3 id="text_itemName"><?=$itemName;?></h3></div>
	<div class="row">
		<div class="col-md-3">
			<img src="<?=$imageUrl;?>" alt="<?=$itemName;?>" width="100%" height="150">
			<form>
				<div id="price"><?=$price?></div>
				<?=$spinner_html;?>
				<label>Quantity:</label>
				<input type="number" class="form-control" id="input_quantity" placeholder="Quantity" value="1" min="1" required>
				<?=$button_order;?>
			</form>
		</div>
								
		<div class="col-md-9">
			<ul class="nav nav-tabs" id="profileTabs" role="tablist">
				<li class="nav-item">
					<a class="nav-link active tab_padding" id="itemDescription-tab" data-toggle="tab" href="#itemDescription" role="tab" aria-controls="itemDescription" aria-selected="true">Description</a>
				</li>
				<li class="nav-item">
					<a class="nav-link tab_padding" id="userListings-tab" data-toggle="tab" href="#userListings" role="tab" aria-controls="userListings" aria-selected="false">Listings</a>
				</li>
			</ul>
			
			<div class="tab-content mb-5" id="profileTabContent">
				<div class="tab-pane fade show active" id="itemDescription" role="tabpanel" aria-labelledby="itemDescription-tab"><p><?=$description;?></p><?=$add_description;?></div>
				<div class="tab-pane fade" id="userListings" role="tabpanel" aria-labelledby="userListings-tab">
					<div class="row">
						<div class="col-2 header_listing">Listing ID</div><div class="col-6">Type</div><div class="col-2">Price</div><div class="col-2">Stock</div>
					</div>	

					<?php
					//TODO: swap to ajax for performance
					//----------------------------------Pull user listings------------------------------------//
					$sql="SELECT * FROM Listings WHERE itemId = {$_GET["item"]} ORDER BY price";
					$result = $link->query($sql);
					while($row = $result->fetch_assoc()) {
						if ($result->num_rows== 0) echo "No listings yet!";						
						echo		'<div class="row item_listing">
										<div class="col-2 listingId">'.$row["listingId"].'</div><div class="col-6 listingProperties">'.$row["properties"].'</div><div class="col-2 listingPrice">'.$row["price"].'</div><div class="col-2 listingStock">'.$row["quantity"].'</div>
									</div>';
						
					}
					?>
				</div>
			</div>
		</div>
	</div>
</div>