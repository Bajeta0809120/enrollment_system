<?php
header('Content-Type: application/json');
require 'db.php';

try {
    $stmt = $pdo->query("
        SELECT p.id, p.name, i.name AS institute 
        FROM programs p
        LEFT JOIN institutes i ON p.institute_id = i.id
        ORDER BY p.name
    ");
    $programs = $stmt->fetchAll();

    echo json_encode(['success' => true, 'data' => $programs]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
