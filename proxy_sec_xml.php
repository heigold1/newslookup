<?php 

require_once("simple_html_dom.php"); 
error_reporting(1);

$symbol=$_GET['symbol'];
$secCompanyName = $_GET['secCompanyName'];
$secCompanyName = preg_replace('/ /', '+', $secCompanyName);

$yesterdayDays = 1;

fopen("cookies.txt", "w");

function buildNewsNotes()
{
    $newsNotes = '<ul style="font-family: arial;">
                      <li>Entry into a Material Definitive Agreement - STAY AWAY, SHARE PRICE COMING OUT</li>
                      </ul>
                      '; 
    return $newsNotes; 
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
    curl_setopt($ch, CURLOPT_STDERR,$f = fopen(__DIR__ . "/error.log", "w+"));

    $returnHTML = curl_exec($ch); 

    if($errno = curl_errno($ch)) {
      $error_message = curl_strerror($errno);
      echo "cURL error ({$errno}):\n {$error_message}";
    }   
    curl_close($ch);
    return $returnHTML; 

} // end of function grabHTML


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
                <br><div style="background-color: red"><span style="font-size: 55px">SEC WEBSITE IS DOWN</span></div>' . getSectorIndustry() . 
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
                <br><div style="background-color: red"><span style="font-size: 55px">NO MATCHING COMPANIES</span></div>' . getSectorIndustry() . 
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
                <br><div style="background-color: red"><span style="font-size: 55px">AMBIGUOUS</span></div>' . $result . getSectorIndustry() . 
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

          $xmlFinalString=simplexml_load_file($rssFullLink);

          $registrationOffering = "";

          for ($i = 0; $i < 5; $i++)
           { 
              $entryRowObject = $xmlFinalString->entry[0];
              $filingType = "";

              if (!$entryRowObject)
              {
                  break;
              }

              $entryContent = $xmlFinalString->entry[$i]->content; 

              $title = ""; 
              $datestamp = getDateFromUTC($xmlFinalString->entry[$i]->updated);
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

              $time = getAMPMTimeFromUTC($xmlFinalString->entry[$i]->updated);

              $firstLink  = $xmlFinalString->entry[$i]->link['href']; 
              $firstLinkResults = grabHTML('www.sec.gov', $firstLink); 



              $html2 = str_get_html($firstLinkResults);

              $tableRow2 = $html2->find('tr'); 

              $a2 = $tableRow2[1]->find('a');

              $href2 = 'https://www.sec.gov' . $a2[0]->href;

              $time = getAMPMTimeFromUTC($xmlFinalString->entry[$i]->updated);
              for ($j = $yesterdayDays; $j >= 1; $j--)
              {
                  $datestamp = preg_replace('/(' .  get_trade_date($j) . ')/', '<span style="font-size: 16px; background-color:#000080; color:white">$1</span>', $datestamp);
                  if (preg_match('/(' .  get_trade_date($j) . ')/', $datestamp))
                  {

                      if ($j == $yesterdayDays)
                      {
                          if (!timestampIsSafe($xmlFinalString->entry[$i]->updated))
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

      echo $returnHtml; 

?>




