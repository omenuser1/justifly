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

// معالجة إضافة مادة جديدة
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_subject'])) {
    $name = trim($_POST['name']);
    $code = trim($_POST['code']);
    $sub_department_id = $_POST['sub_department_id'];
    
    // التحقق من صحة البيانات
    if (empty($name) || empty($code) || empty($sub_department_id)) {
        $error = "Veuillez remplir tous les champs obligatoires.";
    } else {
        // التحقق من عدم وجود الكود مسبقًا
        $stmt = $conn->prepare("SELECT id FROM subjects WHERE code = ?");
        $stmt->bind_param("s", $code);
        $stmt->execute();
        if ($stmt->get_result()->num_rows > 0) {
            $error = "Ce code de matière existe déjà.";
        } else {
            // إضافة المادة
            $stmt = $conn->prepare("
                INSERT INTO subjects (name, code, sub_department_id) 
                VALUES (?, ?, ?)
            ");
            $stmt->bind_param("ssi", $name, $code, $sub_department_id);
            $stmt->execute();
            
            $_SESSION['success'] = "Matière ajoutée avec succès!";
            header("Location: admin_subjects.php");
            exit();
        }
    }
}

// معالجة تعديل مادة
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_subject'])) {
    $subject_id = $_POST['subject_id'];
    $name = trim($_POST['name']);
    $code = trim($_POST['code']);
    $sub_department_id = $_POST['sub_department_id'];
    
    // التحقق من صحة البيانات
    if (empty($name) || empty($code) || empty($sub_department_id)) {
        $error = "Veuillez remplir tous les champs obligatoires.";
    } else {
        // التحقق من عدم وجود الكود لمادة أخرى
        $stmt = $conn->prepare("SELECT id FROM subjects WHERE code = ? AND id != ?");
        $stmt->bind_param("si", $code, $subject_id);
        $stmt->execute();
        if ($stmt->get_result()->num_rows > 0) {
            $error = "Ce code de matière existe déjà pour une autre matière.";
        } else {
            // تحديث المادة
            $stmt = $conn->prepare("
                UPDATE subjects SET name = ?, code = ?, sub_department_id = ? 
                WHERE id = ?
            ");
            $stmt->bind_param("ssii", $name, $code, $sub_department_id, $subject_id);
            $stmt->execute();
            
            $_SESSION['success'] = "Matière modifiée avec succès!";
            header("Location: admin_subjects.php");
            exit();
        }
    }
}

// معالجة حذف مادة
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_subject'])) {
    $subject_id = $_POST['subject_id'];
    
    // بدء المعاملة
    $conn->begin_transaction();
    
    try {
        // حذف العلاقات مع المستويات
        $stmt = $conn->prepare("DELETE FROM subject_levels WHERE subject_id = ?");
        $stmt->bind_param("i", $subject_id);
        $stmt->execute();
        
        // حذف العلاقات مع المعلمين
        $stmt = $conn->prepare("DELETE FROM teacher_subject_levels WHERE subject_id = ?");
        $stmt->bind_param("i", $subject_id);
        $stmt->execute();
        
        // حذف الغيابات المرتبطة
        $stmt = $conn->prepare("DELETE FROM absences WHERE subject_id = ?");
        $stmt->bind_param("i", $subject_id);
        $stmt->execute();
        
        // حذف المادة
        $stmt = $conn->prepare("DELETE FROM subjects WHERE id = ?");
        $stmt->bind_param("i", $subject_id);
        $stmt->execute();
        
        $conn->commit();
        $_SESSION['success'] = "Matière supprimée avec succès!";
    } catch (Exception $e) {
        $conn->rollback();
        $error = "Erreur lors de la suppression de la matière: " . $e->getMessage();
    }
    
    header("Location: admin_subjects.php");
    exit();
}

// معالجة إضافة مستوى لمادة
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_level_to_subject'])) {
    $subject_id = $_POST['subject_id'];
    $level_id = $_POST['level_id'];
    
    // التحقق من عدم وجود التخصيص مسبقًا
    $stmt = $conn->prepare("
        SELECT id FROM subject_levels 
        WHERE subject_id = ? AND level_id = ?
    ");
    $stmt->bind_param("ii", $subject_id, $level_id);
    $stmt->execute();
    
    if ($stmt->get_result()->num_rows === 0) {
        // إضافة التخصيص
        $stmt = $conn->prepare("
            INSERT INTO subject_levels (subject_id, level_id) 
            VALUES (?, ?)
        ");
        $stmt->bind_param("ii", $subject_id, $level_id);
        $stmt->execute();
        
        $_SESSION['success'] = "Niveau ajouté à la matière avec succès!";
    } else {
        $_SESSION['error'] = "Ce niveau est déjà assigné à cette matière.";
    }
    
    header("Location: admin_subjects.php");
    exit();
}

// معالجة إزالة مستوى من مادة
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_level_from_subject'])) {
    $subject_level_id = $_POST['subject_level_id'];
    
    // حذف التخصيص
    $stmt = $conn->prepare("DELETE FROM subject_levels WHERE id = ?");
    $stmt->bind_param("i", $subject_level_id);
    $stmt->execute();
    
    $_SESSION['success'] = "Niveau supprimé de la matière avec succès!";
    header("Location: admin_subjects.php");
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

// جلب المستويات للقسم
$stmt = $conn->prepare("
    SELECT l.id, l.name, sd.name as sub_department_name
    FROM levels l
    JOIN sub_departments sd ON l.sub_department_id = sd.id
    WHERE sd.department_id = ?
    ORDER BY l.name
");
$stmt->bind_param("i", $department_id);
$stmt->execute();
$levels = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// جلب جميع المواد في القسم
$search = $_GET['search'] ?? '';
$filter_subdept = $_GET['filter_subdept'] ?? '';

$stmt = $conn->prepare("
    SELECT s.*, sd.name as sub_department_name
    FROM subjects s
    JOIN sub_departments sd ON s.sub_department_id = sd.id
    WHERE sd.department_id = ?
    AND (s.name LIKE ? OR s.code LIKE ?)
    " . ($filter_subdept ? "AND s.sub_department_id = ?" : "") . "
    ORDER BY s.name
");

$search_param = "%$search%";
if ($filter_subdept) {
    $stmt->bind_param("isssi", $department_id, $search_param, $search_param, $filter_subdept);
} else {
    $stmt->bind_param("iss", $department_id, $search_param, $search_param);
}
$stmt->execute();
$subjects = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Matières - Justifly</title>
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
        
        .subject-badge {
            background: var(--accent-color);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 600;
        }
        
        .level-badge {
            background: rgba(16, 185, 129, 0.2);
            color: #10b981;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.875rem;
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
                <div class="sidebar-item active">
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
                <h1 class="text-3xl font-bold mb-2">Gestion des Matières</h1>
                <p class="text-gray-400">Gérez les matières de votre département</p>
            </div>
            <button class="btn-primary" onclick="openAddModal()">
                <i class="fas fa-plus mr-2"></i>
                Ajouter une matière
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
                    <input type="text" class="form-input" id="searchInput" placeholder="Nom, code..." value="<?php echo htmlspecialchars($search); ?>">
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
                    <button class="btn-primary w-full" onclick="filterSubjects()">
                        <i class="fas fa-search mr-2"></i>
                        Rechercher
                    </button>
                </div>
            </div>
        </div>

        <!-- Subjects Table -->
        <div class="table-container">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="border-b border-gray-700">
                        <tr>
                            <th class="text-left p-4">Nom</th>
                            <th class="text-left p-4">Code</th>
                            <th class="text-left p-4">Spécialité</th>
                            <th class="text-left p-4">Niveaux</th>
                            <th class="text-left p-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($subjects)): ?>
                            <tr>
                                <td colspan="5" class="text-center p-8 text-gray-400">
                                    Aucune matière trouvée
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($subjects as $subject): ?>
                                <?php
                                // جلب المستويات المرتبطة بالمادة
                                $stmt = $conn->prepare("
                                    SELECT l.name 
                                    FROM subject_levels sl
                                    JOIN levels l ON sl.level_id = l.id
                                    WHERE sl.subject_id = ?
                                    ORDER BY l.name
                                ");
                                $stmt->bind_param("i", $subject['id']);
                                $stmt->execute();
                                $subject_levels = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
                                $level_names = array_column($subject_levels, 'name');
                                ?>
                                <tr class="border-b border-gray-800 hover:bg-gray-800/50">
                                    <td class="p-4">
                                        <div class="font-semibold"><?php echo htmlspecialchars($subject['name']); ?></div>
                                        <div class="text-sm text-gray-400">ID: <?php echo $subject['id']; ?></div>
                                    </td>
                                    <td class="p-4">
                                        <span class="subject-badge"><?php echo htmlspecialchars($subject['code']); ?></span>
                                    </td>
                                    <td class="p-4"><?php echo htmlspecialchars($subject['sub_department_name']); ?></td>
                                    <td class="p-4">
                                        <?php if (empty($level_names)): ?>
                                            <span class="text-gray-400 text-sm">Aucun niveau</span>
                                        <?php else: ?>
                                            <div class="flex flex-wrap gap-1">
                                                <?php foreach ($level_names as $level_name): ?>
                                                    <span class="level-badge"><?php echo htmlspecialchars($level_name); ?></span>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td class="p-4">
                                        <div class="flex gap-2">
                                            <button class="btn-warning" onclick="editSubject(<?php echo $subject['id']; ?>)">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn-success" onclick="manageLevels(<?php echo $subject['id']; ?>)">
                                                <i class="fas fa-layer-group"></i>
                                            </button>
                                            <button class="btn-danger" onclick="deleteSubject(<?php echo $subject['id']; ?>)">
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

    <!-- Add Subject Modal -->
    <div id="addModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('addModal')">&times;</span>
            <h2 class="text-xl font-bold mb-4">Ajouter une matière</h2>
            <form method="POST">
                <div class="grid grid-cols-1 gap-4">
                    <div class="form-group">
                        <label class="form-label">Nom de la matière <span class="text-red-500">*</span></label>
                        <input type="text" class="form-input" name="name" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Code <span class="text-red-500">*</span></label>
                        <input type="text" class="form-input" name="code" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Spécialité <span class="text-red-500">*</span></label>
                        <select class="form-select" name="sub_department_id" required>
                            <option value="">Sélectionnez une spécialité</option>
                            <?php foreach ($sub_departments as $sub_dept): ?>
                                <option value="<?php echo $sub_dept['id']; ?>"><?php echo htmlspecialchars($sub_dept['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="flex gap-3 mt-6">
                    <button type="submit" name="add_subject" class="btn-primary flex-1">
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

    <!-- Edit Subject Modal -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('editModal')">&times;</span>
            <h2 class="text-xl font-bold mb-4">Modifier une matière</h2>
            <form method="POST" id="editForm">
                <input type="hidden" name="subject_id" id="editSubjectId">
                <div class="grid grid-cols-1 gap-4">
                    <div class="form-group">
                        <label class="form-label">Nom de la matière <span class="text-red-500">*</span></label>
                        <input type="text" class="form-input" name="name" id="editName" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Code <span class="text-red-500">*</span></label>
                        <input type="text" class="form-input" name="code" id="editCode" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Spécialité <span class="text-red-500">*</span></label>
                        <select class="form-select" name="sub_department_id" id="editSubDeptId" required>
                            <option value="">Sélectionnez une spécialité</option>
                            <?php foreach ($sub_departments as $sub_dept): ?>
                                <option value="<?php echo $sub_dept['id']; ?>"><?php echo htmlspecialchars($sub_dept['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="flex gap-3 mt-6">
                    <button type="submit" name="edit_subject" class="btn-primary flex-1">
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

    <!-- Manage Levels Modal -->
    <div id="levelsModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('levelsModal')">&times;</span>
            <h2 class="text-xl font-bold mb-4">Gérer les niveaux</h2>
            <div id="levelsContent">
                <!-- Content will be loaded via AJAX -->
            </div>
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

        function editSubject(id) {
            // Fetch subject data via AJAX
            fetch(`get_subject_data.php?id=${id}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('editSubjectId').value = data.id;
                    document.getElementById('editName').value = data.name;
                    document.getElementById('editCode').value = data.code;
                    document.getElementById('editSubDeptId').value = data.sub_department_id;
                    document.getElementById('editModal').style.display = 'block';
                })
                .catch(error => {
                    alert('Erreur lors du chargement des données de la matière');
                });
        }

        function manageLevels(id) {
            // Load levels management via AJAX
            fetch(`manage_subject_levels.php?subject_id=${id}`)
                .then(response => response.text())
                .then(html => {
                    document.getElementById('levelsContent').innerHTML = html;
                    document.getElementById('levelsModal').style.display = 'block';
                })
                .catch(error => {
                    alert('Erreur lors du chargement de la gestion des niveaux');
                });
        }

        function deleteSubject(id) {
            if (confirm('Êtes-vous sûr de vouloir supprimer cette matière? Cette action est irréversible et supprimera toutes les données associées.')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.innerHTML = `<input type="hidden" name="delete_subject" value="${id}">`;
                document.body.appendChild(form);
                form.submit();
            }
        }

        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }

        function filterSubjects() {
            const search = document.getElementById('searchInput').value;
            const subdept = document.getElementById('subdeptFilter').value;
            const params = new URLSearchParams();
            if (search) params.append('search', search);
            if (subdept) params.append('filter_subdept', subdept);
            window.location.href = `admin_subjects.php?${params.toString()}`;
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const addModal = document.getElementById('addModal');
            const editModal = document.getElementById('editModal');
            const levelsModal = document.getElementById('levelsModal');
            if (event.target === addModal) {
                addModal.style.display = 'none';
            }
            if (event.target === editModal) {
                editModal.style.display = 'none';
            }
            if (event.target === levelsModal) {
                levelsModal.style.display = 'none';
            }
        }
    </script>
</body>
</html>