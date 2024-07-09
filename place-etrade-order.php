<?php
/**
 * E*TRADE PHP SDK
 *
 * @package    	PHP-SDK
 * @version		1.1
 * @copyright  	Copyright (c) 2012 E*TRADE FINANCIAL Corp.
 *
 */

// to run this file is http://localhost/newslookup/place-etrade-order.php?orderString=hello-world


require_once("config.php");
require_once(dirname(__FILE__) . '\Common\Common.php');
require_once(dirname(__FILE__) . '\Orders\OrderClient.class.php');

$logFile = '\place-etrade-order.log';

$orderString=$_GET['orderString'];

// $dataArray = array();

$ini_array = parse_ini_file('etrade.ini', true);

//$consumer   = new etOAuthConsumer(ETWS_APP_KEY,ETWS_APP_SECRET);
// $consumer = new etOAuthConsumer($ini_array['OAuth']['oauth_consumer_key'],$ini_array['OAuth']['consumer_secret']);
//$consumer->oauth_token      = $ini_array['OAuth']['oauth_token'];
//$consumer->oauth_token_secret   = $ini_array['OAuth']['oauth_token_secret'];
//$ac_obj = new OrderClient($consumer);

echo "<pre>"; 






function create_oauth_signature($url, $http_method, $parameters, $consumer_key, $consumer_secret, $token, $token_secret) {
    // Add OAuth required parameters
    $oauth_parameters = [
        'oauth_consumer_key' => $consumer_key,
        'oauth_token' => $token,
        'oauth_signature_method' => 'HMAC-SHA1',
        'oauth_timestamp' => $parameters['oauth_timestamp'],
        'oauth_nonce' => $parameters['oauth_nonce'],
        'oauth_version' => '1.0'
    ];

    // Merge parameters
    $all_parameters = array_merge($parameters, $oauth_parameters);
    unset($all_parameters['oauth_signature']); // Exclude oauth_signature from the base string

    // Collect and encode parameters
    $encoded_parameters = [];
    foreach ($all_parameters as $key => $value) {
        $encoded_parameters[rawurlencode($key)] = rawurlencode($value);
    }
    
    // Sort parameters
    ksort($encoded_parameters);

    // Create parameter string
    $parameter_string = '';
    foreach ($encoded_parameters as $key => $value) {
        $parameter_string .= $key . '=' . $value . '&';
    }
    $parameter_string = rtrim($parameter_string, '&');

    // Create base string
    $base_string = strtoupper($http_method) . '&' . rawurlencode($url) . '&' . rawurlencode($parameter_string);

    // Create signing key
    $signing_key = rawurlencode($consumer_secret) . '&' . rawurlencode($token_secret);

    // Generate signature
    $signature = base64_encode(hash_hmac('sha1', $base_string, $signing_key, true));

    return rawurlencode($signature);
}

// Given input values
$consumer_key = 'c5bb4dcb7bd6826c7c4340df3f791188';
$consumer_secret = '7d30246211192cda43ede3abd9b393b9';
$token = 'VbiNYl63EejjlKdQM6FeENzcnrLACrZ2JYD6NQROfVI=';
$token_secret = 'XCF9RzyQr4UEPloA+WlC06BnTfYC1P0Fwr3GUw/B0Es=';
$timestamp = '1344885636';
$nonce = '0bba225a40d1bbac2430aa0c6163ce44';
$http_method = 'GET';
$url = 'https://api.etrade.com/v1/accounts/list';
$parameters = [
    'oauth_timestamp' => $timestamp,
    'oauth_nonce' => $nonce
];

// Create the signature
$signature = create_oauth_signature($url, $http_method, $parameters, $consumer_key, $consumer_secret, $token, $token_secret);

echo "Generated Signature: " . $signature . PHP_EOL;


die(); 














































/*
function generate_oauth_signature($method, $url, $params, $xml_data, $consumer_secret, $token_secret) {
    // Add OAuth parameters
    $oauth_params = [
        'oauth_consumer_key' => $params['oauth_consumer_key'],
        'oauth_token' => $params['oauth_token'],
        'oauth_nonce' => bin2hex(random_bytes(16)),
        'oauth_timestamp' => time(),
        'oauth_signature_method' => 'HMAC-SHA1',
        'oauth_version' => '1.0'
    ];


echo "insider generate_oauth_signature, oauth_token is " . $params['oauth_token'] . "<br>"; 

    // Merge all parameters including XML data
    $all_params = array_merge($params, $oauth_params, ['xml_data' => $xml_data]);

    // Normalize parameters
    $encoded_params = [];
    foreach ($all_params as $key => $value) {
        $encoded_params[rawurlencode($key)] = rawurlencode($value);
    }

echo "insider generate_oauth_signature, encode_params is <br>";
var_dump($encoded_params); 

    ksort($encoded_params);
    $normalized_params = [];
    foreach ($encoded_params as $key => $value) {
        $normalized_params[] = "$key=$value";
    }
    $param_string = implode('&', $normalized_params);

    // Create signature base string
    $base_string = strtoupper($method) . '&' . rawurlencode($url) . '&' . rawurlencode($param_string);

    // Create signing key
    $signing_key = rawurlencode($consumer_secret) . '&' . rawurlencode($token_secret);

    // Generate signature
    $signature = base64_encode(hash_hmac('sha1', $base_string, $signing_key, true));

    // Add signature to OAuth parameters
    $oauth_params['oauth_signature'] = $signature;

    // Build the Authorization header string
    $auth_header = 'OAuth ';
    $auth_values = [];
    foreach ($oauth_params as $key => $value) {
        $auth_values[] = "$key=\"" . rawurlencode($value) . "\"";
    }
    $auth_header .= implode(', ', $auth_values);

    // Return the headers including the Authorization header
    return [
        'Content-Type: application/xml',
        'Authorization: ' . $auth_header
    ];
}








            

// API endpoint
$url = 'https://api.etrade.com/v1/accounts/S11DfWByF1AJIO-pGBEw-g/orders/preview';

//  HTTP method
$http_method = 'GET';

// consumer secret
$oauth_consumer_key =  'c5bb4dcb7bd6826c7c4340df3f791188';   // $ini_array['OAuth']['oauth_consumer_key']; 
$oauth_consumer_secret = '7d30246211192cda43ede3abd9b393b9'; // $ini_array['OAuth']['consumer_secret']; 
$oauth_token = 'VbiNYl63EejjlKdQM6FeENzcnrLACrZ2JYD6NQROfVI=';   $ini_array['OAuth']['oauth_token']; 
$oauth_token_secret = 'XCF9RzyQr4UEPloA+WlC06BnTfYC1P0Fwr3GUw/B0Es=';    $ini_array['OAuth']['oauth_token_secret'];

// Output OAuth signature


error_log("About to try"); 

try{

//    $request_params = new EquityOrderRequest();

//    $request_params->__set('accountId','706697');
//    $request_params->__set('clientOrderId','1');
//    $request_params->__set('symbol','AMIX');
//    $request_params->__set('priceType','LIMIT');
//    $request_params->__set('limitPrice',0.21);
//    $request_params->__set('quantity',2);
//    $request_params->__set('orderAction','BUY');
//    $request_params->__set('orderType', 'EQ'); 


//    $request_xml_object = new PreviewEquityOrder($request_params);


//echo "request_xml_object is:\n";

//var_dump($request_xml_object); 
//die();

                
//    $out    =   $ac_obj->previewEquityOrder($request_xml_object);





    // Create a new DOMDocument object
$xmlDoc = new DOMDocument('1.0', 'UTF-8');

// Create root element <PreviewOrderRequest>
$previewOrderRequest = $xmlDoc->createElement('PreviewOrderRequest');
$xmlDoc->appendChild($previewOrderRequest);

// Add child elements to <PreviewOrderRequest>
$orderType = $xmlDoc->createElement('orderType', 'EQ');
$previewOrderRequest->appendChild($orderType);

$clientOrderId = $xmlDoc->createElement('clientOrderId', '1');
$previewOrderRequest->appendChild($clientOrderId);

// Create <Order> element and its child elements
$order = $xmlDoc->createElement('Order');
$previewOrderRequest->appendChild($order);

$allOrNone = $xmlDoc->createElement('allOrNone', 'false');
$order->appendChild($allOrNone);

$priceType = $xmlDoc->createElement('priceType', 'LIMIT');
$order->appendChild($priceType);

$orderTerm = $xmlDoc->createElement('orderTerm', 'GOOD_FOR_DAY');
$order->appendChild($orderTerm);

$marketSession = $xmlDoc->createElement('marketSession', 'REGULAR');
$order->appendChild($marketSession);

$stopPrice = $xmlDoc->createElement('stopPrice');
$order->appendChild($stopPrice); // Note: This is empty as per your example

$limitPrice = $xmlDoc->createElement('limitPrice', '0.23');
$order->appendChild($limitPrice);

// Create <Instrument> element and its child elements
$instrument = $xmlDoc->createElement('Instrument');
$order->appendChild($instrument);

$product = $xmlDoc->createElement('Product');
$instrument->appendChild($product);

$securityType = $xmlDoc->createElement('securityType', 'EQ');
$product->appendChild($securityType);

$symbol = $xmlDoc->createElement('symbol', 'AMIX');
$product->appendChild($symbol);

$orderAction = $xmlDoc->createElement('orderAction', 'BUY');
$instrument->appendChild($orderAction);

$quantityType = $xmlDoc->createElement('quantityType', 'QUANTITY');
$instrument->appendChild($quantityType);

$quantity = $xmlDoc->createElement('quantity', '10');
$instrument->appendChild($quantity);

// Convert DOMDocument object to XML string
$xmlString = $xmlDoc->saveXML();



$params = [
    'oauth_consumer_key' => $oauth_consumer_key, 
    'oauth_token' => $oauth_token
]; 


echo "oauth_token is " . $oauth_token . "\n"; 


$headers = generate_oauth_signature('POST', $url, $params, $xmlString, $oauth_consumer_secret, $oauth_token_secret);


echo "headers is: \n";
var_dump($headers); 





// Using cURL
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $xmlString);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

print_r($response);

*/


die(); 



// Initialize cURL session
/*
$ch = curl_init();

// Set cURL options
curl_setopt($ch, CURLOPT_URL, 'https://api.etrade.com/v1/accounts/S11DfWByF1AJIO-pGBEw-g/orders/preview');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $xmlString);
curl_setopt($ch, CURLOPT_HTTPHEADER, $header); 

// Execute cURL session
$response = curl_exec($ch);

// Check for cURL errors
if(curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
}

// Close cURL session
curl_close($ch);

// Handle API response
if ($response === false) {
    echo 'cURL Error: Failed to execute API request';
} else {
    echo 'API Response: ' . $response;
}
*/




























/*
brent, beginning of commenting the entire thing out.
// This is the actual order placement

                // From orderRequestMain
//                 $request_params->__set('accountId',83600842);
                $request_params->__set('accountId',706697);
                                                   
                $request_params->__set('clientOrderId','1');
                $request_params->__set('symbol','AMIX');
                $request_params->__set('limitPrice',0.21);
                $request_params->__set('quantity',2);
                $request_params->__set('orderAction','BUY');
                $request_params->__set('priceType','LIMIT');
                $request_params->__set('orderType', 'EQ'); 

error_log("Just set the request_params"); 

//                $request_params->__set('previewId','');
//                $request_params->__set('stopPrice',300); 
            
                // From basicOrderRequest
//                 $request_params->__set('allOrNone','');

//                $request_params->__set('reserveOrder','');
//                $request_params->__set('reserveQuantity',0);
            
                // From EquityOrderRequest
                
//                $request_params->__set('stopLimitPrice','');
//                $request_params->__set('symbol','GE');
//                $request_params->__set('orderAction','SELL'); //{BUY,   SELL,    BUY_TO_COVER,    SELL_SHORT'}
//                $request_params->__set('priceType','MARKET');
                

//                $request_params->__set('stopLimitPrice','');
//                $request_params->__set('symbol','AAPL');
//                $request_params->__set('orderAction','BUY'); //{BUY,   SELL,    BUY_TO_COVER,    SELL_SHORT'}
//                $request_params->__set('priceType','LIMIT');// { MARKET,    LIMIT,  STOP,   STOP_LIMIT, MARKET_ON_CLOSE'}


//                $request_params->__set('routingDestination','');
//                $request_params->__set('marketSession','REGULAR');// { REGULAR, EXTENDED }
//                $request_params->__set('orderTerm','GOOD_FOR_DAY'); //{ GOOD_UNTIL_CANCEL,GOOD_FOR_DAY,IMMEDIATE_OR_CANCEL,FILL_OR_KILL}
    
                
                $request_xml_object = new PlaceEquityOrder($request_params);

var_dump($request_xml_object); 

                $out    = $ac_obj->placeEquityOrder($request_xml_object);

error_log("out is " . $out); 

brent, end of commenting the entire thing out 
*/ 



/*
    }catch(ETWSException $e){
//         $h2t  = new html2text($e->getErrorMessage());
//        $msgtxt = $h2t->get_text(); 
        echo    "***Caught exception***  \n".
                "Error Code     : " . $e->getErrorCode()."\n" .
                "Error Message  : " . $e->getErrorMessage() . "\n" ;
        if(DEBUG_MODE) echo $e->getTraceAsString() . "\n" ;
        exit;
    }catch(Exception $e){
        echo    "***Caught exception***  \n".
                "Error Code     : " . $e->getCode()."\n" .
                "Error Message  : " . $e->getMessage() . "\n" ;
        if(DEBUG_MODE) echo $e->getTraceAsString() . "\n" ;
        echo "Exiting...\n";
        exit;

    }
    echo "==============Response==================";
    error_log($out);
    echo "============== Response End==================";


*/


?>