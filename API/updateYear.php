<?php
header('Content-Type: application/json');
require 'db.php';

$data = json_decode(file_get_contents('php://input'), true);

if (empty($data['id']) || empty($data['year_name'])) {
    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
    exit;
}

$id = (int) $data['id'];
$year_name = trim($data['year_name']);

try {
    $check = $pdo->prepare("SELECT * FROM years WHERE id = ?");
    $check->execute([$id]);
    if ($check->rowCount() === 0) {
        echo json_encode(['success' => false, 'message' => 'Year not found']);
        exit;
    }

    $stmt = $pdo->prepare("UPDATE years SET year_name = ? WHERE id = ?");
    $stmt->execute([$year_name, $id]);

    echo json_encode(['success' => true, 'message' => 'Year updated successfully']);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
