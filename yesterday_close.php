<?php 


function get_friday_trade_date()
{
    $friday_trade_date = date('Y-m-d', strtotime("-75 hours"));
    return $friday_trade_date;
}

function get_yesterday_trade_date()
{
    $yesterday_trade_date = date('Y-m-d', strtotime("-27 hours"));
    return $yesterday_trade_date;
}



function grabHTML($url)
{

$ch = curl_init();
$header=array('GET /1575051 HTTP/1.1',
    "Host: query.yahooapis.com",
    'Accept:text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
    'Accept-Language:en-US,en;q=0.8',
    'Cache-Control:max-age=0',
    'Connection:keep-alive',
    'User-Agent:Mozilla/5.0 (Macintosh; Intel Mac OS X 10_8_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/27.0.1453.116 Safari/537.36',
    );

    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($ch,CURLOPT_CONNECTTIMEOUT, 300);
    curl_setopt( $ch, CURLOPT_COOKIESESSION, true );
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

    curl_setopt($ch,CURLOPT_COOKIEFILE,'cookies.txt');
    curl_setopt($ch,CURLOPT_COOKIEJAR,'cookies.txt');
    curl_setopt($ch,CURLOPT_HTTPHEADER,$header);



	curl_setopt($ch, CURLOPT_VERBOSE, true);
	curl_setopt($ch, CURLOPT_STDERR,$f = fopen(__DIR__ . "/error.log", "w+"));
	

    $returnHTML = curl_exec($ch); 

if($errno = curl_errno($ch)) {
    $error_message = curl_strerror($errno);
    echo "cURL error ({$errno}):\n {$error_message}";
}   
   curl_close($ch);
    return $returnHTML; 
} // end of function grabHTML

    $current_time = date('Gis', strtotime("-9 hours")); 
  	$symbol='MSFT'; // $_GET['symbol'];
  	$date_parameter = "";

  	if ($current_time > 130000)  // 1:00 PM 
  	{
  		// if it's after 1:00 (closing bell) then we go with today's date 
  		$date_parameter = date('Y-m-d', strtotime("-3 hours"));
  	}
  	else
  	{
  		// if it's still before 1:00 (closing bell) then we are using yesterday's date
		$todays_date_weekday = date('l', strtotime("-3 hours"));   //   h:i:s A 
    	if ($todays_date_weekday == "Monday")
    	{
      		$date_parameter = get_friday_trade_date();
    	}
    	else
    	{
	      	$date_parameter = get_yesterday_trade_date();
    	}

  	}


	$fullURL = "http://query.yahooapis.com/v1/public/yql?q=select%20*%20from%20%20%20yahoo.finance.historicaldata%20where%20%20symbol%20%20%20%20=%20%22" . $symbol . "%22and%20%20%20%20startDate%20=%20%22" . $date_parameter . "%22%20and%20%20%20%20endDate%20%20%20=%20%22" . $date_parameter . "%22&format=json&diagnostics=true&env=store://datatables.org/alltableswithkeys&callback="; 

	$result = grabHTML($fullURL);

	$result_json = json_decode($result);
	if (isset($result_json->query->results))
	{
	  	echo $result_json->query->results->quote->Close;
	}
	else
	{
	  	echo "------";
	}


?>