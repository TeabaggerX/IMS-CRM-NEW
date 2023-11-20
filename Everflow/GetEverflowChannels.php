<?php
include("/home/dh_uey5n8/imscrm.com/lib/connect.php");
include '/home/dh_uey5n8/imscrm.com/lib/autoloader.php';
include '/home/dh_uey5n8/imscrm.com/Everflow/functions.php';

$mySQL = new db();

$mySQL->insert_sql("UPDATE everflow_channels SET `del` = 1");

$mySQL = new db();

$del = 0;

// API endpoint URL
$baseURL = "https://api.eflow.team/v1/networks/channels";

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
// $payload = json_encode([
//     "filters" => [
//         "status" => "active"
//     ]
// ]);

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

    // Extract and append the "channels" data to the array
    if (isset($responseData['channels'])) {
        $allData = array_merge($allData, $responseData['channels']);
    }

    // Increment the page for the next request
    $page++;

} while (!empty($responseData['channels']));

// Process and use the collected data (in $allData) as needed
foreach ($allData as $channelData) {

    $tableChannelEntry = everflowChannel::getOneWhere(['network_channel_id' => $channelData['network_channel_id']]);

    if(empty($tableChannelEntry->id)){
        $tableChannelEntry = new everflowChannel();
    }

    $tableChannelEntry->network_channel_id = $channelData['network_channel_id'];
    $tableChannelEntry->network_id = $channelData['network_id'];
    $tableChannelEntry->name = $channelData['name'];
    $tableChannelEntry->status = $channelData['status'];
    $tableChannelEntry->save();
}

?>
