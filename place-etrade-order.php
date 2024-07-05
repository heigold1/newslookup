<?php
/**
 * E*TRADE PHP SDK
 *
 * @package    	PHP-SDK
 * @version		1.1
 * @copyright  	Copyright (c) 2012 E*TRADE FINANCIAL Corp.
 *
 */

require_once("config.php");
require_once(dirname(__FILE__) . '\Common\Common.php');
require_once(dirname(__FILE__) . '\Orders\OrderClient.class.php');

$logFile = '\place-etrade-order.log';

$orderString=$_GET['orderString'];

// $dataArray = array();

$ini_array = parse_ini_file('etrade.ini', true);

//$consumer   = new etOAuthConsumer(ETWS_APP_KEY,ETWS_APP_SECRET);
$consumer = new etOAuthConsumer($ini_array['OAuth']['oauth_consumer_key'],$ini_array['OAuth']['consumer_secret']);

$consumer->oauth_token      = $ini_array['OAuth']['oauth_token'];
$consumer->oauth_token_secret   = $ini_array['OAuth']['oauth_token_secret'];

$ac_obj = new OrderClient($consumer);

$request_params = new EquityOrderRequest();
            

echo "<pre>"; 


error_log("About to try"); 

try{

                /* From orderRequestMain. */
//                 $request_params->__set('accountId',83600842);
                $request_params->__set('accountId',706697);
                                                   
//                 $request_params->__set('clientOrderId','asdfdsa12312');
                $request_params->__set('symbol','AMIX');
                $request_params->__set('limitPrice',0.21);
                $request_params->__set('quantity',2);
                $request_params->__set('orderAction','BUY');
                $request_params->__set('priceType','LIMIT');

error_log("Just set the request_params"); 

/*                $request_params->__set('previewId','');
                $request_params->__set('stopPrice',300);  */
            
                /* From basicOrderRequest. */
//                 $request_params->__set('allOrNone','');

//                $request_params->__set('reserveOrder','');
//                $request_params->__set('reserveQuantity',0);
            
                /* From EquityOrderRequest */
                
//                $request_params->__set('stopLimitPrice','');
//                $request_params->__set('symbol','GE');
//                $request_params->__set('orderAction','SELL'); //{BUY,   SELL,    BUY_TO_COVER,    SELL_SHORT'}
//                $request_params->__set('priceType','MARKET');
                
                /* 
                $request_params->__set('stopLimitPrice','');
                $request_params->__set('symbol','AAPL');
                $request_params->__set('orderAction','BUY'); //{BUY,   SELL,    BUY_TO_COVER,    SELL_SHORT'}
                $request_params->__set('priceType','LIMIT');// { MARKET,    LIMIT,  STOP,   STOP_LIMIT, MARKET_ON_CLOSE'}
                 */
//                $request_params->__set('routingDestination','');
//                $request_params->__set('marketSession','REGULAR');// { REGULAR, EXTENDED }
//                $request_params->__set('orderTerm','GOOD_FOR_DAY'); //{ GOOD_UNTIL_CANCEL,GOOD_FOR_DAY,IMMEDIATE_OR_CANCEL,FILL_OR_KILL}
    
                
                $request_xml_object = new PlaceEquityOrder($request_params);

var_dump($request_xml_object); 





                $out    = $ac_obj->placeEquityOrder($request_xml_object);

error_log("out is " . $out); 

    }catch(ETWSException $e){
        /* $h2t  = new html2text($e->getErrorMessage());
        $msgtxt = $h2t->get_text(); */
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




?>