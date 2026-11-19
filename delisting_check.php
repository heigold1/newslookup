<?php
// delisting_check.php?symbol=XYZ
// Checks every delisting source for one ticker and returns JSON:
//   { ok, symbol, checked_at_et, sources_checked: [...], flags: [ {level, source, text, date, days, link, note} ], errors: [ {source, error} ] }
//   level: "red"    = suspension/delisting today, already happened, or within DL_SOON_DAYS days
//          "orange" = a date further out
//          "check"  = found something but no usable date - look at it by hand
//
// ===== TO ADD A NEW SOURCE (e.g. however Jay finds them) =====
//   1) write a function  dl_check_something($symbol, $today)  that returns an array of flags (same shape as in dl_check_nasdaq_pending)
//      - if the source can't be read, throw an Exception: it shows up as "CHECK FAILED" instead of silently passing
//   2) add one line for it to $DL_SOURCES at the bottom of this file.

header('Content-Type: application/json');
date_default_timezone_set('America/New_York');

const DL_CACHE_SECONDS = 1800;   // download each list at most every 30 minutes (cached in the server's temp folder)
const DL_SOON_DAYS     = 5;      // a date within this many days (or already passed) = red
const DL_SHOW_DAYS     = 30;     // a future date further out than this = no pink box (still listed in the Claude dump)

function dl_ago($n) {            // 1 -> "1 day ago", 3 -> "3 days ago"
    return $n == 1 ? '1 day ago' : "$n days ago";
}

// ---------- helpers ----------

function dl_http_get($url) {
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_CONNECTTIMEOUT => 8,
        CURLOPT_TIMEOUT        => 15,
        CURLOPT_ENCODING       => '',            // accept gzip
        CURLOPT_HTTPHEADER     => [              // Nasdaq's API doesn't answer requests without browser-like headers
            'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0 Safari/537.36',
            'Accept: application/json, text/plain, */*',
            'Accept-Language: en-US,en;q=0.9',
            'Origin: https://www.nasdaq.com',
            'Referer: https://www.nasdaq.com/',
        ],
    ]);
    $body = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $err  = curl_error($ch);
    curl_close($ch);
    if ($body === false || $code != 200) {
        throw new Exception("download failed (HTTP $code" . ($err ? " - $err" : "") . ")");
    }
    return $body;
}

// Returns [decoded JSON, note]. Uses the cached copy if it's fresh; if a download fails, falls back to a copy under 24 hours old.
function dl_cached_json($cacheName, $url) {
    $file = sys_get_temp_dir() . '/newslookup_' . $cacheName . '.json';
    $age  = file_exists($file) ? time() - filemtime($file) : null;

    if ($age !== null && $age < DL_CACHE_SECONDS) {
        return [json_decode(file_get_contents($file), true), null];
    }
    try {
        $body = dl_http_get($url);
        $json = json_decode($body, true);
        if (!is_array($json)) throw new Exception('the response was not JSON');
        file_put_contents($file, $body);
        return [$json, null];
    } catch (Exception $e) {
        if ($age !== null && $age < 86400) {
            return [json_decode(file_get_contents($file), true),
                    'using a copy from ' . round($age / 60) . ' min ago - ' . $e->getMessage()];
        }
        throw $e;
    }
}

function dl_parse_date($s) {            // "11/19/2026" -> DateTime (midnight New York) or null
    $d = DateTime::createFromFormat('!n/j/Y', trim((string)$s), new DateTimeZone('America/New_York'));
    return $d ?: null;
}

function dl_days_from_today($today, $d) {   // negative = in the past
    return (int)$today->diff($d)->format('%r%a');
}

// ---------- SOURCE 1: Nasdaq "Issues Pending Suspension or Delisting" ----------
// Page: https://www.nasdaq.com/market-activity/stocks/issuers-pending-suspension-delisting
// An issue stays on this list until the first business day AFTER it's delisted (so a stock delisted yesterday is already gone).

function dl_check_nasdaq_pending($symbol, $today) {
    list($json, $note) = dl_cached_json('nasdaq_pending',
        'https://api.nasdaq.com/api/quote/list-type-extended/listing?queryString=suspended');

    $rows = $json['data']['suspendedCompanyList']['rows'] ?? null;
    if (!is_array($rows)) throw new Exception('unexpected format (no rows) - Nasdaq may have changed the feed');

    $flags = [];
    foreach ($rows as $r) {
        if (strtoupper(trim($r['ExchangeSymbolId'] ?? '')) !== $symbol) continue;

        $status = trim($r['Status'] ?? '');
        $reason = trim($r['Reason'] ?? '') ?: 'reason not given';
        $f25    = trim($r['ApplicationDate'] ?? '');
        $tail   = ' - ' . $reason . ($f25 ? ', Form 25 filed ' . $f25 : '') . ' (Nasdaq pending list)';
        $d      = dl_parse_date($r['EffectiveDate'] ?? '');

        if (!$d) {
            $level = 'check';
            $days  = null;
            $text  = 'ON NASDAQ PENDING SUSPENSION/DELISTING LIST - no date given - status ' . ($status ?: '?') . $tail;
        } else {
            $days = dl_days_from_today($today, $d);
            $when = $d->format('D j M');
            if (strcasecmp($status, 'Suspended') === 0) {
                if     ($days > 1)  $text = "SUSPENDED FROM NASDAQ IN $days DAYS ($when)";
                elseif ($days == 1) $text = "SUSPENDED FROM NASDAQ AS OF TOMORROW ($when)";
                elseif ($days == 0) $text = "SUSPENDED FROM NASDAQ TODAY ($when)";
                else                $text = "SUSPENDED FROM NASDAQ SINCE $when (" . dl_ago(-$days) . ") - may only trade OTC";
            } else {
                $rel  = $days == 0 ? 'TODAY' : ($days == 1 ? 'TOMORROW' : ($days > 1 ? "in $days days" : dl_ago(-$days)));
                $text = "NASDAQ DELISTING PENDING - effective $when ($rel) - status: " . ($status ?: '?');
            }
            $text .= $tail;
            if ($days > DL_SHOW_DAYS)      $level = 'info';     // more than a month away - no pink box, only noted in the Claude dump
            elseif ($days <= DL_SOON_DAYS) $level = 'red';
            else                           $level = 'orange';
        }

        $flags[] = [
            'level'  => $level,
            'source' => 'Nasdaq pending list',
            'text'   => $text,
            'date'   => $d ? $d->format('Y-m-d') : null,
            'days'   => $days,
            'link'   => 'https://www.nasdaq.com/market-activity/stocks/issuers-pending-suspension-delisting',
            'note'   => $note,
        ];
    }
    return $flags;
}

// ---------- run every source ----------

$symbol = strtoupper(trim($_GET['symbol'] ?? ''));
if (!preg_match('/^[A-Z0-9.\-]{1,12}$/', $symbol)) {
    echo json_encode(['ok' => false, 'error' => 'bad or missing symbol']);
    exit;
}

// ===== ADD NEW SOURCES HERE:  'Name shown in the dump' => 'function_name', =====
$DL_SOURCES = [
    'Nasdaq pending list' => 'dl_check_nasdaq_pending',
];

$today  = new DateTime('today', new DateTimeZone('America/New_York'));
$flags  = [];
$errors = [];
foreach ($DL_SOURCES as $name => $fn) {
    try {
        $flags = array_merge($flags, $fn($symbol, $today));
    } catch (Throwable $e) {
        $errors[] = ['source' => $name, 'error' => $e->getMessage()];
    }
}

echo json_encode([
    'ok'              => true,
    'symbol'          => $symbol,
    'checked_at_et'   => date('D, j M Y g:i:s A T'),
    'sources_checked' => array_keys($DL_SOURCES),
    'flags'           => $flags,
    'errors'          => $errors,
]);