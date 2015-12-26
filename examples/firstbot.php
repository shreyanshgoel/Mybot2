<?php


require '../autoloader.php';

// set unlimited execution time

set_time_limit(0);

// set default timeout to 30 seconds

Mybot2\lib\WebBot\WebBot::$conf_default_timeout = 30;

// set delay between fetches to 1 seconds

Mybot2\lib\WebBot\WebBot::$conf_delay_between_fetches = 1;

// do not use HTTPS protocol (we'll use HTTP protocol)

Mybot2\lib\WebBot\WebBot::$conf_force_https = false;

// do not include document field raw values

Mybot2\lib\WebBot\WebBot::$conf_include_document_field_raw_values = false;


// URLs to fetch data from
$urls = [
	'search' => 'http://www.medifee.com/tests/'
];

// document fields [document field ID => document field regex
// pattern, [...]]

$document_fields = [
'links'=> '<td><a href=\'(.*)\'>',
'title'=>'<tr><td>(.*)\<\/td>'

];

// set WebBot object
$webbot = new Mybot2\lib\WebBot\WebBot($urls, $document_fields);

// execute fetch data from URLs
$webbot->execute();



// display documents summary
echo $webbot->total_documents . ' total documents <br />';
echo $webbot->total_documents_success . ' total documents fetched successfully <br />';
echo $webbot->total_documents_failed . ' total documents failed to fetch <br /><br />';

// check if fetch(es) successful
if($webbot->success){

// display each document
	foreach($webbot->getDocuments() as /* \WebBot\Document */ $document){
			
			// was document data fetched successfully?
			if($document->success) {

				// display document meta data
				
				echo 'Document: ' . $document->id . '<br />';
				echo 'URL: ' . $document->url . '<br />';
				
				// display/print document fields and values
				
				$fields = $document->getFields();
				echo '<pre>' . print_r($fields, true) . '</pre>';
			}

		// failed to fetch document data, display error
			else{

				echo 'Document error: ' . $document->error . '<br />';
			}
	}
}
// not successful, display error
else{

	echo 'Failed, error: ' . $webbot->error;
}

for($i=0; $i<148; $i++){

	$newurls[$i] = 'http://www.medifee.com' . $fields['links'][$i];
}

// document fields [document field ID => document field regex
// pattern, [...]]

$newdocument_fields = [
	'para' => '<p>(.*)\<\/p>'
];

// set WebBot object
$webbot = new Mybot2\lib\WebBot\WebBot($newurls, $newdocument_fields);

// execute fetch data from URLs
$webbot->execute();

// display documents summary
echo $webbot->total_documents . ' total documents <br />';
echo $webbot->total_documents_success . ' total documents fetched successfully <br />';
echo $webbot->total_documents_failed . ' total documents failed to fetch <br /><br />';

// check if fetch(es) successful
if($webbot->success){

// display each document

	foreach($webbot->getDocuments() as /* \WebBot\Document */ $document){
			
			// was document data fetched successfully?
			if($document->success) {

				// display document meta data
				
				$id = $document->id;

				echo 'Document: ' . $id . '<br />';
				echo 'URL: ' . $document->url . '<br />';
				
				// display/print document fields and values
				
				$newfields = $document->getFields();
				echo '<pre>' . print_r($newfields, true) . '</pre>';

				$data[$id] = $newfields['para'][0];
				

			}

		// failed to fetch document data, display error
			else{

				echo 'Document error: ' . $document->error . '<br />';
			}
	}
}
// not successful, display error
else{

	echo 'Failed, error: ' . $webbot->error;
}

		$servername = "localhost";
        $username = "shrey";
        $password = "";

        // Create connection
        $conn = new PDO('mysql:host=localhost;dbname=medifee', $username, $password);
        
		// Check connection
        if (!$conn) {
                    die("Connection failed: " . mysqli_connect_error());
        }	

for($i=0; $i<148; $i++){


	$title = strip_tags($fields['title'][$i]);
	$details = strip_tags($data[$i]);

	$query = "INSERT INTO medifee (title, details) VALUES ('$title', '$details')";
	$query_run = $conn->query($query);

	if($query_run){

		echo $i . "successful" . "<br>";
	}else{
		echo $i . "not" . "<br>";
	}
}                   		


?>