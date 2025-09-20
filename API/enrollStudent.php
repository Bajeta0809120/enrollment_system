<?php
header('Content-Type: application/json');
require 'db.php';

$data = json_decode(file_get_contents('php://input'), true);

if (empty($data['student_id']) || empty($data['subject_id'])) {
    echo json_encode(['success' => false, 'message' => 'Missing student or subject ID']);
    exit;
}

$student_id = (int) $data['student_id'];
$subject_id = (int) $data['subject_id'];

try {
    $check = $pdo->prepare("SELECT * FROM enrollments WHERE student_id = ? AND subject_id = ?");
    $check->execute([$student_id, $subject_id]);
    if ($check->rowCount() > 0) {
        echo json_encode(['success' => false, 'message' => 'Student already enrolled in this subject']);
        exit;
    }

    $stmt = $pdo->prepare("INSERT INTO enrollments (student_id, subject_id) VALUES (?, ?)");
    $stmt->execute([$student_id, $subject_id]);

    echo json_encode(['success' => true, 'message' => 'Student enrolled successfully']);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
