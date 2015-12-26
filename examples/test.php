<?php



$url = "www.google.com/intl/en/about/products/";

$ch = curl_init();	// Initialising cURL session

curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

curl_setopt($ch, CURLOPT_URL, $url);

//curl_setopt($ch, CURLOPT_USERAGENT, self::$user_agent);

$results = curl_exec($ch); // Executing cURL session

curl_close($ch); // Closing cURL session

?>