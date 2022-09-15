<?php 

require_once("simple_html_dom.php"); 
error_reporting(E_ALL);

$symbol=$_GET['symbol'];
$secCompanyName = $_GET['secCompanyName'];
$secCompanyName = preg_replace('/ /', '+', $secCompanyName);
$secCompanyName = preg_replace("/<.*?>/", "", $secCompanyName);

$yesterdayDays = 1;

fopen("cookies.txt", "w");

function buildNewsNotes()
{
    $newsNotes = '<ul style="font-family: arial;">
                      <li style="background-color: #00ff00;">SEC</li>
                      <li>Entry into a Material Definitive Agreement - STAY AWAY, SHARE PRICE COMING OUT</li>
                      </ul>
                      '; 
    return $newsNotes; 
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

function get_friday_trade_date()
{
    $friday_yahoo_trade_date = "";

    $friday_yahoo_trade_month_day = date('Y-m-d', strtotime("-3 days"));
    $friday_yahoo_trade_date = $friday_yahoo_trade_month_day;

    return $friday_yahoo_trade_date;
}

function get_saturday_trade_date()
{
    $saturday_yahoo_trade_date = "";

    $saturday_yahoo_trade_month_day = date('Y-m-d', strtotime("-2 days"));
    $saturday_yahoo_trade_date = $saturday_yahoo_trade_month_day;
 
    return $saturday_yahoo_trade_date;
}

function get_yesterday_trade_date()
{
    $yesterday_yahoo_trade_date = "";

    $yesterday_yahoo_trade_month_day = date('Y-m-d', strtotime("-1 days"));
    $yesterday_yahoo_trade_date = $yesterday_yahoo_trade_month_day;

    return $yesterday_yahoo_trade_date;
}

function get_today_trade_date()
{
    $todays_yahoo_trade_date = "";

    $todays_yahoo_trade_month_day = date('Y-m-d');
    $todays_yahoo_trade_date = $todays_yahoo_trade_month_day;

    return $todays_yahoo_trade_date;
}

function get_trade_date($daysBack)
{
    $trade_date = "";

    $trade_month_day = date('Y-m-d', strtotime("-" . $daysBack . " days"));
    $trade_date = $trade_month_day;

    return $trade_date;
}

function getURLTimestamp($url){
    $curl = curl_init($url);

    //don't fetch the actual page, you only want headers
    curl_setopt($curl, CURLOPT_NOBODY, true);

    //stop it from outputting stuff to stdout
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    // attempt to retrieve the modification date
    curl_setopt($curl, CURLOPT_FILETIME, true);

    $result = curl_exec($curl);

    if ($result === false) {
        return "No timestamp";
    }

    $timestamp = curl_getinfo($curl, CURLINFO_FILETIME);
    if ($timestamp != -1) { //otherwise unknown
        return $timestamp; 
    } 
    else
    {
      return "No timestamp";
    }
}

function getDateFromUTC($utcDate)
{
  return date("Y-m-d", strtotime($utcDate));
}

function getAMPMTimeFromUTC($utcDate)
{
    $amPm = "";

    preg_match("/T(\d{2})/", $utcDate, $hh); 

    if ($hh[1] > 12)
    {
      $hh[1] -= 12; 
      $amPm = "PM"; 
    }
    else
    {
      $hh[1] -= 0;       
      $amPm = "AM";
    }

    preg_match("/T\d{2}:(\d{2})/", $utcDate, $mm); 

    $returnTime = $hh[1] . ":" . $mm[1] . " " . $amPm; 
    return $returnTime;
}

function timestampIsSafe($utcDate)
{
    preg_match("/T(\d{2})/", $utcDate, $hh); 

    if ($hh[1] > 12)
    {
      return false; 
    }
    else
    {
      return true;
    }
}

function timestampIsSafeDateColumn($dateValue){
    preg_match("/(\d{2}):\d{2}:\d{2}/", $dateValue, $date_time); 
    if ((int)$date_time[1] > 12)
    {
        return false;
    }
    else
    {
        return true;
    }
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

function grabHTML($function_host_name, $url)
{ 

    $ch = curl_init();
    $header=array('GET /1575051 HTTP/1.1',
    "Host: $function_host_name",
    'Accept:text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
    'Accept-Language:en-US,en;q=0.8',
    'Cache-Control: no-store, no-cache, must-revalidate, max-age=0',
    'Cache-Control: post-check=0, pre-check=0', 
    'Pragma: no-cache', 
//     'Connection:keep-alive',
    'User-Agent:brent@heigoldinvestments.com',
    );

    curl_setopt($ch,CURLOPT_URL,$url);

    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($ch,CURLOPT_CONNECTTIMEOUT, 300);
    curl_setopt( $ch, CURLOPT_COOKIESESSION, true );

    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

/*    curl_setopt($ch,CURLOPT_COOKIEFILE,'cookies.txt');
    curl_setopt($ch,CURLOPT_COOKIEJAR,'cookies.txt');  */ 
    curl_setopt($ch,CURLOPT_HTTPHEADER,$header);
    curl_setopt($ch, CURLOPT_FRESH_CONNECT, TRUE);

    curl_setopt($ch, CURLOPT_VERBOSE, true);
//     curl_setopt($ch, CURLOPT_STDERR,$f = fopen(__DIR__ . "/error.log", "w+"));

    $returnHTML = curl_exec($ch); 

    if($errno = curl_errno($ch)) {
      $error_message = curl_strerror($errno);
      echo "cURL error ({$errno}):\n {$error_message}";
    }   
    curl_close($ch);
    return $returnHTML; 

} // end of function grabHTML

function produce_XML_object_tree($raw_XML) {
    libxml_use_internal_errors(true);
    try {
        $xmlTree = new SimpleXMLElement($raw_XML);
    } catch (Exception $e) {
        // Something went wrong.
        $error_message = 'SimpleXMLElement threw an exception.';
        foreach(libxml_get_errors() as $error_line) {
            $error_message .= "\t" . $error_line->message;
        }
        trigger_error($error_message);
        return false;
    }
    return $xmlTree;
}

function getSectorIndustry()
{

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

    $result = $mysqli->query("SELECT sector, industry, count(*) as count FROM sector GROUP BY sector, industry");


    $html = "";

    if ($result->num_rows > 0) {

        $html = "<div><table style='border: 1px solid black !important;'><tbody>";
        // output data of each row
        while($row = $result->fetch_assoc()) {
            $html .=  "<tr style='font-size: 11px;";

            if ($row["count"] >= 4)
            {
                $html .= " background-color: red; "; 
            }

            $html .= "'><td style='border: 1px solid black !important;'>&nbsp;SECTOR: <b>" . $row["sector"] . "</b></td><td style='border: 1px solid black !important; width: 400px;'>&nbsp;INDUSTRY: <b>" . $row["industry"] . "</b></td><td style='border: 1px solid black !important;'>&nbsp;COUNT: <b>" . $row["count"] . "<b></td></tr>";
        }
        $html .= "</tbody></table></div>";
    } else {
        $html = "<span style='font-size: 15px>Nothing yet</span>";
    }

    $result = $mysqli->query("SELECT country, count(*) as count FROM sector GROUP BY country");

    if ($result->num_rows > 0) {

        $html .= "<div><table style='border: 1px solid black !important;'><tbody>";
        // output data of each row
        while($row = $result->fetch_assoc()) {
            $html .=  "<tr style='font-size: 11px;'><td style='border: 1px solid black !important;'>&nbsp;COUNTRY: <b>" . $row["country"] . "</b></td><td style='border: 1px solid black !important; width: 400px;'>&nbsp;COUNT: <b>" . $row["count"] . "</b></td></tr>";
        }
        $html .= "</tbody></table></div>";
    } else {
        $html .= "<span style='font-size: 15px>Nothing yet for countries</span>";
    }



    return $html;

}  // end of getSectorIndustry



function getStreetInsider($symbol, $yesterdayDays)
{

    $servername = "localhost";
    $username = "superuser";
    $password = "heimer27";
    $db = "daytrade"; 
    $mysqli = null;
    $date = date("Y-m-d"); 
    $streetInsiderNews = "";
    $link = null; 
    $query = null; 
    $reScrape = false; 

    // Check connection
    try {
        $link = mysqli_connect($servername, $username, $password, $db) or die($link); 
    } catch (mysqli_sql_exception $e) {

    } 

    $SQL = "SELECT symbol, htmltext, lastUpdated FROM streetinsider WHERE symbol = '" . $symbol . "'"; 
    try 
    {
        $link->set_charset("utf8");
        $query = mysqli_query($link, $SQL);
        if(!$query)
        {
            echo "Error: " . mysqli_error($link);
        }
    } 
    catch (mysqli_sql_exception $e) 
    {
        echo "Error when selecting from database is " . $e->errorMessage() . "<br>"; 
    } 

    $rowCount = mysqli_num_rows($query); 
    $currentTimeInt = strtotime('- 8 hours'); 
    $currentTime = date('Y-m-d H:i:s', $currentTimeInt); 

    // If it's been over 30 minutes since we last scraped, or we haven't scraped it yet (i.e. no rows in the database) 
    // then we re-scrape (i.e. $reScrape = true) 
    if ($rowCount >= 1)
    {
        while ($myRow = mysqli_fetch_assoc($query))
        {
            $lastUpdatedInt = strtotime($myRow['lastUpdated'] . "- 8 hours");
            $lastUpdated = date('Y-m-d H:i:s', $lastUpdatedInt); 
            $timeDiff = ($currentTimeInt - $lastUpdatedInt)/60; 
            // If it's newer than half-an-hour (i.e. 30.00 minutes) then just use what's stored in the database, because
            // the StreetInsider bot hasn't expired. 
            if ($timeDiff < 25.00)
            {
                $streetInsiderNews = $myRow['htmltext']; 
            }
            else 
            {
                $reScrape = true; 
            }
        }
    }
    else 
    {
        $reScrape = true; 
    }

    if ($reScrape == true)
    {    
        $rssStreetInsider = "https://www.streetinsider.com/freefeed.php?ticker=" . $symbol;
        $xmlStreetInsider=grabHTML('www.streetinsider.com', $rssStreetInsider);
        $xmlFinalObject = produce_XML_object_tree($xmlStreetInsider); 


        $streetInsiderNews = "<ul class='newsSide'>";
        $streetInsiderNews .= "<li style='font-size: 20px !important; background-color: #00ff00;'>StreetInsider News</li>";

        $classActionAdded = false;
        $j = 0;

        $previousNewsTitle = "";
        $currentNewsTitle = ""; 

        foreach ($xmlFinalObject->channel->item as $feedItem) {
            $j++;

            // Convert time from GMT to  AM/PM New York
            $publicationDateStrToTime = strtotime($feedItem->pubDate) - 14400;

            $convertedDate = new DateTime(); 
            $convertedDate->setTimestamp($publicationDateStrToTime);

            $publicationDate = $feedItem->pubDate;
            $publicationDate = preg_replace("/[0-9][0-9]\:[0-9][0-9]\:[0-9][0-9] \-[0-9][0-9][0-9][0-9]/", "", $publicationDate); 
            $publicationTime = $convertedDate->format("g:i A");

            $newsTitle = $feedItem->title; 
            $currentNewsTitle = $newsTitle; 
            if (strcmp($previousNewsTitle, $currentNewsTitle) == 0)
            {
                $j--; 
                continue; 
            }

            if (preg_match('/class.action/i', $newsTitle))
            {
                if ($classActionAdded == true)
                {
                  $j--;
                  continue;              
                }
                else
                {
                  $classActionAdded = true;
                }
            }

            $streetInsiderNews .= "<li "; 

            // red/green highlighting for yesterday/today
            for ($i = $yesterdayDays; $i >= 1; $i--)
            {
                if (preg_match('/(' .  get_yahoo_trade_date($i) . ')/', $publicationDate))
                {
                    $publicationTime = preg_replace('/PM/', '<span style="background-color: red">PM</span>', $publicationTime); 
                    if ($i == $yesterdayDays) 
                    {
                        $publicationTime = preg_replace('/AM/', '<span style="background-color: #00ff00">AM</span>', $publicationTime); 
                  
                    }
                    else
                    {
                        $publicationTime = preg_replace('/AM/', '<span style="background-color: red">AM</span>', $publicationTime); 
                    }  
                }
            }

            if ($j % 2 == 1)
            {
              $streetInsiderNews .=  "style='background-color: #ebd8bd; '"; 
            };
            
            // if the regular expression contains (.*) then we need to do it per title, to avoid greedy regular expressions

            $newsTitle = preg_replace('/ withdrawal(.*?)application/i', '<span style="font-size: 12px; background-color:red; color:black"><b> withdrawal $1 application (55%) </b></span> ', $newsTitle);
            $newsTitle = preg_replace('/nasdaq rejects(.*?)listing/i', '<span style="font-size: 12px; background-color:red; color:black"><b>Nasdaq rejects $1 listing</span> If delisting tomorrow 65%, if delisting days away then 50-55%</b>&nbsp;', $newsTitle);

            $streetInsiderNews .=  " ><a target='_blank' href='$feedItem->link'> " . $publicationDate . " " . $publicationTime . " - <br>" . $newsTitle . "</a>";

            $previousNewsTitle = $currentNewsTitle; 
        } // looping through each news channel item 

        $streetInsiderNews .=  "</ul>";

        $streetInsiderNews = preg_replace('/(' .  get_yahoo_yesterday_trade_date() . ')/', '<span style="font-size: 12px; background-color:   #0747a1; color:white; border: 1px solid red; "> $1</span> ', $streetInsiderNews);
        $streetInsiderNews = preg_replace('/(' .  get_yahoo_todays_trade_date() . ')/', '<span style="font-size: 12px; background-color:  black; color:white; border: 1px solid red; "> $1</span> ', $streetInsiderNews);

        $streetInsiderNews .=  "<br>"; 

        // yellow highlighting for before yesterday
        for ($daysBack = 14; $daysBack > $yesterdayDays; $daysBack--)
        {
            $streetInsiderNews = preg_replace('/(' .  get_yahoo_trade_date($daysBack) . ')/', '<span style="font-size: 12px; background-color:yellow ; color:black">$1</span>', $streetInsiderNews);      
        }
        // blue highlighting for yesterday
        for ($daysBack = $yesterdayDays; $daysBack >= 1; $daysBack--)
        {
            $streetInsiderNews = preg_replace('/(' .  get_yahoo_trade_date($daysBack) . ')/', '<span style="font-size: 12px; background-color:#0747a1 ; color:white">$1</span>', $streetInsiderNews);
        }

        $streetInsiderNews .= "yesterdayDays is " . $yesterdayDays . "<br>"; 


          $streetInsiderNews = preg_replace('/(' .  get_yahoo_yesterday_trade_date() . ')/', '<span style="font-size: 12px; background-color:   #0747a1; color:white"> $1</span> ', $streetInsiderNews);
          $streetInsiderNews = preg_replace('/(' .  get_yahoo_todays_trade_date() . ')/', '<span style="font-size: 12px; background-color:  black; color:white"> $1</span> ', $streetInsiderNews);






          $streetInsiderNews = preg_replace('/ delist/i', '<span style="font-size: 25px; background-color:red; color:black"><b> delist If delisting tomorrow 65%, if days away then 50-55%</b></span>', $streetInsiderNews);
          $streetInsiderNews = preg_replace('/Delist/', '<span style="font-size: 25px; background-color:red; color:black"><b> delist If delisting tomorrow 65%, if days away then 50-55%</b></span>', $streetInsiderNews);
          $streetInsiderNews = preg_replace('/ chapter 11|chapter 11 /i', '<span style="font-size: 25px; background-color:red; color:black"><b> &nbsp;CHAPTER 11</b></span>', $streetInsiderNews);
          $streetInsiderNews = preg_replace('/ reverse split|reverse split /i', '<span style="font-size: 25px; background-color:red; color:black"><b> &nbsp;REVERSE SPLIT</b></span>', $streetInsiderNews);
          $streetInsiderNews = preg_replace('/ reverse.stock split|reverse stock split /i', '<div style="font-size: 25px; background-color:red; display: inline-block;">REVERSE STOCK SPLIT</div>', $streetInsiderNews);
          $streetInsiderNews = preg_replace('/ downgrade|downgrade /i', '<span style="font-size: 12px; background-color:red; color:black"><b> &nbsp;DOWNGRADE</b></span>', $streetInsiderNews);      
          $streetInsiderNews = preg_replace('/ ex-dividend|ex-dividend /i', '<div style="font-size: 12px; background-color:red; display: inline-block;">EX-DIVIDEND (chase at 25%)</div>', $streetInsiderNews);
          $streetInsiderNews = preg_replace('/ sales miss|sales miss /i', '<span style="font-size: 12px; background-color:red; color:black"><b> &nbsp;SALES MISS (Chase at 65-70%)</b>&nbsp;</span>', $streetInsiderNews);      
          $streetInsiderNews = preg_replace('/ miss sales|miss sales /i', '<span style="font-size: 12px; background-color:red; color:black"><b> &nbsp;MISS SALES (Chase at 65-70%)</b>&nbsp;</span>', $streetInsiderNews);
          $streetInsiderNews = preg_replace('/ disappointing sales|disappointing sales /i', '<span style="font-size: 12px; background-color:red; color:black"><b> &nbsp;DISAPPOINTINT SALES (Chase at 65-70%)</b>&nbsp;</span>', $streetInsiderNews);      
          $streetInsiderNews = preg_replace('/ sales results|sales results /i', '<span style="font-size: 12px; background-color:red; color:black"><b> &nbsp;SALES RESULTS (If bad, chase at 65-70%)</b>&nbsp;</span>', $streetInsiderNews);      
          $streetInsiderNews = preg_replace('/ 8-k/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp;8-K</span> (if it involves litigation, then back off)</b>&nbsp;', $streetInsiderNews);
          $streetInsiderNews = preg_replace('/ accountant/i', '<span style="font-size: 25px; background-color:red; color:black"><b> &nbsp;accountant (if hiring new accountant, 35-40%)</b>&nbsp;</span>', $streetInsiderNews);            
          $streetInsiderNews = preg_replace('/ clinical trial/i', '<span style="font-size: 12px; background-color:red; color:black"><b> &nbsp;clinical trial</b>&nbsp;</span>', $streetInsiderNews);            
          $streetInsiderNews = preg_replace('/ recall/i', '<span style="font-size: 25px; background-color:red; color:black"><b> &nbsp;recall (bad, back off)</b>&nbsp;</span>', $streetInsiderNews);                  
          $streetInsiderNews = preg_replace('/ etf/i', '<span style="font-size: 12px; background-color:red; color:black"><b> &nbsp;ETF</b>&nbsp;</span>', $streetInsiderNews);                        
          $streetInsiderNews = preg_replace('/ etn/i', '<span style="font-size: 12px; background-color:red; color:black"><b> &nbsp;ETN</b>&nbsp;</span>', $streetInsiderNews);                        
          $streetInsiderNews = preg_replace('/[ \']disruption[ \']/i', '<span style="font-size: 12px; background-color:red; color:black"><b> &nbsp;disruption&nbsp;</span> (chase at 52%)</b>', $streetInsiderNews);
          $streetInsiderNews = preg_replace('/ abandon/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp;abandon&nbsp;</span> (65-70%)</b>', $streetInsiderNews);
          $streetInsiderNews = preg_replace('/ bankrupt/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp;bankrupt&nbsp;</span> (65%)</b>', $streetInsiderNews);      
          $streetInsiderNews = preg_replace('/ terminate| terminates| terminated| termination/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp;terminate&nbsp;</span> (65%) </b>', $streetInsiderNews);            

          $streetInsiderNews = preg_replace('/ drug/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp;drug </span></b> ', $streetInsiderNews);

          $streetInsiderNews = preg_replace('/ guidance/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp;GUIDANCE</span></b>&nbsp;', $streetInsiderNews);
          $streetInsiderNews = preg_replace('/ regulatory update/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp;regulatory update (35% even if regulation is good)</span></b>&nbsp;', $streetInsiderNews);
          $streetInsiderNews = preg_replace('/ suspended/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp;suspended</span> (65-70%)</b>&nbsp;', $streetInsiderNews);
          $streetInsiderNews = preg_replace('/ fraud/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp;fraud</span></b>&nbsp;', $streetInsiderNews);      
          $streetInsiderNews = preg_replace('/ dividend/i', '<span style="font-size: 25px; background-color:#202020; color:white"><b>&nbsp;dividend (if cut, 35% ALSO CHECK THE ISSUEING DATE)</span></b>&nbsp;', $streetInsiderNews);            
          $streetInsiderNews = preg_replace('/ strategic alternatives/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp;strategic alternatives</span></b>&nbsp;', $streetInsiderNews);
          $streetInsiderNews = preg_replace('/ unpatentable/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp;unpatentable</span> (60%)</b>&nbsp;', $streetInsiderNews);
          $streetInsiderNews = preg_replace('/ accelerate or increase/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp;accelerate or increase</span> (Possible Chapter 11, stay away)</b>&nbsp;', $streetInsiderNews);
          $streetInsiderNews = preg_replace('/ denial of application/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp;denial of application</span> (65%)</b>&nbsp;', $streetInsiderNews);
          $streetInsiderNews = preg_replace('/ restructuring support agreement/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp;Restructuring Support Agreement</span> (53%)</b>&nbsp;', $streetInsiderNews);
          $streetInsiderNews = preg_replace('/ breach of contract/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp;breach of contract</span> (If lost lawsuit, then 75%, if won then 35% premarket/first round)</b>&nbsp;', $streetInsiderNews);      
          $streetInsiderNews = preg_replace('/ jury verdict/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp;jury verdict</span> BE CAREFUL (If lost major lawsuit, then 70-75%)</b>&nbsp;', $streetInsiderNews);      
          $streetInsiderNews = preg_replace('/ fcc/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp;FCC</span> if regulation had long-term ratifications, then 65-70%</b>&nbsp;', $streetInsiderNews);      
          $streetInsiderNews = preg_replace('/ layoffs/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp;layoffs</span> (check Jay\'s percentages)</b>&nbsp;', $streetInsiderNews);      
          $streetInsiderNews = preg_replace('/ layoff/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp;layoff</span> (check Jay\'s percentages)</b>&nbsp;', $streetInsiderNews);      
          $streetInsiderNews = preg_replace('/ lays off/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp;lays off</span> (check Jay\'s percentages)</b>&nbsp;', $streetInsiderNews);      
          $streetInsiderNews = preg_replace('/ restructuring/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp;restructuring</span></b>&nbsp;', $streetInsiderNews);      
          $streetInsiderNews = preg_replace('/ restructure/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp;restructure</span></b>&nbsp;', $streetInsiderNews);      
          $streetInsiderNews = preg_replace('/ clinical hold/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp;clinical hold</span> (65 - 70%)</b>&nbsp;', $streetInsiderNews);
          $streetInsiderNews = preg_replace('/ convertible senior notes/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; convertible senior notes (back off until you see a price)</b></span>&nbsp;', $streetInsiderNews);
          $streetInsiderNews = preg_replace('/ amendment(.*) to secured credit facilities/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp; amendments to secured credit facilities (65%)</b></span>&nbsp;', $streetInsiderNews);
          $streetInsiderNews = preg_replace('/ notice of effectiveness/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp; notice of effectiveness (40% till you see the notice)</b></span>&nbsp;', $streetInsiderNews);
          $streetInsiderNews = preg_replace('/ equity investment/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp; equity investment - look for price of new shares</b></span>&nbsp;', $streetInsiderNews);
          $streetInsiderNews = preg_replace('/ lease termination/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp; DANGER - Chapter 7 warning - 90%</b></span>&nbsp;', $streetInsiderNews);
          $streetInsiderNews = preg_replace('/ redemption of public shares/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp; Redemption of Public Shares - 92%</b></span>&nbsp;', $streetInsiderNews);
          $streetInsiderNews = preg_replace('/ phase 2/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp; Phase 2 - 82% and set a stop loss of around 12%</b></span>&nbsp;', $streetInsiderNews);
          $streetInsiderNews = preg_replace('/ investor call/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; INVESTOR CALL - CHECK THE DATE &nbsp;</b></span>&nbsp;', $streetInsiderNews);
          $streetInsiderNews = preg_replace('/ strategic update/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp; STRATEGIC UPDATE - BE CAREFUL &nbsp;</b></span>&nbsp;', $streetInsiderNews);
          $streetInsiderNews = preg_replace('/ attorney general/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp; attorney general (if there is an attorney general probe then 45-50%) &nbsp;</b></span>&nbsp;', $streetInsiderNews);
          $streetInsiderNews = preg_replace('/ merger/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp; merger - if changing deadline (or update in general, 35%), if lawfirm investigating then 22%  &nbsp;</b></span>&nbsp;', $streetInsiderNews);
          $streetInsiderNews = preg_replace('/ preliminary(.*?)outlook/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp; Preliminary$1Outlook -</span> <span style="font-size: 12px; background-color:lightgreen; color:black">41% right off the bat, then 48% literally 3 minutes later.  TAKE NO MORE THAN 5% AND BAIL &nbsp;</b></span>&nbsp;', $streetInsiderNews);
          $streetInsiderNews = preg_replace('/ conference call to provide update/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp; Conference Call to Provide Update -</span> <span style="font-size: 12px; background-color:lightgreen; color:black">CHECK THE DATE/TIME OF THE CALL &nbsp;</b></span>&nbsp;', $streetInsiderNews);
          $streetInsiderNews = preg_replace('/ seeking alpha/i', '<span style="font-size: 25px; background-color:red; color:black">SEEKING ALPHA &nbsp;</b></span>&nbsp;', $streetInsiderNews);
          $streetInsiderNews = preg_replace('/ fictitious sales/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp; fictitious sales - STAY AWAY </b></span> &nbsp;', $streetInsiderNews);     
          $streetInsiderNews = preg_replace('/ board of directors/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp; board of directors - if change to board of directors - 20% </b></span> &nbsp;', $streetInsiderNews);
          $streetInsiderNews = preg_replace('/ class action/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; class action</b></span> &nbsp;', $streetInsiderNews);
          $streetInsiderNews = preg_replace('/ business combination/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp; business combination - BE CAREFUL, could get de-listed </b></span> &nbsp;', $streetInsiderNews);
          $streetInsiderNews = preg_replace('/ annual meeting of shareholders/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp; annual meeting of shareholders - 40% early</b></span> &nbsp;', $streetInsiderNews);
          $streetInsiderNews = preg_replace('/ transcript/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; transcript </b></span> &nbsp;', $streetInsiderNews);
          $streetInsiderNews = preg_replace('/ share consolidation/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; share consolidation - REVERSE STOCK SPLIT </b></span> &nbsp;', $streetInsiderNews);
          $streetInsiderNews = preg_replace('/ share exchange transaction/i', '<span style="font-size: 20px; background-color:red; color:black"><b>&nbsp; share exchange transaction - 47% </b></span> &nbsp;', $streetInsiderNews);
          $streetInsiderNews = preg_replace('/ closes(.*)offering/i', '<span style="font-size: 22px; background-color:red; color:black"><b>&nbsp; closes offering - be careful, 24%</b></span>&nbsp;', $streetInsiderNews);
          $streetInsiderNews = preg_replace('/ announces pricing/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; Announces Pricing </b></span>&nbsp;', $streetInsiderNews);
          $streetInsiderNews = preg_replace('/ late interest payment/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; late interest payment - BACK OFF, POSSIBLE CHAPTER 11 </b></span>&nbsp;', $streetInsiderNews);
          $streetInsiderNews = preg_replace('/ q1 loss/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; Q1 LOSS </b></span>&nbsp;', $streetInsiderNews);
          $streetInsiderNews = preg_replace('/ q2 loss/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; Q2 LOSS </b></span>&nbsp;', $streetInsiderNews);
          $streetInsiderNews = preg_replace('/ q3 loss/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; Q3 LOSS </b></span>&nbsp;', $streetInsiderNews);
          $streetInsiderNews = preg_replace('/ q4 loss/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; Q4 LOSS </b></span>&nbsp;', $streetInsiderNews);
          $streetInsiderNews = preg_replace('/ first.quarter/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; FIRST QUARTER </b></span>&nbsp;', $streetInsiderNews);
          $streetInsiderNews = preg_replace('/ second.quarter/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; SECOND QUARTER </b></span>&nbsp;', $streetInsiderNews);
          $streetInsiderNews = preg_replace('/ third.quarter/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; THIRD QUARTER </b></span>&nbsp;', $streetInsiderNews);
          $streetInsiderNews = preg_replace('/ fourth.quarter/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; FOURTH QUARTER </b></span>&nbsp;', $streetInsiderNews);
          $streetInsiderNews = preg_replace('/ q1 results/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; Q1 RESULTS </b></span>&nbsp;', $streetInsiderNews);
          $streetInsiderNews = preg_replace('/ q2 results/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; Q2 RESULTS </b></span>&nbsp;', $streetInsiderNews);
          $streetInsiderNews = preg_replace('/ q3 results/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; Q3 RESULTS </b></span>&nbsp;', $streetInsiderNews);
          $streetInsiderNews = preg_replace('/ q4 results/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; Q4 RESULTS </b></span>&nbsp;', $streetInsiderNews);
          $streetInsiderNews = preg_replace('/ q1 - results/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; Q1 - RESULTS </b></span>&nbsp;', $streetInsiderNews);
          $streetInsiderNews = preg_replace('/ q2 - results/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; Q2 - RESULTS </b></span>&nbsp;', $streetInsiderNews);
          $streetInsiderNews = preg_replace('/ q3 - results/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; Q3 - RESULTS </b></span>&nbsp;', $streetInsiderNews);
          $streetInsiderNews = preg_replace('/ q4 - results/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; Q4 - RESULTS </b></span>&nbsp;', $streetInsiderNews);
          $streetInsiderNews = preg_replace('/ q1 earnings/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; Q1 EARNINGS </b></span>&nbsp;', $streetInsiderNews);
          $streetInsiderNews = preg_replace('/ q2 earnings/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; Q2 EARNINGS </b></span>&nbsp;', $streetInsiderNews);
          $streetInsiderNews = preg_replace('/ q3 earnings/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; Q3 EARNINGS </b></span>&nbsp;', $streetInsiderNews);
          $streetInsiderNews = preg_replace('/ q4 earnings/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; Q4 EARNINGS </b></span>&nbsp;', $streetInsiderNews);
          $streetInsiderNews = preg_replace('/ withdrawal of auditors\' report/i', "<span style=\"font-size: 25px; background-color:red; color:black\"><b>&nbsp; Withdrawal of Auditors' Report - 40\% </b></span>&nbsp;", $streetInsiderNews);
          $streetInsiderNews = preg_replace('/ withdraws audit report/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; withdraws audit report - 40% </b></span>&nbsp;', $streetInsiderNews);
          $streetInsiderNews = preg_replace('/ withdrawing audits/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; withdrawing audits - 40% </b></span>&nbsp;', $streetInsiderNews);
          $streetInsiderNews = preg_replace('/ move to otc/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; MOVE TO OTC - DELISTING - BE CAREFUL </b></span>&nbsp;', $streetInsiderNews);
          $streetInsiderNews = preg_replace('/ corporate update/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; CORPORATE UPDATE - BACK OFF </b></span>&nbsp;', $streetInsiderNews);
          $streetInsiderNews = preg_replace('/ provides update/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; PROVIDES UPDATE - BACK OFF </b></span>&nbsp;', $streetInsiderNews);
          $streetInsiderNews = preg_replace('/ external advisor| external adviser/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; EXTERNAL ADVISOR - BACK OFF </b></span>&nbsp;', $streetInsiderNews);
          $streetInsiderNews = preg_replace('/ turnaround/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; TURNAROUND - BACK OFF </b></span>&nbsp;', $streetInsiderNews);
          $streetInsiderNews = preg_replace('/ clinical.stage/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; CLINICAL STAGE - BACK OFF </b></span>&nbsp;', $streetInsiderNews);      
          $streetInsiderNews = preg_replace('/ ratio change/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; RATIO CHANGE - REVERSE SPLIT </b></span>&nbsp;', $streetInsiderNews);
          $streetInsiderNews = preg_replace('/ 10\-q/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; 10-Q </b></span>&nbsp;', $streetInsiderNews);
          $streetInsiderNews = preg_replace('/ registered direct offering/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; REGISTERED DIRECT OFFERING </b></span>&nbsp;', $streetInsiderNews);
          $streetInsiderNews = preg_replace('/ fda clearance/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; FDA Clearance - back off </b></span>&nbsp;', $streetInsiderNews);
          $streetInsiderNews = preg_replace('/ deficiency in compliance/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; Deficiency in Compliance - CHECK FOR DELISTING</b></span>&nbsp;', $streetInsiderNews);
          $streetInsiderNews = preg_replace('/ net asset value/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; NET ASSET VALUE - 25%</b></span>&nbsp;', $streetInsiderNews);
          $streetInsiderNews = preg_replace('/ efficacy data/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; EFFICACY DATA - DRUG NEWS</b></span>&nbsp;', $streetInsiderNews);
          $streetInsiderNews = preg_replace('/ phase 1/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; PHASE 1!!!!</b></span>&nbsp;', $streetInsiderNews);
          $streetInsiderNews = preg_replace('/ phase 2/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; PHASE 2!!!!</b></span>&nbsp;', $streetInsiderNews); 
          $streetInsiderNews = preg_replace('/ phase 3/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; PHASE 3!!!!</b></span>&nbsp;', $streetInsiderNews); 
          $streetInsiderNews = preg_replace('/ to present/i', '<span style="font-size: 25px; background-color:#202020; color:white"><b>&nbsp; TO PRESENT - CHECK DATE - IF EARNINGS THEN OK TO CHASE AROUND 21-23%</b></span>&nbsp;', $streetInsiderNews);
          $streetInsiderNews = preg_replace('/ shareholder investigation/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; SHAREHOLDER INVESTIGATION - 19.5%</b></span>&nbsp;', $streetInsiderNews);
          $streetInsiderNews = preg_replace('/ announces an investigation/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; ANNOUNCES AN INVESTIGATION - 19.5%</b></span>&nbsp;', $streetInsiderNews);
          $streetInsiderNews = preg_replace('/ convertible bonds/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; convertible bonds (back off until you see a price)</b></span>&nbsp;', $streetInsiderNews);
          $streetInsiderNews = preg_replace('/ equity grants/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; EQUITY GRANTS - (20-23% early on)</b></span>&nbsp;', $streetInsiderNews);
          $streetInsiderNews = preg_replace('/ to announce/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; TO ANNOUNCE - CHECK THE DATE </b></span>&nbsp;', $streetInsiderNews);
          $streetInsiderNews = preg_replace('/ to report/i', '<span style="font-size: 25px; background-color:#202020; color:white"><b>&nbsp; TO REPORT - CHECK THE DATE - DO NOT CHASE EARLY </b></span>&nbsp;', $streetInsiderNews);
          $streetInsiderNews = preg_replace('/ to host/i', '<span style="font-size: 25px; background-color:#202020; color:white"><b>&nbsp; TO HOST - CHECK THE DATE </b></span>&nbsp;', $streetInsiderNews);
          $streetInsiderNews = preg_replace('/ to release/i', '<span style="font-size: 25px; background-color:#202020; color:white"><b>&nbsp; TO RELEASE - CHECK THE DATE </b></span>&nbsp;', $streetInsiderNews);
          $streetInsiderNews = preg_replace('/ schedules/i', '<span style="font-size: 25px; background-color:#202020; color:white"><b>&nbsp; SCHEDULES - CHECK THE DATE </b></span>&nbsp;', $streetInsiderNews);
          $streetInsiderNews = preg_replace('/ sets date for the release of/i', '<span style="font-size: 25px; background-color:#202020; color:white"><b>&nbsp; SETS DATE FOR THE RELEASE OF - CHECK THE DATE </b></span>&nbsp;', $streetInsiderNews);
          $streetInsiderNews = preg_replace('/ collaboration/i', '<span style="font-size: 25px; background-color: red; color:black"><b>&nbsp; COLLABORATION - CAREFUL </b></span>&nbsp;', $streetInsiderNews);
          $streetInsiderNews = preg_replace('/ china/i', '<span style="font-size: 65px; background-color: red; color:black"><b>&nbsp; CHINA </b></span>&nbsp;', $streetInsiderNews);
          $streetInsiderNews = preg_replace('/ taiwan/i', '<span style="font-size: 65px; background-color: red; color:black"><b>&nbsp; TAIWAN </b></span>&nbsp;', $streetInsiderNews);
          $streetInsiderNews = preg_replace('/ hong kong/i', '<span style="font-size: 65px; background-color: red; color:black"><b>&nbsp; HONG KONG </b></span>&nbsp;', $streetInsiderNews);
          $streetInsiderNews = preg_replace('/ kerrisdale/i', '<span style="font-size: 25px; background-color: red; color:black"><b>&nbsp; Kerrisdale - 65% </b></span>&nbsp;', $streetInsiderNews);
          $streetInsiderNews = preg_replace('/ clinical/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; CLINICAL - DRUG NEWS </b></span>&nbsp;', $streetInsiderNews);
          $streetInsiderNews = preg_replace('/ preclinical/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; PRECLINICAL - DRUG NEWS </b></span>&nbsp;', $streetInsiderNews);
          $streetInsiderNews = preg_replace('/ to showcase/i', '<span style="font-size: 25px; background-color: red; color:black"><b>&nbsp; TO SHOWCASE - CHECK THE DATE </b></span>&nbsp;', $streetInsiderNews);
          $streetInsiderNews = preg_replace('/ rescue financing/i', '<span style="font-size: 25px; background-color: red; color:black"><b>&nbsp; 
              RESCUE FINANCING - BE CAREFUL</b></span>&nbsp;', $streetInsiderNews);
          $streetInsiderNews = preg_replace('/ begins trading/i', '<span style="font-size: 25px; background-color: red; color:black"><b>&nbsp; BEGINS TRADING - 29%</b></span>&nbsp;', $streetInsiderNews);
          $streetInsiderNews = preg_replace('/ consider shorting/i', '<span style="font-size: 25px; background-color: red; color:black"><b>&nbsp; CONSIDER SHORTING - 35%</b></span>&nbsp;', $streetInsiderNews);
          $streetInsiderNews = preg_replace('/ liquidity/i', '<span style="font-size: 25px; background-color: red; color:black"><b>&nbsp; LIQUIDITY - BACK OFF</b></span>&nbsp;', $streetInsiderNews);
          $streetInsiderNews = preg_replace('/annual report /i', '<span style="font-size: 25px; background-color: red; color:black"><b>&nbsp; LIQUIDITY - BACK OFF</b></span>&nbsp;', $streetInsiderNews);
          $streetInsiderNews = preg_replace('/IPO/', '<span style="font-size: 55px; background-color: red; color:black"><b>&nbsp; IPO </b></span>&nbsp;', $streetInsiderNews);
          $streetInsiderNews = preg_replace('/ call put ratio/i', '<span style="font-size: 25px; background-color: red; color:black"><b>&nbsp; CALL PUT RATIO - OK AT 20%</b></span>&nbsp;', $streetInsiderNews);
          $streetInsiderNews = preg_replace('/ s-1/i', '<span style="font-size: 25px; background-color: red; color:black"><b>&nbsp; s-1 - BACK OFF!!</b></span>&nbsp;', $streetInsiderNews);
          $streetInsiderNews = preg_replace('/(Files \$.*M)/i', '<span style="font-size: 25px; background-color: red; color:black"><b>&nbsp; $1 - OFFERING!!!</b></span>&nbsp;', $streetInsiderNews);
          $streetInsiderNews = preg_replace('/ (cuts.*?guidance)/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp;$1 </span></b>&nbsp;', $streetInsiderNews); 
          $streetInsiderNews = preg_replace('/ chapter 22/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp;CHAPTER 22</span></b>&nbsp;', $streetInsiderNews);
          $streetInsiderNews = preg_replace('/ (disappointing.*?results)/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp;$1</span></b>&nbsp;', $streetInsiderNews);
          $streetInsiderNews = preg_replace('/ (reports.*?results)/i', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp;$1</span></b>&nbsp;', $streetInsiderNews);

        try 
        {
            $link->set_charset("utf8");
            $query = mysqli_query($link, "REPLACE INTO streetinsider (symbol, htmltext) VALUES ('" . $symbol . "', '" . mysqli_real_escape_string($link, $streetInsiderNews) . "')");
            if(!$query)
            {
                echo "Error: " . mysqli_error($link);
            }
        } 
        catch (mysqli_sql_exception $e) 
        {
            echo "Error when writing to database is " . $e->errorMessage() . "<br>"; 
        } 
    }  // if either we didn't find it in the database, or it hasn't been half an hour since we last scraped it. 

    return "<div style='height: 250px; width: 100%; overflow-y:auto; border-style: double !important; border-color: black !important; color: black;'>" . $streetInsiderNews . "</div>"; 

} // end of getStreetInsider 

$ret = "";
$finalReturn = "";
$noTimeFound = false;

      // try https://www.nasdaq.com/symbol/staf/sec-filings

      $url = "https://www.sec.gov/cgi-bin/browse-edgar?CIK=" . $symbol . "&owner=include&action=getcompany&rand=" . rand(); 
      $result = grabHTML('www.sec.gov', $url); 

      if (preg_match('/This page is temporarily unavailable/', $result))
      {
          echo '<!DOCTYPE html><html><title>Filing - ' . $symbol . ' (NOT FOUND)</title><body>
              <a style="font-size: 35px" target="_blank" href="https://www.nasdaq.com/symbol/' . $symbol . '/sec-filings">Nasdaq</a><br>
                  <a style="font-size: 35px" target="_blank" href=https://www.etrade.wallst.com/v1/stocks/snapshot/snapshot.asp?ChallengeUrl=https://idp.etrade.com/idp/SSO.saml2&reinitiate-handshake=0&prospectnavyear=2011&AuthnContext=prospect&env=PRD&symbol=' . $symbol . '&rsO=new&country=US>E*TRADE</a>
                <br><div style="background-color: red"><span style="font-size: 55px">SEC WEBSITE IS DOWN</span></div>' . getStreetInsider($symbol, $yesterdayDays) . getSectorIndustry() . 
                '</body></html>';  
          return; 
      }

      if (preg_match('/No matching Ticker Symbol/', $result))
      {
          $url = "https://www.sec.gov/cgi-bin/browse-edgar?company=" . $secCompanyName . "&owner=include&action=getcompany&rand=" . rand();  
          $result = grabHTML('www.sec.gov', $url); 

          if (preg_match('/No matching companies/', $result))
          {
              echo '<!DOCTYPE html><html><title>Filing - ' . $symbol . ' (NOT FOUND)</title><body>
                  <a style="font-size: 35px" target="_blank" href="https://www.nasdaq.com/symbol/' . $symbol . '/sec-filings">Nasdaq</a><br>
                  <a style="font-size: 35px" target="_blank" href=https://www.etrade.wallst.com/v1/stocks/snapshot/snapshot.asp?ChallengeUrl=https://idp.etrade.com/idp/SSO.saml2&reinitiate-handshake=0&prospectnavyear=2011&AuthnContext=prospect&env=PRD&symbol=' . $symbol . '&rsO=new&country=US>E*TRADE</a>
                <br>
                <br><div style="background-color: red"><span style="font-size: 55px">NO MATCHING COMPANIES</span></div>' . getStreetInsider($symbol, $yesterdayDays) . getSectorIndustry() . 
                '</body></html>';  
              return; 
          }

          if (preg_match('/Companies with names matching/', $result))
          {
              echo '<!DOCTYPE html><html><title>Filing - ' . $symbol . ' (AMBIGUOUS)</title><body>
                  <a style="font-size: 35px" target="_blank" href="https://www.nasdaq.com/symbol/' . $symbol . '/sec-filings">Nasdaq</a><br>
                  <a style="font-size: 35px" target="_blank" href=https://www.etrade.wallst.com/v1/stocks/snapshot/snapshot.asp?ChallengeUrl=https://idp.etrade.com/idp/SSO.saml2&reinitiate-handshake=0&prospectnavyear=2011&AuthnContext=prospect&env=PRD&symbol=' . $symbol . '&rsO=new&country=US>E*TRADE</a>
                <br><div style="background-color: red"><span style="font-size: 55px">AMBIGUOUS</span></div>' . $result . getStreetInsider($symbol, $yesterdayDays) . getSectorIndustry() . 
                '</body></html>';  
              return; 
          }
      }
      $html = str_get_html($result);

      $rssTableRow = $html->find(' div table tbody tr'); 
      $rssLink = $rssTableRow[3]->find('a');
      $rssFullLink = "https://www.sec.gov" . $rssLink[0]->href; 
      $rssFullLink = strval($rssFullLink); 

      preg_match('/CIK\=(.*?)\&/', $rssLink[0]->href, $group); 

      $cik = $group[1]; 

      $returnHtml = "";
      $tableRows = "";
      $recentNews = false;
      $secTableRowCount = 0; 

      $command = escapeshellcmd('python3 ../pythonscrape/scrape-sec-gov.py ' . $cik);
      $secXMLString = shell_exec($command);

//       $xmlFinalString=grabHTML('www.sec.gov', "https://www.sec.gov/cgi-bin/browse-edgar?action=getcompany&CIK=0001430306&type=&dateb=&owner=include&start=0&count=40&output=atom");

//k       $xmlFinalString=grabHTML('www.sec.gov', $secXMLString);

      $xmlFinalObject = produce_XML_object_tree($secXMLString); 

          $registrationOffering = "";

          for ($i = 0; $i < 5; $i++)
           { 
              $entryRowObject = $xmlFinalObject->entry[0];
              $filingType = "";

              if (!$entryRowObject)
              {
                  break;
              }

              $entryContent = $xmlFinalObject->entry[$i]->content; 

              $title = ""; 
              $datestamp = getDateFromUTC($xmlFinalObject->entry[$i]->updated);
              $itemDescription = ""; 

              foreach($entryContent as $k => $v){
                  foreach ($v as $key => $value)
                  {
                      if ($key == 'filing-type')
                      {

                        $filingType = $value; 
                      }
                      elseif ($key == 'form-name')
                      {
                        $title = $value; 
                      }
                      elseif ($key == 'items-desc')
                      {
                        $itemDescription = $value; 
                      }
                  }
              }

              $time = getAMPMTimeFromUTC($xmlFinalObject->entry[$i]->updated);

              $firstLink  = $xmlFinalObject->entry[$i]->link['href']; 
              $firstLinkResults = grabHTML('www.sec.gov', $firstLink); 



              $html2 = str_get_html($firstLinkResults);

              $tableRow2 = $html2->find('tr'); 

              $a2 = $tableRow2[1]->find('a');

              $href2 = 'https://www.sec.gov' . $a2[0]->href;

              $time = getAMPMTimeFromUTC($xmlFinalObject->entry[$i]->updated);
              for ($j = $yesterdayDays; $j >= 1; $j--)
              {
                  $datestamp = preg_replace('/(' .  get_trade_date($j) . ')/', '<span style="font-size: 16px; background-color:#0747a1; border: 1px solid red; color:white">$1</span>', $datestamp);
                  if (preg_match('/(' .  get_trade_date($j) . ')/', $datestamp))
                  {

                      if ($j == $yesterdayDays)
                      {
                          if (!timestampIsSafe($xmlFinalObject->entry[$i]->updated))
                          {
                              $recentNews = true;
                          }
                          $time = preg_replace('/AM/', '<span style="background-color: lightgreen">AM</span>', $time); 
                      }
                      else
                      {
                          $recentNews = true;
                          $time = preg_replace('/AM/', '<span style="background-color: red">AM</span>', $time); 
                      }
                      $time = preg_replace('/PM/', '<table><tr><td><span style="background-color: red; font-size: 25px;">PM CHECK NEWS</span></td></tr></table>', $time); 
                  }
              } // for ($j = $yesterdayDays; $j >= 1; $j--)

              if (preg_match('/(' .  get_today_trade_date() . ')/', $datestamp))
              {
                  $recentNews = true; 
              }

              $datestamp = preg_replace('/(' .  get_today_trade_date() . ')/', '<span style="font-size: 16px; background-color:black;  border: 1px solid red; color:white">$1</span>', $datestamp);

              $title = preg_replace('/registration statement/i', '<span style="font-size: 16px; background-color:red; color:black"><b>&nbsp;Registration statement - OFFERING COMING OUT, HOLD OFF</span></b>&nbsp;', $title);      
              $title = preg_replace('/statement of acquisition of beneficial ownership by individuals/i', '<span style="font-size: 16px; background-color:#00ff00; color:black"><b>&nbsp;Statement of acquisition of beneficial ownership by individuals - 18% early</span></b>&nbsp;', $title);
              $title = preg_replace('/statement of changes in beneficial ownership of securities/i', '<span style="font-size: 16px; background-color:#00ff00; color:black"><b>&nbsp;Statement of changes in beneficial ownership of securities - 18% early</span></b>&nbsp;', $title);
              $title = preg_replace('/inability to timely file form/i', '<span style="font-size: 16px; background-color:red; color:black"><b>&nbsp;inability to timely file form - 84%</span></b>&nbsp;', $title);
              $title = preg_replace('/exempt offering of securities/i', '<span style="font-size: 16px; background-color:red; color:black"><b>&nbsp;Exempt Offering of Securities - 20% and must be a fast drop</span></b>&nbsp;', $title);
              $title = preg_replace('/1\.01/i', '<span style="font-size: 16px; background-color:red; color:black"><b>&nbsp;1.01 - Entry into a Material Definitive Agreement - OFFERING COMING! BACK OFF!</span></b>&nbsp;', $title);
              $title = preg_replace('/Current report/i', '<span style="font-size: 16px; background-color:red; color:black"><b>&nbsp;Current report</span></b>&nbsp;', $title);
              $title = preg_replace('/7\.01/i', '<span style="font-size: 16px; background-color:lightblue; color:black"><b>&nbsp;Regulation FD Disclosure</span></b>&nbsp;<br>', $title);
              $title = preg_replace('/8\.01/i', '<span style="font-size: 16px; background-color:lightblue; color:black"><b>&nbsp;Other Events</span></b>&nbsp;<br>', $title);
              $title = preg_replace('/9\.01/i', '<span style="font-size: 16px; background-color:lightblue; color:black"><b>&nbsp;Financial Statemtnes and Exhibits</span></b>&nbsp;<br>', $title);
              $title = preg_replace('/general form for registration of securities/i', '<span style="font-size: 35px; background-color:red; color:black"><b>&nbsp;General form for registration of securities</span></b>&nbsp;', $title);

              if (preg_match('/registration/i', $title) || preg_match('/offering/i', $title))
              {
                  $registrationOffering = " - REGISTRATION";
              }

              $tableRows .=  "<tr style='border: 1px solid black !important; height: 20px;'><td style='border: 1px solid black !important'>" . $filingType . '</td><td style="border: 1px solid black !important"><a target="_blank" href ="' . $href2 . '">' . $title . ', '. $itemDescription .  '</a></td><td style="border: 1px solid black !important">' . $datestamp . "</td><td style='border: 1px solid black !important; font-size: 18px;'>" . $time . "</td></tr>"; 
              $secTableRowCount++; 
            }

      $returnHtml .= "<!DOCTYPE html>"; 
      $returnHtml .= "<html>";
      $returnHtml .= '<head>
        <link type="text/css" href="./css/main.css" rel="stylesheet"/>
      </head>';

      if ($noTimeFound == true)
      {
          $returnHtml .= "<title>Filing - " . $symbol . "(CHECK TIME)" . $registrationOffering . "</title>";  
      }
      elseif ($recentNews){
          $returnHtml .= "<title>Filing - " . $symbol . "</title>";  
      }
      else{
          $returnHtml .= "<title>Filing - " . $symbol . " (NONE)" . $registrationOffering . "</title>";   
      }
      
      $returnHtml .= "<body>"; 

      $returnHtml .= getStreetInsider($symbol, $yesterdayDays); 

      $returnHtml .= buildNewsNotes(); 

      $returnHtml .= "<table style='border: 1px solid black !important'>"; 

      $secMessage = " rowcount is " . $secTableRowCount . " "; 
      if ($secTableRowCount == 0)
      {
        $secMessage = "<span style='font-size: 50px; background-color: red'> - CHECK STREET INSIDER</span>"; 
      }

      $returnHtml .= "<tr><td>Type</td><td>Title" . $secMessage . "</td><td>Date</td><td>Time</td></tr>"; 
      $returnHtml .= $tableRows;
      $returnHtml .=  "</table>";

      $returnHtml .=  '<a style="font-size: 25px" target="_blank" href="http://ec2-54-210-42-143.compute-1.amazonaws.com/newslookup/scrape-street-insider.php?symbol=' . $symbol . '">Street Insider Scrape</a><br> 
        <a style="font-size: 25px" target="_blank" href="https://www.streetinsider.com/stock_lookup.php?LookUp=Get+Quote&q=' . $symbol . '">Street Insider Actual Page</a><br>
        <a style="font-size: 25px" target="_blank" href="https://www.nasdaq.com/symbol/' . $symbol . '/sec-filings">Nasdaq</a><br>
        <a style="font-size: 25px" target="_blank" href="https://www.sec.gov/cgi-bin/browse-edgar?CIK=' . $symbol . '&owner=include&action=getcompany">SEC Symbol</a><br>
        <a style="font-size: 25px" target="_blank" href="https://www.sec.gov/cgi-bin/browse-edgar?company=' . $secCompanyName . '&owner=include&action=getcompany">SEC Company Name</a><br>
        <a style="font-size: 25px" target="_blank" href=https://www.etrade.wallst.com/v1/stocks/snapshot/snapshot.asp?ChallengeUrl=https://idp.etrade.com/idp/SSO.saml2&reinitiate-handshake=0&prospectnavyear=2011&AuthnContext=prospect&env=PRD&symbol=' . $symbol . '&rsO=new&country=US>E*TRADE</a>
        <br>


        '; 

      $returnHtml .= getSectorIndustry(); 

      $returnHtml .=  "</body>";
      $returnHtml .=  "</html>";

/*
      $returnArray['html'] = $returnHtml; 
      $returnArray['links'] = $returnLinks;
      $returnArray['sector_industry'] = $returnSectorIndustry; 

      $returnFinalArray = json_encode($returnArray); 

      file_put_contents("returnFinalArray.txt", $returnFinalArray); 

      echo json_encode($returnFinalArray); 
*/
      echo $returnHtml;       

?>




