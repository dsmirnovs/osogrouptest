<?php

const API_KEY = 'c58239668b-e02021b3ef-s43w7o';
function getAllRates() {
    $apikey = API_KEY;
    $json = file_get_contents("https://api.fastforex.io/fetch-all?from=EUR&api_key={$apikey}");
    $obj = json_decode($json, true);
    if(is_null($obj)) {
        return $http_response_header[0];
    }
    return $obj;
}

function convertCurrency($from, $to, $amount) {
    $apikey = API_KEY;
    // change to the free URL if you're using the free version
    $json = file_get_contents("https://api.fastforex.io/convert?from={$from}&to={$to}&amount={$amount}&api_key={$apikey}");
    $obj = json_decode($json, true);
    return $obj;
}

