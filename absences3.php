<?php
require_once 'Database.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Récupérer les informations de l'étudiant
$stmt = $pdo->prepare("SELECT nom, prenom, email, matricule, username FROM users WHERE id = :user_id");
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$student = $stmt->fetch(PDO::FETCH_ASSOC);

// Vérifier la date actuelle
$mois_actuel = date('m');
$jour_actuel = date('d');

// Appliquer la condition pour masquer après le 30 août
$condition_date = ($mois_actuel <= 8 && $jour_actuel <= 30) ? "" : "AND date_debut > '2024-08-30'";

// Absences du **premier semestre** (septembre - janvier) **OU** si elles finissent dans هذه الفترة
$stmt = $pdo->prepare("
    SELECT * FROM absences 
    WHERE user_id = :user_id 
    AND (
        (MONTH(date_debut) BETWEEN 9 AND 12 OR MONTH(date_debut) = 1) 
        OR (MONTH(date_fin) BETWEEN 9 AND 12 OR MONTH(date_fin) = 1)
    ) 
    $condition_date
");
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$absences_premier_semestre = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Absences du **deuxième semestre** (février - août) **OU** si elles finissent في هذه الفترة
$stmt = $pdo->prepare("
    SELECT * FROM absences 
    WHERE user_id = :user_id 
    AND (
        (MONTH(date_debut) BETWEEN 2 AND 8) 
        OR (MONTH(date_fin) BETWEEN 2 AND 8)
    ) 
    $condition_date
");
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$absences_deuxieme_semestre = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des absences - Étudiant</title>
    <!-- Local Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <!-- Custom Styles -->
    <link rel="stylesheet" href="css/absences3.css">
</head>
<body>
   
    <main class="main-content">
        <div class="container">
        <h2 class="text-center">Informations de l'étudiant</h2>
<div class="student-info">
    <div class="student-details">
        <p><strong>Nom:</strong> <?php echo htmlspecialchars($student['nom']); ?></p>
        <p><strong>Prénom:</strong> <?php echo htmlspecialchars($student['prenom']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($student['email']); ?></p>
        <p><strong>Matricule:</strong> <?php echo htmlspecialchars($student['matricule']); ?></p>
        <p><strong>Nom d'utilisateur:</strong> <?php echo htmlspecialchars($student['username']); ?></p>
            <div style="display: inline-block; margin-top: 15px;">
        <button class="btn btn-primary" onclick="window.location.href='modifier_profil.php';" 
                style="margin-right: 10px;">Modifier le profil</button>
        <button class="btn btn-danger" onclick="window.location.href='logout_etudiant.php';"
                style="background-color: #E94624; border-color: #E94624; color: white; 
                       padding: 0.75rem 1.5rem; border-radius: 0.8rem; font-size: 1rem;
                       cursor: pointer; transition: all 0.3s;">
            Déconnexion
        </button>
    </div>
    </div>
    <img src="assets/images/SchoolScenne.gif" alt="School Scene" class="student-image">
</div>

            <h2 class="text-center">Mes Absences (Premier semestre)</h2>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Date de</th>
                        <th>Date à</th>
                        <th>Justification</th>
                        <th>État</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($absences_premier_semestre)): ?>
                        <tr><td colspan="5" style="text-align:center;">Aucune absence enregistrée.</td></tr>
                    <?php else: ?>
                        <?php foreach ($absences_premier_semestre as $absence): ?>
                            <tr id="row-<?php echo $absence['id']; ?>">
                                <td><?php echo htmlspecialchars($absence['date_debut']); ?></td>
                                <td><?php echo htmlspecialchars($absence['date_fin']); ?></td>
                                <td>
                                    <?php if (!empty($absence['justification_file'])): ?>
                                        <a href="uploads/<?php echo htmlspecialchars($absence['justification_file']); ?>" download class="btn btn-secondary">Télécharger</a>
                                    <?php else: ?>
                                        Pas de fichier
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php
                                    if ($absence['justification_envoyee'] == 0) {
                                        echo "Brouillon";
                                    } elseif ($absence['etat'] == 2) {
                                        echo "<span style='color:green;'>Favorable</span>";
                                    } elseif ($absence['etat'] == 3) {
                                        echo "<span style='color:red;'>Défavorable</span>";
                                    } elseif ($absence['etat'] == 4) {
                                        echo "<span style='color:orange;'>Ramener la justification à l'administration</span>";
                                    } else {
                                        echo "Envoyé";
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php if ($absence['justification_envoyee'] == 0): ?>
                                        <button onclick="envoyerJustification(<?php echo $absence['id']; ?>)" class="btn btn-success">Envoyer</button>
                                        <button onclick="window.location.href='modifier_justification.php?id=<?php echo $absence['id']; ?>'" class="btn btn-warning">modifier</button>
                                        <button onclick="supprimerAbsence(<?php echo $absence['id']; ?>)" class="btn btn-danger">Supprimer</button>
                                    <?php else: ?>
                                        Non modifiable
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
            <a href="justification.php" class="btn btn-primary">Justifier une absence</a>

            <h2 class="text-center">Mes Absences (Deuxième semestre)</h2>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Date de</th>
                        <th>Date à</th>
                        <th>Justification</th>
                        <th>État</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($absences_deuxieme_semestre)): ?>
                        <tr><td colspan="5" style="text-align:center;">Aucune absence enregistrée.</td></tr>
                    <?php else: ?>
                        <?php foreach ($absences_deuxieme_semestre as $absence): ?>
                            <tr id="row-<?php echo $absence['id']; ?>">
                                <td><?php echo htmlspecialchars($absence['date_debut']); ?></td>
                                <td><?php echo htmlspecialchars($absence['date_fin']); ?></td>
                                <td>
                                    <?php if (!empty($absence['justification_file'])): ?>
                                        <a href="uploads/<?php echo htmlspecialchars($absence['justification_file']); ?>" download class="btn btn-secondary">Télécharger</a>
                                    <?php else: ?>
                                        Pas de fichier
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php
                                    if ($absence['justification_envoyee'] == 0) {
                                        echo "Brouillon";
                                    } elseif ($absence['etat'] == 2) {
                                        echo "<span style='color:green;'>Favorable</span>";
                                    } elseif ($absence['etat'] == 3) {
                                        echo "<span style='color:red;'>Défavorable</span>";
                                    } elseif ($absence['etat'] == 4) {
                                        echo "<span style='color:orange;'>Ramener la justification à l'administration</span>";
                                    } else {
                                        echo "Envoyé";
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php if ($absence['justification_envoyee'] == 0): ?>
                                        <button onclick="envoyerJustification(<?php echo $absence['id']; ?>)" class="btn btn-success">Envoyer</button>
                                        <button onclick="window.location.href='modifier_justification.php?id=<?php echo $absence['id']; ?>'" class="btn btn-warning">modifier</button>
                                        <button onclick="supprimerAbsence(<?php echo $absence['id']; ?>)" class="btn btn-danger">Supprimer</button>
                                    <?php else: ?>
                                        Non modifiable
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
            <a href="justification.php" class="btn btn-primary">Justifier une absence</a>
            <p>Pour consulter les justifications autorisées par le Ministère de l'Enseignement Supérieur dans l'université, cliquez <a href="justificationsautorises.php">ici</a>.</p>
        </div>
    </main>
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <ul class="list-inline">
                    <li class="list-inline-item"><a href="index.php">Accueil</a></li>
                    <li class="list-inline-item"><a href="about_us.php">À propos</a></li>
                    <li class="list-inline-item"><a href="contact.php">Contact</a></li>
                </ul>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2025 Prénix - Gestion des Absences</p>
            </div>
        </div>
    </footer>



    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script>
    function supprimerAbsence(absenceId) {
        if (confirm("Voulez-vous vraiment supprimer cette absence ?")) {
            fetch("supprimer_justification.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded",
                },
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
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded",
                },
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
</body>
</html>