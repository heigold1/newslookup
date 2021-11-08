<?php
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

include './Samples/config.php';
include './Common/Common.php';
include './OAuth/etOAuth.class.php';
include './Orders/equityOrderRequest.class.php'; 
include './Orders/orderClient.class.php'; 


/*
$file_name = dirname(__FILE__);
echo $file_name; die(); */

$symbol=$_GET['symbol'];

$dataArray = array();

$ini_array = parse_ini_file('etrade.ini', true);

//$consumer   = new etOAuthConsumer(ETWS_APP_KEY,ETWS_APP_SECRET);
$consumer = new etOAuthConsumer($ini_array['OAuth']['oauth_consumer_key'],$ini_array['OAuth']['consumer_secret']);

$consumer->oauth_token      = $ini_array['OAuth']['oauth_token'];
$consumer->oauth_token_secret   = $ini_array['OAuth']['oauth_token_secret'];
$ac_obj = new MarketClient($consumer);



$orderRequestObject = new EquityOrderRequest(); 
$orderRequestObject->accountId = '3524-0027'; 
$orderRequestObject->symbol = 'GLFH'; // no-name pink sheet stock. 
$orderRequestObject->orderAction = 'BUY'; 
$orderRequestObject->quantity = 500; 
$orderRequestObject->priceType = 'LIMIT'; 
$orderRequestObject->marketSession = 'EXTENDED'; 
$orderRequestObject->orderTerm = 'GOOD_FOR_DAY'; 


$orderClient = new OrderClient($consumer); 








?>
