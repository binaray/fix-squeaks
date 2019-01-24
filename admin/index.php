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

  <?php
	$sql="SELECT itemId, itemName, imageUrl, options, items FROM Inventory ORDER BY itemId DESC LIMIT 6";
	$result = $link->query($sql);
	?>
	
  <body>
	
	<div class="container">
	<div class="row pt-3 pb-3"><h3 class="col">Current Inventory</h3><a class="btn btn-danger" href="?logout=true">Logout</a></div>
	<!-- Displaying Inventory -->
	
	<div class="border-bottom">
		<div class='row font-weight-bold border-top border-bottom'> 
			<div class='col-1 border-right'>itemId</div>
			<div class='col-1 border-right'>ownerId</div>
			<div class='col-3 border-right'>itemName</div>
			<div class='col-4 border-right'>description</div>
			<div class='col-1 border-right'>price</div>
			<div class='col-1 border-right'>qty</div>
			<div class='col-1'></div>
		</div>
		<?php
		$sql="SELECT vendorId FROM Vendors WHERE email = '{$_SESSION["admin"]}'";
        $result = $link->query($sql);
        // printf("Error: %s\n", $link->error);
		while($row = $result->fetch_assoc()) {
            if ($result->num_rows== 0) echo "Please login!";
            $vendorId = $row["vendorId"];
        }
		$result->close();
		
		if ($_SESSION["admin"]=="bigsqueak@pipsqueak.com") $sql="SELECT * FROM Inventory";
		else $sql="SELECT * FROM Inventory WHERE vendorId='{$vendorId}'";
		$result = $link->query($sql);
		
		$disp_bg=false;
		while($row = $result->fetch_assoc()) {
			$items = json_decode($row["items"],true);
			echo($disp_bg ?	'<div class="row pt-2 pb-2" style="background: #ebf8ff;">' : '<div class="row pt-2 pb-2">');
			echo "	<div class='col-1 border-right'>".$row["itemId"]."</div>
					<div class='col-1 border-right'>".$row["vendorId"]."</div>
					<div class='col-3 border-right'>".$row["itemName"]."</div>
					<div class='col-4 border-right'>".$row["description"]."</div>
					<div class='col-1 border-right text-right'>".number_format($items["price"],2)."</div>
					<div class='col-1 border-right text-right'>".$items["quantity"]."</div>
					<a href='edit?id=".$row["itemId"]."' class='col-1 itemId'>Edit</a>
				</div>";							
			$disp_bg = !$disp_bg;
		}
		?>
	</div>

		
	<!--Remove Items-->
	<div class="text-right p-5">
		<a href="upload" class='btn btn-primary'>Upload</a> 
	</div>


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>	
	<script src="js/nav.js"></script>
  </div>
  </body>
</html>
