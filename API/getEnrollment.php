<?php
header('Content-Type: application/json');
require 'db.php';

try {
    $stmt = $pdo->query("
        SELECT e.id, s.name AS student_name, sub.name AS subject_name, p.name AS program_name,
               y.year_name, sem.semester_name
        FROM enrollments e
        JOIN students s ON e.student_id = s.id
        JOIN subjects sub ON e.subject_id = sub.id
        JOIN programs p ON s.program_id = p.id
        JOIN years y ON s.year_id = y.id
        JOIN semesters sem ON sub.semester_id = sem.id
        ORDER BY s.name, sub.name
    ");

    $enrollments = $stmt->fetchAll();

    echo json_encode(['success' => true, 'data' => $enrollments]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
