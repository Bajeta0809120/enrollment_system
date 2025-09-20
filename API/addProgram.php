<?php
header('Content-Type: application/json');
require 'db.php';

$data = json_decode(file_get_contents('php://input'), true);

if (empty($data['name']) || empty($data['institute_id'])) {
    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
    exit;
}

$name = trim($data['name']);
$institute_id = (int) $data['institute_id'];

try {
    $stmt = $pdo->prepare("INSERT INTO programs (name, institute_id) VALUES (?, ?)");
    $stmt->execute([$name, $institute_id]);

    echo json_encode(['success' => true, 'message' => 'Program added successfully']);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
