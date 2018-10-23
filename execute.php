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
// $sql[0] = "CREATE TABLE Users(
        // userId INT(2) NOT NULL PRIMARY KEY AUTO_INCREMENT,
        // email VARCHAR(50) NOT NULL UNIQUE,
        // password VARCHAR(255) NOT NULL,
        // name VARCHAR(50) NOT NULL,
		// phone INT(2),
		// telegramId BIGINT
        // )";
// $sql[1] = "CREATE TABLE Inventory(
        // itemId INT(3) NOT NULL PRIMARY KEY AUTO_INCREMENT,
        // itemName VARCHAR(30) NOT NULL,
		// description VARCHAR(1000),
		// imageUrl VARCHAR(2000),
		// options TEXT,
		// items TEXT,
        // category VARCHAR(30)
		// )";
$sql = "CREATE TABLE Orders(
        receiptId BIGINT NOT NULL PRIMARY KEY AUTO_INCREMENT,
		userId INT(2),
        itemsBought TEXT NOT NULL,
		status VARCHAR(10),
		createdAt DATETIME DEFAULT CURRENT_TIMESTAMP,
		FOREIGN KEY (userId) REFERENCES Users(userId)
		)";
// $sql[3] = "CREATE TABLE Listings(
        // listingId BIGINT NOT NULL PRIMARY KEY AUTO_INCREMENT, 
		// userId INT(2) NOT NULL,
        // itemId INT(3),
		// properties TEXT,	//json{type:property, type:property}
		// price FLOAT(10),
		// quantity INT(2),
		// createdAt DATETIME DEFAULT CURRENT_TIMESTAMP,	//ignore
		// reserved BOOLEAN,		//ignore
		// FOREIGN KEY (userId) REFERENCES Users(userId)
		// )";
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