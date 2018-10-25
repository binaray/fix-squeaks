<?php
require_once "_config.php";
session_start();

if (!isset($_SESSION['email'])) {
	header("location: logon/login.php?redirect=/sell");
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
	<div class="fixed-top overlay">
		<div class="overlay_sell overlay_form">
			<h3 class="overlay_header">Sell Item</h3>
			<form enctype="multipart/form-data" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
				<input id="input_itemId" type="number" name="itemId" style="display: none;" required>
				<input type="number" name="quantity" class="form-control" placeholder="qty" min="1" step="1" required>
				<input type="number" name="price" class="form-control" placeholder="per price" min="0.00" step="0.01" required>
				<div id="spinner_html"></div>
				<div class="form-group">
					<input type="submit" class="btn btn-primary" value="Submit">
					<input type="reset" class="btn btn-default button_cancel" value="Cancel">
				</div>
			</form>
		</div>
	</div>
	
	<div class="container">
		<?php
		$sql = "SELECT * FROM Listings WHERE userId = '{$userId}'";
		$result = $link->query($sql);
		if($result->num_rows>0) echo "<h3>Listed Items</h3>";
		while($row = $result->fetch_assoc()) {
			echo "<div class='row'>".json_encode($row)."</div>";
		}
		?>
		
		<h3>Add item to sell</h3>
		<form class="form-inline mx-auto" enctype="multipart/form-data" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="get">
			<input type="text" class="form-control" placeholder="Enter to search..." name="search" id="searchInput" class="search">
			<input type="submit" class="btn btn-outline-primary" value="Submit">
		</form>
		<a href="/index.php">Not listed? Send in a request to to list your item!</a>
		
		<?php
		if(isset($_GET["search"])){
			$search=$_GET["search"];

			$sql="SELECT * FROM Inventory WHERE itemName LIKE '%".$search."%'";	
			$result = $link->query($sql);
			while($row = $result->fetch_assoc()) {
				echo "<div class='row sellable'> <div class='itemId'>{$row["itemId"]}</div> {$row["itemName"]} {$row["description"]} {$row["category"]}</div>";			
			}
		}
		?>
	</div>
	
	<?php include "footer.php";?>
	
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>	
	<script src="js/sell.js"></script>
	<script type="text/javascript">
	
	</script>
  </body>
</html>