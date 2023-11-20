<?php
include("/home/dh_uey5n8/imscrm.com/lib/connect.php");
include '/home/dh_uey5n8/imscrm.com/lib/autoloader.php';
include '/home/dh_uey5n8/imscrm.com/Everflow/functions.php';

file_put_contents('/home/dh_uey5n8/imscrm.com/jonnydebugfile.txt', "\n(".date('H:i:s').") ". basename(__FILE__).':'.__LINE__." || Everflow Channels Start", 8);

$query = everflowChannel::startQuery()->where("id", ">", 0);
everflowChannel::updateWithConditions($query, array("del" => 1));

$del = 0;

// API endpoint URL
$baseURL = "https://api.eflow.team/v1/networks/channels";

// Request headers
$headers = array(
    "X-Eflow-API-Key: c9ce04CoQieR8hAg3tyDPw",
    "Content-Type: application/json"
);

// Initialize cURL session
$ch = curl_init($baseURL);

// Set cURL options
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Return the response as a string
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); // Set request headers

// Execute the cURL request
$response = curl_exec($ch);

// Check for cURL errors
if (curl_errno($ch)) {
    echo 'Error: ' . curl_error($ch);
}

// Close cURL session
curl_close($ch);

// Decode the JSON response
$responseData = json_decode($response, true);

// Check if the decoding was successful
if ($responseData === null) {
    echo 'Error decoding JSON response';
}


// Process and use the collected data (in $allData) as needed
foreach ($responseData['channels'] as $channelData) {

    $tableChannelEntry = everflowChannel::getOneWhere(['network_channel_id' => $channelData['network_channel_id']]);

    if(empty($tableChannelEntry->id)){
        $tableChannelEntry = new everflowChannel();
    }

    $tableChannelEntry->network_channel_id = $channelData['network_channel_id'];
    $tableChannelEntry->network_id = $channelData['network_id'];
    $tableChannelEntry->name = $channelData['name'];
    $tableChannelEntry->status = $channelData['status'];
    $tableChannelEntry->del = $del;
    $tableChannelEntry->save();
}

$query = everflowChannel::startQuery()->where("del", "=", 1);
everflowChannel::deleteWithConditions($query, array("del" => 1));

file_put_contents('/home/dh_uey5n8/imscrm.com/jonnydebugfile.txt', "\n(".date('H:i:s').") ". basename(__FILE__).':'.__LINE__." || Everflow Channels End", 8);
?>
