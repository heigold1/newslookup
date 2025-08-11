<?php

$orderStub = null; 

if (isset($_POST['order_stub'])) {
    $orderStub = $_POST['order_stub']; 
}
else{
    echo "Could not get the order stub";
    die(); 
}

// Step 1: Trim everything after "--"
$beforeDash = explode("--", $orderStub)[0];
$beforeDash = trim($beforeDash);

// Step 2: Match the parts we want
// Pattern: SYMBOL BUY SHARES $PRICE (price can have commas, optional decimals)
if (preg_match('/^(\S+)\s+BUY\s+(\d+)\s+\$([\d,]+(?:\.\d{1,2})?)/', $beforeDash, $matches)) {
    $symbol = $matches[1];
    $shares = (int)$matches[2];
    // Remove commas from price before converting to float
    $price  = (float) str_replace(',', '', $matches[3]);

} else {
    echo "Couldn't parse the order stub.\n";
    die(); 
}

    // Validate and sanitize the inputs (optional)
    $symbol = strtoupper(trim($symbol));  // Ensure symbol is uppercase and trimmed
    $shares = intval($shares);  // Ensure shares is an integer
    $price = floatval($price);  // Ensure price is a float

// file_put_contents('debug_log.txt', "POST data: " . print_r($_POST, true) . "\n", FILE_APPEND);

$ini_array = parse_ini_file('./etrade.ini', true);

// Set your E*TRADE credentials
$consumerKey = $ini_array['OAuth']['oauth_consumer_key']; 
$consumerSecret = $ini_array['OAuth']['consumer_secret'];
$accessToken = $ini_array['OAuth']['oauth_token'];
$accessTokenSecret = $ini_array['OAuth']['oauth_token_secret'];

$message = "consumerKey is " . $consumerKey . ", consumerSecret " . $consumerSecret . ", and accessToken is " . $accessToken . ", accessTokenSecret is " . $accessTokenSecret; 

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

function arrayToXml($data, &$xml) {
    foreach ($data as $key => $value) {
        // Remove 'Item' wrappers from single-element arrays
        if (is_numeric($key)) {
            arrayToXml($value, $xml);
        } elseif (is_array($value)) {
            $subnode = $xml->addChild($key);
            arrayToXml($value, $subnode);
        } else {
            $xml->addChild($key, htmlspecialchars($value));
        }
    }
}

function convertToXmlString($orderPayloadArray) {
    $xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><PlaceOrderRequest/>');
    arrayToXml($orderPayloadArray["PlaceOrderRequest"], $xml);
    return $xml->asXML();
}

function curlRequest($url, $oauthHeader, $payload, $format)
{
    // Initialize cURL request
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: $oauthHeader",
        "Content-Type: application/" . $format
    ]);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

    // Enable verbose output for debugging
    curl_setopt($ch, CURLOPT_VERBOSE, false);
    $verbose = fopen('php://temp', 'w+');
    curl_setopt($ch, CURLOPT_STDERR, $verbose);

    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    // Capture verbose output
    // rewind($verbose);
    // $verboseLog = stream_get_contents($verbose);
    // echo "Verbose information:\n" . $verboseLog;

    if ($response === false) {
    echo "cURL error: " . curl_error($ch);
    }

   curl_close($ch);
   return $response; 
}

$accountId = 'S11DfWByF1AJIO-pGBEw-g';
$url = "https://api.etrade.com/v1/accounts/{$accountId}/orders/preview";
$clientOrderId = generateClientOrderId(); 

// Create the preview order payload

$jsonPayloadArray = [
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
                "limitPrice" => $price,
                "Instrument" => [
                    [
                        "Product" => [
                            "securityType" => "EQ",
                            "symbol" => "$symbol"
                        ],
                        "orderAction" => "BUY",
                        "quantityType" => "QUANTITY",
                        "quantity" => $shares 
                    ]
                ]
            ]
        ]
    ]
];

$jsonPayload = json_encode($jsonPayloadArray, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

// Build the OAuth header
$oauthHeader = buildOAuthHeader($url, 'POST', $consumerKey, $consumerSecret, $accessToken, $accessTokenSecret);

$response = curlRequest($url, $oauthHeader, $jsonPayload, "json"); 

$orderPreviewXMLResponseObject = simplexml_load_string($response);

$previewId = 0; 

if (!empty($orderPreviewXMLResponseObject->PreviewIds->previewId)) {
    $previewId = $orderPreviewXMLResponseObject->PreviewIds->previewId;

    $orderPayloadArray = [
        "PlaceOrderRequest" => [
            "orderType" => "EQ",
            "clientOrderId" => $clientOrderId,
            "PreviewIds" => [
                "previewId" => "$previewId"  // Add the previewId here
            ],
            "Order" => [
                [
                    "allOrNone" => "false",
                    "priceType" => "LIMIT",
                    "orderTerm" => "GOOD_FOR_DAY",
                    "marketSession" => "REGULAR",
                    "stopPrice" => "",
                    "limitPrice" => $price,
                    "Instrument" => [
                        [
                            "Product" => [
                                "securityType" => "EQ",
                                "symbol" => "$symbol"
                            ],
                            "orderAction" => "BUY",
                            "quantityType" => "QUANTITY",
                            "quantity" => $shares 
                        ]
                    ]
                ]
            ]
        ]
    ];

    $orderXMLPayload = convertToXmlString($orderPayloadArray);

    $orderUrl = "https://api.etrade.com/v1/accounts/{$accountId}/orders/place"; 
    $oauthHeader = buildOAuthHeader($orderUrl, 'POST', $consumerKey, $consumerSecret, $accessToken, $accessTokenSecret);
    $response = curlRequest($orderUrl, $oauthHeader, $orderXMLPayload, "xml"); 
    $placeOrderXMLResponseObject = simplexml_load_string($response);

    return json_decode($response, true);

} else {
    echo "Preview order failed!";
    $previewId = -1; 
    die(); 
}



?>