<?php
require_once "../_config.php";
session_start();
if (!isset($_SESSION['admin'])) header("location: login");
if (isset($_GET['logout'])){
	if ($_GET['logout']) unset($_SESSION['admin']);
	header("location: ../");
}
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
	<link rel="stylesheet" href="css/pipsqueaks.css">
	<style>	

	</style>
    <title>Pipsqueak Marketplace</title>
  </head>
<!--
  <?php
	$sql="SELECT itemId, itemName, imageUrl, options, items FROM Inventory ORDER BY itemId DESC LIMIT 6";
	$result = $link->query($sql);
	?>
	-->
  <body>
	<div class="container">
    <a href="?logout=true">Logout</a>
	<div class="row">
		<div class="col-1">Vendor</div>
	</div>
	<!-- Displaying Inventory -->
	<div class="row">
		<div class="col-3">Current Inventory</div>
	</div>
	<div class="p-1">
		<?php
		$sql="SELECT vendorId FROM Vendors WHERE email = '{$_SESSION["admin"]}'";
        $result = $link->query($sql);
        var_dump($result);
		printf("Error: %s\n", $link->error);
		while($row = $result->fetch_assoc()) {
            if ($result->num_rows== 0) echo "Please login!";
            $vendorId = $row["vendorId"];
        }
		$result->close();
		
		if ($_SESSION["admin"]=="bigsqueak@pipsqueak.com") $sql="SELECT * FROM Inventory";
		else $sql="SELECT * FROM Inventory WHERE vendorId='{$vendorId}'";
		$result = $link->query($sql);
		
		while($row = $result->fetch_assoc()) {
				echo "<div class='row inventory pt-1 pb-1'> 
								<div class='col-2 itemId'>".$row["itemId"]."</div>
								<div class='col-2 itemNameId'>".$row["itemName"]."</div>
								<div class='col-3 options'>".$row["options"]."</div>
								<div class='col-2 items'>".$row["items"]."</div>
								<a href='edit?id=".$row["itemId"]."' class='col-2 itemId'>Edit</a>
							</div>";
							
		}
		?>
	</div>

	<!--Add Items-->
	<a href="upload" class='col-2 itemId'>Upload</a> 
	<div class="row">
		
		<form name="test1" method="get">
		<input type="submit" class="btn btn-primary" class="col-2" value="Submit">
		<input class="col-2" type="text" name="itemId" id="itemId" placeholder="itemId">
		<input class="col-2" type="text" name="itemNameId" id="itemNameId" placeholder="itemNameId">
		<input class="col-2" type="text" name="options" id="options" placeholder="options">
		<input class="col-2" type="text" name="items" id="items" placeholder="items">
		</form>
	</div>
<!--	
	<?php 
		// $itemId = $_GET['itemId'];
		// $itemNameId = $_GET['itemNameId'];
		// $options = $_GET['options'];
		// $items = $_GET['items'];
		// echo $_GET['itemId'];
		// echo "\n";
		// echo "<br>"; //new line
		// echo $_GET['itemNameId'];
		// echo "\n";
		// echo "<br>"; //new line
		// echo $_GET['options'];
		// echo "\n";
		// echo "<br>"; //new line
		// echo $_GET['items'];
		
		// $sqlinsert="INSERT INTO inventory (options,items) VALUES ('{$options}','{$items}')";
		// if(mysqli_query($link,$sqlinsert)){
			// echo "New record created successfully";
		// } else {
			// echo "Error: " .$sqlinsert . "<br>" .mysqli_error($link);
		// }
		// mysqli_close($link);
	?>
-->	
		
	<!--Remove Items-->
	<div class="row" class="p-1">
		<div class="col-2">
		<button type="button" class="btn btn-lg btn-primary" disabled="disabled">Remove Items</button>
		</div>
	</div>


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>	
	<script src="js/nav.js"></script>
  </div>
  </body>
</html>
