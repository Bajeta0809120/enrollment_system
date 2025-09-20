<?php
header('Content-Type: application/json');
require 'db.php';

$data = json_decode(file_get_contents('php://input'), true);

if (empty($data['semester_name']) || empty($data['year_id'])) {
    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
    exit;
}

$semester_name = trim($data['semester_name']);
$year_id = (int) $data['year_id'];

try {
    $stmt = $pdo->prepare("INSERT INTO semesters (semester_name, year_id) VALUES (?, ?)");
    $stmt->execute([$semester_name, $year_id]);

    echo json_encode(['success' => true, 'message' => 'Semester added successfully']);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
