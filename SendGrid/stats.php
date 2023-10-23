<?php
file_put_contents('/home/dh_uey5n8/imscrm.com/jonnydebugfile.txt', "\n(".date('H:i:s').") ". basename(__FILE__).':'.__LINE__." || SendGrid Stats Start", 8);
include("/home/dh_uey5n8/imscrm.com/lib/connect.php");
$apiKeys = [
    "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|PTR",
    "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|WIA",
    "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|ATN",
    "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|TCI",
    "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|OTR",
    "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|TD", //Traders Daily
    "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|TSR" //Top Stocks Report
];

//Initialize SQL DB
$mySQL = new db();

foreach ($apiKeys as $key) {
    // API endpoint and API key
    $apiEndpoint = "https://api.sendgrid.com/v3/marketing/singlesends";

    // Initialize page_token to null for the first request
    $page_token = null;

    // Reset the arrays before the loop
    $campaignIds = [];
    $batchedCampaignIds = [];

    $apiInfo = explode("|", $key);
    $key = $apiInfo[0];
    $project_name = $apiInfo[1];

    $totalCount = 0; // Initialize count for each API key

    file_put_contents('/home/dh_uey5n8/imscrm.com/jonnydebugfile.txt', "\n(" . date('H:i:s') . ") " . basename(__FILE__) . ':' . __LINE__ . " || Project: $project_name <----MAIN PROJECT START", 8);

    $combinedResponseData = []; // Initialize an array to store all the statsData

    do {
        // Create the URL for the API request, including the page_token if available
        $url = $apiEndpoint . ($page_token ? "?page_token=" . $page_token : "");

        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $key,
        ]);

        $response = curl_exec($ch);

        if ($response === false) {
            file_put_contents('/home/dh_uey5n8/imscrm.com/jonnydebugfile.txt', "\n(" . date('H:i:s') . ") " . basename(__FILE__) . ':' . __LINE__ . " || cURL error:  ". curl_error($ch), 8);
        } else {
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if ($http_code == 200) {
                $responseData = json_decode($response, true);

                // Process $responseData as needed
                foreach ($responseData['result'] as $item) {
                    
                    // Append each item to the combinedStatsData array
                    $combinedResponseData[] = $item;
                    
                }

                // Extract the next page_token if it exists
                if (isset($responseData['_metadata']['next'])) {
                    $next_url = $responseData['_metadata']['next'];
                    $page_token = parse_url($next_url, PHP_URL_QUERY);
                    parse_str($page_token, $page_token);
                    $page_token = $page_token['page_token'];
                } else {
                    // No more pages to fetch
                    $page_token = null;
                }
            }else {
                file_put_contents('/home/dh_uey5n8/imscrm.com/jonnydebugfile.txt', "\n(" . date('H:i:s') . ") " . basename(__FILE__) . ':' . __LINE__ . " || API request failed with HTTP status code: ". $http_code, 8);
            }
            
        }

        curl_close($ch);

    } while ($page_token);

    file_put_contents('/home/dh_uey5n8/imscrm.com/jonnydebugfile.txt', "\n(" . date('H:i:s') . ") " . basename(__FILE__) . ':' . __LINE__ . " || Combined Response Data Starts Here for $project_name ", 8);
    foreach ($combinedResponseData as $item) {
        $campaign_id = $item['id'];
        $campaign_name =  $item['name'];
        $campaign_status = $item['status'];
        $send_at = $item['send_at'];
    }

    $count = count($combinedResponseData);
    file_put_contents('/home/dh_uey5n8/imscrm.com/jonnydebugfile.txt', "\n(" . date('H:i:s') . ") " . basename(__FILE__) . ':' . __LINE__ . " || Total Count: " . $count, 8);

    $apiEndpoint = "https://api.sendgrid.com/v3/marketing/stats/singlesends";

    $page_token = null;
    $next_url = null;
    $response = null;

    $combinedStatsData = []; // Initialize an array to store all the statsData

    do {
        // Create the URL for the API request, including the page_token if available
        $url = $apiEndpoint . ($page_token ? "?page_token=" . $page_token : "");

        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $key,
        ]);

        $response = curl_exec($ch);

        if ($response === false) {
            file_put_contents('/home/dh_uey5n8/imscrm.com/jonnydebugfile.txt', "\n(" . date('H:i:s') . ") " . basename(__FILE__) . ':' . __LINE__ . " || cURL error:  ". curl_error($ch), 8);
        } else {
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if ($http_code == 200) {
                $statsData = json_decode($response, true);

                // Process $statsData as needed
                foreach ($statsData['results'] as $item) {
                    // Append each item to the combinedStatsData array
                    $combinedStatsData[] = $item;
                }

                // Extract the next page_token if it exists
                if (isset($statsData['_metadata']['next'])) {
                    $next_url = $statsData['_metadata']['next'];
                    $page_token = parse_url($next_url, PHP_URL_QUERY);
                    parse_str($page_token, $page_token);
                    $page_token = $page_token['page_token'];
                } else {
                    // No more pages to fetch
                    $page_token = null;
                }
            } else {
                file_put_contents('/home/dh_uey5n8/imscrm.com/jonnydebugfile.txt', "\n(" . date('H:i:s') . ") " . basename(__FILE__) . ':' . __LINE__ . " || API request failed with HTTP status code: ". $http_code, 8);
            }
        }

        curl_close($ch);

    } while ($page_token);

    file_put_contents('/home/dh_uey5n8/imscrm.com/jonnydebugfile.txt', "\n(" . date('H:i:s') . ") " . basename(__FILE__) . ':' . __LINE__ . " || Combined Stats Data Starts Here for $project_name ", 8);
    /*
    foreach ($combinedStatsData as $item) {
        $campaign_id = $item['id'];

        $bounce_drops = $item['stats']['bounce_drops'];
        $bounces = $item['stats']['bounces'];
        $clicks = $item['stats']['clicks'];
        $unique_clicks = $item['stats']['unique_clicks'];
        $delivered = $item['stats']['delivered'];
        $invalid_emails = $item['stats']['invalid_emails'];
        $opens = $item['stats']['opens'];
        $unique_opens = $item['stats']['unique_opens'];
        $requests = $item['stats']['requests'];
        $spam_report_drops = $item['stats']['spam_report_drops'];
        $spam_reports = $item['stats']['spam_reports'];
        $unsubscribes = $item['stats']['unsubscribes'];
        
    }
    */

    // Step 1: Create a new array to store the merged data
    $mergedData = [];

    // Step 2: Loop through combinedResponseData to create an associative array
    foreach ($combinedResponseData as $item) {
        $campaign_id = $item['id'];
        $mergedData[$campaign_id] = $item;
    }

    // Step 3: Loop through combinedStatsData to merge stats data into the corresponding campaign_id
    foreach ($combinedStatsData as $item) {
        $campaign_id = $item['id'];
        if (isset($mergedData[$campaign_id])) {
            // Merge the stats data into the existing array
            $mergedData[$campaign_id]['bounce_drops'] = $item['stats']['bounce_drops'];
            $mergedData[$campaign_id]['bounces'] = $item['stats']['bounces'];
            $mergedData[$campaign_id]['clicks'] = $item['stats']['clicks'];
            $mergedData[$campaign_id]['unique_clicks'] = $item['stats']['unique_clicks'];
            $mergedData[$campaign_id]['delivered'] = $item['stats']['delivered'];
            $mergedData[$campaign_id]['invalid_emails'] = $item['stats']['invalid_emails'];
            $mergedData[$campaign_id]['opens'] = $item['stats']['opens'];
            $mergedData[$campaign_id]['unique_opens'] = $item['stats']['unique_opens'];
            $mergedData[$campaign_id]['requests'] = $item['stats']['requests'];
            $mergedData[$campaign_id]['spam_report_drops'] = $item['stats']['spam_report_drops'];
            $mergedData[$campaign_id]['spam_reports'] = $item['stats']['spam_reports'];
            $mergedData[$campaign_id]['unsubscribes'] = $item['stats']['unsubscribes'];
        }
    }

    file_put_contents('/home/dh_uey5n8/imscrm.com/jonnydebugfile.txt', "\n(".date('H:i:s').") ". basename(__FILE__).':'.__LINE__." || BEFORE SQL IMPORT", 8);
    
    // Step 4: Loop through the merged data and echo every component
    foreach ($mergedData as $campaign_id => $data) {
        //$campaign_id;
        $campaign_name = $data['name'];
        $campaign_status = $data['status'];
        $send_at = $data['send_at'];
        $bounce_drops = $data['bounce_drops'];
        $bounces = $data['bounces'];
        $clicks = $data['clicks'];
        $unique_clicks = $data['unique_clicks'];
        $delivered = $data['delivered'];
        $invalid_emails = $data['invalid_emails'];
        $opens = $data['opens'];
        $unique_opens = $data['unique_opens'];
        $requests = $data['requests'];
        $spam_report_drops = $data['spam_report_drops'];
        $spam_reports = $data['spam_reports'];
        $unsubscribes = $data['unsubscribes'];


        $result = $mySQL->insert_sql("
        INSERT INTO sendgrid_stats
        (                        
            `project_name`,
            `campaign_id`,
            `campaign_name`,
            `campaign_status`,
            `send_at`,
            `bounce_drops`,
            `bounces`,
            `clicks`,
            `unique_clicks`,
            `delivered`,
            `invalid_emails`,
            `opens`,
            `unique_opens`,
            `requests`,
            `spam_report_drops`,
            `spam_reports`,
            `unsubscribes`
        )
        VALUES
        (
            '{$project_name}',
            '{$campaign_id}',
            '{$campaign_name}',
            '{$campaign_status}',
            '{$send_at}',
            '{$bounce_drops}',
            '{$bounces}',
            '{$clicks}',
            '{$unique_clicks}',
            '{$delivered}',
            '{$invalid_emails}',
            '{$opens}',
            '{$unique_opens}',
            '{$requests}',
            '{$spam_report_drops}',
            '{$spam_reports}',
            '{$unsubscribes}'
        )
        on duplicate KEY UPDATE 
            `project_name` = '{$project_name}',
            `campaign_id` = '{$campaign_id}',
            `campaign_name` = '{$campaign_name}',
            `campaign_status` = '{$campaign_status}',
            `send_at` = '{$send_at}',
            `bounce_drops` = '{$bounce_drops}',
            `bounces` = '{$bounces}',
            `clicks` = '{$clicks}',
            `unique_clicks` = '{$unique_clicks}',
            `delivered` = '{$delivered}',
            `invalid_emails` = '{$invalid_emails}',
            `opens` = '{$opens}',
            `unique_opens` = '{$unique_opens}',
            `requests` = '{$requests}',
            `spam_report_drops` = '{$spam_report_drops}',
            `spam_reports` = '{$spam_reports}',
            `unsubscribes` = '{$unsubscribes}'

    ");

    }

    file_put_contents('/home/dh_uey5n8/imscrm.com/jonnydebugfile.txt', "\n(".date('H:i:s').") ". basename(__FILE__).':'.__LINE__." || Result: $result", 8);
    file_put_contents('/home/dh_uey5n8/imscrm.com/jonnydebugfile.txt', "\n(".date('H:i:s').") ". basename(__FILE__).':'.__LINE__." || AFTER SQL IMPORT", 8);
    
    /*
    foreach ($mergedData as $campaign_id => $data) {
        $output = "Campaign ID: $campaign_id\n" .
                "Campaign Name: {$data['name']}\n" .
                "Campaign Status: {$data['status']}\n" .
                "Send At: {$data['send_at']}\n" .
                "Bounce Drops: {$data['bounce_drops']}\n" .
                "Bounces: {$data['bounces']}\n" .
                "Clicks: {$data['clicks']}\n" .
                "Unique Clicks: {$data['unique_clicks']}\n" .
                "Delivered: {$data['delivered']}\n" .
                "Invalid Emails: {$data['invalid_emails']}\n" .
                "Opens: {$data['opens']}\n" .
                "Unique Opens: {$data['unique_opens']}\n" .
                "Requests: {$data['requests']}\n" .
                "Spam Report Drops: {$data['spam_report_drops']}\n" .
                "Spam Reports: {$data['spam_reports']}\n" .
                "Unsubscribes: {$data['unsubscribes']}\n\n";

        file_put_contents('/home/dh_uey5n8/imscrm.com/jonnydebugfile.txt', "\n(" . date('H:i:s') . ") " . basename(__FILE__) . ':' . __LINE__ . " || $output", 8);
    }
    */
    
    $count = count($mergedData);
    file_put_contents('/home/dh_uey5n8/imscrm.com/jonnydebugfile.txt', "\n(" . date('H:i:s') . ") " . basename(__FILE__) . ':' . __LINE__ . " || Total Count: " . $count, 8);

}

file_put_contents('/home/dh_uey5n8/imscrm.com/jonnydebugfile.txt', "\n(".date('H:i:s').") ". basename(__FILE__).':'.__LINE__." || Sendgrid Stats End", 8);
?>