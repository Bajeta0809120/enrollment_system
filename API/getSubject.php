<?php
header('Content-Type: application/json');
require 'db.php';

try {
    $stmt = $pdo->query("
        SELECT sub.id, sub.name AS subject_name, sem.semester_name, y.year_name
        FROM subjects sub
        JOIN semesters sem ON sub.semester_id = sem.id
        JOIN years y ON sem.year_id = y.id
        ORDER BY y.year_name, sem.semester_name, sub.name
    ");
    $subjects = $stmt->fetchAll();

    echo json_encode(['success' => true, 'data' => $subjects]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
