<?php

session_start();
// if ($_SESSION['email']!="bigsqueak@pipsqueak.com")
	// header("location: ../index.php");

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
			
		}
	}
	// $csvFile=$_FILES["csvFile"];
// $sql = <<<eof
    // LOAD DATA INFILE '$csvFile'
     // INTO TABLE Items
     // FIELDS TERMINATED BY ','
     // LINES TERMINATED BY '\n'
    // (item,description,category,price,imageUrl)
// eof;
	// if ($link->query($sql) === TRUE) {
		// echo "Query successful:".$sql;
	// } else {
		// echo "Query error: " . $link->error;
	// }
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
	<?php include "../header.php";?>
	
	<div class="container">
		<div class="row">
			<form id="itemSingle" enctype="multipart/form-data" action="<?=htmlspecialchars($_SERVER["PHP_SELF"])."?upload=single"?>" method="post">
				<!--div class="custom-file">
					<input type="file" class="custom-file-input" id="validatedCustomFile" name="csvFile" accept=".csv" required>
					<label class="custom-file-label" for="validatedCustomFile">Choose csv file...</label>
				</div-->
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
		</div>
		<div class="row">
			<form id="itemGroup" enctype="multipart/form-data" action="<?=htmlspecialchars($_SERVER["PHP_SELF"])."?upload=group"?>" method="post">
				<h5>item group upload</h5>
				<textarea rows="4" cols="100%" form="itemGroup" name="csvString" placeholder="Not yet available.." readonly required></textarea>
				<div class="form-group">
					<input type="submit" class="btn btn-primary disabled" value="Submit">
				</div>
			</form>
		</div>
	</div>	
	
	<?php include "../footer.php";?>
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>	
	<script src="../js/logon.js"></script>
	<script type="text/javascript">
	
	</script>
  </body>
</html>