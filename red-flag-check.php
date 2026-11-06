<?php
/**
 * red-flag-check.php
 *
 * Runs a set of "red flag" checks on a ticker.
 * If ANY check raises a flag -> has_red_flags = true.
 *
 * Current checks:
 *   1. pipeline  - pharma / biotech / med-device company with <= 2 products
 *                  in human trials, under FDA review, or approved.
 *
 * To add a future check:
 *   1. Write a function  check_<name>(string $ticker): array  that returns
 *      ['flag' => true/false, 'reason' => '...', ...any extra details].
 *   2. Add '<name>' => 'check_<name>' to the list in red_flag_checks().
 *
 * Usage (command line):
 *     export ANTHROPIC_API_KEY="sk-ant-..."
 *     php red-flag-check.php PMN
 *
 * From the scanner (later):
 *     require_once 'red-flag-check.php';
 *     $result = run_red_flag_checks('PMN');
 *     if ($result['has_red_flags']) { ... }
 *
 * Requires: PHP 7.4+ with the curl extension.
 */

const RF_MODEL   = 'claude-haiku-5-5';   // cheap; swap to 'claude-sonnet-5-5' if results look shaky
const RF_API_URL = 'https://api.anthropic.com/v1/messages';

// ---------------------------------------------------------------------------
// The list of checks to run. Add new ones here.
// ---------------------------------------------------------------------------
function red_flag_checks(): array
{
    return [
        'pipeline' => 'check_pipeline',
    ];
}

// ---------------------------------------------------------------------------
// Runs every check and combines the results.
// ---------------------------------------------------------------------------
function run_red_flag_checks(string $ticker): array
{
    $ticker  = strtoupper(trim($ticker));
    $flags   = [];
    $details = [];

    foreach (red_flag_checks() as $name => $fn) {
        try {
            $res = $fn($ticker);
        } catch (Throwable $e) {
            // A failed check is treated as "unknown", not as a flag.
            $res = ['flag' => false, 'error' => $e->getMessage(),
                    'reason' => "$name check failed"];
        }
        $details[$name] = $res;
        if (!empty($res['flag'])) {
            $flags[] = $name . ': ' . ($res['reason'] ?? '');
        }
    }

    return [
        'ticker'        => $ticker,
        'has_red_flags' => count($flags) > 0,
        'flags'         => $flags,
        'details'       => $details,
    ];
}

// ---------------------------------------------------------------------------
// CHECK 1: drug / device pipeline
// ---------------------------------------------------------------------------
function check_pipeline(string $ticker): array
{
      $prompt = <<<'EOT'
Use web search to check the company with stock ticker {TICKER}.

Step 1 - Identify the company. Confirm the company that currently trades under {TICKER}
(tickers get reused; make sure it's the current company).

Step 1b - Decide is_pharma_biotech_or_device. Answer true ONLY if the company develops
or sells its OWN drugs, biologics, biosimilars, vaccines, cell/gene therapies, regulated
medical devices or diagnostic tests that need FDA (or equivalent) approval/clearance.
Answer false for: research tools/reagents/lab equipment, CROs, contract manufacturers
(CDMOs), lab testing services, distributors/pharmacies, agricultural or animal biotech,
licensing-only platforms, and any non-healthcare business - even if it calls itself "biotech".

Step 2 - Find its products. Prefer the company's own sources: its latest 10-K/10-Q,
its investor-relations pipeline page, or recent press releases. Use the most recent
information available.

Step 3 - List each qualifying product ONCE. A product qualifies only if it is:
  - approved/cleared AND marketed, or
  - under FDA (or equivalent regulator) review, or
  - in human clinical trials (Phase 1, 2 or 3).

Counting rules (follow exactly):
  - Count DISTINCT products only: a distinct drug molecule, biologic, biosimilar,
    device or diagnostic test.
  - Biosimilars, biologics, vaccines and cell/gene therapies DO count.
  - The SAME product counts as ONE, even if it has different models, sizes, versions,
    generations, accessories, applicators, doses, formulations, brand names, or is used
    in different diseases/indications.
  - A device platform sold under one brand (system + its parts) = ONE product.
  - Do NOT count: preclinical or discovery programs, discontinued products, products
    licensed out to another company, services, or software that isn't a regulated medical device.
  - If you are unsure whether two items are the same product, count them as ONE.
  - If you are unsure whether a product qualifies, INCLUDE it and add "(uncertain)" after its stage.

Step 4 - pipeline_count = the number of items in your "products" list. They must match.

Answer ONLY with a JSON object, no other text, in exactly this format:
{
  "company": "<company name>",
  "is_pharma_biotech_or_device": true or false,
  "products": ["<product name> (<stage, e.g. FDA-cleared & marketed / Phase 2 / under FDA review>)", ...],
  "pipeline_count": <number of items in "products">,
  "reason": "<one short sentence explaining the count, naming anything you excluded and why>"
}
EOT;

    $res = ask_claude_json(str_replace('{TICKER}', $ticker, $prompt));

    // The flag rule lives here in PHP, not in the prompt, so it can't drift.
    $isHealth = !empty($res['is_pharma_biotech_or_device']);
    $count    = isset($res['pipeline_count']) ? (int)$res['pipeline_count'] : null;
    $res['flag'] = $isHealth && $count !== null && $count <= 2;

    // A real drug/device developer with 0 products = the search failed, not a fact.
    if ($isHealth && $count === 0 && empty($res['error'])) {
        $res['error'] = 'developer with 0 products found - likely a failed search';
    }

    return $res;
}

// ---------------------------------------------------------------------------
// Shared helper: send a prompt to Claude (with web search), return its JSON.
// ---------------------------------------------------------------------------
function ask_claude_json(string $prompt): array
{
    $apiKey = getenv('ANTHROPIC_API_KEY') ?: require '/etc/redflag/anthropic_key.php';
    if (!$apiKey) {
        throw new RuntimeException('ANTHROPIC_API_KEY is not set');
    }

    $messages = [['role' => 'user', 'content' => $prompt]];

    // A long web-search turn can occasionally pause; continue it (max 4 extra calls).
    for ($i = 0; $i < 5; $i++) {
        $data = call_claude_api($messages, $apiKey);
        if (($data['stop_reason'] ?? '') !== 'pause_turn') {
            break;
        }
        $messages[] = ['role' => 'assistant', 'content' => $data['content']];
    }

    $text = '';
    foreach ($data['content'] ?? [] as $block) {
        if (($block['type'] ?? '') === 'text') {
            $text .= $block['text'];
        }
    }
    if (!preg_match('/\{.*\}/s', $text, $m)) {
        $why = $data['stop_reason'] ?? 'unknown';
        throw new RuntimeException("No JSON found (stop_reason: $why):\n$text");
    }
    $result = json_decode($m[0], true);
    if (!is_array($result)) {
        throw new RuntimeException("Could not parse JSON:\n{$m[0]}");
    }
    return $result;
}

function call_claude_api(array $messages, string $apiKey): array
{
    $payload = [
        'model'      => RF_MODEL,
        'max_tokens' => 4096,
        'tools'      => [[
            'type'     => 'web_search_20250305',
            'name'     => 'web_search',
            'max_uses' => 3,
        ]],
        'messages'   => $messages,
    ];

    $ch = curl_init(RF_API_URL);
    curl_setopt_array($ch, [
        CURLOPT_POST           => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 90,
        CURLOPT_HTTPHEADER     => [
            'x-api-key: ' . $apiKey,
            'anthropic-version: 2023-06-01',
            'content-type: application/json',
        ],
        CURLOPT_POSTFIELDS     => json_encode($payload),
    ]);

    $body = curl_exec($ch);
    if ($body === false) {
        $err = curl_error($ch);
        curl_close($ch);
        throw new RuntimeException("curl error: $err");
    }
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    $data = json_decode($body, true);
    if ($status !== 200 || !is_array($data)) {
        throw new RuntimeException("API returned HTTP $status: $body");
    }
    return $data;
}

// ---------------------------------------------------------------------------
// Command-line test (skipped when this file is require()'d by the scanner)
// ---------------------------------------------------------------------------
if (PHP_SAPI === 'cli' && isset($argv[0]) && realpath($argv[0]) === __FILE__) {
    if (count($argv) !== 2) {
        fwrite(STDERR, "Usage: php red-flag-check.php TICKER\n");
        exit(1);
    }

    $result = run_red_flag_checks($argv[1]);
    echo json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES), "\n\n";

    if ($result['has_red_flags']) {
        echo "*** RED FLAGS on {$result['ticker']} ***\n";
        foreach ($result['flags'] as $f) {
            echo "  - $f\n";
        }
    } else {
        echo "No red flags found on {$result['ticker']}\n";
    }

    // Surface any check that errored out, so a failure isn't mistaken for "clean".
    foreach ($result['details'] as $name => $d) {
        if (!empty($d['error'])) {
            echo "  ! $name check ERROR: {$d['error']}\n";
        }
    }
}