<?php
session_start();
require_once 'db_connect.php';

header('Content-Type: application/json');

$response = ['success' => false, 'message' => ''];

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    $response['message'] = 'Non autorisé.';
    echo json_encode($response);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $absence_id = $_POST['absence_id'] ?? '';
    $file = $_FILES['justification_file'] ?? null;

    if (empty($absence_id) || !$file) {
        $response['message'] = 'Veuillez sélectionner un fichier et une absence.';
        echo json_encode($response);
        exit;
    }

    // التحقق من أن الغياب ينتمي للطالب
    $sql = "SELECT absence_id FROM absences WHERE absence_id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $absence_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        $response['message'] = 'Absence invalide.';
        echo json_encode($response);
        $stmt->close();
        exit;
    }
    $stmt->close();

    // معالجة الملف
    $upload_dir = 'Uploads/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $file_name = time() . '_' . basename($file['name']);
    $file_path = $upload_dir . $file_name;
    $allowed_types = ['application/pdf', 'image/jpeg', 'image/png'];

    if (!in_array($file['type'], $allowed_types)) {
        $response['message'] = 'Type de fichier non autorisé. Veuillez uploader un PDF ou une image (JPG/PNG).';
        echo json_encode($response);
        exit;
    }

    if ($file['size'] > 5 * 1024 * 1024) { // 5MB limit
        $response['message'] = 'Le fichier est trop volumineux (max 5MB).';
        echo json_encode($response);
        exit;
    }

    if (move_uploaded_file($file['tmp_name'], $file_path)) {
        // إدخال التبرير في قاعدة البيانات
        $sql = "INSERT INTO justifications (user_id, absence_id, file_path, submission_date, status) VALUES (?, ?, ?, NOW(), 'pending')";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iis", $user_id, $absence_id, $file_path);
        
        if ($stmt->execute()) {
            // تحديث حالة الغياب
            $sql_update = "UPDATE absences SET justification_status = 'pending' WHERE absence_id = ?";
            $stmt_update = $conn->prepare($sql_update);
            $stmt_update->bind_param("i", $absence_id);
            $stmt_update->execute();
            $stmt_update->close();
            
            $response['success'] = true;
            $response['message'] = 'Justificatif soumis avec succès!';
        } else {
            $response['message'] = 'Erreur lors de l\'enregistrement du justificatif.';
            unlink($file_path); // حذف الملف إذا فشل الإدخال
        }
        $stmt->close();
    } else {
        $response['message'] = 'Erreur lors de l\'upload du fichier.';
    }

    $conn->close();
} else {
    $response['message'] = 'Méthode non autorisée.';
}

echo json_encode($response);
?>