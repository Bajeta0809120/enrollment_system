<?php
header('Content-Type: application/json');
require 'db.php';

// Get JSON input
$data = json_decode(file_get_contents('php://input'), true);

if (
    empty($data['id']) || 
    empty($data['name']) || 
    empty($data['program']) || 
    empty($data['year']) || 
    empty($data['semester']) || 
    !isset($data['allowance'])
) {
    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
    exit;
}

$id = (int) $data['id'];
$name = trim($data['name']);
$program_id = (int) $data['program'];
$year_id = (int) $data['year'];
$semester_id = (int) $data['semester'];
$allowance = (float) $data['allowance'];

try {
    // Check if student exists
    $check = $pdo->prepare("SELECT * FROM students WHERE id = ?");
    $check->execute([$id]);
    if ($check->rowCount() === 0) {
        echo json_encode(['success' => false, 'message' => 'Student not found']);
        exit;
    }

    // Update student record
    $stmt = $pdo->prepare("UPDATE students SET name = ?, program_id = ?, year_id = ?, semester_id = ?, allowance = ? WHERE id = ?");
    $stmt->execute([$name, $program_id, $year_id, $semester_id, $allowance, $id]);

    echo json_encode(['success' => true, 'message' => 'Student updated successfully']);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Failed to update student: ' . $e->getMessage()]);
}
