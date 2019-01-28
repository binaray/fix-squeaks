<?php
require_once "../_config.php";

if (isset($_GET["orderId"])){
	$sql = "SELECT Orders.orderId, Orders.userId, Users.telegramId, Orders.itemsBought, Orders.status, Orders.createdAt FROM Orders INNER JOIN Users ON Orders.userId = Users.userId WHERE orderId = '{$_GET["orderId"]}'";
	$result = $link->query($sql);

	while($row = $result->fetch_assoc()) {
		if ($result->num_rows== 0) echo "No such order!";
		else {
			$row["itemsBought"]=json_decode($row["itemsBought"]);
			echo json_encode($row);
		}
	}
	mysqli_close($link);
}
?>