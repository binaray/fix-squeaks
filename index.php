<?php
require_once "_config.php";

session_start();
if (isset($_GET['logout'])){
	if ($_GET['logout']) unset($_SESSION['email']);
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
		<div class="row">
		<?php
			if(isset($_GET["item"])){
				$sql="SELECT * FROM Inventory WHERE itemId = {$_GET["item"]}";
				$result = $link->query($sql);
				while($row = $result->fetch_assoc()) {
					if ($result->num_rows== 0) echo "No such item!";
					
					$spinner_html="";
					$add_description="";
					
					//----------------------------------multi item------------------------------------------//
					if(!empty($row["options"])){
						$multi_item_disp=true;
						$options=json_decode($row["options"],true);
						$option_count=0;
						
						foreach ($options as $option => $properties){
							$spinner_html.='<label>'.$option.'</label>
							<input name="type'.$option_count.'" style="display: none; value="'.$option.'">
							<select id="property'.$option_count.'" name="property'.$option_count.'" class="form-control input_spinner">';
							
							foreach ($properties as &$selection){
								$spinner_html.='<option>'.$selection.'</option>';
							}	
							$spinner_html.='</select>';
							$option_count++;
						}
						
						$items_json = $row["items"];
						$items = json_decode($row["items"], true);
						
						$price = (empty($items[0]["price"])) ? "Price unavailable" : $items[0]["price"];
						$add_description = "<div id='text_itemDescription'>".$items[0]["description"]."</div>";
						$button_order = ($items[0]["availability"]) ? '<button id="button_addToCart" type="button" class="btn btn-outline-primary">Add to cart</button>' : '<button type="button" class="btn btn-outline-primary">No stock</button>';
					}
					//----------------------------------single item------------------------------------------//
					else{
						$multi_item_disp=false;
						$item_json = $row["items"];
						$item = json_decode($row["items"], true);
						$price = (empty($item["price"])) ? "Price unavailable" : $item["price"];
						$button_order = ($item["availability"]) ? '<button id="button_addToCart" type="button" class="btn btn-outline-primary">Add to cart</button>' : '<button type="button" class="btn btn-outline-primary">No stock</button>';
					}
					
					echo '<h3 id="text_itemName">'.$row["itemName"].'</h3></div>';
					
					echo'<div class="row">
						<div class="col-md-3">
							<img src="'.$row["imageUrl"].'" alt="'.$row["itemName"].'" width="100%" height="150">
							<form>
								<div id="price">$'.$price.'</div>'
								.$spinner_html.
								'<label>Quantity:</label>
								<input type="number" class="form-control" id="input_quantity" placeholder="Quantity" value="1" min="1" required>'
								.$button_order.
							'</form>
						</div>';
												
					echo '<div class="col-md-9">
							<ul class="nav nav-tabs" id="profileTabs" role="tablist">
								<li class="nav-item">
									<a class="nav-link active tab_padding" id="itemDescription-tab" data-toggle="tab" href="#itemDescription" role="tab" aria-controls="itemDescription" aria-selected="true">Description</a>
								</li>
								<li class="nav-item">
									<a class="nav-link tab_padding" id="userListings-tab" data-toggle="tab" href="#userListings" role="tab" aria-controls="userListings" aria-selected="false">Listings</a>
								</li>
							</ul>';
							
					echo	'<div class="tab-content mb-5" id="profileTabContent">
								<div class="tab-pane fade show active" id="itemDescription" role="tabpanel" aria-labelledby="itemDescription-tab"><p>'.$row["description"].'</p>'.$add_description.'</div>
								<div class="tab-pane fade" id="userListings" role="tabpanel" aria-labelledby="userListings-tab">';
					
					echo 			'<div class="row">
										<div class="col"></div>;
									</div>';
									
					echo		'</div>
							</div>';
					//close col-9
					echo "</div>";
				}
			}
			
			//-----------------------------------------------------------ITEM LIST----------------------------------------------------------------//
			else{
				if(isset($_GET["page"])){
					$offset=$_GET["page"]*ITEMS_PER_PAGE;
					if(isset($_GET["category"])){
						$category = $_GET["category"];
						$sql="SELECT itemId, itemName, imageUrl, options, items, category FROM Inventory WHERE category='{$category}' ORDER BY itemId DESC LIMIT 24 OFFSET {$offset}";
					}
					else
						$sql="SELECT itemId, itemName, imageUrl, options, items, category FROM Inventory ORDER BY itemId DESC LIMIT 24 OFFSET {$offset}";
				}
				else{
					if(isset($_GET["category"])){
						$category = $_GET["category"];
						$sql="SELECT itemId, itemName, imageUrl, options, items, category FROM Inventory WHERE category='{$category}' ORDER BY itemId DESC LIMIT 24";
					}
					else
						$sql="SELECT itemId, itemName, imageUrl, options, items, category FROM Inventory ORDER BY itemId DESC LIMIT 24";
				}
				
				$result = $link->query($sql);

				if ($result->num_rows > 0) {
					// output data of each row
					while($row = $result->fetch_assoc()) {
						
						$imageUrl = (empty($row["imageUrl"])) ? "https://via.placeholder.com/150x150" : $row["imageUrl"];
						
						//single item type
						if(empty($row["options"])){
							$item = json_decode($row["items"], true);
							$price = (empty($item["price"])) ? "Price unavailable" : "$".$item["price"];
							
							echo 
								'<a href="?item='.$row["itemId"].'" class="col-6 col-sm-4 col-md-3 col-lg-3 col-xl-2">
									<div class="img-thumbnail item">
										<img src="'.$imageUrl.'" alt="'.$row["itemName"].'" width="100%" height="150">
										<div class="caption">
											<div align="center" class="text_item">'.$row["itemName"].'</div>
											<div align="center" class="text_category">'.$row["category"].'</div>
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
							$avg_price = (empty($avg_price)) ? "Price unavailable" : "$".$avg_price;
							
							echo 
								'<a href="?item='.$row["itemId"].'" class="col-6 col-sm-4 col-md-3 col-lg-3 col-xl-2">
									<div class="img-thumbnail item">
										<img src="'.$imageUrl.'" alt="'.$row["itemName"].'" width="100%" height="150">
										<div class="caption">
											<div align="center" class="text_item">'.$row["itemName"].'</div>
											<div align="center" class="text_category">'.$row["category"].'</div>
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
			}
		?>
		</div>
	</div>
	
	<?php include "footer.php";?>
	
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>	
	<script src="js/logon.js"></script>
	<script type="text/javascript">
	'use strict'
	let log = console.log.bind(console);
	if(Array.prototype.equals)
		console.warn("Overriding existing Array.prototype.equals. Possible causes: New API defines the method, there's a framework conflict or you've got double inclusions in your code.");
	Array.prototype.equals = function (array) {
		if (!array)
			return false;
		if (this.length != array.length)
			return false;

		for (var i = 0, l=this.length; i < l; i++) {
			if (this[i] instanceof Array && array[i] instanceof Array) {
				if (!this[i].equals(array[i]))
					return false;       
			}           
			else if (this[i] != array[i]) { 
				return false;   
			}           
		}       
		return true;
	}
	Object.defineProperty(Array.prototype, "equals", {enumerable: false});
	
	
	$(document).ready(function () {
		
		<?php 
		if(isset($multi_item_disp)){
			if(!$multi_item_disp) echo
			'
			let current_item = JSON.parse(\''.$item_json.'\');
			';
			else echo
			'let items = JSON.parse(\''.$items_json.'\');
			let option_count = items[0]["properties"].length-1;
			let current_item = items[0];
			
			$(".input_spinner").change(function() {
				let count = option_count;
				let current_property = [];
				
				while (count > -1){
					current_property.push($("#property"+count).find(":selected").text());
					count--;
				}
				log(current_property);
				
				let i;
				for (i = 0; i < items.length; i++){
					if (items[i]["properties"].equals(current_property)) {
						current_item = items[i];
						updateDivs();
						log(current_item);
					}
				}
			});
			
			function updateDivs(){
				$("#price").text(current_item["price"]);
				$("#input_price").val(current_item["price"]);
				$("#text_itemDescription").text(current_item["description"]);
			}';
		}
		?>
		
		$("#button_addToCart").click(function(){
			let url = new URL(window.location.href);
			let itemToAdd = {
				itemId : url.searchParams.get("item"),
				itemName : $("#text_itemName").text(),
				properties : current_item["properties"],
				quantity : $("#input_quantity").val(),
				price : current_item["price"]
			};
			log(JSON.stringify(itemToAdd));
			$.post("ajax/shopping-cart",{item : JSON.stringify(itemToAdd)}, function(data){
				log(data);
			});
		});
	});

	</script>
  </body>
</html>