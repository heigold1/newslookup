<?php
header("Access-Control-Allow-Origin: http://localhost");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Origin: *");



error_reporting(E_ALL);

$fromDaysBack = 100;
$toDaysBack = 1; 
$symbol=$_GET['symbol'];

fopen("cookies.txt", "w");


function getTradeDate($daysBack)
{
    $trade_date = "";

    $trade_date = date('Y-m-d', strtotime("-" . $daysBack . " days"));

    return $trade_date;
}

function secondsToTime($seconds) {
    $dtF = new \DateTime('@0');
    $dtT = new \DateTime("@$seconds");
    return $dtF->diff($dtT)->format('%a');
}

function dateDifference($date)
{
    $currentDate = new DateTime(); 
    $currentDateTimeStamp = $currentDate->format('U'); 

    return secondsToTime($currentDateTimeStamp - $date); 

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


$fromDate = getTradeDate($fromDaysBack); 
$toDate = getTradeDate($toDaysBack); 
$returnArray = array();
$returnArray['penny_to_dollar'] = false; 

/*echo "test";
die();  */ 

$url = "https://api.marketstack.com/v2/eod?access_key=d36ab142bed5a1430fcde797063f6b9a&symbols=" . $symbol . "&date_from=" . $fromDate . "&date_to=" . $toDate . "&limit=100"; 

$results = grabHTML("api.marketstack.com", $url);

$fullJSON = json_decode($results);

$fiveDayVolume = 0.0; 


$yestVol = 0; // default if both are missing
if (!empty($fullJSON->data[0]->adj_volume) && !empty($fullJSON->data[0]->volume)) {
    $yestVol = max((float)$fullJSON->data[0]->adj_volume, (float)$fullJSON->data[0]->volume);
} elseif (!empty($fullJSON->data[0]->adj_volume)) {
    $yestVol = (float)$fullJSON->data[0]->adj_volume;
} elseif (!empty($fullJSON->data[0]->volume)) {
    $yestVol = (float)$fullJSON->data[0]->volume;
}

$returnArray['yest_volume'] = $yestVol;

if (isset($fullJSON->data[1]->close)) {
    $day_1_percentage = (($fullJSON->data[0]->close - $fullJSON->data[1]->close) / $fullJSON->data[1]->close) * 100;
    $day_1_percentage_low = (($fullJSON->data[0]->low - $fullJSON->data[1]->close) / $fullJSON->data[1]->close) * 100;

    $returnArray['day_1'] = number_format((float)$day_1_percentage, 2, '.', '');
    $returnArray['day_1_low'] = number_format((float)$day_1_percentage_low, 2, '.', '');

    // Calculate day_1_volume using both adj_volume and volume
    $prevVol = 0; // previous day's volume (data[1])
    $currVol = 0; // current day's volume (data[0])

    // data[1] volume
    if (!empty($fullJSON->data[1]->adj_volume) && !empty($fullJSON->data[1]->volume)) {
        $prevVol = max((float)$fullJSON->data[1]->adj_volume, (float)$fullJSON->data[1]->volume);
    } elseif (!empty($fullJSON->data[1]->adj_volume)) {
        $prevVol = (float)$fullJSON->data[1]->adj_volume;
    } elseif (!empty($fullJSON->data[1]->volume)) {
        $prevVol = (float)$fullJSON->data[1]->volume;
    }

    // data[0] volume
    if (!empty($fullJSON->data[0]->adj_volume) && !empty($fullJSON->data[0]->volume)) {
        $currVol = max((float)$fullJSON->data[0]->adj_volume, (float)$fullJSON->data[0]->volume);
    } elseif (!empty($fullJSON->data[0]->adj_volume)) {
        $currVol = (float)$fullJSON->data[0]->adj_volume;
    } elseif (!empty($fullJSON->data[0]->volume)) {
        $currVol = (float)$fullJSON->data[0]->volume;
    }

    // day_1_volume is ratio of current to previous day
    $returnArray['day_1_volume'] = $prevVol > 0 ? number_format($currVol / $prevVol, 2) : 0;

    $returnArray['day_1_total_volume'] = number_format($currVol);
    $returnArray['day_1_recovery'] = number_format((($fullJSON->data[0]->close - $fullJSON->data[0]->low) / $fullJSON->data[0]->low) * 100, 2);

    $fiveDayVolume += $currVol;

    // check penny stock conversion
    if (($fullJSON->data[0]->close > 1.00) && ($fullJSON->data[1]->close < 1.00)) {
        $returnArray['penny_to_dollar'] = true;
    }

} else {
    $returnArray['day_1'] = "N/A";
    $returnArray['day_1_volume'] = 0;
    $returnArray['day_1_total_volume'] = 0;
    $returnArray['day_1_low'] = "N/A";
    $returnArray['day_1_recovery'] = 0;
}



if (isset($fullJSON->data[2]->close)) {
    $day_2_percentage = (($fullJSON->data[1]->close - $fullJSON->data[2]->close) / $fullJSON->data[2]->close) * 100;
    $day_2_percentage_low = (($fullJSON->data[1]->low - $fullJSON->data[2]->close) / $fullJSON->data[2]->close) * 100;

    $returnArray['day_2'] = number_format((float)$day_2_percentage, 2, '.', '');
    $returnArray['day_2_low'] = number_format((float)$day_2_percentage_low, 2, '.', '');

    // Calculate day_2_volume using both adj_volume and volume
    $prevVol = 0; // previous day's volume (data[2])
    $currVol = 0; // current day's volume (data[1])

    // data[2] volume
    if (!empty($fullJSON->data[2]->adj_volume) && !empty($fullJSON->data[2]->volume)) {
        $prevVol = max((float)$fullJSON->data[2]->adj_volume, (float)$fullJSON->data[2]->volume);
    } elseif (!empty($fullJSON->data[2]->adj_volume)) {
        $prevVol = (float)$fullJSON->data[2]->adj_volume;
    } elseif (!empty($fullJSON->data[2]->volume)) {
        $prevVol = (float)$fullJSON->data[2]->volume;
    }

    // data[1] volume
    if (!empty($fullJSON->data[1]->adj_volume) && !empty($fullJSON->data[1]->volume)) {
        $currVol = max((float)$fullJSON->data[1]->adj_volume, (float)$fullJSON->data[1]->volume);
    } elseif (!empty($fullJSON->data[1]->adj_volume)) {
        $currVol = (float)$fullJSON->data[1]->adj_volume;
    } elseif (!empty($fullJSON->data[1]->volume)) {
        $currVol = (float)$fullJSON->data[1]->volume;
    }

    // day_2_volume is ratio of current to previous day
    $returnArray['day_2_volume'] = $prevVol > 0 ? number_format($currVol / $prevVol, 2) : 0;

    $returnArray['day_2_total_volume'] = number_format($currVol);
    $fiveDayVolume += $currVol;

} else {
    $returnArray['day_2'] = "N/A";
    $returnArray['day_2_volume'] = 0;
    $returnArray['day_2_total_volume'] = 0;
    $returnArray['day_2_low'] = "N/A";
}


if (isset($fullJSON->data[3]->close)) {
    $day_3_percentage = (($fullJSON->data[2]->close - $fullJSON->data[3]->close) / $fullJSON->data[3]->close) * 100;
    $day_3_percentage_low = (($fullJSON->data[2]->low - $fullJSON->data[3]->close) / $fullJSON->data[3]->close) * 100;

    $returnArray['day_3'] = number_format((float)$day_3_percentage, 2, '.', '');
    $returnArray['day_3_low'] = number_format((float)$day_3_percentage_low, 2, '.', '');

    // Previous day volume (data[3])
    $prevVol = 0;
    if (!empty($fullJSON->data[3]->adj_volume) && !empty($fullJSON->data[3]->volume)) {
        $prevVol = max((float)$fullJSON->data[3]->adj_volume, (float)$fullJSON->data[3]->volume);
    } elseif (!empty($fullJSON->data[3]->adj_volume)) {
        $prevVol = (float)$fullJSON->data[3]->adj_volume;
    } elseif (!empty($fullJSON->data[3]->volume)) {
        $prevVol = (float)$fullJSON->data[3]->volume;
    }

    // Current day volume (data[2])
    $currVol = 0;
    if (!empty($fullJSON->data[2]->adj_volume) && !empty($fullJSON->data[2]->volume)) {
        $currVol = max((float)$fullJSON->data[2]->adj_volume, (float)$fullJSON->data[2]->volume);
    } elseif (!empty($fullJSON->data[2]->adj_volume)) {
        $currVol = (float)$fullJSON->data[2]->adj_volume;
    } elseif (!empty($fullJSON->data[2]->volume)) {
        $currVol = (float)$fullJSON->data[2]->volume;
    }

    // day_3_volume is ratio of current to previous
    $returnArray['day_3_volume'] = $prevVol > 0 ? number_format($currVol / $prevVol, 2) : 0;

    $returnArray['day_3_total_volume'] = number_format($currVol);
    $fiveDayVolume += $currVol;

} else {
    $returnArray['day_3'] = "N/A";
    $returnArray['day_3_low'] = "N/A";
    $returnArray['day_3_volume'] = 0;
    $returnArray['day_3_total_volume'] = 0;
}


if (isset($fullJSON->data[4]->close)) {
    $day_4_percentage = (($fullJSON->data[3]->close - $fullJSON->data[4]->close) / $fullJSON->data[4]->close) * 100;
    $day_4_percentage_low = (($fullJSON->data[3]->low - $fullJSON->data[4]->close) / $fullJSON->data[4]->close) * 100;

    $returnArray['day_4'] = number_format((float)$day_4_percentage, 2, '.', '');
    $returnArray['day_4_low'] = number_format((float)$day_4_percentage_low, 2, '.', '');

    // Previous day volume (data[4])
    $prevVol = 0;
    if (!empty($fullJSON->data[4]->adj_volume) && !empty($fullJSON->data[4]->volume)) {
        $prevVol = max((float)$fullJSON->data[4]->adj_volume, (float)$fullJSON->data[4]->volume);
    } elseif (!empty($fullJSON->data[4]->adj_volume)) {
        $prevVol = (float)$fullJSON->data[4]->adj_volume;
    } elseif (!empty($fullJSON->data[4]->volume)) {
        $prevVol = (float)$fullJSON->data[4]->volume;
    }

    // Current day volume (data[3])
    $currVol = 0;
    if (!empty($fullJSON->data[3]->adj_volume) && !empty($fullJSON->data[3]->volume)) {
        $currVol = max((float)$fullJSON->data[3]->adj_volume, (float)$fullJSON->data[3]->volume);
    } elseif (!empty($fullJSON->data[3]->adj_volume)) {
        $currVol = (float)$fullJSON->data[3]->adj_volume;
    } elseif (!empty($fullJSON->data[3]->volume)) {
        $currVol = (float)$fullJSON->data[3]->volume;
    }

    // day_4_volume is ratio of current to previous
    $returnArray['day_4_volume'] = $prevVol > 0 ? number_format($currVol / $prevVol, 2) : 0;

    $returnArray['day_4_total_volume'] = number_format($currVol);
    $fiveDayVolume += $currVol;

} else {
    $returnArray['day_4'] = "N/A";
    $returnArray['day_4_low'] = "N/A";
    $returnArray['day_4_volume'] = 0;
    $returnArray['day_4_total_volume'] = 0;
}


if (isset($fullJSON->data[5]->close)) {
    $day_5_percentage = (($fullJSON->data[4]->close - $fullJSON->data[5]->close) / $fullJSON->data[5]->close) * 100;
    $day_5_percentage_low = (($fullJSON->data[4]->low - $fullJSON->data[5]->close) / $fullJSON->data[5]->close) * 100;

    $returnArray['day_5'] = number_format((float)$day_5_percentage, 2, '.', '');
    $returnArray['day_5_low'] = number_format((float)$day_5_percentage_low, 2, '.', '');

    // Previous day volume (data[5])
    $prevVol = 0;
    if (!empty($fullJSON->data[5]->adj_volume) && !empty($fullJSON->data[5]->volume)) {
        $prevVol = max((float)$fullJSON->data[5]->adj_volume, (float)$fullJSON->data[5]->volume);
    } elseif (!empty($fullJSON->data[5]->adj_volume)) {
        $prevVol = (float)$fullJSON->data[5]->adj_volume;
    } elseif (!empty($fullJSON->data[5]->volume)) {
        $prevVol = (float)$fullJSON->data[5]->volume;
    }

    // Current day volume (data[4])
    $currVol = 0;
    if (!empty($fullJSON->data[4]->adj_volume) && !empty($fullJSON->data[4]->volume)) {
        $currVol = max((float)$fullJSON->data[4]->adj_volume, (float)$fullJSON->data[4]->volume);
    } elseif (!empty($fullJSON->data[4]->adj_volume)) {
        $currVol = (float)$fullJSON->data[4]->adj_volume;
    } elseif (!empty($fullJSON->data[4]->volume)) {
        $currVol = (float)$fullJSON->data[4]->volume;
    }

    // day_5_volume is ratio of current to previous
    $returnArray['day_5_volume'] = $prevVol > 0 ? number_format($currVol / $prevVol, 2) : 0;

    $returnArray['day_5_total_volume'] = number_format($currVol);
    $fiveDayVolume += $currVol;

} else {
    $returnArray['day_5'] = "N/A";
    $returnArray['day_5_low'] = "N/A";
    $returnArray['day_5_volume'] = 0;
    $returnArray['day_5_total_volume'] = 0;
}


$returnArray['five_day_average_volume'] = $fiveDayVolume/5.0; 

$latestDay = intval($fullJSON->pagination->count) - 1; 

if (isset($fullJSON->pagination->count))
{
    $latestDay = strtotime($fullJSON->data[$latestDay]->date); 
    $daysOld = dateDifference($latestDay); 

    if ($daysOld < 30)
    {
        $returnArray['new_stock'] = true; 
    }
    else
    {
        $returnArray['new_stock'] = false;
    }
    $returnArray['count'] = $fullJSON->pagination->count; 
}
else
{
    $returnArray['count'] = 0;  
    $returnArray['new_stock'] = true; 
}


// ---------- BUILD FULL OHLC ARRAY FOR CHART ----------

$returnArray['ohlc'] = [];

if (isset($fullJSON->data) && is_array($fullJSON->data)) {
    foreach ($fullJSON->data as $bar) {
        if (
            isset($bar->open) &&
            isset($bar->high) &&
            isset($bar->low) &&
            isset($bar->close) &&
            isset($bar->date)
        ) {
            // Determine the proper volume
            $vol = 0;
            if (!empty($bar->adj_volume) && !empty($bar->volume)) {
                $vol = max((float)$bar->adj_volume, (float)$bar->volume);
            } elseif (!empty($bar->adj_volume)) {
                $vol = (float)$bar->adj_volume;
            } elseif (!empty($bar->volume)) {
                $vol = (float)$bar->volume;
            } // else $vol stays 0

            $returnArray['ohlc'][] = [
                "x" => strtotime($bar->date) * 1000, // milliseconds
                "o" => (float)$bar->open,
                "h" => (float)$bar->high,
                "l" => (float)$bar->low,
                "c" => (float)$bar->close,
                "v" => $vol
            ];
        }
    }
}



echo json_encode($returnArray);

?>