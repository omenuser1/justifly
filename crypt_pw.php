<?php
$password = "1234"; // كلمة المرور الأصلية
echo password_hash($password, PASSWORD_DEFAULT);
?>