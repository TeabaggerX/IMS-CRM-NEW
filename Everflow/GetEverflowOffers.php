<?php
file_put_contents('/home/dh_uey5n8/imscrm.com/jonnydebugfile.txt', "\n(".date('H:i:s').") ". basename(__FILE__).':'.__LINE__." || Everflow Offer Start", 8);
include("/home/dh_uey5n8/imscrm.com/lib/connect.php");
include '/home/dh_uey5n8/imscrm.com/lib/autoloader.php';
include '/home/dh_uey5n8/imscrm.com/Everflow/functions.php';
/*
$mySQL = new db();

$mySQL->insert_sql("UPDATE everflowOffer SET `del` = 1");

$mySQL = new db();
*/

$query = everflowOffer::startQuery()->where("id", ">", 0);
everflowOffer::updateWithConditions($query, array("del" => 1));

$del = 0;

// API endpoint URL
$baseURL = "https://api.eflow.team/v1/networks/offerstable";

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
        "offer_status" => "active"
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

    // Extract and append the "offers" data to the array
    if (isset($responseData['offers'])) {
        $allData = array_merge($allData, $responseData['offers']);
    }

    // Increment the page for the next request
    $page++;

} while (!empty($responseData['offers']));
$network_offer_id_array = '';
// Process and use the collected data (in $allData) as needed
foreach ($allData as $offer) {
    $payload = json_encode([
        "filters" => [
            "offer_status" => "active"
        ]
    ]);
    $offers = eflowAPI('networks/offers/'.$offer['network_offer_id'], 'get', $payload);
    
//file_put_contents('/home/dh_uey5n8/imscrm.com/jonnydebugfile.txt', "\n(".date('H:i:s').") ". basename(__FILE__).':'.__LINE__." || Offer: ".print_r($offers,true), 8);

    //$channel_id = $offers['relationship']['channels']['entries'][0]['network_channel_id'];
    $channel_id = $offers['relationship']['channels']['entries'][0]['network_channel_id'] ?? null;

//file_put_contents('/home/dh_uey5n8/imscrm.com/jonnydebugfile.txt', "\n(".date('H:i:s').") ". basename(__FILE__).':'.__LINE__." || channel_id: $channel_id ", 8);

    $network_offer_id  = $offer['network_offer_id'];
    $network_offer_id_array .= $offer['network_offer_id'].',';
    $network_advertiser_id = $offer['network_advertiser_id'];
    $name = addslashes($offer['name']);
    $offer_status = $offer['offer_status'];
    $visibility = $offer['visibility'];
    $network_advertiser_name = addslashes($offer['network_advertiser_name']);
    $category = $offer['category'];
    $channels = json_encode($offer['channels']);
    $payout_type = $offer['payout_type'];
    $revenue_type = $offer['revenue_type'];
    
    $network_offer_id_array .= $offer['network_offer_id'].',';

    $the_offer = everflowOffer::getOneWhere(['offer_id' => $offer['network_offer_id']]);

    if(empty($the_offer->id)){
        $the_offer = new everflowOffer();
    }

    $the_offer->offer_id  = $offer['network_offer_id'];
    $the_offer->advertiser_id = $offer['network_advertiser_id'];
    $the_offer->name = addslashes($offer['name']);
    $the_offer->offer_status = $offer['offer_status'];
    $the_offer->visibility = $offer['visibility'];
    $the_offer->advertiser_name = addslashes($offer['network_advertiser_name']);
    $the_offer->category = $offer['category'];
    $the_offer->channels = json_encode($offer['channels']);
    $the_offer->channel_id = $channel_id;
    $the_offer->payout_type = $offer['payout_type'];
    $the_offer->revenue_type = $offer['revenue_type'];
    $the_offer->offer_id_encoded = 0;
    $the_offer->del = $del;
    $the_offer->save();
    
}

// $mySQL->insert_sql("DELETE FROM everflowOffer WHERE del = 1");
    $network_offer_id_array = rtrim($network_offer_id_array, ",");

    $baseURL = "https://api.eflow.team/v1/networks/encode";
    // Request headers
    $headers = array(
        "X-Eflow-API-Key: c9ce04CoQieR8hAg3tyDPw",
        "Content-Type: application/json"
    );
    // Initialize cURL session
    $ch = curl_init($baseURL);

    $payload = '{
        "type": "tracking_link_offer",
        "ids": ['.$network_offer_id_array.']
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

    foreach ($responseData['values'] as $offerInfo) {
        $theOffer = everflowOffer::getOneWhere(['offer_id' => $offerInfo['decoded']]);
        if($theOffer->id != ''){
            $theOffer->offer_id_encoded = $offerInfo['encoded'];
        }
        $theOffer->save();
    }

    $query = everflowOffer::startQuery()->where("del", "=", 1);
    everflowOffer::deleteWithConditions($query, array("del" => 1));

file_put_contents('/home/dh_uey5n8/imscrm.com/jonnydebugfile.txt', "\n(".date('H:i:s').") ". basename(__FILE__).':'.__LINE__." || Everflow Offer End", 8);
?>