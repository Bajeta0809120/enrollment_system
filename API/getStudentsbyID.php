<?php
header('Content-Type: application/json');
require_once 'db.php';

try {
    $stmt = $pdo->prepare("
        SELECT s.stud_id, s.stud_name, p.prog_name, y.year_name, sem.semester_name, s.allowance
        FROM students s
        JOIN programs p ON s.prog_id = p.prog_id
        JOIN years y ON s.year_id = y.year_id
        JOIN semesters sem ON s.semester_id = sem.semester_id
        ORDER BY s.stud_id ASC
    ");
    $stmt->execute();
    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['success' => true, 'data' => $students]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
