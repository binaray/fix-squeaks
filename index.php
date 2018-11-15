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
	
	<div id="main_body">
	<?php
	//if item parameter is specified, show item details; otherwise show item list
	if(isset($_GET["item"]))
		include "index_containers/item_container.php";
	else if(isset($_GET["search"]))
		include "index_containers/search_container.php";
	else
		include "index_containers/main_container.php";				
	?>
	</div>
	
	<?php include "footer.php";?>
	
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>	
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
			let current_item = JSON.parse(\''.$items_json.'\');
			';
			else echo
			'let items = JSON.parse(JSON.stringify('.$items_json.'));
			log(items);
			let option_count = '.$option_count.'-1;
			let current_property = [];
			let count = option_count;
			while (count > -1){
				current_property.push($("#property"+count).find(":selected").text());
				count--;
			}
			let current_item = items[JSON.stringify(current_property)];
			updateDivs();
			
			$(".input_spinner").change(function() {
				let count = option_count;
				let current_property = [];
				
				while (count > -1){
					current_property.push($("#property"+count).find(":selected").text());
					count--;
				}
				log(current_property);
				
				current_item = items[JSON.stringify(current_property)];
				updateDivs();
				log(current_item);
			});
			
			function updateDivs(){
				let text_price="";
				if(current_item["price"]){
					text_price="Brand new at: "+Number(current_item["price"]).toFixed(2)+" SGD";
				}else{
					log(current_item["price"]);
					text_price="Price unavailable";
				}
				$("#price").text(text_price);
				$("#input_price").val(current_item["price"]);
				$("#text_itemDescription").text(current_item["description"]);
				if(current_item["quantity"]>0){
					$( "#button_addMultiToCart" ).replaceWith( \'<button id="button_addMultiToCart" type="button" class="btn btn-outline-primary">Add to cart</button>\' );
					$("#button_addMultiToCart").click(function(){
						let url = new URL(window.location.href);
						let itemToAdd = {
							itemId : url.searchParams.get("item"),
							itemName : $("#text_itemName").text(),
							properties : current_property,
							quantity : $("#input_quantity").val(),
							price : current_item["price"]
						};
						log(JSON.stringify(itemToAdd));
						$.post("ajax/shopping-cart",{item : JSON.stringify(itemToAdd)}, function(data){
							log(data);
							alert("Item added to cart!");
						});
					});
				}
				else{
					$( "#button_addMultiToCart" ).replaceWith( \'<button id="button_addMultiToCart" type="button" class="btn btn-outline-primary disabled">Unavailable</button>\' );
				}
			}
			
			';
		}
		?>
		
		$("#button_addToCart").click(function(){
			let url = new URL(window.location.href);
			let itemToAdd = {
				itemId : url.searchParams.get("item"),
				itemName : $("#text_itemName").text(),
				quantity : $("#input_quantity").val(),
				price : current_item["price"]
			};
			log(JSON.stringify(itemToAdd));
			$.post("ajax/shopping-cart",{item : JSON.stringify(itemToAdd)}, function(data){
				log(data);
				alert("Item added to cart!");
			});
		});
		
		var selectedListedItem;
		
		$(".item_listing").click(function(){
			$(".overlay").show();
			$(".overlay_listing").show();
			
			selectedListedItem = $(this);
			$("#overlay_header_listing").text("Selected Listing for: "+$("#text_itemName").text());
									
			let selectedListedProperties = $(this).find(".listingProperties").text();
			if (selectedListedProperties) {
				$("#overlay_listingProperties").text(selectedListedProperties);
			}
			$("#overlay_listingPrice").text("Per price: $"+$(this).find(".listingPrice").text());
			$("#overlay_listingStock").text("Available stock: "+$(this).find(".listingStock").text());
		});
		
		$("#button_addListingToCart").click(function(){
			let itemToAdd = {
				listingId : selectedListedItem.find(".listingId").text(),
				itemName : $("#text_itemName").text(),
				properties : JSON.parse(selectedListedItem.find(".listingProperties").text()),
				quantity : $("#input_listingQuantity").val(),
				price : selectedListedItem.find(".listingPrice").text()
			};
			log(JSON.stringify(itemToAdd));
			$.post("ajax/shopping-cart",{listedItem : JSON.stringify(itemToAdd)}, function(data){
				log(data);
				$(".overlay_listing").hide();
				$(".overlay").hide();
				alert("Item added to cart!");
			});
			// if (confirm('Confirm purchase? Unlike buying from us directly, receipts will not be issued.')) {
				// alert('Thanks for confirming');
			// } else {
				// alert('Why did you press cancel? You should have confirmed');
			// }
		});
	});
	</script>
	<script src="js/logon.js"></script>
  </body>
</html>