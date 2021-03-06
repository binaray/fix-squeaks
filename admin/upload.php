<?php

session_start();
if (!isset($_SESSION['admin']))
	header("location: ../index.php");

if($_SERVER["REQUEST_METHOD"] == "POST"){
	$valid_extensions = array('jpeg', 'jpg', 'png', 'gif', 'bmp'); // valid extensions
	$path = '../assets/uploads/images'; // upload directory
	
	if(isset($_GET["upload"])){
		
		require_once "../_config.php";
		
		//get vendorId first
		$sql="SELECT vendorId FROM Vendors WHERE email = '{$_SESSION["admin"]}'";
		$result = $link->query($sql);
		while($row = $result->fetch_assoc()) {
			if ($result->num_rows== 0) echo "Please login!";
			$vendorId = $row["vendorId"];
		}
		$result->close();
		echo $vendorId;
		if($_GET["upload"]=="single"){
			var_dump($_REQUEST);
			$csvString = $_POST["csvString"];
			$data = str_getcsv($csvString, "\n"); //parse the rows
			
			$success = 0;
			$failures = 0;
			
			foreach($data as &$row) {
				$row = str_getcsv($row, "|"); //parse the items in rows 
				if (!empty($row[0])){
					
					// Prepare an insert statement
					$sql = "INSERT INTO Inventory (itemName, description, imageUrl, items, category, vendorId) VALUES (?, ?, ?, ?, ?, ?)";
					 
					if($stmt = mysqli_prepare($link, $sql)){
						// Bind variables to the prepared statement as parameters
						mysqli_stmt_bind_param($stmt, "ssssss", $itemName, $description, $imageUrl, $item, $category, $vendorId);
						
						// Set parameters
						$itemName = trim($row[0]);
						$description = trim($row[1]);
						$imageUrl = trim($row[2]);
						$category = trim(strtolower($row[3]));
						$price = trim($row[4]);
						$quantity = trim($row[5]);
						
						$item=json_encode(array(
							'price' => $price,
							'quantity' => $quantity
						));
						
						// Attempt to execute the prepared statement
						if(mysqli_stmt_execute($stmt)){
							$success++;
						} else{
							$failures++;
						}
						mysqli_stmt_close($stmt);
					}
				}
			}			
			echo "Upload status: {$success} success, {$failures} failures.";
		}
		else{
			$sql = "INSERT INTO Inventory (itemName, description, imageUrl, options, items, category) VALUES (?, ?, ?, ?, ?, ?)";
			
			if($stmt = mysqli_prepare($link, $sql)){
				// Bind variables to the prepared statement as parameters
				mysqli_stmt_bind_param($stmt, "ssssss", $itemName, $description, $defImageUrl, $options_multi, $items, $category);
				
				$itemGen = str_getcsv($_POST["item"], "|");	//general item details
				// Set parameters
				$itemName = trim($itemGen[0]);
				$description = trim($itemGen[1]);
				$defImageUrl = trim($itemGen[2]);
				$category = trim(strtolower($itemGen[3]));
				
				$x=0;
				$options_multi=array();
				while(!empty($_POST["options{$x}"]) && !empty($_POST["type{$x}"])){
					$dict=array();
					$options = str_getcsv($_POST["options{$x}"], ",");
					$type = $_POST["type{$x}"];
					
					$dict[$type] = array();
					// $options_multi[$type] = array();
					foreach($options as $option){
						array_push($dict[$type],trim($option));
					}
					array_push($options_multi,$dict);
					$x++;
				}
				$options_multi=json_encode($options_multi);
				// echo $options_multi;
				
				$items=array();	//array to store items
				$data = str_getcsv($_POST["items"], "\n"); //parse the rows
				$property_combination = str_getcsv($_POST["propertyCombination"], "\n");
				
				for ($i = 0; $i < count($data); $i++) {
					$row = str_getcsv($data[$i], "|");
					$properties = str_getcsv($property_combination[$i]);
					$empty = array_shift($properties);
					$properties = array_map('trim', $properties);
					$properties = json_encode($properties);
					
					$addDescription = trim($row[0]);
					$imageUrl = trim($row[1]);
					$price = trim($row[2]);
					$quantity = trim($row[3]);
					
					$items[$properties]=array(
						'description' => $addDescription,
						'imageUrl' => $imageUrl,
						'price' => $price,
						'quantity' => $quantity
					);
				}
				$items = json_encode($items);
				
				// Attempt to execute the prepared statement
				if(mysqli_stmt_execute($stmt)){
					echo "Successfully added multi-item!";
				} else{
					echo "Failed to execute statement...";
				}
				mysqli_stmt_close($stmt);
			}
		}
		mysqli_close($link);
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
	<link rel="stylesheet" href="../css/pipsqueaks.css">
    <title>fixsqueaks</title>
  </head>
  
  <body>
	
	<div class="container">
		<form id="itemSingle" enctype="multipart/form-data" action="<?=htmlspecialchars($_SERVER["PHP_SELF"])."?upload=single"?>" method="post">
			<h5>CSV text upload</h5>
			<p>Input format:<br>
			itemName0 | description0 | imageUrl0 | category0 | price0 | quantity0<br>
			itemName1 | description1 |imageUrl1 | category1 | price1 | quantity1<br>
			(for quantity: 0 if out of stock, -1 if unavailable)
			</p>
			<textarea rows="4" cols="100%" form="itemSingle" name="csvString" placeholder="itemName | description | imageUrl | category | price | quantity..." required></textarea>
			<div class="form-group mt-2">
				<input type="submit" class="btn btn-primary" value="Submit">
				<a href="index" class="btn btn-default">Cancel</a>
			</div>
		</form>
		<form id="itemGroup" enctype="multipart/form-data" action="<?=htmlspecialchars($_SERVER["PHP_SELF"])."?upload=group"?>" method="post" style="display: none;">
			<h5>item group upload (SINGLE ITEM GROUP ONLY)</h5>
			<p>Input format:<br>
			itemName | description | defaultImageUrl | category
			</p>
			<input type="text" class="form-control" name="item" placeholder="itemName | description | defaultImageUrl | category" required>
			<div class="row">
			
			</div>
			<div id="itemOptions">
				<div id="options0" class="form-group form-row">
					<input type="text" class="form-control col-3" name="type0" placeholder="type">
					<input type="text" class="form-control itemOptions col-9" name="options0" id="inputOptions0" placeholder="option1, option2...">
				</div>
			</div>
			<button id="button_addOption" type="button" class="btn btn-outline-danger">Add new option</button>
			<button id="button_deleteOption" type="button" class="btn btn-outline-danger">Delete option</button>
			<p>Input format:<br>
			description | imageUrl | price | quantity<br>
			description | imageUrl | price | quantity<br>
			(for quantity: 0 if out of stock, -1 if unavailable)
			</p>
			<div id="items" class="form-group form-row">
				<textarea id='itemsHelp' class="form-control col-3" rows="4" cols="100%" form="itemGroup" name="propertyCombination" placeholder="options" readonly required></textarea>
				<textarea class="form-control col-9" rows="4" cols="100%" form="itemGroup" name="items" placeholder="description | imageUrl | price | quantity" required></textarea>
			</div>
			<div class="form-group">
				<input type="submit" class="btn btn-primary" value="Submit">
				<a href="index" class="btn btn-default">Cancel</a>
			</div>
		</form>
	</div>	
	
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>	
	<script src="../js/upload.js"></script>
  </body>
</html>