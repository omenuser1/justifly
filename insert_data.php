<?php
require_once 'db_connect.php'; // تأكد من وجود ملف db_connect.php

// إعداد كلمة المرور الافتراضية
$default_password = password_hash('Password123', PASSWORD_BCRYPT);
$department_id = 17; // استبدل هذا بـ department_id الصحيح لـ Département de Mathématiques et Informatique

// إدخال الطلاب
$students = [
    ['username' => 'mohamed.ali', 'full_name' => 'Mohamed Ali', 'email' => 'mohamed.ali@university.com', 'role' => 'student'],
    ['username' => 'aymen.saidani', 'full_name' => 'Aymen Saidani', 'email' => 'aymen.saidani@university.com', 'role' => 'student'],
    ['username' => 'sara.tayeb', 'full_name' => 'Sara Tayeb', 'email' => 'sara.tayeb@university.com', 'role' => 'student'],
    ['username' => 'said.chach', 'full_name' => 'Said Chach', 'email' => 'said.chach@university.com', 'role' => 'student']
];

$sql = "INSERT INTO users (username, password, role, department_id, email, full_name) VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);

if ($stmt) {
    foreach ($students as $student) {
        $stmt->bind_param("sssiss", $student['username'], $default_password, $student['role'], $department_id, $student['email'], $student['full_name']);
        if ($stmt->execute()) {
            echo "تم إدخال الطالب: {$student['full_name']}<br>";
        } else {
            echo "خطأ في إدخال الطالب: {$student['full_name']} - " . $stmt->error . "<br>";
        }
    }
    $stmt->close();
} else {
    echo "خطأ في إعداد الاستعلام للطلاب: " . $conn->error . "<br>";
}

// إدخال المعلمين
$teachers = [
    ['username' => 'amine.massoudi', 'full_name' => 'Amine Massoudi', 'email' => 'amine.massoudi@university.com', 'role' => 'teacher'],
    ['username' => 'jamal.benaribi', 'full_name' => 'Jamal Benaribi', 'email' => 'jamal.benaribi@university.com', 'role' => 'teacher']
];

$sql = "INSERT INTO users (username, password, role, department_id, email, full_name) VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);

if ($stmt) {
    foreach ($teachers as $teacher) {
        $stmt->bind_param("sssiss", $teacher['username'], $default_password, $teacher['role'], $department_id, $teacher['email'], $teacher['full_name']);
        if ($stmt->execute()) {
            echo "تم إدخال المعلم: {$teacher['full_name']}<br>";
        } else {
            echo "خطأ في إدخال المعلم: {$teacher['full_name']} - " . $stmt->error . "<br>";
        }
    }
    $stmt->close();
} else {
    echo "خطأ في إعداد الاستعلام للمعلمين: " . $conn->error . "<br>";
}

// إدخال المواد
$courses = [
    ['course_name' => 'Compilation', 'teacher_username' => 'amine.massoudi'],
    ['course_name' => 'Intelligence Artificielle', 'teacher_username' => null],
    ['course_name' => 'Développement Web', 'teacher_username' => null],
    ['course_name' => 'Décision Support Systems', 'teacher_username' => 'jamal.benaribi'],
    ['course_name' => 'Programmation Logique', 'teacher_username' => null]
];

$sql = "INSERT INTO courses (course_name, department_id, teacher_id) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);

if ($stmt) {
    foreach ($courses as $course) {
        // استرجاع teacher_id إذا كان المعلم محددًا
        $teacher_id = null;
        if ($course['teacher_username']) {
            $sql_teacher = "SELECT user_id FROM users WHERE username = ? AND role = 'teacher'";
            $stmt_teacher = $conn->prepare($sql_teacher);
            $stmt_teacher->bind_param("s", $course['teacher_username']);
            $stmt_teacher->execute();
            $result = $stmt_teacher->get_result();
            if ($result->num_rows > 0) {
                $teacher_id = $result->fetch_assoc()['user_id'];
            }
            $stmt_teacher->close();
        }

        $stmt->bind_param("sii", $course['course_name'], $department_id, $teacher_id);
        if ($stmt->execute()) {
            echo "تم إدخال المادة: {$course['course_name']}<br>";
        } else {
            echo "خطأ في إدخال المادة: {$course['course_name']} - " . $stmt->error . "<br>";
        }
    }
    $stmt->close();
} else {
    echo "خطأ في إعداد الاستعلام للمواد: " . $conn->error . "<br>";
}

$conn->close();
?>