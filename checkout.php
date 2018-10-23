<?php
session_start();
if (!isset($_SESSION['email'])){
	header("location: /");
}

if(isset($_GET["action"]) && isset($_SESSION["cart"])){
	require_once "_config.php";
	
	$sql = "SELECT userId FROM Users WHERE email = '{$_SESSION['email']}'";
	$result = $link->query($sql);
	while($row = $result->fetch_assoc()) {
		$userId=$row["userId"];
	}
	if (isset($userId)){
		
		$itemsBought = array();
		
		foreach ($_SESSION["cart"] as $item){
			array_push($itemsBought,json_decode($item,true));
		}
		
		var_dump($itemsBought);
		$itemsBought = json_encode($itemsBought);
		$status = "PENDING";
		
		$sql = "INSERT INTO Orders (userId, itemsBought, status) VALUES (?, ?, ?)";	
		if($stmt = mysqli_prepare($link, $sql)){
			// Bind variables to the prepared statement as parameters
			mysqli_stmt_bind_param($stmt, "sss", $userId, $itemsBought, $status);
			
			// Attempt to execute the prepared statement
			if(mysqli_stmt_execute($stmt)){
				echo "Successfully added!\n";
				unset($_SESSION["cart"]);
			} else{
				echo "Something went wrong. Please try again later.";
			}
		}
		mysqli_stmt_close($stmt);
	}
	mysqli_close($link);
}

//Shopping cart options
//---------------------------
//Reset
if (isset($_GET['reset'])){
	if ($_GET["reset"] == 'true'){
		unset($_SESSION["cart"]); 
	}
}

//---------------------------
//Add
if (isset($_GET["add"])){
	$_SESSION["cart"][$i] = $i;
}

//---------------------------
//Delete
if (isset($_GET["delete"])){
	$i = $_GET["delete"];
	$qty = $_SESSION["qty"][$i];
	$qty--;
	$_SESSION["qty"][$i] = $qty;
	//remove item if quantity is zero
	if ($qty == 0){
		$_SESSION["amounts"][$i] = 0;
		unset($_SESSION["cart"][$i]);
	}
	else{
		$_SESSION["amounts"][$i] = $amounts[$i] * $qty;
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
	<link rel="stylesheet" href="css/pipsqueaks.css">
    <title>fixsqueaks</title>
  </head>
  
  <body>
	<div class="container">
		<h3>Check-out</h3>
		<div class='row itemHeader'>
			<div class='col-6'>Item</div>
			<div class='col-2'>Cost</div>
			<div class='col-2'>Qty</div>
			<div class='col-2'>Total</div>
		</div>
	<?php
	if (isset($_SESSION["cart"])){
		
		$grandTotal=0.00;
		
		foreach ($_SESSION["cart"] as $item){
			$item = json_decode($item,true);
			
			$properties_html="<br>";
			if (isset($item["properties"])){
				foreach ($item["properties"] as $property){
					$properties_html.="<span class='property'>{$property} </span>";
				}
			}
			$total = $item["quantity"]*$item["price"];
			
			echo "<div class='row cart_item'>
						<div class='col-6'>".$item["itemName"].$properties_html."</div>
						<div class='col-2'>".$item["price"]."</div>
						<div class='col-2'>".$item["quantity"]."</div>
						<div class='col-2'>".$total."</div>
					</div>";
			$grandTotal+=$total;
		}
		
		echo "<div class='row itemHeader'>
				<div class='col-10 text-right'>Grand total:</div>
				<div class='col-2'>".$grandTotal."</div>
			</div>";
			
		echo '<div class="float-right">
				<button id="button_reset" type="button" class="btn btn-outline-danger">Clear cart</button>
				<a id="button_confirmPurchase" type="button" class="btn btn-outline-primary" href="'.htmlspecialchars($_SERVER["PHP_SELF"]).'?action=purchase">Confirm purchase</a>
			</div>';
	}
	else{
		echo "No items added";
	}
	?>
    </div>    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>	
</body>
</html>	
