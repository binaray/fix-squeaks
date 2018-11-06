<?php
session_start();
//Check admin email here
require_once "../../_config.php";

if($_SERVER["REQUEST_METHOD"] == "POST"){
	$orderId=$_POST["orderId"];
	
	if(isset($_POST["issue"])) {
		$sql = "SELECT Users.name, Users.email, Orders.itemsBought FROM Orders INNER JOIN Users ON Orders.userID=Users.userID WHERE Orders.orderId = '{$orderId}'";
		$result = $link->query($sql);
		while($row = $result->fetch_assoc()) {
			$itemsBought=json_decode($row["itemsBought"],true);
			$itemName="";
			$price="";
			$quantity="";
			
			for ($j=0; $j<sizeof($itemsBought); $j++){
				if(isset($itemsBought[$j]["properties"])){
					$itemsBought[$j]["itemName"].=" (";
					
					for ($i=0; $i<sizeof($itemsBought[$j]["properties"]); $i++){
						if ($i==sizeof($itemsBought[$j]["properties"])-1) $itemsBought[$j]["itemName"].=$itemsBought[$j]["properties"][$i].")";
						else $itemsBought[$j]["itemName"].=$itemsBought[$j]["properties"][$i]." | ";
					}
				}
				if ($j==sizeof($itemsBought)-1){
					$itemName.=$itemsBought[$j]["itemName"];
					$price.=$itemsBought[$j]["price"];
					$quantity.=$itemsBought[$j]["quantity"];
				}
				else{
					$itemName.=$itemsBought[$j]["itemName"].",";
					$price.=$itemsBought[$j]["price"].",";
					$quantity.=$itemsBought[$j]["quantity"].",";
				}
			}
			echo "<div class='row'>
						<div class='col-1 orderId'>".$row["name"]."</div>
						<div class='col-1 userId'>".$row["email"]."</div>
						<div class='col-8 itemsBought'>".json_encode($itemsBought)."</div>
					</div>";
			$name = $row["name"];
			$email = $row["email"];
		}
		$date = date_create();
		
		$data = array(
			'name' => $name,
			'email' => $email,
			'itemName' => $itemName,
			'price' => $price,
			'quantity' => $quantity,
			'dateTimeGenerated' => date_timestamp_get($date)
		 );
		
		$jsonEncodedData = json_encode($data);
		
		$sql = "UPDATE Orders SET status='APPROVED' WHERE orderId={$orderId}";
		if ($link->query($sql) === TRUE) {
			echo "Record updated successfully";
		} else {
			echo "Error updating record: " . $conn->error;
		}
	}
	else if(isset($_POST["reject"])) { 
		$sql = "UPDATE Orders SET status='REJECTED' WHERE orderId={$orderId}";
		if ($link->query($sql) === TRUE) {
			echo "Record updated successfully";
		} else {
			echo "Error updating record: " . $conn->error;
		}
	}
}
?>

<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>fixsqueaks</title>
  </head>
  
  <body>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>	
	<script type="text/javascript">    
    'use strict'
	let log = console.log.bind(console);
	
	$(document).ready(function () {
		// window.location.replace("orders");
		<?php 
			// echo '$.post("http://hooks.zapier.com/hooks/catch/2321555/e335wh/",{item : '.$jsonEncodedData.'}, function(data){
				// log(data);
				// window.location.replace("orders");
			// });';
		?>
		
	});
	</script>
</body>
</html>	