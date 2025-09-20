<?php
header('Content-Type: application/json');
require 'db.php';

try {
    $stmt = $pdo->query("
        SELECT s.id, s.semester_name, y.year_name
        FROM semesters s
        JOIN years y ON s.year_id = y.id
        ORDER BY y.year_name, s.semester_name
    ");
    $semesters = $stmt->fetchAll();

    echo json_encode(['success' => true, 'data' => $semesters]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
