<?php
session_start();
//to convert to ajax
//Check admin email here
require_once "../../_config.php";

if($_SERVER["REQUEST_METHOD"] == "POST"){
	$orderId=$_POST["orderId"];
	
	if(isset($_POST["issue"])) {
		$sql = "SELECT Users.userId, Users.name, Users.email, Orders.orderId, Orders.itemsBought, Orders.createdAt FROM Orders INNER JOIN Users ON Orders.userID=Users.userID WHERE Orders.orderId = '{$orderId}'";
		$result = $link->query($sql);
		while($row = $result->fetch_assoc()) {
			$itemsBought=$row["itemsBought"];
			$invNo = $row["orderId"];
			$clientName = $row["name"];
			$email = $row["email"];
			$date = $row["createdAt"];
		}
		// $date = date_create();
		// $date = date_timestamp_get($date);
		
		// $data = array(
			// 'invNo' => $invNo,
			// 'clientName' => $clientName,
			// 'email' => $email,
			// 'itemsBought' => $itemsBought,
			// 'dateTimeGenerated' => $date
		 // );
		
		
		
		// $jsonEncodedData = json_encode($data);
		
		$sql = "UPDATE Orders SET status='APPROVED' WHERE orderId={$orderId}";
		if ($link->query($sql) === TRUE) {
			echo "Record updated successfully";
		} else {
			echo "Error updating record: " . $conn->error;
		}
	}
	else if(isset($_POST["review"])) { 
		$sql = "UPDATE Orders SET status='TO REVIEW' WHERE orderId={$orderId}";
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
		<form id="betapost" action="https://beta.pipsqueak.sg" target="_blank" method="post">
            <input type="text" name="date" value="<?=$date?>" style="display: none;">
            <input type="text" name="invNo" value="<?=$invNo?>" style="display: none;">
            <input type="text" name="clientName" value="<?=$clientName?>" style="display: none;">
            <input type="text" name="email" value="<?=$email?>" style="display: none;">
            <input type="text" name="itemsBought" value='<?=$itemsBought?>' style="display: none">
			
			<?php
			if(isset($_POST["issue"]))
				echo '<input type="submit" class="btn btn-primary" value="Click to generate receipt!" style="">';
			?>
        </form>
		
		<a href="../orders">Return</a>
  
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>	
	<script type="text/javascript">    
    'use strict'
	let log = console.log.bind(console);
	
	$(document).ready(function () {
	});
	</script>
</body>
</html>	