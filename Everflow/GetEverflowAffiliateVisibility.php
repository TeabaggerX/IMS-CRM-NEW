<?php

file_put_contents('/home/dh_uey5n8/imscrm.com/jonnydebugfile.txt', "\n(".date('H:i:s').") ". basename(__FILE__).':'.__LINE__." || Start");

include("/home/dh_uey5n8/imscrm.com/lib/connect.php");
//init sql connection
$mySQL = new db();

$mySQL->insert_sql("UPDATE everflowAffiliateVisibility SET `del` = 1");

$mySQL = new db();

ini_set('memory_limit', '4096M');

// API endpoint URL
$baseURL = "https://api.eflow.team/v1/networks/affiliatestable";

// Request headers
$headers = array(
    "X-Eflow-API-Key: c9ce04CoQieR8hAg3tyDPw",
    "Content-Type: application/json"
);

//Set the del to 0 for new entries
$del = 0;

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

file_put_contents('/home/dh_uey5n8/imscrm.com/jonnydebugfile.txt', "\n(".date('H:i:s').") ". basename(__FILE__).':'.__LINE__." || Before Loop", 8);

// Process and use the collected data (in $allData) as needed
foreach ($allData as $affiliate) {
    
    $AffiliateID = $affiliate['network_affiliate_id'];
file_put_contents('/home/dh_uey5n8/imscrm.com/jonnydebugfile.txt', "\n(".date('H:i:s').") ". basename(__FILE__).':'.__LINE__." || AffiliateID: $AffiliateID", 8);

    // Set initial pagination parameters
    $page = 1;
    $pageSize = 50; // You can adjust the page size as needed

    // Initialize an array to collect the data for the current affiliate
    //$affiliateData = array();

    // Continue making requests until all data is retrieved
    do {

        // Build the URL with the pagination parameters
        $urlWithPagination = "https://api.eflow.team/v1/networks/affiliates/$AffiliateID/offerstable?page=$page";

        // Request headers
        $headers = array(
            "X-Eflow-API-Key: c9ce04CoQieR8hAg3tyDPw",
            "Content-Type: application/json"
        );

        // Request payload for filtering
        $payload = json_encode([
            "filters" => [
                "offer_status" => "active",
                "affiliate_runnable_status" => "runnable"
            ]
        ]);

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

        foreach ($responseData['offers'] as $offer) {
            //$allData2[] = $offer;
            $network_offer_id = $offer['network_offer_id'];
            $unique = $AffiliateID.$network_offer_id;

            $mySQL->insert_sql("
                INSERT INTO everflowAffiliateVisibility (
                `unique_key`,
                `offer_id`,
                `affiliate_id`,
                `del`
                ) VALUES (
                '{$unique}',
                '{$network_offer_id}',
                '{$AffiliateID}',
                '{$del}'
                ) ON DUPLICATE KEY UPDATE
                `del` = '{$del}'
                "
            );
        }

        // Increment the page for the next request
        $page++;

    } while (!empty($responseData['offers']));
    
}

$mySQL->insert_sql("DELETE FROM everflowAffiliateVisibility WHERE del = 1");
?>