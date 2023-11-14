<?php
file_put_contents('/home/dh_uey5n8/imscrm.com/jonnydebugfile.txt', "\n(".date('H:i:s').") ". basename(__FILE__).':'.__LINE__." || Google API Start", 8);
/*

To run file by date range, ctrl+f "to run this code by date range" and follow instructions. Should need to uncomment 3 places...

LAST REFRESH TOKEN DATE: 9/20/2023

The refresh token (refresh_token) in this script needs to be manually refreshed every 6 months or so. To refresh it, follow this video: https://www.youtube.com/watch?v=t0RKgHskYwI
Basically, you need to start by getting your authorization code. Hit this URL manually: https://accounts.google.com/o/oauth2/auth?client_id=456106292397-h48o8b1mksukloojco9j0bh8ofson7g4.apps.googleusercontent.com&redirect_uri=http://localhost&response_type=code&scope=https://www.googleapis.com/auth/postmaster.readonly&access_type=offline

It produces a URL that contains a code that looks like this: 4/0AfJohXnrSTP9k7UHHjoSelky-tlZVsRiyWwY5EfC_uoKHen0l8bOeoyVn0Bv1aUpgaM1ow
This is your Auth Code. Now you can use the Auth code to generate a 6 month refresh_token.
Not to confuse Refresh Token with Access Token, which needs to be refreshed every single time you run the API.
So its 3 parts here: Auth Code -> Refresh Token -> Access Token.

Anyways, now that you have the auth code, you need to make a PHP API and view the results. In the JSON results, there is a Refresh Token. Here is how to run the API in PHP:

        // Define the POST data as an associative array
        $postData = array(
Auth Code ->'code' => '4/0AfJohXnSUipsuaB1Yq6U31v8AqQ11asE91A1eB5PTNHzB3csPGj4X_fF49d2tK3SVcZQzA',         <-----AUTH CODE HERE!!! **** AUTH CODE HERE!!!!
            'client_id' => '456106292397-h48o8b1mksukloojco9j0bh8ofson7g4.apps.googleusercontent.com',
            'client_secret' => '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',
            'redirect_uri' => 'http://localhost',
            'grant_type' => 'authorization_code'
        );

        // Initialize cURL session
        $ch = curl_init('https://oauth2.googleapis.com/token');

        // Set cURL options
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Return the response as a string
        curl_setopt($ch, CURLOPT_POST, true); // Set the request method to POST
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData)); // Set the POST data

        // Execute the cURL request
        $response = curl_exec($ch);

        // Check for errors
        if ($response === false) {
            echo 'cURL Error: ' . curl_error($ch);
        } else {
            // Print the response
            echo $response;
        }

        // Close cURL session
        curl_close($ch);

Again, in the $response above you will find your new refresh token. It looks something like 1//06821z6481swGCgYIARAAGAYSNwF-L9Ir2h0rERev-peakw9Ab3T4lMnGm850Z_8swyBEaF-nfllCKqPJ6QtzcUXIXsOopLYEuYk. Remember, on line 15 above, you use the Auth Code you generated.
This will only work ONE TIME. Then you need to get a new auth code.
Now you should be good for the next 6 months.
*/



include("/home/dh_uey5n8/imscrm.com/lib/connect.php");

// API endpoint URL
$apiUrl = 'https://oauth2.googleapis.com/token';

// Define the POST data as an associative array
$postData = array(
    'client_id' => '456106292397-h48o8b1mksukloojco9j0bh8ofson7g4.apps.googleusercontent.com',
    'client_secret' => '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',
    'refresh_token' => '1//06821z6481swGCgYIARAAGAYSNwF-L9Ir2h0rERev-peakw9Ab3T4lMnGm850Z_8swyBEaF-nfllCKqPJ6QtzcUXIXsOopLYEuYk', //GOOD FOR 6 MONTHS ONLY. LAST REFRESH DATE 9/20/2023. SHOULD FAIL AROUND 3/20/2024
    'grant_type' => 'refresh_token'
);

// Initialize cURL session
$ch = curl_init($apiUrl);

// Set cURL options
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Return the response as a string
curl_setopt($ch, CURLOPT_POST, true); // Set the request method to POST
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData)); // Set the POST data

// Execute the cURL request
$response = curl_exec($ch);

// Check for cURL errors
if ($response === false) {
    echo 'cURL Error: ' . curl_error($ch);
} else {
    // Print the API response
    //echo $response;
}

// Close cURL session
curl_close($ch);

// Decode the JSON response
$responseData = json_decode($response);

// Check if the decoding was successful
if ($responseData !== null && isset($responseData->access_token)) {
    // Echo the access token
    //echo "Access Token: " . $responseData->access_token;
} else {
    echo "Failed to extract the access token from the response.";
}

//PostMaster API Begins Here
// Replace 'your_access_token' with your actual access token
$accessToken = $responseData->access_token;

// Get today's date
$today = new DateTime();

// Subtract 2 days from today's date
$daysBefore = 2;
$targetDate = $today->sub(new DateInterval("P{$daysBefore}D"));

//Uncomment this next block to run this code by date range
/*
// Define your start and end date in Ymd format
$start_date = '20231107'; // Replace with your desired start date
$end_date = '20231112';   // Replace with your desired end date

// Convert the start and end dates to DateTime objects
$start_datetime = DateTime::createFromFormat('Ymd', $start_date);
$end_datetime = DateTime::createFromFormat('Ymd', $end_date);

// Iterate through the date range
$current_date = $start_datetime;
while ($current_date <= $end_datetime) {
    // Format the current date in Ymd format
    $current_date_str = $current_date->format('Ymd');
    
    // Your API request code
    // Replace the following line with your API request code
    // makeApiRequest($current_date_str);

    // Your SQL insert code
    // Replace the following line with your SQL insert code
    // insertIntoDatabase($current_date_str);
*/


// Format the target date as 'YYYYMMDD'
$targetDateString = $targetDate->format('Ymd');
//$targetDateString = '20231107';
//Uncomment this next line to run this code by date range
//$targetDateString = $current_date_str;

$baseURL = 'https://gmailpostmastertools.googleapis.com/v1/domains/';

$domainNames = [
    'thecheapinvestor.com',
    'activetradernews.com',
    'optionstradingreport.com',
    'topstockreports.com',
    'tradersdaily.com',
    'tradingreportsdaily.com',
    'dailygoldalerts.com',
    'protradingresearch.com',
    'activetraderdaily.com',
    'investingwealthdaily.com',
    'weeklyinvestoralerts.com',
    'tradingstocksnow.com',
    'tradingcheatsheet.com',
    'marketspectator.com',
    'wealthyinvestorreport.com',
    'em.dailygoldalerts.com',
    'e.investingwealthdaily.com',
    'em.activetraderdaily.com',
    'e.tradingstocksnow.com',
    'em.protradingresearch.com'
];

$apiEndpoints = [];

foreach ($domainNames as $domain) {
    $apiEndpoints[] = $baseURL . $domain . '/trafficStats/' . $targetDateString;
}

// Now, $apiEndpoints contains all the URLs for the given domain names with the specified target date.


// Initialize cURL session
$ch = curl_init();

// Loop through the API endpoints
foreach ($apiEndpoints as $url) {

    // Find "v1/domains/" in the URL
    if (strpos($url, 'v1/domains/') !== false) {
        // Get the part of the URL after "v1/domains/"
        $afterV1Domains = substr($url, strpos($url, 'v1/domains/') + strlen('v1/domains/'));

        // Split the remaining part by "/"
        $parts = explode('/', $afterV1Domains);

        // The domain should be the first part
        if (isset($parts[0])) {
            $Domain = $parts[0];
        } else {
            echo "Domain not found in the URL.";
        }
    } else {
        echo "v1/domains/ not found in the URL.";
    }

    // Set the cURL URL to the current endpoint
    curl_setopt($ch, CURLOPT_URL, $url);

    // Set common cURL options (headers, timeouts, etc.)
    // Set cURL options
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Return the response as a string
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Authorization: Bearer ' . $accessToken // Set the Bearer token in the header
    ));

    // Execute the cURL request
    $response = curl_exec($ch);

    // Check for cURL errors
    if (curl_errno($ch)) {
        echo 'cURL error: ' . curl_error($ch);
        // Handle the error as needed
    } else {
        $data = json_decode($response);
        if ($data !== null) {
            // You can now work with the decoded data
            //Do all SQL code here...
            $mySQL = new db();
            $userReportedSpamRatio = $data->userReportedSpamRatio;
            foreach ($data->ipReputations as $element) {
                if (isset($element->ipCount)) {
                    $ipReputation = $element->reputation;
                }
            }
            $domainReputation =  $data->domainReputation;
            $spfSuccessRatio = $data->spfSuccessRatio;
            $dkimSuccessRatio = $data->dkimSuccessRatio;
            $dmarcSuccessRatio = $data->dmarcSuccessRatio;
            $inboundEncryptionRatio = $data->inboundEncryptionRatio;
            $unique_id = $Domain.'|'.$targetDateString;
            $mySQL->insert_sql("INSERT INTO google_postmaster (`Domain`, `userReportedSpamRatio`, `ipReputation`, `domainReputation`, `spfSuccessRatio`, `dkimSuccessRatio`, `dmarcSuccessRatio`, `inboundEncryptionRatio`, `statDate`, `unique_id`) 
            VALUES ('{$Domain}', '{$userReportedSpamRatio}', '{$ipReputation}', '{$domainReputation}', '{$spfSuccessRatio}', '{$dkimSuccessRatio}', '{$dmarcSuccessRatio}', '{$inboundEncryptionRatio}', '{$targetDateString}', '{$unique_id}')
            ON DUPLICATE KEY UPDATE
                `userReportedSpamRatio` = '{$userReportedSpamRatio}',
                `ipReputation` = '{$ipReputation}',
                `domainReputation` = '{$domainReputation}',
                `spfSuccessRatio` = '{$spfSuccessRatio}',
                `dkimSuccessRatio` = '{$dkimSuccessRatio}',
                `dmarcSuccessRatio` = '{$dmarcSuccessRatio}',
                `inboundEncryptionRatio` = '{$inboundEncryptionRatio}'
            ");

        } else {
            echo "Error decoding JSON response";
        }

        // Close the cURL session for this endpoint
        curl_reset($ch);
    }
}

//Uncomment this block to run this code by date range
/*
// Comment out the loop when you don't want to execute it
    // Increment the current date by one day
    $current_date->modify('+1 day');
}
*/

// Close the cURL session
curl_close($ch);

file_put_contents('/home/dh_uey5n8/imscrm.com/jonnydebugfile.txt', "\n(".date('H:i:s').") ". basename(__FILE__).':'.__LINE__." || Google API End", 8);
?>