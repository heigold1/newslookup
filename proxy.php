<?php 

include './Samples/config.php';

require_once("simple_html_dom.php"); 
require_once("country-codes.php");

libxml_use_internal_errors(true);

$yesterdayDays = 1;

error_reporting(1);
//ini_set('display_errors', 1);

// header('Content-type: text/html');
$symbol=$_GET['symbol'];
$originalSymbol = $_GET['originalSymbol']; 
$host_name=$_GET['host_name'];
$which_website=$_GET['which_website'];
$stockOrFund=$_GET['stockOrFund']; 
$google_keyword_string = $_GET['google_keyword_string'];

fopen("cookies.txt", "w");


function getYMDTradeDate($daysBack)
{
    $trade_date = "";

    $trade_date = date('Y-m-d', strtotime("-" . $daysBack . " days"));

    return $trade_date;
}

function get_yahoo_trade_date($daysBack)
{
    $trade_date = "";

    $week_day = date('l', strtotime("-" . $daysBack . " days"));
    $week_day = mb_substr($week_day, 0, 3);
    $month_day = date(', d M Y', strtotime("-" . $daysBack . " days"));
    $trade_date = $week_day . $month_day;

    return $trade_date;
}

function get_yahoo_friday_trade_date()
{
    $friday_yahoo_trade_date = "";

    $friday_yahoo_trade_week_day = date('l', strtotime("-3 days"));
    $friday_yahoo_trade_week_day = mb_substr($friday_yahoo_trade_week_day, 0, 3);
    $friday_yahoo_trade_month_day = date(', d M Y', strtotime("-3 days"));
    $friday_yahoo_trade_date = $friday_yahoo_trade_week_day . $friday_yahoo_trade_month_day;

    return $friday_yahoo_trade_date;
}

function get_yahoo_saturday_trade_date()
{
    $saturday_yahoo_trade_date = "";

    $saturday_yahoo_trade_week_day = date('l', strtotime("-2 days"));
    $saturday_yahoo_trade_week_day = mb_substr($saturday_yahoo_trade_week_day, 0, 3);
    $saturday_yahoo_trade_month_day = date(', d M Y', strtotime("-2 days"));
    $saturday_yahoo_trade_date = $saturday_yahoo_trade_week_day . $saturday_yahoo_trade_month_day;

    return $saturday_yahoo_trade_date;
}

function get_yahoo_yesterday_trade_date()
{
    $yesterday_yahoo_trade_date = "";

    $yesterday_yahoo_trade_week_day = date('l', strtotime("-1 days"));
    $yesterday_yahoo_trade_week_day = mb_substr($yesterday_yahoo_trade_week_day, 0, 3);
    $yesterday_yahoo_trade_month_day = date(', d M Y', strtotime("-1 days"));
    $yesterday_yahoo_trade_date = $yesterday_yahoo_trade_week_day . $yesterday_yahoo_trade_month_day;

    return $yesterday_yahoo_trade_date;
}

function get_yahoo_todays_trade_date()
{
    $todays_yahoo_trade_date = "";

    $todays_yahoo_trade_week_day = date('l');
    $todays_yahoo_trade_week_day = mb_substr($todays_yahoo_trade_week_day, 0, 3);
    $todays_yahoo_trade_month_day = date(', d M Y');
    $todays_yahoo_trade_date = $todays_yahoo_trade_week_day . $todays_yahoo_trade_month_day;

    return $todays_yahoo_trade_date;
}






function get_marketwatch_trade_date($daysBack)
{
    $trade_date = "";
    $month = date('M',strtotime("-" . $daysBack . " days"));

    if ($month == ('Mar'))
    {
      $trade_date = "Mar. " . date('d, Y',strtotime("-" . $daysBack . " days"));      
    } 
    else if ($month == ('Apr'))
    {
      $trade_date = "Apr. " . date('d, Y',strtotime("-" . $daysBack . " days"));      
    }
    else if ($month == ('May'))
    {
      $trade_date = "May. " . date('d, Y',strtotime("-" . $daysBack . " days"));      
    }
    else 
    {
      $trade_date = date('M. d, Y',strtotime("-" . $daysBack . " days"));      
    }

    $trade_date = preg_replace('/0([1-9]),/', '$1,', $trade_date);

    return $trade_date;
}




function get_marketwatch_friday_trade_date()
{
    $friday_marketwatch_trade_date = "";
    $month = date('M',strtotime("-3 days"));

    if ($month == ('Mar'))
    {
      $friday_marketwatch_trade_date = "March. " . date('d, Y',strtotime("-3 days"));      
    }
    else if ($month == ('Apr'))
    {
      $friday_marketwatch_trade_date = "Apr. " . date('d, Y',strtotime("-3 days"));      
    }
    else if ($month == ('May'))
    {
      $friday_marketwatch_trade_date = "May. " . date('d, Y',strtotime("-3 days"));      
    }
    else 
    {
      $friday_marketwatch_trade_date = date('M. d, Y',strtotime("-3 days"));      
    }

    $friday_marketwatch_trade_date = preg_replace('/0([1-9]),/', '$1,', $friday_marketwatch_trade_date);

    return $friday_marketwatch_trade_date;
}

function get_marketwatch_saturday_trade_date()
{
    $saturday_marketwatch_trade_date = "";
    $month = date('M',strtotime("-2 days"));

    if ($month == ('Mar'))
    {
      $saturday_marketwatch_trade_date = "March. " . date('d, Y',strtotime("-2 days"));
    }
    else if ($month == ('Apr'))
    {
      $saturday_marketwatch_trade_date = "Apr. " . date('d, Y',strtotime("-2 days"));
    }
    else if ($month == ('May'))
    {
      $saturday_marketwatch_trade_date = "May. " . date('d, Y',strtotime("-2 days"));
    }
    else
    {
      $saturday_marketwatch_trade_date = date('M. d, Y',strtotime("-2 days"));
    }

    $saturday_marketwatch_trade_date = preg_replace('/0([1-9]),/', '$1,', $saturday_marketwatch_trade_date);

    return $saturday_marketwatch_trade_date;
}

function get_marketwatch_yesterday_trade_date()
{
    $yesterday_marketwatch_trade_date = "";
    $month = date('M',strtotime("-1 days"));

    if ($month == ('Mar'))
    {
      $yesterday_marketwatch_trade_date = "March. " . date('d, Y',strtotime("-1 days"));
    }
    else if ($month == ('Apr'))
    {
      $yesterday_marketwatch_trade_date = "Apr. " . date('d, Y',strtotime("-1 days"));
    }
    else if ($month == ('May'))
    {
      $yesterday_marketwatch_trade_date = "May. " . date('d, Y',strtotime("-1 days"));
    }
    else
    {
      $yesterday_marketwatch_trade_date = date('M. d, Y',strtotime("-1 days"));
    }

    $yesterday_marketwatch_trade_date = preg_replace('/0([1-9]),/', '$1,', $yesterday_marketwatch_trade_date);

    return $yesterday_marketwatch_trade_date;
}

function get_marketwatch_today_trade_date()
{
    $today_marketwatch_trade_date = "";
    $month = date('M');

    if ($month == ('Mar'))
    {
      $today_marketwatch_trade_date = "Mar. " . date('d, Y');
    }
    else if ($month == ('Apr'))
    {
      $today_marketwatch_trade_date = "Apr. " . date('d, Y');
    }
    else if ($month == ('May'))
    {
      $today_marketwatch_trade_date = "May. " . date('d, Y');
    }
    else
    {
      $today_marketwatch_trade_date = date('M. d, Y');
    }

    $today_marketwatch_trade_date = preg_replace('/0([1-9]),/', '$1,', $today_marketwatch_trade_date);

    return $today_marketwatch_trade_date;
}

function mapCountryCodes($code)
{
  $map = array(
    'US' => 'USA',

); 
  $country = $map[$code]; 

}


function calcFinVizAvgVolume($string)
{
    $string = preg_replace('/.*Avg Volume/', '', $string);

    $string = preg_replace('/Price.*$/', '', $string); 
    $string = rtrim($string);  

    $string = preg_replace('/string/', '', $string);

    $thousands = false; 
    $millions = false;

    if (preg_match('/K/', $string)){ $thousands = true;}
    if (preg_match('/M/', $string)){ $millions = true;}

    $string = preg_replace('/M|K/', '', $string); 
    $string = rtrim($string);

    $string = preg_replace("/<.*?>/", "", $string);
    $string = rtrim($string);

    $string = floatval($string);

    if ($thousands){ $string*= 1000;} elseif ($millions){ $string *= 1000000;}

    $string = number_format($string);

    return($string);
}

function addYahooSectorIndustry($symbol, $sector, $industry, $country, $companyName)
{

    if (preg_match('/NO SECTOR/', $sector) || preg_match('/COMPANY NOT FOUND/', $sector))
    {
        return;
    }

    if (preg_match('/ETF\/ETN/', $sector)) 
    {
        $industry .= " " . $companyName; 
    }

    $servername = "localhost";
    $username = "superuser";
    $password = "heimer27";
    $db = "daytrade"; 
    $mysqli = null;
    $date = date("Y-m-d"); 

    // Check connection
    try {
        $mysqli = new mysqli($servername, $username, $password, $db);
    } catch (mysqli_sql_exception $e) {

    } 

    $symbol = $mysqli->real_escape_string($symbol);
    $sector = $mysqli->real_escape_string($sector);
    $industry = $mysqli->real_escape_string($industry);
    $country = $mysqli->real_escape_string($country);
    $date = $mysqli->real_escape_string($date);

    $sqlStatement = "REPLACE INTO sector (symbol, sector, industry, country, date) VALUES ('" . $symbol . "', '" . $sector . "', '" . $industry . "', '" . $country . "','" . $date . "')"; 

    $mysqli->query("REPLACE INTO sector (symbol, sector, industry, country, date) VALUES ('" . $symbol . "', '" . $sector . "', '" . $industry . "', '" . $country . "','" . $date . "')");

}


function curl_get_contents($url)
{
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_URL, $url);

    $data = curl_exec($ch);
    curl_close($ch);

    return $data;
}


function getTradeHalts()
{
    $rss_feed = simplexml_load_file("https://www.nasdaqtrader.com/rss.aspx?feed=tradehalts");

    $returnArray['halt_table'] = "<table>"; 
    $haltSymbolList = array(); 
    $currentlyHalted = array(); 

    $dateTime = new DateTime(); 
    $dateTime->modify('-8 hours'); 
    $currentDate = $dateTime->format("m/d/Y"); 

    foreach ($rss_feed->channel->item as $feed_item) {

      $ns = $feed_item->getNamespaces(true); 
      $child = $feed_item->children($ns["ndaq"]);

      $date = $child->HaltDate; 
      $resumptionTime = $child->ResumptionTradeTime; 
      $symbol = trim($feed_item->title); 
      $reasonCode = trim($child->ReasonCode); 

      if ($date == $currentDate)
      {
        if (!in_array($symbol, $haltSymbolList)) {
            array_push($haltSymbolList, $symbol); 
        }

        if ($resumptionTime == "")
        {
            if (!in_array($symbol, $currentlyHalted)) {
                $currentlyHalted[$symbol] = $reasonCode; 
            }
        }        

      }
    }

    $returnArray['halt_symbol_list'] = json_encode($haltSymbolList); 
    $returnArray['currently_halted'] = json_encode($currentlyHalted); 

    return $returnArray; 
}




function grabEtradeHTML($etrade_host_name, $url)
{

    $ch = curl_init();
    $header=array('GET /1575051 HTTP/1.1',
    "Host: www.etrade.wallst.com",
    'Accept:text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
    'Accept-Language:en-US,en;q=0.8',
    'Cookie: oda_bsid=386242%3A%3A@@*; 1432%5F0=C3D1E46964D0597C69BAB2D9F8A3652F',
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
        curl_setopt($_h, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 );
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

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
    curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 );
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

    curl_setopt($ch,CURLOPT_COOKIEFILE,'cookies.txt');
    curl_setopt($ch,CURLOPT_COOKIEJAR,'cookies.txt');
    curl_setopt($ch,CURLOPT_HTTPHEADER,$header);

    curl_setopt($ch, CURLOPT_VERBOSE, true);
    curl_setopt($ch, CURLOPT_STDERR,$f = fopen(__DIR__ . "/error.log", "w+"));




$response = curl_exec($ch);

if (curl_errno($ch)) {
    echo 'Curl error: ' . curl_error($ch);
} else {
    $finalUrl = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
    return htmlspecialchars($response);
}








/*

    $returnHTML = curl_exec($ch); 

    if($errno = curl_errno($ch)) {
        $error_message = curl_strerror($errno);
        echo "cURL error ({$errno}):\n {$error_message}";
    }   
    curl_close($ch);
    return $returnHTML; 
*/

} // end of function grabHTML

$ret = "";
$finalReturn = "";

if ($which_website == "marketwatch")
{
      $url = "https://www.marketwatch.com/investing/$stockOrFund/$symbol"; 
      $marketwatch_todays_date = date('l'); 

      $results = grabHTML("www.marketwatch.com", $url);
      $results = str_replace(PHP_EOL, '', $results);
      $results = preg_replace('/<head>(.*)<\/head>/', "", $results);

      preg_match('/<div class="article__content">(.*)<\/div>/', $results, $arrayMatch);

      $batchString = $arrayMatch[0];

      preg_match_all('/<div class="article__content">(.*?)<\/div>/', $batchString, $individualArticleDiv);

      $finalArray = array();

      $classActionAdded == false;
/*
      foreach ($individualArticleDiv[0] as $articleDiv)
      {
          $articleStruct = array();

          preg_match('/href="(.*?)"/', $articleDiv, $linksArray);
          $articleStruct['link'] = $linksArray[1];
          preg_match('/<a.*>(.*?)<\/a>/', $articleDiv, $headlinesArray);
          $articleStruct['headline'] = $headlinesArray[1];
          preg_match('/data-est="(.*?)"/', $articleDiv, $timeStampArray);
          $timeStamp = $timeStampArray[1];
          preg_match('/article__timestamp">(.*?)<\/li>/', $articleDiv, $dateStringArray);
          $articleStruct['date'] = $dateStringArray[1];

          $timeStampInt = strtotime($timeStamp);

          if ($articleStruct['link'] != "")
          {
              $finalArray[$timeStampInt] = $articleStruct; 
          }
      }

      krsort($finalArray);

*/

      $marketWatchNewsHTML = '<html><head><link rel="stylesheet" href="./css/combined-min-1.0.5754.css" type="text/css"/>
      <link type="text/css" href="./css/quote-layout.css" rel="stylesheet"/>
        <link type="text/css" href="./css/quote-typography.css" rel="stylesheet"/>
      </head>
      <body>
      '; 

      $marketWatchNewsHTML = "<h1>Marketwatch News</h1>";
      $marketWatchNewsHTML .= '<div style="max-height: 300px; overflow: auto;">';

      $classActionAdded = false; 

      foreach ($individualArticleDiv[0] as $articleDiv)
      {
          $articleStruct = array();

          preg_match('/href="(.*?)"/', $articleDiv, $linksArray);
          $articleStruct['link'] = $linksArray[1];
          preg_match('/<a.*>(.*?)<\/a>/', $articleDiv, $headlinesArray);
          $articleStruct['headline'] = $headlinesArray[1];
          preg_match('/data-est="(.*?)"/', $articleDiv, $timeStampArray);
          $timeStamp = $timeStampArray[1];
          preg_match('/data-est=".*?">(.*?)<\/span>/', $articleDiv, $dateStringArray);
          $articleStruct['date'] = $dateStringArray[1];

          // am/pm  red green highlighting  
          for ($i = $yesterdayDays; $i >= 1; $i--)
          {
              if (preg_match('/(' .  get_marketwatch_trade_date($i) . ')/', $articleStruct['date']))
              {
                  $articleStruct['date'] = preg_replace('/p\.m\./', '<span style="background-color: red">p.m.</span>', $articleStruct['date']); 
                  if ($i == $yesterdayDays) 
                  {
                      $articleStruct['date'] = preg_replace('/a\.m\./', '<span style="background-color: lightgreen">a.m.</span>', $articleStruct['date']); 
                
                  }
                  else
                  {
                      $articleStruct['date'] = preg_replace('/a\.m\./', '<span style="background-color: red">a.m.</span>', $articleStruct['date']); 
                  }  
              }
          }

          $timeStampInt = strtotime($timeStamp);

          if ($articleStruct['link'] != "")
          {
              $finalArray[$timeStampInt] = $articleStruct; 
          }
      }
      krsort($finalArray);

      $classActionAdded = false;

      foreach ($finalArray as $article)
      {
          if (preg_match('/class.action/i', $article['headline']))
          {
              if ($classActionAdded == true)
              {
                continue;              
              }
              else
              {
                $classActionAdded = true;
              }
          }

          $marketWatchNewsHTML .= '<div>' . $article['date'] . '&nbsp;&nbsp;<a target="blank" href="' . $article['link'] . '">' . $article['headline'] . '</a></div>';
      }

      $marketWatchNewsHTML .= '</div>';

      // yellow highlighting for before yesterday
      for ($daysBack = 14; $daysBack >= 7; $daysBack--)
      {
          $marketWatchNewsHTML = preg_replace('/(' .  get_marketwatch_trade_date($daysBack) . ')/', '<span style="font-size: 10px; background-color:#FFFFC5 ; color:black">$1</span>', $marketWatchNewsHTML);      
      }

      for ($daysBack = 6; $daysBack > $yesterdayDays; $daysBack--)
      {
          $marketWatchNewsHTML = preg_replace('/(' .  get_marketwatch_trade_date($daysBack) . ')/', '<span style="font-size: 10px; background-color:yellow ; color:black">$1</span>', $marketWatchNewsHTML);      
      }


      // blue highlighting for yesterday
      for ($daysBack = $yesterdayDays; $daysBack >= 1; $daysBack--)
      {
          $marketWatchNewsHTML = preg_replace('/(' .  get_marketwatch_trade_date($daysBack) . ')/', '<span style="font-size: 12px; background-color:#0747a1 ; color:white">$1</span>', $marketWatchNewsHTML);
      }

      $marketWatchNewsHTML = preg_replace('/(' .  get_marketwatch_today_trade_date() . ')/', '<span style="font-size: 10px; background-color:black; color:white">$1</span>', $marketWatchNewsHTML);           

      $marketWatchNewsHTML = preg_replace('/([0-9][0-9]:[0-9][0-9] [a-z]\.m\.  Today)|([0-9]:[0-9][0-9] [a-z]\.m\.  Today)/', '<span style="font-size: 8px; background-color:black; color:white">$1$2</span>', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/([0-9][0-9] min ago)|([0-9] min ago)/', '<span style="font-size: 8px; background-color:black; color:white">$1$2</span>', $marketWatchNewsHTML);      
      $marketWatchNewsHTML = preg_replace('/ delist/i', '<span style="font-size: 55px; background-color:red; color:black"><b> delist - check the date</b></span>', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/Delist/', '<span style="font-size: 55px; background-color:red; color:black"><b> delist - check the date</b></span>', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/ chapter 11|chapter 11 /i', '<span style="font-size: 55px; background-color:red; color:black"><br><br><b> &nbsp;CHAPTER 11</b>&nbsp;</span>', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/ reverse split|reverse split /i', '<span style="font-size: 55px; background-color:red; color:black"><b> &nbsp;REVERSE SPLIT - CHECK THE DATE</b>&nbsp;</span>', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/ reverse.stock split|reverse stock split /i', '<span style="font-size: 25px; background-color:red; color:black"><b> &nbsp;REVERSE SPLIT</b>&nbsp;</span>', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/ seeking alpha|seeking alpha /i', '<font size="3" style="font-size: 12px; background-color:#CCFF99; color: black; display: inline-block;">&nbsp;<b>Seeking Alpha</b>&nbsp;</font>', $marketWatchNewsHTML);      
      $marketWatchNewsHTML = preg_replace('/ downgrade|downgrade /i', '<span style="font-size: 12px; background-color:red; color:black"><b> &nbsp;DOWNGRADE</b>&nbsp;</span>', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/ ex-dividend|ex-dividend /i', '<span style="font-size: 12px; background-color:red; color:black"><b> &nbsp;EX-DIVIDEND (chase at 25%)</b>&nbsp;</span>', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/ sales miss|sales miss /i', '<span style="font-size: 12px; background-color:red; color:black"><b> &nbsp;SALES MISS (Chase at 65-70%)</b>&nbsp;</span>', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/ miss sales|miss sales /i', '<span style="font-size: 12px; background-color:red; color:black"><b> &nbsp;MISS SALES (Chase at 65-70%)</b>&nbsp;</span>', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/ disappointing sales|disappointing sales /i', '<span style="font-size: 12px; background-color:red; color:black"><b> &nbsp;DISAPPOINTINT SALES (Chase at 65-70%)</b>&nbsp;</span>', $marketWatchNewsHTML);      
      $marketWatchNewsHTML = preg_replace('/ sales results|sales results /i', '<span style="font-size: 12px; background-color:red; color:black"><b> &nbsp;SALES RESULTS (If bad, chase at 65-70%)</b>&nbsp;</span>', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/ 8-k/i', '<span style="font-size: 55px; background-color:red; color:black"><b>&nbsp;8-K</span> (if it involves litigation, then back off)</b>&nbsp;', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/ accountant/i', '<span style="font-size: 25px; background-color:red; color:black"><b> &nbsp;accountant (if hiring new accountant, 35-40%)</b>&nbsp;</span>', $marketWatchNewsHTML);      
      $marketWatchNewsHTML = preg_replace('/ clinical trial/i', '<span style="font-size: 12px; background-color:red; color:black"><b> &nbsp;clinical trial</b>&nbsp;</span>', $marketWatchNewsHTML);            
      $marketWatchNewsHTML = preg_replace('/ recall/i', '<span style="font-size: 25px; background-color:red; color:black"><b> &nbsp;recall (bad, back ff)</b>&nbsp;</span>', $marketWatchNewsHTML);                  
      $marketWatchNewsHTML = preg_replace('/ etf/i', '<span style="font-size: 12px; background-color:red; color:black"><b> &nbsp;ETF</b>&nbsp;</span>', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/ etn/i', '<span style="font-size: 12px; background-color:red; color:black"><b> &nbsp;ETN</b>&nbsp;</span>', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/[ \']disruption[ \']/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp;disruption&nbsp;</span> (chase at 52%)</b>', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/ abandon/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp;abandon&nbsp;</span> (65-70%)</b>', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/ bankrupt/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp;bankrupt&nbsp;</span>(65%)</b>', $marketWatchNewsHTML);      
      $marketWatchNewsHTML = preg_replace('/ terminate| terminates| terminated| termination/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp;terminate&nbsp;</span> (65%) </b>', $marketWatchNewsHTML);            
      $marketWatchNewsHTML = preg_replace('/ drug/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp;drug&nbsp;</span></b>', $marketWatchNewsHTML);            
      $marketWatchNewsHTML = preg_replace('/ guidance/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp;GUIDANCE</span></b>&nbsp;', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/ regulatory update/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp;regulatory update (35% even if regulation is good)</span></b>&nbsp;', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/ susbended/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp;suspended</span> (65-70%)</b>&nbsp;', $marketWatchNewsHTML);      
      $marketWatchNewsHTML = preg_replace('/ fraud/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp;fraud</span></b>&nbsp;', $marketWatchNewsHTML);      
      $marketWatchNewsHTML = preg_replace('/ dividend/i', '<span style="font-size: 55px; background-color:red; color:black"><b>&nbsp;DIVIDEND -  CHECK THE ISSUING DATE)</span></b>&nbsp;', $marketWatchNewsHTML); 
      $marketWatchNewsHTML = preg_replace('/ strategic alternatives/i', '<span style="font-size: 55px; background-color:red; color:black"><b>&nbsp;strategic alternatives</span>**BANKRUPTCY**</b>&nbsp;', $marketWatchNewsHTML);                  
      $marketWatchNewsHTML = preg_replace('/ unpatentable/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp;unpatentable</span> (65%)</b>&nbsp;', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/ accelerate or increase/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp;accelerate or increase</span> (Possible Chapter 11, stay away)</b>&nbsp;', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/ denial of application/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp;denial of application</span> (65%)</b>&nbsp;', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/ restructuring support agreement/i', '<span style="font-size: 35px; background-color:red; color:black"><b>&nbsp;Restructuring Support Agreement</span> (53%)</b>&nbsp;', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/ breach of contract/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp;breach of contract</span> (If lost lawsuit, then 75%, if won then 35% premarket/first round)</b>&nbsp;', $marketWatchNewsHTML);      
      $marketWatchNewsHTML = preg_replace('/ jury verdict/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp;jury verdict</span> BE CAREFUL (If lost major lawsuit, then 70-75%)</b>&nbsp;', $marketWatchNewsHTML);      
      $marketWatchNewsHTML = preg_replace('/ fcc/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp;FCC</span> if regulation had long-term ratifications, then 65-70%</b>&nbsp;', $marketWatchNewsHTML);      
      $marketWatchNewsHTML = preg_replace('/ layoffs/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp;layoffs</span> (check Jay\'s percentages)</b>&nbsp;', $marketWatchNewsHTML);      
      $marketWatchNewsHTML = preg_replace('/ layoff/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp;layoff</span> (check Jay\'s percentages)</b>&nbsp;', $marketWatchNewsHTML);      
      $marketWatchNewsHTML = preg_replace('/ lays off/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp;lays off</span> (check Jay\'s percentages)</b>&nbsp;', $marketWatchNewsHTML);      
      $marketWatchNewsHTML = preg_replace('/ restructuring/i', '<span style="font-size: 45px; background-color:red; color:black"><b>&nbsp;restructuring</span></b>&nbsp;', $marketWatchNewsHTML);      
      $marketWatchNewsHTML = preg_replace('/ restructure/i', '<span style="font-size: 45px; background-color:red; color:black"><b>&nbsp;restructure</span></b>&nbsp;', $marketWatchNewsHTML);      
      $marketWatchNewsHTML = preg_replace('/nasdaq rejects(.*)listing/i', '<span style="font-size: 12px; background-color:red; color:black"><b>Nasdaq rejects $1 listing</span> If delisting tomorrow 65%, if delisting days away then 50-55%</b>&nbsp;', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/ clinical hold/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp;clinical hold</span> (65 - 70%)</b>&nbsp;', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/ withdrawal(.*)application/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp; withdrawal $1 application (55%)</b></span>&nbsp;', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/ convertible senior notes/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; convertible senior notes (back off until you see a price)</b></span>&nbsp;', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/ amendment(.*) to secured credit facilities/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp; amendments to secured credit facilities (65%)</b></span>&nbsp;', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/ notice of effectiveness/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp; notice of effectiveness (40% till you see the notice)</b></span>&nbsp;', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/ equity investment/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp; equity investment - look for price of new shares</b></span>&nbsp;', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/ lease termination/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp; DANGER - Chapter 7 warning - 90%</b></span>&nbsp;', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/ redemption of public shares/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp; Redemption of Public Shares - 92%</b></span>&nbsp;', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/ phase 2/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp; Phase 2 - 82% and set a stop loss of around 12%</b></span>&nbsp;', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/ investor call/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; INVESTOR CALL - CHECK THE DATE &nbsp;</b></span>&nbsp;', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/ strategic update/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp; STRATEGIC UPDATE - BE CAREFUL &nbsp;</b></span>&nbsp;', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/ attorney general/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp; attorney general (if there is an attorney general probe then 45-50%) &nbsp;</b></span>&nbsp;', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/ merger/i', '<span style="font-size: 12px; background-color:red; color:black"><br><br><b>&nbsp; merger - if changing deadline (or update in general, 35%), if lawfirm investigating then 22% &nbsp;</b></span>&nbsp;', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/ preliminary(.*?)outlook/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp; Preliminary$1Outlook -</span> <span style="font-size: 12px; background-color:lightgreen; color:black">41% right off the bat, then 48% literally 3 minutes later.  TAKE NO MORE THAN 5% AND BAIL &nbsp;</b></span>&nbsp;', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/ conference call to provide update/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp; Conference Call to Provide Update -</span> <span style="font-size: 12px; background-color:lightgreen; color:black">CHECK THE DATE/TIME OF THE CALL &nbsp;</b></span>&nbsp;', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/ fictitious sales/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp; fictitious sales - STAY AWAY </b></span> &nbsp;', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/ board of directors/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp; board of directors - if change to board of directors - 20%  </b></span> &nbsp;', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/ class action/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; class action</b></span> &nbsp;', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/ business combination/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp; BUSINESS COMBINATION - TREAT AS A NEWLY-ISSUED STOCK </b></span> &nbsp;', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/ annual meeting of shareholders/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp; annual meeting of shareholders - 40% early </b></span> &nbsp;', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/ transcript/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; transcript  </b></span> &nbsp;', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/ share consolidation/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; share consolidation - REVERSE STOCK SPLIT </b></span> &nbsp;', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/ share exchange transaction/i', '<span style="font-size: 20px; background-color:red; color:black"><b>&nbsp; share exchange transaction - 47% </b></span> &nbsp;', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/ closes(.*)offering/i', '<span style="font-size: 22px; background-color:red; color:black"><b>&nbsp; closes offering - be careful, 24%</b></span>&nbsp;', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/ announces pricing/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; Announces Pricing </b></span>&nbsp;', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/ late interest payment/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; late interest payment - BACK OFF, POSSIBLE CHAPTER 11 </b></span>&nbsp;', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/ q1 loss/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; Q1 LOSS </b></span>&nbsp;', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/ q2 loss/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; Q2 LOSS </b></span>&nbsp;', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/ q3 loss/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; Q3 LOSS </b></span>&nbsp;', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/ q4 loss/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; Q4 LOSS </b></span>&nbsp;', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/ first.quarter/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; FIRST QUARTER </b></span>&nbsp;', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/ second.quarter/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; SECOND QUARTER </b></span>&nbsp;', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/ third.quarter/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; THIRD QUARTER </b></span>&nbsp;', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/ fourth.quarter/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; FOURTH QUARTER </b></span>&nbsp;', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/ q1- results/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; Q1 - RESULTS </b></span>&nbsp;', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/ q2 - results/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; Q2 - RESULTS </b></span>&nbsp;', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/ q3 - results/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; Q3 - RESULTS </b></span>&nbsp;', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/ q4 - results/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; Q4 - RESULTS </b></span>&nbsp;', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/ q1 earnings/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; Q1 EARNINGS </b></span>&nbsp;', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/ q2 earnings/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; Q2 EARNINGS </b></span>&nbsp;', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/ q3 earnings/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; Q3 EARNINGS </b></span>&nbsp;', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/ q4 earnings/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; Q4 EARNINGS </b></span>&nbsp;', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/ withdrawal of auditors\' report/i', "<span style=\"font-size: 25px; background-color:red; color:black\"><b>&nbsp; Withdrawal of Auditors' Report - 40\% </b></span>&nbsp;", $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/ withdraws audit report/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; withdraws audit report - 40% </b></span>&nbsp;', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/ withdrawing audits/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; withdrawing audits - 40% </b></span>&nbsp;', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/ move to otc/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; MOVE TO OTC - DELISTING - BE CAREFUL </b></span>&nbsp;', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/ corporate update/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; CORPORATE UPDATE - BACK OFF </b></span>&nbsp;', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/ provides update/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; PROVIDES UPDATE - BACK OFF </b></span>&nbsp;', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/ external advisor| external adviser/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; EXTERNAL ADVISOR - BACK OFF </b></span>&nbsp;', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/ turnaround/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; TURNAROUND - BACK OFF </b></span>&nbsp;', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/ clinical.stage/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; CLINICAL STAGE - BACK OFF </b></span>&nbsp;', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/ ratio change/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; RATIO CHANGE - REVERSE SPLIT </b></span>&nbsp;', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/ 10\-q/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; 10-Q </b></span>&nbsp;', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/ registered direct offering/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; REGISTERED DIRECT OFFERING </b></span>&nbsp;', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/ fda clearance/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; FDA Clearance - back off </b></span>&nbsp;', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/ deficiency in compliance/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; Deficiency in Compliance - CHECK FOR DELISTING</b></span>&nbsp;', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/ net asset value/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; NET ASSET VALUE - 25%</b></span>&nbsp;', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/ efficacy data/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; EFFICACY DATA - DRUG NEWS</b></span>&nbsp;', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/ phase 1/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; PHASE 1!!!!</b></span>&nbsp;', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/ phase 2/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; PHASE 2!!!!</b></span>&nbsp;', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/ phase 3/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; PHASE 3!!!!</b></span>&nbsp;', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/ to present/i', '<span style="font-size: 25px; background-color:#202020; color:white"><b>&nbsp; TO PRESENT - CHECK DATE - IF EARNINGS THEN OK TO CHASE AROUND 21-23%</b></span>&nbsp;', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/ shareholder investigation/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; SHAREHOLDER INVESTIGATION - 19.5%</b></span>&nbsp;', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/ announces an investigation/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; ANNOUNCES AN INVESTIGATION - 19.5%</b></span>&nbsp;', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/ convertible bonds/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; convertible bonds (back off until you see a price)</b></span>&nbsp;', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/ equity grants/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; EQUITY GRANTS - (20-23% early on)</b></span>&nbsp;', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/ to announce/i', '<span style="font-size: 25px; background-color: red; color:black"><b>&nbsp; TO ANNOUNCE - CHECK THE DATE </b></span>&nbsp;', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/ to report/i', '<span style="font-size: 25px; background-color: red; color:white"><b>&nbsp; TO REPORT - CHECK THE DATE - DO NOT CHASE EARLY </b></span>&nbsp;', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/ to host/i', '<span style="font-size: 25px; background-color: red; color:white"><b>&nbsp; TO HOST - CHECK THE DATE </b></span>&nbsp;', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/ to release/i', '<span style="font-size: 25px; background-color: black; color:white"><b>&nbsp; TO RELEASE - CHECK THE DATE </b></span>&nbsp;', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/ schedules/i', '<span style="font-size: 25px; background-color: black; color:white"><b>&nbsp; SCHEDULES - CHECK THE DATE </b></span>&nbsp;', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/ sets date for the release of/i', '<span style="font-size: 25px; background-color: black; color:white"><b>&nbsp; SETS DATE FOR THE RELEASE OF - CHECK THE DATE </b></span>&nbsp;', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/ collaboration/i', '<span style="font-size: 25px; background-color: red; color:white"><b>&nbsp; COLLABORATION - CAREFUL </b></span>&nbsp;', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/ china/i', '<span style="font-size: 65px; background-color: red; color:black"><br><br><b>&nbsp; CHINA </b></span>&nbsp;', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/ taiwan/i', '<span style="font-size: 65px; background-color: red; color:black"><b>&nbsp; TAIWAN </b></span>&nbsp;', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/ hong kong/i', '<span style="font-size: 65px; background-color: red; color:black"><b>&nbsp; HONG KONG </b></span>&nbsp;', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/ kerrisdale/i', '<span style="font-size: 25px; background-color: red; color:black"><b>&nbsp; Kerrisdale - 65% </b></span>&nbsp;', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/ to showcase/i', '<span style="font-size: 25px; background-color: red; color:black"><b>&nbsp; TO SHOWCASE - CHECK THE DATE </b></span>&nbsp;', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/ rescue financing/i', '<span style="font-size: 25px; background-color: red; color:black"><b>&nbsp; 
        RESCUE FINANCING - BE CAREFUL</b></span>&nbsp;', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/ liquidity/i', '<span style="font-size: 25px; background-color: red; color:black"><b>&nbsp; LIQUIDITY - BACK OFF</b></span>&nbsp;', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/annual report/i', '<span style="font-size: 25px; background-color: red; color:black"><b>&nbsp; ANNUAL REPORT</b></span>&nbsp;', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/IPO/', '<span style="font-size: 45px; background-color: red; color:black"><b>&nbsp; IPO - CHECK THE DATE</b></span>&nbsp;', $marketWatchNewsHTML);



      $marketWatchNewsHTML .= '</body></html>'; 

      echo $marketWatchNewsHTML;       
}
else if ($which_website == "yahoo")
{
    // grab the news 

    $companyName = $_GET['company_name']; 
    $companyNameArray = explode(" ", $companyName);

/*
These methods don't work anymore, so we have to do a python scrape 
   $rss = simplexml_load_file("https://feeds.finance.yahoo.com/rss/2.0/headline?s=$symbol&region=US&lang=en-US");
   $rss = grabHTML('feeds.finance.yahoo.com', "https://feeds.finance.yahoo.com/rss/2.0/headline?s=$symbol&region=US&lang=en-US"); 
*/



      $command = escapeshellcmd('python3 ./pythonscrape/scrape-yahoo-finance-rss.py ' . $symbol . ' ' . $yesterdayDays);
      $allNews = shell_exec($command);

/*

echo "About to echo the yahoo finance command<br>";

echo "<pre>"; 
var_dump($allNews); 
echo "</pre>"; 
die(); 
*/


/*
    $allNews = "<ul class='newsSide'>";
    $allNews .= "<li style='font-size: 20px !important; background-color: #00ff00;'>Yahoo Finance News</li>";

    $classActionAdded = false;
    $j = 0;

    foreach ($rssYahoo->channel->item as $feedItem) {
        $j++;

        // Convert time from GMT to  AM/PM New York
        // just add or subtract 3600 (seconds) to add or subtract one hour 
        $publicationDateStrToTime = strtotime($feedItem->pubDate) - 14400; 
        $convertedDate = new DateTime(); 
        $convertedDate->setTimestamp($publicationDateStrToTime);

        $publicationDate = $feedItem->pubDate;

        $publicationDate = preg_replace("/[0-9][0-9]\:[0-9][0-9]\:[0-9][0-9] \+0000/", "", $publicationDate); 
        $publicationTime = $convertedDate->format("g:i A");

        $newsTitle = $feedItem->title; 

        if (preg_match('/class.action/i', $newsTitle))
        {
            if ($classActionAdded == true)
            {
              continue;              
            }
            else
            {
              $classActionAdded = true;
            }
        }

        $allNews .= "<li "; 

        // red/green highlighting for yesterday/today
        for ($i = $yesterdayDays; $i >= 1; $i--)
        {
            if (preg_match('/(' .  get_yahoo_trade_date($i) . ')/', $publicationDate))
            {
                $publicationTime = preg_replace('/PM/', '<span style="background-color: red; font-size: 18px; ">PM</span>', $publicationTime); 
                if ($i == $yesterdayDays) 
                {
                    $publicationTime = preg_replace('/AM/', '<span style="background-color: #00ff00; font-size: 18px;">AM</span>', $publicationTime); 
              
                }
                else
                {
                    $publicationTime = preg_replace('/AM/', '<span style="background-color: red; font-size: 18px;">AM</span>', $publicationTime); 
                }  
            }
        }

        if ($j % 2 == 1)
        {
          $allNews .=  "style='background-color: #ebd8bd; '"; 
        };
        
        // if the regular expression contains (.*) then we need to do it per title, to avoid greedy regular expressions

        $newsTitle = preg_replace('/ withdrawal(.*?)application/i', '<span style="font-size: 12px; background-color:red; color:black"><b> withdrawal $1 application (55%) </b></span> ', $newsTitle);
        $newsTitle = preg_replace('/nasdaq rejects(.*?)listing/i', '<span style="font-size: 12px; background-color:red; color:black"><b>Nasdaq rejects $1 listing</span> If delisting tomorrow 65%, if delisting days away then 50-55%</b>&nbsp;', $newsTitle);

        $allNews .=  " ><a href='$feedItem->link'> " . $publicationDate . " " . $publicationTime . " - <br>" . $newsTitle;
    }

      $allNews .=  "</ul>";

      */ 





      // grab the finanicals 


                      // Now we grab website, sector, and company from finviz.com
/*
                      $url="https://www.finviz.com/quote.ashx?t=$symbol";
                      $host_name = "www.finviz.com";
                      $result = grabHTML($host_name, $url);
                      $html = str_get_html($result);
                 
                      $companyWebsiteArray = $html->find('table.fullview-title tbody tr td a');
                      $companyWebsite = $companyWebsiteArray[1];
                      $companyWebsite = preg_replace('/<b>.*<\/b>/', '<b>Website</b>', $companyWebsite);
                      $companyWebsite = str_replace('<a ', '<a target="_blank" onclick="return openPage(this.href)" ', $companyWebsite);

                      $sectorCountryArray = $html->find('table.fullview-title tbody tr td a');
                      $sectorCountry = " " . $sectorCountryArray[2] . " - " . $sectorCountryArray[3] . " - " . $sectorCountryArray[4] . "<br>";
                      $sectorCountry = str_replace('<a', '<span id="geo_country"', $sectorCountry);    
                      $sectorCountry = str_replace('\/a', '/span', $sectorCountry);   
*/


/* Scrape Yahoo Finance */
/*
      $command = escapeshellcmd('python3 ./pythonscrape/scrape-yahoo-finance-company-profile.py ' . $symbol);
      $yahooFinanceJson = shell_exec($command);

      $yahooFinanceObject = json_decode($yahooFinanceJson);

      $companyWebsite = '<a target="_blank" style="font-size: 15px;" onclick="return openPage(this.href)" href="' . $yahooFinanceObject->website . '" class="tab-link"><b>Website</b></a>&nbsp;&nbsp;';

      $countryPipeString = $yahooFinanceObject->address; 
      $countryPipeArray = explode('|', $countryPipeString);
      $countryPipeArrayLength = count($countryPipeArray);

      $country = trim($countryPipeArray[$countryPipeArrayLength - 1]); 

      $yahooFinanceSector = $yahooFinanceObject->sector; 
      $yahooFinanceIndustry = $yahooFinanceObject->industry; 
/* 

/* We are currently grabbing sector, industry, and country from financialmodelingprep.com */

      $country = "";
      $yahooFinanceSector = ""; 
      $yahooFinanceIndustry = ""; 
      $website = ""; 
      $cik = ""; 

      $apiUrl = 'https://financialmodelingprep.com/api/v3/profile/' . $originalSymbol . '?apikey=EdahmOwRgQ6xcbs6j37SESSCrCIhcoa9';

      $yahooFinanceJson = file_get_contents($apiUrl); 

      // Make the HTTP GET request

      if ($yahooFinanceJson === false) {
        // Handle error, e.g., unable to connect to the API
        $country = "CHECK E*TRADE";
        $yahooFinanceSector = "NOT LISTED"; 
        $yahooFinanceIndustry = "NOT LISTED"; 
        $website = "NOT LISTED"; 
        $cik = "NOT_FOUND"; 
        $ceo = "NOT_FOUND"; 
        $description = "NOT_FOUND"; 
      }
      else
      {
        $yahooFinanceObject = json_decode($yahooFinanceJson, true); 
        if (empty($yahooFinanceObject))
        {
          $country = "CHECK E*TRADE";
          $yahooFinanceSector = "NOT LISTED"; 
          $yahooFinanceIndustry = "NOT LISTED"; 
          $website = "NOT LISTED"; 
          $cik = "NOT_FOUND"; 
          $ceo = "NOT_FOUND"; 
          $description = "NOT_FOUND"; 
        }
        else 
        {
          $country = $countryCodes[$yahooFinanceObject[0]['country']];
          $yahooFinanceSector = $yahooFinanceObject[0]['sector']; 
          $yahooFinanceIndustry = $yahooFinanceObject[0]['industry']; 
          $website = $yahooFinanceObject[0]['website'];
          $city = $yahooFinanceObject[0]['city']; 
          $state = $yahooFinanceObject[0]['state']; 
          $cik = $yahooFinanceObject[0]['cik']; 
          $ceo = $yahooFinanceObject[0]['ceo']; 
          $description = $yahooFinanceObject[0]['description']; 
          $descriptionRegex = $description;
          $chineseCityArray = "/\b(china|japan|singapore|taiwan|malaysia|korea|hong kong)\b/i"; 


          preg_match_all($chineseCityArray, $descriptionRegex, $matches);

          if (!empty($matches[0])) {
            // Join all the matched countries with a comma and space, then prepend to the text
            $descriptionRegex = strtoupper(implode(", ", array_unique($matches[0]))) . " - " . $descriptionRegex;
          }


          $descriptionRegex = preg_replace($chineseCityArray, '<br><br><span style="font-size: 45px; background-color: red; color:black"><b>&nbsp; $1</b></span>$1</span>', $descriptionRegex);
          $descriptionRegex .= '<button onclick="prepareChineseJay(\'' . $symbol . '\',\''. addslashes($ceo). '\',\'' . addslashes($description) . '\')">Prepare Chinese Question</button>';   


          $chineseSurnames = ["Li", "Wang", "Zhang", "Liu", "Chen", "Yang", "Huang", "Zhao", "Wu", "Zhou", "Xu", "Sun", "Ma", "Hu", "Gao", "Lin", "He", "Guo", "Luo", "Deng", "Long", "Kwan", "Yau", "Ho", "Tsu", "Qian", "Jie", "Tuo", "Ze", "Dongye", "Dao", "Du", "Zhi", "Xu", "Di", "Bo", "Du", "Duan", "Gao", "Cai", "Xiyong", "Hou", "Xiao", "Sui", "Ming", "Mei", "Phua", "Wing", "Fung", "Siu", "Lu", "Pun", "Ping", "Xiaoyan", "Mi", "Jin", "Chow", "Ching", "Chang", "Chan", "Kim", "Ly", "Zhai", "Yin", "Yan"];

          $surnamePattern = "/\b(" . implode("|", $chineseSurnames) . ")\b/i";

          $ceoHasChineseSurname = preg_match($surnamePattern, $ceo);
          $descriptionContainsAsianCountry = preg_match($chineseCityArray, $description);

          if ($ceoHasChineseSurname || $descriptionContainsAsianCountry) {
            $ceo = '<br><br><span style="font-size: 55px; background-color: red;">CEO ' . $ceo . '</span>'; 
          }


          if (trim($city) == "")
          {
              $city = "ALPHA";
          }
          if (trim($state) == "")
          {
              $state = "ALPHA";
          }


/*          if ($country == "United States") 
          { */
            $country .= " (" . $city . ", " . $state . ")"; 

//          }
        }

      }

      if ($country == null)
      {
        $country = "NOT LISTED, CHECK"; 
      }

      if ($cik == null)
      {
        $cik = "NOT_FOUND"; 
      }

      $companyWebsite = '<a target="_blank" style="font-size: 15px;" onclick="return openPage(this.href)" href="' . $website . '" class="tab-link"><b>Website</b></a>&nbsp;&nbsp;';

      addYahooSectorIndustry($symbol, $yahooFinanceSector, $yahooFinanceIndustry, $country, $companyName);

/* Scrape the stockanalysis.com website for country, sector, and industry information */ 
/*
      $command = escapeshellcmd('python3 ./pythonscrape/scrape-stock-analysis.py ' . $symbol);
      $stockAnalysisJson = shell_exec($command);
      $yahooFinanceObject = json_decode($stockAnalysisJson); 

      $companyWebsite = '<a target="_blank" style="font-size: 15px;" onclick="return openPage(this.href)" href="" class="tab-link"><b>No website</b></a>&nbsp;&nbsp;';

      $country = $yahooFinanceObject->country; 
      $yahooFinanceSector = $yahooFinanceObject->sector; 
      $yahooFinanceIndustry = $yahooFinanceObject->industry; 

*/

/* end of scraping stockanalysis.com website */ 


/* Alphavantage alpha vantage API for company overview (sector, industry, country) 
      
      

    $alphaVantageAPI = "https://www.alphavantage.co/query?function=OVERVIEW&symbol=" . $symbol . "&apikey=d36ab142bed5a1430fcde797063f6b9a"; 
    $alphaVantageResults = grabHTML("www.alphavantage.co", $alphaVantageAPI);
    $alphaVantageJSON = json_decode($alphaVantageResults);

    $alphaVantageSector = $alphaVantageJSON->Sector; 
    $alphaVantageIndustry = $alphaVantageJSON->Industry; 
    $alphaVantageAddress = $alphaVantageJSON->Address; 

    $commaLocation = intval(strrpos($alphaVantageAddress, ',')); 
    $alphaVantageCountryCode = trim(substr($alphaVantageAddress, $commaLocation + 1)); 



//     $alphaVantagCountry = mapCountryCodes($alphaVantageCountryCode); 



  end of Alphavantage alpha vantage API for company overview */  





      /* Highlight certain sector words that should put us on guard */ 

      $yahooFinanceSector = preg_replace('/energy/i', '<span style="font-size: 20px; background-color: red; color:black"><b>&nbsp; ENERGY</b></span>&nbsp;', $yahooFinanceSector); 
      $yahooFinanceSector = preg_replace('/shell companies/i', '<span style="font-size: 20px; background-color: red; color:black"><b>&nbsp; SHELL COMPANIES - STAY AWAY</b></span>&nbsp;', $yahooFinanceSector); 
      $yahooFinanceSector = preg_replace('/healthcare/i', '<br><span style="font-size: 60px; background-color: red; color:black"><b>&nbsp; HEALTHCARE</b></span>&nbsp;', $yahooFinanceSector); 

      $yahooFinanceIndustry = preg_replace('/oil \& gas/i', '<span style="font-size: 60px; background-color: red; color:black"><b>&nbsp; OIL & GAS</b></span>&nbsp;', $yahooFinanceIndustry); 

      $yahooFinanceIndustry = preg_replace('/medical devices/i', '<span style="font-size: 60px; background-color: red; color:black"><b>&nbsp; MEDICAL<br><br> DEVICES</b></span>&nbsp;', $yahooFinanceIndustry); 

      $yahooFinanceIndustry = preg_replace('/(biotechnology|technology)/i', '<br><span style="font-size: 30px; background-color: red; color:black"><b>&nbsp; $1</b></span>&nbsp;', $yahooFinanceIndustry); 

      $yahooFinanceIndustry = preg_replace('/shell companies/i', '<span style="font-size: 35px; background-color: red; color:black"><b>&nbsp; Shell Companies</b></span>&nbsp;', $yahooFinanceIndustry); 

      $yahooFinanceIndustry = preg_replace('/banks/i', '<span style="font-size: 35px; background-color: red; color:black"><b>&nbsp; BANKS</b></span>&nbsp;', $yahooFinanceIndustry); 

      $sectorCountry = '<span style="font-size: 15px;">SECTOR - ' . $yahooFinanceSector . '</span>&nbsp;&nbsp;<span id="industry" style="font-size: 15px;">INDUSTRY - ' . $yahooFinanceIndustry . ' </span>' . 'CEO ' . $ceo . '<br><br><div id="country" style="font-size: 15px; height:75px; ">' . $country . '</div>'; 



      $returnCompanyName = '<h1>' . $companyName . '</h1>';

      $returnCompanyName = preg_replace('/ holding/i', '<span style="font-size: 45px; background-color:red; color:black"><b>&nbsp;HOLDING</span></b>&nbsp;', $returnCompanyName);  
      $returnCompanyName = preg_replace('/ hldgs/i', '<span style="font-size: 45px; background-color:red; color:black"><b>&nbsp;HLDGS</span></b>&nbsp;', $returnCompanyName);  
      $returnCompanyName = preg_replace('/ class a/i', '<span style="font-size: 45px; background-color:red; color:black"><b>&nbsp;CLASS A</span></b>&nbsp;', $returnCompanyName);  

      $yesterdayVolume = (int) $_GET['yesterday_volume'];
      $currentVolume = (int) $_GET['total_volume'];
      $volumeRatio = 0; 

      if ($yesterdayVolume == 0)
      {
        $volumeRation = "DIV0";
      }
      else 
      {
        $volumeRatio = $currentVolume/$yesterdayVolume;         
      }

      $volumeRatio = number_format((float)$volumeRatio, 2, '.', '');
      $yesterdayVolume = number_format($yesterdayVolume);
      $currentVolume = number_format($currentVolume);

      $volumeRatioHTML = '<span id="vol_ratio" style="font-size: 20px; display: inline-block;"><b>&nbsp;' . $volumeRatio . '&nbsp;</b></span>';

      $yesterdayVolumeHTML = '<span id="vol_yesterday" style="font-size: 12px; background-color:lightgrey; color: black; display: inline-block;"><b>YEST Vol - ' . $yesterdayVolume . '</b></span>'; 

      $currentVolumeHTML = '<span id="vol_current" style="font-size: 12px; background-color:#ff9999; color: black; display: inline-block;"><b>Vol - ' . $currentVolume . '</b></span>'; 

      // Calculate the finViz 3 month volume number
/*
                $finVizTDArray = $html->find('table.snapshot-table2 tbody tr.table-dark-row');
                $avgVolFinViz = "<span id='vol_fin_viz' style='background-color: orange'><b>FinVizAVG - " . calcFinVizAvgVolume($finVizTDArray[10]) . "</b></span>";
*/


/*
      // get Yahoo Finance average volume number.  We're going to skip yahoo finance avg vol for now, it's not that important 

      $command = escapeshellcmd('python3 ./pythonscrape/scrape-yahoo-finance-summary.py ' . $symbol);
      $yahooFinanceJson = shell_exec($command);

      $yahooFinanceObject = json_decode($yahooFinanceJson);

      $avgVolYahoo =  '<span id="vol_yahoo" style="background-color: orange; font-size: 20px;"><b>YahooAVG - ' . $yahooFinanceObject->avgvol . '</b></span>'; 
*/




      $avgVol10days = '<span id="vol_10_day" style="font-size: 20px; background-color:#CCFF99; color: black; display: inline-block;"><b>eTradeAVG - ' . number_format((int) $_GET['ten_day_volume']) . '</b></span>'; 

      // todo - put in the avgVol3Months from finviz.com
      // $avgVol3Months = 

      $google_keyword_string = $returnCompanyName; 
      $google_keyword_string = trim($google_keyword_string); 
      $google_keyword_string = preg_replace('/<h1>/', "", $google_keyword_string);
      $google_keyword_string = preg_replace('/<\/h1>/', "", $google_keyword_string);
      $google_keyword_string = preg_replace('/\(/', "", $google_keyword_string);
      $google_keyword_string = preg_replace('/\)/', "", $google_keyword_string);
      $google_keyword_string = preg_replace('/\,/', "", $google_keyword_string);
      $google_keyword_string = preg_replace('/ /', "+", $google_keyword_string);

      $google_keyword_string = preg_replace('/&/i', "+", $google_keyword_string);
      $google_keyword_string = preg_replace('/amp;/i', "+", $google_keyword_string);
      $google_keyword_string = preg_replace('/.international/i', "+", $google_keyword_string);
      $google_keyword_string = preg_replace('/inc\./i', "+", $google_keyword_string);
      $google_keyword_string = preg_replace('/ltd\./i', "+", $google_keyword_string);


      // Not RSS feed but just a google scrape with keywords

      $googleNewsFlag = "there is no google news"; 
      $googleNewsURL = "https://www.google.com/search?q=stock+" . $symbol . "&tbm=nws"; 

      $googleNewsResults = grabHTML("www.google.com", $googleNewsURL);

//      $googleNewsHtml = str_get_html($googleNewsResults);  

      if (
        preg_match('/minutes? ago/', $googleNewsResults) || 
        preg_match('/mins ago/', $googleNewsResults)  
      )
      {
          $googleNewsFlag = "there is google news"; 
      }

      // URL of Google News RSS feed

/*
      $googleNewsRSSFeed = simplexml_load_file('https://news.google.com/news/feeds?hl=en&gl=ca&q='.$google_keyword_string.'&um=1&ie=UTF-8&output=rss'); 

      $googleRSSArray = array();

      foreach ($googleNewsRSSFeed->channel->item as $feedItem) {
          if (!preg_match('/This RSS feed URL is deprecated/', $feedItem->title))
          {
              $time = strToTime($feedItem->pubDate);

              $publicationDateStrToTime = strtotime($feedItem->pubDate);
              $convertedDate = new DateTime(); 
              $convertedDate->setTimestamp($publicationDateStrToTime);

              $publicationDate = $feedItem->pubDate;
              $publicationDate = preg_replace("/[0-9][0-9]\:[0-9][0-9]\:[0-9][0-9] GMT/", "", $publicationDate); 
              $publicationTime = $convertedDate->format("g:i A");

              // red/green highlighting for AM/PM 
              for ($i = $yesterdayDays; $i >= 1; $i--)
              {
                  if (preg_match('/(' .  get_yahoo_trade_date($i) . ')/', $publicationDate))
                  {
                      $publicationTime = preg_replace('/PM/', '<span style="background-color: red">PM</span>', $publicationTime); 
                      if ($i == $yesterdayDays) 
                      {
                          $publicationTime = preg_replace('/AM/', '<span style="background-color: lightgreen">AM</span>', $publicationTime); 
                    
                      }
                      else
                      {
                          $publicationTime = preg_replace('/AM/', '<span style="background-color: red">AM</span>', $publicationTime); 
                      }  
                  }
              }

              $link = $feedItem->link; 
              $title = $feedItem->title; 

              $title = preg_replace('/' . $companyNameArray[0] . '/i', '<span style="font-size: 12px; background-color:lightblue; color:black"><b>' . $companyNameArray[0] . '</b></span>', $title);              

              $title = preg_replace('/' . $symbol . '/i', '<span style="font-size: 12px; background-color:lightblue; color:black"><b>' . $symbol . '</b></span>', $title);              

              $pubDate = $feedItem->pubDate; 
              $googleStruct = array();

              $googleStruct['link'] =  strval($feedItem->link);
              $googleStruct['title'] =  strval($title);
              $googleStruct['pub-date'] =  strval($publicationDate . " " . $publicationTime);

              $googleRSSArray[$time] = $googleStruct;
          }
      }

      krsort($googleRSSArray);

      $url = "https://www.stocksplithistory.com/?symbol=" . $symbol; 

      $results = grabHTML("www.stocksplithistory.com", $url);

      $html = str_get_html($results);  
      $splitsTable = $html->find('body center div table tbody tr td table'); 

      $stockSplitsTable = $splitsTable[4]; 

      if (preg_match('/latest/i', $stockSplitsTable))
      {
          $stockSplitsTable = "<table><tbody><tr><td><span style='font-style:arial; font-size: 25px;'>SPLIT NOT WORKING</span></td></tr></tbody></table>"; 
      }

//       $stockSplitsTable = preg_replace("/\<table border.*\/table\>?/", "", $stockSplitsTable); 

      $googleNews = "<ul class='newsSide'>";
      $googleNews .= "<li style='font-size: 20px !important; background-color: lightblue; '>Google News</li>";
      $i = 0;
      foreach ($googleRSSArray as $feedItem) {

          $i++;
          $googleNews .= "<li "; 

          if ($i % 2 == 1)
          {
            $googleNews .=  "style='background-color: #ebd8bd; '"; 
          };
          $googleNews .=  " ><a hr   
       ef='" . $feedItem['link'] . "'>" . $feedItem['pub-date'] . " - <br>" . $feedItem['title'] . "</a></li>";
      }
      $googleNews .=  "</ul>";

*/

      /*** Seeking Alpha RSS Parse ***/ 


      $rssSeekingAlpha = simplexml_load_file("https://seekingalpha.com/api/sa/combined/" . $originalSymbol . ".xml");

      $seekingAlphaNews = "<ul class='newsSide'>";
      $seekingAlphaNews .= "<li style='font-size: 20px !important; background-color: #00ff00;'>Seeking Alpha News</li>";

      $classActionAdded = false;
      $j = 0;


         if (isset($rssSeekingAlpha->channel->item) && count($rssSeekingAlpha->channel->item) > 0) {

          foreach ($rssSeekingAlpha->channel->item as $feedItem) {
            $j++;

            // Convert time from GMT to  AM/PM New York
            // 18000 is 5 hours X 60 seconds/minute X 60 minutes/hour
            $publicationDateStrToTime = strtotime($feedItem->pubDate) - 14400;

            $convertedDate = new DateTime(); 
            $convertedDate->setTimestamp($publicationDateStrToTime);

            $publicationDate = $feedItem->pubDate;
            $publicationDate = preg_replace("/[0-9][0-9]\:[0-9][0-9]\:[0-9][0-9] \-[0-9][0-9][0-9][0-9]/", "", $publicationDate); 
            $publicationTime = $convertedDate->format("g:i A");

            $newsTitle = $feedItem->title; 

            if (preg_match('/class.action/i', $newsTitle))
            {
                if ($classActionAdded == true)
                {
                  continue;              
                }
                else
                {
                  $classActionAdded = true;
                }
            }

            $seekingAlphaNews .= "<li "; 

            // red/green highlighting for yesterday/today
            for ($i = $yesterdayDays; $i >= 1; $i--)
            {
                if (preg_match('/(' .  get_yahoo_trade_date($i) . ')/', $publicationDate))
                {
                    $publicationTime = preg_replace('/PM/', '<span style="background-color: red; font-size: 18px;">PM</span>', $publicationTime); 
                    if ($i == $yesterdayDays) 
                    {
                        $publicationTime = preg_replace('/AM/', '<span style="background-color: #00ff00; ; font-size: 18px;">AM</span>', $publicationTime); 
                  
                    }
                    else
                    {
                        $publicationTime = preg_replace('/AM/', '<span style="background-color: red; font-size: 18px;">AM</span>', $publicationTime); 
                    }  
                }
            }

            if ($j % 2 == 1)
            {
              $seekingAlphaNews .=  "style='background-color: #ebd8bd; '"; 
            };
            
            // if the regular expression contains (.*) then we need to do it per title, to avoid greedy regular expressions

            $newsTitle = preg_replace('/ withdrawal(.*?)application/i', '<span style="font-size: 12px; background-color:red; color:black"><b> withdrawal $1 application (55%) </b></span> ', $newsTitle);
            $newsTitle = preg_replace('/nasdaq rejects(.*?)listing/i', '<span style="font-size: 12px; background-color:red; color:black"><b>Nasdaq rejects $1 listing</span> If delisting tomorrow 65%, if delisting days away then 50-55%</b>&nbsp;', $newsTitle);

            $seekingAlphaNews .=  " ><a target='_blank' href='$feedItem->link'> " . $publicationDate . " " . $publicationTime . " - <br>" . $newsTitle . "</a>";
          }
        }
        else 
        {
            $seekingAlphaNews .= "<br><br><span style='font-size: 45px;'>NO ITEMS FOUND</span>"; 
        }

      $seekingAlphaNews .=  "</ul>";

      $seekingAlphaNews .= "yesterdayDays = " . $yesterdayDays . "<br>"; 

    /*** End of Seeking Alpha RSS Parse ***/ 


      $finalReturn = "<td valign='top' style='width: 50%' >" . str_replace('<a ', '<a target="_blank" onclick="return openPage(this.href)" ', $allNews) . '</td><td valign="top" style="width: 50%">' . $stockSplitsTable . $seekingAlphaNews . /* str_replace('<a ', '<a target="_blank" onclick="return openPage(this.href)" ', $googleNews) .  */  '</td>';

      $finalReturn = preg_replace($patterns = array("/<img[^>]+\>/i", "/<embed.+?<\/embed>/im", "/<iframe.+?<\/iframe>/im", "/<script.+?<\/script>/im"), $replace = array("", "", "", ""), $finalReturn);

      $finalReturn = preg_replace('/Headlines/', '<b>Yahoo Headlines</b>', $finalReturn);
      $finalReturn = preg_replace('/<cite>/', '<cite> - ', $finalReturn);              
      $finalReturn = preg_replace('/<span>/', '<span style="font-size: 12px; background-color:#D8D8D8; color:black"><b> ', $finalReturn); 
      $finalReturn = preg_replace('/<\/span>/', '</b></span>', $finalReturn); 

      $finalReturn = preg_replace('/([A-Z][a-z][a-z] [0-9][0-9]:[0-9][0-9][A-Z]M EDT)|([A-Z][a-z][a-z] [0-9]:[0-9][0-9][A-Z]M EDT)/', '<span style="font-size: 12px; background-color:black; color:white">$1$2</span> ', $finalReturn);
      $finalReturn = preg_replace('/([A-Z][a-z][a-z] [0-9][0-9]:[0-9][0-9][A-Z]M EST)|([A-Z][a-z][a-z] [0-9]:[0-9][0-9][A-Z]M EST)/', '<span style="font-size: 12px; background-color:black; color:white">$1$2</span> ', $finalReturn);

      // light yellow highlighting for - from two weeks ago to a week ago.
      // light yellow is #fffdaf 
      for ($daysBack = 14; $daysBack > 6; $daysBack--)
      {
          $finalReturn = preg_replace('/(' .  get_yahoo_trade_date($daysBack) . ')/', '<span style="font-size: 12px; background-color:yellow; color:black">$1</span>', $finalReturn);
      }

      // darker yellow highlighting for - a weeks ago to (yesterday days)
      // yellow highlighting for before yesterday
      for ($daysBack = 5; $daysBack > $yesterdayDays; $daysBack--)
      {
          $finalReturn = preg_replace('/(' .  get_yahoo_trade_date($daysBack) . ')/', '<span style="font-size: 12px; background-color:yellow ; color:black">$1</span>', $finalReturn);      
      }

      // blue highlighting for yesterday
      for ($daysBack = $yesterdayDays; $daysBack >= 1; $daysBack--)
      {
          $finalReturn = preg_replace('/(' .  get_yahoo_trade_date($daysBack) . ')/', '<span style="font-size: 12px; background-color:#0747a1 ; color:white">$1</span>', $finalReturn);
      }

      $finalReturn = preg_replace('/(' .  get_yahoo_yesterday_trade_date() . ')/', '<span style="font-size: 12px; background-color:   #0747a1; color:white; border: 1px solid red;"> $1</span> ', $finalReturn);
      $finalReturn = preg_replace('/(' .  get_yahoo_todays_trade_date() . ')/', '<span style="font-size: 12px; background-color:  black; color:white; border: 1px solid red;"> $1</span> ', $finalReturn);

      $finalReturn = preg_replace('/ voluntarily delist/i', '<span style="font-size: 65px; background-color:red; color:black"><b> voluntarily delist - 65%</b></span>', $finalReturn);
      $finalReturn = preg_replace('/ voluntary delist/i', '<span style="font-size: 65px; background-color:red; color:black"><b> voluntary delist - 65%</b></span>', $finalReturn);
      $finalReturn = preg_replace('/ delist/i', '<span style="font-size: 65px; background-color:red; color:black"><b> delist </b></span>', $finalReturn);
      $finalReturn = preg_replace('/Delist/', '<span style="font-size: 65px; background-color:red; color:black"><b> delist </b></span>', $finalReturn);
      $finalReturn = preg_replace('/ chapter 11|chapter 11 /i', '<span style="font-size: 55px; background-color:red; color:black"><br><br><b> &nbsp;CHAPTER 11</b></span>', $finalReturn);
      $finalReturn = preg_replace('/ reverse split|reverse split /i', '<span style="font-size: 65px; background-color:red; color:black"><b> &nbsp;<br>REVERSE<br><br><br> SPLIT<br><br> - CHECK<br><br><br> DATE</b></span>', $finalReturn);
      $finalReturn = preg_replace('/ reverse.stock split|reverse stock split /i', '<div style="font-size: 65px; background-color:red; color:black; display: inline-block;"><b><br>REVERSE<br><br><br> SPLIT<br><br> - CHECK<br><br><br> DATE</b></div>', $finalReturn);
      $finalReturn = preg_replace('/ reverse.share split|reverse stock split /i', '<div style="font-size: 65px; background-color:red; color:black; display: inline-block;"><b><br>REVERSE<br><br><br> SPLIT<br><br> - CHECK<br><br><br> DATE</b></div>', $finalReturn);
      $finalReturn = preg_replace('/rbc capital downgrade/i', '<span style="font-size: 25px; background-color:red; color:black"><b> &nbsp;RBC CAPITAL DOWNGRADE - STAY AWAY</b></span>', $finalReturn);      
      $finalReturn = preg_replace('/ downgrade|downgrade /i', '<span style="font-size: 15px; background-color:red; color:black"><b> &nbsp;DOWNGRADE</b></span>', $finalReturn); 
      $finalReturn = preg_replace('/ lowered to/i', '<span style="font-size: 15px; background-color:red; color:black"><b> &nbsp;LOWERED TO</b></span>', $finalReturn);      
      $finalReturn = preg_replace('/ ex-dividend|ex-dividend /i', '<div style="font-size: 12px; background-color:red; display: inline-block;">EX-DIVIDEND (chase at 25%)</div>', $finalReturn);
      $finalReturn = preg_replace('/ sales miss|sales miss /i', '<span style="font-size: 12px; background-color:red; color:black"><b> &nbsp;SALES MISS (Chase at 65-70%)</b>&nbsp;</span>', $finalReturn);      
      $finalReturn = preg_replace('/ miss sales|miss sales /i', '<span style="font-size: 12px; background-color:red; color:black"><b> &nbsp;MISS SALES (Chase at 65-70%)</b>&nbsp;</span>', $finalReturn);
      $finalReturn = preg_replace('/ disappointing sales|disappointing sales /i', '<span style="font-size: 12px; background-color:red; color:black"><b> &nbsp;DISAPPOINTINT SALES (Chase at 65-70%)</b>&nbsp;</span>', $finalReturn);      
      $finalReturn = preg_replace('/ sales results|sales results /i', '<span style="font-size: 12px; background-color:red; color:black"><b> &nbsp;SALES RESULTS (If bad, chase at 65-70%)</b>&nbsp;</span>', $finalReturn);      
      $finalReturn = preg_replace('/ ..-k/i', '<br><br><span style="font-size: 35px; background-color:red; color:black"><b>&nbsp;$1 - LOOK</span></b>&nbsp;<br><br>', $finalReturn);
      $finalReturn = preg_replace('/ accountant/i', '<span style="font-size: 25px; background-color:red; color:black"><b> &nbsp;accountant (if hiring new accountant, 35-40%)</b>&nbsp;</span>', $finalReturn);            
      $finalReturn = preg_replace('/ clinical trial/i', '<span style="font-size: 12px; background-color:red; color:black"><b> &nbsp;clinical trial</b>&nbsp;</span>', $finalReturn);            
      $finalReturn = preg_replace('/ recall/i', '<span style="font-size: 25px; background-color:red; color:black"><b> &nbsp;recall (bad, back off)</b>&nbsp;</span>', $finalReturn);                  
      $finalReturn = preg_replace('/ etf/i', '<span style="font-size: 12px; background-color:red; color:black"><b> &nbsp;ETF</b>&nbsp;</span>', $finalReturn);                        
      $finalReturn = preg_replace('/ etn/i', '<span style="font-size: 12px; background-color:red; color:black"><b> &nbsp;ETN</b>&nbsp;</span>', $finalReturn);                        
      $finalReturn = preg_replace('/[ \']disruption[ \']/i', '<span style="font-size: 12px; background-color:red; color:black"><b> &nbsp;disruption&nbsp;</span> (chase at 52%)</b>', $finalReturn);
      $finalReturn = preg_replace('/ abandon/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp;abandon&nbsp;</span> (65-70%)</b>', $finalReturn);
      $finalReturn = preg_replace('/ bankrupt/i', '<span style="font-size: 35px; background-color:red; color:black"><b><br><br>&nbsp;bankrupt - are they winding down?  Check for sale of assets</span></b>', $finalReturn);      
      $finalReturn = preg_replace('/ terminate| terminates| terminated| termination/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp;terminate&nbsp;</span> (65% dollar, 75% penny) </b>', $finalReturn);            

      $finalReturn = preg_replace('/ drug/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp;drug </span></b> ', $finalReturn);

      $finalReturn = preg_replace('/ guidance/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp;GUIDANCE </span></b>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ regulatory update/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp;regulatory update (35% even if regulation is good)</span></b>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ suspended/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp;suspended</span> (65-70%)</b>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ fraud/i', '<span style="font-size: 45px; background-color:red; color:black"><b>&nbsp;fraud</span></b>&nbsp;', $finalReturn);      
      $finalReturn = preg_replace('/ dividend/i', '<span style="font-size: 55px; background-color:red; color:black"><br><br><b>&nbsp;DIVIDEND<br><br> - CHECK<br><br> ISSUING<br><br> DATE)</span></b>&nbsp;', $finalReturn);             
      $finalReturn = preg_replace('/ strategic alternatives/i', '<span style="font-size: 55px; background-color:red; color:black"><b>&nbsp;strategic alternatives - check if winding down</span></b>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ unpatentable/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp;unpatentable</span> (60%)</b>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ accelerate or increase/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp;accelerate or increase</span> (Possible Chapter 11, stay away)</b>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ denial of application/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp;denial of application</span> (65%)</b>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ restructuring support agreement/i', '<span style="font-size: 15px; background-color:red; color:black"><b>&nbsp;Restructuring Support Agreement</span> (53%)</b>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ breach of contract/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp;breach of contract</span> (If lost lawsuit, then 75%, if won then 35% premarket/first round)</b>&nbsp;', $finalReturn);      
      $finalReturn = preg_replace('/ jury verdict/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp;jury verdict</span> BE CAREFUL (If lost major lawsuit, then 70-75%)</b>&nbsp;', $finalReturn);      
      $finalReturn = preg_replace('/ fcc/i', '<span style="font-size: 15px; background-color:red; color:black"><b>&nbsp;FCC</span> if regulation had long-term ratifications, then 65-70%</b>&nbsp;', $finalReturn);      
      $finalReturn = preg_replace('/ layoffs/i', '<span style="font-size: 15px; background-color:red; color:black"><b>&nbsp;layoffs</span> (check Jay\'s percentages)</b>&nbsp;', $finalReturn);      
      $finalReturn = preg_replace('/ layoff/i', '<span style="font-size: 15px; background-color:red; color:black"><b>&nbsp;layoff</span> (check Jay\'s percentages)</b>&nbsp;', $finalReturn);      
      $finalReturn = preg_replace('/ lays off/i', '<span style="font-size: 15px; background-color:red; color:black"><b>&nbsp;lays off</span> (check Jay\'s percentages)</b>&nbsp;', $finalReturn);      
      $finalReturn = preg_replace('/ restructuring/i', '<br><br><span style="font-size: 45px; background-color:red; color:black"><b>&nbsp;restructuring</span><br></b>&nbsp;', $finalReturn);      
      $finalReturn = preg_replace('/ restructure/i', '<br><br><span style="font-size: 45px; background-color:red; color:black"><b>&nbsp;restructure</span><br></b>&nbsp;', $finalReturn);      
      $finalReturn = preg_replace('/ clinical hold/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp;clinical hold</span> (65 - 70%)</b>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ convertible senior notes/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; convertible senior notes (back off until you see a price)</b></span>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ amendment(.*) to secured credit facilities/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp; amendments to secured credit facilities (65%)</b></span>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ notice of effectiveness/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp; notice of effectiveness (40% till you see the notice)</b></span>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ equity investment/i', '<span style="font-size: 15px; background-color:red; color:black"><b>&nbsp; equity investment - look for price of new shares</b></span>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ lease termination/i', '<span style="font-size: 15px; background-color:red; color:black"><b>&nbsp; DANGER - Chapter 7 warning - 90%</b></span>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ redemption of public shares/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp; Redemption of Public Shares - 92%</b></span>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ investor call/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; INVESTOR CALL - CHECK THE DATE &nbsp;</b></span>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ strategic update/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; STRATEGIC UPDATE - BE CAREFUL &nbsp;</b></span>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ strategic shift/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; STRATEGIC SHIFT - BE CAREFUL &nbsp;</b></span>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ attorney general/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; attorney general (if there is an attorney general probe then 45-50%) &nbsp;</b></span>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ merger/i', '<span style="font-size: 55px; background-color:red; color:black"><br><br><b>&nbsp; MERGER<br><br> - STAY AWAY</b></span><span style="font-size: 25px; background-color:red; color:black"><br><br><b>&nbsp; LET IT SETTLE FOR A COUPLE DAYS</b></span>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ take private/i', '<span style="font-size: 55px; background-color:red; color:black"><b>&nbsp; TAKE PRIVATE<br><br> - STAY AWAY</b></span>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ preliminary(.*?)outlook/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp; Preliminary$1Outlook -</span> <span style="font-size: 12px; background-color:lightgreen; color:black">41% right off the bat, then 48% literally 3 minutes later.  TAKE NO MORE THAN 5% AND BAIL &nbsp;</b></span>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ conference call to provide update/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp; Conference Call to Provide Update -</span> <span style="font-size: 12px; background-color:lightgreen; color:black">CHECK THE DATE/TIME OF THE CALL &nbsp;</b></span>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ seeking alpha/i', '<span style="font-size: 25px; background-color:red; color:black">SEEKING ALPHA &nbsp;</b></span>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ fictitious sales/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp; fictitious sales - STAY AWAY </b></span> &nbsp;', $finalReturn);     
      $finalReturn = preg_replace('/ board of directors/i', '<span style="font-size: 15px; background-color:red; color:black"><b>&nbsp; board of directors - if no big deal then 20% </b></span> &nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ class action/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; class action - 20-23%</b></span> &nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ business combination/i', '<span style="font-size: 55px; background-color:red; color:black"><br><br><b>&nbsp; BUSINESS<br><br> COMBINATION<br><br> - STAY<br><br>AWAY<br><br> </b></span> &nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ annual meeting of shareholders/i', '<span style="font-size: 15px; background-color:red; color:black"><b>&nbsp; annual meeting of shareholders - 40% early</b></span> &nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ transcript/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; transcript </b></span> &nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ share consolidation/i', '<span style="font-size: 65px; background-color:red; color:black"><b>&nbsp; <br><br>REVERSE<br><br><br> SPLIT<br><br> - CHECK<br><br><br> DATE</b></span> &nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ share exchange transaction/i', '<span style="font-size: 20px; background-color:red; color:black"><b>&nbsp; share exchange transaction - 47% </b></span> &nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ closes(.*)offering/i', '<span style="font-size: 22px; background-color:red; color:black"><b>&nbsp; closes offering - be careful, 24%</b></span>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ announces pricing/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; Announces Pricing </b></span>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ late interest payment/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; late interest payment - BACK OFF, POSSIBLE CHAPTER 11 </b></span>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ q1 loss/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; Q1 LOSS </b></span>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ q2 loss/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; Q2 LOSS </b></span>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ q3 loss/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; Q3 LOSS </b></span>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ q4 loss/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; Q4 LOSS </b></span>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ first.quarter/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; FIRST QUARTER </b></span>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ second.quarter/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; SECOND QUARTER </b></span>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ third.quarter/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; THIRD QUARTER </b></span>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ fourth.quarter/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; FOURTH QUARTER </b></span>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ q1- results/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; Q1 - RESULTS </b></span>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ q2 - results/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; Q2 - RESULTS </b></span>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ q3 - results/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; Q3 - RESULTS </b></span>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ q4 - results/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; Q4 - RESULTS </b></span>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ q1 earnings/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; Q1 EARNINGS </b></span>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ q2 earnings/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; Q2 EARNINGS </b></span>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ q3 earnings/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; Q3 EARNINGS </b></span>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ q4 earnings/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; Q4 EARNINGS </b></span>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ withdrawal of auditors\' report/i', "<span style=\"font-size: 25px; background-color:red; color:black\"><b>&nbsp; Withdrawal of Auditors' Report - 40\% </b></span>&nbsp;", $finalReturn);
      $finalReturn = preg_replace('/ withdraws audit report/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; withdraws audit report - 40% </b></span>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ withdrawing audits/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; withdrawing audits - 40% </b></span>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ move to otc/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; MOVE TO OTC - DELISTING - BE CAREFUL </b></span>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ corporate update/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; CORPORATE UPDATE - BACK OFF </b></span>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ provides update/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; PROVIDES UPDATE - BACK OFF </b></span>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ external advisor| external adviser/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; EXTERNAL ADVISOR - BACK OFF </b></span>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ turnaround/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; TURNAROUND - BACK OFF </b></span>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ clinical.stage/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; CLINICAL STAGE - BACK OFF </b></span>&nbsp;', $finalReturn);      
      $finalReturn = preg_replace('/ ratio change/i', '<span style="font-size: 65px; background-color:red; color:black"><b>&nbsp; <br><br>REVERSE<br><br><br> SPLIT<br><br> - CHECK<br><br><br> DATE</b></span>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ 10\-q/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; 10-Q </b></span>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ registered direct offering/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; REGISTERED DIRECT OFFERING </b></span>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ fda clearance/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; FDA Clearance - back off </b></span>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ deficiency in compliance/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; Deficiency in Compliance - CHECK FOR DELISTING</b></span>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ net asset value/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; NET ASSET VALUE - 25%</b></span>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ efficacy data/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; EFFICACY DATA - DRUG NEWS</b></span>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ phase 1/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; PHASE 1!!!!</b></span>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ phase 2/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; PHASE 2!!!!</b></span>&nbsp;', $finalReturn); 
      $finalReturn = preg_replace('/ phase 3/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; PHASE 3!!!!</b></span>&nbsp;', $finalReturn); 
      $finalReturn = preg_replace('/ to present/i', '<span style="font-size: 65px; background-color: red; color:black"><b><br>TO<br><br>PRESENT<br><br>CHECK<br><br>DATE</b><br></span>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ to participate/i', '<span style="font-size: 65px; background-color: red; color:black"><b><br>TO<br><br>PARTICIPATE<br><br>CHECK<br><br>DATE</b><br></span>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ to co.host/i', '<span style="font-size: 65px; background-color: red; color:black"><b><br>TO<br><br>CO-HOST<br><br>CHECK<br><br>DATE</b><br></span>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ shareholder investigation/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; SHAREHOLDER INVESTIGATION - 19.5%</b></span>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ announces an investigation/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; ANNOUNCES AN INVESTIGATION - 19.5%</b></span>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ convertible bonds/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; convertible bonds (back off until you see a price)</b></span>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ equity grants/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; EQUITY GRANTS - (20-23% early on)</b></span>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ to announce/i', '<span style="font-size: 25px; background-color: red; color:black"><b>&nbsp; TO ANNOUNCE - CHECK THE DATE </b></span>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ to report/i', '<span style="font-size: 55px; background-color:red; color:black"><b>&nbsp; TO<br><br> REPORT<br><br> - CHECK<br><br> DATE </b></span>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ to host/i', '<span style="font-size: 25px; background-color: red; color:black"><b>&nbsp; TO HOST - CHECK THE DATE </b></span>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ to release/i', '<span style="font-size: 25px; background-color: #202020; color:white"><b>&nbsp; TO RELEASE - CHECK THE DATE </b></span>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ schedules/i', '<span style="font-size: 25px; background-color: #202020; color:white"><b>&nbsp; SCHEDULES - CHECK THE DATE </b></span>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ sets date for the release of/i', '<span style="font-size: 25px; background-color: #202020; color:white"><b>&nbsp; SETS DATE FOR THE RELEASE OF - CHECK THE DATE </b></span>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ collaboration/i', '<span style="font-size: 25px; background-color: red; color:black"><b>&nbsp; COLLABORATION - CAREFUL </b></span>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ china/i', '<span style="font-size: 65px; background-color: red; color:black"><br><br><b>&nbsp; CHINA </b></span>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ taiwan/i', '<span style="font-size: 65px; background-color: red; color:black"><b>&nbsp; TAIWAN </b></span>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ hong kong/i', '<span style="font-size: 65px; background-color: red; color:black"><b>&nbsp; HONG KONG </b></span>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ kerrisdale/i', '<span style="font-size: 25px; background-color: red; color:black"><b>&nbsp; Kerrisdale - 65% </b></span>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ q1 results/i', '<span style="font-size: 25px; background-color: red; color:black"><b>&nbsp; Q1 RESULTS </b></span>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ q2 results/i', '<span style="font-size: 25px; background-color: red; color:black"><b>&nbsp; Q2 RESULTS </b></span>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ q3 results/i', '<span style="font-size: 25px; background-color: red; color:black"><b>&nbsp; Q3 RESULTS </b></span>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ q4 results/i', '<span style="font-size: 25px; background-color: red; color:black"><b>&nbsp; Q4 RESULTS </b></span>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ clinical/i', '<span style="font-size: 25px; background-color: red; color:black"><b>&nbsp; CLINICAL - DRUG NEWS </b></span>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ preclinical/i', '<span style="font-size: 25px; background-color: red; color:black"><b>&nbsp; PRECLINICAL - DRUG NEWS </b></span>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ to showcase/i', '<span style="font-size: 25px; background-color: red; color:black"><b>&nbsp; TO SHOWCASE - CHECK THE DATE </b></span>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ rescue financing/i', '<span style="font-size: 25px; background-color: red; color:black"><b>&nbsp; RESCUE FINANCING - BE CAREFUL</b></span>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ begins trading/i', '<span style="font-size: 25px; background-color: red; color:black"><b>&nbsp; BEGINS TRADING - 29%</b></span>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ consider shorting/i', '<span style="font-size: 25px; background-color: red; color:black"><b>&nbsp; CONSIDER SHORTING - 35%</b></span>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ liquidity/i', '<span style="font-size: 25px; background-color: red; color:black"><b>&nbsp; LIQUIDITY - BACK OFF</b></span>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/annual report/i', '<span style="font-size: 25px; background-color: red; color:black"><b>&nbsp; ANNUAL REPORT</b></span>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/IPO/', '<span style="font-size: 55px; background-color: red; color:black"><b>&nbsp; IPO</b></span>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ call put ratio/i', '<span style="font-size: 25px; background-color: red; color:black"><b>&nbsp; CALL PUT RATIO - OK AT 20%</b></span>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ (s-[0-9])/i', '<span style="font-size: 35px; background-color:red; color:black"><b>&nbsp;$1</span></b>&nbsp;', $finalReturn);   
      $finalReturn = preg_replace('/(Files \$.*M)/i', '<span style="font-size: 25px; background-color: red; color:black"><b>&nbsp; $1 - OFFERING!!!</b></span>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ (cuts.*?guidance)/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp;$1</span></b>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ chapter 22/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp;CHAPTER 22</span></b>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ (disappointing.*?results)/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp;$1</span></b>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ (reports.*?results)/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp;$1 - IF PRELIMINARY AND NO INCOME/LOSS IS MENTIONED YOU CAN GO IN AT 40%</span></b>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ EPS/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp;EPS </span></b>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ committee investigation/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; COMMITTEE INVESTIGATION - STAY AWAY</span></b>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ (posts.*?data)/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp;$1</span></b>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ (illegally.*\")/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp;$1 - STAY AWAY</span></b>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ at+the+market/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp;AT THE MARKET - ADJUSTMENT COULD HAPPEN</span></b>&nbsp;', $finalReturn);      
      $finalReturn = preg_replace('/ debt financing/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp;DEBT FINANCING - STAY AWAY - FOR DAYS</span></b>&nbsp;', $finalReturn);   
      $finalReturn = preg_replace('/ liquidation/i', '<span style="font-size: 35px; background-color:red; color:black"><b>&nbsp;LIQUIDATION - STAY AWAY</span></b>&nbsp;', $finalReturn);   
      $finalReturn = preg_replace('/ SPAC /i', '<span style="font-size: 35px; background-color:red; color:black"><b>&nbsp;SPAC - STAY AWAY</span></b>&nbsp;', $finalReturn);   
      $finalReturn = preg_replace('/ to begin trading/i', '<span style="font-size: 35px; background-color:red; color:black"><b>&nbsp;TO BEGIN TRADING - CHECK THE DATE</span></b>&nbsp;', $finalReturn);   
      $finalReturn = preg_replace('/ distribution ratios/i', '<span style="font-size: 55px; background-color:red; color:black"><b>&nbsp;DISTRIBUTION<br><br> RATIOS<br><br> - CHECK DATE</span></b>&nbsp;', $finalReturn);   
      $finalReturn = preg_replace('/ distribution date/i', '<span style="font-size: 55px; background-color:red; color:black"><b>&nbsp;DISTRIBUTION<br><br> DATE<br><br> - CHECK DATE</span></b>&nbsp;', $finalReturn);   
      $finalReturn = preg_replace('/ Hindenburg/i', '<span style="font-size: 55px; background-color:red; color:black"><b>&nbsp;HINDENBERG<br><br> RESEARCH<br><br> - STAY AWAY</span></b>&nbsp;', $finalReturn);   
      $finalReturn = preg_replace('/ mentioned cautiously/i', '<span style="font-size: 35px; background-color:red; color:black"><b>&nbsp;MENTIONED CAUTIOUSLY - STAY AWAY</span></b>&nbsp;', $finalReturn);   
      $finalReturn = preg_replace('/ to join /i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp;TO JOIN - TREAT AS HIGH RISK, 21-23%</span></b>&nbsp;', $finalReturn);   
      $finalReturn = preg_replace('/ upcoming /i', '<span style="font-size: 55px; background-color:red; color:black"><b>&nbsp;UPCOMING<br><br> - CHECK<br><br> DATE</span></b>&nbsp;', $finalReturn);   
      $finalReturn = preg_replace('/ listing deficiency /i', '<span style="font-size: 55px; background-color:red; color:black"><b>&nbsp;LISTING DEFICIENCY<br><br> - CHECK<br><br> DATE</span></b>&nbsp;', $finalReturn);   
      $finalReturn = preg_replace('/ mentioned as short/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp;MENTIONED AS SHORT - BACK OFF</span></b>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ transaction support agreement/i', '<span style="font-size: 55px; background-color:red; color:black"><br><br><b>&nbsp;TRANSACTION SUPPORT AGREEMENT - BANKRUPTCY</span><br><br></b>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ to highlight/i', '<span style="font-size: 65px; background-color:red; color:black"><br><br><b>&nbsp;TO<br><br>HIGHLIGHT<br><br>CHECK<br><br>DATE<br><br></span></b>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ short report/i', '<br><br><span style="font-size: 35px; background-color:red; color:black"><b>SHORT REPORT - STAY AWAY</span></b>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ mixed shelf/i', '<span style="font-size: 35px; background-color:red; color:black"><b>MIXED SHELF - OFFERING</span></b>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ closing of private placement/i', '<span style="font-size: 35px; background-color:red; color:black"><b>CLOSING OF PRIVATE PLACEMENT</span></b>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ files to sell/i', '<span style="font-size: 25px; background-color:red; color:black"><b>FILES TO SELL - OFFERING</span></b>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ delays filing/i', '<span style="font-size: 35px; background-color:red; color:black"><b>DELAYS FILING - 40%</span></b>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ recapitalization/i', '<span style="font-size: 35px; background-color:red; color:black"><b>RECAPITALIZATION - BANKRUPTCY</span></b>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ department of justice/i', '<span style="font-size: 25px; background-color:red; color:black"><b> DEPARTMENT OF JUSTICE - STAY AWAY</span></b>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ license agreements/i', '<span style="font-size: 25px; background-color:red; color:black"><b>LICENSE AGREEMENTS - 40% NEWS UPDATE</b></span>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ private placement/i', '<span style="font-size: 25px; background-color:red; color:black"><b>PRIVATE PLACEMENT - CHECK INSIDE ARTICLE FOR PRICE</b></span>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ SEC investigation/i', '<span style="font-size: 25px; background-color:red; color:black"><b>SEC INVESTIGATION - STAY AWAY</b></span>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ U\.S\. probe/i', '<span style="font-size: 25px; background-color:red; color:black"><b>U.S. PROBE - STAY AWAY</b></span>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ to submit/i', '<span style="font-size: 25px; background-color:red; color:black"><b>TO SUBMIT - CHECK DATE - IF IN THE FUTURE THEN ITS OK TO CHASE AT USUAL ENTRY</b></span>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ launch/i', '<span style="font-size: 20px; background-color:orange; color:black; "><b>LAUNCH - IF ABOUT TO LAUNCH OR LAUNCH IS SUCCESSFUL, 18-20%.  IF FAILED LAUNCH, 40%</b></span>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ to supply/i', '<span style="font-size: 20px; background-color:#00ff00; color:black; "><b>TO SUPPLY - THIS IS OK</b></span>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ enters partnership/i', '<span style="font-size: 20px; background-color:#00ff00; color:black; "><b>ENTERS PARTNERSHIP - THIS IS OK</b></span>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ announces partnership/i', '<span style="font-size: 20px; background-color:#00ff00; color:black; "><b>ANNOUNCES PARTNERSHIP - THIS IS OK</b></span>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ shareholder update/i', '<span style="font-size: 25px; background-color:red; color:black; "><b>SHAREHOLDER UPDATE - 40%</b></span>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ granted extension (.*?) listing/i', '<span style="font-size: 25px; background-color:red; color:black; "><b>GRANTED EXTENSION $1 LISTING - 40%</b></span>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ preliminary purchase/i', '<span style="font-size: 25px; background-color:red; color:black; "><b>PRELIMINARY PURCHASE - 40%</b></span>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ patent filing/i', '<span style="font-size: 25px; background-color:red; color:black; "><b>patent filing - 40% news update</b></span>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ insolvency/i', '<span style="font-size: 25px; background-color:red; color:black; "><b>INSOLVENCY - STAY AWAY</b></span>&nbsp;', $finalReturn); 
      $finalReturn = preg_replace('/ (announces.*?presentation)/i', '<span style="font-size: 25px; background-color:red; color:black; "><b>$1 - CHECK DATE</b></span>&nbsp;', $finalReturn); 
      $finalReturn = preg_replace('/ exercise of warrants/i', '<span style="font-size: 25px; background-color:red; color:black; "><b>EXERCISE OF WARRANTS - STAY AWAY</b></span>&nbsp;', $finalReturn); 
      $finalReturn = preg_replace('/ (regains .*?compliance)/i', '<span style="font-size: 25px; background-color:red; color:black; "><b>$1 - 30-35%</b></span>&nbsp;', $finalReturn); 
      $finalReturn = preg_replace('/ successfully delivers/i', '<span style="font-size: 20px; background-color:#00ff00; color:black; "><b> SUCCESSFULLY DELIVERS - THIS IS OK AT 18-20%</b></span>&nbsp;', $finalReturn); 
      $finalReturn = preg_replace('/ notice of allowance/i', '<span style="font-size: 20px; background-color:#00ff00; color:black; "><b> NOTICE OF ALLOWANCE - THIS IS OK AT 18-20%</b></span>&nbsp;', $finalReturn); 
      $finalReturn = preg_replace('/ (closes.*loan)/i', '<span style="font-size: 20px; background-color:red; color:black; "><b> $1 - NEWS UPDATE - 40%</b></span>&nbsp;', $finalReturn); 
      $finalReturn = preg_replace('/ announces financing/i', '<span style="font-size: 20px; background-color:red; color:black; "><b> ANNOUNCES FINANCING - BACK OFF, COULD BE A SHARE PRICE</b></span>&nbsp;', $finalReturn); 
      $finalReturn = preg_replace('/ public offering/i', '<span style="font-size: 25px; background-color:red; color:black; "><b> PUBLIC OFFERING - STAY AWAY</b></span>&nbsp;', $finalReturn); 
      $finalReturn = preg_replace('/ share offering/i', '<span style="font-size: 25px; background-color:red; color:black; "><b> SHARE OFFERING - STAY AWAY</b></span>&nbsp;', $finalReturn); 
      $finalReturn = preg_replace('/ purchase order/i', '<span style="font-size: 25px; background-color:red; color:black; "><b> PURCHASE ORDER - BACK OFF, EVEN IF IT LOOKS GOOD</b></span>&nbsp;', $finalReturn); 
      $finalReturn = preg_replace('/ withdraws appliction/i', '<span style="font-size: 25px; background-color:red; color:black; "><b>WITHDRAWS APPLICATION</b></span>&nbsp;', $finalReturn); 
      $finalReturn = preg_replace('/ review options/i', '<span style="font-size: 25px; background-color:red; color:black; "><b>REVIEW OPTIONS</b></span><br><br><span style="font-size: 50px; background-color:red; color:black; "><b>STAY AWAY</b></span>&nbsp;', $finalReturn); 
      $finalReturn = preg_replace('/ ceo exits/i', '<span style="font-size: 25px; background-color:red; color:black; "><b>CEO EXITS</b></span><br><br><span style="font-size: 50px; background-color:red; color:black; "><b>STAY AWAY</b></span>&nbsp;', $finalReturn); 
      $finalReturn = preg_replace('/ strategic review/i', '<span style="font-size: 25px; background-color:red; color:black; "><b>STRAGETIC REVIEW</b></span><br><br><span style="font-size: 50px; background-color:red; color:black; "><b>STAY AWAY</b></span>&nbsp;', $finalReturn); 





      $returnArray['dividendCheckDate'] = 0; 
      if (preg_match('/dividend/i', $finalReturn))
      {
        $returnArray['dividendCheckDate'] = 1; 
      }
      $returnArray['checkReportDate'] = 0;
      if (preg_match('/to report/i', $finalReturn))
      {
        $returnArray['checkReportDate'] = 1;
      }
      $returnArray['checkAnnouncementDate'] = 0;
      if (preg_match('/to announce/i', $finalReturn))
      {
        $returnArray['checkAnnouncementDate'] = 1;
      }
      $returnArray['checkPresentationDate'] = 0;
      if (preg_match('/to present/i', $returnHtml))
      {
        $returnArray['checkPresentationDate'] = 1;
      }
      if (preg_match('/to highlight/i', $returnHtml))
      {
        $returnArray['checkHighlightDate'] = 1;
      }
      if (preg_match('/to participate/i', $returnHtml))
      {
        $returnArray['checkParticipationDate'] = 1;
      }


       $message_board = '</font><a target="_blank" onclick="return openPage(this.href)" href="http://finance.yahoo.com/quote/' . $symbol . '/community?ltr=1"> Yahoo Message Boards</a>&nbsp;&nbsp;&nbsp;&nbsp;'; 
      $company_profile = '<a target="_blank" onclick="return openPage(this.href)" href="http://finance.yahoo.com/quote/' . $symbol . '/profile">Yahoo Company Profile</a> &nbsp;'; 
      $yahoo_main_page = '<a target="_blank" href="http://finance.yahoo.com/q?s=' . $symbol . '&ql=1">Yahoo Main Page</a>&nbsp;&nbsp';
      $yahoo_5_day_chart = '<a target="_blank" href="http://finance.yahoo.com/echarts?s=' . $symbol . '+Interactive#symbol=' . $symbol . ';range=5d">5-day Chart for ' . $symbol . '</a><br><br>';
      $google = '<a target="_blank" onclick="return openPage(this.href)" href="https://www.google.com/search?hl=en&gl=us&tbm=nws&authuser=1&q=' . $google_keyword_string . '">Google news</a>';
        $google = preg_replace('/<h1>/', '', $google);
        $google = preg_replace('/<\/h1>/', '', $google);
      $nasdaqInfo = '&nbsp;&nbsp;<a target="_blank" onclick="return openPage(this.href)" href="https://www.nasdaq.com/symbol/' . $symbol . '/sec-filings"> Nasdaq Info</a>&nbsp;&nbsp;'; 
      $streetInsider = '&nbsp;&nbsp;<a target="_blank" onclick="return openPage(this.href)" href="https://www.streetinsider.com/stock_lookup.php?LookUp=Get+Quote&q=' . $symbol . '"> SI</a>&nbsp;&nbsp;'; 

      $streetInsiderScrape = '&nbsp;&nbsp;<a target="_blank" onclick="return openPage(this.href)" href="./scrape-street-insider.php?symbol=' . $symbol . '"> SI Scrape</a>&nbsp;&nbsp;';  




      $splits = '&nbsp;&nbsp;<a target="_blank" onclick="return openPage(this.href)" href="https://www.stocksplithistory.com/?symbol=' . $symbol . '"> Splits</a>&nbsp;&nbsp;'; 

      $marketStackFromDate = getYMDTradeDate(100); 
      $marketStackToDate = getYMDTradeDate(1); 
      $marketStackURL = "https://api.marketstack.com/v2/eod?access_key=d36ab142bed5a1430fcde797063f6b9a&symbols=" . $symbol . "&date_from=" . $marketStackFromDate . "&date_to=" . $marketStackToDate;         
      $marketStackOHLC = '&nbsp;&nbsp;<a target="_blank" onclick="return openPage(this.href)" href= ' . $marketStackURL . '> OHLC</a>&nbsp;&nbsp;&nbsp;&nbsp;'; 

      $seekingAlphaURL = "https://seekingalpha.com/symbol/" . $originalSymbol; 
      $seekingAlpha = '&nbsp;&nbsp;<a target="_blank" onclick="return openPage(this.href)" href= ' . $seekingAlphaURL . '> Seeking Alpha' . '</a>&nbsp;&nbsp;&nbsp;&nbsp;'; 

      $otcMarketsURL = "https://www.otcmarkets.com/stock/" . $symbol . "/profile";
      $otcMarkets = '&nbsp;&nbsp;<a target="_blank" onclick="return openPage(this.href)" href= ' . $otcMarketsURL . '> OTC Markets' . '</a>&nbsp;&nbsp;&nbsp;&nbsp;'; 

      $financialModelingPrep = '&nbsp;&nbsp;<a target="_blank" onclick="return openPage(this.href)" href= ' . $apiUrl . '> FinModelPrepApi' . '</a>&nbsp;&nbsp;&nbsp;&nbsp;'; 

      $eTrade = '&nbsp;&nbsp;<a target="_blank" href="https://www.etrade.wallst.com/v1/stocks/snapshot/snapshot.asp?ChallengeUrl=https://idp.etrade.com/idp/SSO.saml2&reinitiate-handshake=0&prospectnavyear=2011&AuthnContext=prospect&env=PRD&symbol=' . $symbol . '&rsO=new&country=US">E*TRADE news for ' . $symbol . '</a>';

      $finalReturn = $yahooDates . $returnCompanyName . $companyWebsite . $sectorCountry . $returnYesterdaysClose . $preMarketYesterdaysClose[0] . "<br>" . "<div style='display: inline-block;'>" . /* $yesterdayVolumeHTML . */ $currentVolumeHTML .  /* $volumeRatioHTML . */ $avgVol10days . /* $avgVolYahoo . */ $company_profile . $yahoo_main_page . $message_board . $google . $nasdaqInfo . $streetInsider . $streetInsiderScrape . $splits . $marketStackOHLC . $seekingAlpha . $otcMarkets . $financialModelingPrep . $eTrade . '<table width="700px"><tr width="575px">' . $finalReturn . '</tr></table>' . $googleNewsFlag . $googleNewsHtmlDOM[0];  

      $tradeHaltsArray = getTradeHalts(); 

      $returnArray['halt_symbol_list'] = $tradeHaltsArray['halt_symbol_list']; 
      $returnArray['currently_halted'] = $tradeHaltsArray['currently_halted']; 
      $returnArray['final_return'] = $finalReturn;
      $returnArray['cik'] = $cik; 
      $returnArray['description'] = $description; 
      $returnArray['descriptionRegex'] = $descriptionRegex; 
      $returnArray['ceo'] = $ceo; 

      echo json_encode($returnArray); 

} // if ($which_website == "yahoo")
else if ($which_website == "bigcharts")
{
      // grab the last value from bigcharts
      $url = "https://bigcharts.marketwatch.com/quickchart/quickchart.asp?symb=" . $symbol . "&insttype=&freq=9&show=&time=1&rand=" . rand(); 
      $result = grabHTML("bigcharts.marketwatch.com", $url);
      $html = str_get_html($result);  

      $command = escapeshellcmd('python3 ./pythonscrape/scrape-bigcharts.py ' . $symbol);
      $bigChartsValues = shell_exec($command);

      $values = explode('|', $bigChartsValues); 

      $bigChartsPercentage = $values[0];
      $bigChartsLast = $values[1]; 
      $bigChartsTime = $values[2]; 

      echo   $bigChartsPercentage . "|" . $bigChartsLast . "|" . $bigChartsTime;   

} // if ($which_website == "bigcharts")
else if ($which_website == "streetinsider")
{
 $url =  "https://www.streetinsider.com/stock_lookup.php?LookUp=Get+Quote&q=$symbol";

      $result = grabEtradeHTML("https://www.streetinsider.com", $url);
      $html = str_get_html("**" + $result + "**");  

      echo "url is $url"; 

}
else if ($which_website == "etrade")
{
      $url =  "www.etrade.wallst.com/v1/stocks/news/search_results.asp?symbol=$symbol&rsO=new";
      $result = grabEtradeHTML("www.etrade.wallst.com", $url);
      $html = str_get_html($result);  
      $eTradeNewsDiv = $html->find('#news_story');

      $returnEtradeHTML = $eTradeNewsDiv[0]; 
      $returnEtradeHTML = preg_replace('/<div class="fRight newsSideWidth t10">(.*)<div class="clear"><\/div>/', '', $returnEtradeHTML); 
      $returnEtradeHTML = preg_replace('/width:306px;/', 'width:600px;', $returnEtradeHTML); 

      echo $returnEtradeHTML; 
}

?>
