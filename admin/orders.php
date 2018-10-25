<?php
session_start();
// if (!isset($_SESSION['email'])){
	// header("location: /");
// }

require_once "../_config.php";

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
			'email' => "Chester_Tan@mymail.sutd.edu.sg",
			'itemName' => $itemName,
			'price' => $price,
			'quantity' => $quantity,
			'dateTimeGenerated' => date_timestamp_get($date)
		 );
		
		$jsonEncodedData = json_encode($data);
		echo $jsonDataEncoded;
	
		$ch = curl_init();

		$opts = array(
			CURLOPT_URL             => 'http://hooks.zapier.com/hooks/catch/2321555/e335wh/',
			CURLOPT_RETURNTRANSFER  => true,
			CURLOPT_CUSTOMREQUEST   => 'POST',
			CURLOPT_POST            => 1,
			CURLOPT_POSTFIELDS      => $jsonEncodedData,
			CURLOPT_HTTPHEADER  => array('Content-Type: application/json','Content-Length: ' . strlen($jsonEncodedData))                                                                       
		);

		curl_setopt_array($ch, $opts);
		$result = curl_exec($ch);
		curl_close ($ch);

		echo "\n".$result;
		
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

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
	<link rel="stylesheet" href="/css/pipsqueaks.css">
    <title>fixsqueaks</title>
  </head>
  
  <body>
	<div class="container">
		<h3>Order Admin Panel</h3>
		<div class='row itemHeader'>
			<div class='col-1'>OID</div>
			<div class='col-1'>UID</div>
			<div class='col-8'>Items</div>
			<div class='col-2'>Status</div>
		</div>
	<?php		
	
	
	$sql = "SELECT * FROM Orders";// WHERE status = 'PENDING'";
	$result = $link->query($sql);
	while($row = $result->fetch_assoc()) {
		echo "<div class='row order_item'>
					<div class='col-1 orderId'>".$row["orderId"]."</div>
					<div class='col-1 userId'>".$row["userId"]."</div>
					<div class='col-8 itemsBought'>".$row["itemsBought"]."</div>
					<div class='col-2 status'>".$row["status"]."</div>
				</div>";
	}	
		
	echo '<form enctype="multipart/form-data" action="'.htmlspecialchars($_SERVER["PHP_SELF"]).'" method="post" class="button_group float-right" style="display: none;">
			<input type="number" id="input_order" name="orderId" style="display: none;" required>
			<input type="submit" class="btn btn-outline-danger" value="Reject order" name="reject">
			<input type="submit" class="btn btn-outline-primary" value="Issue receipt" name="issue">
		</form>';

	?>
    </div>    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>	
	<script type="text/javascript">
	'use strict'
	let log = console.log.bind(console);
	
	$(document).ready(function () {
		var selectedOrder;
		
		$(".order_item").click(function(){
			$(".order_item").removeAttr('style');
			$(this).css('background-color', '#c3f3f8');
			selectedOrder = $(this);
			$(".button_group").show();
			generateReceipt();
		});
		
		function deselect(){
			$(".order_item").removeAttr('style');
			selectedOrder = null;
		}
		
		function generateReceipt(){
			let orderId = selectedOrder.find('.orderId').text();
			$("#input_order").val(orderId);
			log(orderId);
			<?php 
			// if (!empty($jsonDataEncoded)){
			
			// echo '$.post("http://hooks.zapier.com/hooks/catch/2321555/e335wh/",{item : '.$jsonDataEncoded.'}, function(data){
				// log(data);
			// });
			// ';
			// }
			?>
		}
		
		
	});
	</script>
</body>
</html>	
