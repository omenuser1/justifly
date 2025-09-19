<?php
require_once 'config.php';
session_start();

// التحقق من تسجيل دخول الأدمن
if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'admin') {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Non autorisé']);
    exit();
}

$teacher_id = $_GET['id'] ?? 0;

if (!$teacher_id) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'ID manquant']);
    exit();
}

// جلب بيانات المعلم
$stmt = $conn->prepare("
    SELECT t.*, u.username, u.email, u.sub_department_id
    FROM teachers t
    JOIN users u ON t.user_id = u.id
    WHERE t.id = ? AND u.department_id = ?
");
$stmt->bind_param("ii", $teacher_id, $_SESSION['department_id']);
$stmt->execute();
$teacher = $stmt->get_result()->fetch_assoc();

if ($teacher) {
    header('Content-Type: application/json');
    echo json_encode($teacher);
} else {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Enseignant non trouvé']);
}
?>