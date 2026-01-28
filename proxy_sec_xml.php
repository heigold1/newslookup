<?php 

require_once("simple_html_dom.php"); 
require_once("regex-street-insider.php"); 

error_reporting(E_ALL);


$symbol=$_GET['symbol'];
$originalSymbol=$_GET['originalSymbol']; 
$secCompanyName = $_GET['secCompanyName'];
$secCompanyName = preg_replace('/ /', '+', $secCompanyName);
$secCompanyName = preg_replace("/<.*?>/", "", $secCompanyName); 
$cikNumber = $_GET['cikNumber']; 
$checkSec = $_GET['checkSec']; 

$yesterdayDays = 1;

fopen("cookies.txt", "w");

function buildNewsNotes($companyName)
{
    $newsNotes = '<ul style="font-family: arial;">
                      <li style="background-color: #00ff00;">SEC - Company name is ' . $companyName . '</li>
                      <li>Entry into (stay away), termination of (40%) Material Definitive Agreement.</li>
                      <li>If granted extension for listing - that is no news.</li>
                      </ul><br><br>
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

    curl_setopt($ch, CURLOPT_VERBOSE, false);
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


function getIndustryCount($symbol)
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

    $result = $mysqli->query("SELECT industry, count(industry) as count FROM sector WHERE industry = (
        SELECT industry FROM sector WHERE symbol = '" . $symbol . "')");

    $returnValue = $result->fetch_assoc(); 

    $returnArray["count"] = $returnValue["count"];
    $returnArray["industry"] = $returnValue["industry"]; 

    return $returnArray; 

}  // end of getIndustryCount 




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
            error_log("Error: " . mysqli_error($link));
        }
    } 
    catch (mysqli_sql_exception $e) 
    {
        error_log("Error when selecting from database is " . $e->errorMessage() . "<br>"); 
    } 

    $rowCount = mysqli_num_rows($query); 
    $currentTimeInt = strtotime('- 8 hours'); 
    $currentTime = date('Y-m-d H:i:s', $currentTimeInt); 

    // If it's been over 25 minutes since we last scraped, or we haven't scraped it yet (i.e. no rows in the database) 
    // then we re-scrape (i.e. $reScrape = true) 
    if ($rowCount >= 1)
    {
        while ($myRow = mysqli_fetch_assoc($query))
        {
            $lastUpdatedInt = strtotime($myRow['lastUpdated'] . "- 8 hours");
            $lastUpdated = date('Y-m-d H:i:s', $lastUpdatedInt); 
            $timeDiff = ($currentTimeInt - $lastUpdatedInt)/60; 
            // If it's newer than 10 minutes then just use what's stored in the database, because
            // the StreetInsider bot hasn't expired. 
            if ($timeDiff < 15.00)
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

        $streetInsiderLink = $xmlFinalObject->channel->item{0}->link;
        $streetInsiderTitle = $xmlFinalObject->channel->item{0}->title;

        $streetInsiderLink = mysqli_real_escape_string($link, $streetInsiderLink);
        $streetInsiderTitle = mysqli_real_escape_string($link, $streetInsiderTitle);

        $streetInsiderNews = "<ul class='newsSide'>";
        $streetInsiderNews .= "<li style='font-size: 20px !important; background-color: #00ff00;'>
<a style='font-size: 25px' target='_blank' href='https://www.streetinsider.com/stock_lookup.php?LookUp=Get+Quote&q=" . $symbol . "'>StreetInsider News</a> -- <a style='font-size: 25px' target='_blank' href='https://newsquantified.com/" . $symbol . "'>News Quantified</a></li>";


        $classActionAdded = false;
        $j = 0;

        $previousNewsTitle = "";
        $currentNewsTitle = ""; 

        foreach ($xmlFinalObject->channel->item as $feedItem) {
            $j++;

            // Convert time from GMT to  AM/PM New York
            // 14400 is 4 hours X 60 seconds/minute X 60 minutes/hour
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

            if (preg_match('/^form.*?4/i', $newsTitle) ||
               preg_match('/^form.*?sc.*?13/i', $newsTitle)
                )
            {
                continue; 
            }

            $streetInsiderNews .= "<li "; 

            // red/green highlighting for yesterday/today
            for ($i = $yesterdayDays; $i >= 1; $i--)
            {
                if (preg_match('/(' .  get_yahoo_trade_date($i) . ')/', $publicationDate))
                {
                    $publicationTime = preg_replace('/PM/', '<span style="background-color: red; font-size: 18px; ">PM</span>', $publicationTime); 
                    if ($i == $yesterdayDays) 
                    {
                        $publicationTime = preg_replace('/AM/', '<span style="background-color: #00ff00; font-size: 18px; ">AM</span>', $publicationTime); 
                  
                    }
                    else
                    {
                        $publicationTime = preg_replace('/AM/', '<span style="background-color: red; font-size: 18px; ">AM</span>', $publicationTime); 
                    }  
                }
            }

            if ($j % 2 == 1)
            {
              $streetInsiderNews .=  "style='background-color: #ebd8bd; '"; 
            };
            
            // if the regular expression contains (.*) then we need to do it per title, to avoid greedy regular expressions

            $newsTitle = preg_replace('/ withdrawal(.*?)application/i', '<span style="font-size: 12px; background-color:red; color:black"><b> withdrawal $1 application (55%) </b></span> ', $newsTitle);
            $newsTitle = preg_replace('/nasdaq rejects(.*?)listing/i', '<span style="font-size: 12px; background-color:red; color:black"><b>Nasdaq rejects $1 listing</span> </b>&nbsp;', $newsTitle);
            $newsTitle = preg_replace('/ announces(.*?)offering/i', '<span style="font-size: 35px; background-color:red; color:black"><b> ANNOUNCES $1 OFFERING </b></span> ', $newsTitle);

            $streetInsiderNews .=  " ><a target='_blank' href='$feedItem->link'> " . $publicationDate . " " . $publicationTime . " - <br>" . $newsTitle . "</a></li>";

            $previousNewsTitle = $currentNewsTitle; 
        } // looping through each news channel item 

        $streetInsiderNews .=  "</ul>";

        // light yellow highlighting for - from two weeks ago to a week ago.
        // light yellow is #FFFFC5 

        for ($daysBack = 14; $daysBack >= 7; $daysBack--)
        {
            $streetInsiderNews = preg_replace('/(' .  get_yahoo_trade_date($daysBack) . ')/', '<span style="font-size: 12px; background-color:yellow; color:black">$1</span>', $streetInsiderNews);      
        }

        // yellow highlighting for before yesterday
        for ($daysBack = 6; $daysBack > $yesterdayDays; $daysBack--)
        {
            $streetInsiderNews = preg_replace('/(' .  get_yahoo_trade_date($daysBack) . ')/', '<span style="font-size: 12px; background-color:yellow ; color:black">$1</span>', $streetInsiderNews);      
        }

        // blue highlighting for yesterday
        for ($daysBack = $yesterdayDays; $daysBack >= 1; $daysBack--)
        {
            $streetInsiderNews = preg_replace('/(' .  get_yahoo_trade_date($daysBack) . ')/', '<span style="font-size: 12px; background-color:#0747a1 ; color:white">$1</span>', $streetInsiderNews);
        }

        $streetInsiderNews .= "yesterdayDays is " . $yesterdayDays . "<br>"; 

        $streetInsiderNews = regexStreetInsider($streetInsiderNews); 
                             
        try 
        {
            $link->set_charset("utf8");

            $sqlStatement = "REPLACE INTO streetinsider (symbol, htmltext, lastLink, lastTitle) VALUES ('" . $symbol . "', '" . mysqli_real_escape_string($link, $streetInsiderNews) . "', '" . $streetInsiderLink . "', '" . $streetInsiderTitle . "')"; 

            $query = mysqli_query($link, $sqlStatement);
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




function getNewsQuantified($symbol, $yesterdayDays)
{

    $streetInsiderNews = "<ul class='newsSide'>";
    $streetInsiderNews .= "<li style='font-size: 20px !important; background-color: #00ff00;'><a style='font-size: 25px' target='_blank' href='https://www.streetinsider.com/stock_lookup.php?LookUp=Get+Quote&q=" . $symbol . "'>StreetInsider News</a> -- <a style='font-size: 25px' target='_blank' href='https://newsquantified.com/" . $symbol . "'>News Quantified</a></li>";

    $command = escapeshellcmd('python3 ./pythonscrape/scrape-news-quantified.py ' . $symbol . ' ' . $yesterdayDays);
    $streetInsiderNews .= shell_exec($command);
    $streetInsiderNews .=  "</ul>";

        $streetInsiderNews = preg_replace('/(' .  get_yahoo_yesterday_trade_date() . ')/', '<span style="font-size: 12px; background-color:   #0747a1; color:white; border: 1px solid red; "> $1</span> ', $streetInsiderNews);
        $streetInsiderNews = preg_replace('/(' .  get_yahoo_todays_trade_date() . ')/', '<span style="font-size: 12px; background-color:  black; color:white; border: 1px solid red; "> $1</span> ', $streetInsiderNews);

        $streetInsiderNews .=  "<br>"; 



        // light yellow highlighting for - from two weeks ago to a week ago.
        // light yellow is #fffdaf 
        for ($daysBack = 14; $daysBack > 6; $daysBack--)
        {
            $streetInsiderNews = preg_replace('/(' .  get_yahoo_trade_date($daysBack) . ')/', '<span style="font-size: 12px; background-color:yellow; color:black">$1</span>', $streetInsiderNews);      
        }

        // yellow highlighting for before yesterday
        for ($daysBack = 5; $daysBack > $yesterdayDays; $daysBack--)
        {
            $streetInsiderNews = preg_replace('/(' .  get_yahoo_trade_date($daysBack) . ')/', '<span style="font-size: 12px; background-color:yellow ; color:black">$1</span>', $streetInsiderNews);      
        }

        // blue highlighting for yesterday
        for ($daysBack = $yesterdayDays; $daysBack >= 1; $daysBack--)
        {
            $streetInsiderNews = preg_replace('/(' .  get_yahoo_trade_date($daysBack) . ')/', '<span style="font-size: 12px; background-color:#0747a1 ; color:white">$1</span>', $streetInsiderNews);
        }

        $streetInsiderNews .= "yesterdayDays is " . $yesterdayDays . "<br>"; 


        $streetInsiderNews = regexStreetInsider($streetInsiderNews); 




        return "<div style='height: 250px; width: 100%; overflow-y:auto; border-style: double !important; border-color: black !important; color: black;'>" . $streetInsiderNews . "</div>"; 

}


function getSecFilings($symbol, $originalSymbol, $yesterdayDays, $cikNumber, $secCompanyName)
{

     $command = escapeshellcmd('python3 ./pythonscrape/scrape-sec-gov-full.py ' . $symbol . " " . $originalSymbol . " " . $yesterdayDays . " " . $cikNumber . " " . $secCompanyName);

//      $command = escapeshellcmd('python3 ./pythonscrape/scrape-sec-gov.py ' . $cik);

      $secData = shell_exec($command);
      $tableData = json_decode($secData, true);

      return $tableData['message'];  

} // end of getSecFilings

      $returnHtml = "<!DOCTYPE html>"; 
      $returnHtml .= "<html>";
      $returnHtml .= '<head>
        <link type="text/css" href="./css/main.css" rel="stylesheet"/>
      </head>';

      $returnHtml .= "<title>Filing - " . $symbol . "</title>";  
      
      $returnHtml .= "<body>"; 

      $returnHtml .= getStreetInsider($symbol, $yesterdayDays); 


//       $returnHtml .= getNewsQuantified($symbol, $yesterdayDays); 




      $returnHtml .= buildNewsNotes($secCompanyName); 


      $returnArray['dividendCheckDate'] = 0;
      if (preg_match('/dividend/i', $returnHtml))
      {
        $returnArray['dividendCheckDate'] = 1;
      }
      $returnArray['checkReportDate'] = 0;
      if (preg_match('/to report/i', $returnHtml))
      {
        $returnArray['checkReportDate'] = 1;
      }
      $returnArray['checkAnnouncementDate'] = 0;
      if (preg_match('/to announce/i', $returnHtml))
      {
        $returnArray['checkAnnouncementtDate'] = 1;
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


      if (intval($checkSec) == 1) 
      {
        $returnHtml .= getSecFilings($symbol, $originalSymbol, $yesterdayDays, $cikNumber, $secCompanyName); 
      }
      else
      {
        $returnHtml .= "<table border='1' style='font-size: 35px; border: 1px solid black !important; height: 40px;'><tr><td ></td><td>NOT CHECKING SEC</td><td></td><td></td></tr></table><br>"; 
      }

      $returnHtml .=  '<a style="font-size: 25px" target="_blank" href="http://ec2-54-210-42-143.compute-1.amazonaws.com/newslookup/scrape-street-insider.php?symbol=' . $symbol . '">Street Insider Scrape</a><br> 
        <a style="font-size: 25px" target="_blank" href="https://www.streetinsider.com/stock_lookup.php?LookUp=Get+Quote&q=' . $symbol . '">Street Insider Actual Page</a><br>
        <a style="font-size: 25px" target="_blank" href="https://seekingalpha.com/' . $symbol . '/sec-filings">Seeking Alpha SEC</a><br>
        <a style="font-size: 25px" target="_blank" href="https://www.nasdaq.com/symbol/' . $symbol . '/sec-filings">Nasdaq</a><br>
        <a style="font-size: 25px" target="_blank" href="https://www.nasdaq.com/symbol/' . $symbol . '/">Nasdaq Company</a><br>
        <a style="font-size: 25px" target="_blank" href="https://www.sec.gov/cgi-bin/browse-edgar?CIK=' . $symbol . '&owner=include&action=getcompany">SEC Symbol</a><br>
        <a style="font-size: 25px" target="_blank" href="https://www.sec.gov/cgi-bin/browse-edgar?company=' . $secCompanyName . '&owner=include&action=getcompany">SEC Company Name</a><br>
        <a style="font-size: 25px" target="_blank" href=https://www.etrade.wallst.com/v1/stocks/snapshot/snapshot.asp?ChallengeUrl=https://idp.etrade.com/idp/SSO.saml2&reinitiate-handshake=0&prospectnavyear=2011&AuthnContext=prospect&env=PRD&symbol=' . $symbol . '&rsO=new&country=US>E*TRADE</a>
        <br>


        '; 

      $returnHtml .= getSectorIndustry(); 

      $returnHtml .=  "</body>";
      $returnHtml .=  "</html>";


      $returnArray['html'] = $returnHtml;

      $industryCount = getIndustryCount($symbol); 

      $returnArray['industryCount'] = $industryCount["count"]; 
      $returnArray['industry'] = $industryCount["industry"]; 

        echo json_encode($returnArray);       

?>




