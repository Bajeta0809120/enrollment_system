<?php
header('Content-Type: application/json');
require 'db.php';

// Get the JSON input and decode it
$data = json_decode(file_get_contents('php://input'), true);

if (
    empty($data['name']) || 
    empty($data['program']) || 
    empty($data['year']) || 
    empty($data['semester']) || 
    !isset($data['allowance'])
) {
    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
    exit;
}

$name = trim($data['name']);
$program_id = (int) $data['program'];
$year_id = (int) $data['year'];
$semester_id = (int) $data['semester'];
$allowance = (float) $data['allowance'];

try {
    // Insert new student
    $stmt = $pdo->prepare("INSERT INTO students (name, program_id, year_id, semester_id, allowance) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$name, $program_id, $year_id, $semester_id, $allowance]);

    echo json_encode(['success' => true, 'message' => 'Student added successfully']);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Failed to add student: ' . $e->getMessage()]);
}
