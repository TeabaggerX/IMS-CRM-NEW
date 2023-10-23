<?php
file_put_contents('/home/dh_uey5n8/imscrm.com/jonnydebugfile.txt', "\n(".date('H:i:s').") ". basename(__FILE__).':'.__LINE__." || Iterable Stats Start", 8);
include("/home/dh_uey5n8/imscrm.com/lib/connect.php");
$apiKeys = [
    "376dc9706e95434ab3a320853931b394|DGA", //DGA
    "d8cfa498442e45bb9a1492050f9fde68|IWD", //IWD
    "b584c8bdb5a1425c898e325bab504c63|TCS",
    "eac92a1a6caa4e4389799d9b1450c5ca|PTR",
    "b4614ac19cff4d48b1928caa90166f18|ATD",
    "534a18c4c26d45f29af9d6cd14ac7275|TSN",
    "feb361f56067427fbe177f42bdae1af2|OTR",
    "e14b05c4d1f24311afc673294ef3918e|TRD"
];

//Initialize SQL DB
$mySQL = new db();

// Loop through the API endpoints
foreach ($apiKeys as $key) {

    file_put_contents('/home/dh_uey5n8/imscrm.com/jonnydebugfile.txt', "\n(".date('H:i:s').") ". basename(__FILE__).':'.__LINE__." || Project: $key <----MAIN PROJECT START", 8);

    // API endpoint and API key
    $apiEndpoint = "https://api.iterable.com/api/campaigns"; 
    $response = "";
    // Reset the arrays before the loop
    $campaignIds = [];
    $batchedCampaignIds = [];

    $apiInfo = explode("|",$key);
    $key = $apiInfo[0];
    $project_name = $apiInfo[1];

    // Define the rate limit settings
    $maxRequestsPerMinute = 10;
    $delayBetweenRequests = 60 / $maxRequestsPerMinute; // Seconds

    // Get today's date
    $currentDate = date("Y-m-d");

    // Initialize cURL session
    $ch = curl_init($apiEndpoint);

    // Set cURL options
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "API-Key: $key",
    ]);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Execute cURL session and store the response in $response
    $response = curl_exec($ch);

    // Check for cURL errors
    if (curl_errno($ch)) {
        echo "cURL Error: " . curl_error($ch);
    }

    // Close cURL session
    curl_close($ch);


    $responseData = json_decode($response, true);

    // Check if parsing was successful
    if ($responseData !== null) {

        //print_r($responseData);
        //echo "<br><br><br><br><br>";

        // Check if the 'campaigns' key exists in the response
        if (isset($responseData['campaigns'])) {

            // Split the campaign IDs into batches (e.g., 400 IDs per batch)
            $campaignIds = array_column($responseData['campaigns'], 'id');
            $batchedCampaignIds = array_chunk($campaignIds, 400);

            // Loop through each batch of campaign IDs
            foreach ($batchedCampaignIds as $campaignIdBatch) {

                // Build the dynamic API endpoint with batched campaign IDs and today's date
                $apiEndpoint = "https://api.iterable.com/api/campaigns/metrics?campaignId=" . implode('&campaignId=', $campaignIdBatch) . "&startDateTime=1900-01-01&endDateTime=2099-01-01";

                // Implement rate limiting
                sleep($delayBetweenRequests); // Delay between requests in seconds

                // Initialize cURL session
                $ch = curl_init($apiEndpoint);

                // Set cURL options
                curl_setopt($ch, CURLOPT_HTTPHEADER, [
                    "API-Key: $key",
                ]);

                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                // Execute cURL session and store the response in $apiResponse
                $apiResponse = curl_exec($ch);

                // Check for cURL errors
                if (curl_errno($ch)) {
                    echo "cURL Error: " . curl_error($ch);
                    die();
                }

                // Close cURL session
                curl_close($ch);

                // Print the API response
                //echo "API Response: " . $apiResponse . "\n";
                
                // Split the response into lines
                $responseLines = explode("\n", $apiResponse);

                // Extract headers from the first line and split into an array
                $headers = explode(",", $responseLines[0]);

                // Initialize an array to store the result
                $result = [];

                // Loop through the remaining lines (data)
                for ($i = 1; $i < count($responseLines); $i++) {
                    // Split each line into an array
                    $rowData = explode(",", $responseLines[$i]);
                    
                    // Initialize an associative array for this row
                    $row = [];
                    
                    // Match headers with data and create key-value pairs
                    for ($j = 0; $j < count($headers); $j++) {
                        $row[$headers[$j]] = $rowData[$j];
                    }
                    
                    // Add the row to the result array
                    $result[] = $row;
                }

                // Now, $result is an array of associative arrays with key-value pairs
                //print_r($result);

                // Loop through the result array and echo the 'id' for each item
                foreach ($result as $item) {
                    //echo "ID: " . $item['id'] . "\n";

                    file_put_contents('/home/dh_uey5n8/imscrm.com/jonnydebugfile.txt', "\n(".date('H:i:s').") ". basename(__FILE__).':'.__LINE__." || Project: $key", 8);

                    $id = $item['id'];
                    $Average_Custom_Conversion_Value = $item['Average Custom Conversion Value'];
                    $Average_Order_Value = $item['Average Order Value'];
                    $Purchases_M_Email = $item['Purchases / M (Email)'];
                    $Revenue = $item['Revenue'];
                    $Revenue_M_Email = $item['Revenue / M (Email)'];
                    $Sum_of_Custom_Conversions = $item['Sum of Custom Conversions'];
                    $Total_Complaints = $item['Total Complaints'];
                    $Total_Custom_Conversions = $item['Total Custom Conversions'];
                    $Total_Email_Holdout = $item['Total Email Holdout'];
                    $Total_Email_Opens = $item['Total Email Opens'];
                    $Total_Email_Opens_filtered = $item['Total Email Opens (filtered)'];
                    $Total_Email_Send_Skips = $item['Total Email Send Skips'];
                    $Total_Email_Sends = $item['Total Email Sends'];
                    $Total_Emails_Bounced = $item['Total Emails Bounced'];
                    $Total_Emails_Clicked = $item['Total Emails Clicked'];
                    $Total_Emails_Delivered = $item['Total Emails Delivered'];
                    $Total_Purchases = $item['Total Purchases'];
                    $Total_Unsubscribes = $item['Total Unsubscribes'];
                    $Unique_Custom_Conversions = $item['Unique Custom Conversions'];
                    $Unique_Email_Clicks = $item['Unique Email Clicks'];
                    $Unique_Email_Opens = $item['Unique Email Opens'];
                    $Unique_Email_Opens_filtered = $item['Unique Email Opens (filtered)'];
                    $Unique_Email_Opens_Or_Clicks = $item['Unique Email Opens Or Clicks'];
                    $Unique_Email_Sends = $item['Unique Email Sends'];
                    $Unique_Emails_Bounced = $item['Unique Emails Bounced'];
                    $Unique_Emails_Delivered = $item['Unique Emails Delivered'];
                    $Unique_Purchases = $item['Unique Purchases'];
                    $Unique_Unsubscribes = $item['Unique Unsubscribes'];

                    //file_put_contents('/home/dh_uey5n8/imscrm.com/jonnydebugfile.txt', "\n(".date('H:i:s').") ". basename(__FILE__).':'.__LINE__." || All Variables: $project_name, $id, $Average_Order_Value, $Purchases_M_Email, $Revenue, $Revenue_M_Email, $Sum_of_Custom_Conversions, $Total_Complaints, $Total_Custom_Conversions, $Total_Email_Holdout, $Total_Email_Opens, $Total_Email_Opens_filtered, $Total_Email_Send_Skips, $Total_Email_Sends, $Total_Emails_Bounced, $Total_Emails_Clicked, $Total_Emails_Delivered, $Total_Purchases, $Total_Unsubscribes, $Unique_Custom_Conversions, $Unique_Email_Clicks, $Unique_Email_Opens, $Unique_Email_Opens_filtered, $Unique_Email_Opens_Or_Clicks, $Unique_Email_Sends, $Unique_Emails_Bounced, $Unique_Emails_Delivered, $Unique_Purchases, $Unique_Unsubscribes", 8);                  

                    //mySQL->insert_sql("INSERT INTO iterable_stats (`campaign_id`, `Average_Order_Value`) VALUES ('{$id}', '{$Average_Order_Value}') ON DUPLICATE KEY UPDATE `campaign_id` = '{$Average_Order_Value}', `Average_Order_Value` = '{$Average_Order_Value}'");
                    $result = $mySQL->insert_sql("
                        INSERT INTO iterable_stats
                        (                        
                            `project_name`,
                            `campaign_id`,
                            `average_order_value`,
                            `purchases_m_email`,
                            `revenue`,
                            `revenue_m_email`,
                            `sum_of_custom_conversions`,
                            `total_complaints`,
                            `total_custom_conversions`,
                            `total_email_holdout`,
                            `total_email_opens`,
                            `total_email_opens_filtered`,
                            `total_email_send_skips`,
                            `total_email_sends`,
                            `total_emails_bounced`,
                            `total_emails_clicked`,
                            `total_emails_delivered`,
                            `total_purchases`,
                            `total_unsubscribes`,
                            `unique_custom_conversions`,
                            `unique_email_clicks`,
                            `unique_email_opens`,
                            `unique_email_opens_filtered`,
                            `unique_email_opens_or_clicks`,
                            `unique_email_sends`,
                            `unique_emails_bounced`,
                            `unique_emails_delivered`,
                            `unique_purchases`,
                            `unique_unsubscribes`
                        )
                        VALUES
                        (
                            '{$project_name}',
                            '{$id}',
                            '{$Average_Order_Value}',
                            '{$Purchases_M_Email}',
                            '{$Revenue}',
                            '{$Revenue_M_Email}',
                            '{$Sum_of_Custom_Conversions}',
                            '{$Total_Complaints}',
                            '{$Total_Custom_Conversions}',
                            '{$Total_Email_Holdout}',
                            '{$Total_Email_Opens}',
                            '{$Total_Email_Opens_filtered}',
                            '{$Total_Email_Send_Skips}',
                            '{$Total_Email_Sends}',
                            '{$Total_Emails_Bounced}',
                            '{$Total_Emails_Clicked}',
                            '{$Total_Emails_Delivered}',
                            '{$Total_Purchases}',
                            '{$Total_Unsubscribes}',
                            '{$Unique_Custom_Conversions}',
                            '{$Unique_Email_Clicks}',
                            '{$Unique_Email_Opens}',
                            '{$Unique_Email_Opens_filtered}',
                            '{$Unique_Email_Opens_Or_Clicks}',
                            '{$Unique_Email_Sends}',
                            '{$Unique_Emails_Bounced}',
                            '{$Unique_Emails_Delivered}',
                            '{$Unique_Purchases}',
                            '{$Unique_Unsubscribes}'
                        )
                        on duplicate KEY UPDATE 
                            `project_name` = '{$project_name}',
                            `campaign_id` = '{$id}',
                            `average_order_value` = '{$Average_Order_Value}',
                            `purchases_m_email` = '{$Purchases_M_Email}',
                            `revenue` = '{$Revenue}',
                            `revenue_m_email` = '{$Revenue_M_Email}',
                            `sum_of_custom_conversions` = '{$Sum_of_Custom_Conversions}',
                            `total_complaints` = '{$Total_Complaints}',
                            `total_custom_conversions` = '{$Total_Custom_Conversions}',
                            `total_email_holdout` = '{$Total_Email_Holdout}',
                            `total_email_opens` = '{$Total_Email_Opens}',
                            `total_email_opens_filtered` = '{$Total_Email_Opens_filtered}',
                            `total_email_send_skips` = '{$Total_Email_Send_Skips}',
                            `total_email_sends` = '{$Total_Email_Sends}',
                            `total_emails_bounced` = '{$Total_Emails_Bounced}',
                            `total_emails_clicked` = '{$Total_Emails_Clicked}',
                            `total_emails_delivered` = '{$Total_Emails_Delivered}',
                            `total_purchases` = '{$Total_Purchases}',
                            `total_unsubscribes` = '{$Total_Unsubscribes}',
                            `unique_custom_conversions` = '{$Unique_Custom_Conversions}',
                            `unique_email_clicks` = '{$Unique_Email_Clicks}',
                            `unique_email_opens` = '{$Unique_Email_Opens}',
                            `unique_email_opens_filtered` = '{$Unique_Email_Opens_filtered}',
                            `unique_email_opens_or_clicks` = '{$Unique_Email_Opens_Or_Clicks}',
                            `unique_email_sends` = '{$Unique_Email_Sends}',
                            `unique_emails_bounced` = '{$Unique_Emails_Bounced}',
                            `unique_emails_delivered` = '{$Unique_Emails_Delivered}',
                            `unique_purchases` = '{$Unique_Purchases}',
                            `unique_unsubscribes` = '{$Unique_Unsubscribes}'
                    ");
                    file_put_contents('/home/dh_uey5n8/imscrm.com/jonnydebugfile.txt', "\n(".date('H:i:s').") ". basename(__FILE__).':'.__LINE__." || Result: $result", 8);
                    file_put_contents('/home/dh_uey5n8/imscrm.com/jonnydebugfile.txt', "\n(".date('H:i:s').") ". basename(__FILE__).':'.__LINE__." || AFTER SQL IMPORT", 8);
                    
                }
                
            }

            // Reset the arrays before the loop
            $campaignIds = [];
            $batchedCampaignIds = [];

            foreach ($responseData['campaigns'] as $campaign) {
                $string = $campaign['name'];
                $campID = $campaign['id'];
                if (isset($campaign['startAt'])) {
                    $startAt = $campaign['startAt'];
                } else {
                    // Handle the case where $campaign['startAt'] is not set
                    $startAt = null;
                }
                $pattern = '/(\d{6})/'; // Matches 6 consecutive digits
                if (preg_match($pattern, $string, $matches)) {
                    $parsedDate = $matches[1];

                    // Parse the date with the desired format
                    $date = DateTime::createFromFormat('mdy', $parsedDate);

                    // Format the date as YYYY-MM-DD
                    $dateFormatted = $date->format('Y-m-d');
                    if (empty($startAt)) {
                        $mySQL->insert_sql("UPDATE iterable_stats SET `Name_Date` = '{$dateFormatted}', `campaign_name` = '{$string}' WHERE `campaign_id` = '{$campID}'");
                    } else {
                        $mySQL->insert_sql("UPDATE iterable_stats SET `Name_Date` = '{$dateFormatted}', `campaign_name` = '{$string}', `start_at` = '{$startAt}' WHERE `campaign_id` = '{$campID}'");
                    }
                } else {
                    //echo "Date not found in the string.";
                }
            }
            
        } else {
            echo "No campaigns found in the response.\n";
        }

    } else {
        echo "Failed to decode JSON response TEST.\n";
        print_r($responseData);
    }
    }

file_put_contents('/home/dh_uey5n8/imscrm.com/jonnydebugfile.txt', "\n(".date('H:i:s').") ". basename(__FILE__).':'.__LINE__." || Iterable Stats End", 8);
?>