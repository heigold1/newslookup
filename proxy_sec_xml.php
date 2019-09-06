<?php 

require_once("simple_html_dom.php"); 
error_reporting(1);

$symbol=$_GET['symbol'];
$secCompanyName = $_GET['secCompanyName'];
$secCompanyName = preg_replace('/ /', '+', $secCompanyName);

$yesterdayDays = 3; 

fopen("cookies.txt", "w");


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
$noTimeFound = false;

      // try https://www.nasdaq.com/symbol/staf/sec-filings

      $url = "https://www.sec.gov/cgi-bin/browse-edgar?CIK=" . $symbol . "&owner=include&action=getcompany"; 
      $result = grabHTML('www.sec.gov', $url); 

      if (preg_match('/No matching Ticker Symbol/', $result))
      {
          $url = "https://www.sec.gov/cgi-bin/browse-edgar?company=" . $secCompanyName . "&owner=include&action=getcompany"; 
          $result = grabHTML('www.sec.gov', $url); 

          if (preg_match('/No matching companies/', $result))
          {
              echo '<!DOCTYPE html><html><title>Filing - ' . $symbol . ' (NOT FOUND)</title><body>
                  <a style="font-size: 35px" target="_blank" href="https://www.nasdaq.com/symbol/' . $symbol . '/sec-filings">Nasdaq</a><br>
                  <a style="font-size: 35px" target="_blank" href="http://ec2-54-210-42-143.compute-1.amazonaws.com/newslookup/scrape-street-insider.php?symbol=' . $symbol . '">Street Insider Scrape</a><br>
                  <a style="font-size: 35px" target="_blank" href="https://www.streetinsider.com/stock_lookup.php?LookUp=Get+Quote&q=MSFT' . $symbol . '">Street Insider Actual Page</a>
                <br>
                <br>' . $result . 
                '</body></html>';  
              return; 
          }



          if (preg_match('/Companies with names matching/', $result))
          {
              echo '<!DOCTYPE html><html><title>Filing - ' . $symbol . ' (AMBIGUOUS)</title><body>
                  <a style="font-size: 35px" target="_blank" href="https://www.nasdaq.com/symbol/' . $symbol . '/sec-filings">Nasdaq</a><br>
                  <a style="font-size: 35px" target="_blank" href="http://ec2-54-210-42-143.compute-1.amazonaws.com/newslookup/scrape-street-insider.php?symbol=' . $symbol . '">Street Insider Scrape</a><br>
                  <a style="font-size: 35px" target="_blank" href="https://www.streetinsider.com/stock_lookup.php?LookUp=Get+Quote&q=MSFT' . $symbol . '">Street Insider Actual Page</a>
                <br>
                <br>' . $result . 
                '</body></html>';  
              return; 
          }
      }

      $html = str_get_html($result);

      $rssTableRow = $html->find(' form table tbody tr'); 

      $rssLink = $rssTableRow[1]->find('a');

      $rssFullLink = "https://www.sec.gov" . $rssLink[0]->href; 

      $returnHtml = "";
      $tableRows = "";
      $recentNews = false;

          $xmlFinalString=simplexml_load_file($rssFullLink);

          $recentNews = false; 

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
                      $time = preg_replace('/PM/', '<span style="background-color: red">PM</span>', $time); 
                  }
              } // for ($j = $yesterdayDays; $j >= 1; $j--)

              if (preg_match('/(' .  get_today_trade_date() . ')/', $datestamp))
              {
                  $recentNews = true; 
              }

              $datestamp = preg_replace('/(' .  get_today_trade_date() . ')/', '<span style="font-size: 16px; background-color:black; color:white">$1</span>', $datestamp);

              $title = preg_replace('/registration statement/i', '<span style="font-size: 16px; background-color:red; color:black"><b>&nbsp;Registration statement - OFFERING COMING OUT, HOLD OFF</span></b>&nbsp;', $title);      
              $title = preg_replace('/Statement of acquisition of beneficial ownership/i', '<span style="font-size: 16px; background-color:red; color:black"><b>&nbsp;Statement of acquisition of beneficial ownership - 35%, penny 39%</span></b>&nbsp;', $title);
              $title = preg_replace('/inability to timely file form/i', '<span style="font-size: 16px; background-color:red; color:black"><b>&nbsp;inability to timely file form - 84%</span></b>&nbsp;', $title);

              if (preg_match('/registration/i', $title) || preg_match('/offering/i', $title))
              {
                  $registrationOffering = " - REGISTRATION";
              }

              $tableRows .=  "<tr><td>" . $filingType . '</td><td><a href ="' . $href2 . '">' . $title . ', '. $itemDescription .  '</a></td><td>' . $datestamp . "</td><td>" . $time . "</td></tr>"; 
            }


      $returnHtml .= "<!DOCTYPE html>"; 
      $returnHtml .= "<html>";
      $returnHtml .= "<head>";

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
      $returnHtml .= "<table class='striped' border = 1>"; 
      $returnHtml .= $tableRows;
      $returnHtml .=  "</table>";

      $returnHtml .=  '<a style="font-size: 35px" target="_blank" href="http://ec2-54-210-42-143.compute-1.amazonaws.com/newslookup/scrape-street-insider.php?symbol=' . $symbol . '">Street Insider Scrape</a><br> 
        <a style="font-size: 35px" target="_blank" href="https://www.streetinsider.com/stock_lookup.php?LookUp=Get+Quote&q=' . $symbol . '">Street Insider Actual Page</a><br>
        <a style="font-size: 35px" target="_blank" href="https://www.nasdaq.com/symbol/' . $symbol . '/sec-filings">Nasdaq</a><br>'; 

      $returnHtml .=  "</body>";
      $returnHtml .=  "</html>";

      echo $returnHtml; 

?>