<?php
require_once "_config.php";
session_start();

if (!isset($_SESSION['email'])) {
	header("location: logon/login?redirect=/sell");
}	
else{			
	$sql = "SELECT userId FROM Users WHERE email = '{$_SESSION['email']}'";
	$result = $link->query($sql);
	while($row = $result->fetch_assoc()) {
		$userId=$row["userId"];
	}
}

if($_SERVER["REQUEST_METHOD"] == "POST"){
	
	$x=0;
	$itemId = $_POST["itemId"];
	$price = $_POST["price"];
	$quantity = $_POST["quantity"];
	$properties = array();
	while(!empty($_POST["property{$x}"])){
		array_push($properties,trim($_POST["property{$x}"]));
		$x++;
	}
	$properties=json_encode(array_reverse($properties));		
	
	$sql = "INSERT INTO Listings (userId, itemId, properties, price, quantity) VALUES (?, ?, ?, ?, ?)";
	 
	if($stmt = mysqli_prepare($link, $sql)){
		// Bind variables to the prepared statement as parameters
		mysqli_stmt_bind_param($stmt, "sssss", $userId, $itemId, $properties, $price, $quantity);
		
		// Attempt to execute the prepared statement
		if(mysqli_stmt_execute($stmt)){
			echo "Item successfully listed";
		} else{
			echo "Something went wrong. Please try again later.";
		}
	}
	mysqli_stmt_close($stmt);	
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
	<div id="overlay_sell" class="fixed-top overlay">
		<div id="form_sell" class="overlay_form">
			<h3 id="overlay_sell_title" class="overlay_header">Sell Item</h3>
			<form enctype="multipart/form-data" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
				<input id="input_itemId" type="number" name="itemId" style="display: none;" required>
				<input type="number" name="quantity" class="form-control" placeholder="Your stock quantity" min="1" step="1" required>
				<input type="number" name="price" class="form-control" placeholder="per price" min="0.00" step="0.01" required>
				<div id="spinner_html"></div>
				<div class="form-group mt-3">
					<input type="submit" class="btn btn-primary" value="Submit">
					<input type="reset" class="btn btn-default button_cancel" value="Cancel">
				</div>
			</form>
		</div>
	</div>
	
  <?php include "header.php";?>
  
  <div id="main_body">
  <div class="container">
	
	<?php
	$sql = "SELECT Listings.listingId, Listings.properties, Listings.price, Listings.quantity, Listings.createdAt, Inventory.itemName FROM Listings INNER JOIN Inventory ON Listings.itemId = Inventory.itemId WHERE userId = '{$userId}'";
	$result = $link->query($sql);
	if($result->num_rows>0) {
		echo "
			<h3>Your Listed Items</h3>
			<div class='row'>
				<div class='col-1'>ID</div>
				<div class='col-6'>Item</div>
				<div class='col-1'>Price</div>
				<div class='col-1'>Qty</div>
				<div class='col-3'>Date added</div>
			</div>";
		
	}
	while($row = $result->fetch_assoc()) {
		
		echo "
			<div class='row'>
				<div class='col-1'>".$row["listingId"]."</div>
				<div class='col-6'>".$row["itemName"]."<br>".$row["properties"]."</div>
				<div class='col-1'>".$row["price"]."</div>
				<div class='col-1'>".$row["quantity"]."</div>
				<div class='col-3'>".$row["createdAt"]."</div>
			</div>";
	}
	?>
	
	<h3>Add item to sell</h3>
	<form class="form-inline mx-auto" enctype="multipart/form-data" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="get">
		<input type="text" class="form-control" placeholder="Enter to search..." name="search" id="searchInput" class="search">
		<input type="submit" class="btn btn-outline-primary" value="Submit">
	</form>
	<a href="mailto:ray_cheng@mymail.sutd.edu.sg">Not listed? Send in a request to to list your item!</a>
	
	<?php
	if(isset($_GET["search"])){
		$search=$_GET["search"];

		$sql="SELECT * FROM Inventory WHERE itemName LIKE '%".$search."%'";	
		$result = $link->query($sql);
		if ($result->num_rows == 0) echo "<div class='text-center'>No results..</div>";
		while($row = $result->fetch_assoc()) {
			echo "
			<div class='row sellable'> 
				<div class='itemId col-1'>{$row["itemId"]}</div> 
				<div class='col-3'>{$row["itemName"]}</div> 
				<div class='col-6'>{$row["description"]}</div> 
				<div class='col-2'>{$row["category"]}</div> 
			</div>";			
		}
	}
	?>
  </div>
  </div>
	
	<?php include "footer.php";?>
	
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>	
	<script src="js/sell.js"></script>
	<script src="js/nav.js"></script>
	<script type="text/javascript">
	
	</script>
  </body>
</html>