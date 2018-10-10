<?php
// require_once "_config.php";

session_start();
if ($_SESSION['email']!="bigsqueak@pipsqueak.com")
	header("location: ../index.php");

if($_SERVER["REQUEST_METHOD"] == "POST"){
	
	require_once "../_config.php";
	
	$csvString = $_POST["csvString"];
	$data = str_getcsv($csvString, "\n"); //parse the rows
	
	$success = 0;
	$failures = 0;
	
	foreach($data as &$row) {
		$row = str_getcsv($row); //parse the items in rows 
		if (!empty($row[0])){
			
			// Prepare an insert statement
			$sql = "INSERT INTO Items (item, description, imageUrl, category, price) VALUES (?, ?, ?, ?, ?)";
			 
			if($stmt = mysqli_prepare($link, $sql)){
				// Bind variables to the prepared statement as parameters
				mysqli_stmt_bind_param($stmt, "ssssd", $param_item, $param_description, $param_imageUrl, $param_category, $param_price);
				
				// Set parameters
				$param_item = $row[0];
				$param_description = $row[1];
				$param_imageUrl = $row[2];
				$param_category = $row[3];
				$param_price = $row[4];
				
				// Attempt to execute the prepared statement
				if(mysqli_stmt_execute($stmt)){
					$success++;
					//echo "Success!";
				} else{
					$failures++;
					//echo "Something went wrong. Please try again later.";
				}
				mysqli_stmt_close($stmt);
			}
		}
	}
	mysqli_close($link);
	echo "Upload status: {$success} success, {$failures} failures.";
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
	<link rel="stylesheet" href="css/pipsqueaks.css">
    <title>fixsqueaks</title>
  </head>
  
  <body>
	<?php include "../header.php";?>
	
	<div class="container">
		<div class="row">
			<form id="csvForm" enctype="multipart/form-data" action="<?=htmlspecialchars($_SERVER["PHP_SELF"])?>" method="post">
				<!--div class="custom-file">
					<input type="file" class="custom-file-input" id="validatedCustomFile" name="csvFile" accept=".csv" required>
					<label class="custom-file-label" for="validatedCustomFile">Choose csv file...</label>
				</div-->
				<textarea rows="4" cols="50" form="csvForm" name="csvString" required></textarea>
				<div class="form-group">
					<input type="submit" class="btn btn-primary" value="Submit">
				</div>
			</form>
		</div>
	</div>	
	
	<?php include "../footer.php";?>
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>	
	<script src="js/logon.js"></script>
  </body>
</html>