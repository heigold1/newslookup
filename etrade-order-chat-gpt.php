<?php


/*  Preview Order Request(EQUITY Order) */




/*

Response: <?xml version="1.0" encoding="UTF-8" standalone="yes"?><PreviewOrderResponse><orderType>EQ</orderType><totalOrderValue>10</totalOrderValue><Order><orderTerm>GOOD_FOR_DAY</orderTerm><priceType>LIMIT</priceType><limitPrice>10</limitPrice><stopPrice>0</stopPrice><marketSession>REGULAR</marketSession><allOrNone>false</allOrNone><Instrument><Product><symbol>AAPL</symbol><securityType>EQ</securityType></Product><symbolDescription>APPLE INC COM</symbolDescription><orderAction>BUY</orderAction><quantityType>QUANTITY</quantityType><quantity>1</quantity><cancelQuantity>0.0</cancelQuantity><reserveOrder>true</reserveOrder><reserveQuantity>0.0</reserveQuantity></Instrument><egQual>EG_QUAL_NOT_IN_FORCE</egQual><estimatedCommission>0</estimatedCommission><estimatedTotalAmount>10</estimatedTotalAmount><netPrice>0</netPrice><netBid>0</netBid><netAsk>0</netAsk><gcd>0</gcd><ratio></ratio></Order><PreviewIds><previewId>2146000078106</previewId></PreviewIds><previewTime>1743268778182</previewTime><dstFlag>true</dstFlag><accountId>151706697</accountId><optionLevelCd>0</optionLevelCd><marginLevelCd>MARGIN_TRADING_ALLOWED</marginLevelCd><Disclosure><ahDisclosureFlag>true</ahDisclosureFlag><aoDisclosureFlag>true</aoDisclosureFlag><conditionalDisclosureFlag>true</conditionalDisclosureFlag><ehDisclosureFlag>true</ehDisclosureFlag></Disclosure><marginBpDetails><marginable><currentBp>1050.71</currentBp><currentNetBp>1050.71</currentNetBp><currentOor>0.00</currentOor><currentOrderImpact>10.00</currentOrderImpact><netBp>1040.71</netBp></marginable><nonMarginable><currentBp>1050.71</currentBp></nonMarginable></marginBpDetails></PreviewOrderResponse>

*/


/*

<?xml version="1.0" encoding="UTF-8" standalone="yes"?><AccountListResponse><Accounts><Account><accountId>151706697</accountId><accountIdKey>S11DfWByF1AJIO-pGBEw-g</accountIdKey><accountMode>MARGIN</accountMode><accountDesc>US Stocks</accountDesc><accountName>US Stocks</accountName><accountType>INDIVIDUAL</accountType><institutionType>BROKERAGE</institutionType><accountStatus>ACTIVE</accountStatus><closedDate>0</closedDate><shareWorksAccount>false</shareWorksAccount><fcManagedMssbClosedAccount>false</fcManagedMssbClosedAccount></Account>

<Account><accountId>326538367</accountId><accountIdKey>j9LikxyEkpqmW_KV9AGiWw</accountIdKey><accountMode>CASH</accountMode><accountDesc>Individual Brokerage</accountDesc><accountName></accountName><accountType>INDIVIDUAL</accountType><institutionType>BROKERAGE</institutionType><accountStatus>ACTIVE</accountStatus><closedDate>0</closedDate><shareWorksAccount>false</shareWorksAccount><fcManagedMssbClosedAccount>false</fcManagedMssbClosedAccount></Account></Accounts></AccountListResponse>

*/


$ini_array = parse_ini_file('etrade.ini', true);

// Set your E*TRADE credentials
$consumerKey = $ini_array['OAuth']['oauth_consumer_key']; 
$consumerSecret = $ini_array['OAuth']['consumer_secret'];
$accessToken = $ini_array['OAuth']['oauth_token'];
$accessTokenSecret = $ini_array['OAuth']['oauth_token_secret'];

// Generate OAuth 1.0a Authorization Header
function buildOAuthHeader($url, $method, $consumerKey, $consumerSecret, $accessToken, $accessTokenSecret) {
    $oauthNonce = bin2hex(random_bytes(16));
    $oauthTimestamp = time();

    $oauthParams = [
        'oauth_consumer_key' => $consumerKey,
        'oauth_nonce' => $oauthNonce,
        'oauth_signature_method' => 'HMAC-SHA1',
        'oauth_timestamp' => $oauthTimestamp,
        'oauth_token' => $accessToken,
        'oauth_version' => '1.0',
    ];

    // Sort and encode parameters
    ksort($oauthParams);
    $encodedParams = [];
    foreach ($oauthParams as $key => $value) {
        $encodedParams[] = rawurlencode($key) . '=' . rawurlencode($value);
    }

    $baseString = strtoupper($method) . '&' . rawurlencode($url) . '&' . rawurlencode(implode('&', $encodedParams));
    $signingKey = rawurlencode($consumerSecret) . '&' . rawurlencode($accessTokenSecret);

    // Create signature
    $oauthParams['oauth_signature'] = base64_encode(hash_hmac('sha1', $baseString, $signingKey, true));

    // Build Authorization header
    $authHeader = 'OAuth ';
    $values = [];
    foreach ($oauthParams as $key => $value) {
        $values[] = rawurlencode($key) . '="' . rawurlencode($value) . '"';
    }
    $authHeader .= implode(', ', $values);

    return $authHeader;
}


function generateClientOrderId() {
    // Get current timestamp
    $timestamp = time();

    // Generate a random alphanumeric string (e.g., 5 characters)
    $randomString = strtoupper(bin2hex(random_bytes(3))); // 3 bytes = 6 characters

    // Combine the timestamp and random string to create a unique order ID
    $clientOrderId = $timestamp . $randomString;

    // Ensure the length is less than or equal to 20 characters
    return substr($clientOrderId, 0, 20);
}


$accountId = 'S11DfWByF1AJIO-pGBEw-g';
$url = "https://api.etrade.com/v1/accounts/{$accountId}/orders/preview";
$clientOrderId = generateClientOrderId(); 

// Create the order payload
/*
$orderPayload = json_encode([
   "PreviewOrderRequest" => [
      "orderType" => "EQ",
      "clientOrderId" => $clientOrderId,
      "Order" => [
         [
            "allOrNone" => "false",
            "priceType" => "LIMIT",
            "orderTerm" => "GOOD_FOR_DAY",
            "marketSession" => "REGULAR",
            "stopPrice" => "",
            "limitPrice" => "18.51",
            "Instrument" => [
               [
                  "Product" => [
                     "securityType" => "EQ",
                     "symbol" => "FB"
                  ],
                  "orderAction" => "BUY",
                  "quantityType" => "QUANTITY",
                  "quantity" => 10
               ]
            ]
         ]
      ]
   ]
]);

$jsonPayload = json_encode($orderPayload, JSON_UNESCAPED_SLASHES);
*/

$jsonPayload = '{  
   "PreviewOrderRequest":{  
      "orderType":"EQ",
      "clientOrderId":"' . $clientOrderId . '",
      "Order":[  
         {  
            "allOrNone":"false",
            "priceType":"LIMIT",
            "orderTerm":"GOOD_FOR_DAY",
            "marketSession":"REGULAR",
            "stopPrice":"",
            "limitPrice":"10.00",
            "Instrument":[  
               {  
                  "Product":{  
                     "securityType":"EQ",
                     "symbol":"AAPL"
                  },
                  "orderAction":"BUY",
                  "quantityType":"QUANTITY",
                  "quantity":"1"
               }
            ]
         }
      ]
   }
}'; 



// Build the OAuth header
$oauthHeader = buildOAuthHeader($url, 'POST', $consumerKey, $consumerSecret, $accessToken, $accessTokenSecret);

// Initialize cURL request
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: $oauthHeader",
    "Content-Type: application/json"
]);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonPayload);

// Enable verbose output for debugging
curl_setopt($ch, CURLOPT_VERBOSE, true);
$verbose = fopen('php://temp', 'w+');
curl_setopt($ch, CURLOPT_STDERR, $verbose);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

// Capture verbose output
rewind($verbose);
$verboseLog = stream_get_contents($verbose);
echo "Verbose information:\n" . $verboseLog;

if ($response === false) {
    echo "cURL error: " . curl_error($ch);
}

curl_close($ch);

echo "HTTP Status Code: $httpCode\n";
echo "Response: $response\n";

return json_decode($response, true);

?>