<?php
file_put_contents('/home/dh_uey5n8/imscrm.com/jonnydebugfile.txt', "\n(".date('H:i:s').") ". basename(__FILE__).':'.__LINE__." || Sendgrid Dead List Start", 8);
include("/home/dh_uey5n8/imscrm.com/lib/connect.php");

// API endpoint URL for the first call
$exportUrl = 'https://api.sendgrid.com/v3/marketing/contacts/exports';

// API endpoint URL for the second call
$getStatusUrl = 'https://api.sendgrid.com/v3/marketing/contacts/exports/';

// Bearer token
$token = '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';

// Request data for the first call
$exportData = array(
    "segment_ids" => ["e336cc0d-b58f-460c-b8c5-bd76dfe5babd"],
    "file_type" => "json"
);

// Convert data to JSON format for the first call
$jsonExportData = json_encode($exportData);

// Initialize cURL session for the first call
$ch1 = curl_init($exportUrl);

// Set cURL options for the first call
curl_setopt($ch1, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch1, CURLOPT_POSTFIELDS, $jsonExportData);
curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch1, CURLOPT_HTTPHEADER, array(
    'Authorization: Bearer ' . $token,
    'Content-Type: application/json',
    'Content-Length: ' . strlen($jsonExportData)
));

// Execute cURL request for the first call and get the response
$response1 = curl_exec($ch1);

// Check for cURL errors for the first call
if (curl_errno($ch1)) {
    echo 'Error: ' . curl_error($ch1);
}

// Close cURL session for the first call
curl_close($ch1);

//echo $response1;

sleep(5);

// Parse the JSON response from the first call to extract the ID
$responseData = json_decode($response1, true);
$id = $responseData['id'];

// Use the ID in the URL for the second call
$getStatusUrl = $getStatusUrl . $id;

//curl_setopt($ch, CURLOPT_ENCODING, 'UTF-8');

// Initialize cURL session for the second call
$ch2 = curl_init($getStatusUrl);

// Set cURL options for the second call
curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch2, CURLOPT_HTTPHEADER, array(
    'Authorization: Bearer ' . $token
));

function getStatus($ch2){
    // Check for cURL errors for the second call
    if (curl_errno($ch2)) {
        echo 'Error: ' . curl_error($ch2);
        return false; // You might want to return an error indicator here.
    }
    // Execute cURL request for the second call and get the response
    $response2 = json_decode(curl_exec($ch2), true);

    if (isset($response2['urls']) && is_array($response2['urls']) && count($response2['urls']) > 0) {
        $url = $response2['urls'][0];
        return $response2['status'] . '|' . $url;
    } else {
        // Handle the case where 'urls' array is not set or empty.
        return 'No URL found';
    }
}

/*
function getStatus($ch2){
    // Check for cURL errors for the second call
    if (curl_errno($ch2)) {
        echo 'Error: ' . curl_error($ch2);
    }
    // Execute cURL request for the second call and get the response
    $response2 = json_decode(curl_exec($ch2), true);
    $url = $response2['urls'][0];
    return $response2['status'].'|'.$url;


}
*/

$v = "pending";
$i=0;

while ($v != "ready" && $i < 100) {
    $i++;
    sleep(5);
    $theReturn = getStatus($ch2);

    if ($theReturn === false) {
        // Handle the case where there's a cURL error
        // You might want to log the error or take other appropriate action.
        continue; // Skip the rest of the loop and retry.
    }

    $explodedV = explode('|', $theReturn);

    if ($explodedV[0] === 'No URL found') {
        // Handle the case where no URL is found in the response
        // You might want to log this or take other appropriate action.
        continue; // Skip the rest of the loop and retry.
    }

    $v = $explodedV[0];
    $url = $explodedV[1];
}

/*
while($v != "ready" && $i<100){
    $i++;
    sleep(5);
    $theReturn = getStatus($ch2);
    $explodedV = explode('|',$theReturn);
    $v = $explodedV[0];
    $url = $explodedV[1];
}
*/

//echo $url;

// Close cURL session for the second call
curl_close($ch2);

$options = array(
    'http' => array('header' => 'Accept-Encoding: gzip, deflate')
);
$context = stream_context_create($options);

$fileContents = file_get_contents($url, false, $context);

if ($fileContents !== false) {
    // Check for content encoding and decompress if necessary
    $encoding = isset($http_response_header) ? implode(' ', $http_response_header) : '';
    if (stripos($encoding, 'gzip') !== false) {
        $fileContents = gzdecode($fileContents);
    }

    //echo "File contents:\n";
    //echo $fileContents;

    //$contacts = json_decode($fileContents, true);
    //print_r($contacts);

    // Preprocess the JSON response to make it an array of objects
    $strReplace = str_replace(array("}\n", "}\r"), "},\n", $fileContents);
    $strReplace = substr($strReplace, 0, -2);
    $jsonResponse = "[" . $strReplace . "]";
    
    // Decode the JSON response into an array of objects
    $data = json_decode($jsonResponse);

    if ($data !== null) {
        //Do all SQL code here...
        $mySQL = new db();

        foreach ($data as $item) {
            // Access the properties of each item and build your SQL insert statement
            $email = $item->email;
            $created_at = $item->created_at;
            
            $unique_id = $email.'|DEAD';

            // Build the SQL insert statement
            $mySQL->insert_sql("INSERT INTO sendgrid_tci_deadlist (`unique_id`, `email`, `created_at`, `segment_from_id`, `segment_from_name`) VALUES ('{$unique_id}','{$email}', '{$created_at}', 'e336cc0d-b58f-460c-b8c5-bd76dfe5babd', 'DEAD')");
        }

    } else {
        echo "Error decoding JSON response";
    }

} else {
    echo "Error: Unable to read the file from the URL.";
}

file_put_contents('/home/dh_uey5n8/imscrm.com/jonnydebugfile.txt', "\n(".date('H:i:s').") ". basename(__FILE__).':'.__LINE__." || Sendgrid Dead List End", 8);
?>