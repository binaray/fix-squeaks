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
	<style>		
/* Makes images fully responsive */

.img-responsive,
.thumbnail > img,
.thumbnail a > img,
.carousel-inner > .carousel-item > img,
.carousel-inner > .carousel-item > a > img {
  display: block;
  min-width: 100%;
  min-height: 100%;
  overflow: hidden;
}


/* ------------------- Carousel Styling ------------------- */

.carousel-inner {
  border-radius: 15px;
  max-height: 400px;
}
.carousel-item {
  height: 400px;
}
.carousel-caption {
  background-color: rgba(0,0,0,.5);
  position: absolute;
  left: 0;
  right: 0;
  bottom: 0;
  z-index: 10;
  padding: 10px 0 15px 25px;
  color: #fff;
  text-align: left;
}

.carousel-indicators {
  position: absolute;
  bottom: 0;
  right: 0;
  left: 0;
  width: 100%;
  margin: 0;
  padding: 0 0 5px 0;
  text-align: right;
}

.carousel-indicators li{
	width: 10px;
    height: 10px;
    border-radius: 50%;
}
.carousel-control-prev,
.carousel-control-next {
  background-image: none;
}

	</style>
    <title>Pipsqueak Marketplace</title>
  </head>
  
  <body>
	<?php include "header.php";?>
		  
	<div id="main_body">
	<div class="container mt-0">
	<div id="carousel_main" class="carousel slide mx-auto pb-4" data-ride="carousel">
	  <!-- Wrapper for slides -->
	  <div class="carousel-inner">
		<div class="carousel-item active">
		  <img src="http://placehold.it/800x400" alt="...">
		  <div class="carousel-caption">
			<h3 class="title_category">Welcome!</h3>
			<p>to Pipsqueak Marketplace. <a href="http://pipsqueak.sg" style="color: #80bdff;">About us</a></p>
		  </div>
		</div>
		<div class="carousel-item">
		  <img src="http://placehold.it/800x400" alt="...">
		  <div class="carousel-caption">
			<h3 class="title_category">Pipsqueak Listings</h3>
			<p>Sell project materials, buy from others!</p>
		  </div>
		</div>
		<div class="carousel-item">
		  <img src="http://placehold.it/800x400" alt="...">
		  <div class="carousel-caption">
			<h3 class="title_category">Pipsqueak Cache</h3>
			<p>Buy firsthand products from us!</p>
		  </div>
		</div>
	  </div>

	  <!-- Controls -->
	  <a class="carousel-control-prev" href="#carousel_main" data-slide="prev">
		<span class="carousel-control-prev-icon"></span>
	  </a>
	  <a class="carousel-control-next" href="#carousel_main" data-slide="next">
		<span class="carousel-control-next-icon"></span>
	  </a>
	</div>
	</div>
	
	<?php
	$sql="SELECT itemId, itemName, imageUrl, options, items FROM Inventory ORDER BY itemId DESC LIMIT 6";
	$result = $link->query($sql);
	?>

	<div class="container">
		<div class="row header_category" style="border-color: <?=(isset($category) ? BORDER_COLOUR["$category"] : 'grey')?>;">
			<h6 class="title_category">Featured items</h6>
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
					$price = (empty($item["price"])) ? "Price unavailable" : number_format($item["price"],2)." SGD";
					
					echo 
						'<a href="items?id='.$row["itemId"].'" class="col-6 col-sm-4 col-md-3 col-lg-3 col-xl-2">
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
						if (is_numeric($item["price"]))
						{
							if (!isset($from_price)) $from_price = $item["price"];
							else{
								if ($item["price"] < $from_price) $from_price = $item["price"];
							}
							$avg_price += $item["price"];
							$count++;
						}				
					}
					$avg_price /= $count;
					$from_price = (empty($from_price)) ? "Unavailable" : "From ".number_format($from_price,2)." SGD";
					
					echo 
						'<a href="items?id='.$row["itemId"].'" class="col-6 col-sm-4 col-md-3 col-lg-3 col-xl-2">
							<div class="img-thumbnail item">
								<img src="image?upload='.$imageUrl.'" alt="'.$row["itemName"].'" width="100%" height="150">
								<div class="caption">
									<div align="center" class="text_item">'.$row["itemName"].'</div>
									<div align="center" class="text_price">'.$from_price.'</div>
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
		
		<div class="row header_category" style="border-color: <?=(isset($category) ? BORDER_COLOUR["$category"] : 'grey')?>;">
			<h6 class="title_category">Upcoming features</h6>
		</div>
		<div class="row mt-3">
			<div class="col-md-6">
			  <div class="card flex-md-row mb-4 shadow-sm h-md-250">
				<div class="card-body d-flex flex-column align-items-start">
				  <h4 class="mb-0">
					<a class="text-dark" href="#">Tompang@Pipsqueak</a>
				  </h4>
				  <!--div class="mb-1 text-muted">Nov 12</div-->
				  <p class="card-text mb-auto">Collaborate with friends and strangers for cheaper bulk orders!</p>
				</div>
				<img class="card-img-right flex-auto d-none d-lg-block" data-src="holder.js/200x250?theme=thumb" alt="Thumbnail [200x250]" style="width: 200px; height: 250px;" src="data:image/svg+xml;charset=UTF-8,%3Csvg%20width%3D%22200%22%20height%3D%22250%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20viewBox%3D%220%200%20200%20250%22%20preserveAspectRatio%3D%22none%22%3E%3Cdefs%3E%3Cstyle%20type%3D%22text%2Fcss%22%3E%23holder_1672516c977%20text%20%7B%20fill%3A%23eceeef%3Bfont-weight%3Abold%3Bfont-family%3AArial%2C%20Helvetica%2C%20Open%20Sans%2C%20sans-serif%2C%20monospace%3Bfont-size%3A13pt%20%7D%20%3C%2Fstyle%3E%3C%2Fdefs%3E%3Cg%20id%3D%22holder_1672516c977%22%3E%3Crect%20width%3D%22200%22%20height%3D%22250%22%20fill%3D%22%2355595c%22%3E%3C%2Frect%3E%3Cg%3E%3Ctext%20x%3D%2256.203125%22%20y%3D%22131%22%3EThumbnail%3C%2Ftext%3E%3C%2Fg%3E%3C%2Fg%3E%3C%2Fsvg%3E" data-holder-rendered="true">
			  </div>
			</div>
			<div class="col-md-6">
			  <div class="card flex-md-row mb-4 shadow-sm h-md-250">
				<div class="card-body d-flex flex-column align-items-start">
				  <h4 class="mb-0">
					<a class="text-dark" href="#">Datasheets</a>
				  </h4>
				  <!--div class="mb-1 text-muted">Nov 11</div-->
				  <p class="card-text mb-auto">Enjoy documentation on electronics at the marketplace without having to search elsewhere.</p>
				</div>
				<img class="card-img-right flex-auto d-none d-lg-block" data-src="holder.js/200x250?theme=thumb" alt="Thumbnail [200x250]" src="data:image/svg+xml;charset=UTF-8,%3Csvg%20width%3D%22200%22%20height%3D%22250%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20viewBox%3D%220%200%20200%20250%22%20preserveAspectRatio%3D%22none%22%3E%3Cdefs%3E%3Cstyle%20type%3D%22text%2Fcss%22%3E%23holder_1672516c979%20text%20%7B%20fill%3A%23eceeef%3Bfont-weight%3Abold%3Bfont-family%3AArial%2C%20Helvetica%2C%20Open%20Sans%2C%20sans-serif%2C%20monospace%3Bfont-size%3A13pt%20%7D%20%3C%2Fstyle%3E%3C%2Fdefs%3E%3Cg%20id%3D%22holder_1672516c979%22%3E%3Crect%20width%3D%22200%22%20height%3D%22250%22%20fill%3D%22%2355595c%22%3E%3C%2Frect%3E%3Cg%3E%3Ctext%20x%3D%2256.203125%22%20y%3D%22131%22%3EThumbnail%3C%2Ftext%3E%3C%2Fg%3E%3C%2Fg%3E%3C%2Fsvg%3E" data-holder-rendered="true" style="width: 200px; height: 250px;">
			  </div>
			</div>
		</div>
	</div>

	<?php $link->close();?>
	
	<?php include "footer.php";?>
	
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>	
	<script src="js/nav.js"></script>
  </body>
</html>