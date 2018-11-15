<?php
//TODO: security and get check
const IMAGE_SIZE = [
    "s"     => 150,
    "m"    => 500,
    "l"     => 1000,
]; 

header('Content-Type: image/jpg');
$fileDir = realpath(dirname(__FILE__))."/../private_html/assets/uploads/images/";
$file = $fileDir.$_GET['upload'];

if (!file_exists($file)||empty($_GET['upload']))
	$file = $fileDir."pipsqueak.jpg";

readfile($file);

?>