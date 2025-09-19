<?php
require_once 'db_connect.php'; // تأكد من وجود ملف db_connect.php

// إعداد بيانات الأدمن
$admin = [
    'username' => 'khaled.benissa',
    'full_name' => 'Khaled Benissa',
    'email' => 'khaled.benissa@university.com',
    'role' => 'admin',
    'department_id' => 17 // استبدل هذا بـ department_id الصحيح
];

// إعداد كلمة المرور الافتراضية
$password = password_hash('Admin123', PASSWORD_BCRYPT);

// إدخال الأدمن إلى جدول users
$sql = "INSERT INTO users (username, password, role, department_id, email, full_name) VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);

if ($stmt) {
    $stmt->bind_param("sssiss", $admin['username'], $password, $admin['role'], $admin['department_id'], $admin['email'], $admin['full_name']);
    if ($stmt->execute()) {
        echo "تم إدخال الأدمن: {$admin['full_name']} بنجاح<br>";
    } else {
        echo "خطأ في إدخال الأدمن: {$admin['full_name']} - " . $stmt->error . "<br>";
    }
    $stmt->close();
} else {
    echo "خطأ في إعداد الاستعلام: " . $conn->error . "<br>";
}

$conn->close();
?>