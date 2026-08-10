<?php
/**
 * OTC Corporate Actions — OHLC lookup backend
 *
 * Called via AJAX from index.html.
 * Expects POST params:
 *   - symbol   : the ticker to look up (already cleaned on the front end)
 *   - fallback : optional old symbol to try if `symbol` returns no data
 *                (used for "Symbol Changes"; omitted/empty for "Venue Changes")
 *
 * Returns JSON:
 *   { "status": "ok",   "symbolUsed": "XXXX", "ohlc": [ {date,open,high,low,close,volume}, ... ] }
 *   { "status": "none" }                      // no data for symbol (or fallback)
 *   { "status": "error", "message": "..." }   // unexpected error
 */

header('Content-Type: application/json');

// ---- Config ----
$ACCESS_KEY = "d36ab142bed5a1430fcde797063f6b9a";   // marketstack access key (server-side)
$API_BASE   = "https://api.marketstack.com/v2/eod";
$WEEKS_BACK = 3;

// ---- Read input ----
$symbol   = isset($_POST['symbol'])   ? trim($_POST['symbol'])   : "";
$fallback = isset($_POST['fallback']) ? trim($_POST['fallback']) : "";

if ($symbol === "" && $fallback === "") {
    echo json_encode(["status" => "error", "message" => "No symbol provided"]);
    exit;
}

// ---- Date range ----
$today        = date("Y-m-d");
$weeksAgoTime = strtotime("-{$WEEKS_BACK} weeks");
$dateFrom     = date("Y-m-d", $weeksAgoTime);

/**
 * Query marketstack EOD for a single symbol.
 * Returns: array of OHLC rows on success, or null if no valid data.
 */
function fetchOHLC($symbol, $ACCESS_KEY, $API_BASE, $dateFrom, $today) {
    $url = $API_BASE
         . "?access_key=" . urlencode($ACCESS_KEY)
         . "&symbols="    . urlencode($symbol)
         . "&date_from="  . urlencode($dateFrom)
         . "&date_to="    . urlencode($today);

    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL            => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 20,
        CURLOPT_SSL_VERIFYPEER => true,
    ]);
    $raw = curl_exec($ch);
    $err = curl_error($ch);
    curl_close($ch);

    if ($raw === false) {
        // network/curl failure — treat as no data but signal upstream
        return ["__curl_error" => $err];
    }

    $decoded = json_decode($raw, true);
    if ($decoded === null) {
        return ["__parse_error" => true];
    }

    // marketstack "no symbol" error shape:
    // {"error":{"code":"no_valid_symbols_provided","message":"..."}}
    if (isset($decoded['error'])) {
        return null; // no valid data for this symbol
    }

    // Success shape: {"data":[ {open,high,low,close,volume,date,symbol}, ... ]}
    if (!isset($decoded['data']) || !is_array($decoded['data']) || count($decoded['data']) === 0) {
        return null; // empty data
    }

    // Normalize into the fields we need, oldest-first for charting.
    $rows = [];
    foreach ($decoded['data'] as $d) {
        // Skip malformed rows
        if (!isset($d['date'])) continue;
        $rows[] = [
            "date"   => substr($d['date'], 0, 10),
            "open"   => isset($d['open'])   ? (float)$d['open']   : null,
            "high"   => isset($d['high'])   ? (float)$d['high']   : null,
            "low"    => isset($d['low'])    ? (float)$d['low']    : null,
            "close"  => isset($d['close'])  ? (float)$d['close']  : null,
            "volume" => isset($d['volume']) ? (float)$d['volume'] : 0,
        ];
    }

    if (count($rows) === 0) return null;

    // marketstack returns newest-first; sort oldest-first for the chart.
    usort($rows, function ($a, $b) {
        return strcmp($a['date'], $b['date']);
    });

    return $rows;
}

// ---- Try primary symbol ----
$result = fetchOHLC($symbol, $ACCESS_KEY, $API_BASE, $dateFrom, $today);

// Handle hard errors from the primary attempt
if (is_array($result) && isset($result['__curl_error'])) {
    echo json_encode(["status" => "error", "message" => "Network error: " . $result['__curl_error']]);
    exit;
}
if (is_array($result) && isset($result['__parse_error'])) {
    echo json_encode(["status" => "error", "message" => "Could not parse API response"]);
    exit;
}

if ($result !== null) {
    echo json_encode([
        "status"     => "ok",
        "symbolUsed" => $symbol,
        "ohlc"       => $result,
    ]);
    exit;
}

// ---- Primary returned no data. Try fallback (old symbol) if provided ----
if ($fallback !== "") {
    $fb = fetchOHLC($fallback, $ACCESS_KEY, $API_BASE, $dateFrom, $today);

    if (is_array($fb) && (isset($fb['__curl_error']) || isset($fb['__parse_error']))) {
        // fallback errored — report no data rather than crash
        echo json_encode(["status" => "none"]);
        exit;
    }

    if ($fb !== null) {
        echo json_encode([
            "status"     => "ok",
            "symbolUsed" => $fallback,
            "ohlc"       => $fb,
        ]);
        exit;
    }
}

// ---- Nothing found ----
echo json_encode(["status" => "none"]);
exit;
