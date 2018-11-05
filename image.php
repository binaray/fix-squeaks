<?php
//TODO: security and get check
header('Content-Type: image/jpg');
$fileDir = realpath(dirname(__FILE__))."/../private_html/assets/uploads/images/";
$file = $fileDir.$_GET['upload'];

if (!file_exists($file)||empty($_GET['upload']))
	readfile($fileDir."pipsqueak.jpg");
else
	readfile($file);

?>