<?php
header('Content-Type: application/json');
require 'db.php';

$data = json_decode(file_get_contents('php://input'), true);

if (empty($data['id'])) {
    echo json_encode(['success' => false, 'message' => 'Missing program ID']);
    exit;
}

$id = (int) $data['id'];

try {
    // Check if any student is enrolled in this program
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM students WHERE program_id = ?");
    $stmt->execute([$id]);
    if ($stmt->fetchColumn() > 0) {
        echo json_encode(['success' => false, 'message' => 'Cannot delete program: students are enrolled']);
        exit;
    }

    // Delete program
    $stmt = $pdo->prepare("DELETE FROM programs WHERE id = ?");
    $stmt->execute([$id]);

    echo json_encode(['success' => true, 'message' => 'Program deleted successfully']);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
