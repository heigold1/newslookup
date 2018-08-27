<?php 

include 'config.php';

require_once("simple_html_dom.php"); 
error_reporting(0);

// header('Content-type: text/html');
$symbol=$_GET['symbol'];
$host_name=$_GET['host_name'];
$which_website=$_GET['which_website'];
$stockOrFund=$_GET['stockOrFund']; 
$google_keyword_string = $_GET['google_keyword_string'];

fopen("cookies.txt", "w");

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
      $friday_marketwatch_trade_date = "April. " . date('d, Y',strtotime("-3 days"));      
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
      $saturday_marketwatch_trade_date = "April. " . date('d, Y',strtotime("-2 days"));
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
      $yesterday_marketwatch_trade_date = "April. " . date('d, Y',strtotime("-1 days"));
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
      $today_marketwatch_trade_date = "March. " . date('d, Y');
    }
    else if ($month == ('Apr'))
    {
      $today_marketwatch_trade_date = "April. " . date('d, Y');
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
//      $url="https://$host_name/investing/$stockOrFund/$symbol";

//      $url = "http://ec2-52-41-122-145.us-west-2.compute.amazonaws.com/puppeteer-marketwatch/grab-news.php?stockOrFund=$stockOrFund&symbol=$symbol";

return;

      $result = curl_get_contents($url);

      $resultDecoded = json_decode($result, true);

      $marketWatchNewsHTML = '<html><head><link rel="stylesheet" href="./css/combined-min-1.0.5754.css" type="text/css"/>
      <link type="text/css" href="./css/quote-layout.css" rel="stylesheet"/>
        <link type="text/css" href="./css/quote-typography.css" rel="stylesheet"/>
      </head>
      <body>
      '; 

      $marketWatchNewsHTML = "<h1>Recent News</h1>";
      $marketWatchNewsHTML .= '<div style="max-height: 200px; overflow: auto;">';

      foreach ($resultDecoded['mw'] as $mw)
      {
        $marketWatchNewsHTML .= '<div>';
        $marketWatchNewsHTML .= '<span>' . $mw['date'] . '</span>' . '<a target="blank" href="' . $mw['link'] . '" >' . $mw['headline'] . '</a>'; 
        $marketWatchNewsHTML .= '</div>';
      } 
      $marketWatchNewsHTML .= '</div>';

      $marketWatchNewsHTML .= "<h1>Other News</h1>";
      $marketWatchNewsHTML .= '<div style="max-height: 250px; overflow: auto;">';

      foreach ($resultDecoded['other'] as $other)
      {
        $marketWatchNewsHTML .= '<div>';
        $marketWatchNewsHTML .= '<span>' . $other['date'] . '</span>' . '<a target="blank" href="' . $other['link'] . '" >' . $other['headline'] . '</a>'; 
        $marketWatchNewsHTML .= '</div>';
      } 


      $marketWatchNewsHTML .= '</div>';

      $marketwatch_todays_date = date('l'/*, strtotime("-9 hours")*/); 
      if ($marketwatch_todays_date == "Monday")
      {
        $marketWatchNewsHTML = preg_replace('/(' .  get_marketwatch_friday_trade_date() . ')/', '<span style="font-size: 10px; background-color:#000080 ; color:white">$1</span>', $marketWatchNewsHTML);
        $marketWatchNewsHTML = preg_replace('/(' .  get_marketwatch_saturday_trade_date() . ')/', '<span style="font-size: 10px; background-color:#000080 ; color:white">$1</span>', $marketWatchNewsHTML);      
      }  

      $marketWatchNewsHTML = preg_replace('/(' .  get_marketwatch_yesterday_trade_date() . ')/', '<span style="font-size: 10px; background-color:#000080 ; color:white">$1</span>', $marketWatchNewsHTML);     
      $marketWatchNewsHTML = preg_replace('/(' .  get_marketwatch_today_trade_date() . ')/', '<span style="font-size: 10px; background-color:black; color:white">$1</span>', $marketWatchNewsHTML);           

      $marketWatchNewsHTML = preg_replace('/([0-9][0-9]:[0-9][0-9] [a-z]\.m\.  Today)|([0-9]:[0-9][0-9] [a-z]\.m\.  Today)/', '<span style="font-size: 8px; background-color:black; color:white">$1$2</span>', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/([0-9][0-9] min ago)|([0-9] min ago)/', '<span style="font-size: 8px; background-color:black; color:white">$1$2</span>', $marketWatchNewsHTML);      
      $marketWatchNewsHTML = preg_replace('/ delist/i', '<span style="font-size: 12px; background-color:red; color:black"><b> delist</span> If delisting tomorrow 65%, if days away then 50-55%</b>', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/ chapter 11|chapter 11 /i', '<span style="font-size: 12px; background-color:red; color:black"><b> &nbsp;CHAPTER 11</b>&nbsp;</span>', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/ reverse split|reverse split /i', '<span style="font-size: 12px; background-color:red; color:black"><b> &nbsp;REVERSE SPLIT</b>&nbsp;</span>', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/ reverse stock split|reverse stock split /i', '<span style="font-size: 12px; background-color:red; color:black"><b> &nbsp;REVERSE STOCK SPLIT</b>&nbsp;</span>', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/ seeking alpha|seeking alpha /i', '<font size="3" style="font-size: 12px; background-color:#CCFF99; color: black; display: inline-block;">&nbsp;<b>Seeking Alpha</b>&nbsp;</font>', $marketWatchNewsHTML);      
      $marketWatchNewsHTML = preg_replace('/ downgrade|downgrade /i', '<span style="font-size: 12px; background-color:red; color:black"><b> &nbsp;DOWNGRADE</b>&nbsp;</span>', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/ ex-dividend|ex-dividend /i', '<span style="font-size: 12px; background-color:red; color:black"><b> &nbsp;EX-DIVIDEND (chase at 25%)</b>&nbsp;</span>', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/ sales miss|sales miss /i', '<span style="font-size: 12px; background-color:red; color:black"><b> &nbsp;SALES MISS (Chase at 65-70%)</b>&nbsp;</span>', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/ miss sales|miss sales /i', '<span style="font-size: 12px; background-color:red; color:black"><b> &nbsp;MISS SALES (Chase at 65-70%)</b>&nbsp;</span>', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/ disappointing sales|disappointing sales /i', '<span style="font-size: 12px; background-color:red; color:black"><b> &nbsp;DISAPPOINTINT SALES (Chase at 65-70%)</b>&nbsp;</span>', $marketWatchNewsHTML);      
      $marketWatchNewsHTML = preg_replace('/ sales results|sales results /i', '<span style="font-size: 12px; background-color:red; color:black"><b> &nbsp;SALES RESULTS (If bad, chase at 65-70%)</b>&nbsp;</span>', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/ 8-k/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp;8-K</span> (if it involves litigation, then back off)</b>&nbsp;', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/ accountant/i', '<span style="font-size: 12px; background-color:red; color:black"><b> &nbsp;accountant (if hiring new accountant, 35-40%)</b>&nbsp;</span>', $marketWatchNewsHTML);      
      $marketWatchNewsHTML = preg_replace('/ clinical trial/i', '<span style="font-size: 12px; background-color:red; color:black"><b> &nbsp;clinical trial</b>&nbsp;</span>', $marketWatchNewsHTML);            
      $marketWatchNewsHTML = preg_replace('/ recall/i', '<span style="font-size: 12px; background-color:red; color:black"><b> &nbsp;recall (bad, back ff)</b>&nbsp;</span>', $marketWatchNewsHTML);                  
      $marketWatchNewsHTML = preg_replace('/ etf/i', '<span style="font-size: 12px; background-color:red; color:black"><b> &nbsp;ETF</b>&nbsp;</span>', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/ etn/i', '<span style="font-size: 12px; background-color:red; color:black"><b> &nbsp;ETN</b>&nbsp;</span>', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/[ \']disruption[ \']/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp;disruption&nbsp;</span> (chase at 52%)</b>', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/ abandon/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp;abandon&nbsp;</span> (65-70%)</b>', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/ bankrupt/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp;bankrupt&nbsp;</span>(65%)</b>', $marketWatchNewsHTML);      
      $marketWatchNewsHTML = preg_replace('/ terminate| terminates| terminated| termination/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp;terminate&nbsp;</span> (65%) </b>', $marketWatchNewsHTML);            
      $marketWatchNewsHTML = preg_replace('/ drug/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp;drug&nbsp;</span></b>', $marketWatchNewsHTML);            
      $marketWatchNewsHTML = preg_replace('/ guidance/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp;guidance</span> (35-40% early)</b>&nbsp;', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/ regulatory update/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp;regulatory update (35% even if regulation is good)</span></b>&nbsp;', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/ susbended/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp;suspended</span> (65-70%)</b>&nbsp;', $marketWatchNewsHTML);      
      $marketWatchNewsHTML = preg_replace('/ fraud/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp;fraud</span></b>&nbsp;', $marketWatchNewsHTML);      
      $marketWatchNewsHTML = preg_replace('/ dividend/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp;dividend</span> (if cut, 65-70%)</b>&nbsp;', $marketWatchNewsHTML);            
      $marketWatchNewsHTML = preg_replace('/ strategic alternatives/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp;strategic alternatives</span>**BANKRUPTCY**</b>&nbsp;', $marketWatchNewsHTML);                  
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
      $marketWatchNewsHTML = preg_replace('/ restructuring/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp;restructuring</span></b>&nbsp;', $marketWatchNewsHTML);      
      $marketWatchNewsHTML = preg_replace('/ restructure/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp;restructure</span></b>&nbsp;', $marketWatchNewsHTML);      
      $marketWatchNewsHTML = preg_replace('/nasdaq rejects(.*)listing/i', '<span style="font-size: 12px; background-color:red; color:black"><b>Nasdaq rejects $1 listing</span> If delisting tomorrow 65%, if delisting days away then 50-55%</b>&nbsp;', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/ clinical hold/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp;clinical hold</span> (65 - 70%)</b>&nbsp;', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/ withdrawal(.*)application/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp; withdrawal $1 application (55%)</b></span>&nbsp;', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/ convertible senior notes/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp; convertible senior notes (35%)</b></span>&nbsp;', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/ amendment(.*) to secured credit facilities/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp; amendments to secured credit facilities (65%)</b></span>&nbsp;', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/ notice of effectiveness/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp; notice of effectiveness (40% till you see the notice)</b></span>&nbsp;', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/ equity investment/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp; equity investment - look for price of new shares</b></span>&nbsp;', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/ lease termination/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp; DANGER - Chapter 7 warning - 90%</b></span>&nbsp;', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/ redemption of public shares/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp; Redemption of Public Shares - 92%</b></span>&nbsp;', $marketWatchNewsHTML);
      $marketWatchNewsHTML = preg_replace('/ phase 2/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp; Phase 2 - 74% and set a stop loss of around 12%</b></span>&nbsp;', $marketWatchNewsHTML);

      $marketWatchNewsHTML .= '</body></html>'; 

      echo $marketWatchNewsHTML;       
}
else if ($which_website == "yahoo")
{
    // grab the news 

    $rss = simplexml_load_file("http://feeds.finance.yahoo.com/rss/2.0/headline?s=$symbol&region=US&lang=en-US");
    $allNews = "<ul class='newsSide'>";
    $i = 0;
    foreach ($rss->channel->item as $feedItem) {
        $i++;
        $allNews .= "<li "; 

        // Convert time from GMT to  AM/PM New York
        $publicationDateStrToTime = strtotime($feedItem->pubDate);
        $convertedDate = new DateTime(); 
        $convertedDate->setTimestamp($publicationDateStrToTime);
        $interval = DateInterval::createFromDateString('-3 hours'); 
        $convertedDate->add($interval); 

        $feedItem->pubDate = preg_replace("/[0-9][0-9]\:[0-9][0-9]\:[0-9][0-9] \+0000/", "", $feedItem->pubDate); 

        if ($i % 2 == 1)
        {
          $allNews .=  "style='background-color: #FFFFFF; '"; 
        };
        
        $newsTitle = $feedItem->title; 

        // if the regular expression contains (.*) then we need to do it per title, to avoid greedy regular expressions

        $newsTitle = preg_replace('/ withdrawal(.*)application/i', '<span style="font-size: 12px; background-color:red; color:black"><b> withdrawal $1 application (55%) </b></span> ', $newsTitle);
        $newsTitle = preg_replace('/nasdaq rejects(.*)listing/i', '<span style="font-size: 12px; background-color:red; color:black"><b>Nasdaq rejects $1 listing</span> If delisting tomorrow 65%, if delisting days away then 50-55%</b>&nbsp;', $newsTitle);

        $allNews .=  " ><a href='$feedItem->link' title='$feedItem->title'> " . $feedItem->pubDate . " " . $convertedDate->format("g:i A") . " - " . $newsTitle . "</a></li>";
    }

      $allNews .=  "</ul>";

      // grab the finanicals 

      // Now we grab website, sector, and company from finviz.com

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
      $sectorCountry = str_replace('<a', '<span', $sectorCountry);    
      $sectorCountry = str_replace('\/a', '/span', $sectorCountry);   

      $returnCompanyName = '<h1>' . $_GET['company_name'] . '</h1>';      // $companyNameArray[0]; 

      $currentVolume = '<span style="font-size: 12px; background-color:#ff9999; color: black; display: inline-block;"><b>Vol - ' . number_format((int) $_GET['total_volume']) . '</b></span>'; 

      $avgVol3Months = "to do";  // $volumeArray[15];

      $avgVol10days = '<span style="font-size: 12px; background-color:#CCFF99; color: black; display: inline-block;"><b>10 day vol - ' . number_format((int) $_GET['ten_day_volume']) . '</b></span>'; 


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


      $googleNews = "<ul class='newsSide'>";
      $i = 0;
      foreach ($googleNewsRSSFeed->channel->item as $feedItem) {
          $i++;
          $googleNews .= "<li "; 

          if ($i % 2 == 1)
          {
            $googleNews .=  "style='background-color: #FFFFFF; '"; 
          };
          
          $googleNews .=  " ><a href='$feedItem->link' title='$feedItem->title'> " . $feedItem->pubDate . " " . $feedItem->title . "</a></li>";
      }
      $googleNews .=  "</ul>";

      $finalReturn = "<td valign='top' >" . str_replace('<a ', '<a target="_blank" onclick="return openPage(this.href)" ', $allNews) . '</td><td valign="top">' . str_replace('<a ', '<a target="_blank" onclick="return openPage(this.href)" ', $googleNews) . '</td>';

      $finalReturn = preg_replace($patterns = array("/<img[^>]+\>/i", "/<embed.+?<\/embed>/im", "/<iframe.+?<\/iframe>/im", "/<script.+?<\/script>/im"), $replace = array("", "", "", ""), $finalReturn);

      $finalReturn = preg_replace('/Headlines/', '<b>Yahoo Headlines</b>', $finalReturn);
      $finalReturn = preg_replace('/<cite>/', '<cite> - ', $finalReturn);              
      $finalReturn = preg_replace('/<span>/', '<span style="font-size: 12px; background-color:#D8D8D8; color:black"><b> ', $finalReturn); 
      $finalReturn = preg_replace('/<\/span>/', '</b></span>', $finalReturn); 

      $finalReturn = preg_replace('/([A-Z][a-z][a-z] [0-9][0-9]:[0-9][0-9][A-Z]M EDT)|([A-Z][a-z][a-z] [0-9]:[0-9][0-9][A-Z]M EDT)/', '<span style="font-size: 12px; background-color:black; color:white">$1$2</span> ', $finalReturn);
      $finalReturn = preg_replace('/([A-Z][a-z][a-z] [0-9][0-9]:[0-9][0-9][A-Z]M EST)|([A-Z][a-z][a-z] [0-9]:[0-9][0-9][A-Z]M EST)/', '<span style="font-size: 12px; background-color:black; color:white">$1$2</span> ', $finalReturn);

      $yahoo_todays_date = date('l' /*, strtotime("-9 hours") */); 
      if ($yahoo_todays_date == "Monday")
      {
        $finalReturn = preg_replace('/(' .  get_yahoo_friday_trade_date() . ')/', '<span style="font-size: 12px; background-color:#000080 ; color:white"> $1</span> ', $finalReturn);
        $finalReturn = preg_replace('/(' .  get_yahoo_saturday_trade_date() . ')/', '<span style="font-size: 12px; background-color:#000080 ; color:white"> $1</span> ', $finalReturn);      
      }

      $finalReturn = preg_replace('/(' .  get_yahoo_yesterday_trade_date() . ')/', '<span style="font-size: 12px; background-color:   #000080; color:white"> $1</span> ', $finalReturn);
      $finalReturn = preg_replace('/(' .  get_yahoo_todays_trade_date() . ')/', '<span style="font-size: 12px; background-color:  black; color:white"> $1</span> ', $finalReturn);

      $finalReturn = preg_replace('/ delist/i', '<span style="font-size: 12px; background-color:red; color:black"><b> delist</span> If delisting tomorrow 65%, if days away then 50-55%</b>', $finalReturn);
      $finalReturn = preg_replace('/ chapter 11|chapter 11 /i', '<span style="font-size: 12px; background-color:red; color:black"><b> &nbsp;CHAPTER 11</b></span>', $finalReturn);
      $finalReturn = preg_replace('/ reverse split|reverse split /i', '<span style="font-size: 12px; background-color:red; color:black"><b> &nbsp;REVERSE SPLIT</b></span>', $finalReturn);
      $finalReturn = preg_replace('/ reverse stock split|reverse stock split /i', '<div style="font-size: 12px; background-color:red; display: inline-block;">REVERSE STOCK SPLIT</div>', $finalReturn);
      $finalReturn = preg_replace('/ downgrade|downgrade /i', '<span style="font-size: 12px; background-color:red; color:black"><b> &nbsp;DOWNGRADE</b></span>', $finalReturn);      
      $finalReturn = preg_replace('/ ex-dividend|ex-dividend /i', '<div style="font-size: 12px; background-color:red; display: inline-block;">EX-DIVIDEND (chase at 25%)</div>', $finalReturn);
      $finalReturn = preg_replace('/ sales miss|sales miss /i', '<span style="font-size: 12px; background-color:red; color:black"><b> &nbsp;SALES MISS (Chase at 65-70%)</b>&nbsp;</span>', $finalReturn);      
      $finalReturn = preg_replace('/ miss sales|miss sales /i', '<span style="font-size: 12px; background-color:red; color:black"><b> &nbsp;MISS SALES (Chase at 65-70%)</b>&nbsp;</span>', $finalReturn);
      $finalReturn = preg_replace('/ disappointing sales|disappointing sales /i', '<span style="font-size: 12px; background-color:red; color:black"><b> &nbsp;DISAPPOINTINT SALES (Chase at 65-70%)</b>&nbsp;</span>', $finalReturn);      
      $finalReturn = preg_replace('/ sales results|sales results /i', '<span style="font-size: 12px; background-color:red; color:black"><b> &nbsp;SALES RESULTS (If bad, chase at 65-70%)</b>&nbsp;</span>', $finalReturn);      
      $finalReturn = preg_replace('/ 8-k/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp;8-K</span> (if it involves litigation, then back off)</b>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ accountant/i', '<span style="font-size: 12px; background-color:red; color:black"><b> &nbsp;accountant (if hiring new accountant, 35-40%)</b>&nbsp;</span>', $finalReturn);            
      $finalReturn = preg_replace('/ clinical trial/i', '<span style="font-size: 12px; background-color:red; color:black"><b> &nbsp;clinical trial</b>&nbsp;</span>', $finalReturn);            
      $finalReturn = preg_replace('/ recall/i', '<span style="font-size: 12px; background-color:red; color:black"><b> &nbsp;recall (bad, back off)</b>&nbsp;</span>', $finalReturn);                  
      $finalReturn = preg_replace('/ etf/i', '<span style="font-size: 12px; background-color:red; color:black"><b> &nbsp;ETF</b>&nbsp;</span>', $finalReturn);                        
      $finalReturn = preg_replace('/ etn/i', '<span style="font-size: 12px; background-color:red; color:black"><b> &nbsp;ETN</b>&nbsp;</span>', $finalReturn);                        
      $finalReturn = preg_replace('/[ \']disruption[ \']/i', '<span style="font-size: 12px; background-color:red; color:black"><b> &nbsp;disruption&nbsp;</span> (chase at 52%)</b>', $finalReturn);
      $finalReturn = preg_replace('/ abandon/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp;abandon&nbsp;</span> (65-70%)</b>', $finalReturn);
      $finalReturn = preg_replace('/ bankrupt/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp;bankrupt&nbsp;</span> (65%)</b>', $finalReturn);      
      $finalReturn = preg_replace('/ terminate| terminates| terminated| termination/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp;terminate&nbsp;</span> (65%) </b>', $finalReturn);            
      $finalReturn = preg_replace('/ drug/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp;drug&nbsp;</span></b>', $finalReturn);
      $finalReturn = preg_replace('/ guidance/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp;guidance</span> (35-40% early)</b>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ regulatory update/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp;regulatory update (35% even if regulation is good)</span></b>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ suspended/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp;suspended</span> (65-70%)</b>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ fraud/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp;fraud</span></b>&nbsp;', $finalReturn);      
      $finalReturn = preg_replace('/ dividend/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp;dividend</span> (if cut, 65-70%)</b>&nbsp;', $finalReturn);            
      $finalReturn = preg_replace('/ strategic alternatives/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp;strategic alternatives</span></b>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ unpatentable/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp;unpatentable</span> (60%)</b>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ accelerate or increase/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp;accelerate or increase</span> (Possible Chapter 11, stay away)</b>&nbsp;', $finalReturn);
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
      $finalReturn = preg_replace('/ convertible senior notes/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp; convertible senior notes (35%)</b></span>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ amendment(.*) to secured credit facilities/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp; amendments to secured credit facilities (65%)</b></span>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ notice of effectiveness/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp; notice of effectiveness (40% till you see the notice)</b></span>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ equity investment/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp; equity investment - look for price of new shares</b></span>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ lease termination/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp; DANGER - Chapter 7 warning - 90%</b></span>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ redemption of public shares/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp; Redemption of Public Shares - 92%</b></span>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ phase 2/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp; Phase 2 - 74% and set a stop loss of around 12%</b></span>&nbsp;', $finalReturn);

      $message_board = '</font><a target="_blank" onclick="return openPage(this.href)" href="http://finance.yahoo.com/quote/' . $symbol . '/community?ltr=1"> Yahoo Message Boards</a>&nbsp;&nbsp;&nbsp;&nbsp;'; 
      $company_profile = '<a target="_blank" onclick="return openPage(this.href)" href="http://finance.yahoo.com/quote/' . $symbol . '/profile">Yahoo Company Profile for ' . $symbol . '</a><br>'; 
      $yahoo_main_page = '<a target="_blank" href="http://finance.yahoo.com/q?s=' . $symbol . '&ql=1">Yahoo Main Page for ' . $symbol . '</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
      $yahoo_5_day_chart = '<a target="_blank" href="http://finance.yahoo.com/echarts?s=' . $symbol . '+Interactive#symbol=' . $symbol . ';range=5d">5-day Chart for ' . $symbol . '</a><br><br>';
      $eTrade = '<a target="_blank" href="https://www.etrade.wallst.com/v1/stocks/news/search_results.asp?symbol=' . $symbol . '">E*TRADE news for ' . $symbol . '</a>';
      $google = '<a target="_blank" onclick="return openPage(this.href)" href="https://www.google.com/search?hl=en&gl=us&tbm=nws&authuser=1&q=' . $google_keyword_string . '">Google news for ' . $symbol . '</a><br>';
        $google = preg_replace('/<h1>/', '', $google);
        $google = preg_replace('/<\/h1>/', '', $google);

      $finalReturn = $returnCompanyName . $companyWebsite . $sectorCountry . $returnYesterdaysClose . $preMarketYesterdaysClose[0] . "<br>" . "<div style='display: inline-block;'>" . $currentVolume . $avgVol10days . $avgVol3Months . $company_profile . $message_board . $google . '<table width="575px"><tr width="575px">' . $finalReturn . '</tr></table>'; 

      echo $finalReturn; 

} // if ($which_website == "yahoo")
else if ($which_website == "bigcharts")
{
      $url = "http://$host_name/quickchart/quickchart.asp?symb=$symbol&insttype=&freq=1&show=&time=8";
      $result = grabHTML($host_name, $url);
      $html = str_get_html($result);  


      $bigChartsYestClose = $html->find('table#quote tbody tr td div'); 
      $bigChartsYestClose[4] = preg_replace('/<div>/', '<div><b>PRE MKT prev close (last)</b> - $', $bigChartsYestClose[4]); 

      $bigChartsHighLow = $html->find('td.maincontent table#quote tbody tr td div'); 

      $bigChartsReturn = $bigChartsYestClose[4]; 

      $bigChartsHigh = $bigChartsHighLow[7];
      $bigChartsLow = $bigChartsHighLow[8];      
      $bigChartsHigh = preg_replace('/<div>/', '', $bigChartsHigh); 
      $bigChartsHigh = preg_replace('/<\/div>/', '', $bigCharsHigh);       
      $bigChartsHigh = (float)$bigChartsHigh; 
      $bigChartsLow = preg_replace('/<div>/', '', $bigChartsLow);       
      $bigChartsLow = preg_replace('/<\/div>/', '', $bigChartsLow); 
      $bigChartsLow = (float)$bigChartsLow;

      $percentageChange = number_format((100*(($bigChartsHigh - $bigChartsLow)/$bigChartsLow)), 4); 

      $bigChartsHigh = number_format($bigChartsHigh, 4); 
      $bigChartsLow = number_format($bigChartsLow, 4);

      $percentageChangeText = '<br><b>&nbsp; % Change</b> - \$' . $bigChartsHigh .'/\$' . $bigChartsLow . ' = ' . $percentageChange; 

      // grab the last vix value 
      $url = "http://bigcharts.marketwatch.com/quickchart/quickchart.asp?symb=vix&insttype=&freq=9&show=&time=1"; 
 
      $result = grabHTML("bigcharts.marketwatch.com", $url);
      $html = str_get_html($result);  

      $vixTDArray = $html->find('table#quote tbody tr td div'); 

      $vixTDArray[4] = preg_replace('/<div\>/', '', $vixTDArray[4]); 
      $vixTDArray[4] = preg_replace('/<\/div>/', '', $vixTDArray[4]); 
      $vixTDReturn = "<br>Last VIX Value: " . $vixTDArray[4];

      $bigChartsReturn = preg_replace('/<\/div>/', $vixTDReturn . '</div>', $bigChartsReturn); 

      echo $bigChartsReturn; 

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