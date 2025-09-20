<?php
header('Content-Type: application/json');
require 'db.php';

// Read JSON input
$data = json_decode(file_get_contents('php://input'), true);

if (empty($data['id'])) {
    echo json_encode(['success' => false, 'message' => 'Missing student ID']);
    exit;
}

$id = (int) $data['id'];

try {
    // Optional: check if student exists first
    $check = $pdo->prepare("SELECT * FROM students WHERE id = ?");
    $check->execute([$id]);
    if ($check->rowCount() === 0) {
        echo json_encode(['success' => false, 'message' => 'Student not found']);
        exit;
    }

    // Delete student
    $stmt = $pdo->prepare("DELETE FROM students WHERE id = ?");
    $stmt->execute([$id]);

    echo json_encode(['success' => true, 'message' => 'Student deleted successfully']);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Failed to delete student: ' . $e->getMessage()]);
}
