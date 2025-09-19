<?php
session_start();
include 'Database.php';


if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$message = "";


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $date_debut = $_POST['date_debut'];
    $date_fin = $_POST['date_fin'];
    $autre_chose = $_POST['autre_chose'] ?? '';
    $niveau = $_POST['niveau'];
    $justification_envoyee = isset($_POST['envoyer']) ? 1 : 0; 
    $justification_file = "";

    
    if ($date_fin < $date_debut) {
        $message = "Erreur: La date de fin doit être supérieure ou égale à la date de début.";
    } else {

        if (empty($_FILES['justification_file']['name'])) {
            $message = "Erreur: Vous devez joindre un fichier.";
        } else {
          
            if (!empty($_FILES['justification_file']['name'])) {
                $target_dir = "uploads/";
                $justification_file = basename($_FILES["justification_file"]["name"]);
                $target_file = $target_dir . $justification_file;
                move_uploaded_file($_FILES["justification_file"]["tmp_name"], $target_file);
            }

          
            $sql = "INSERT INTO absences (user_id, date_debut, date_fin, justification_envoyee, justification_file, niveau) 
                    VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$user_id, $date_debut, $date_fin, $justification_envoyee, $justification_file, $niveau]);

            header("Location: absences3.php");
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Justification d'Absence</title>
    
    <link rel="stylesheet" href="css/bootstrap.min.css">
 
    <link rel="stylesheet" href="css/justification.css">
</head>
<body>
 


    <section class="justification-section">
        <div class="container d-flex justify-content-center align-items-center" style="min-height: 80vh;">
            <div class="justification-card">
                <div class="card-header">
                    <h2>Ajouter une Justification</h2>
                </div>
                <form method="POST" enctype="multipart/form-data" class="card-body">
                   
                    <?php if (!empty($message)) { ?>
                        <p style="color: red;"><?= htmlspecialchars($message) ?></p>
                    <?php } ?>

                    <div class="form-group">
                        <label for="date_debut">Date de début :</label>
                        <input type="date" name="date_debut" id="date_debut" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="date_fin">Date de fin :</label>
                        <input type="date" name="date_fin" id="date_fin" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="niveau">Niveau (L'absence de la séance absenté) :</label>
                        <select name="niveau" id="niveau" class="form-control">
                            <option value="L1">L1</option>
                            <option value="L2">L2</option>
                            <option value="L3">L3</option>
                            <option value="M1">M1</option>
                            <option value="M2">M2</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="justification_file">Joindre un fichier : </label>
                        <input type="file" name="justification_file" id="justification_file" class="form-control" required>
                      
                    </div>

                    <button type="submit" name="envoyer" class="btn btn-primary">Envoyer</button>
                    <button type="submit" name="brouillon" class="btn btn-secondary">Enregistrer comme brouillon</button>
                </form>
            </div>
        </div>
    </section>
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

    

   
    <script src="js/jquery-3.5.1.slim.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
</body>
</html>