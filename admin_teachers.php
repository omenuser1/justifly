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

// معالجة إضافة معلم جديد
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_teacher'])) {
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $sub_department_id = $_POST['sub_department_id'] ?: null;
    
    // التحقق من صحة البيانات
    if (empty($first_name) || empty($last_name) || empty($username) || empty($email) || empty($password)) {
        $error = "Veuillez remplir tous les champs obligatoires.";
    } else {
        // التحقق من عدم وجود اسم المستخدم أو البريد الإلكتروني مسبقًا
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        if ($stmt->get_result()->num_rows > 0) {
            $error = "Ce nom d'utilisateur ou cet email est déjà utilisé.";
        } else {
            // تشفير كلمة المرور
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            // بدء المعاملة
            $conn->begin_transaction();
            
            try {
                // إدخال المستخدم في جدول users
                $stmt = $conn->prepare("
                    INSERT INTO users (username, password, email, role, department_id, sub_department_id) 
                    VALUES (?, ?, ?, 'teacher', ?, ?)
                ");
                $stmt->bind_param("ssssi", $username, $hashed_password, $email, $department_id, $sub_department_id);
                $stmt->execute();
                $user_id = $conn->insert_id;
                
                // إدخال المعلم في جدول teachers
                $stmt = $conn->prepare("INSERT INTO teachers (user_id, first_name, last_name) VALUES (?, ?, ?)");
                $stmt->bind_param("iss", $user_id, $first_name, $last_name);
                $stmt->execute();
                
                $conn->commit();
                $_SESSION['success'] = "Enseignant ajouté avec succès!";
                header("Location: admin_teachers.php");
                exit();
            } catch (Exception $e) {
                $conn->rollback();
                $error = "Erreur lors de l'ajout de l'enseignant: " . $e->getMessage();
            }
        }
    }
}

// معالجة تعديل معلم
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_teacher'])) {
    $teacher_id = $_POST['teacher_id'];
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $sub_department_id = $_POST['sub_department_id'] ?: null;
    
    // التحقق من صحة البيانات
    if (empty($first_name) || empty($last_name) || empty($username) || empty($email)) {
        $error = "Veuillez remplir tous les champs obligatoires.";
    } else {
        // التحقق من عدم وجود اسم المستخدم أو البريد الإلكتروني لمعلم آخر
        $stmt = $conn->prepare("
            SELECT u.id FROM users u 
            JOIN teachers t ON u.id = t.user_id 
            WHERE (u.username = ? OR u.email = ?) AND t.id != ?
        ");
        $stmt->bind_param("ssi", $username, $email, $teacher_id);
        $stmt->execute();
        if ($stmt->get_result()->num_rows > 0) {
            $error = "Ce nom d'utilisateur ou cet email est déjà utilisé par un autre enseignant.";
        } else {
            // بدء المعاملة
            $conn->begin_transaction();
            
            try {
                // تحديث جدول teachers
                $stmt = $conn->prepare("UPDATE teachers SET first_name = ?, last_name = ? WHERE id = ?");
                $stmt->bind_param("ssi", $first_name, $last_name, $teacher_id);
                $stmt->execute();
                
                // تحديث جدول users
                $stmt = $conn->prepare("
                    UPDATE users SET username = ?, email = ?, sub_department_id = ? 
                    WHERE id = (SELECT user_id FROM teachers WHERE id = ?)
                ");
                $stmt->bind_param("ssii", $username, $email, $sub_department_id, $teacher_id);
                $stmt->execute();
                
                $conn->commit();
                $_SESSION['success'] = "Enseignant modifié avec succès!";
                header("Location: admin_teachers.php");
                exit();
            } catch (Exception $e) {
                $conn->rollback();
                $error = "Erreur lors de la modification de l'enseignant: " . $e->getMessage();
            }
        }
    }
}

// معالجة حذف معلم
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_teacher'])) {
    $teacher_id = $_POST['teacher_id'];
    
    // بدء المعاملة
    $conn->begin_transaction();
    
    try {
        // جلب user_id للمعلم
        $stmt = $conn->prepare("SELECT user_id FROM teachers WHERE id = ?");
        $stmt->bind_param("i", $teacher_id);
        $stmt->execute();
        $teacher = $stmt->get_result()->fetch_assoc();
        
        if ($teacher) {
            $user_id = $teacher['user_id'];
            
            // حذف العلاقات مع المواد والمستويات
            $stmt = $conn->prepare("DELETE FROM teacher_subject_levels WHERE teacher_id = ?");
            $stmt->bind_param("i", $teacher_id);
            $stmt->execute();
            
            // حذف المعلم من جدول teachers
            $stmt = $conn->prepare("DELETE FROM teachers WHERE id = ?");
            $stmt->bind_param("i", $teacher_id);
            $stmt->execute();
            
            // حذف المستخدم من جدول users
            $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            
            $conn->commit();
            $_SESSION['success'] = "Enseignant supprimé avec succès!";
        } else {
            throw new Exception("Enseignant non trouvé");
        }
    } catch (Exception $e) {
        $conn->rollback();
        $error = "Erreur lors de la suppression de l'enseignant: " . $e->getMessage();
    }
    
    header("Location: admin_teachers.php");
    exit();
}

// جلب الأقسام الفرعية للقسم
$stmt = $conn->prepare("
    SELECT id, name FROM sub_departments 
    WHERE department_id = ? 
    ORDER BY name
");
$stmt->bind_param("i", $department_id);
$stmt->execute();
$sub_departments = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// جلب جميع المعلمين في القسم
$search = $_GET['search'] ?? '';
$filter_subdept = $_GET['filter_subdept'] ?? '';

$stmt = $conn->prepare("
    SELECT t.*, u.username, u.email, sd.name as sub_department_name
    FROM teachers t
    JOIN users u ON t.user_id = u.id
    LEFT JOIN sub_departments sd ON u.sub_department_id = sd.id
    WHERE u.department_id = ?
    AND (t.first_name LIKE ? OR t.last_name LIKE ? OR u.username LIKE ?)
    " . ($filter_subdept ? "AND u.sub_department_id = ?" : "") . "
    ORDER BY t.first_name, t.last_name
");

$search_param = "%$search%";
if ($filter_subdept) {
    $stmt->bind_param("isssi", $department_id, $search_param, $search_param, $search_param, $filter_subdept);
} else {
    $stmt->bind_param("isss", $department_id, $search_param, $search_param, $search_param);
}
$stmt->execute();
$teachers = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Enseignants - Justifly</title>
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
        
        .table-container {
            background: var(--bg-card);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            overflow: hidden;
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
        
        .form-group {
            margin-bottom: 1rem;
        }
        
        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--text-primary);
        }
        
        .form-input, .form-select {
            width: 100%;
            padding: 0.75rem 1rem;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            color: var(--text-primary);
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        
        .form-input:focus, .form-select:focus {
            outline: none;
            border-color: var(--accent-color);
            background: rgba(255, 255, 255, 0.08);
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
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
                <div class="sidebar-item active">
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
                <div class="sidebar-item" onclick="window.location.href='admin_settings.php'">
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
                <h1 class="text-3xl font-bold mb-2">Gestion des Enseignants</h1>
                <p class="text-gray-400">Gérez les enseignants de votre département</p>
            </div>
            <button class="btn-primary" onclick="openAddModal()">
                <i class="fas fa-plus mr-2"></i>
                Ajouter un enseignant
            </button>
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

        <!-- Search and Filter -->
        <div class="bg-gray-800/50 rounded-lg p-4 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-2">Rechercher</label>
                    <input type="text" class="form-input" id="searchInput" placeholder="Nom, prénom, username..." value="<?php echo htmlspecialchars($search); ?>">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Filtrer par spécialité</label>
                    <select class="form-select" id="subdeptFilter">
                        <option value="">Toutes les spécialités</option>
                        <?php foreach ($sub_departments as $sub_dept): ?>
                            <option value="<?php echo $sub_dept['id']; ?>" <?php echo $filter_subdept == $sub_dept['id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($sub_dept['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="flex items-end">
                    <button class="btn-primary w-full" onclick="filterTeachers()">
                        <i class="fas fa-search mr-2"></i>
                        Rechercher
                    </button>
                </div>
            </div>
        </div>

        <!-- Teachers Table -->
        <div class="table-container">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="border-b border-gray-700">
                        <tr>
                            <th class="text-left p-4">Nom</th>
                            <th class="text-left p-4">Username</th>
                            <th class="text-left p-4">Email</th>
                            <th class="text-left p-4">Spécialité</th>
                            <th class="text-left p-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($teachers)): ?>
                            <tr>
                                <td colspan="5" class="text-center p-8 text-gray-400">
                                    Aucun enseignant trouvé
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($teachers as $teacher): ?>
                                <tr class="border-b border-gray-800 hover:bg-gray-800/50">
                                    <td class="p-4">
                                        <div class="font-semibold"><?php echo htmlspecialchars($teacher['first_name'] . ' ' . $teacher['last_name']); ?></div>
                                        <div class="text-sm text-gray-400">ID: <?php echo $teacher['id']; ?></div>
                                    </td>
                                    <td class="p-4"><?php echo htmlspecialchars($teacher['username']); ?></td>
                                    <td class="p-4"><?php echo htmlspecialchars($teacher['email']); ?></td>
                                    <td class="p-4"><?php echo htmlspecialchars($teacher['sub_department_name'] ?: 'Non spécifié'); ?></td>
                                    <td class="p-4">
                                        <div class="flex gap-2">
                                            <button class="btn-warning" onclick="editTeacher(<?php echo $teacher['id']; ?>)">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn-success" onclick="assignSubjects(<?php echo $teacher['id']; ?>)">
                                                <i class="fas fa-book"></i>
                                            </button>
                                            <button class="btn-danger" onclick="deleteTeacher(<?php echo $teacher['id']; ?>)">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add Teacher Modal -->
    <div id="addModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('addModal')">&times;</span>
            <h2 class="text-xl font-bold mb-4">Ajouter un enseignant</h2>
            <form method="POST">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-group">
                        <label class="form-label">Prénom <span class="text-red-500">*</span></label>
                        <input type="text" class="form-input" name="first_name" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nom <span class="text-red-500">*</span></label>
                        <input type="text" class="form-input" name="last_name" required>
                    </div>
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
                        <label class="form-label">Spécialité (optionnel)</label>
                        <select class="form-select" name="sub_department_id">
                            <option value="">Sélectionnez une spécialité</option>
                            <?php foreach ($sub_departments as $sub_dept): ?>
                                <option value="<?php echo $sub_dept['id']; ?>"><?php echo htmlspecialchars($sub_dept['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="flex gap-3 mt-6">
                    <button type="submit" name="add_teacher" class="btn-primary flex-1">
                        <i class="fas fa-plus mr-2"></i>
                        Ajouter
                    </button>
                    <button type="button" onclick="closeModal('addModal')" class="btn-danger flex-1">
                        Annuler
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Teacher Modal -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('editModal')">&times;</span>
            <h2 class="text-xl font-bold mb-4">Modifier un enseignant</h2>
            <form method="POST" id="editForm">
                <input type="hidden" name="teacher_id" id="editTeacherId">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-group">
                        <label class="form-label">Prénom <span class="text-red-500">*</span></label>
                        <input type="text" class="form-input" name="first_name" id="editFirstName" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nom <span class="text-red-500">*</span></label>
                        <input type="text" class="form-input" name="last_name" id="editLastName" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Username <span class="text-red-500">*</span></label>
                        <input type="text" class="form-input" name="username" id="editUsername" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email <span class="text-red-500">*</span></label>
                        <input type="email" class="form-input" name="email" id="editEmail" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Spécialité (optionnel)</label>
                        <select class="form-select" name="sub_department_id" id="editSubDeptId">
                            <option value="">Sélectionnez une spécialité</option>
                            <?php foreach ($sub_departments as $sub_dept): ?>
                                <option value="<?php echo $sub_dept['id']; ?>"><?php echo htmlspecialchars($sub_dept['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="flex gap-3 mt-6">
                    <button type="submit" name="edit_teacher" class="btn-primary flex-1">
                        <i class="fas fa-save mr-2"></i>
                        Enregistrer
                    </button>
                    <button type="button" onclick="closeModal('editModal')" class="btn-danger flex-1">
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

        function openAddModal() {
            document.getElementById('addModal').style.display = 'block';
        }

        function editTeacher(id) {
            // Fetch teacher data via AJAX
            fetch(`get_teacher_data.php?id=${id}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('editTeacherId').value = data.id;
                    document.getElementById('editFirstName').value = data.first_name;
                    document.getElementById('editLastName').value = data.last_name;
                    document.getElementById('editUsername').value = data.username;
                    document.getElementById('editEmail').value = data.email;
                    document.getElementById('editSubDeptId').value = data.sub_department_id || '';
                    document.getElementById('editModal').style.display = 'block';
                })
                .catch(error => {
                    alert('Erreur lors du chargement des données de l\'enseignant');
                });
        }

        function assignSubjects(id) {
            // This will open the subject assignment page
            window.location.href = `assign_teacher_subjects.php?teacher_id=${id}`;
        }

        function deleteTeacher(id) {
            if (confirm('Êtes-vous sûr de vouloir supprimer cet enseignant? Cette action est irréversible.')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.innerHTML = `<input type="hidden" name="delete_teacher" value="${id}">`;
                document.body.appendChild(form);
                form.submit();
            }
        }

        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }

        function filterTeachers() {
            const search = document.getElementById('searchInput').value;
            const subdept = document.getElementById('subdeptFilter').value;
            const params = new URLSearchParams();
            if (search) params.append('search', search);
            if (subdept) params.append('filter_subdept', subdept);
            window.location.href = `admin_teachers.php?${params.toString()}`;
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const addModal = document.getElementById('addModal');
            const editModal = document.getElementById('editModal');
            if (event.target === addModal) {
                addModal.style.display = 'none';
            }
            if (event.target === editModal) {
                editModal.style.display = 'none';
            }
        }
    </script>
</body>
</html>