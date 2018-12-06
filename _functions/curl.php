<?php
function mycurl($url, $method="GET", $json_request=null, $username = null, $password = null){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	
	if ($method=="GET")
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
	else if ($method=="POST")
		curl_setopt($ch, CURLOPT_POST, 1);
	
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	if (!empty($username)){ 
		curl_setopt($ch, CURLOPT_USERPWD, "{$username}:{$password}");
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
	}
	if (!empty($json_request)){
		curl_setopt($ch, CURLOPT_POSTFIELDS, $json_request);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
	}
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	
	$response = curl_exec($ch);
	
	if (curl_errno($ch)) {
		// echo 'Error:' . curl_error($ch);
		return null;
	}
	else {
		return $response;
	}
}  
?>