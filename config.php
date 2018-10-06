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
        // id INT(2)  PRIMARY KEY, 
        // name VARCHAR(30) NOT NULL,
        // email VARCHAR(50)
		// phone INT(2)
		// orders 
        // )";
// $sql = "CREATE TABLE Items(
        // id INT(2)  PRIMARY KEY, 
        // item VARCHAR(30) NOT NULL,
		// image 
        // category VARCHAR(30)
		// price FLOAT(10)
        // )";
// $sql = "CREATE TABLE Requests(
        // id INT(2)  PRIMARY KEY, 
        // item VARCHAR(30) NOT NULL
		// votes INT(2) NOT NULL
		// )";
$sql = "DROP TABLE Users";
		
if ($conn->query($sql) === TRUE) {
    echo "Query successful";
} else {
    echo "Error creating table: " . $conn->error;
}
?>