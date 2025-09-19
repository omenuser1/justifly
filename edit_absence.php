<?php
require_once 'config.php';
session_start();

// التحقق من تسجيل دخول الطالب
if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'student') {
    header("Location: index2.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// جلب بيانات الطالب
$stmt = $conn->prepare("
    SELECT s.*, l.id as level_id
    FROM students s
    JOIN levels l ON s.level_id = l.id
    WHERE s.user_id = ?
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$student = $stmt->get_result()->fetch_assoc();

// جلب بيانات الغياب المطلوب تعديله
$absence_id = $_GET['id'] ?? 0;
if (!$absence_id) {
    header("Location: student_dashboard.php");
    exit();
}

$stmt = $conn->prepare("
    SELECT a.*, sub.name as subject_name, sub.code as subject_code
    FROM absences a
    JOIN subjects sub ON a.subject_id = sub.id
    WHERE a.id = ? AND a.student_id = ?
");
$stmt->bind_param("ii", $absence_id, $student['id']);
$stmt->execute();
$absence = $stmt->get_result()->fetch_assoc();

// التحقق من وجود الغياب وأنه يمكن تعديله
if (!$absence || $absence['status'] !== 'pending') {
    $_SESSION['error'] = "Cette absence ne peut pas être modifiée.";
    header("Location: student_dashboard.php");
    exit();
}

// جلب المواد المتاحة للطالب
$stmt = $conn->prepare("
    SELECT DISTINCT sub.id, sub.name, sub.code
    FROM subjects sub
    JOIN subject_levels sl ON sub.id = sl.subject_id
    WHERE sl.level_id = ?
    ORDER BY sub.name
");
$stmt->bind_param("i", $student['level_id']);
$stmt->execute();
$subjects = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// معالجة تحديث النموذج
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $subject_id = $_POST['subject_id'];
    $date = $_POST['date'];
    $reason = $_POST['reason'];
    
    // التحقق من صحة البيانات
    if (empty($subject_id) || empty($date)) {
        $error = "Veuillez remplir tous les champs obligatoires.";
    } else {
        // معالجة رفع الملف الجديد (إذا تم رفع ملف جديد)
        $justification_document = $absence['justification_document']; // الاحتفاظ بالملف القديم افتراضيًا
        
        if (isset($_FILES['justification_document']) && $_FILES['justification_document']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = 'uploads/';
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            
            // حذف الملف القديم إذا كان موجودًا
            if ($absence['justification_document'] && file_exists($upload_dir . $absence['justification_document'])) {
                unlink($upload_dir . $absence['justification_document']);
            }
            
            $file_name = time() . '_' . $_FILES['justification_document']['name'];
            $file_path = $upload_dir . $file_name;
            
            if (move_uploaded_file($_FILES['justification_document']['tmp_name'], $file_path)) {
                $justification_document = $file_name;
            }
        }
        
        // تحديث الغياب في قاعدة البيانات
        $stmt = $conn->prepare("
            UPDATE absences 
            SET subject_id = ?, date = ?, reason = ?, justification_document = ?
            WHERE id = ? AND student_id = ?
        ");
        $stmt->bind_param("isssii", $subject_id, $date, $reason, $justification_document, $absence_id, $student['id']);
        
        if ($stmt->execute()) {
            $_SESSION['success'] = "Absence modifiée avec succès!";
            header("Location: student_dashboard.php");
            exit();
        } else {
            $error = "Erreur lors de la modification de l'absence.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier une absence - Justifly</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* نفس أنماط CSS من صفحة الإضافة */
        :root {
            --bg-primary: #0f172a;
            --bg-secondary: #1e293b;
            --bg-card: rgba(255, 255, 255, 0.05);
            --text-primary: #e2e8f0;
            --text-secondary: #94a3b8;
            --accent-color: #667eea;
            --gradient-primary: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        [data-theme="light"] {
            --bg-primary: #f8fafc;
            --bg-secondary: #ffffff;
            --bg-card: rgba(255, 255, 255, 0.8);
            --text-primary: #1e293b;
            --text-secondary: #475569;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg-primary);
            color: var(--text-primary);
            transition: all 0.3s ease;
            min-height: 100vh;
        }
        
        .sidebar {
            background: var(--bg-secondary);
            border-right: 1px solid rgba(255, 255, 255, 0.1);
            min-height: 100vh;
            position: fixed;
            width: 250px;
            z-index: 100;
        }
        
        .main-content {
            margin-left: 250px;
            padding: 2rem;
            width: calc(100% - 250px);
        }
        
        .form-container {
            background: var(--bg-card);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            padding: 2rem;
            max-width: 800px;
            margin: 0 auto;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--text-primary);
        }
        
        .form-input, .form-select, .form-textarea {
            width: 100%;
            padding: 0.75rem 1rem;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            color: var(--text-primary);
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        
        .form-input:focus, .form-select:focus, .form-textarea:focus {
            outline: none;
            border-color: var(--accent-color);
            background: rgba(255, 255, 255, 0.08);
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        
        .form-textarea {
            resize: vertical;
            min-height: 100px;
        }
        
        .file-upload {
            position: relative;
            display: inline-block;
            width: 100%;
        }
        
        .file-upload input[type=file] {
            position: absolute;
            opacity: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
        }
        
        .file-upload-label {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 2rem;
            border: 2px dashed rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .file-upload-label:hover {
            border-color: var(--accent-color);
            background: rgba(102, 126, 234, 0.05);
        }
        
        .btn-primary {
            background: var(--gradient-primary);
            border: none;
            padding: 0.75rem 2rem;
            border-radius: 12px;
            color: white;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 100%;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        }
        
        .btn-secondary {
            background: transparent;
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 0.75rem 2rem;
            border-radius: 12px;
            color: var(--text-primary);
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 100%;
            margin-top: 1rem;
        }
        
        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.05);
            border-color: rgba(255, 255, 255, 0.3);
        }
        
        .sidebar-item {
            padding: 1rem;
            margin: 0.5rem 0;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .sidebar-item:hover {
            background: rgba(102, 126, 234, 0.1);
            color: var(--accent-color);
        }
        
        .sidebar-item.active {
            background: var(--gradient-primary);
            color: white;
        }
        
        .alert {
            padding: 1rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .alert-error {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #ef4444;
        }
        
        .alert-success {
            background: rgba(16, 185, 129, 0.1);
            border: 1px solid rgba(16, 185, 129, 0.3);
            color: #10b981;
        }
        
        .current-file {
            background: rgba(102, 126, 234, 0.1);
            border: 1px solid rgba(102, 126, 234, 0.3);
            border-radius: 8px;
            padding: 0.75rem;
            margin-top: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .current-file a {
            color: var(--accent-color);
            text-decoration: none;
        }
        
        .current-file a:hover {
            text-decoration: underline;
        }
        
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }
            
            .sidebar.open {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
                width: 100%;
                padding: 1rem;
            }
            
            .form-container {
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="p-4">
            <div class="flex items-center gap-3 mb-8">
                <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-indigo-600 rounded-xl flex items-center justify-center text-white font-bold text-xl">
                    J
                </div>
                <div>
                    <h2 class="font-bold text-lg">Justifly</h2>
                    <p class="text-sm text-gray-400">Étudiant</p>
                </div>
            </div>
            
            <nav>
                <div class="sidebar-item" onclick="window.location.href='student_dashboard.php'">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Tableau de bord</span>
                </div>
                <div class="sidebar-item" onclick="window.location.href='add_absence.php'">
                    <i class="fas fa-plus-circle"></i>
                    <span>Déclarer absence</span>
                </div>
                <div class="sidebar-item active">
                    <i class="fas fa-edit"></i>
                    <span>Modifier absence</span>
                </div>
                <div class="sidebar-item" onclick="window.location.href='profile.php'">
                    <i class="fas fa-user"></i>
                    <span>Mon profil</span>
                </div>
                <div class="sidebar-item" onclick="window.location.href='notifications.php'">
                    <i class="fas fa-bell"></i>
                    <span>Notifications</span>
                </div>
                <div class="sidebar-item mt-8" onclick="window.location.href='logout.php'">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Déconnexion</span>
                </div>
            </nav>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Header -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold mb-2">Modifier une absence</h1>
                <p class="text-gray-400">Modifiez les informations de votre absence ci-dessous</p>
            </div>
            <button class="md:hidden" onclick="toggleSidebar()">
                <i class="fas fa-bars text-2xl"></i>
            </button>
        </div>

        <!-- Form Container -->
        <div class="form-container">
            <?php if (isset($error)): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i>
                    <span><?php echo htmlspecialchars($error); ?></span>
                </div>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data" id="absence-form">
                <!-- Current Absence Info -->
                <div class="bg-gray-800/50 rounded-lg p-4 mb-6">
                    <h3 class="font-semibold mb-2">Informations actuelles</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-gray-400">Matière:</span>
                            <span class="ml-2"><?php echo htmlspecialchars($absence['subject_name']); ?></span>
                        </div>
                        <div>
                            <span class="text-gray-400">Date:</span>
                            <span class="ml-2"><?php echo date('d/m/Y', strtotime($absence['date'])); ?></span>
                        </div>
                        <div>
                            <span class="text-gray-400">Statut:</span>
                            <span class="ml-2">
                                <span class="status-badge status-<?php echo $absence['status']; ?>">
                                    <?php 
                                    switch($absence['status']) {
                                        case 'pending': echo 'En attente'; break;
                                        case 'approved': echo 'Approuvée'; break;
                                        case 'rejected': echo 'Rejetée'; break;
                                    }
                                    ?>
                                </span>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Subject Selection -->
                <div class="form-group">
                    <label class="form-label" for="subject_id">
                        <i class="fas fa-book mr-2"></i>Matière <span class="text-red-500">*</span>
                    </label>
                    <select class="form-select" id="subject_id" name="subject_id" required>
                        <?php foreach ($subjects as $subject): ?>
                            <option value="<?php echo $subject['id']; ?>" 
                                    <?php echo $subject['id'] == $absence['subject_id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($subject['name'] . ' (' . $subject['code'] . ')'); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Date Selection -->
                <div class="form-group">
                    <label class="form-label" for="date">
                        <i class="fas fa-calendar mr-2"></i>Date de l'absence <span class="text-red-500">*</span>
                    </label>
                    <input type="date" class="form-input" id="date" name="date" 
                           value="<?php echo $absence['date']; ?>" 
                           required max="<?php echo date('Y-m-d'); ?>">
                </div>

                <!-- Reason -->
                <div class="form-group">
                    <label class="form-label" for="reason">
                        <i class="fas fa-comment-alt mr-2"></i>Motif de l'absence
                    </label>
                    <textarea class="form-textarea" id="reason" name="reason" 
                              placeholder="Décrivez brièvement le motif de votre absence..."><?php 
                              echo htmlspecialchars($absence['reason']); 
                              ?></textarea>
                </div>

                <!-- Justification Document -->
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-paperclip mr-2"></i>Document de justification
                    </label>
                    
                    <?php if ($absence['justification_document']): ?>
                        <div class="current-file">
                            <div>
                                <i class="fas fa-file-alt mr-2"></i>
                                <span>Document actuel: <?php echo htmlspecialchars($absence['justification_document']); ?></span>
                            </div>
                            <a href="uploads/<?php echo htmlspecialchars($absence['justification_document']); ?>" download>
                                <i class="fas fa-download"></i>
                            </a>
                        </div>
                        <p class="text-sm text-gray-400 mt-2">
                            Téléchargez un nouveau document pour remplacer l'actuel
                        </p>
                    <?php endif; ?>
                    
                    <div class="file-upload">
                        <input type="file" id="justification_document" name="justification_document" 
                               accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                        <label for="justification_document" class="file-upload-label">
                            <i class="fas fa-cloud-upload-alt text-2xl"></i>
                            <div>
                                <p class="font-semibold">Cliquez pour télécharger un nouveau document</p>
                                <p class="text-sm text-gray-400">PDF, JPG, PNG, DOC (Max: 5MB)</p>
                            </div>
                        </label>
                    </div>
                    <div id="file-name" class="mt-2 text-sm text-gray-400"></div>
                </div>

                <!-- Submit Buttons -->
                <div class="flex gap-4 mt-6">
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-save mr-2"></i>
                        Enregistrer les modifications
                    </button>
                    <button type="button" class="btn-secondary" onclick="window.location.href='student_dashboard.php'">
                        <i class="fas fa-times mr-2"></i>
                        Annuler
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('open');
        }

        // Afficher le nom du fichier sélectionné
        document.getElementById('justification_document').addEventListener('change', function(e) {
            const fileName = e.target.files[0]?.name || '';
            document.getElementById('file-name').textContent = fileName ? 
                `Nouveau fichier: ${fileName}` : '';
        });

        // Validation du formulaire
        document.getElementById('absence-form').addEventListener('submit', function(e) {
            const dateInput = document.getElementById('date');
            const selectedDate = new Date(dateInput.value);
            const today = new Date();
            
            if (selectedDate > today) {
                e.preventDefault();
                alert('La date d\'absence ne peut pas être dans le futur.');
            }
        });

        // Theme toggle (si nécessaire)
        const themeToggle = document.getElementById('theme-toggle');
        if (themeToggle) {
            themeToggle.addEventListener('change', () => {
                const theme = themeToggle.checked ? 'light' : 'dark';
                document.documentElement.setAttribute('data-theme', theme);
                localStorage.setItem('theme', theme);
            });
        }
    </script>
</body>
</html>