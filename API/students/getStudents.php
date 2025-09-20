<?php
header('Content-Type: application/json');
require 'db.php';

try {
    $stmt = $pdo->query("
        SELECT s.id, s.name, p.name AS program_name, y.school_year AS year, sem.semester_name AS semester, s.allowance
        FROM students s
        JOIN programs p ON s.program_id = p.id
        JOIN years y ON s.year_id = y.id
        JOIN semesters sem ON s.semester_id = sem.id
    ");

    $students = $stmt->fetchAll();

    // Optionally, get subjects per student here or via separate endpoint

    echo json_encode(['success' => true, 'data' => $students]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
