<?php 

include './Samples/config.php';

require_once("simple_html_dom.php"); 

$yesterdayDays = 1;

error_reporting(1);
//ini_set('display_errors', 1);

// header('Content-type: text/html');
$symbol=$_GET['symbol'];
$host_name=$_GET['host_name'];
$which_website=$_GET['which_website'];
$stockOrFund=$_GET['stockOrFund']; 
$google_keyword_string = $_GET['google_keyword_string'];

fopen("cookies.txt", "w");


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
      for ($daysBack = 14; $daysBack > $yesterdayDays; $daysBack--)
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
      $marketWatchNewsHTML = preg_replace('/ delist/i', '<span style="font-size: 25px; background-color:red; color:black"><b> delist If delisting tomorrow 65%, if days away then 50-55%</b></span>', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/Delist/', '<span style="font-size: 25px; background-color:red; color:black"><b> delist If delisting tomorrow 65%, if days away then 50-55%</b></span>', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/ chapter 11|chapter 11 /i', '<span style="font-size: 25px; background-color:red; color:black"><b> &nbsp;CHAPTER 11</b>&nbsp;</span>', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/ reverse split|reverse split /i', '<span style="font-size: 25px; background-color:red; color:black"><b> &nbsp;REVERSE SPLIT</b>&nbsp;</span>', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/ reverse.stock split|reverse stock split /i', '<span style="font-size: 25px; background-color:red; color:black"><b> &nbsp;REVERSE STOCK SPLIT</b>&nbsp;</span>', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/ seeking alpha|seeking alpha /i', '<font size="3" style="font-size: 12px; background-color:#CCFF99; color: black; display: inline-block;">&nbsp;<b>Seeking Alpha</b>&nbsp;</font>', $marketWatchNewsHTML);      
      $marketWatchNewsHTML = preg_replace('/ downgrade|downgrade /i', '<span style="font-size: 12px; background-color:red; color:black"><b> &nbsp;DOWNGRADE</b>&nbsp;</span>', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/ ex-dividend|ex-dividend /i', '<span style="font-size: 12px; background-color:red; color:black"><b> &nbsp;EX-DIVIDEND (chase at 25%)</b>&nbsp;</span>', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/ sales miss|sales miss /i', '<span style="font-size: 12px; background-color:red; color:black"><b> &nbsp;SALES MISS (Chase at 65-70%)</b>&nbsp;</span>', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/ miss sales|miss sales /i', '<span style="font-size: 12px; background-color:red; color:black"><b> &nbsp;MISS SALES (Chase at 65-70%)</b>&nbsp;</span>', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/ disappointing sales|disappointing sales /i', '<span style="font-size: 12px; background-color:red; color:black"><b> &nbsp;DISAPPOINTINT SALES (Chase at 65-70%)</b>&nbsp;</span>', $marketWatchNewsHTML);      
      $marketWatchNewsHTML = preg_replace('/ sales results|sales results /i', '<span style="font-size: 12px; background-color:red; color:black"><b> &nbsp;SALES RESULTS (If bad, chase at 65-70%)</b>&nbsp;</span>', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/ 8-k/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp;8-K</span> (if it involves litigation, then back off)</b>&nbsp;', $marketWatchNewsHTML);
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
      $marketWatchNewsHTML = preg_replace('/ dividend/i', '<span style="font-size: 25px; background-color:black; color:white"><b>&nbsp;dividend (if cut, 35% ALSO CHECK THE ISSUING DATE)</span></b>&nbsp;', $marketWatchNewsHTML); 
      $marketWatchNewsHTML = preg_replace('/ strategic alternatives/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp;strategic alternatives</span>**BANKRUPTCY**</b>&nbsp;', $marketWatchNewsHTML);                  
      $marketWatchNewsHTML = preg_replace('/ unpatentable/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp;unpatentable</span> (65%)</b>&nbsp;', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/ accelerate or increase/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp;accelerate or increase</span> (Possible Chapter 11, stay away)</b>&nbsp;', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/ denial of application/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp;denial of application</span> (65%)</b>&nbsp;', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/ restructuring support agreement/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp;Restructuring Support Agreement</span> (53%)</b>&nbsp;', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/ breach of contract/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp;breach of contract</span> (If lost lawsuit, then 75%, if won then 35% premarket/first round)</b>&nbsp;', $marketWatchNewsHTML);      
      $marketWatchNewsHTML = preg_replace('/ jury verdict/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp;jury verdict</span> BE CAREFUL (If lost major lawsuit, then 70-75%)</b>&nbsp;', $marketWatchNewsHTML);      
      $marketWatchNewsHTML = preg_replace('/ fcc/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp;FCC</span> if regulation had long-term ratifications, then 65-70%</b>&nbsp;', $marketWatchNewsHTML);      
      $marketWatchNewsHTML = preg_replace('/ layoffs/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp;layoffs</span> (check Jay\'s percentages)</b>&nbsp;', $marketWatchNewsHTML);      
      $marketWatchNewsHTML = preg_replace('/ layoff/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp;layoff</span> (check Jay\'s percentages)</b>&nbsp;', $marketWatchNewsHTML);      
      $marketWatchNewsHTML = preg_replace('/ lays off/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp;lays off</span> (check Jay\'s percentages)</b>&nbsp;', $marketWatchNewsHTML);      
      $marketWatchNewsHTML = preg_replace('/ restructuring/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp;restructuring</span></b>&nbsp;', $marketWatchNewsHTML);      
      $marketWatchNewsHTML = preg_replace('/ restructure/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp;restructure</span></b>&nbsp;', $marketWatchNewsHTML);      
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
      $marketWatchNewsHTML = preg_replace('/ merger/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp; merger - if changing deadline (or update in general, 35%), if lawfirm investigating then 22% &nbsp;</b></span>&nbsp;', $marketWatchNewsHTML);
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
      $marketWatchNewsHTML = preg_replace('/ to report/i', '<span style="font-size: 25px; background-color: black; color:white"><b>&nbsp; TO REPORT - CHECK THE DATE - DO NOT CHASE EARLY </b></span>&nbsp;', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/ to host/i', '<span style="font-size: 25px; background-color: black; color:white"><b>&nbsp; TO HOST - CHECK THE DATE </b></span>&nbsp;', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/ to release/i', '<span style="font-size: 25px; background-color: black; color:white"><b>&nbsp; TO RELEASE - CHECK THE DATE </b></span>&nbsp;', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/ schedules/i', '<span style="font-size: 25px; background-color: black; color:white"><b>&nbsp; SCHEDULES - CHECK THE DATE </b></span>&nbsp;', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/ sets date for the release of/i', '<span style="font-size: 25px; background-color: black; color:white"><b>&nbsp; SETS DATE FOR THE RELEASE OF - CHECK THE DATE </b></span>&nbsp;', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/ collaboration/i', '<span style="font-size: 25px; background-color: red; color:white"><b>&nbsp; COLLABORATION - CAREFUL </b></span>&nbsp;', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/ china/i', '<span style="font-size: 65px; background-color: red; color:black"><b>&nbsp; CHINA </b></span>&nbsp;', $marketWatchNewsHTML);
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

    $rss = simplexml_load_file("http://feeds.finance.yahoo.com/rss/2.0/headline?s=$symbol&region=US&lang=en-US");
    $allNews = "<ul class='newsSide'>";
    $allNews .= "<li style='font-size: 20px !important; background-color: #00ff00;'>Yahoo Finance News</li>";

    $classActionAdded = false;
    $j = 0;
    foreach ($rss->channel->item as $feedItem) {
        $j++;

        // Convert time from GMT to  AM/PM New York
        $publicationDateStrToTime = strtotime($feedItem->pubDate);
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



      $command = escapeshellcmd('python3 ../pythonscrape/scrape-yahoo-finance-company-profile.py ' . $symbol);
      $yahooFinanceJson = shell_exec($command);

      $yahooFinanceObject = json_decode($yahooFinanceJson);

      $companyWebsite = '<a target="_blank" style="font-size: 15px;" onclick="return openPage(this.href)" href="' . $yahooFinanceObject->website . '" class="tab-link"><b>Website</b></a>&nbsp;&nbsp;';

      $countryPipeString = $yahooFinanceObject->address; 
      $countryPipeArray = explode('|', $countryPipeString);
      $countryPipeArrayLength = count($countryPipeArray);

      $country = trim($countryPipeArray[$countryPipeArrayLength - 1]); 

      $yahooFinanceSector = $yahooFinanceObject->sector; 
      $yahooFinanceIndustry = $yahooFinanceObject->industry; 

      /* Highlight certain sector words that should put us on guard */ 

      $yahooFinanceSector = preg_replace('/energy/i', '<span style="font-size: 20px; background-color: red; color:black"><b>&nbsp; ENERGY</b></span>&nbsp;', $yahooFinanceSector); 

      $yahooFinanceIndustry = preg_replace('/oil \& gas/i', '<span style="font-size: 35px; background-color: red; color:black"><b>&nbsp; OIL & GAS</b></span>&nbsp;', $yahooFinanceIndustry); 

      $yahooFinanceIndustry = preg_replace('/shell companies/i', '<span style="font-size: 35px; background-color: red; color:black"><b>&nbsp; Shell Companies</b></span>&nbsp;', $yahooFinanceIndustry); 

      $sectorCountry = '<span style="font-size: 15px;">SECTOR - ' . $yahooFinanceSector . '</span>&nbsp;&nbsp;<span id="industry" style="font-size: 15px;">INDUSTRY - ' . $yahooFinanceIndustry . '</span><br><br><div id="country" style="font-size: 15px;">' . $country . '</div>'; 



      addYahooSectorIndustry($symbol, $yahooFinanceObject->sector, $yahooFinanceObject->industry, $country, $companyName);

      $returnCompanyName = '<h1>' . $companyName . '</h1>';

      $yesterdayVolume = (int) $_GET['yesterday_volume'];
      $currentVolume = (int) $_GET['total_volume'];
      $volumeRatio = 0; 

      $volumeRatio = $currentVolume/$yesterdayVolume; 
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

      // get Yahoo Finance average volume number
      $command = escapeshellcmd('python3 ../pythonscrape/scrape-yahoo-finance-summary.py ' . $symbol);
      $yahooFinanceJson = shell_exec($command);

      $yahooFinanceObject = json_decode($yahooFinanceJson);

      $avgVolYahoo =  '<span id="vol_yahoo" style="background-color: orange; font-size: 12px;"><b>YahooAVG - ' . $yahooFinanceObject->avgvol . '</b></span>'; 




      $avgVol10days = '<span id="vol_10_day" style="font-size: 12px; background-color:#CCFF99; color: black; display: inline-block;"><b>eTradeAVG - ' . number_format((int) $_GET['ten_day_volume']) . '</b></span>'; 

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

      // URL of Google News RSS feed
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


      /*** Seeking Alpha RSS Parse ***/ 

      $rssSeekingAlpha = simplexml_load_file("https://seekingalpha.com/api/sa/combined/" . $symbol . ".xml");

      $seekingAlphaNews = "<ul class='newsSide'>";
      $seekingAlphaNews .= "<li style='font-size: 20px !important; background-color: #00ff00;'>Seeking Alpha News</li>";

      $classActionAdded = false;
      $j = 0;
      foreach ($rssSeekingAlpha->channel->item as $feedItem) {
        $j++;

        // Convert time from GMT to  AM/PM New York
        $publicationDateStrToTime = strtotime($feedItem->pubDate);

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

        if ($j % 2 == 1)
        {
          $seekingAlphaNews .=  "style='background-color: #ebd8bd; '"; 
        };
        
        // if the regular expression contains (.*) then we need to do it per title, to avoid greedy regular expressions

        $newsTitle = preg_replace('/ withdrawal(.*?)application/i', '<span style="font-size: 12px; background-color:red; color:black"><b> withdrawal $1 application (55%) </b></span> ', $newsTitle);
        $newsTitle = preg_replace('/nasdaq rejects(.*?)listing/i', '<span style="font-size: 12px; background-color:red; color:black"><b>Nasdaq rejects $1 listing</span> If delisting tomorrow 65%, if delisting days away then 50-55%</b>&nbsp;', $newsTitle);

        $seekingAlphaNews .=  " ><a target='_blank' href='$feedItem->link'> " . $publicationDate . " " . $publicationTime . " - <br>" . $newsTitle . "</a>";
      }

      $seekingAlphaNews .=  "</ul>";

      $seekingAlphaNews .= "yesterdayDays = " . $yesterdayDays . "<br>"; 

    /*** End of Seeking Alpha RSS Parse ***/ 

      $finalReturn = "<td valign='top' style='width: 50%' >" . str_replace('<a ', '<a target="_blank" onclick="return openPage(this.href)" ', $allNews) . '</td><td valign="top" style="width: 50%">' . $stockSplitsTable . $seekingAlphaNews . str_replace('<a ', '<a target="_blank" onclick="return openPage(this.href)" ', $googleNews) . '</td>';

      $finalReturn = preg_replace($patterns = array("/<img[^>]+\>/i", "/<embed.+?<\/embed>/im", "/<iframe.+?<\/iframe>/im", "/<script.+?<\/script>/im"), $replace = array("", "", "", ""), $finalReturn);

      $finalReturn = preg_replace('/Headlines/', '<b>Yahoo Headlines</b>', $finalReturn);
      $finalReturn = preg_replace('/<cite>/', '<cite> - ', $finalReturn);              
      $finalReturn = preg_replace('/<span>/', '<span style="font-size: 12px; background-color:#D8D8D8; color:black"><b> ', $finalReturn); 
      $finalReturn = preg_replace('/<\/span>/', '</b></span>', $finalReturn); 

      $finalReturn = preg_replace('/([A-Z][a-z][a-z] [0-9][0-9]:[0-9][0-9][A-Z]M EDT)|([A-Z][a-z][a-z] [0-9]:[0-9][0-9][A-Z]M EDT)/', '<span style="font-size: 12px; background-color:black; color:white">$1$2</span> ', $finalReturn);
      $finalReturn = preg_replace('/([A-Z][a-z][a-z] [0-9][0-9]:[0-9][0-9][A-Z]M EST)|([A-Z][a-z][a-z] [0-9]:[0-9][0-9][A-Z]M EST)/', '<span style="font-size: 12px; background-color:black; color:white">$1$2</span> ', $finalReturn);

      // yellow highlighting for before yesterday
      for ($daysBack = 14; $daysBack > $yesterdayDays; $daysBack--)
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







      $finalReturn = preg_replace('/ delist/i', '<span style="font-size: 25px; background-color:red; color:black"><b> delist If delisting tomorrow 65%, if days away then 50-55%</b></span>', $finalReturn);
      $finalReturn = preg_replace('/Delist/', '<span style="font-size: 25px; background-color:red; color:black"><b> delist If delisting tomorrow 65%, if days away then 50-55%</b></span>', $finalReturn);
      $finalReturn = preg_replace('/ chapter 11|chapter 11 /i', '<span style="font-size: 25px; background-color:red; color:black"><b> &nbsp;CHAPTER 11</b></span>', $finalReturn);
      $finalReturn = preg_replace('/ reverse split|reverse split /i', '<span style="font-size: 25px; background-color:red; color:black"><b> &nbsp;REVERSE SPLIT</b></span>', $finalReturn);
      $finalReturn = preg_replace('/ reverse.stock split|reverse stock split /i', '<div style="font-size: 25px; background-color:red; display: inline-block;">REVERSE STOCK SPLIT</div>', $finalReturn);
      $finalReturn = preg_replace('/ downgrade|downgrade /i', '<span style="font-size: 12px; background-color:red; color:black"><b> &nbsp;DOWNGRADE</b></span>', $finalReturn);      
      $finalReturn = preg_replace('/ ex-dividend|ex-dividend /i', '<div style="font-size: 12px; background-color:red; display: inline-block;">EX-DIVIDEND (chase at 25%)</div>', $finalReturn);
      $finalReturn = preg_replace('/ sales miss|sales miss /i', '<span style="font-size: 12px; background-color:red; color:black"><b> &nbsp;SALES MISS (Chase at 65-70%)</b>&nbsp;</span>', $finalReturn);      
      $finalReturn = preg_replace('/ miss sales|miss sales /i', '<span style="font-size: 12px; background-color:red; color:black"><b> &nbsp;MISS SALES (Chase at 65-70%)</b>&nbsp;</span>', $finalReturn);
      $finalReturn = preg_replace('/ disappointing sales|disappointing sales /i', '<span style="font-size: 12px; background-color:red; color:black"><b> &nbsp;DISAPPOINTINT SALES (Chase at 65-70%)</b>&nbsp;</span>', $finalReturn);      
      $finalReturn = preg_replace('/ sales results|sales results /i', '<span style="font-size: 12px; background-color:red; color:black"><b> &nbsp;SALES RESULTS (If bad, chase at 65-70%)</b>&nbsp;</span>', $finalReturn);      
      $finalReturn = preg_replace('/ 8-k/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp;8-K</span> (if it involves litigation, then back off)</b>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ accountant/i', '<span style="font-size: 25px; background-color:red; color:black"><b> &nbsp;accountant (if hiring new accountant, 35-40%)</b>&nbsp;</span>', $finalReturn);            
      $finalReturn = preg_replace('/ clinical trial/i', '<span style="font-size: 12px; background-color:red; color:black"><b> &nbsp;clinical trial</b>&nbsp;</span>', $finalReturn);            
      $finalReturn = preg_replace('/ recall/i', '<span style="font-size: 25px; background-color:red; color:black"><b> &nbsp;recall (bad, back off)</b>&nbsp;</span>', $finalReturn);                  
      $finalReturn = preg_replace('/ etf/i', '<span style="font-size: 12px; background-color:red; color:black"><b> &nbsp;ETF</b>&nbsp;</span>', $finalReturn);                        
      $finalReturn = preg_replace('/ etn/i', '<span style="font-size: 12px; background-color:red; color:black"><b> &nbsp;ETN</b>&nbsp;</span>', $finalReturn);                        
      $finalReturn = preg_replace('/[ \']disruption[ \']/i', '<span style="font-size: 12px; background-color:red; color:black"><b> &nbsp;disruption&nbsp;</span> (chase at 52%)</b>', $finalReturn);
      $finalReturn = preg_replace('/ abandon/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp;abandon&nbsp;</span> (65-70%)</b>', $finalReturn);
      $finalReturn = preg_replace('/ bankrupt/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp;bankrupt</span></b>', $finalReturn);      
      $finalReturn = preg_replace('/ terminate| terminates| terminated| termination/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp;terminate&nbsp;</span> (65%) </b>', $finalReturn);            

      $finalReturn = preg_replace('/ drug/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp;drug </span></b> ', $finalReturn);

      $finalReturn = preg_replace('/ guidance/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp;GUIDANCE </span></b>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ regulatory update/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp;regulatory update (35% even if regulation is good)</span></b>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ suspended/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp;suspended</span> (65-70%)</b>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ fraud/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp;fraud</span></b>&nbsp;', $finalReturn);      
      $finalReturn = preg_replace('/ dividend/i', '<span style="font-size: 25px; background-color:#202020; color:white"><b>&nbsp;dividend (if cut, 35% ALSO CHECK THE ISSUEING DATE)</span></b>&nbsp;', $finalReturn);            
      $finalReturn = preg_replace('/ strategic alternatives/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp;strategic alternatives</span></b>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ unpatentable/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp;unpatentable</span> (60%)</b>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ accelerate or increase/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp;accelerate or increase</span> (Possible Chapter 11, stay away)</b>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ denial of application/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp;denial of application</span> (65%)</b>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ restructuring support agreement/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp;Restructuring Support Agreement</span> (53%)</b>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ breach of contract/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp;breach of contract</span> (If lost lawsuit, then 75%, if won then 35% premarket/first round)</b>&nbsp;', $finalReturn);      
      $finalReturn = preg_replace('/ jury verdict/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp;jury verdict</span> BE CAREFUL (If lost major lawsuit, then 70-75%)</b>&nbsp;', $finalReturn);      
      $finalReturn = preg_replace('/ fcc/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp;FCC</span> if regulation had long-term ratifications, then 65-70%</b>&nbsp;', $finalReturn);      
      $finalReturn = preg_replace('/ layoffs/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp;layoffs</span> (check Jay\'s percentages)</b>&nbsp;', $finalReturn);      
      $finalReturn = preg_replace('/ layoff/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp;layoff</span> (check Jay\'s percentages)</b>&nbsp;', $finalReturn);      
      $finalReturn = preg_replace('/ lays off/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp;lays off</span> (check Jay\'s percentages)</b>&nbsp;', $finalReturn);      
      $finalReturn = preg_replace('/ restructuring/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp;restructuring</span></b>&nbsp;', $finalReturn);      
      $finalReturn = preg_replace('/ restructure/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp;restructure</span></b>&nbsp;', $finalReturn);      
      $finalReturn = preg_replace('/ clinical hold/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp;clinical hold</span> (65 - 70%)</b>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ convertible senior notes/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; convertible senior notes (back off until you see a price)</b></span>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ amendment(.*) to secured credit facilities/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp; amendments to secured credit facilities (65%)</b></span>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ notice of effectiveness/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp; notice of effectiveness (40% till you see the notice)</b></span>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ equity investment/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp; equity investment - look for price of new shares</b></span>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ lease termination/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp; DANGER - Chapter 7 warning - 90%</b></span>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ redemption of public shares/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp; Redemption of Public Shares - 92%</b></span>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ phase 2/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp; Phase 2 - 82% and set a stop loss of around 12%</b></span>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ investor call/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; INVESTOR CALL - CHECK THE DATE &nbsp;</b></span>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ strategic update/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp; STRATEGIC UPDATE - BE CAREFUL &nbsp;</b></span>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ attorney general/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp; attorney general (if there is an attorney general probe then 45-50%) &nbsp;</b></span>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ merger/i', '<span style="font-size: 55px; background-color:red; color:black"><b>&nbsp; MERGER<br><br> - STAY AWAY</b></span>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ preliminary(.*?)outlook/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp; Preliminary$1Outlook -</span> <span style="font-size: 12px; background-color:lightgreen; color:black">41% right off the bat, then 48% literally 3 minutes later.  TAKE NO MORE THAN 5% AND BAIL &nbsp;</b></span>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ conference call to provide update/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp; Conference Call to Provide Update -</span> <span style="font-size: 12px; background-color:lightgreen; color:black">CHECK THE DATE/TIME OF THE CALL &nbsp;</b></span>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ seeking alpha/i', '<span style="font-size: 25px; background-color:red; color:black">SEEKING ALPHA &nbsp;</b></span>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ fictitious sales/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp; fictitious sales - STAY AWAY </b></span> &nbsp;', $finalReturn);     
      $finalReturn = preg_replace('/ board of directors/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp; board of directors - if change to board of directors - 20% </b></span> &nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ class action/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; class action</b></span> &nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ business combination/i', '<span style="font-size: 55px; background-color:red; color:black"><b>&nbsp; BUSINESS<br><br> COMBINATION<br><br> - STAY<br><br>AWAY<br><br> </b></span> &nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ annual meeting of shareholders/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp; annual meeting of shareholders - 40% early</b></span> &nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ transcript/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; transcript </b></span> &nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ share consolidation/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; share consolidation - REVERSE STOCK SPLIT </b></span> &nbsp;', $finalReturn);
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
      $finalReturn = preg_replace('/ ratio change/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; RATIO CHANGE - REVERSE SPLIT </b></span>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ 10\-q/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; 10-Q </b></span>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ registered direct offering/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; REGISTERED DIRECT OFFERING </b></span>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ fda clearance/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; FDA Clearance - back off </b></span>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ deficiency in compliance/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; Deficiency in Compliance - CHECK FOR DELISTING</b></span>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ net asset value/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; NET ASSET VALUE - 25%</b></span>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ efficacy data/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; EFFICACY DATA - DRUG NEWS</b></span>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ phase 1/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; PHASE 1!!!!</b></span>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ phase 2/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; PHASE 2!!!!</b></span>&nbsp;', $finalReturn); 
      $finalReturn = preg_replace('/ phase 3/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; PHASE 3!!!!</b></span>&nbsp;', $finalReturn); 
      $finalReturn = preg_replace('/ to present/i', '<span style="font-size: 25px; background-color:#202020; color:white"><b>&nbsp; TO PRESENT - CHECK DATE - IF EARNINGS THEN OK TO CHASE AROUND 21-23%</b></span>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ shareholder investigation/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; SHAREHOLDER INVESTIGATION - 19.5%</b></span>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ announces an investigation/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; ANNOUNCES AN INVESTIGATION - 19.5%</b></span>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ convertible bonds/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; convertible bonds (back off until you see a price)</b></span>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ equity grants/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; EQUITY GRANTS - (20-23% early on)</b></span>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ to announce/i', '<span style="font-size: 25px; background-color: red; color:black"><b>&nbsp; TO ANNOUNCE - CHECK THE DATE </b></span>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ to report/i', '<span style="font-size: 25px; background-color: #202020; color:white"><b>&nbsp; TO REPORT - CHECK THE DATE - DO NOT CHASE EARLY </b></span>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ to host/i', '<span style="font-size: 25px; background-color: #202020; color:white"><b>&nbsp; TO HOST - CHECK THE DATE </b></span>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ to release/i', '<span style="font-size: 25px; background-color: #202020; color:white"><b>&nbsp; TO RELEASE - CHECK THE DATE </b></span>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ schedules/i', '<span style="font-size: 25px; background-color: #202020; color:white"><b>&nbsp; SCHEDULES - CHECK THE DATE </b></span>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ sets date for the release of/i', '<span style="font-size: 25px; background-color: #202020; color:white"><b>&nbsp; SETS DATE FOR THE RELEASE OF - CHECK THE DATE </b></span>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ collaboration/i', '<span style="font-size: 25px; background-color: red; color:black"><b>&nbsp; COLLABORATION - CAREFUL </b></span>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ china/i', '<span style="font-size: 65px; background-color: red; color:black"><b>&nbsp; CHINA </b></span>&nbsp;', $finalReturn);
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
      $finalReturn = preg_replace('/ s-1/i', '<span style="font-size: 25px; background-color: red; color:black"><b>&nbsp; s-1 - BACK OFF!!</b></span>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/(Files \$.*M)/i', '<span style="font-size: 25px; background-color: red; color:black"><b>&nbsp; $1 - OFFERING!!!</b></span>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ (cuts.*?guidance)/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp;$1</span></b>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ chapter 22/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp;CHAPTER 22</span></b>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ (disappointing.*?results)/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp;$1</span></b>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ (reports.*?results)/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp;$1</span></b>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ EPS/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp;EPS - CHECK FOR A LOSS</span></b>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ committee investigation/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; COMMITTEE INVESTIGATION - STAY AWAY</span></b>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ (posts.*?data)/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp;$1</span></b>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ (illegally.*\")/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp;$1 - STAY AWAY</span></b>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ at+the+market/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp;AT THE MARKET - ADJUSTMENT COULD HAPPEN</span></b>&nbsp;', $finalReturn);      






      $message_board = '</font><a target="_blank" onclick="return openPage(this.href)" href="http://finance.yahoo.com/quote/' . $symbol . '/community?ltr=1"> Yahoo Message Boards</a>&nbsp;&nbsp;&nbsp;&nbsp;'; 
      $company_profile = '<a target="_blank" onclick="return openPage(this.href)" href="http://finance.yahoo.com/quote/' . $symbol . '/profile">Yahoo Company Profile for ' . $symbol . '</a><br>'; 
      $yahoo_main_page = '<a target="_blank" href="http://finance.yahoo.com/q?s=' . $symbol . '&ql=1">Yahoo Main Page for ' . $symbol . '</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
      $yahoo_5_day_chart = '<a target="_blank" href="http://finance.yahoo.com/echarts?s=' . $symbol . '+Interactive#symbol=' . $symbol . ';range=5d">5-day Chart for ' . $symbol . '</a><br><br>';
      $eTrade = '<a target="_blank" href="https://www.etrade.wallst.com/v1/stocks/news/search_results.asp?symbol=' . $symbol . '">E*TRADE news for ' . $symbol . '</a>';
      $google = '<a target="_blank" onclick="return openPage(this.href)" href="https://www.google.com/search?hl=en&gl=us&tbm=nws&authuser=1&q=' . $google_keyword_string . '">Google news for ' . $symbol . '</a>';
        $google = preg_replace('/<h1>/', '', $google);
        $google = preg_replace('/<\/h1>/', '', $google);
      $nasdaqInfo = '&nbsp;&nbsp;<a target="_blank" onclick="return openPage(this.href)" href="https://www.nasdaq.com/symbol/' . $symbol . '/sec-filings"> Nasdaq Info</a>&nbsp;&nbsp;&nbsp;&nbsp;'; 
      $streetInsider = '&nbsp;&nbsp;<a target="_blank" onclick="return openPage(this.href)" href="https://www.streetinsider.com/stock_lookup.php?LookUp=Get+Quote&q=' . $symbol . '"> SI</a>&nbsp;&nbsp;&nbsp;&nbsp;'; 

      $streetInsiderScrape = '&nbsp;&nbsp;<a target="_blank" onclick="return openPage(this.href)" href="http://ec2-54-210-42-143.compute-1.amazonaws.com/newslookup/scrape-street-insider.php?symbol=' . $symbol . '"> SI Scrape</a>&nbsp;&nbsp;&nbsp;&nbsp;';  

      $splits = '&nbsp;&nbsp;<a target="_blank" onclick="return openPage(this.href)" href="https://www.stocksplithistory.com/?symbol=' . $symbol . '"> Splits</a>&nbsp;&nbsp;&nbsp;&nbsp;'; 

      $finalReturn = $yahooDates . $returnCompanyName . $companyWebsite . $sectorCountry . $returnYesterdaysClose . $preMarketYesterdaysClose[0] . "<br>" . "<div style='display: inline-block;'>" . $yesterdayVolumeHTML . $currentVolumeHTML . $volumeRatioHTML . $avgVol10days . $avgVolYahoo .  $company_profile . $yahoo_main_page . $message_board . $google . $nasdaqInfo . $streetInsider . $streetInsiderScrape . $splits . '<table width="700px"><tr width="575px">' . $finalReturn . '</tr></table>'; 

      echo $finalReturn; 

} // if ($which_website == "yahoo")
else if ($which_website == "bigcharts")
{
      // grab the last value from bigcharts
      $url = "https://bigcharts.marketwatch.com/quickchart/quickchart.asp?symb=" . $symbol . "&insttype=&freq=9&show=&time=1&rand=" . rand(); 
 
      $result = grabHTML("bigcharts.marketwatch.com", $url);
      $html = str_get_html($result);  

      $tdArray = $html->find('td.change div'); 

      $bigChartsReturn = $tdArray[1]; 

      $bigChartsReturn = preg_replace('/<div.*?\>/', '', $bigChartsReturn); 
      $bigChartsReturn = preg_replace('/<\/div>/', '', $bigChartsReturn); 
      $bigChartsReturn = preg_replace('/\%/', '', $bigChartsReturn); 
      $bigChartsReturn = preg_replace('/\-/', '', $bigChartsReturn); 
      $bigChartsReturn = preg_replace('/\+/', '', $bigChartsReturn); 

      $lastArray = $html->find('td.last div'); 
      $lastValue = $lastArray[0]; 
      $lastValue = preg_replace('/<div.*?\>/', '', $lastValue); 
      $lastValue = preg_replace('/<\/div>/', '', $lastValue); 

      $timeArray = $html->find('tr.header td.time'); 
      $timeValue = $timeArray[0]; 
      $timeValue = preg_replace('/<td.*?\>/', '', $timeValue); 
      $timeValue = preg_replace('/<\/td>/', '', $timeValue); 
      $timeValue = preg_replace('/\/\d{4}/', '', $timeValue); 



      echo   $bigChartsReturn . "|" . $lastValue . "|" . $timeValue;         // json_encode($bigChartsReturn); 

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
