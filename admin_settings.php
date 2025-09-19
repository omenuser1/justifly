<?php
require_once 'config.php';
session_start();

// التحقق من تسجيل دخول الأدمن
if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'admin') {
    header("Location: admin_login.php");
    exit();
}

$admin_id = $_SESSION['user_id'];
$department_id = $_SESSION['department_id'];
$active_tab = $_GET['tab'] ?? 'general';

// معالجة إعدادات عامة
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_general_settings'])) {
    $university_name = trim($_POST['university_name'] ?? '');
    $academic_year = trim($_POST['academic_year'] ?? '');
    $max_absence_days = intval($_POST['max_absence_days'] ?? 30);
    $auto_approve_threshold = intval($_POST['auto_approve_threshold'] ?? 3);
    $notification_email = trim($_POST['notification_email'] ?? '');
    
    // تحديث أو إدراج الإعدادات في جدول الإعدادات (سننشئ جدول مؤقت)
    $settings = [
        'university_name' => $university_name,
        'academic_year' => $academic_year,
        'max_absence_days' => $max_absence_days,
        'auto_approve_threshold' => $auto_approve_threshold,
        'notification_email' => $notification_email,
        'updated_at' => date('Y-m-d H:i:s'),
        'updated_by' => $admin_id
    ];
    
    // تخزين الإعدادات في الجلسة مؤقتاً (يمكن تحسينها لاحقاً)
    $_SESSION['system_settings'] = $settings;
    
    $_SESSION['success'] = "Paramètres généraux mis à jour avec succès!";
    header("Location: admin_settings.php?tab=general");
    exit();
}

// معالجة إضافة قسم رئيسي
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_department'])) {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    
    if (empty($name)) {
        $error = "Le nom du département est obligatoire.";
    } else {
        $stmt = $conn->prepare("INSERT INTO departments (name, description) VALUES (?, ?)");
        $stmt->bind_param("ss", $name, $description);
        $stmt->execute();
        
        $_SESSION['success'] = "Département ajouté avec succès!";
        header("Location: admin_settings.php?tab=departments");
        exit();
    }
}

// معالجة تعديل قسم رئيسي
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_department'])) {
    $department_id_edit = $_POST['department_id'];
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    
    if (empty($name)) {
        $error = "Le nom du département est obligatoire.";
    } else {
        $stmt = $conn->prepare("UPDATE departments SET name = ?, description = ? WHERE id = ?");
        $stmt->bind_param("ssi", $name, $description, $department_id_edit);
        $stmt->execute();
        
        $_SESSION['success'] = "Département modifié avec succès!";
        header("Location: admin_settings.php?tab=departments");
        exit();
    }
}

// معالجة إضافة قسم فرعي
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_sub_department'])) {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $parent_department_id = $_POST['parent_department_id'];
    
    if (empty($name) || empty($parent_department_id)) {
        $error = "Veuillez remplir tous les champs obligatoires.";
    } else {
        $stmt = $conn->prepare("INSERT INTO sub_departments (name, description, department_id) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $name, $description, $parent_department_id);
        $stmt->execute();
        
        $_SESSION['success'] = "Spécialité ajoutée avec succès!";
        header("Location: admin_settings.php?tab=departments");
        exit();
    }
}

// معالجة إضافة مستوى دراسي
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_level'])) {
    $name = trim($_POST['name']);
    $sub_department_id = $_POST['sub_department_id'];
    
    if (empty($name) || empty($sub_department_id)) {
        $error = "Veuillez remplir tous les champs obligatoires.";
    } else {
        $stmt = $conn->prepare("INSERT INTO levels (name, sub_department_id) VALUES (?, ?)");
        $stmt->bind_param("si", $name, $sub_department_id);
        $stmt->execute();
        
        $_SESSION['success'] = "Niveau ajouté avec succès!";
        header("Location: admin_settings.php?tab=levels");
        exit();
    }
}

// معالجة إضافة أدمن جديد
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_admin'])) {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $admin_department_id = $_POST['admin_department_id'];
    
    if (empty($username) || empty($email) || empty($password) || empty($admin_department_id)) {
        $error = "Veuillez remplir tous les champs obligatoires.";
    } else {
        // التحقق من عدم وجود المستخدم
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        
        if ($stmt->get_result()->num_rows > 0) {
            $error = "Ce nom d'utilisateur ou cet email est déjà utilisé.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            $conn->begin_transaction();
            try {
                // إضافة المستخدم
                $stmt = $conn->prepare("
                    INSERT INTO users (username, password, email, role, department_id) 
                    VALUES (?, ?, ?, 'admin', ?)
                ");
                $stmt->bind_param("sssi", $username, $hashed_password, $email, $admin_department_id);
                $stmt->execute();
                
                $conn->commit();
                $_SESSION['success'] = "Administrateur ajouté avec succès!";
                header("Location: admin_settings.php?tab=admins");
                exit();
            } catch (Exception $e) {
                $conn->rollback();
                $error = "Erreur lors de l'ajout de l'administrateur: " . $e->getMessage();
            }
        }
    }
}

// جلب البيانات من قاعدة البيانات
// جلب جميع الأقسام الرئيسية
$stmt = $conn->prepare("SELECT * FROM departments ORDER BY name");
$stmt->execute();
$departments = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// جلب جميع الأقسام الفرعية
$stmt = $conn->prepare("
    SELECT sd.*, d.name as department_name 
    FROM sub_departments sd 
    JOIN departments d ON sd.department_id = d.id 
    ORDER BY d.name, sd.name
");
$stmt->execute();
$sub_departments = $stmt->execute();
$sub_departments = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// جلب جميع المستويات
$stmt = $conn->prepare("
    SELECT l.*, sd.name as sub_department_name, d.name as department_name 
    FROM levels l 
    JOIN sub_departments sd ON l.sub_department_id = sd.id 
    JOIN departments d ON sd.department_id = d.id 
    ORDER BY d.name, sd.name, l.name
");
$stmt->execute();
$levels = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// جلب جميع الأدمنة
$stmt = $conn->prepare("
    SELECT u.*, d.name as department_name 
    FROM users u 
    JOIN departments d ON u.department_id = d.id 
    WHERE u.role = 'admin' 
    ORDER BY u.username
");
$stmt->execute();
$admins = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// الحصول على الإعدادات الحالية (من الجلسة مؤقتاً)
$settings = $_SESSION['system_settings'] ?? [
    'university_name' => 'Université Belhadj Bouchib',
    'academic_year' => '2024-2025',
    'max_absence_days' => 30,
    'auto_approve_threshold' => 3,
    'notification_email' => 'admin@university.edu'
];
?>

<!DOCTYPE html>
<html lang="fr" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paramètres - Justifly</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
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
        
        .settings-card {
            background: var(--bg-card);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            transition: all 0.3s ease;
        }
        
        .settings-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.2);
        }
        
        .btn-primary {
            background: var(--gradient-primary);
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            color: white;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        }
        
        .btn-success {
            background: rgba(16, 185, 129, 0.2);
            border: 1px solid rgba(16, 185, 129, 0.3);
            color: #10b981;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .btn-warning {
            background: rgba(251, 191, 36, 0.2);
            border: 1px solid rgba(251, 191, 36, 0.3);
            color: #fbbf24;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .btn-danger {
            background: rgba(239, 68, 68, 0.2);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #ef4444;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
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
        
        .settings-tab {
            padding: 1rem 1.5rem;
            border-radius: 12px 12px 0 0;
            cursor: pointer;
            transition: all 0.3s ease;
            border-bottom: 3px solid transparent;
        }
        
        .settings-tab:hover {
            background: rgba(255, 255, 255, 0.05);
        }
        
        .settings-tab.active {
            background: var(--bg-card);
            border-bottom-color: var(--accent-color);
        }
        
        .form-group {
            margin-bottom: 1rem;
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
        
        .table-container {
            background: var(--bg-card);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            overflow: hidden;
        }
        
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }
        
        .modal-content {
            background-color: var(--bg-secondary);
            margin: 5% auto;
            padding: 2rem;
            border-radius: 16px;
            width: 90%;
            max-width: 600px;
            max-height: 80vh;
            overflow-y: auto;
        }
        
        .close {
            color: var(--text-secondary);
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }
        
        .close:hover {
            color: var(--text-primary);
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
                    <p class="text-sm text-gray-400">Administrateur</p>
                </div>
            </div>
            
            <nav>
                <div class="sidebar-item" onclick="window.location.href='admin_dashboard.php'">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Tableau de bord</span>
                </div>
                <div class="sidebar-item" onclick="window.location.href='admin_students.php'">
                    <i class="fas fa-user-graduate"></i>
                    <span>Gestion étudiants</span>
                </div>
                <div class="sidebar-item" onclick="window.location.href='admin_teachers.php'">
                    <i class="fas fa-chalkboard-teacher"></i>
                    <span>Gestion enseignants</span>
                </div>
                <div class="sidebar-item" onclick="window.location.href='admin_subjects.php'">
                    <i class="fas fa-book"></i>
                    <span>Gestion matières</span>
                </div>
                <div class="sidebar-item" onclick="window.location.href='admin_reports.php'">
                    <i class="fas fa-chart-bar"></i>
                    <span>Rapports</span>
                </div>
                <div class="sidebar-item active">
                    <i class="fas fa-cog"></i>
                    <span>Paramètres</span>
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
                <h1 class="text-3xl font-bold mb-2">Paramètres du Système</h1>
                <p class="text-gray-400">Configurez les paramètres de votre département</p>
            </div>
        </div>

        <!-- Success/Error Messages -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="bg-green-500/20 border border-green-500/30 rounded-lg p-4 mb-6 flex items-center gap-3">
                <i class="fas fa-check-circle text-green-500"></i>
                <span><?php echo htmlspecialchars($_SESSION['success']); ?></span>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if (isset($error)): ?>
            <div class="bg-red-500/20 border border-red-500/30 rounded-lg p-4 mb-6 flex items-center gap-3">
                <i class="fas fa-exclamation-circle text-red-500"></i>
                <span><?php echo htmlspecialchars($error); ?></span>
            </div>
        <?php endif; ?>

        <!-- Settings Tabs -->
        <div class="bg-gray-800/50 rounded-t-lg p-2 mb-0">
            <div class="flex gap-2">
                <div class="settings-tab <?php echo $active_tab == 'general' ? 'active' : ''; ?>" onclick="showTab('general')">
                    <i class="fas fa-sliders-h mr-2"></i>Général
                </div>
                <div class="settings-tab <?php echo $active_tab == 'departments' ? 'active' : ''; ?>" onclick="showTab('departments')">
                    <i class="fas fa-building mr-2"></i>Départements
                </div>
                <div class="settings-tab <?php echo $active_tab == 'levels' ? 'active' : ''; ?>" onclick="showTab('levels')">
                    <i class="fas fa-layer-group mr-2"></i>Niveaux
                </div>
                <div class="settings-tab <?php echo $active_tab == 'admins' ? 'active' : ''; ?>" onclick="showTab('admins')">
                    <i class="fas fa-user-shield mr-2"></i>Administrateurs
                </div>
            </div>
        </div>

        <div class="table-container">
            <!-- General Settings -->
            <div id="general-tab" class="settings-content p-6" style="<?php echo $active_tab != 'general' ? 'display: none;' : ''; ?>">
                <h2 class="text-xl font-bold mb-6">Paramètres Généraux</h2>
                
                <form method="POST">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="settings-card">
                            <h3 class="text-lg font-semibold mb-4">
                                <i class="fas fa-university mr-2"></i>Informations de l'établissement
                            </h3>
                            <div class="form-group">
                                <label class="form-label">Nom de l'université</label>
                                <input type="text" class="form-input" name="university_name" value="<?php echo htmlspecialchars($settings['university_name']); ?>" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Année académique</label>
                                <input type="text" class="form-input" name="academic_year" value="<?php echo htmlspecialchars($settings['academic_year']); ?>" required>
                            </div>
                        </div>
                        
                        <div class="settings-card">
                            <h3 class="text-lg font-semibold mb-4">
                                <i class="fas fa-calendar-check mr-2"></i>Paramètres des absences
                            </h3>
                            <div class="form-group">
                                <label class="form-label">Nombre maximum de jours d'absence</label>
                                <input type="number" class="form-input" name="max_absence_days" value="<?php echo htmlspecialchars($settings['max_absence_days']); ?>" min="1" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Seuil d'approbation automatique</label>
                                <input type="number" class="form-input" name="auto_approve_threshold" value="<?php echo htmlspecialchars($settings['auto_approve_threshold']); ?>" min="1" required>
                                <small class="text-gray-400">Nombre d'absences après lequel l'approbation devient automatique</small>
                            </div>
                        </div>
                        
                        <div class="settings-card">
                            <h3 class="text-lg font-semibold mb-4">
                                <i class="fas fa-envelope mr-2"></i>Notifications
                            </h3>
                            <div class="form-group">
                                <label class="form-label">Email de notification</label>
                                <input type="email" class="form-input" name="notification_email" value="<?php echo htmlspecialchars($settings['notification_email']); ?>" required>
                            </div>
                        </div>
                        
                        <div class="settings-card">
                            <h3 class="text-lg font-semibold mb-4">
                                <i class="fas fa-info-circle mr-2"></i>Informations système
                            </h3>
                            <div class="space-y-2 text-sm">
                                <p><strong>Version:</strong> 1.0.0</p>
                                <p><strong>Dernière mise à jour:</strong> <?php echo date('d/m/Y H:i'); ?></p>
                                <p><strong>Base de données:</strong> MySQL</p>
                                <p><strong>PHP:</strong> <?php echo phpversion(); ?></p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-6">
                        <button type="submit" name="update_general_settings" class="btn-primary">
                            <i class="fas fa-save mr-2"></i>Enregistrer les paramètres
                        </button>
                    </div>
                </form>
            </div>

            <!-- Departments Settings -->
            <div id="departments-tab" class="settings-content p-6" style="<?php echo $active_tab != 'departments' ? 'display: none;' : ''; ?>">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-bold">Gestion des Départements</h2>
                    <div class="flex gap-2">
                        <button class="btn-primary" onclick="openAddDepartmentModal()">
                            <i class="fas fa-plus mr-2"></i>Ajouter département
                        </button>
                        <button class="btn-success" onclick="openAddSubDepartmentModal()">
                            <i class="fas fa-plus mr-2"></i>Ajouter spécialité
                        </button>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Departments List -->
                    <div class="settings-card">
                        <h3 class="text-lg font-semibold mb-4">Départements Principaux</h3>
                        <div class="space-y-3">
                            <?php foreach ($departments as $dept): ?>
                                <div class="flex justify-between items-center p-3 bg-gray-800/30 rounded-lg">
                                    <div>
                                        <div class="font-semibold"><?php echo htmlspecialchars($dept['name']); ?></div>
                                        <div class="text-sm text-gray-400"><?php echo htmlspecialchars($dept['description']); ?></div>
                                    </div>
                                    <div class="flex gap-2">
                                        <button class="btn-warning" onclick="editDepartment(<?php echo $dept['id']; ?>)">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn-danger" onclick="deleteDepartment(<?php echo $dept['id']; ?>)">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    
                    <!-- Sub-departments List -->
                    <div class="settings-card">
                        <h3 class="text-lg font-semibold mb-4">Spécialités</h3>
                        <div class="space-y-3">
                            <?php foreach ($sub_departments as $sub_dept): ?>
                                <div class="flex justify-between items-center p-3 bg-gray-800/30 rounded-lg">
                                    <div>
                                        <div class="font-semibold"><?php echo htmlspecialchars($sub_dept['name']); ?></div>
                                        <div class="text-sm text-gray-400"><?php echo htmlspecialchars($sub_dept['department_name']); ?></div>
                                    </div>
                                    <div class="flex gap-2">
                                        <button class="btn-warning" onclick="editSubDepartment(<?php echo $sub_dept['id']; ?>)">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn-danger" onclick="deleteSubDepartment(<?php echo $sub_dept['id']; ?>)">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Levels Settings -->
            <div id="levels-tab" class="settings-content p-6" style="<?php echo $active_tab != 'levels' ? 'display: none;' : ''; ?>">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-bold">Gestion des Niveaux</h2>
                    <button class="btn-primary" onclick="openAddLevelModal()">
                        <i class="fas fa-plus mr-2"></i>Ajouter niveau
                    </button>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="border-b border-gray-700">
                            <tr>
                                <th class="text-left p-4">Niveau</th>
                                <th class="text-left p-4">Spécialité</th>
                                <th class="text-left p-4">Département</th>
                                <th class="text-center p-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($levels as $level): ?>
                                <tr class="border-b border-gray-800 hover:bg-gray-800/50">
                                    <td class="p-4 font-semibold"><?php echo htmlspecialchars($level['name']); ?></td>
                                    <td class="p-4"><?php echo htmlspecialchars($level['sub_department_name']); ?></td>
                                    <td class="p-4"><?php echo htmlspecialchars($level['department_name']); ?></td>
                                    <td class="p-4 text-center">
                                        <div class="flex gap-2 justify-center">
                                            <button class="btn-warning" onclick="editLevel(<?php echo $level['id']; ?>)">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn-danger" onclick="deleteLevel(<?php echo $level['id']; ?>)">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Admins Settings -->
            <div id="admins-tab" class="settings-content p-6" style="<?php echo $active_tab != 'admins' ? 'display: none;' : ''; ?>">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-bold">Gestion des Administrateurs</h2>
                    <button class="btn-primary" onclick="openAddAdminModal()">
                        <i class="fas fa-plus mr-2"></i>Ajouter administrateur
                    </button>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="border-b border-gray-700">
                            <tr>
                                <th class="text-left p-4">Username</th>
                                <th class="text-left p-4">Email</th>
                                <th class="text-left p-4">Département</th>
                                <th class="text-left p-4">Date création</th>
                                <th class="text-center p-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($admins as $admin): ?>
                                <tr class="border-b border-gray-800 hover:bg-gray-800/50">
                                    <td class="p-4">
                                        <div class="font-semibold"><?php echo htmlspecialchars($admin['username']); ?></div>
                                        <?php if ($admin['id'] == $admin_id): ?>
                                            <span class="text-xs text-green-400">(Vous)</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="p-4"><?php echo htmlspecialchars($admin['email']); ?></td>
                                    <td class="p-4"><?php echo htmlspecialchars($admin['department_name']); ?></td>
                                    <td class="p-4"><?php echo date('d/m/Y', strtotime($admin['created_at'])); ?></td>
                                    <td class="p-4 text-center">
                                        <div class="flex gap-2 justify-center">
                                            <?php if ($admin['id'] != $admin_id): ?>
                                                <button class="btn-warning" onclick="editAdmin(<?php echo $admin['id']; ?>)">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn-danger" onclick="deleteAdmin(<?php echo $admin['id']; ?>)">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            <?php else: ?>
                                                <span class="text-gray-400">-</span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Department Modal -->
    <div id="addDepartmentModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('addDepartmentModal')">&times;</span>
            <h2 class="text-xl font-bold mb-4">Ajouter un Département</h2>
            <form method="POST">
                <div class="form-group">
                    <label class="form-label">Nom du département <span class="text-red-500">*</span></label>
                    <input type="text" class="form-input" name="name" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea class="form-textarea" name="description" rows="3"></textarea>
                </div>
                <div class="flex gap-3 mt-6">
                    <button type="submit" name="add_department" class="btn-primary flex-1">
                        <i class="fas fa-plus mr-2"></i>Ajouter
                    </button>
                    <button type="button" onclick="closeModal('addDepartmentModal')" class="btn-danger flex-1">
                        Annuler
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Add Sub Department Modal -->
    <div id="addSubDepartmentModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('addSubDepartmentModal')">&times;</span>
            <h2 class="text-xl font-bold mb-4">Ajouter une Spécialité</h2>
            <form method="POST">
                <div class="form-group">
                    <label class="form-label">Nom de la spécialité <span class="text-red-500">*</span></label>
                    <input type="text" class="form-input" name="name" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea class="form-textarea" name="description" rows="3"></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Département parent <span class="text-red-500">*</span></label>
                    <select class="form-select" name="parent_department_id" required>
                        <option value="">Sélectionnez un département</option>
                        <?php foreach ($departments as $dept): ?>
                            <option value="<?php echo $dept['id']; ?>"><?php echo htmlspecialchars($dept['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="flex gap-3 mt-6">
                    <button type="submit" name="add_sub_department" class="btn-primary flex-1">
                        <i class="fas fa-plus mr-2"></i>Ajouter
                    </button>
                    <button type="button" onclick="closeModal('addSubDepartmentModal')" class="btn-danger flex-1">
                        Annuler
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Add Level Modal -->
    <div id="addLevelModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('addLevelModal')">&times;</span>
            <h2 class="text-xl font-bold mb-4">Ajouter un Niveau</h2>
            <form method="POST">
                <div class="form-group">
                    <label class="form-label">Nom du niveau <span class="text-red-500">*</span></label>
                    <input type="text" class="form-input" name="name" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Spécialité <span class="text-red-500">*</span></label>
                    <select class="form-select" name="sub_department_id" required>
                        <option value="">Sélectionnez une spécialité</option>
                        <?php foreach ($sub_departments as $sub_dept): ?>
                            <option value="<?php echo $sub_dept['id']; ?>"><?php echo htmlspecialchars($sub_dept['name']); ?> (<?php echo htmlspecialchars($sub_dept['department_name']); ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="flex gap-3 mt-6">
                    <button type="submit" name="add_level" class="btn-primary flex-1">
                        <i class="fas fa-plus mr-2"></i>Ajouter
                    </button>
                    <button type="button" onclick="closeModal('addLevelModal')" class="btn-danger flex-1">
                        Annuler
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Add Admin Modal -->
    <div id="addAdminModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('addAdminModal')">&times;</span>
            <h2 class="text-xl font-bold mb-4">Ajouter un Administrateur</h2>
            <form method="POST">
                <div class="grid grid-cols-1 gap-4">
                    <div class="form-group">
                        <label class="form-label">Username <span class="text-red-500">*</span></label>
                        <input type="text" class="form-input" name="username" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email <span class="text-red-500">*</span></label>
                        <input type="email" class="form-input" name="email" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Mot de passe <span class="text-red-500">*</span></label>
                        <input type="password" class="form-input" name="password" required minlength="6">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Département <span class="text-red-500">*</span></label>
                        <select class="form-select" name="admin_department_id" required>
                            <option value="">Sélectionnez un département</option>
                            <?php foreach ($departments as $dept): ?>
                                <option value="<?php echo $dept['id']; ?>"><?php echo htmlspecialchars($dept['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="flex gap-3 mt-6">
                    <button type="submit" name="add_admin" class="btn-primary flex-1">
                        <i class="fas fa-plus mr-2"></i>Ajouter
                    </button>
                    <button type="button" onclick="closeModal('addAdminModal')" class="btn-danger flex-1">
                        Annuler
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function showTab(tabName) {
            // Hide all tabs
            document.querySelectorAll('.settings-content').forEach(el => {
                el.style.display = 'none';
            });
            
            // Remove active class from all tab buttons
            document.querySelectorAll('.settings-tab').forEach(el => {
                el.classList.remove('active');
            });
            
            // Show selected tab
            document.getElementById(tabName + '-tab').style.display = 'block';
            
            // Add active class to clicked tab
            event.target.closest('.settings-tab').classList.add('active');
            
            // Update URL without reload
            const url = new URL(window.location);
            url.searchParams.set('tab', tabName);
            window.history.pushState({}, '', url);
        }

        function openAddDepartmentModal() {
            document.getElementById('addDepartmentModal').style.display = 'block';
        }

        function openAddSubDepartmentModal() {
            document.getElementById('addSubDepartmentModal').style.display = 'block';
        }

        function openAddLevelModal() {
            document.getElementById('addLevelModal').style.display = 'block';
        }

        function openAddAdminModal() {
            document.getElementById('addAdminModal').style.display = 'block';
        }

        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }

        function editDepartment(id) {
            // Implementation for editing department
            alert('Fonctionnalité de modification en développement');
        }

        function deleteDepartment(id) {
            if (confirm('Êtes-vous sûr de vouloir supprimer ce département? Cette action affectera tous les utilisateurs associés.')) {
                // Implementation for deleting department
                alert('Fonctionnalité de suppression en développement');
            }
        }

        function editSubDepartment(id) {
            // Implementation for editing sub-department
            alert('Fonctionnalité de modification en développement');
        }

        function deleteSubDepartment(id) {
            if (confirm('Êtes-vous sûr de vouloir supprimer cette spécialité? Cette action affectera tous les utilisateurs associés.')) {
                // Implementation for deleting sub-department
                alert('Fonctionnalité de suppression en développement');
            }
        }

        function editLevel(id) {
            // Implementation for editing level
            alert('Fonctionnalité de modification en développement');
        }

        function deleteLevel(id) {
            if (confirm('Êtes-vous sûr de vouloir supprimer ce niveau? Cette action affectera tous les étudiants associés.')) {
                // Implementation for deleting level
                alert('Fonctionnalité de suppression en développement');
            }
        }

        function editAdmin(id) {
            // Implementation for editing admin
            alert('Fonctionnalité de modification en développement');
        }

        function deleteAdmin(id) {
            if (confirm('Êtes-vous sûr de vouloir supprimer cet administrateur?')) {
                // Implementation for deleting admin
                alert('Fonctionnalité de suppression en développement');
            }
        }

        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('open');
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modals = document.querySelectorAll('.modal');
            modals.forEach(modal => {
                if (event.target === modal) {
                    modal.style.display = 'none';
                }
            });
        }
    </script>
</body>
</html>