<?php
header('Content-Type: application/json');
require 'db.php';

$data = json_decode(file_get_contents('php://input'), true);

if (empty($data['enrollment_id']) || empty($data['subject_id'])) {
    echo json_encode(['success' => false, 'message' => 'Missing enrollment ID or subject ID']);
    exit;
}


$enrollment_id = (int) $data['enrollment_id'];
$new_subject_id = (int) $data['subject_id'];

try {
    $check = $pdo->prepare("SELECT * FROM enrollments WHERE id = ?");
    $check->execute([$enrollment_id]);
    if ($check->rowCount() === 0) {
        echo json_encode(['success' => false, 'message' => 'Enrollment not found']);
        exit;
    }

    $stmt = $pdo->prepare("UPDATE enrollments SET subject_id = ? WHERE id = ?");
    $stmt->execute([$new_subject_id, $enrollment_id]);

    echo json_encode(['success' => true, 'message' => 'Enrollment updated successfully']);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
