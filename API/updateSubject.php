<?php
header('Content-Type: application/json');
require 'db.php';

$data = json_decode(file_get_contents('php://input'), true);

if (empty($data['id']) || empty($data['name']) || empty($data['semester_id'])) {
    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
    exit;
}

$id = (int) $data['id'];
$name = trim($data['name']);
$semester_id = (int) $data['semester_id'];

try {
    $check = $pdo->prepare("SELECT * FROM subjects WHERE id = ?");
    $check->execute([$id]);
    if ($check->rowCount() === 0) {
        echo json_encode(['success' => false, 'message' => 'Subject not found']);
        exit;
    }

    $stmt = $pdo->prepare("UPDATE subjects SET name = ?, semester_id = ? WHERE id = ?");
    $stmt->execute([$name, $semester_id, $id]);

    echo json_encode(['success' => true, 'message' => 'Subject updated successfully']);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
