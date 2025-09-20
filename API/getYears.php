<?php
header('Content-Type: application/json');
require 'db.php';

try {
    $stmt = $pdo->query("SELECT * FROM years ORDER BY year_name");
    $years = $stmt->fetchAll();

    echo json_encode(['success' => true, 'data' => $years]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
