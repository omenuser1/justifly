<?php
session_start();

// التحقق من أن المستخدم طالب ومسجل الدخول
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: index2.php");
    exit;
}

require_once 'db_connect.php';

// جلب بيانات الطالب (بالفرنسية)
$user_id = $_SESSION['user_id'];
$sql = "SELECT full_name FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$full_name = $user['full_name'];

// جلب الغيابات بناءً على الفصول الدراسية
$current_month = date('m');
$condition_date = ($current_month <= 8 && date('d') <= 30) ? "" : "AND absence_date > '2024-08-30'";

$sql_absences_premier = "SELECT absence_id, course_id, absence_date, justification_status, justification_envoyee, reason 
                        FROM absences 
                        WHERE student_id = ? 
                        AND (MONTH(absence_date) BETWEEN 9 AND 12 OR MONTH(absence_date) = 1) 
                        $condition_date";
$stmt_premier = $conn->prepare($sql_absences_premier);
$stmt_premier->bind_param("i", $user_id);
$stmt_premier->execute();
$absences_premier = $stmt_premier->get_result()->fetch_all(MYSQLI_ASSOC);

$sql_absences_deuxieme = "SELECT absence_id, course_id, absence_date, justification_status, justification_envoyee, reason 
                         FROM absences 
                         WHERE student_id = ? 
                         AND (MONTH(absence_date) BETWEEN 2 AND 8) 
                         $condition_date";
$stmt_deuxieme = $conn->prepare($sql_absences_deuxieme);
$stmt_deuxieme->bind_param("i", $user_id);
$stmt_deuxieme->execute();
$absences_deuxieme = $stmt_deuxieme->get_result()->fetch_all(MYSQLI_ASSOC);

$stmt->close();
$stmt_premier->close();
$stmt_deuxieme->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="fr" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord - Étudiant</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Tajawal', sans-serif;
            background-color: #f5f7fa;
        }
        .main-content {
            padding: 20px;
        }
        .student-info {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
        }
        .student-image {
            max-width: 200px;
            border-radius: 10px;
        }
        .table th, .table td {
            text-align: left;
        }
        .btn-custom {
            margin-right: 5px;
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
    <main class="main-content">
        <div class="container">
            <h2 class="text-center mb-4">Tableau de bord Étudiant</h2>
            <div class="student-info">
                <div class="student-details">
                    <p><strong>Nom complet:</strong> <?php echo htmlspecialchars($full_name); ?></p>
                    <div>
                        <button class="btn btn-primary btn-custom" onclick="window.location.href='modifier_profil.php';">Modifier le profil</button>
                        <button class="btn btn-danger btn-custom" onclick="window.location.href='logout.php';" 
                                style="background-color: #E94624; border-color: #E94624;">
                            Déconnexion
                        </button>
                    </div>
                </div>
                <img src="assets/images/SchoolScenne.gif" alt="Scène scolaire" class="student-image">
            </div>

            <h2 class="text-center mb-4">Mes absences (Premier semestre)</h2>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Matière</th>
                        <th>Motif</th>
                        <th>État</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($absences_premier)): ?>
                        <tr><td colspan="5" class="text-center">Aucune absence enregistrée.</td></tr>
                    <?php else: ?>
                        <?php foreach ($absences_premier as $absence): ?>
                            <tr id="row-<?php echo $absence['absence_id']; ?>">
                                <td><?php echo htmlspecialchars($absence['absence_date']); ?></td>
                                <td>
                                    <?php 
                                    $matieres = ['1' => 'Mathématiques', '2' => 'Physique', '3' => 'Programmation', '4' => 'Chimie'];
                                    echo isset($matieres[$absence['course_id']]) ? $matieres[$absence['course_id']] : 'Inconnue';
                                    ?>
                                </td>
                                <td><?php echo htmlspecialchars($absence['reason'] ?? 'Non spécifié'); ?></td>
                                <td>
                                    <?php
                                    $status = $absence['justification_status'];
                                    if ($status === 'pending') {
                                        echo '<span style="color:orange;">En attente</span>';
                                    } elseif ($status === 'approved') {
                                        echo '<span style="color:green;">Approuvé</span>';
                                    } elseif ($status === 'rejected') {
                                        echo '<span style="color:red;">Rejeté</span>';
                                    } else {
                                        echo htmlspecialchars($status);
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php if ($absence['justification_envoyee'] == 0): ?>
                                        <button onclick="envoyerJustification(<?php echo $absence['absence_id']; ?>)" class="btn btn-success btn-custom">Envoyer</button>
                                        <button onclick="window.location.href='modifier_justification.php?id=<?php echo $absence['absence_id']; ?>'" class="btn btn-warning btn-custom">Modifier</button>
                                        <button onclick="supprimerAbsence(<?php echo $absence['absence_id']; ?>)" class="btn btn-danger btn-custom">Supprimer</button>
                                    <?php else: ?>
                                        Non modifiable
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
            <a href="justification.php" class="btn btn-primary mb-4">Justifier une absence</a>

            <h2 class="text-center mb-4">Mes absences (Deuxième semestre)</h2>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Matière</th>
                        <th>Motif</th>
                        <th>État</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($absences_deuxieme)): ?>
                        <tr><td colspan="5" class="text-center">Aucune absence enregistrée.</td></tr>
                    <?php else: ?>
                        <?php foreach ($absences_deuxieme as $absence): ?>
                            <tr id="row-<?php echo $absence['absence_id']; ?>">
                                <td><?php echo htmlspecialchars($absence['absence_date']); ?></td>
                                <td>
                                    <?php 
                                    $matieres = ['1' => 'Mathématiques', '2' => 'Physique', '3' => 'Programmation', '4' => 'Chimie'];
                                    echo isset($matieres[$absence['course_id']]) ? $matieres[$absence['course_id']] : 'Inconnue';
                                    ?>
                                </td>
                                <td><?php echo htmlspecialchars($absence['reason'] ?? 'Non spécifié'); ?></td>
                                <td>
                                    <?php
                                    $status = $absence['justification_status'];
                                    if ($status === 'pending') {
                                        echo '<span style="color:orange;">En attente</span>';
                                    } elseif ($status === 'approved') {
                                        echo '<span style="color:green;">Approuvé</span>';
                                    } elseif ($status === 'rejected') {
                                        echo '<span style="color:red;">Rejeté</span>';
                                    } else {
                                        echo htmlspecialchars($status);
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php if ($absence['justification_envoyee'] == 0): ?>
                                        <button onclick="envoyerJustification(<?php echo $absence['absence_id']; ?>)" class="btn btn-success btn-custom">Envoyer</button>
                                        <button onclick="window.location.href='modifier_justification.php?id=<?php echo $absence['absence_id']; ?>'" class="btn btn-warning btn-custom">Modifier</button>
                                        <button onclick="supprimerAbsence(<?php echo $absence['absence_id']; ?>)" class="btn btn-danger btn-custom">Supprimer</button>
                                    <?php else: ?>
                                        Non modifiable
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
            <a href="justification.php" class="btn btn-primary mb-4">Justifier une absence</a>
        </div>
    </main>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function supprimerAbsence(absenceId) {
            if (confirm("Voulez-vous vraiment supprimer cette absence ?")) {
                fetch("supprimer_justification.php", {
                    method: "POST",
                    headers: {"Content-Type": "application/x-www-form-urlencoded"},
                    body: "id=" + absenceId
                })
                .then(response => response.text())
                .then(response => {
                    response = response.trim();
                    if (response === "success") {
                        alert("Absence supprimée avec succès !");
                        document.getElementById("row-" + absenceId).remove();
                    } else {
                        alert("Erreur lors de la suppression !");
                    }
                })
                .catch(error => {
                    alert("Une erreur s'est produite lors de la suppression !");
                });
            }
        }

        function envoyerJustification(absenceId) {
            if (confirm("Voulez-vous envoyer cette justification ?")) {
                fetch("envoyer_justification.php", {
                    method: "POST",
                    headers: {"Content-Type": "application/x-www-form-urlencoded"},
                    body: "id=" + absenceId
                })
                .then(response => response.text())
                .then(response => {
                    response = response.trim();
                    if (response === "success") {
                        alert("Justification envoyée avec succès !");
                        location.reload();
                    } else {
                        alert("Erreur lors de l'envoi !");
                    }
                })
                .catch(error => {
                    alert("Une erreur s'est produite lors de l'envoi !");
                });
            }
        }
    </script>