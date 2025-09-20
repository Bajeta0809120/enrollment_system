<?php
header('Content-Type: application/json');
require 'db.php';

$data = json_decode(file_get_contents('php://input'), true);

if (empty($data['id'])) {
    echo json_encode(['success' => false, 'message' => 'Missing semester ID']);
    exit;
}

$id = (int) $data['id'];

try {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM subjects WHERE semester_id = ?");
    $stmt->execute([$id]);
    if ($stmt->fetchColumn() > 0) {
        echo json_encode(['success' => false, 'message' => 'Cannot delete semester: subjects exist']);
        exit;
    }

    $stmt2 = $pdo->prepare("DELETE FROM semesters WHERE id = ?");
    $stmt2->execute([$id]);

    echo json_encode(['success' => true, 'message' => 'Semester deleted successfully']);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
