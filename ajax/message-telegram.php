<?php
//TODO: secure this endpoint
require_once (realpath(dirname(__FILE__))."/../_config.php");
require_once (realpath(dirname(__FILE__))."/../_functions/curl.php");

function messageTelegram($text){
	$botID = TELE_BOT_ID;
	$chatID = TELE_ADMIN_GROUP;
	
	$text = urlencode($text);	
	$url = "https://api.telegram.org/bot{$botID}/sendMessage?chat_id={$chatID}&text={$text}";
	mycurl($url);
}
?>