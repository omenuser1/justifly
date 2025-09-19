<?php
session_start();
require_once 'config.php';

header('Content-Type: application/json');

$response = ['success' => false, 'message' => '', 'redirect' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $role = trim($_POST['role'] ?? '');

    // تسجيل القيم المدخلة للتصحيح
    error_log("Login attempt: username=$username, role=$role");

    if (empty($username) || empty($password) || empty($role)) {
        $response['message'] = 'Veuillez remplir tous les champs.';
        echo json_encode($response);
        exit;
    }

    // التحقق من بيانات تسجيل الدخول
    $sql = "SELECT id, email, password, role, department_id, sub_department_id FROM users WHERE username = ? AND role = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        $response['message'] = 'Erreur de préparation de la requête: ' . $conn->error;
        error_log("SQL Prepare Error: " . $conn->error);
        echo json_encode($response);
        exit;
    }

    $stmt->bind_param("ss", $username, $role);
    if (!$stmt->execute()) {
        $response['message'] = 'Erreur d\'exécution de la requête: ' . $stmt->error;
        error_log("SQL Execute Error: " . $stmt->error);
        echo json_encode($response);
        exit;
    }

    $result = $stmt->get_result();
    error_log("Query returned " . $result->num_rows . " rows");
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        error_log("User found: " . json_encode($user));

        // التحقق من كلمة المرور
        if (password_verify($password, $user['password'])) {
            // تعيين بيانات الجلسة
            $_SESSION['loggedin'] = true;
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['department_id'] = $user['department_id'];
            $_SESSION['sub_department_id'] = $user['sub_department_id'];

            $response['success'] = true;
            $response['message'] = 'Connexion réussie!';
            // تحديد وجهة التوجيه بناءً على الدور
            if ($user['role'] === 'student') {
                $response['redirect'] = 'student_dashboard.php';
            } elseif ($user['role'] === 'teacher') {
                $response['redirect'] = 'teacher_dashboard.php';
            } elseif ($user['role'] === 'admin') {
                $response['redirect'] = 'admin_dashboard.php';
            }
        } else {
            $response['message'] = 'Mot de passe incorrect!';
            error_log("Password verification failed for username=$username");
        }
    } else {
        $response['message'] = 'Nom d\'utilisateur ou rôle incorrect!';
        error_log("No user found for username=$username and role=$role");
    }

    $stmt->close();
    $conn->close();
} else {
    $response['message'] = 'Méthode non autorisée.';
    error_log("Invalid request method: " . $_SERVER['REQUEST_METHOD']);
}

echo json_encode($response);
?>