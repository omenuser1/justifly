<?php
require_once 'config.php';
session_start();

// حفظ دور المستخدم قبل تدمير الجلسة
$user_role = isset($_SESSION['role']) ? $_SESSION['role'] : '';

// تدمير جميع بيانات الجلسة
session_unset();
session_destroy();

// حذف ملفات الكوكيز
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 3600, '/');
}

// إعادة التوجيه بناءً على دور المستخدم
switch ($user_role) {
    case 'admin':
        header("Location: admin_login.php");
        break;
    case 'teacher':
        header("Location: loggin-e.php");
        break;
    case 'student':
        header("Location: loggin.php");
        break;
    default:
        header("Location: index2.php");
        break;
}
exit();
?>