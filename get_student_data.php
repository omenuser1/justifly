<?php
require_once 'config.php';
session_start();

// التحقق من تسجيل دخول الأدمن
if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'admin') {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Non autorisé']);
    exit();
}

$student_id = $_GET['id'] ?? 0;

if (!$student_id) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'ID manquant']);
    exit();
}

// جلب بيانات الطالب
$stmt = $conn->prepare("
    SELECT s.*, u.username, u.email, u.sub_department_id
    FROM students s
    JOIN users u ON s.user_id = u.id
    WHERE s.id = ? AND u.department_id = ?
");
$stmt->bind_param("ii", $student_id, $_SESSION['department_id']);
$stmt->execute();
$student = $stmt->get_result()->fetch_assoc();

if ($student) {
    header('Content-Type: application/json');
    echo json_encode($student);
} else {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Étudiant non trouvé']);
}
?>