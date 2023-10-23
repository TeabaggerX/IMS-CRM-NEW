<?php
file_put_contents('/home/dh_uey5n8/imscrm.com/jonnydebugfile.txt', "\n(".date('H:i:s').") ". basename(__FILE__).':'.__LINE__." || MarketBeat Test Start", 8);

// List of email addresses to send emails to
$emails = [
    'yoxomo9690@wermink.com',
    'boxipaw158@wisnick.com',
    'yehah83709@wermink.com',
    'yitig39631@wisnick.com',
    'xisoy54764@wermink.com'
];

// API endpoint
//$endpoint = 'https://pro.imstrk01.com/43ISQI?email=';
$endpoint = "https://www.americanconsumernews.net/scripts/MobileEmail.ashx?Name=&Source=StockReportCom&Email=";

foreach ($emails as $email) {
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
    
        if ($httpCode === 202) {
            // Request was accepted for processing
            // You can provide a specific success message
            file_put_contents('/home/dh_uey5n8/imscrm.com/jonnydebugfile.txt', "\n(".date('H:i:s').") ". basename(__FILE__).':'.__LINE__." || Request accepted for processing with HTTP status code " . $httpCode, 8);
        }
    } else {
        // Request failed, you can handle the error as needed
        // For example: echo "Request failed with HTTP status code " . curl_getinfo($ch, CURLINFO_HTTP_CODE);
        echo "Request failed with HTTP status code " . curl_getinfo($ch, CURLINFO_HTTP_CODE);
        file_put_contents('/home/dh_uey5n8/imscrm.com/jonnydebugfile.txt', "\n(".date('H:i:s').") ". basename(__FILE__).':'.__LINE__." || Request failed with HTTP status code " . $httpCode, 8);
    }

    curl_close($ch);
}
file_put_contents('/home/dh_uey5n8/imscrm.com/jonnydebugfile.txt', "\n(".date('H:i:s').") ". basename(__FILE__).':'.__LINE__." || MarketBeat Test End", 8);
?>
