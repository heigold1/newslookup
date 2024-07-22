<?php 

error_reporting(E_ALL);

$fromDaysBack = 100;
$toDaysBack = 1; 
$symbol=$_GET['symbol'];

fopen("cookies.txt", "w");


function getTradeDate($daysBack)
{
    $trade_date = "";

    $trade_date = date('Y-m-d', strtotime("-" . $daysBack . " days"));

    return $trade_date;
}

function secondsToTime($seconds) {
    $dtF = new \DateTime('@0');
    $dtT = new \DateTime("@$seconds");
    return $dtF->diff($dtT)->format('%a');
}

function dateDifference($date)
{
    $currentDate = new DateTime(); 
    $currentDateTimeStamp = $currentDate->format('U'); 

    return secondsToTime($currentDateTimeStamp - $date); 

}

function grabHTML($function_host_name, $url)
{

$ch = curl_init();
$header=array('GET /1575051 HTTP/1.1',
    "Host: $function_host_name",
    'Accept:text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
    'Accept-Language:en-US,en;q=0.8',
    'Cache-Control:max-age=0',
    'Connection:keep-alive',
    'User-Agent:Mozilla/5.0 (Macintosh; Intel Mac OS X 10_8_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/27.0.1453.116 Safari/537.36',
    );

    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
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


$fromDate = getTradeDate($fromDaysBack); 
$toDate = getTradeDate($toDaysBack); 
$returnArray = array();

/*echo "test";
die();  */ 

$url = "https://api.marketstack.com/v1/eod?access_key=d36ab142bed5a1430fcde797063f6b9a&symbols=" . $symbol . "&date_from=" . $fromDate . "&date_to=" . $toDate; 

$results = grabHTML("api.marketstack.com", $url);

$fullJSON = json_decode($results);

$fiveDayVolume = 0.0; 


if (isset($fullJSON->data[0]->volume))
{
    $returnArray['yest_volume'] = (int)$fullJSON->data[0]->volume;
}
else
{
    $returnArray['yest_volume'] = 'not_found';
}

if (isset($fullJSON->data[1]->close))
{
  $day_1_percentage = (($fullJSON->data[0]->close - $fullJSON->data[1]->close)/$fullJSON->data[1]->close)*100; 
  $day_1_percentage_low = (($fullJSON->data[0]->low - $fullJSON->data[1]->close)/$fullJSON->data[1]->close)*100; 
      $returnArray['day_1'] = number_format((float)$day_1_percentage, 2, '.', '');
      $returnArray['day_1_low'] = number_format((float)$day_1_percentage_low, 2, '.', '');

      if ($fullJSON->data[1]->volume != "")
      {
          $returnArray['day_1_volume'] = number_format($fullJSON->data[0]->volume/$fullJSON->data[1]->volume, 2); 
      }
      else  
      {
          $returnArray['day_1_volume'] = 'DIV0'; 
      }  

      $returnArray['day_1_total_volume'] = number_format($fullJSON->data[0]->volume); 

      $returnArray['day_1_recovery'] = number_format((($fullJSON->data[0]->close - $fullJSON->data[0]->low)/$fullJSON->data[0]->low)*100, 2); 
      $fiveDayVolume += floatval($fullJSON->data[0]->volume); 
}
else
{
  $returnArray['day_1'] = "N/A";
}

if (isset($fullJSON->data[2]->close))
{
  $day_1_percentage = (($fullJSON->data[1]->close - $fullJSON->data[2]->close)/$fullJSON->data[2]->close)*100; 
  $day_1_percentage_low = (($fullJSON->data[1]->low - $fullJSON->data[2]->close)/$fullJSON->data[2]->close)*100; 
      $returnArray['day_2'] = number_format((float)$day_1_percentage, 2, '.', '');
      $returnArray['day_2_low'] = number_format((float)$day_1_percentage_low, 2, '.', '');

      if ($fullJSON->data[2]->volume != "")
      {
          $returnArray['day_2_volume'] = number_format($fullJSON->data[1]->volume/$fullJSON->data[2]->volume, 2); 
      }
      else  
      {
          $returnArray['day_2_volume'] = 'DIV0'; 
      }  

      $returnArray['day_2_total_volume'] = number_format($fullJSON->data[1]->volume); 
      $fiveDayVolume += floatval($fullJSON->data[1]->volume); 
}
else
{
  $returnArray['day_2'] = "N/A";
}

if (isset($fullJSON->data[3]->close))
{
  $day_1_percentage = (($fullJSON->data[2]->close - $fullJSON->data[3]->close)/$fullJSON->data[3]->close)*100; 
  $day_1_percentage_low = (($fullJSON->data[2]->low - $fullJSON->data[3]->close)/$fullJSON->data[3]->close)*100; 
      $returnArray['day_3'] = number_format((float)$day_1_percentage, 2, '.', '');
      $returnArray['day_3_low'] = number_format((float)$day_1_percentage_low, 2, '.', '');

      if ($fullJSON->data[3]->volume != "")
      {
          $returnArray['day_3_volume'] = number_format($fullJSON->data[2]->volume/$fullJSON->data[3]->volume, 2); 
      }
      else  
      {
          $returnArray['day_3_volume'] = 'DIV0'; 
      }  

      $returnArray['day_3_total_volume'] = number_format($fullJSON->data[2]->volume); 
      $fiveDayVolume += floatval($fullJSON->data[2]->volume); 
}
else
{
  $returnArray['day_3'] = "N/A";
}

if (isset($fullJSON->data[4]->close))
{
  $day_1_percentage = (($fullJSON->data[3]->close - $fullJSON->data[4]->close)/$fullJSON->data[4]->close)*100; 
  $day_1_percentage_low = (($fullJSON->data[3]->low - $fullJSON->data[4]->close)/$fullJSON->data[4]->close)*100; 
      $returnArray['day_4'] = number_format((float)$day_1_percentage, 2, '.', '');
      $returnArray['day_4_low'] = number_format((float)$day_1_percentage_low, 2, '.', '');

      if ($fullJSON->data[4]->volume != "")
      {
          $returnArray['day_4_volume'] = number_format($fullJSON->data[3]->volume/$fullJSON->data[4]->volume, 2); 
      }
      else  
      {
          $returnArray['day_4_volume'] = 'DIV0'; 
      }  

      $returnArray['day_4_total_volume'] = number_format($fullJSON->data[3]->volume); 
      $fiveDayVolume += floatval($fullJSON->data[3]->volume); 
}
else
{
  $returnArray['day_4'] = "N/A";
}

if (isset($fullJSON->data[5]->close))
{
  $day_1_percentage = (($fullJSON->data[4]->close - $fullJSON->data[5]->close)/$fullJSON->data[5]->close)*100; 
  $day_1_percentage_low = (($fullJSON->data[4]->low - $fullJSON->data[5]->close)/$fullJSON->data[5]->close)*100; 
      $returnArray['day_5'] = number_format((float)$day_1_percentage, 2, '.', '');
      $returnArray['day_5_low'] = number_format((float)$day_1_percentage_low, 2, '.', '');

      if ($fullJSON->data[5]->volume != "")
      {
          $returnArray['day_5_volume'] = number_format($fullJSON->data[4]->volume/$fullJSON->data[5]->volume, 2); 
      }
      else  
      {
          $returnArray['day_5_volume'] = 'DIV0'; 
      }  

      $returnArray['day_5_total_volume'] = number_format($fullJSON->data[4]->volume); 
       $fiveDayVolume += floatval($fullJSON->data[4]->volume); 
}
else
{
  $returnArray['day_5'] = "N/A";
}

$returnArray['five_day_average_volume'] = $fiveDayVolume/5.0; 

$latestDay = intval($fullJSON->pagination->count) - 1; 

if (isset($fullJSON->pagination->count))
{
    $latestDay = strtotime($fullJSON->data[$latestDay]->date); 
    $daysOld = dateDifference($latestDay); 

    if ($daysOld < 30)
    {
        $returnArray['new_stock'] = true; 
    }
    else
    {
        $returnArray['new_stock'] = false;
    }
    $returnArray['count'] = $fullJSON->pagination->count; 
}
else
{
    $returnArray['count'] = 0;  
    $returnArray['new_stock'] = true; 
}

echo json_encode($returnArray);

?>