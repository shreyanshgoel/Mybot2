<?php

/**
* Example HTTP GET request
*/
// include our classes

require '../autoloader.php';
// execute example HTTP GET request

$response = Mybot2\lib\HTTP\Request::head('http://www.swiftintern.com/');

// print out HTTP response (\HTTP\Response object)


// display response status
if($response->success){
	
	echo 'Successful request <br />';
}else{
	
	echo 'Error: request failed, status code: ' . $response->getStatusCode() . '<br />'; // prints status code
}

?>