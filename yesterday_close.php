<?php
/**
 * E*TRADE PHP SDK
 *
 * @package    	PHP-SDK
 * @version		1.1
 * @copyright  	Copyright (c) 2012 E*TRADE FINANCIAL Corp.
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

include './Samples/Config.php';
include './Common/Common.php';
include './OAuth/etOAuth.class.php';
include './Market/MarketClient.class.php';

/*
$file_name = dirname(__FILE__);
echo $file_name; die(); */

$symbol=$_GET['symbol'];

$ini_array = parse_ini_file('etrade.ini', true);

//$consumer 	= new etOAuthConsumer(ETWS_APP_KEY,ETWS_APP_SECRET);
$consumer = new etOAuthConsumer($ini_array['OAuth']['oauth_consumer_key'],$ini_array['OAuth']['consumer_secret']);
 
$consumer->oauth_token 			= $ini_array['OAuth']['oauth_token'];
$consumer->oauth_token_secret 	= $ini_array['OAuth']['oauth_token_secret'];

$ac_obj	= new MarketClient($consumer);

	try{
					
		$request_params	= new getQuoteParams();
		//		$request_params->__set('symbolList', array('GOOG','CSCO'));
		$request_params->__set('symbolList', array($symbol));
		$request_params->__set('detailFlag', 'All');
		$out 	= $ac_obj->getQuote($request_params);

	}catch(ETWSException $e){
		echo 	"***Caught exception***  \n".
				"Error Code 	: " . $e->getErrorCode()."\n" .
				"Error Message 	: " . $e->getErrorMessage() . "\n" ;
		if(DEBUG_MODE) echo $e->getTraceAsString() . "\n" ;
		exit;
	}catch(Exception $e){
		echo 	"***Caught exception***  \n".
				"Error Code 	: " . $e->getCode()."\n" .
				"Error Message 	: " . $e->getMessage() . "\n" ;
		if(DEBUG_MODE) echo $e->getTraceAsString() . "\n" ;
		echo "Exiting...\n";
		exit;

	}

	$mkt_responce_obj = json_decode($out); //etHttpUtils::GetResponseObject($out);

	$current_time = date('Gis', strtotime("-3 hours")); 

  	if ($current_time > 130000)  // 1:00 PM 
  	{
  		if (isset($mkt_responce_obj->quoteResponse->quoteData->all->lastTrade))
  		{
  			$last_trade = (string) $mkt_responce_obj->quoteResponse->quoteData->all->lastTrade;
			$low = (string) $mkt_responce_obj->quoteResponse->quoteData->all->low;

  			if (preg_match('/E-4/', $last_trade))
  			{
  				$last_trade = floatval(preg_replace('/E-4/', '', $last_trade))/10000;
  			}
  			echo '{"prev_close":"' . $last_trade . '", ' . 
  				 ' "low":"' . $low . '"}';
		}
		else
  		{
			echo "------";
		}

  	}
  	else
  	{
  		if (isset($mkt_responce_obj->quoteResponse->quoteData->all->prevClose))
  		{
  			$prev_close = (string) $mkt_responce_obj->quoteResponse->quoteData->all->prevClose;
			$low = (string) $mkt_responce_obj->quoteResponse->quoteData->all->low;
  			if (preg_match('/E-4/', $prev_close))
  			{
  				$prev_close = floatval(preg_replace('/E-4/', '', $prev_close))/10000;
  			}
  			echo '{"prev_close":"' . $prev_close . '", ' . 
  				 ' "low":"' . $low . '"}';
		}
		else
		{
			echo "------";
		}
  	}

?>
