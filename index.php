<?php
require_once "_config.php";

session_start();
if (isset($_GET['logout'])){
	if ($_GET['logout']) unset($_SESSION['email']);
}

//$_GET['category']
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
					if(empty($row["options"])){	
						echo "{$row["itemId"]} {$row["imageUrl"]} {$row["description"]} {$row["options"]} {$row["items"]} ";
						$item = json_decode($row["items"], true);
						
						$price = (empty($item["price"])) ? "Price unavailable" : "$".$item["price"];
						$imageUrl = (empty($row["imageUrl"])) ? "https://via.placeholder.com/150x150" : $row["imageUrl"];
						echo '<button type="button" class="btn btn-outline-primary">Add to cart</button>';
					}
					else{
						echo "itemGroup type";
					}
				}
			}
			else{ 
				if(isset($_GET["page"])){
					$offset=$_GET["page"]*ITEMS_PER_PAGE;
					$sql="SELECT itemId, itemName, imageUrl, options, items, category FROM Inventory ORDER BY itemId DESC LIMIT 24 OFFSET {$offset}";
				}
				else{
					$sql="SELECT itemId, itemName, imageUrl, options, items, category FROM Inventory ORDER BY itemId DESC LIMIT 24";
				}
				$result = $link->query($sql);

				if ($result->num_rows > 0) {
					// output data of each row
					while($row = $result->fetch_assoc()) {
						if(empty($row["options"])){
							
							$item = json_decode($row["items"], true);
							
							$price = (empty($item["price"])) ? "Price unavailable" : "$".$item["price"];
							$imageUrl = (empty($row["imageUrl"])) ? "https://via.placeholder.com/150x150" : $row["imageUrl"];
							
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
						else{
							echo "itemGroup type";
						}
					}
				} else {
					echo "0 results";
				}
				$link->close();
				
				for ($x=0; $x<4; $x++){
					echo 
					'<a href="admin/upload.php" class="col-6 col-sm-4 col-md-3 col-lg-3 col-xl-2 filter material">
						<div class="img-thumbnail item">
							<img src="https://cdn3.wpbeginner.com/wp-content/uploads/2014/10/broken-img-alt-text.jpg" alt="{$item}" width="100%" height="150">
							<div class="caption">
								<div align="center" class="text_item">Test Item</div>
								<div align="center" class="text_category">Category</div>
								<div align="center" class="text_price">Price</div>
							</div>
						</div>
					</a>';
				}
			}
		?>
		</div>
	</div>
	
	<?php include "footer.php";?>
	
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>	
	<script src="js/logon.js"></script>
	<script type="text/javascript">
	
	</script>
  </body>
</html>