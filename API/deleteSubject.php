<?php
header('Content-Type: application/json');
require 'db.php';

$data = json_decode(file_get_contents('php://input'), true);

if (empty($data['id'])) {
    echo json_encode(['success' => false, 'message' => 'Missing subject ID']);
    exit;
}

$id = (int) $data['id'];

try {
    // Check if any enrollments exist for this subject
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM enrollments WHERE subject_id = ?");
    $stmt->execute([$id]);
    if ($stmt->fetchColumn() > 0) {
        echo json_encode(['success' => false, 'message' => 'Cannot delete subject: students are enrolled']);
        exit;
    }

    // Delete subject
    $stmt2 = $pdo->prepare("DELETE FROM subjects WHERE id = ?");
    $stmt2->execute([$id]);

    echo json_encode(['success' => true, 'message' => 'Subject deleted successfully']);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
