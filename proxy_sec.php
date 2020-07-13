<?php 

require_once("simple_html_dom.php"); 
error_reporting(1);

$symbol=$_GET['symbol'];
$secCompanyName = $_GET['secCompanyName'];
$secCompanyName = preg_replace('/ /', '+', $secCompanyName);

$yesterdayDays = 1; 

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

function timestampIsSafe($timestamp){
    if (((int) date('H', $timestamp)) < 12){
        return true;
    }
    else
    {
        return false; 
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
/*
        echo "<title>Filing - " . $symbol . " (No match)</title><h1>No matching ticker symbol</h1>";
        return; 
*/
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

      $returnHtml = "";
      $tableRows = "";
      $recentNews = false;

          $tableRow1 = $html->find('.tableFile2 tbody tr'); 

          $recentNews = false; 

          $registrationOffering = "";

            for ($i = 1; $i < 6; $i++)
            { 
              $row = str_get_html($tableRow1[$i]);

              if (!$row)
              {
                  break;
              }

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

//echo "tableRow2 is " . $tableRow2[1] . "<br>";


// DO NOT DELETE THE NEXT TWO COMMENTED LINES.  YOU MAY HAVE TO COME BACK TO THIS
//                  $td2nd = $tableRow2[1]->find('td'); 
//                  $a2 = $td2nd[2]->find('a');
                  $a2 = $tableRow2[1]->find('a');


// echo "** a2[0]->href is " . $a2[0]->href . "<br><br>"; 
//die();

                  $href2 = 'https://www.sec.gov' . $a2[0]->href;
                  $time = "";

                  for ($j = $yesterdayDays; $j >= 1; $j--)
                  {
                      $td3 = preg_replace('/(' .  get_trade_date($j) . ')/', '<span style="font-size: 16px; background-color:#000080; color:white">$1</span>', $td3);
                      if (preg_match('/(' .  get_trade_date($j) . ')/', $td3))
                      {
                          $timestamp = getURLTimestamp($href2);
                          if ($timestamp == "No timestamp")
                          {
                              $noTimeFound = true; 
                          }
                          else
                          {
                              // 10800 is adding 3 hours offset 
                              $time = date("g:i A", $timestamp + 10800);
                              if ($time == '')
                              {
                                  $noTimeFound = true; 
                              }
                          }

                          if ($j == $yesterdayDays)
                          {
                              if (!timestampIsSafe($timestamp) || !timestampIsSafeDateColumn($td3))
                              {
                                  $recentNews = true;

                                  // if there happens to be a 24-hour timestamp in the date field then highlight the HH: section red if it's > 12
                                  if (!timestampIsSafeDateColumn($td3))
                                  {
                                    $td3 = preg_replace('/ (\d{2}):/i', ' <span style="font-size: 15px; background-color:red; color:black">$1</span>:', $td3);
                                  }
                                  else
                                  {
                                    $td3 = preg_replace('/ (\d{2}):/i', ' <span style="font-size: 15px; background-color:#00FF00; color:black">$1</span>:', $td3);
                                  }
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
                  if (preg_match('/(' .  get_today_trade_date() . ')/', $td3))
                  {
                      $timestamp = getURLTimestamp($href2);
                      if ($timestamp == "No timestamp")
                      {
                          $noTimeFound = true; 
                      }
                      else
                      {
                          // 10800 is adding 3 hours offset 
                          $time = date("g:i A", $timestamp + 10800);
                          if ($time == '')
                          {
                              $noTimeFound = true; 
                          }
                      }
                  }

                  if (preg_match('/(' .  get_today_trade_date() . ')/', $td3))
                  {
                      $recentNews = true; 
                  }

                  $td3 = preg_replace('/(' .  get_today_trade_date() . ')/', '<span style="font-size: 16px; background-color:black; color:white">$1</span>', $td3);

                  $td2 = preg_replace('/registration statement/i', '<span style="font-size: 16px; background-color:red; color:black"><b>&nbsp;Registration statement - OFFERING COMING OUT, HOLD OFF</span></b>&nbsp;', $td2);      
                  $td2 = preg_replace('/statement of acquisition of beneficial ownership/i', '<span style="font-size: 16px; background-color:red; color:black"><b>&nbsp;Statement of acquisition of beneficial ownership - 35%, penny 39%</span></b>&nbsp;', $td2);
                  $td2 = preg_replace('/inability to timely file form/i', '<span style="font-size: 16px; background-color:red; color:black"><b>&nbsp;inability to timely file form - 84%</span></b>&nbsp;', $td2);
                  


                  if (preg_match('/registration/i', $td2) || preg_match('/offering/i', $td2))
                  {
                      $registrationOffering = " - REGISTRATION";
                  }

              $tableRows .=  "<tr>" . $td0 . '<td><a href ="' . $href2 . '">' . $td2 . '</a></td>' . $td3 . "<td>" . $time . "</td></tr>"; 
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
      $returnHtml .=  "</body>";
      $returnHtml .=  "</html>";

      echo $returnHtml; 

?>