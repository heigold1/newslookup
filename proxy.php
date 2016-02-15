<?php 

/* 
oauth_consumer_key: 874c996f1f6ecaa46c65abb115da9912
consumer_secret: 886529f1c9d06729e97b6f511a89b4df
*/

include 'config.php';
include 'C:\Xampp\php\Common\Common.php';

require_once("simple_html_dom.php"); 
error_reporting(0);

$filename = "test.txt";
$file = fopen( $filename, "w" );
if( $file == false )
{
   echo ( "Error in opening new file" );
   exit();
}

fwrite( $file, "testwrite");   

// header('Content-type: text/html');
$symbol=$_GET['symbol'];
$host_name=$_GET['host_name'];
$which_website=$_GET['which_website'];
$stockOrFund=$_GET['stockOrFund']; 
$google_keyword_string = $_GET['google_keyword_string'];

// $html_return=file_get_contents($url);

// fwrite( $file, "stockOrFund is " . $stockOrFund);

// fwrite( $file, "which_website is  " . $which_website);

fopen("cookies.txt", "w");

function testFunction()
{
  fwrite( $file, "test function");   
}


function get_yahoo_friday_trade_date()
{
    $friday_yahoo_trade_date = "";

    $friday_yahoo_trade_week_day = date('l', strtotime("-3 days"));
    $friday_yahoo_trade_week_day = mb_substr($friday_yahoo_trade_week_day, 0, 3);
    $friday_yahoo_trade_month_day = date(', M d', strtotime("-3 days"));
    $friday_yahoo_trade_date = $friday_yahoo_trade_week_day . $friday_yahoo_trade_month_day;
 
    $friday_yahoo_trade_date = preg_replace('/0([1-9])/', '$1', $friday_yahoo_trade_date);
 
    return $friday_yahoo_trade_date;
}

function get_yahoo_saturday_trade_date()
{
    $saturday_yahoo_trade_date = "";

    $saturday_yahoo_trade_week_day = date('l', strtotime("-2 days"));
    $saturday_yahoo_trade_week_day = mb_substr($saturday_yahoo_trade_week_day, 0, 3);
    $saturday_yahoo_trade_month_day = date(', M d', strtotime("-2 days"));
    $saturday_yahoo_trade_date = $saturday_yahoo_trade_week_day . $saturday_yahoo_trade_month_day;
 
    $saturday_yahoo_trade_date = preg_replace('/0([1-9])/', '$1', $saturday_yahoo_trade_date);

    return $saturday_yahoo_trade_date;
}

function get_yahoo_yesterday_trade_date()
{
    $yesterday_yahoo_trade_date = "";

    $yesterday_yahoo_trade_week_day = date('l', strtotime("-1 days"));
    $yesterday_yahoo_trade_week_day = mb_substr($yesterday_yahoo_trade_week_day, 0, 3);
    $yesterday_yahoo_trade_month_day = date(', M d', strtotime("-1 days"));
    $yesterday_yahoo_trade_date = $yesterday_yahoo_trade_week_day . $yesterday_yahoo_trade_month_day;
 
    $yesterday_yahoo_trade_date = preg_replace('/0([1-9])/', '$1', $yesterday_yahoo_trade_date);

    return $yesterday_yahoo_trade_date;
}

function get_marketwatch_friday_trade_date()
{
    $friday_marketwatch_trade_date = "";
    $friday_marketwatch_trade_date = date('M. d, Y',strtotime("-3 days"));

    $friday_marketwatch_trade_date = preg_replace('/0([1-9]),/', '$1,', $friday_marketwatch_trade_date);

    return $friday_marketwatch_trade_date;
}

function get_marketwatch_saturday_trade_date()
{
    $saturday_marketwatch_trade_date = "";
    $saturday_marketwatch_trade_date = date('M. d, Y',strtotime("-2 days"));

    $saturday_marketwatch_trade_date = preg_replace('/0([1-9]),/', '$1,', $saturday_marketwatch_trade_date);

    return $saturday_marketwatch_trade_date;
}

function get_marketwatch_yesterday_trade_date()
{
    $yesterday_marketwatch_trade_date = "";
    $yesterday_marketwatch_trade_date = date('M. d, Y',strtotime("-1 days"));

    $yesterday_marketwatch_trade_date = preg_replace('/0([1-9]),/', '$1,', $yesterday_marketwatch_trade_date);

    return $yesterday_marketwatch_trade_date;
}

function get_marketwatch_today_trade_date()
{
    $today_marketwatch_trade_date = "";
    $today_marketwatch_trade_date = date('M. d, Y');

    $today_marketwatch_trade_date = preg_replace('/0([1-9]),/', '$1,', $today_marketwatch_trade_date);

    return $today_marketwatch_trade_date;
}


function grabEtradeHTML($etrade_host_name, $url)
{

$filename = "test_grabEtradeHTML.txt";
$file_grabHTML = fopen( $filename, "w" );
if( $file_grabHTML == false )
{
   echo ( "Error in opening new file" );
   exit();
}

//fwrite( $file_grabHTML, " inside grabHTML, function_host_name is " . $function_host_name . "\r\n");
//fwrite( $file_grabHTML, "  url is " . $url . "\r\n");

$ch = curl_init();
$header=array('GET /1575051 HTTP/1.1',
"Host: www.etrade.wallst.com",
'Accept:text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
'Accept-Language:en-US,en;q=0.8',
// old 'Cookie: 1432%5F0=E57E25DD8D0A3F041AED1172A4473B69; GZIP=1; mmcore.tst=0.478; mmid=2017346849%7CAQAAAAqgcK3s3AsAAA%3D%3D; mmcore.pd=1639725045%7CAQAAAAoBQqBwrezcCx0RoEIBAL2yvfdgTdJIDXVzLmV0cmFkZS5jb20OAAAAvbK992BN0kgAAAAA/////wD//////////wANdXMuZXRyYWRlLmNvbQLcCwEAAAAAAAIAAAAAAP///////////////wAAAAAAAUU%3D; mmcore.srv=cg6.usw; s_cc=true; s_ppv=26; oda_uid=386242%3A%3A@@553b78bf9315fa054c5e2297; oda_bsid=386242%3A%3A@@*; oda_sid=386242%3A%3A@@553b78bffcbb01f5d14768e5; oda_lv=386242%3A%3A@@1429960895712; oda_cp=386242%3A%3A@@Quotes%7E%21@Snapshot%20-%20Equity%7E%21@1429960895712%7E%21@1; s_fid=1B78A634002A44E1-0A2A2F01C2B51A25; SC_pvp=www.etrade.wallst.com%3Av1%3Astocks%3Asnapshot%3Asnapshot.asp; s_sq=etrglobal%2Cetrwsod%3D%2526pid%253Dwww.etrade.wallst.com%25253Av1%25253Astocks%25253Asnapshot%25253Asnapshot.asp%2526pidt%253D1%2526oid%253Dhttps%25253A%25252F%25252Fwww.etrade.wallst.com%25252Fv1%25252Fstocks%25252Fnews%25252Fsearch_results.asp%25253Fsymbol%25253DMSFT_1%2526oidt%253D1%2526ot%253DA%2526oi%253D1;',
//'Cookie: GZIP=1; oda_bsid=386242%3A%3A@@*; 1432%5F0=7AF57B6C68E6B44D9F01302B067266B6; et_segment=; hb_referrer_flg=; mmcore.LBWay=; mmcore.tst=0.405; mmid=2140111876%7CAgAAAAqgcK3s3AsAAA%3D%3D; mmcore.pd=-389252185%7CAwAAAAoBQqBwrezcC8MKotQCAMTETlaeTdJIDXVzLmV0cmFkZS5jb20OAAAAvbK992BN0kgAAAAA/////wD//////////wANdXMuZXRyYWRlLmNvbQLcCwIAAAAAAAIAAAAAAP///////////////wAAAAAAAUU%3D; mmcore.srv=cg6.usw; s_cc=true; oda_uid=386242%3A%3A@@553b78bf9315fa054c5e2297; oda_sid=386242%3A%3A@@553bdd760a80da1ee260f226; oda_lv=386242%3A%3A@@1429987252445; oda_cp=386242%3A%3A@@Quotes%7E%21@Snapshot%20-%20Equity%7E%21@1429987252445%7E%21@0; s_ppv=26; s_fid=1B78A634002A44E1-0A2A2F01C2B51A25; SC_pvp=www.etrade.wallst.com%3Av1%3Astocks%3Asnapshot%3Asnapshot.asp;   s_sq=etrglobal%2Cetrwsod%3D%2526pid%253Dwww.etrade.wallst.com%25253Av1%25253Astocks%25253Asnapshot%25253Asnapshot.asp%2526pidt%253D1%2526oid%253Dhttps%25253A%25252F%25252Fwww.etrade.wallst.com%25252Fv1%25252Fstocks%25252Fnews%25252Fsearch_results.asp%25253Fsymbol%25253DMSFT%2526ot%253DA', // Saturday at 11:44 AM
  'Cookie: oda_bsid=386242%3A%3A@@*; 1432%5F0=C3D1E46964D0597C69BAB2D9F8A3652F', // Saturday at 3:09 PM - this worked

'Cache-Control:max-age=0',
'Connection:keep-alive',
'User-Agent:Mozilla/5.0 (Macintosh; Intel Mac OS X 10_8_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/27.0.1453.116 Safari/537.36',
);

curl_setopt($ch,CURLOPT_URL, $url);
curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,0);
curl_setopt( $ch, CURLOPT_COOKIESESSION, true );

curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

// curl_setopt ($ch, CURLOPT_POSTFIELDS, "username=michael@moderneveryday.com&password=modern1213");

curl_setopt($ch,CURLOPT_COOKIEFILE,'cookies.txt');
curl_setopt($ch,CURLOPT_COOKIEJAR,'cookies.txt');
curl_setopt($ch,CURLOPT_HTTPHEADER,$header);

$returnHTML = curl_exec($ch);

//fwrite( $file_grabHTML, "  returnHTML is " . $returnHTML . "\r\n");
fclose( $file_grabHTML);

return $returnHTML;

} // end of function grabEtradeHTML


function grabHTML($function_host_name, $url)
{

$filename = "test_grabHTML.txt";
$file_grabHTML = fopen( $filename, "w" );
if( $file_grabHTML == false )
{
   echo ( "Error in opening new file" );
   exit();
}

fwrite($file_grabHTML, "hello"); 
fwrite($file_grabHTML, "urls is " . $url . "\r\n"); 

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
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,0);
    curl_setopt( $ch, CURLOPT_COOKIESESSION, true );

//    curl_setopt($ch, CURLOPT_USERPWD, "heigold1:heimer27");
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

    curl_setopt($ch,CURLOPT_COOKIEFILE,'cookies.txt');
    curl_setopt($ch,CURLOPT_COOKIEJAR,'cookies.txt');
    curl_setopt($ch,CURLOPT_HTTPHEADER,$header);

    $returnHTML = curl_exec($ch); 

fwrite($file_grabHTML, "curl result is *" . $returnHTML . "*\r\n"); 
   
    return $returnHTML; 
    $result=curl_exec($ch);
    curl_close($ch);

} // end of function grabHTML

$ret = "";
$finalReturn = "";

if ($which_website == "marketwatch")
{
      $url="http://$host_name/investing/$stockOrFund/$symbol/news";
      $result = grabHTML($host_name, $url); 
      $html = str_get_html($result);

      if (($pos = strpos($html, "<html><head><title>Object moved") > -1) && 
          ($stockOrFund == "stock"))
          {
              $url="http://$host_name/investing/fund/$symbol/news";
              $result = grabHTML($host_name, $url); 
          }
      else if (($pos = strpos($html, "<html><head><title>Object moved") > -1) && 
          ($stockOrFund == "fund"))
          {
              $url="http://$host_name/investing/stock/$symbol/news";
              $result = grabHTML($host_name, $url); 
          }

      $result = str_replace ('href="/', 'href="http://www.marketwatch.com/', $result);  
      $result = str_replace ('heigoldinvestments.com', 'marketwatch.com', $result); 
      $result = str_replace ('localhost', 'www.marketwatch.com', $result); 
      $result = preg_replace('/ etf/i', '<span style="background-color:red; color:black"><b> &nbsp;ETF</b>&nbsp;</span>', $result);
      $result = preg_replace('/ etn/i', '<span style="background-color:red; color:black"><b> &nbsp;ETN</b>&nbsp;</span>', $result);
      $result = str_replace ('a href', 'a onclick="return openPage(this.href)" href', $result);  


      $html = str_get_html($result);

      $full_company_name = $html->find('#instrumentname'); 
      $ret = $html->find('#maincontent'); 

      $returnHTML = $ret[0]; 

      $returnHTML = str_replace('<span>', '<span style="font-weight: bold;">', $returnHTML); 

      $marketwatch_todays_date = date('l'); 
      if ($marketwatch_todays_date == "Monday")
      {
        $returnHTML = preg_replace('/(' .  get_marketwatch_friday_trade_date() . ')/', '<span style="font-size: 8px; background-color:black; color:white">$1</span>', $returnHTML);
        $returnHTML = preg_replace('/(' .  get_marketwatch_saturday_trade_date() . ')/', '<span style="font-size: 8px; background-color:black; color:white">$1</span>', $returnHTML);      
      }  

      $returnHTML = preg_replace('/(' .  get_marketwatch_yesterday_trade_date() . ')/', '<span style="font-size: 8px; background-color:black; color:white">$1</span>', $returnHTML);     
      $returnHTML = preg_replace('/(' .  get_marketwatch_today_trade_date() . ')/', '<span style="font-size: 8px; background-color:black; color:white">$1</span>', $returnHTML);           

      $returnHTML = preg_replace('/([0-9][0-9]:[0-9][0-9] [a-z]\.m\.  Today)|([0-9]:[0-9][0-9] [a-z]\.m\.  Today)/', '<span style="font-size: 8px; background-color:black; color:white">$1$2</span>', $returnHTML);
      $returnHTML = preg_replace('/([0-9][0-9] min ago)|([0-9] min ago)/', '<span style="font-size: 8px; background-color:black; color:white">$1$2</span>', $returnHTML);      

      $returnHTML = preg_replace('/ delisted|delisted /i', '<span style="font-size: 12px; background-color:red; color:black"><b> &nbsp;DELISTED</b>&nbsp;</span>', $returnHTML);
      $returnHTML = preg_replace('/ delisting|delisting /i', '<span style="font-size: 12px; background-color:red; color:black"><b> &nbsp;DELISTING</b>&nbsp;</span>', $returnHTML);
      $returnHTML = preg_replace('/ chapter 11|chapter 11 /i', '<span style="font-size: 12px; background-color:red; color:black"><b> &nbsp;CHAPTER 11</b>&nbsp;</span>', $returnHTML);
      $returnHTML = preg_replace('/ reverse split|reverse split /i', '<span style="font-size: 12px; background-color:red; color:black"><b> &nbsp;REVERSE SPLIT</b>&nbsp;</span>', $returnHTML);
      $returnHTML = preg_replace('/ reverse stock split|reverse stock split /i', '<span style="font-size: 12px; background-color:red; color:black"><b> &nbsp;REVERSE STOCK SPLIT</b>&nbsp;</span>', $returnHTML);
      $returnHTML = preg_replace('/ seeking alpha|seeking alpha /i', '<font size="3" style="font-size: 12px; background-color:#CCFF99; color: black; display: inline-block;">&nbsp;<b>Seeking Alpha</b>&nbsp;</font>', $returnHTML);      
      $returnHTML = preg_replace('/ downgrade|downgrade /i', '<span style="font-size: 12px; background-color:red; color:black"><b> &nbsp;DOWNGRADE</b>&nbsp;</span>', $returnHTML);
      $returnHTML = preg_replace('/ ex-dividend|ex-dividend /i', '<span style="font-size: 12px; background-color:red; color:black"><b> &nbsp;EX-DIVIDEND (chase at 25%)</b>&nbsp;</span>', $returnHTML);
      $returnHTML = preg_replace('/ sales miss|sales miss /i', '<span style="font-size: 12px; background-color:red; color:black"><b> &nbsp;SALES MISS (Chase at 65-70%)</b>&nbsp;</span>', $returnHTML);
      $returnHTML = preg_replace('/ miss sales|miss sales /i', '<span style="font-size: 12px; background-color:red; color:black"><b> &nbsp;MISS SALES (Chase at 65-70%)</b>&nbsp;</span>', $returnHTML);
      $returnHTML = preg_replace('/ disappointing sales|disappointing sales /i', '<span style="font-size: 12px; background-color:red; color:black"><b> &nbsp;DISAPPOINTINT SALES (Chase at 65-70%)</b>&nbsp;</span>', $returnHTML);      
      $returnHTML = preg_replace('/ sales results|sales results /i', '<span style="font-size: 12px; background-color:red; color:black"><b> &nbsp;SALES RESULTS (If bad, chase at 65-70%)</b>&nbsp;</span>', $returnHTML);
      $returnHTML = preg_replace('/ 8-k/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp;8-K</span> (if it involves litigation, then back off)</b>&nbsp;', $returnHTML);
      $returnHTML = preg_replace('/ accountant/i', '<span style="font-size: 12px; background-color:red; color:black"><b> &nbsp;accountant (if hiring new accountant, 35-40%)</b>&nbsp;</span>', $returnHTML);      
      $returnHTML = preg_replace('/ clinical trial/i', '<span style="font-size: 12px; background-color:red; color:black"><b> &nbsp;clinical trial</b>&nbsp;</span>', $returnHTML);            
      $returnHTML = preg_replace('/ recall/i', '<span style="font-size: 12px; background-color:red; color:black"><b> &nbsp;recall (bad, back off)</b>&nbsp;</span>', $returnHTML);                  
      $returnHTML = preg_replace('/ etf/i', '<span style="font-size: 12px; background-color:red; color:black"><b> &nbsp;ETF</b>&nbsp;</span>', $returnHTML);
      $returnHTML = preg_replace('/ etn/i', '<span style="font-size: 12px; background-color:red; color:black"><b> &nbsp;ETN</b>&nbsp;</span>', $returnHTML);
      $returnHTML = preg_replace('/[ \']disruption[ \']/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp;disruption&nbsp;</span> (chase at 52%)</b>', $returnHTML);
      $returnHTML = preg_replace('/ abandon/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp;abandon&nbsp;</span> (65-70%)</b>', $returnHTML);
      $returnHTML = preg_replace('/ bankrupt/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp;bankrupt&nbsp;</span>(65%)</b>', $returnHTML);      
      $returnHTML = preg_replace('/ terminate| terminates| terminated| termination/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp;terminate&nbsp;</span> (65%) </b>', $returnHTML);            
      $returnHTML = preg_replace('/ drug/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp;drug&nbsp;</span></b>', $returnHTML);            
      $returnHTML = preg_replace('/ guidance/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp;guidance</span> (35-40% early)</b>&nbsp;', $returnHTML);
      $returnHTML = preg_replace('/ update/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp;update</span> (Jay backs off)</b>&nbsp;', $returnHTML);
      $returnHTML = preg_replace('/ susbended/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp;suspended</span> (65-70%)</b>&nbsp;', $returnHTML);      
      $returnHTML = preg_replace('/ fraud/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp;fraud</span></b>&nbsp;', $returnHTML);      
      $returnHTML = preg_replace('/ dividend/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp;dividend</span> (if cut, 65-70%)</b>&nbsp;', $returnHTML);            
      $returnHTML = preg_replace('/ strategic alternatives/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp;strategic alternatives</span>**BANKRUPTCY**</b>&nbsp;', $returnHTML);                  
      $returnHTML = preg_replace('/ unpatentable/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp;unpatentable</span> (65%)</b>&nbsp;', $returnHTML);
      $returnHTML = preg_replace('/ accelerate or increase/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp;accelerate or increase</span> (Possible Chapter 11, stay away)</b>&nbsp;', $returnHTML);


      $beginHTML = '<html><head><link rel="stylesheet" href="./css/combined-min-1.0.5754.css" type="text/css"/>
<link type="text/css" href="./css/quote-layout.css" rel="stylesheet"/>
  <link type="text/css" href="./css/quote-typography.css" rel="stylesheet"/>
</head>
<body>
'; 
      $beginHTML .= $full_company_name[0]; 
      $beginHTML .= $returnHTML; 
      $beginHTML .= '</body></html>'; 
      $marketWatchfinalReturn = str_replace('<a ', '<a target="_blank"', $beginHTML);

      echo $marketWatchfinalReturn;       
}
else if ($which_website == "yahoo")
{
      // first round of data, from normal finance.yahoo.com/q?s=msft&ql=1

      $url="http://$host_name/q?s=$symbol&ql=1"; 
      $result = grabHTML($host_name, $url); 
      $result = str_replace ('href="/', 'href="http://finance.yahoo.com/', $result);  
      $result = str_replace ('a href', 'a onclick="return openPage(this.href)" href', $result);  
//      $result = preg_replace('/ etf/i', '<span style="background-color:red; color:black"><b> &nbsp;ETF</b>&nbsp;</span>', $result);                        
//      $result = preg_replace('/ etn/i', '<span style="background-color:red; color:black"><b> &nbsp;ETN</b>&nbsp;</span>', $result);                        


      $html = str_get_html($result);  
//      $ret = $html->find('#yfncsumtab'); 
      $retOriginalPage = $html->find('#yfi_headlines'); 

      $full_company_name = $html->find('div#yfi_rt_quote_summary div div h2'); 
      $returnCompanyName = $full_company_name[0]; 
      $returnCompanyName = preg_replace('/h2/', 'h1', $returnCompanyName);              

      $google_keyword_string = $returnCompanyName; 
/*      $google_keyword_string = trim($google_keyword_string); 
      $google_keyword_string = preg_replace('/<h1>/', "", $google_keyword_string);
      $google_keyword_string = preg_replace('/<\/h1>/', "", $google_keyword_string);
      $google_keyword_string = preg_replace('/\(/', "", $google_keyword_string);
      $google_keyword_string = preg_replace('/\)/', "", $google_keyword_string);
      $google_keyword_string = preg_replace('/\,/', "", $google_keyword_string);
      $google_keyword_string = preg_replace('/ /', "+", $google_keyword_string);  */ 
      $yesterdays_close =  $html->find('div#yfi_quote_summary_data table tbody tr td'); 

      $returnYesterdaysClose = $yesterdays_close[0]; 

      $returnYesterdaysClose = preg_replace('/<td class="(.*)">/', '<b>Prev. close</b> - $', $returnYesterdaysClose);  
      $returnYesterdaysClose = preg_replace('/<\/td>/', ' - ', $returnYesterdaysClose);        
//      $returnYesterdaysClose = '<h4 style="display: inline">' . $returnYesterdaysClose . '</h4>';   

      $preMarketYesterdaysClose = $html->find('div#yfi_rt_quote_summary div div span span'); 
      $preMarketYesterdaysClose[1] = preg_replace('/<span id="(.*)">/', '<span> <b>PRE MKT prev. close (last)</b> - $', $preMarketYesterdaysClose[1]);

      $tableDataArray = $html->find('.yfnc_tabledata1'); 
      $avgVol3Months = $tableDataArray[10];
      $avgVol3Months = preg_replace('/<td class="(.*)">/', '<font size="3" style="font-size: 12px; background-color:#CCFF99; color: black; display: inline-block;"><b>Avg Vol (3m)</b> - ', $avgVol3Months);  
      $avgVol3Months = preg_replace('/<\/td>/', ' - ', $avgVol3Months);

      $currentVolume = $tableDataArray[9];
      $currentVolume = preg_replace('/<td class="(.*)">/', '<b>Current Vol</b> - ', $currentVolume);  
      $currentVolume = preg_replace('/<\/td>/', ' - ', $currentVolume);

      // grab the information from the company profile 

      $url="http://finance.yahoo.com/q/pr?s=$symbol+Profile"; 
      $result = grabHTML($host_name, $url); 
      $result = str_replace ('href="/', 'href="http://finance.yahoo.com/', $result);  
      $html = str_get_html($result); 
      $retProfilePage = $html->find('.yfnc_modtitlew1');
      $finalProfileInfo = str_replace('<a ', '<a target="_blank"', $retProfilePage[0]);    

      $finalProfileInfo = str_replace('class="yfnc_modtitlew1"', 'valign="top"', $finalProfileInfo); 

      $finalProfileInfo = preg_replace('/Phone:(.*)\d{4}<br>/', '', $finalProfileInfo);      
      $finalProfileInfo = preg_replace('/<span class="yfi-module-title">Details<\/span>/', '', $finalProfileInfo);      
      $finalProfileInfo = preg_replace('/<tr><td class="yfnc_tablehead1" width="50%">Index Membership:<\/td><td class="yfnc_tabledata1">N\/A<\/td><\/tr>/', '', $finalProfileInfo);
      $finalProfileInfo = preg_replace('/<tr><th align="left"><span class="yfi-module-title">Business Summary<\/span><\/th><th align="right">&nbsp;<\/th><\/tr>/', '', $finalProfileInfo); 
      $finalProfileInfo = preg_replace('/<tr><td class="yfnc_tablehead1" width="50%">Full Time Employees:(.*?)<\/td><\/tr>/', '', $finalProfileInfo); 
      $finalProfileInfo = preg_replace('/Technology/', '<span style="font-size: 12px; background-color:red; color:black"><b>Technology</b></span>', $finalProfileInfo);
      $finalProfileInfo = preg_replace('/Basic Materials/', '<span style="font-size: 12px; background-color:red; color:black"><b>Basic Materials</b></span>', $finalProfileInfo);
      $finalProfileInfo = preg_replace('/Healthcare/', '<span style="font-size: 12px; background-color:red; color:black"><b>Healthcare</b></span>', $finalProfileInfo);
      $finalProfileInfo = preg_replace('/China/', '<span style="font-size: 12px; background-color:red; color:black"><b>China</b></span>', $finalProfileInfo);
      $finalProfileInfo = preg_replace('/Services/', '<span style="font-size: 12px; background-color:red; color:black"><b>Services</b></span>', $finalProfileInfo);
      $finalProfileInfo = preg_replace('/Conglomerates/', '<span style="font-size: 12px; background-color:red; color:black"><b>Conglomerates</b></span>', $finalProfileInfo);
      $finalProfileInfo = preg_replace('/Consumer Goods/', '<span style="font-size: 12px; background-color:red; color:black"><b>Consumer Goods</b></span>', $finalProfileInfo);
      $finalProfileInfo = preg_replace('/Financial/', '<span style="font-size: 12px; background-color:red; color:black"><b>Financial</b></span>', $finalProfileInfo);
      $finalProfileInfo = preg_replace('/Utilities/', '<span style="font-size: 12px; background-color:red; color:black"><b>Utilities</b></span>', $finalProfileInfo);
/*      $finalProfileInfo = preg_replace('/sector:/i', '<span style="font-size: 12px; background-color:red; color:black"><b>Sector:</b></span>', $finalProfileInfo); */
/*      $finalProfileInfo = preg_replace('/industry:/i', '<span style="font-size: 12px; background-color:red; color:black"><b>Industry:</b></span>', $finalProfileInfo); */

      // this will parse the company address from the $retProfilePage string

      preg_match('/<td width=\"270" class="yfnc_modtitlew1">(.*)Details/', $finalProfileInfo, $matches); 
      
      $companyAddress =  $matches[0];      
 
      $finalReturn = str_replace('<a ', '<a target="_blank" ', $retOriginalPage[0]);       

/*      $finalReturn = preg_replace("/<img[^>]+\>/i", "", $finalReturn); 
      $finalReturn = preg_replace("/<embed.+?<\/embed>/im", "", $finalReturn);       
      $finalReturn = preg_replace("/<iframe.+?<\/iframe>/im", "", $finalReturn); 
      $finalReturn = preg_replace("/<script.+?<\/script>/im", "", $finalReturn);  */ 

      $finalReturn = preg_replace($patterns = array("/<img[^>]+\>/i", "/<embed.+?<\/embed>/im", "/<iframe.+?<\/iframe>/im", "/<script.+?<\/script>/im"), $replace = array("", "", "", ""), $finalReturn);

      $finalReturn = preg_replace('/Headlines/', '<b>Yahoo Headlines</b>', $finalReturn);
      $finalReturn = preg_replace('/<cite>/', '<cite> - ', $finalReturn);              
      $finalReturn = preg_replace('/<span>/', '<span style="font-size: 12px; background-color:#D8D8D8; color:black"><b> ', $finalReturn); 
      $finalReturn = preg_replace('/<\/span>/', '</b></span>', $finalReturn); 

      $finalReturn = preg_replace('/([A-Z][a-z][a-z] [0-9][0-9]:[0-9][0-9][A-Z]M EDT)|([A-Z][a-z][a-z] [0-9]:[0-9][0-9][A-Z]M EDT)/', '<span style="font-size: 12px; background-color:black; color:white">$1$2</span> ', $finalReturn);
      $finalReturn = preg_replace('/([A-Z][a-z][a-z] [0-9][0-9]:[0-9][0-9][A-Z]M EST)|([A-Z][a-z][a-z] [0-9]:[0-9][0-9][A-Z]M EST)/', '<span style="font-size: 12px; background-color:black; color:white">$1$2</span> ', $finalReturn);




      $yahoo_todays_date = date('l'); 
      if ($yahoo_todays_date == "Monday")
      {
        $finalReturn = preg_replace('/(' .  get_yahoo_friday_trade_date() . ')/', '<span style="font-size: 12px; background-color:black; color:white">$1</span> ', $finalReturn);
        $finalReturn = preg_replace('/(' .  get_yahoo_saturday_trade_date() . ')/', '<span style="font-size: 12px; background-color:black; color:white">$1</span> ', $finalReturn);      
      }

      $finalReturn = preg_replace('/(' .  get_yahoo_yesterday_trade_date() . ')/', '<span style="font-size: 12px; background-color:black; color:white">$1</span> ', $finalReturn);      


      $finalReturn = preg_replace('/ delisted|delisted /i', '<span style="font-size: 12px; background-color:red; color:black"><b> DELISTED</b></span>', $finalReturn);
      $finalReturn = preg_replace('/ delisting|delisting /i', '<span style="font-size: 12px; background-color:red; color:black"><b> DELISTING</b></span>', $finalReturn);      
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
      $finalReturn = preg_replace('/ update/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp;update</span> (Jay backs off)</b>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ suspended/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp;suspended</span> (65-70%)</b>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ fraud/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp;fraud</span></b>&nbsp;', $finalReturn);      
      $finalReturn = preg_replace('/ dividend/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp;dividend</span> (if cut, 65-70%)</b>&nbsp;', $finalReturn);            
      $finalReturn = preg_replace('/ strategic alternatives/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp;strategic alternatives</span></b>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ unpatentable/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp;unpatentable</span> (60%)</b>&nbsp;', $finalReturn);
      $finalReturn = preg_replace('/ accelerate or increase/i', '<span style="font-size: 12px; background-color:red; color:black"><b>&nbsp;accelerate or increase</span> (Possible Chapter 11, stay away)</b>&nbsp;', $finalReturn);

//      strip_tags($finalReturn, '<embed><img>');// '#<img[^>]*>#i'

      $message_board = '</font><a target="_blank" href="http://finance.yahoo.com/mb/' . $symbol . '/"> Yahoo Message Boards</a>&nbsp;&nbsp;&nbsp;&nbsp;'; 
      $company_profile = '<a target="_blank" href="http://finance.yahoo.com/q/pr?s=' . $symbol . '+Profile">Yahoo Company Profile for ' . $symbol . '</a><br>'; 
      $yahoo_main_page = '<a target="_blank" href="http://finance.yahoo.com/q?s=' . $symbol . '&ql=1">Yahoo Main Page for ' . $symbol . '</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
      $yahoo_5_day_chart = '<a target="_blank" href="http://finance.yahoo.com/echarts?s=' . $symbol . '+Interactive#symbol=' . $symbol . ';range=5d">5-day Chart for ' . $symbol . '</a><br><br>';
      $eTrade = '<a target="_blank" href="https://www.etrade.wallst.com/v1/stocks/news/search_results.asp?symbol=' . $symbol . '">E*TRADE news for ' . $symbol . '</a>';
      $google = '<a target="_blank" href="https://www.google.com/search?hl=en&gl=us&tbm=nws&authuser=1&q=' . $google_keyword_string . '">Google news for ' . $symbol . '</a><br>'; 

      $finalReturn = $returnCompanyName . $returnYesterdaysClose . $preMarketYesterdaysClose[1] . "<br>" . "<div style='display: inline-block;'>" . $currentVolume . $avgVol3Months . $message_board . $yahoo_main_page . $eTrade . '<table width="600px"><tr width="600px"><td valign="top" >' . $finalReturn . '</td><td valign="top">' . $finalProfileInfo . '</td></tr></table>'; 

      echo $finalReturn; 

//        JSON string 

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
//      $url = "http://bigcharts.marketwatch.com/quickchart/quickchart.asp?symb=vix&insttype=&freq=9&show=&time=1"; 
      $url = "http://finance.yahoo.com/q?s=^VIX"; 

      $result = grabHTML("finance.yahoo.com", $url);
      $html = str_get_html($result);  

      $vixTD = $html->find('.time_rtq_ticker span'); 
      $vixTD[0] = preg_replace('/<span id="yfs_l10_\^vix">/', '', $vixTD[0]); 
      $vixTD[0] = preg_replace('/<\/span>/', '', $vixTD[0]); 
      $vixTDReturn = "<br>Last VIX Value: " . $vixTD[0];

      $bigChartsReturn = preg_replace('/<\/div>/', $vixTDReturn . '</div>', $bigChartsReturn); 

echo $bigChartsReturn; 

} // if ($which_website == "bigcharts")
else if ($which_website == "etrade")
{
      fwrite( $file, " which_website is - " . $which_website . "\r\n"); 
      fwrite( $file, " host_name is " . $host_name . "\r\n");  

 $url =  "www.etrade.wallst.com/v1/stocks/news/search_results.asp?symbol=$symbol&rsO=new";

// $url="http://finance.yahoo.com/q?s=$symbol&ql=1"; 

      $result = grabEtradeHTML("www.etrade.wallst.com", $url);
      $html = str_get_html($result);  
      $eTradeNewsDiv = $html->find('#news_story');

//      $html = str_get_html($eTradeNewsDiv[0]);  
      $returnEtradeHTML = $eTradeNewsDiv[0]; 
      $returnEtradeHTML = preg_replace('/<div class="fRight newsSideWidth t10">(.*)<div class="clear"><\/div>/', '', $returnEtradeHTML); 
      $returnEtradeHTML = preg_replace('/width:306px;/', 'width:600px;', $returnEtradeHTML); 

      fwrite( $file, " after calling grabEtradeHTML result is " . $returnEtradeHTML . "\r\n"); 

//       fwrite( $file, " host_name is " . $host_name . " Etade page - " . $html);
      echo $returnEtradeHTML; 
}else if ($which_website == "google")
{
      fwrite( $file, " which_website is - " . $which_website . "\r\n"); 
      fwrite( $file, " host_name is " . $host_name . "\r\n");  
      fwrite( $file, " google_keyword_string is " . $google_keyword_string . "\r\n");

 $url =  "www.google.com/search?hl=en&gl=us&tbm=nws&authuser=1&q=" . $google_keyword_string;

      fwrite( $file, " url is " . $google_keyword_string . "\r\n");
      $result = grabHTML($host_name, $url);

      fwrite( $file, " result is *" . $result . "*\r\n");


/*
      $result = grabEtradeHTML("www.etrade.wallst.com", $url);
      $html = str_get_html($result);  
      $eTradeNewsDiv = $html->find('#news_story');

//      $html = str_get_html($eTradeNewsDiv[0]);  
      $returnEtradeHTML = $eTradeNewsDiv[0]; 
      $returnEtradeHTML = preg_replace('/<div class="fRight newsSideWidth t10">(.*)<div class="clear"><\/div>/', '', $returnEtradeHTML); 
      $returnEtradeHTML = preg_replace('/width:306px;/', 'width:600px;', $returnEtradeHTML); 

      fwrite( $file, " after calling grabHTML for google, result is " . $returnEtradeHTML . "\r\n"); 

//       fwrite( $file, " host_name is " . $host_name . " Etade page - " . $html);
      echo $returnEtradeHTML;   */
}

// fwrite( $file, $ret[0] );

fclose( $file );

?>