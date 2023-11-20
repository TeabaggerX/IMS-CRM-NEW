<?php
include '/home/dh_uey5n8/imscrm.com/lib/autoloader.php';
include '/home/dh_uey5n8/imscrm.com/Everflow/functions.php';


$allData = everflowAffiliate::getWhere(['del' => 0]);
$query = everflowAffiliateVisibility::startQuery()->where("id", ">", 0);
everflowAffiliateVisibility::updateWithConditions($query, array("del" => 1));


// Process and use the collected data (in $allData) as needed
foreach ($allData as $affiliate) {
    
    $affiliate_id = $affiliate->affiliate_id;

    $page = 1;
    $pageSize = 500; // You can adjust the page size as needed

    do {

        // Build the URL with the pagination parameters
        $baseURL = "networks/affiliates/$affiliate_id/offerstable?page=$page&page_size=2000";

        $payload = json_encode([
            "filters" => [
                "offer_status" => "active",
                "affiliate_runnable_status" => "runnable"
            ]
        ]);

        $responseData = eflowAPI($baseURL, 'post', $payload);
        // Check if the decoding was successful
        if ($responseData === null) {
            echo 'Error decoding JSON response';
            break;
        }

        foreach ($responseData['offers'] as $offer) {
            $network_offer_id = $offer['network_offer_id'];
            $unique_key = $affiliate_id.$network_offer_id;

            $evAV = everflowAffiliateVisibility::getOneWhere(['unique_key' => $unique_key]);

            if(empty($evAV)){
                $evAV = new everflowAffiliateVisibility();
                $evAV->unique_key = $unique_key;
            }
        
            $evAV->offer_id = $network_offer_id;
            $evAV->affiliate_id = $affiliate_id;
            $evAV->del = 0;
            $evAV->timestamp = date("Y-m-d h:i:s");
            $evAV->save();
        }

        $page++;

    } while (!empty($responseData['offers']));
    
}

$query = everflowAffiliateVisibility::startQuery()->where("del", "=", 1);
everflowAffiliateVisibility::deleteWithConditions($query, array("del" => 1));
?> 