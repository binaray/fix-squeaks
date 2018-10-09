<?php
$url = parse_url(getenv("CLEARDB_DATABASE_URL"));

// $server = $url["host"];
// $username = $url["user"];
// $password = $url["pass"];
// $db = substr($url["path"], 1);

// $link = new mysqli($server, $username, $password, $db);

define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'test');
 
/* Attempt to connect to MySQL database */
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);


if ($link->connect_error) {
    die("Connection failed: " . $link->connect_error);
}

// sql code to create tables
// $sql = "CREATE TABLE Users(
        // userId INT(2) NOT NULL PRIMARY KEY AUTO_INCREMENT,
        // email VARCHAR(50) NOT NULL UNIQUE,
        // password VARCHAR(255) NOT NULL,
        // name VARCHAR(30) NOT NULL,
		// phone INT(2)
        // )";
// $sql = "CREATE TABLE Receipts(
        // receiptId INT(3)  PRIMARY KEY AUTOINCREMENT, 
		// userId INT(2) NOT NULL,
        // itemsBought VARCHAR(1000) NOT NULL,
		// createdAt DATETIME DEFAULT CURRENT_TIMESTAMP
		// }";
// $sql = "CREATE TABLE Items(
        // itemId INT(3)  PRIMARY KEY AUTOINCREMENT, 
        // item VARCHAR(30) NOT NULL,
		// description VARCHAR(1000),
		// image VARCHAR(MAX),
        // category VARCHAR(30),
		// price FLOAT(10)
        // )";
// $sql = "CREATE TABLE OnSale(
        // vendorId INT(3)  PRIMARY KEY AUTOINCREMENT, 
		// userId INT(2),
        // itemId INT(3),
		// price FLOAT(10),
		// quantity INT(2)
		// createdAt DATETIME DEFAULT CURRENT_TIMESTAMP
		// )";
$sql = "DROP TABLE Users";
		
if ($link->query($sql) === TRUE) {
    echo "Query successful:".$sql;
} else {
    echo "Query error: " . $link->error;
}
?>