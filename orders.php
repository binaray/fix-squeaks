<?php
session_start();

if (!isset($_SESSION['email']))
{
	header("Location: logon/login.php?redirect=/orders");
}

if (isset($_SESSION['buy'])) echo "Order success!";
require_once "_config.php";


$sql = "SELECT userId FROM Users WHERE email = '{$_SESSION['email']}'";
$result = $link->query($sql);
while($row = $result->fetch_assoc()) {
	$userId=$row["userId"];
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
		<h3>Orders</h3>
		<div class='row itemHeader'>
			<div class='col-2'>Order ID</div>			
			<div class='col-8'>Items</div>
			<div class='col-2'>Status</div>
		</div>
		
		<?php
		if (isset($userId)){
			$sql = "SELECT * FROM Orders WHERE userId = '{$userId}'";
			$result = $link->query($sql);
			while($row = $result->fetch_assoc()) {
				$itemsBought=json_decode($row["itemsBought"],true);
				
				foreach ($itemsBought as &$item){
					if(isset($item["properties"])){
						$item["itemName"].=" (";
						for ($i=0; $i<sizeof($item["properties"]); $i++){
							if ($i==sizeof($item["properties"])-1) $item["itemName"].=$item["properties"][$i].")";
							else $item["itemName"].=$item["properties"][$i].", ";
						}
					}
				}
				echo "<div class='row'>
							<div class='col-2 orderId'>".$row["orderId"]."</div>
							<div class='col-8 itemsBought'>".json_encode($itemsBought)."</div>
							<div class='col-2 itemsBought'>".$row["status"]."</div>
						</div>";
			}
		}
		?>
	</div>
	
	<?php include "footer.php";?>
	
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>	
	<script src="js/logon.js"></script>
  </body>
</html>