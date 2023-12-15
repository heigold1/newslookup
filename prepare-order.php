<?php 

header("Access-Control-Allow-Origin: *");

/**
 * E*TRADE PHP SDK
 *
 * @package     PHP-SDK
 * @version   1.1
 * @copyright   Copyright (c) 2012 E*TRADE FINANCIAL Corp.
 *
 */
/*require_once("config.php");
require_once(dirname(__FILE__) . '/../Common/Common.php');
require_once 'Market/MarketClient.class.php';  */

//require_once("config.php");

/*require_once(dirname(__FILE__) . '/Samples/Config.php');
require_once(dirname(__FILE__) . '/Common/Common.php');
require_once(dirname(__FILE__) . '/OAuth/etOAuth.class.php');
require_once(dirname(__FILE__) . '/Market/MarketClient.class.php'); */

include '../newslookup/Samples/config.php';
include '../newslookup/Common/Common.php';
include '../newslookup/OAuth/etOAuth.class.php';
include '../newslookup/Market/MarketClient.class.php';

$symbol=$_GET['symbol'];
$amount = $_GET['amount']; 
$percentage = $_GET['percentage']; 


/*
$file_name = dirname(__FILE__);
echo $file_name; die(); */

$symbol=$_GET['symbol'];

$dataArray = array();

$ini_array = parse_ini_file('../newslookup/etrade.ini', true);

//$consumer   = new etOAuthConsumer(ETWS_APP_KEY,ETWS_APP_SECRET);
$consumer = new etOAuthConsumer($ini_array['OAuth']['oauth_consumer_key'],$ini_array['OAuth']['consumer_secret']);

$consumer->oauth_token      = $ini_array['OAuth']['oauth_token'];
$consumer->oauth_token_secret   = $ini_array['OAuth']['oauth_token_secret'];

$ac_obj = new MarketClient($consumer);

  try{
          
    $request_params = new getQuoteParams();

    //    $request_params->__set('symbolList', array('GOOG','CSCO'));
    $request_params->__set('symbolList', array($symbol));
    $request_params->__set('afterhourFlag', true);
    $request_params->__set('detailFlag', 'All');

    $out  = $ac_obj->getQuote($request_params);

  }catch(ETWSException $e){
    echo  "***Caught exception*** 1 \n".
        "Error Code   : " . $e->getErrorCode()."\n" .
        "Error Message  : " . $e->getErrorMessage() . "\n" ;
    if(DEBUG_MODE) echo $e->getTraceAsString() . "\n" ;
    exit;
  }catch(Exception $e){
    echo  "***Caught exception*** 2 \n".
        "Error Code   : " . $e->getCode()."\n" .
        "Error Message  : " . $e->getMessage() . "\n" ;
    if(DEBUG_MODE) echo $e->getTraceAsString() . "\n" ;
    echo "Exiting...\n";
    exit;

  }

//  $mkt_responce_obj = json_decode($out);   // USE THIS ON THE Verio Server 
// $mkt_response_obj = etHttpUtils::GetResponseObject($out);   // Use this on localhost

$mkt_response_obj = simplexml_load_string($out);

if (isset($mkt_response_obj->QuoteData->All->previousClose))
{
	$prevClose = floatval($mkt_response_obj->QuoteData->All->previousClose);

	$price = $prevClose - $prevClose*floatVal(floatval($percentage)/100); 

	if ($price > 1.00)
	{
		$price = number_format($price, 2, '.', ''); 
	}
	else
	{
    $price = number_format($price, 4, '.', ''); 
	}

	$numShares = floatVal($amount)/$price; 
	$numShares = floor($numShares/100)*100; 

	// There is some kind of regulation that states that you can't place an order for more than 
	// 118,500 shares. 
	if ($numShares > 118500) 
	{
		$numShares = 118500; 
	}

	$orderStub = $symbol . " BUY " . $numShares . " $" . $price . " (" . $percentage . "%)"; 
	echo $orderStub; 
}
else
{
	echo "NO PREVIOUS CLOSE LISTED"; 
}

?>