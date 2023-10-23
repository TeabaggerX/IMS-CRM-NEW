<?php

include("/home/dh_uey5n8/imscrm.com/lib/connect.php");

// API endpoint URL
$baseURL = "https://api.eflow.team/v1/networks/creativestable";

// Request headers
$headers = array(
    "X-Eflow-API-Key: c9ce04CoQieR8hAg3tyDPw",
    "Content-Type: application/json"
);

// Set the initial page and page size
$page = 1;
$pageSize = 500; // You can change this value as needed

// Initialize an array to store all the data
$allData = array();

// Request payload for filtering
$payload = json_encode([
    "search_terms" => [
        [
            "search_type" => "creative_status",
            "value" => "active"
        ]
    ]
]);

//init sql connection
$mySQL = new db();

// Continue making requests until all data is retrieved
do {
    // Build the URL with the pagination parameters
    $urlWithPagination = "$baseURL?page=$page&page_size=$pageSize";

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

    // Extract and append the data to the array
    if (isset($responseData['creatives'])) {
        $allData = array_merge($allData, $responseData['creatives']);
    }

    // Increment the page for the next request
    $page++;

} while (!empty($responseData['creatives']));

// Process and use the collected data (in $allData) as needed
foreach ($allData as $creative) {
    // Do something with each creative entry
    $network_offer_creative_id = $creative['network_offer_creative_id'];
    $name = addslashes($creative['name']);
    $network_offer_id = $creative['network_offer_id'];
    $network_offer_name = addslashes($creative['network_offer_name']);
    $creative_type = $creative['creative_type'];
    $is_private = $creative['is_private'];
    $creative_status = $creative['creative_status'];
    $html_code = addslashes($creative['html_code']);

    $mySQL->insert_sql("
        INSERT INTO everflowCreative (
            `offer_creative_id`,
            `name`,
            `offer_id`,
            `offer_name`,
            `creative_type`,
            `is_private`,
            `creative_status`,
            `html_code`
        ) VALUES (
            '{$network_offer_creative_id}',
            '{$name}',
            '{$network_offer_id}',
            '{$network_offer_name}',
            '{$creative_type}',
            '{$is_private}',
            '{$creative_status}',
            '{$html_code}'
        ) ON DUPLICATE KEY UPDATE
            `name` = '{$name}',
            `creative_type` = '{$creative_type}',
            `is_private` = '{$is_private}',
            `creative_status` = '{$creative_status}',
            `html_code` = '{$html_code}'
    ");

}
