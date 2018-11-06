<?php
// session_start();

// if (!isset($_SESSION['email'])||$_SESSION['email']!="bigsqueak@pipsqueak.com"){
	// header("location: /");
// }

require_once "../_config.php";

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
	?>
		<form enctype="multipart/form-data" action="ajax/validate-order" method="post" class="button_group float-right" style="display: none;">
			<input type="number" id="input_order" name="orderId" style="display: none;" required>
			<input type="submit" class="btn btn-outline-danger" value="Reject order" name="reject">
			<input type="submit" class="btn btn-outline-primary" value="Issue receipt" name="issue">
		</form>

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
			setOrderIdForValidation();
		});
		
		function deselect(){
			$(".order_item").removeAttr('style');
			selectedOrder = null;
		}
		
		function setOrderIdForValidation(){
			let orderId = selectedOrder.find('.orderId').text();
			$("#input_order").val(orderId);
			log(orderId);
		}		
	});
	</script>
</body>
</html>	
