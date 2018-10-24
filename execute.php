<?php
$url = parse_url(getenv("CLEARDB_DATABASE_URL"));

$server = $url["host"];
$username = $url["user"];
$password = $url["pass"];
$db = substr($url["path"], 1);

$link = new mysqli($server, $username, $password, $db);


if ($link->connect_error) {
    die("Connection failed: " . $link->connect_error);
}

// $sql=array();

// sql code to create tables
$sql[0] = "CREATE TABLE Users(
        userId BIGINT NOT NULL PRIMARY KEY AUTO_INCREMENT,
        email VARCHAR(100) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        name VARCHAR(100) NOT NULL,
		phone INT(3),
		telegramId BIGINT
        )";
$sql[1] = "CREATE TABLE Inventory(
        itemId BIGINT NOT NULL PRIMARY KEY AUTO_INCREMENT,
        itemName VARCHAR(50) NOT NULL,
		description TEXT,
		imageUrl VARCHAR(2000),
		options TEXT,
		items TEXT,
        category VARCHAR(30)
		)";
$sql = "CREATE TABLE Orders(
        orderId BIGINT NOT NULL PRIMARY KEY AUTO_INCREMENT,
		userId BIGINT,
        itemsBought TEXT NOT NULL,
		status VARCHAR(10),
		createdAt DATETIME DEFAULT CURRENT_TIMESTAMP,
		FOREIGN KEY (userId) REFERENCES Users(userId)
		)";
$sql[3] = "CREATE TABLE Listings(
        listingId BIGINT NOT NULL PRIMARY KEY AUTO_INCREMENT, 
		userId BIGINT NOT NULL,
        itemId BIGINT,
		properties TEXT,	//json{type:property, type:property} must comply with property order listing
		price FLOAT(10),
		quantity INT(2),
		createdAt DATETIME DEFAULT CURRENT_TIMESTAMP,	//ignore
		reserved BOOLEAN,		//ignore
		FOREIGN KEY (userId) REFERENCES Users(userId),
		FOREIGN KEY (itemId) REFERENCES Inventory(itemId)
		)";
// $sql[4] = "CREATE TABLE Analytics(
        // id INT(2) NOT NULL PRIMARY KEY AUTO_INCREMENT,
        // item VARCHAR(50) NOT NULL UNIQUE,
		// timesClicked INT(2),
		// )";
// $sql = "DROP TABLE Receipts";
// $sql = "ALTER TABLE Users
		// ADD telegramId BIGINT";

// foreach ($sql as $statement){
	if ($link->query($sql) === TRUE) {
		echo "Query successful:".$sql."\n";
	} else {
		echo "Query error: " . $link->error."\n";
	}
// }
?>