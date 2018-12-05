<?php

session_start();
if (!isset($_SESSION['email'])||$_SESSION['email']!="bigsqueak@pipsqueak.com"){
	header("location: /");
}

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
		<ul class="nav nav-tabs" id="orderTabs" role="tablist">
			<li class="nav-item">
				<a class="nav-link active tab_padding" id="statusPending-tab" data-toggle="tab" href="#statusPending" role="tab" aria-controls="statusPending" aria-selected="true">Pending</a>
			</li>
			<li class="nav-item">
				<a class="nav-link tab_padding" id="statusToReview-tab" data-toggle="tab" href="#statusToReview" role="tab" aria-controls="statusToReview" aria-selected="false">To Review</a>
			</li>
			<li class="nav-item">
				<a class="nav-link tab_padding" id="statusRejected-tab" data-toggle="tab" href="#statusRejected" role="tab" aria-controls="statusRejected" aria-selected="false">Rejected</a>
			</li>
			<li class="nav-item">
				<a class="nav-link tab_padding" id="statusSuccess-tab" data-toggle="tab" href="#statusSuccess" role="tab" aria-controls="statusSuccess" aria-selected="false">Approved</a>
			</li>
		</ul>
		<div class='row itemHeader'>
			<div class='col-1'>OID</div>
			<div class='col-1'>UID</div>
			<div class='col-3'>User Details</div>
			<div class='col-6'>Items</div>
			<div class='col-1'>Status</div>
		</div>
		
		<div class="tab-content mb-5 p-2" id="orderTabContent">
			<div class="tab-pane fade show active" id="statusPending" role="tabpanel" aria-labelledby="statusPending-tab">
				<?php			
				$sql = "SELECT * FROM Orders INNER JOIN Users ON Orders.userId = Users.userId WHERE Orders.status = 'PENDING'";
				$result = $link->query($sql);
				while($row = $result->fetch_assoc()) {
					echo "<div class='row order_item pt-1 pb-1'>
								<div class='col-1 orderId'>".$row["orderId"]."</div>
								<div class='col-1 userId'>".$row["userId"]."</div>
								<div class='col-3 userDets'>
									<p>".$row["name"]."<br>
									".$row["email"]."<br>
									".$row["phone"]."<br>
									".$row["telegramId"]."</p>
								</div>
								<div class='col-6 itemsBought'>".$row["itemsBought"]."</div>
								<div class='col-1 status'>".$row["status"]."</div>
							</div>";
				}
				?>
			</div>
			<div class="tab-pane fade" id="statusToReview" role="tabpanel" aria-labelledby="statusToReview-tab">
				<?php			
				$sql = "SELECT * FROM Orders INNER JOIN Users ON Orders.userId = Users.userId WHERE Orders.status = 'TO REVIEW'";
				$result = $link->query($sql);
				while($row = $result->fetch_assoc()) {
					echo "<div class='row order_item pt-1 pb-1'>
								<div class='col-1 orderId'>".$row["orderId"]."</div>
								<div class='col-1 userId'>".$row["userId"]."</div>
								<div class='col-3 userDets'>
									<p>".$row["name"]."<br>
									".$row["email"]."<br>
									".$row["phone"]."<br>
									".$row["telegramId"]."</p>
								</div>
								<div class='col-6 itemsBought'>".$row["itemsBought"]."</div>
								<div class='col-1 status'>".$row["status"]."</div>
							</div>";
				}
				?>
			</div>
			<div class="tab-pane fade" id="statusRejected" role="tabpanel" aria-labelledby="statusRejected-tab">
				<?php			
				$sql = "SELECT * FROM Orders INNER JOIN Users ON Orders.userId = Users.userId WHERE Orders.status = 'REJECTED'";
				$result = $link->query($sql);
				while($row = $result->fetch_assoc()) {
					echo "<div class='row order_item pt-1 pb-1'>
								<div class='col-1 orderId'>".$row["orderId"]."</div>
								<div class='col-1 userId'>".$row["userId"]."</div>
								<div class='col-3 userDets'>
									<p>".$row["name"]."<br>
									".$row["email"]."<br>
									".$row["phone"]."<br>
									".$row["telegramId"]."</p>
								</div>
								<div class='col-6 itemsBought'>".$row["itemsBought"]."</div>
								<div class='col-1 status'>".$row["status"]."</div>
							</div>";
				}
				?>
			</div>
			<div class="tab-pane fade" id="statusSuccess" role="tabpanel" aria-labelledby="statusSuccess-tab">
				<?php			
				$sql = "SELECT * FROM Orders INNER JOIN Users ON Orders.userId = Users.userId WHERE Orders.status = 'SUCCESS'";
				$result = $link->query($sql);
				while($row = $result->fetch_assoc()) {
					echo "<div class='row order_item pt-1 pb-1'>
								<div class='col-1 orderId'>".$row["orderId"]."</div>
								<div class='col-1 userId'>".$row["userId"]."</div>
								<div class='col-3 itemsBought'>
									<p>".$row["name"]."<br>
									".$row["email"]."<br>
									".$row["phone"]."<br>
									".$row["telegramId"]."</p>
								</div>
								<div class='col-6 itemsBought'>".$row["itemsBought"]."</div>
								<div class='col-1 status'>".$row["status"]."</div>
							</div>";
				}
				?>
			</div>
		</div>

		<form enctype="multipart/form-data" action="ajax/validate-order" method="post" class="button_group float-right pb-5" style="display: none;">
			<input type="number" id="input_order" name="orderId" style="display: none;" required>
			<input type="submit" class="btn btn-outline-warning" value="To Review" name="review">
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
