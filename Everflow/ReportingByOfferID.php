<?php
file_put_contents('/home/dh_uey5n8/imscrm.com/jonnydebugfile.txt', "\n(".date('H:i:s').") ". basename(__FILE__).':'.__LINE__." || Everflow Reporting Start", 8);

ini_set('memory_limit', '4096M');
set_time_limit(0); // Removes the time limit
ini_set('max_execution_time', 600); // Set to 10 minutes or longer
ini_set('default_socket_timeout', 600);
// Set the time zone to EST (Eastern Time Zone)
date_default_timezone_set('America/New_York');

//New SQL DBOBJECT file...
include("/home/dh_uey5n8/imscrm.com/lib/autoloader.php");

// Calculate yesterday's date
$yesterday = date('Y-m-d', strtotime('yesterday'));

//Todays Date
$today = date('Y-m-d');

//New SQL Query format...
//Ignoring offer ID 46 because that is the fail traffic offer and it causes issues
$var = everflowOffer::getAllWhere("offer_id", "!=", "46", "offer_id ASC",);

//$mySQL = new db();

// Your API key and endpoint
$api_key = 'c9ce04CoQieR8hAg3tyDPw';
$api_endpoint = 'https://api.eflow.team/v1/networks/reporting/entity/table';

$mh = curl_multi_init(); // Initialize cURL multi handler
$handles = []; // Array to store cURL handles
$limit = 9; // Set the limit to 10 concurrent requests

// Process responses and append them to the $responses array
$responses = [];

$new_filters = '';
foreach ($var as $offer) {
    $new_filters .= "[
        'filter_id_value' => ".strval($offer_id).",
        'resource_type' => 'offer'
    ],";
}

foreach ($var as $offer) {
    //$offer_id = $offer['offer_id'];

    //New way to do offer
    $offer_id = $offer->offer_id;
    $data = []; //Reset the data array

    file_put_contents('/home/dh_uey5n8/imscrm.com/jonnydebugfile.txt', "\n(".date('H:i:s').") ". basename(__FILE__).':'.__LINE__." || Offer ID: $offer_id", 8);
        
    // Prepare the payload data
    $data = [
        /*'from' => '2023-10-06',
        'to' => '2023-10-16',
        */
        'from' => $today,
        'to' => $today,
        'timezone_id' => 80,
        'currency_id' => 'USD',
        'columns' => [
            [
                'column' => 'date'
            ],
            [
                'column' => 'offer'
            ],
            [
                'column' => 'offer_url'
            ],
            [
                'column' => 'affiliate'
            ]
        ],
        'query' => [
            'filters' => [
                [
                    'filter_id_value' => strval($offer_id),
                    'resource_type' => 'offer'
                ]
            ]
        ]
    ];

    // Convert the payload data to JSON format
    $json_data = json_encode($data);

    // Set up the headers
    $headers = [
        'X-Eflow-API-Key: ' . $api_key,
        'Content-Type: application/json',
    ];

    // Initialize cURL session
    $ch = curl_init($api_endpoint);

    // Set cURL options
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

     $handles[] = $ch;

    // Check if the limit is reached, execute requests and collect responses
    if (count($handles) >= $limit) {
        // Execute the existing requests when the limit is reached
        foreach ($handles as $handle) {
            curl_multi_add_handle($mh, $handle);
        }
        $active = null;
        do {
            curl_multi_exec($mh, $active);
        } while ($active > 0);

        // Collect responses and remove handles
        foreach ($handles as $handle) {
            $response = curl_multi_getcontent($handle);
            $responses[] = $response;
            curl_multi_remove_handle($mh, $handle);
            curl_close($handle);
        }

        // Reset the handles array
        $handles = [];
    }
}

// Ensure any remaining requests are executed
foreach ($handles as $handle) {
    curl_multi_add_handle($mh, $handle);
}

$active = null;
do {
    curl_multi_exec($mh, $active);
} while ($active > 0);

// Collect responses for the remaining requests
foreach ($handles as $handle) {
    $response = curl_multi_getcontent($handle);
    $responses[] = $response;
    curl_multi_remove_handle($mh, $handle);
    curl_close($handle);
}

curl_multi_close($mh);

// Now, $responses contains the responses for each Offer ID

foreach ($responses as $response) {

    $data = json_decode($response, true); // Assuming the responses are JSON data
    //file_put_contents('/home/dh_uey5n8/imscrm.com/jonnydebugfile.txt', "\n(".date('H:i:s').") ". basename(__FILE__).':'.__LINE__." || Data: ".print_r($data,true), 8);
    if (isset($data['table']) && is_array($data['table'])) {
        foreach ($data['table'] as $item) {
            // Do something with each $item
            $columns = $item['columns'];
            $reporting = $item['reporting'];

            // Create an associative array to store column_type and label pairs
            $columnLabels = array();

            foreach ($columns as $column) {
                $columnType = $column['column_type'];
                $columnId = $column['id'];
                $columnLabel = $column['label'];

                $columnData[$columnType] = [
                    'label' => $columnLabel,
                    'id' => $columnId,
                ];
            }

            // Now you can access the labels using variables
            $reporting_date_epoch = $columnData['date']['id'];
            $timestamp = $columnData['date']['id'];
            $reporting_date_epoch = $timestamp;
            $date = date("Y-m-d", $timestamp); // Convert the new timestamp to a date
            
            $currentTimestamp = date("Y-m-d H:i:s");

            $offer_id = $columnData['offer']['id'];
            $advertiser_id = $columnData['advertiser']['id'];
            $offer_url = $columnData['offer_url']['label'];
            $offer_url_id = $columnData['offer_url']['id'];
            $affiliate_id = $columnData['affiliate']['id'];
            $imp = $reporting['imp'];
            $totalClick = $reporting['total_click'];
            $uniqueClick = $reporting['unique_click'];
            $invalidClick = $reporting['invalid_click'];
            $duplicateClick = $reporting['duplicate_click'];
            $grossClick = $reporting['gross_click'];
            $ctr = $reporting['ctr'];
            $cv = $reporting['cv'];
            $invalidCvScrub = $reporting['invalid_cv_scrub'];
            $viewThroughCv = $reporting['view_through_cv'];
            $totalCv = $reporting['total_cv'];
            $event = $reporting['event'];
            $cvr = $reporting['cvr'];
            $evr = $reporting['evr'];
            $cpc = $reporting['cpc'];
            $cpm = $reporting['cpm'];
            $cpa = $reporting['cpa'];
            $epc = $reporting['epc'];
            $rpc = $reporting['rpc'];
            $rpa = $reporting['rpa'];
            $rpm = $reporting['rpm'];
            $payout = $reporting['payout'];
            $revenue = $reporting['revenue'];
            $eventRevenue = $reporting['event_revenue'];
            $grossSales = $reporting['gross_sales'];
            $profit = $reporting['profit'];
            $margin = $reporting['margin'];
            $roas = $reporting['roas'];
            $avgSaleValue = $reporting['avg_sale_value'];

            $date_for_alternate_id = date("Ymd", strtotime($date)); // Convert to "YYYYMMDD" format
            $alternate_id = $date_for_alternate_id . $offer_id . $offer_url_id . $affiliate_id;

            file_put_contents('/home/dh_uey5n8/imscrm.com/jonnydebugfile.txt', "\n(".date('H:i:s').") ". basename(__FILE__).':'.__LINE__." || Date: $date <-----------------STARTS HERE", 8);
            file_put_contents('/home/dh_uey5n8/imscrm.com/jonnydebugfile.txt', "\n(".date('H:i:s').") ". basename(__FILE__).':'.__LINE__." || offer_id: $offer_id", 8);
            file_put_contents('/home/dh_uey5n8/imscrm.com/jonnydebugfile.txt', "\n(".date('H:i:s').") ". basename(__FILE__).':'.__LINE__." || advertiser_id: $advertiser_id", 8);
            file_put_contents('/home/dh_uey5n8/imscrm.com/jonnydebugfile.txt', "\n(".date('H:i:s').") ". basename(__FILE__).':'.__LINE__." || offer_url: $offer_url", 8);
            file_put_contents('/home/dh_uey5n8/imscrm.com/jonnydebugfile.txt', "\n(".date('H:i:s').") ". basename(__FILE__).':'.__LINE__." || offer_url_id: $offer_url_id", 8);
            file_put_contents('/home/dh_uey5n8/imscrm.com/jonnydebugfile.txt', "\n(".date('H:i:s').") ". basename(__FILE__).':'.__LINE__." || affiliate_id: $affiliate_id", 8);
            file_put_contents('/home/dh_uey5n8/imscrm.com/jonnydebugfile.txt', "\n(".date('H:i:s').") ". basename(__FILE__).':'.__LINE__." || alternate_id: $alternate_id", 8);
            //file_put_contents('/home/dh_uey5n8/imscrm.com/jonnydebugfile.txt', "\n(".date('H:i:s').") ". basename(__FILE__).':'.__LINE__." || Reporting: ".print_r($reporting,true), 8);
            
            //New SQL Insert Statement
            //Pull in data to see if there is a match on alternate id
            $row = everflowReporting::getOneWhere(['alternate_id' => $alternate_id]);
            //if there is no match, make a new row
            if($row === null){
                $row = new everflowReporting();
                $row->alternate_id = $alternate_id;
                $row->reporting_date = $date;
                $row->reporting_date_epoch = $reporting_date_epoch;
                $row->offer_id = $offer_id;
                $row->advertiser_id = $advertiser_id;
                $row->offer_url = $offer_url;
                $row->offer_url_id = $offer_url_id;
                $row->affiliate_id = $affiliate_id;
            }
            //The following runs no matter what
            $row->imp = $imp;
            $row->totalClick = $totalClick;
            $row->uniqueClick = $uniqueClick;
            $row->invalidClick = $invalidClick;
            $row->duplicateClick = $duplicateClick;
            $row->grossClick = $grossClick;
            $row->ctr = $ctr;
            $row->cv = $cv;
            $row->invalidCvScrub = $invalidCvScrub;
            $row->viewThroughCv = $viewThroughCv;
            $row->totalCv = $totalCv;
            $row->event = $event;
            $row->cvr = $cvr;
            $row->evr = $evr;
            $row->cpc = $cpc;
            $row->cpm = $cpm;
            $row->cpa = $cpa;
            $row->epc = $epc;
            $row->rpc = $rpc;
            $row->rpa = $rpa;
            $row->rpm = $rpm;
            $row->payout = $payout;
            $row->revenue = $revenue;
            $row->eventRevenue = $eventRevenue;
            $row->grossSales = $grossSales;
            $row->profit = $profit;
            $row->margin = $margin;
            $row->roas = $roas;
            $row->avgSaleValue = $avgSaleValue;
            $row->timestamp = $currentTimestamp;
            $row->save();

    }
}

    //file_put_contents('/home/dh_uey5n8/imscrm.com/jonnydebugfile.txt', "\n(".date('H:i:s').") ". basename(__FILE__).':'.__LINE__." || Result: ".print_r($row, true), 8);
    file_put_contents('/home/dh_uey5n8/imscrm.com/jonnydebugfile.txt', "\n(".date('H:i:s').") ". basename(__FILE__).':'.__LINE__." || AFTER SQL IMPORT", 8);
}

file_put_contents('/home/dh_uey5n8/imscrm.com/jonnydebugfile.txt', "\n(".date('H:i:s').") ". basename(__FILE__).':'.__LINE__." || Everflow Reporting End", 8);
?>
