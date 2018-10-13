<?php

session_start();
if ($_SESSION['email']!="bigsqueak@pipsqueak.com")
	header("location: ../index.php");

if($_SERVER["REQUEST_METHOD"] == "POST"){
	
	if(isset($_GET["upload"])){
		
		require_once "../_config.php";
	
		if($_GET["upload"]=="single"){
			var_dump($_REQUEST);
			$csvString = $_POST["csvString"];
			$data = str_getcsv($csvString, "\n"); //parse the rows
			
			$success = 0;
			$failures = 0;
			
			foreach($data as &$row) {
				$row = str_getcsv($row); //parse the items in rows 
				if (!empty($row[0])){
					
					// Prepare an insert statement
					$sql = "INSERT INTO Inventory (itemName, description, imageUrl, items, category) VALUES (?, ?, ?, ?, ?)";
					 
					if($stmt = mysqli_prepare($link, $sql)){
						// Bind variables to the prepared statement as parameters
						mysqli_stmt_bind_param($stmt, "sssss", $itemName, $description, $imageUrl, $item, $category);
						
						// Set parameters
						$itemName = $row[0];
						$description = $row[1];
						$imageUrl = $row[2];
						$category = strtolower($row[3]);
						$price = $row[4];
						$availability = $row[5];
						
						$item=json_encode(array(
							'price' => $price,
							'availability' => $availability
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
			mysqli_close($link);
			
			echo "Upload status: {$success} success, {$failures} failures.";
		}
		else{
			$itemGen = str_getcsv($_POST["item"]);
			// Set parameters
			$itemName = $itemGen[0];
			$description = $itemGen[1];
			$defImageUrl = $itemGen[2];
			$category = strtolower($itemGen[3]);
			
			$x=0;
			$options_multi=array();
			while(!empty($_POST["options{$x}"])&&!empty($_POST["type{$x}"])){
				$options = str_getcsv($_POST["options{$x}"]);
				$type = $_POST["type{$x}"];
				
				$options_multi[$type] = array();
				$options_multi[$type][] = $options;
				$x++;
			}
			$options_multi=json_encode($options_multi);
			
			$items_multi=array();
			$data = str_getcsv($_POST["items"], "\n"); //parse the rows
			foreach($data as &$row) {
				$row = str_getcsv($row);
				
				$addDescription = $row[0];
				$imageUrl = $row[1];
				$price = $row[2];
				$availability = $row[3];
				
				$item=array(
					'description' => $addDescription,
					'imageUrl' => $imageUrl,
					'price' => $price,
					'availability' => $availability
				);
				array_push($items_multi,$item);
			}
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
	<link rel="stylesheet" href="../css/pipsqueaks.css">
    <title>fixsqueaks</title>
  </head>
  
  <body>
	
	<div class="container">
		<form id="itemSingle" enctype="multipart/form-data" action="<?=htmlspecialchars($_SERVER["PHP_SELF"])."?upload=single"?>" method="post">
			<h5>csv upload (for single items)</h5>
			<p>Input format:<br>
			itemName0, description0, imageUrl0, category0, price0, availability0<br>
			itemName1, description1, imageUrl1, category1, price1, availability1
			</p>
			<textarea rows="4" cols="100%" form="itemSingle" name="csvString" placeholder="itemName, description, imageUrl, category, price, availability..." required></textarea>
			<div class="form-group">
				<input type="submit" class="btn btn-primary" value="Submit">
			</div>
		</form>
		<form id="itemGroup" enctype="multipart/form-data" action="<?=htmlspecialchars($_SERVER["PHP_SELF"])."?upload=group"?>" method="post">
			<h5>item group upload (SINGLE ITEM GROUP ONLY)</h5>
			<input type="text" class="form-control" name="item" placeholder="itemName, description, defaultImageUrl, category" required>
			<div id="itemOptions">
				<div id="options0" class="form-group form-row">
					<input type="text" class="form-control col-3" name="type0" placeholder="type">
					<input type="text" class="form-control itemOptions col-9" name="options0" id="inputOptions0" placeholder="option1, option2...">
				</div>
			</div>
			<button id="button_addOption" type="button" class="btn btn-outline-danger">Add new option</button>
			<button id="button_deleteOption" type="button" class="btn btn-outline-danger">Delete option</button>
			<div id="items" class="form-group">
				<div id='itemsHelp'></div>
				<textarea rows="4" cols="100%" form="itemGroup" name="items" placeholder="description, imageUrl, price, availability" required></textarea>
			</div>
			<div class="form-group">
				<input type="submit" class="btn btn-primary disabled" value="Submit">
			</div>
		</form>
	</div>	
	
	<?php include "../footer.php";?>
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>	
	<script src="../js/upload.js"></script>
  </body>
</html>