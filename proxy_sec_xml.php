<?php 

require_once("simple_html_dom.php"); 
error_reporting(E_ALL);

$symbol=$_GET['symbol'];
$secCompanyName = $_GET['secCompanyName'];
$secCompanyName = preg_replace('/ /', '+', $secCompanyName);

$yesterdayDays = 3;

fopen("cookies.txt", "w");

function buildNewsNotes()
{
    $newsNotes = '<ul style="font-family: arial;">
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

function grabHTML($function_host_name, $url)
{

    $ch = curl_init();
    $header=array('GET /1575051 HTTP/1.1',
    "Host: $function_host_name",
    'Accept:text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
    'Accept-Language:en-US,en;q=0.8',
    'Cache-Control:max-age=0',
    'Connection:keep-alive',
    'User-Agent:brent@heigoldinvestments.com',
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
            if ($timeDiff < 30.00)
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
        $streetInsiderNews .= "<li style='font-size: 20px !important'>StreetInsider News</li>";

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
              $streetInsiderNews .=  "style='background-color: #FFFFFF; '"; 
            };
            
            // if the regular expression contains (.*) then we need to do it per title, to avoid greedy regular expressions

            $newsTitle = preg_replace('/ withdrawal(.*?)application/i', '<span style="font-size: 12px; background-color:red; color:black"><b> withdrawal $1 application (55%) </b></span> ', $newsTitle);
            $newsTitle = preg_replace('/nasdaq rejects(.*?)listing/i', '<span style="font-size: 12px; background-color:red; color:black"><b>Nasdaq rejects $1 listing</span> If delisting tomorrow 65%, if delisting days away then 50-55%</b>&nbsp;', $newsTitle);

            $streetInsiderNews .=  " ><a target='_blank' href='$feedItem->link'> " . $publicationDate . " " . $publicationTime . " - " . $newsTitle . "</a>";

            $previousNewsTitle = $currentNewsTitle; 
        } // looping through each news channel item 

        $streetInsiderNews .=  "</ul>";

        $streetInsiderNews = preg_replace('/(' .  get_yahoo_yesterday_trade_date() . ')/', '<span style="font-size: 12px; background-color:   #000080; color:white"> $1</span> ', $streetInsiderNews);
        $streetInsiderNews = preg_replace('/(' .  get_yahoo_todays_trade_date() . ')/', '<span style="font-size: 12px; background-color:  black; color:white"> $1</span> ', $streetInsiderNews);

        $streetInsiderNews .=  "<br>"; 

        // yellow highlighting for before yesterday
        for ($daysBack = 14; $daysBack > $yesterdayDays; $daysBack--)
        {
            $streetInsiderNews = preg_replace('/(' .  get_yahoo_trade_date($daysBack) . ')/', '<span style="font-size: 10px; background-color:yellow ; color:black">$1</span>', $streetInsiderNews);      
        }
        // blue highlighting for yesterday
        for ($daysBack = $yesterdayDays; $daysBack >= 1; $daysBack--)
        {
            $streetInsiderNews = preg_replace('/(' .  get_yahoo_trade_date($daysBack) . ')/', '<span style="font-size: 10px; background-color:#000080 ; color:white">$1</span>', $streetInsiderNews);
        }


          $streetInsiderNews = preg_replace('/(' .  get_yahoo_yesterday_trade_date() . ')/', '<span style="font-size: 12px; background-color:   #000080; color:white"> $1</span> ', $streetInsiderNews);
          $streetInsiderNews = preg_replace('/(' .  get_yahoo_todays_trade_date() . ')/', '<span style="font-size: 12px; background-color:  black; color:white"> $1</span> ', $streetInsiderNews);

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

      $url = "https://www.sec.gov/cgi-bin/browse-edgar?CIK=" . $symbol . "&owner=include&action=getcompany"; 
      $result = grabHTML('www.sec.gov', $url); 

      if (preg_match('/This page is temporarily unavailable/', $result))
      {
          echo '<!DOCTYPE html><html><title>Filing - ' . $symbol . ' (NOT FOUND)</title><body>
              <a style="font-size: 35px" target="_blank" href="https://www.nasdaq.com/symbol/' . $symbol . '/sec-filings">Nasdaq</a><br>
                  <a style="font-size: 35px" target="_blank" href="http://ec2-54-210-42-143.compute-1.amazonaws.com/newslookup/scrape-street-insider.php?symbol=' . $symbol . '">Street Insider Scrape</a><br>
                  <a style="font-size: 35px" target="_blank" href="https://www.streetinsider.com/stock_lookup.php?LookUp=Get+Quote&q=' . $symbol . '">Street Insider Actual Page</a><br>
                  <a style="font-size: 35px" target="_blank" href="https://seekingalpha.com/symbol/' . $symbol . '?s=' . $symbol . '">Seeking Alpha</a><br>
                  <a style="font-size: 35px" target="_blank" href=https://www.etrade.wallst.com/v1/stocks/snapshot/snapshot.asp?ChallengeUrl=https://idp.etrade.com/idp/SSO.saml2&reinitiate-handshake=0&prospectnavyear=2011&AuthnContext=prospect&env=PRD&symbol=' . $symbol . '&rsO=new&country=US>E*TRADE</a>
                <br><div style="background-color: red"><span style="font-size: 55px">SEC WEBSITE IS DOWN</span></div>' . getStreetInsider($symbol, $yesterdayDays) . getSectorIndustry() . 
                '</body></html>';  
          return; 
      }

      if (preg_match('/No matching Ticker Symbol/', $result))
      {
          $url = "https://www.sec.gov/cgi-bin/browse-edgar?company=" . $secCompanyName . "&owner=include&action=getcompany"; 
          $result = grabHTML('www.sec.gov', $url); 

          if (preg_match('/No matching companies/', $result))
          {
              echo '<!DOCTYPE html><html><title>Filing - ' . $symbol . ' (NOT FOUND)</title><body>
                  <a style="font-size: 35px" target="_blank" href="https://www.nasdaq.com/symbol/' . $symbol . '/sec-filings">Nasdaq</a><br>
                  <a style="font-size: 35px" target="_blank" href="http://ec2-54-210-42-143.compute-1.amazonaws.com/newslookup/scrape-street-insider.php?symbol=' . $symbol . '">Street Insider Scrape</a><br>
                  <a style="font-size: 35px" target="_blank" href="https://www.streetinsider.com/stock_lookup.php?LookUp=Get+Quote&q=' . $symbol . '">Street Insider Actual Page</a><br>
                  <a style="font-size: 35px" target="_blank" href="https://seekingalpha.com/symbol/' . $symbol . '?s=' . $symbol . '">Seeking Alpha</a><br>
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
                  <a style="font-size: 35px" target="_blank" href="http://ec2-54-210-42-143.compute-1.amazonaws.com/newslookup/scrape-street-insider.php?symbol=' . $symbol . '">Street Insider Scrape</a><br>
                  <a style="font-size: 35px" target="_blank" href="https://www.streetinsider.com/stock_lookup.php?LookUp=Get+Quote&q=' . $symbol . '">Street Insider Actual Page</a><br>
                  <a style="font-size: 35px" target="_blank" href="https://seekingalpha.com/symbol/' . $symbol . '?s=' . $symbol . '">Seeking Alpha</a>
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

      $returnHtml = "";
      $tableRows = "";
      $recentNews = false;
      $secTableRowCount = 0; 

          $xmlFinalString=grabHTML('www.sec.gov', $rssFullLink);
          $xmlFinalObject = produce_XML_object_tree($xmlFinalString); 

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
                  $datestamp = preg_replace('/(' .  get_trade_date($j) . ')/', '<span style="font-size: 16px; background-color:#000080; color:white">$1</span>', $datestamp);
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

              $datestamp = preg_replace('/(' .  get_today_trade_date() . ')/', '<span style="font-size: 16px; background-color:black; color:white">$1</span>', $datestamp);

              $title = preg_replace('/registration statement/i', '<span style="font-size: 16px; background-color:red; color:black"><b>&nbsp;Registration statement - OFFERING COMING OUT, HOLD OFF</span></b>&nbsp;', $title);      
              $title = preg_replace('/statement of acquisition of beneficial ownership by individuals/i', '<span style="font-size: 16px; background-color:red; color:black"><b>&nbsp;Statement of acquisition of beneficial ownership by individuals - 18% early</span></b>&nbsp;', $title);
              $title = preg_replace('/statement of changes in beneficial ownership of securities/i', '<span style="font-size: 16px; background-color:red; color:black"><b>&nbsp;Statement of changes in beneficial ownership of securities - 18% early</span></b>&nbsp;', $title);
              $title = preg_replace('/inability to timely file form/i', '<span style="font-size: 16px; background-color:red; color:black"><b>&nbsp;inability to timely file form - 84%</span></b>&nbsp;', $title);
              $title = preg_replace('/exempt offering of securities/i', '<span style="font-size: 16px; background-color:red; color:black"><b>&nbsp;Exempt Offering of Securities - 20% and must be a fast drop</span></b>&nbsp;', $title);

              if (preg_match('/registration/i', $title) || preg_match('/offering/i', $title))
              {
                  $registrationOffering = " - REGISTRATION";
              }

              $tableRows .=  "<tr style='border: 1px solid black !important; height: 20px;'><td style='border: 1px solid black !important'>" . $filingType . '</td><td style="border: 1px solid black !important"><a href ="' . $href2 . '">' . $title . ', '. $itemDescription .  '</a></td><td style="border: 1px solid black !important">' . $datestamp . "</td><td style='border: 1px solid black !important; font-size: 18px;'>" . $time . "</td></tr>"; 
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

      $returnHtml .=  '<a style="font-size: 35px" target="_blank" href="http://ec2-54-210-42-143.compute-1.amazonaws.com/newslookup/scrape-street-insider.php?symbol=' . $symbol . '">Street Insider Scrape</a><br> 
        <a style="font-size: 35px" target="_blank" href="https://www.streetinsider.com/stock_lookup.php?LookUp=Get+Quote&q=' . $symbol . '">Street Insider Actual Page</a><br>
        <a style="font-size: 35px" target="_blank" href="https://www.nasdaq.com/symbol/' . $symbol . '/sec-filings">Nasdaq</a><br>
        <a style="font-size: 35px" target="_blank" href="https://seekingalpha.com/symbol/' . $symbol . '?s=' . $symbol . '">Seeking Alpha</a><br>
        <a style="font-size: 35px" target="_blank" href=https://www.etrade.wallst.com/v1/stocks/snapshot/snapshot.asp?ChallengeUrl=https://idp.etrade.com/idp/SSO.saml2&reinitiate-handshake=0&prospectnavyear=2011&AuthnContext=prospect&env=PRD&symbol=' . $symbol . '&rsO=new&country=US>E*TRADE</a>
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




