<?php
header('Content-Type: application/json');
require 'db.php';

$data = json_decode(file_get_contents('php://input'), true);

if (empty($data['name']) || empty($data['semester_id'])) {
    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
    exit;
}

$name = trim($data['name']);
$semester_id = (int) $data['semester_id'];

try {
    $stmt = $pdo->prepare("INSERT INTO subjects (name, semester_id) VALUES (?, ?)");
    $stmt->execute([$name, $semester_id]);

    echo json_encode(['success' => true, 'message' => 'Subject added successfully']);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
