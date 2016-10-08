<?php

function get_date_two_weeks_ago()
{
  $date_two_weeks_ago = date('Y-m-d', strtotime("-14 days"));
  return $date_two_weeks_ago;
}

function get_todays_date()
{
  $todays_date = date('Y-m-d');
  return $todays_date;
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


$symbol=$_GET['symbol'];

$dataArray = array();

        $yahooFinanceAPIURL = "http://query.yahooapis.com/v1/public/yql?q=select%20*%20from%20%20%20yahoo.finance.historicaldata%20where%20%20symbol%20%20%20%20=%20%22" . $symbol . "%22and%20%20%20%20startDate%20=%20%22" . get_date_two_weeks_ago() . "%22%20and%20%20%20%20endDate%20%20%20=%20%22" . get_todays_date() . "%22&format=json&diagnostics=true&env=store://datatables.org/alltableswithkeys&callback="; 

        $result = grabHTML($yahooFinanceAPIURL);

        $result_json = json_decode($result);

        if (isset($result_json->query->results))
        {
          $historicalDataArray = $result_json->query->results->quote;

          $day_1_percentage = (($historicalDataArray[1]->Close - $historicalDataArray[0]->Low)/$historicalDataArray[1]->Close)*100; 
          if ($day_1_percentage > 1)
          {
            $dataArray['day_1'] = number_format((float)$day_1_percentage, 2, '.', '');
          }
          else
          {
            $dataArray['day_1'] = "N/A";
          }

          $day_2_percentage = (($historicalDataArray[2]->Close - $historicalDataArray[1]->Low)/$historicalDataArray[2]->Close)*100; 
          if ($day_2_percentage > 1)
          {
            $dataArray['day_2'] = number_format((float)$day_2_percentage, 2, '.', '');
          }
          else
          {
            $dataArray['day_2'] = "N/A";
          }

          $day_3_percentage = (($historicalDataArray[3]->Close - $historicalDataArray[2]->Low)/$historicalDataArray[3]->Close)*100; 
          if ($day_3_percentage > 1)
          {
            $dataArray['day_3'] = number_format((float)$day_3_percentage, 2, '.', '');
          }
          else
          {
            $dataArray['day_3'] = "N/A";
          }

          $day_4_percentage = (($historicalDataArray[4]->Close - $historicalDataArray[3]->Low)/$historicalDataArray[4]->Close)*100; 
          if ($day_4_percentage > 1)
          {
            $dataArray['day_4'] = number_format((float)$day_4_percentage, 2, '.', '');
          }
          else
          {
            $dataArray['day_4'] = "N/A";
          }

          $day_5_percentage = (($historicalDataArray[5]->Close - $historicalDataArray[4]->Low)/$historicalDataArray[5]->Close)*100; 
          if ($day_5_percentage > 1)
          {
            $dataArray['day_5'] = number_format((float)$day_5_percentage, 2, '.', '');
          }
          else
          {
            $dataArray['day_5'] = "N/A";
          }
      }
      else
      {
        $dataArray['day_1'] = "--";
        $dataArray['day_2'] = "--";
        $dataArray['day_3'] = "--";
        $dataArray['day_4'] = "--";
        $dataArray['day_5'] = "--";
      }

      echo json_encode($dataArray);

?>
