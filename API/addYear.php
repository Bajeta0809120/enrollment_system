<?php
header('Content-Type: application/json');
require 'db.php';

$data = json_decode(file_get_contents('php://input'), true);

if (empty($data['year_name'])) {
    echo json_encode(['success' => false, 'message' => 'Missing year name']);
    exit;
}

$year_name = trim($data['year_name']);

try {
    $stmt = $pdo->prepare("INSERT INTO years (year_name) VALUES (?)");
    $stmt->execute([$year_name]);

    echo json_encode(['success' => true, 'message' => 'Year added successfully']);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
