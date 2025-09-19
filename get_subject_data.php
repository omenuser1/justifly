<?php
require_once 'config.php';
session_start();

// التحقق من تسجيل دخول الأدمن
if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'admin') {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Non autorisé']);
    exit();
}

$subject_id = $_GET['id'] ?? 0;

if (!$subject_id) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'ID manquant']);
    exit();
}

// جلب بيانات المادة
$stmt = $conn->prepare("
    SELECT s.*, sd.name as sub_department_name
    FROM subjects s
    JOIN sub_departments sd ON s.sub_department_id = sd.id
    WHERE s.id = ? AND sd.department_id = ?
");
$stmt->bind_param("ii", $subject_id, $_SESSION['department_id']);
$stmt->execute();
$subject = $stmt->get_result()->fetch_assoc();

if ($subject) {
    header('Content-Type: application/json');
    echo json_encode($subject);
} else {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Matière non trouvée']);
}
?>