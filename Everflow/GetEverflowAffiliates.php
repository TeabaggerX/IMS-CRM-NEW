<?php

file_put_contents('/home/dh_uey5n8/imscrm.com/jonnydebugfile.txt', "\n(".date('H:i:s').") ". basename(__FILE__).':'.__LINE__." || Affiliates Start", 8);

include("/home/dh_uey5n8/imscrm.com/lib/connect.php");
include '/home/dh_uey5n8/imscrm.com/lib/autoloader.php';
include '/home/dh_uey5n8/imscrm.com/Everflow/functions.php';

//init sql connection
$mySQL = new db();

//Set del = 1 on all items. When we update and add new items, we set del to 0. At the end we delete all where del = 1 as it should no longer exist.
$mySQL->insert_sql("UPDATE everflowAffiliate SET `del` = 1");

$mySQL = new db();

//Set the del to 0 for new entries
$del = 0;

// API endpoint URL
$baseURL = "https://api.eflow.team/v1/networks/affiliatestable";

// Request headers
$headers = array(
    "X-Eflow-API-Key: c9ce04CoQieR8hAg3tyDPw",
    "Content-Type: application/json"
);

// Set the initial page and page size
$page = 1;
$pageSize = 50;

// Initialize an array to store all the data
$allData = array();

// Request payload for filtering
$payload = json_encode([
    "filters" => [
        "account_status" => "active"
    ]
]);

//init sql connection
$mySQL = new db();

// Continue making requests until all data is retrieved
do {
    // Build the URL with the pagination parameters
    $urlWithPagination = "$baseURL?page=$page";

    // Initialize cURL session
    $ch = curl_init($urlWithPagination);

    // Set cURL options
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Return the response as a string
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); // Set request headers
    curl_setopt($ch, CURLOPT_POST, 1); // Set as a POST request
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload); // Add the payload

    // Execute the cURL request
    $response = curl_exec($ch);

    // Check for cURL errors
    if (curl_errno($ch)) {
        echo 'Error: ' . curl_error($ch);
        break;
    }

    // Close cURL session
    curl_close($ch);

    // Decode the JSON response
    $responseData = json_decode($response, true);

    // Check if the decoding was successful
    if ($responseData === null) {
        echo 'Error decoding JSON response';
        break;
    }

    // Extract and append the "affiliates" data to the array
    if (isset($responseData['affiliates'])) {
        $allData = array_merge($allData, $responseData['affiliates']);
    }

    // Increment the page for the next request
    $page++;

} while (!empty($responseData['affiliates']));
$affiliate_ids = '';
// Process and use the collected data (in $allData) as needed
foreach ($allData as $affiliate) {
    
    $affiliate_id = $affiliate['network_affiliate_id'];
    $name = addslashes($affiliate['name']); // Manually escape the name
    $accountStatus = $affiliate['account_status'];
    $affiliate_ids .= $affiliate_id.',';

    /*$mySQL->insert_sql("INSERT INTO everflowAffiliate (`AffiliateID`, `Name`, `AccountStatus`) VALUES ('{$AffiliateID}', '{$Name}', '{$AccountStatus}')");*/
    $mySQL->insert_sql("
        INSERT INTO everflowAffiliate (
            `affiliate_id`,
            `name`,
            `accountStatus`,
            `del`
        ) VALUES (
            '{$affiliate_id}',
            '{$name}',
            '{$accountStatus}',
            '{$del}'
        ) ON DUPLICATE KEY UPDATE
            `name` = '{$name}',
            `accountStatus` = '{$accountStatus}',
            `del` = '{$del}'
    ");
    
    file_put_contents('/home/dh_uey5n8/imscrm.com/jonnydebugfile.txt', "\n(".date('H:i:s').") ". basename(__FILE__).':'.__LINE__." || Affiliate ID: $affiliate_id", 8);
    file_put_contents('/home/dh_uey5n8/imscrm.com/jonnydebugfile.txt', "\n(".date('H:i:s').") ". basename(__FILE__).':'.__LINE__." || Name: $name", 8);
    file_put_contents('/home/dh_uey5n8/imscrm.com/jonnydebugfile.txt', "\n(".date('H:i:s').") ". basename(__FILE__).':'.__LINE__." || Account Status: $accountStatus", 8);
}

// $mySQL->insert_sql("DELETE FROM everflowOffer WHERE del = 1");
$affiliate_ids = rtrim($affiliate_ids, ",");

$baseURL = "https://api.eflow.team/v1/networks/encode";
// Request headers
$headers = array(
    "X-Eflow-API-Key: c9ce04CoQieR8hAg3tyDPw",
    "Content-Type: application/json"
);
// Initialize cURL session
$ch = curl_init($baseURL);

$payload = '{
    "type": "tracking_link_affiliate",
    "ids": ['.$affiliate_ids.']
}';

// Set cURL options
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Return the response as a string
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); // Set request headers
curl_setopt($ch, CURLOPT_POST, 1); // Set as a POST request
curl_setopt($ch, CURLOPT_POSTFIELDS, $payload); // Add the payload

$response = curl_exec($ch);

// Check for cURL errors
if (curl_errno($ch)) {
    echo 'Error: ' . curl_error($ch);
   die();
}

// Close cURL session
curl_close($ch);

// Decode the JSON response
$responseData = json_decode($response, true);

// Check if the decoding was successful
if ($responseData === null) {
    echo 'Error decoding JSON response';
   die();
}

foreach ($responseData['values'] as $affiliateInfo) {
    $theAffiliate = everflowAffiliate::getOneWhere(['affiliate_id' => $affiliateInfo['decoded']]);
    if($theAffiliate->id != ''){
        $theAffiliate->affiliate_id_encoded = $affiliateInfo['encoded'];
        $theAffiliate->save();
    }
    file_put_contents(ROOT.'/debugfile.txt', "\n(".date('H:i:s').") ". basename(__FILE__).':'.__LINE__." || ".print_r($theAffiliate,true), 8);
}

$mySQL->insert_sql("DELETE FROM everflowAffiliate WHERE del = 1");

file_put_contents('/home/dh_uey5n8/imscrm.com/jonnydebugfile.txt', "\n(".date('H:i:s').") ". basename(__FILE__).':'.__LINE__." || Affiliates End", 8);

?>