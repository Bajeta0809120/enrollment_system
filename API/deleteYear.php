<?php
header('Content-Type: application/json');
require 'db.php';

$data = json_decode(file_get_contents('php://input'), true);

if (empty($data['id'])) {
    echo json_encode(['success' => false, 'message' => 'Missing year ID']);
    exit;
}

$id = (int) $data['id'];

try {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM semesters WHERE year_id = ?");
    $stmt->execute([$id]);
    if ($stmt->fetchColumn() > 0) {
        echo json_encode(['success' => false, 'message' => 'Cannot delete year: semesters exist']);
        exit;
    }

    $stmt2 = $pdo->prepare("SELECT COUNT(*) FROM students WHERE year_id = ?");
    $stmt2->execute([$id]);
    if ($stmt2->fetchColumn() > 0) {
        echo json_encode(['success' => false, 'message' => 'Cannot delete year: students enrolled']);
        exit;
    }

    $stmt3 = $pdo->prepare("DELETE FROM years WHERE id = ?");
    $stmt3->execute([$id]);

    echo json_encode(['success' => true, 'message' => 'Year deleted successfully']);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
