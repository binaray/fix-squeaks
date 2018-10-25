<?php
require_once(realpath(dirname(__FILE__))."/../private_html/ENV_CONFIG.php"); 

/* Attempt to connect to MySQL database */
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

if ($link->connect_error) {
    die("Connection failed: " . $link->connect_error);
}
?>