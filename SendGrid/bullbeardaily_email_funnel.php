<?php
//New SQL DBOBJECT file...
include("/home/dh_uey5n8/imscrm.com/lib/autoloader.php");

file_put_contents('/home/dh_uey5n8/imscrm.com/jonnydebugfile.txt', "\n(".date('H:i:s').") ". basename(__FILE__).':'.__LINE__." || BBD Funnel Start", 8);

$randomNumber = rand(200, 300);

//SQL Query
$var = email_funnel_bullbeardaily::getAllWithLimitAndNotSent($randomNumber, 'id');

// Count the number of values in the $var array
//$numValues = count($var);
//file_put_contents('/home/dh_uey5n8/imscrm.com/jonnydebugfile.txt', "\n(".date('H:i:s').") ". basename(__FILE__).':'.__LINE__." || Number of Emails: $numValues", 8);

$value = 1;

$endpoint = "https://pro.ptrtrk.com/A7AEpY?email=";


foreach ($var as $row) {
    $email = $row->email;
    $id = $row->id;

    file_put_contents('/home/dh_uey5n8/imscrm.com/jonnydebugfile.txt', "\n(".date('H:i:s').") ". basename(__FILE__).':'.__LINE__." || Email: $email", 8);
    file_put_contents('/home/dh_uey5n8/imscrm.com/jonnydebugfile.txt', "\n(".date('H:i:s').") ". basename(__FILE__).':'.__LINE__." || id: $id", 8);

    // Construct the URL with the email parameter
    $url = $endpoint . urlencode($email);

    // Use cURL to make the HTTP request
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);


    if ($httpCode === 200) {
        // Request was successful
        // Process the response if needed
        // $response contains the response from the endpoint
        file_put_contents('/home/dh_uey5n8/imscrm.com/jonnydebugfile.txt', "\n(".date('H:i:s').") ". basename(__FILE__).':'.__LINE__." || response: $response", 8);
        file_put_contents('/home/dh_uey5n8/imscrm.com/jonnydebugfile.txt', "\n(".date('H:i:s').") ". basename(__FILE__).':'.__LINE__." || response: ".print_r($response, true), 8);
    
        if ($httpCode === 202) {
            // Request was accepted for processing
            // You can provide a specific success message
            file_put_contents('/home/dh_uey5n8/imscrm.com/jonnydebugfile.txt', "\n(".date('H:i:s').") ". basename(__FILE__).':'.__LINE__." || Request accepted for processing with HTTP status code " . $httpCode, 8);
        }
    } else {
        // Request failed, you can handle the error as needed
        // For example: echo "Request failed with HTTP status code " . curl_getinfo($ch, CURLINFO_HTTP_CODE);
        file_put_contents('/home/dh_uey5n8/imscrm.com/jonnydebugfile.txt', "\n(".date('H:i:s').") ". basename(__FILE__).':'.__LINE__." || Request failed with HTTP status code " . $httpCode, 8);
    }

    curl_close($ch);

    //Pull in data
    $row = email_funnel_bullbeardaily::getOneWhere(['id' => $id]);
    $row->sent = 1;
    $row->save();

}

file_put_contents('/home/dh_uey5n8/imscrm.com/jonnydebugfile.txt', "\n(".date('H:i:s').") ". basename(__FILE__).':'.__LINE__." || BBD Funnel End", 8);
?>