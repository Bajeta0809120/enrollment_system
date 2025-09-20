<?php
header('Content-Type: application/json');
require 'db.php';

$data = json_decode(file_get_contents('php://input'), true);

if (empty($data['enrollment_id'])) {
    echo json_encode(['success' => false, 'message' => 'Missing enrollment ID']);
    exit;
}

$enrollment_id = (int) $data['enrollment_id'];

try {
    $stmt = $pdo->prepare("DELETE FROM enrollments WHERE id = ?");
    $stmt->execute([$enrollment_id]);

    echo json_encode(['success' => true, 'message' => 'Enrollment removed successfully']);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
