<?php
require_once 'config.php';
session_start();

// التحقق من تسجيل دخول الطالب
if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'student') {
    header("Location: index2.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// جلب بيانات الطالب مع جميع المعلومات
$stmt = $conn->prepare("
    SELECT s.*, u.username, u.email, l.name as level_name, 
           d.name as department_name, sd.name as sub_department_name
    FROM students s
    JOIN users u ON s.user_id = u.id
    JOIN levels l ON s.level_id = l.id
    JOIN departments d ON u.department_id = d.id
    LEFT JOIN sub_departments sd ON u.sub_department_id = sd.id
    WHERE s.user_id = ?
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$student = $stmt->get_result()->fetch_assoc();

// معالجة تحديث الملف الشخصي
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $username = trim($_POST['username']);
    
    // التحقق من صحة البيانات
    if (empty($first_name) || empty($last_name) || empty($email) || empty($username)) {
        $profile_error = "Veuillez remplir tous les champs obligatoires.";
    } else {
        // التحقق من عدم استخدام اسم المستخدم من قبل مستخدم آخر
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? AND id != ?");
        $stmt->bind_param("si", $username, $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $profile_error = "Ce nom d'utilisateur est déjà utilisé.";
        } else {
            // تحديث جدول الطلاب
            $stmt = $conn->prepare("UPDATE students SET first_name = ?, last_name = ? WHERE user_id = ?");
            $stmt->bind_param("ssi", $first_name, $last_name, $user_id);
            $stmt->execute();
            
            // تحديث جدول المستخدمين
            $stmt = $conn->prepare("UPDATE users SET username = ?, email = ? WHERE id = ?");
            $stmt->bind_param("ssi", $username, $email, $user_id);
            $stmt->execute();
            
            // تحديث بيانات الجلسة
            $_SESSION['email'] = $email;
            
            $profile_success = "Profil mis à jour avec succès!";
            
            // إعادة تحميل بيانات الطالب
            $stmt = $conn->prepare("
                SELECT s.*, u.username, u.email, l.name as level_name, 
                       d.name as department_name, sd.name as sub_department_name
                FROM students s
                JOIN users u ON s.user_id = u.id
                JOIN levels l ON s.level_id = l.id
                JOIN departments d ON u.department_id = d.id
                LEFT JOIN sub_departments sd ON u.sub_department_id = sd.id
                WHERE s.user_id = ?
            ");
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $student = $stmt->get_result()->fetch_assoc();
        }
    }
}

// معالجة تغيير كلمة المرور
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_password'])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    // التحقق من صحة البيانات
    if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
        $password_error = "Veuillez remplir tous les champs.";
    } elseif ($new_password !== $confirm_password) {
        $password_error = "Les nouveaux mots de passe ne correspondent pas.";
    } elseif (strlen($new_password) < 6) {
        $password_error = "Le nouveau mot de passe doit contenir au moins 6 caractères.";
    } else {
        // التحقق من كلمة المرور الحالية
        $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();
        
        if (password_verify($current_password, $user['password'])) {
            // تحديث كلمة المرور
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
            $stmt->bind_param("si", $hashed_password, $user_id);
            $stmt->execute();
            
            $password_success = "Mot de passe changé avec succès!";
        } else {
            $password_error = "Le mot de passe actuel est incorrect.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon profil - Justifly</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* نفس أنماط CSS من الصفحات السابقة */
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
        
        .profile-container {
            background: var(--bg-card);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            padding: 2rem;
            max-width: 800px;
            margin: 0 auto;
        }
        
        .profile-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .avatar {
            width: 120px;
            height: 120px;
            background: var(--gradient-primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            font-size: 3rem;
            color: white;
            font-weight: bold;
        }
        
        .form-section {
            background: rgba(255, 255, 255, 0.03);
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 2rem;
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
        
        .form-input {
            width: 100%;
            padding: 0.75rem 1rem;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            color: var(--text-primary);
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        
        .form-input:focus {
            outline: none;
            border-color: var(--accent-color);
            background: rgba(255, 255, 255, 0.08);
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        
        .form-input:disabled {
            opacity: 0.6;
            cursor: not-allowed;
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
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
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
        
        .info-card {
            background: rgba(255, 255, 255, 0.03);
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 1rem;
        }
        
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 0.5rem 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }
        
        .info-row:last-child {
            border-bottom: none;
        }
        
        .info-label {
            color: var(--text-secondary);
            font-weight: 500;
        }
        
        .info-value {
            color: var(--text-primary);
            font-weight: 600;
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
            
            .profile-container {
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
                <h1 class="text-3xl font-bold mb-2">Mon profil</h1>
                <p class="text-gray-400">Gérez vos informations personnelles</p>
            </div>
            <button class="md:hidden" onclick="toggleSidebar()">
                <i class="fas fa-bars text-2xl"></i>
            </button>
        </div>

        <!-- Profile Container -->
        <div class="profile-container">
            <!-- Profile Header -->
            <div class="profile-header">
                <div class="avatar">
                    <?php echo strtoupper(substr($student['first_name'], 0, 1) . substr($student['last_name'], 0, 1)); ?>
                </div>
                <h2 class="text-2xl font-bold"><?php echo htmlspecialchars($student['first_name'] . ' ' . $student['last_name']); ?></h2>
                <p class="text-gray-400"><?php echo htmlspecialchars($student['email']); ?></p>
            </div>

            <!-- Academic Information -->
            <div class="info-card">
                <h3 class="font-semibold mb-3">Informations académiques</h3>
                <div class="info-row">
                    <span class="info-label">Département</span>
                    <span class="info-value"><?php echo htmlspecialchars($student['department_name']); ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Spécialité</span>
                    <span class="info-value"><?php echo htmlspecialchars($student['sub_department_name']); ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Niveau</span>
                    <span class="info-value"><?php echo htmlspecialchars($student['level_name']); ?></span>
                </div>
            </div>

            <!-- Update Profile Form -->
            <div class="form-section">
                <h3 class="font-semibold mb-4">Modifier mes informations</h3>
                
                <?php if (isset($profile_error)): ?>
                    <div class="alert alert-error">
                        <i class="fas fa-exclamation-circle"></i>
                        <span><?php echo htmlspecialchars($profile_error); ?></span>
                    </div>
                <?php endif; ?>
                
                <?php if (isset($profile_success)): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i>
                        <span><?php echo htmlspecialchars($profile_success); ?></span>
                    </div>
                <?php endif; ?>

                <form method="POST">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="form-group">
                            <label class="form-label" for="first_name">Prénom <span class="text-red-500">*</span></label>
                            <input type="text" class="form-input" id="first_name" name="first_name" 
                                   value="<?php echo htmlspecialchars($student['first_name']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="last_name">Nom <span class="text-red-500">*</span></label>
                            <input type="text" class="form-input" id="last_name" name="last_name" 
                                   value="<?php echo htmlspecialchars($student['last_name']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="username">Nom d'utilisateur <span class="text-red-500">*</span></label>
                            <input type="text" class="form-input" id="username" name="username" 
                                   value="<?php echo htmlspecialchars($student['username']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="email">Email <span class="text-red-500">*</span></label>
                            <input type="email" class="form-input" id="email" name="email" 
                                   value="<?php echo htmlspecialchars($student['email']); ?>" required>
                        </div>
                    </div>
                    
                    <button type="submit" name="update_profile" class="btn-primary w-full">
                        <i class="fas fa-save mr-2"></i>
                        Enregistrer les modifications
                    </button>
                </form>
            </div>

            <!-- Change Password Form -->
            <div class="form-section">
                <h3 class="font-semibold mb-4">Changer le mot de passe</h3>
                
                <?php if (isset($password_error)): ?>
                    <div class="alert alert-error">
                        <i class="fas fa-exclamation-circle"></i>
                        <span><?php echo htmlspecialchars($password_error); ?></span>
                    </div>
                <?php endif; ?>
                
                <?php if (isset($password_success)): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i>
                        <span><?php echo htmlspecialchars($password_success); ?></span>
                    </div>
                <?php endif; ?>

                <form method="POST">
                    <div class="grid grid-cols-1 gap-4">
                        <div class="form-group">
                            <label class="form-label" for="current_password">Mot de passe actuel <span class="text-red-500">*</span></label>
                            <input type="password" class="form-input" id="current_password" name="current_password" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="new_password">Nouveau mot de passe <span class="text-red-500">*</span></label>
                            <input type="password" class="form-input" id="new_password" name="new_password" 
                                   minlength="6" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="confirm_password">Confirmer le nouveau mot de passe <span class="text-red-500">*</span></label>
                            <input type="password" class="form-input" id="confirm_password" name="confirm_password" 
                                   minlength="6" required>
                        </div>
                    </div>
                    
                    <button type="submit" name="change_password" class="btn-primary w-full">
                        <i class="fas fa-key mr-2"></i>
                        Changer le mot de passe
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('open');
        }

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