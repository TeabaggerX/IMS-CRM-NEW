<?php

function eflowAPI($baseURL, $postType, $payload){
    // Request headers
    $headers = array(
        "X-Eflow-API-Key: c9ce04CoQieR8hAg3tyDPw",
        "Content-Type: application/json"
    );
    // Initialize cURL session
    $ch = curl_init('https://api.eflow.team/v1/'.$baseURL);

    // Set cURL options
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Return the response as a string
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); // Set request headers
    if($postType == 'post'){
        curl_setopt($ch, $postType, 1); // Set as a POST request
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload); // Add the payload
    }

    $response = curl_exec($ch);

    // Check for cURL errors
    if (curl_errno($ch)) {
        echo 'Error: ' . curl_error($ch);
       die();
    }

    // Close cURL session
    curl_close($ch);

    return json_decode($response, true);
}

function wordpressAPI($baseURL, $api){
    // Request headers
    $headers = array(
        "Authorization: ".$api,
        "Content-Type: application/json"
    );
    $url = 'https://'.$baseURL . '/wp-json/mo/v1/getPosts';
    // Initialize cURL session
    $curl = curl_init();

    // Set cURL options
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); // Return the response as a string
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers); // Set request headers

    $response = curl_exec($curl);

    // Check for cURL errors
    if (curl_errno($curl)) {
        echo 'Error: ' . curl_error($curl);
       die();
    }

    // Close cURL session
    curl_close($curl);

    return json_decode($response, true);
}