<?php
session_start();
if (!isset($_SESSION['email'])){
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
	<link rel="stylesheet" href="css/pipsqueaks.css">
    <title>fixsqueaks</title>
  </head>
  
  <body>
	<div class="container">
		<h3>Order Admin Panel</h3>
		<div class='row itemHeader'>
			<div class='col-2'>UID</div>
			<div class='col-8'>Items</div>
			<div class='col-2'>Status</div>
		</div>
	<?php		
	
	
	$sql = "SELECT * FROM Orders WHERE status = 'PENDING'";
	$result = $link->query($sql);
	while($row = $result->fetch_assoc()) {
		echo "<div class='row cart_item'>
					<div class='col-2'>".$row["userId"]."</div>
					<div class='col-8'>".$row["itemsBought"]."</div>
					<div class='col-2'>".$row["status"]."</div>
				</div>";
	}	
	
		
	echo '<div class="float-right">
			<button id="button_reset" type="button" class="btn btn-outline-danger">Reject order</button>
			<a id="button_confirmPurchase" type="button" class="btn btn-outline-primary" href="'.htmlspecialchars($_SERVER["PHP_SELF"]).'?action=purchase">Issue receipt</a>
		</div>';

	?>
    </div>    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>	
</body>
</html>	
