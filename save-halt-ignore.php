<?php
header('Content-Type: application/json');

// --- Database credentials ---
$host = 'localhost';
$db   = 'daytrade';
$user = 'superuser';
$pass = 'heimer27';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed']);
    exit;
}

// --- Get JSON input ---
$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['symbol']) || empty($data['symbol'])) {
    echo json_encode(['status' => 'error', 'message' => 'No symbol provided']);
    exit;
}

$symbol = strtoupper(trim($data['symbol']));

// Optional: basic validation (only letters, numbers, dot)
if (!preg_match('/^[A-Z0-9\.]+$/', $symbol)) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid symbol format']);
    exit;
}

try {
    $stmt = $pdo->prepare("INSERT IGNORE INTO halts (symbol) VALUES (:symbol)");
    $stmt->execute(['symbol' => $symbol]);

    echo json_encode([
        'status' => 'success',
        'symbol' => $symbol
    ]);

} catch (PDOException $e) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Insert failed'
    ]);
}
