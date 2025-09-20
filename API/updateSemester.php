<?php
header('Content-Type: application/json');
require 'db.php';

$data = json_decode(file_get_contents('php://input'), true);

if (empty($data['id']) || empty($data['semester_name']) || empty($data['year_id'])) {
    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
    exit;
}

$id = (int) $data['id'];
$semester_name = trim($data['semester_name']);
$year_id = (int) $data['year_id'];

try {
    $check = $pdo->prepare("SELECT * FROM semesters WHERE id = ?");
    $check->execute([$id]);
    if ($check->rowCount() === 0) {
        echo json_encode(['success' => false, 'message' => 'Semester not found']);
        exit;
    }

    $stmt = $pdo->prepare("UPDATE semesters SET semester_name = ?, year_id = ? WHERE id = ?");
    $stmt->execute([$semester_name, $year_id, $id]);

    echo json_encode(['success' => true, 'message' => 'Semester updated successfully']);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
