<?php
require_once 'db_connect.php';

$sql = "SELECT department_id, department_name FROM departments";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<h2>الأقسام المتوفرة:</h2>";
    while ($row = $result->fetch_assoc()) {
        echo "ID: " . $row['department_id'] . " - " . $row['department_name'] . "<br>";
    }
} else {
    echo "لا توجد أقسام.";
}

$conn->close();
?>