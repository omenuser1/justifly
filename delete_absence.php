<?php
require_once 'config.php';
session_start();

// التحقق من تسجيل دخول الطالب
if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'student') {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Non autorisé']);
    exit();
}

$user_id = $_SESSION['user_id'];

// التحقق من أن الطلب هو POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
    exit();
}

// جلب بيانات الطالب
$stmt = $conn->prepare("SELECT id FROM students WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$student = $stmt->get_result()->fetch_assoc();

if (!$student) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Étudiant non trouvé']);
    exit();
}

// جلب معرف الغياب من الطلب
$absence_id = $_POST['id'] ?? 0;

if (!$absence_id) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'ID d\'absence manquant']);
    exit();
}

// التحقق من وجود الغياب وأنه يمكن حذفه
$stmt = $conn->prepare("
    SELECT * FROM absences 
    WHERE id = ? AND student_id = ? AND status = 'pending'
");
$stmt->bind_param("ii", $absence_id, $student['id']);
$stmt->execute();
$absence = $stmt->get_result()->fetch_assoc();

if (!$absence) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Absence non trouvée ou ne peut pas être supprimée']);
    exit();
}

// حذف ملف التبرير إذا كان موجودًا
if ($absence['justification_document']) {
    $file_path = 'uploads/' . $absence['justification_document'];
    if (file_exists($file_path)) {
        unlink($file_path);
    }
}

// حذف الغياب من قاعدة البيانات
$stmt = $conn->prepare("DELETE FROM absences WHERE id = ? AND student_id = ?");
$stmt->bind_param("ii", $absence_id, $student['id']);

if ($stmt->execute()) {
    header('Content-Type: application/json');
    echo json_encode(['success' => true, 'message' => 'Absence supprimée avec succès']);
} else {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Erreur lors de la suppression de l\'absence']);
}

$stmt->close();
$conn->close();
?>