<?php
require_once "../_config.php";
session_start();
if (!isset($_SESSION['admin'])) header("location: ../");

if($_SERVER["REQUEST_METHOD"] == "POST"){
	$itemName = $_POST["itemName"];
	$imageUrl = $_POST["imageUrl"];
	$description = $_POST["description"];
	//$options = $_POST["options"];
	$category = $_POST["category"];
	$items["price"] = $_POST["price"];
	$items["quantity"] = $_POST["quantity"];
	$items = json_encode($items);
	
	// $sql="UPDATE FROM Inventory SET itemName='{$itemName}', imageUrl='{$imageUrl}', description='{$description}', category='{$category}', items='{$items}' WHERE itemId = {$_GET["id"]}";
	$sql="UPDATE Inventory SET itemName=?, description=?, imageUrl=?, category=?, items=? WHERE itemId = {$_GET["id"]}";
	echo $sql;
	
	if($stmt = mysqli_prepare($link, $sql)){
		mysqli_stmt_bind_param($stmt, "sssss", $itemName, $description, $imageUrl, $category, $items);
		
		// Attempt to execute the prepared statement
		if(mysqli_stmt_execute($stmt)){
			echo "Successfully updated item!";
		} else{
			echo "Failed to execute statement...";
		}
		mysqli_stmt_close($stmt);
	} else printf("Error: %s\n", $link->error);	
} 
else if(isset($_GET["delete"])){
	$sql="DELETE FROM Inventory WHERE itemId = {$_GET["id"]}";
	$result = $link->query($sql);
	if ($result === TRUE){
		mysqli_close($link);
		header("location: index");
	} else echo "Deletion error!";
	$result->close();
}

$sql="SELECT * FROM Inventory WHERE itemId = {$_GET["id"]}";
$result = $link->query($sql);
while($row = $result->fetch_assoc()) {
	if ($result->num_rows== 0) echo "No such item!";
	$itemName = $row["itemName"];
	$imageUrl = $row["imageUrl"];
	$description = $row["description"];
	$options = $row["options"];
	$category = $row["category"];
	$items_json = $row["items"];
	$items = json_decode($items_json, true);
}

mysqli_close($link);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit</title>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../css/pipsqueaks.css">
</head>
<body class="bg-light">
    <div class="container">
	<h2>Item Details</h2>
        <form id="itemForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])."?id=".$_GET["id"]?>" method="post">
			<div class="row">
				<div class="form-group col-4">
					<label>Item Name</label>
					<input type="text" name="itemName" class="form-control" value="<?=$itemName?>" required>
				</div>
				<div class="form-group col-4">
					<label>Image Url</label>
					<input type="text" name="imageUrl" class="form-control" value="<?=$imageUrl?>" required>
				</div>
				<div class="form-group col-4">
					<label>Category</label>
					<input type="text" name="category" class="form-control" value="<?=$category?>" required>
				</div>
				<div class="form-group col-4">
					<label>Price</label>
					<input type="number" name="price" step="0.01" min="0" class="form-control" value="<?=$items["price"]?>" required>
				</div>
				<div class="form-group col-4">
					<label>Quantity</label>
					<input type="number" name="quantity" class="form-control" value="<?=$items["quantity"]?>" required>
				</div>
			</div>
			
			<div><label>Description</label></div>
			<textarea rows="4" cols="100%" form="itemForm" name="description" required><?=$description?></textarea>
			
            <div class="form-group mt-3">
                <input type="submit" class="btn btn-primary" value="Confirm Edit">
				<a href="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])."?id=".$_GET["id"]?>&delete=true" class="btn btn-danger">Delete Item</a>
                <a href="index" class="btn btn-default">Cancel</a>
            </div>
        </form>
    </div>    
</body>
</html>