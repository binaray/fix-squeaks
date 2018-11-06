<?php
// require_once "../_config.php";
//check if user exists otherwise add new user
$sql = "SELECT userId, email FROM Users WHERE telegramId = {$telegramId}";
$result = $link->query($sql);

if ($result->num_rows == 0) {
	//create new user with only telegramId
	$param_telegramId = $telegramId;
	$sql = "INSERT INTO Users (telegramId) VALUES (?)";		
	
	mysqli_query($link, "INSERT INTO Users (telegramId) VALUES ('{$telegramId}}')");
	$userId = mysqli_insert_id($link);
	
	$additional_result_text = "To receive receipts you need to complete registration from the link below. Once your order has been processed, the receipt will be emailed to you. \n".
								"<a href='/logon/register?telegramId=".$telegramId."'>Register here</a>";
} 
else {
	while($row = $result->fetch_assoc()) {	
		$userId = $row["userId"];
		$email = $row["email"];
		if (empty($email)){
			$additional_result_text = "To receive receipts you need to complete registration from the link below. Once your order has been processed, the receipt will be emailed to you. \n".
										"<a href='/logon/register?telegramId=".$telegramId."'>Register here</a>";
		}
	}
}
?>