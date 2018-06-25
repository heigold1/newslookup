<?php 

/* 
oauth_consumer_key: 874c996f1f6ecaa46c65abb115da9912
consumer_secret: 886529f1c9d06729e97b6f511a89b4df
*/

include 'config.php';

require_once("simple_html_dom.php"); 
error_reporting(0);


// header('Content-type: text/html');
$symbol=$_GET['symbol'];

fopen("cookies.txt", "w");


function get_friday_trade_date()
{
    $friday_yahoo_trade_date = "";

    $friday_yahoo_trade_month_day = date('Y-m-d', strtotime("-3 days"));
    $friday_yahoo_trade_date = $friday_yahoo_trade_month_day;
 
//    $friday_yahoo_trade_date = preg_replace('/\, 0([1-9])/', '$1', $friday_yahoo_trade_date);
 
    return $friday_yahoo_trade_date;
}

function get_saturday_trade_date()
{
    $saturday_yahoo_trade_date = "";

    $saturday_yahoo_trade_month_day = date('Y-m-d', strtotime("-2 days"));
    $saturday_yahoo_trade_date = $saturday_yahoo_trade_month_day;
 
//    $saturday_yahoo_trade_date = preg_replace('/\, 0([1-9])/', '$1', $saturday_yahoo_trade_date);

    return $saturday_yahoo_trade_date;
}

function get_yesterday_trade_date()
{
    $yesterday_yahoo_trade_date = "";

    $yesterday_yahoo_trade_month_day = date('Y-m-d', strtotime("-1 days"));
    $yesterday_yahoo_trade_date = $yesterday_yahoo_trade_month_day;
 
//    $yesterday_yahoo_trade_date = preg_replace('/\, 0([1-9])/', '$1', $yesterday_yahoo_trade_date);

    return $yesterday_yahoo_trade_date;
}

function get_today_trade_date()
{
    $todays_yahoo_trade_date = "";

    $todays_yahoo_trade_month_day = date('Y-m-d');
    $todays_yahoo_trade_date = $todays_yahoo_trade_month_day;
 
//    $todays_yahoo_trade_date = preg_replace('/\, 0([1-9])/', '$1', $todays_yahoo_trade_date);

    return $todays_yahoo_trade_date;
}



function grabEtradeHTML($etrade_host_name, $url)
{



$ch = curl_init();
$header=array('GET /1575051 HTTP/1.1',
"Host: www.etrade.wallst.com",
'Accept:text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
'Accept-Language:en-US,en;q=0.8',
  'Cookie: oda_bsid=386242%3A%3A@@*; 1432%5F0=C3D1E46964D0597C69BAB2D9F8A3652F', // Saturday at 3:09 PM - this worked
'Cache-Control:max-age=0',
'Connection:keep-alive',
'User-Agent:Mozilla/5.0 (Macintosh; Intel Mac OS X 10_8_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/27.0.1453.116 Safari/537.36',
);

curl_setopt($ch,CURLOPT_URL, $url);
curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,0);
curl_setopt( $ch, CURLOPT_COOKIESESSION, true );

    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

// curl_setopt ($ch, CURLOPT_POSTFIELDS, "username=michael@moderneveryday.com&password=modern1213");

curl_setopt($ch,CURLOPT_COOKIEFILE,'cookies.txt');
curl_setopt($ch,CURLOPT_COOKIEJAR,'cookies.txt');
curl_setopt($ch,CURLOPT_HTTPHEADER,$header);

$returnHTML = curl_exec($ch);


return $returnHTML;

} // end of function grabEtradeHTML


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

//    curl_setopt($ch, CURLOPT_USERPWD, "heigold1:heimer27");
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

$ret = "";
$finalReturn = "";

// echo "symbol is " . $symbol . "\n"; 

      $url = "https://www.sec.gov/cgi-bin/browse-edgar?CIK=" . $symbol . "&owner=exclude&action=getcompany&Find=Search"; 
      $result = grabHTML('www.sec.gov', $url); 
      $html = str_get_html($result);

      echo "<table class='striped' border = 1>"; 

          $tableRow1 = $html->find('.tableFile2 tbody tr'); 

          for ($i = 1; $i < 6; $i++)
           { 
              $row = str_get_html($tableRow1[$i]);
              $td = $row->find('td'); 
              $linkTd = $td[1]->find('a');  

              $td0 = $td[0]; 
              $td2 = $td[2]->plaintext;

              $td2 = preg_replace('/Acc-no.*MB/', '', $td2);
              $td2 = preg_replace('/Acc-no.*KB/', '', $td2);
              $td3 = $td[3];

                  $firstLink  = 'https://www.sec.gov' . $linkTd[0]->href; 
                  $firstLinkResults = grabHTML('www.sec.gov', $firstLink); 
                  $html2 = str_get_html($firstLinkResults);
                  $tableRow2 = $html2->find('tr'); 
                  $td2nd = $tableRow2[1]->find('td'); 
                  $a2 = $td2nd[2]->find('a');
                  $href2 = 'https://www.sec.gov' . $a2[0]->href;


                  $todays_date = date('l'); 
                  if ($todays_date == "Monday")
                  {
                    $td3 = preg_replace('/(' .  get_friday_trade_date() . ')/', '<span style="font-size: 16px; background-color:#000080 ; color:white">$1</span>', $td3);
                    $td3 = preg_replace('/(' .  get_saturday_trade_date() . ')/', '<span style="font-size: 16px; background-color:#000080 ; color:white">$1</span>', $td3);      
                  }  

                  $td3 = preg_replace('/(' .  get_yesterday_trade_date() . ')/', '<span style="font-size: 16px; background-color:#000080 ; color:white">$1</span>', $td3);     
                  $td3 = preg_replace('/(' .  get_today_trade_date() . ')/', '<span style="font-size: 16px; background-color:black; color:white">$1</span>', $td3); 

              echo "<tr>" . $td0 . '<td><a href ="' . $href2 . '">' . $td2 . '</a></td>' . $td3 . "</tr>"; 
            }
      echo "</table>";

?>