
<?php

// إعداد الاتصال بقاعدة البيانات
$host = 'localhost';
$db_name = 'university_belhadjebouchib_db';
$username = 'root'; // مستخدم MySQL الافتراضي في XAMPP
$password = ''; // كلمة المرور الافتراضية فارغة في XAMPP

$conn = new mysqli($host, $username, $password, $db_name);

// التحقق من الاتصال
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// إعداد الترميز لدعم اللغة العربية
$conn->set_charset("utf8mb4");
?>