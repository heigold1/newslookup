<?php 

error_reporting(0);


// header('Content-type: text/html');
$symbol=$_GET['symbol'];

fopen("cookies.txt", "w");



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



$url = "https://www.alphavantage.co/query?function=TIME_SERIES_DAILY&symbol=" . $symbol . "&apikey=RH5DAZF0ZQX2LSE6"; 

//$results = curl_get_contents($url); 
$results = grabHTML("www.alphavantage.co", $url);

$fullJSON = json_decode($results);

$object = null;

foreach($fullJSON as $k => $v){
   if ($k == "Time Series (Daily)")
   {
    $object = $v; 
   }
}

$counter = 0; 
$historicalDataArray = array();
$returnArray = array();

foreach($object as $k => $v){
  $dailyObject = new stdClass();

  foreach ($v as $key => $value)
  {
    if ($key == '1. open')
    {
      $dailyObject->open = $value; 
    }
    elseif ($key == '2. high')
    {
      $dailyObject->high = $value; 
    }
    elseif ($key == '3. low')
    {
      $dailyObject->low = $value; 
    }
    elseif ($key == '4. close')
    {
      $dailyObject->close = $value; 
    }
  }

  $historicalDataArray[$counter] = $dailyObject; 

  if ($counter >= 7)
  {
    break;
  }
  $counter++;
}

if (isset($historicalDataArray[1]->close))
{
  $day_1_percentage = (($historicalDataArray[0]->close - $historicalDataArray[1]->close)/$historicalDataArray[1]->close)*100; 
    $returnArray['day_1'] = number_format((float)$day_1_percentage, 2, '.', '');
}
else
{
  $returnArray['day_1'] = "N/A";
}

if (isset($historicalDataArray[2]->close))
{
  $day_2_percentage = (($historicalDataArray[1]->close - $historicalDataArray[2]->close)/$historicalDataArray[2]->close)*100; 
    $returnArray['day_2'] = number_format((float)$day_2_percentage, 2, '.', '');
}
else
{
  $returnArray['day_2'] = "N/A";
}

if (isset($historicalDataArray[3]->close))
{
  $day_3_percentage = (($historicalDataArray[2]->close - $historicalDataArray[3]->close)/$historicalDataArray[3]->close)*100; 
    $returnArray['day_3'] = number_format((float)$day_3_percentage, 2, '.', '');
}
else
{
  $returnArray['day_3'] = "N/A";
}

if (isset($historicalDataArray[4]->close))
{
  $day_4_percentage = (($historicalDataArray[3]->close - $historicalDataArray[4]->close)/$historicalDataArray[4]->close)*100; 

    $returnArray['day_4'] = number_format((float)$day_4_percentage, 2, '.', '');
}
else
{
  $returnArray['day_4'] = "N/A";
}

if (isset($historicalDataArray[5]->close))
{
  $day_5_percentage = (($historicalDataArray[4]->close - $historicalDataArray[5]->close)/$historicalDataArray[5]->close)*100; 
     $returnArray['day_5'] = number_format((float)$day_5_percentage, 2, '.', '');
}
else
{
  $returnArray['day_5'] = "N/A";
}

echo json_encode($returnArray);
