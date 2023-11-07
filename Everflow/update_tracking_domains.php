<?php
//New SQL DBOBJECT file...
include("/home/dh_uey5n8/imscrm.com/lib/autoloader.php");

file_put_contents('/home/dh_uey5n8/imscrm.com/jonnydebugfile.txt', "\n(".date('H:i:s').") ". basename(__FILE__).':'.__LINE__." || Affiliate Link Update Start", 8);

$api_url = 'https://api.eflow.team/v1/networks/affiliates/';
$api_key = 'c9ce04CoQieR8hAg3tyDPw';

//New SQL Query format...
//Ignoring offer ID 46 because that is the fail traffic offer and it causes issues
$var = everflow_update_tracking_domain_affilliates::getAllFromEverflowUpdateTrackingDomainAffiliates();
//$var = everflow_update_tracking_domain_affilliates::getAllFromEverflowUpdateTrackingDomainAffiliatesWhere1597();

//file_put_contents('/home/dh_uey5n8/imscrm.com/jonnydebugfile.txt', "\n(".date('H:i:s').") ". basename(__FILE__).':'.__LINE__." || Query Output: ".print_r($var, true), 8);
//file_put_contents('/home/dh_uey5n8/imscrm.com/jonnydebugfile.txt', "\n(".date('H:i:s').") ". basename(__FILE__).':'.__LINE__." || Query Count: ".count($var), 8);

foreach ($var as $affilliate) {
    
    $affilliate_id = (int) $affilliate->affilliate_id;
    $tier = $affilliate->tier;

    switch ($tier) {
        case 1:
            $network_tracking_domain_id = 8821;
            break;
        case 2:
            $network_tracking_domain_id = 8822;
            break;
        case 3:
            $network_tracking_domain_id = 8823;
            break;
    }

    $url = $api_url . $affilliate_id . '/trackingdomains';
    
    $data = json_encode([
        "network_affiliate_id" => $affilliate_id,
        "network_tracking_domain_id" => $network_tracking_domain_id,
        "is_apply_all_offers" => true
    ]);

    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Content-type: application/json",
        "X-Eflow-API-Key: $api_key"
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $result = curl_exec($ch);

    if (curl_errno($ch)) {
        // Handle cURL error
        echo 'cURL Error: ' . curl_error($ch);
    } else {
        // Handle the API response
        echo 'API Response: ' . $result;
    }

    curl_close($ch);

}

file_put_contents('/home/dh_uey5n8/imscrm.com/jonnydebugfile.txt', "\n(".date('H:i:s').") ". basename(__FILE__).':'.__LINE__." || Affiliate Link Update End", 8);
?>
