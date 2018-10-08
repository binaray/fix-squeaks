<?php
$url = parse_url(getenv("CLEARDB_DATABASE_URL"));

$server = $url["host"];
$username = $url["user"];
$password = $url["pass"];
$db = substr($url["path"], 1);

$conn = new mysqli($server, $username, $password, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// sql code to create tables
// $sql = "CREATE TABLE Users(
        // userId INT(2)  PRIMARY KEY, 
        // name VARCHAR(30) NOT NULL UNIQUE,
        // email VARCHAR(50) NOT NULL,
        // password VARCHAR(50) NOT NULL,
		// phone INT(2)
        // )";
// $sql = "CREATE TABLE Items(
        // id INT(2)  PRIMARY KEY, 
        // item VARCHAR(30) NOT NULL,
		// description VARCHAR(1000),
		// image VARCHAR(MAX),
        // category VARCHAR(30),
		// stock VARCHAR(MAX),
		// price FLOAT(10)
        // )";
// $sql = "CREATE TABLE Requests(
        // id INT(2)  PRIMARY KEY, 
        // item VARCHAR(30) NOT NULL,
		// votes INT(2) NOT NULL
		// )";
$sql = "DROP TABLE Users";
		
if ($conn->query($sql) === TRUE) {
    echo "Query successful:".$sql;
} else {
    echo "Query error: " . $conn->error;
}
?>