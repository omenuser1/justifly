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
$teacher_id = $_GET['teacher_id'] ?? 0;

if (!$teacher_id) {
    header("Location: admin_teachers.php");
    exit();
}

// جلب معلومات المعلم
$stmt = $conn->prepare("
    SELECT t.*, u.username, u.email, sd.name as sub_department_name
    FROM teachers t
    JOIN users u ON t.user_id = u.id
    LEFT JOIN sub_departments sd ON u.sub_department_id = sd.id
    WHERE t.id = ? AND u.department_id = ?
");
$stmt->bind_param("ii", $teacher_id, $department_id);
$stmt->execute();
$teacher = $stmt->get_result()->fetch_assoc();

if (!$teacher) {
    $_SESSION['error'] = "Enseignant non trouvé";
    header("Location: admin_teachers.php");
    exit();
}

// معالجة إضافة تخصيص مادة ومستوى
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_assignment'])) {
    $subject_id = $_POST['subject_id'];
    $level_id = $_POST['level_id'];
    
    // التحقق من عدم وجود التخصيص مسبقًا
    $stmt = $conn->prepare("
        SELECT id FROM teacher_subject_levels 
        WHERE teacher_id = ? AND subject_id = ? AND level_id = ?
    ");
    $stmt->bind_param("iii", $teacher_id, $subject_id, $level_id);
    $stmt->execute();
    
    if ($stmt->get_result()->num_rows === 0) {
        // إضافة التخصيص
        $stmt = $conn->prepare("
            INSERT INTO teacher_subject_levels (teacher_id, subject_id, level_id) 
            VALUES (?, ?, ?)
        ");
        $stmt->bind_param("iii", $teacher_id, $subject_id, $level_id);
        $stmt->execute();
        
        $_SESSION['success'] = "Matière assignée avec succès!";
    } else {
        $_SESSION['error'] = "Cette matière est déjà assignée à ce niveau pour cet enseignant.";
    }
    
    header("Location: assign_teacher_subjects.php?teacher_id=$teacher_id");
    exit();
}

// معالجة إزالة تخصيص مادة ومستوى
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_assignment'])) {
    $assignment_id = $_POST['assignment_id'];
    
    // التحقق من أن التخصيص ينتمي للمعلم الحالي
    $stmt = $conn->prepare("
        SELECT id FROM teacher_subject_levels 
        WHERE id = ? AND teacher_id = ?
    ");
    $stmt->bind_param("ii", $assignment_id, $teacher_id);
    $stmt->execute();
    
    if ($stmt->get_result()->num_rows > 0) {
        // إزالة التخصيص
        $stmt = $conn->prepare("DELETE FROM teacher_subject_levels WHERE id = ?");
        $stmt->bind_param("i", $assignment_id);
        $stmt->execute();
        
        $_SESSION['success'] = "Assignation supprimée avec succès!";
    } else {
        $_SESSION['error'] = "Assignation non trouvée";
    }
    
    header("Location: assign_teacher_subjects.php?teacher_id=$teacher_id");
    exit();
}

// جلب المواد المتاحة في القسم
$stmt = $conn->prepare("
    SELECT DISTINCT s.id, s.name, s.code
    FROM subjects s
    JOIN sub_departments sd ON s.sub_department_id = sd.id
    WHERE sd.department_id = ?
    ORDER BY s.name
");
$stmt->bind_param("i", $department_id);
$stmt->execute();
$subjects = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// جلب المستويات المتاحة في القسم
$stmt = $conn->prepare("
    SELECT l.id, l.name
    FROM levels l
    JOIN sub_departments sd ON l.sub_department_id = sd.id
    WHERE sd.department_id = ?
    ORDER BY l.name
");
$stmt->bind_param("i", $department_id);
$stmt->execute();
$levels = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// جلب التخصيصات الحالية للمعلم
$stmt = $conn->prepare("
    SELECT tsl.*, s.name as subject_name, s.code as subject_code, l.name as level_name
    FROM teacher_subject_levels tsl
    JOIN subjects s ON tsl.subject_id = s.id
    JOIN levels l ON tsl.level_id = l.id
    WHERE tsl.teacher_id = ?
    ORDER BY s.name, l.name
");
$stmt->bind_param("i", $teacher_id);
$stmt->execute();
$current_assignments = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// جلب التخصيصات المتاحة (التي لم يتم تخصيصها بعد)
$available_assignments = [];
foreach ($subjects as $subject) {
    foreach ($levels as $level) {
        $exists = false;
        foreach ($current_assignments as $assignment) {
            if ($assignment['subject_id'] == $subject['id'] && $assignment['level_id'] == $level['id']) {
                $exists = true;
                break;
            }
        }
        if (!$exists) {
            $available_assignments[] = [
                'subject_id' => $subject['id'],
                'subject_name' => $subject['name'],
                'subject_code' => $subject['code'],
                'level_id' => $level['id'],
                'level_name' => $level['name']
            ];
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assigner des Matières - Justifly</title>
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
        
        .card {
            background: var(--bg-card);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }
        
        .assignment-item {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 0.75rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: all 0.3s ease;
        }
        
        .assignment-item:hover {
            background: rgba(255, 255, 255, 0.05);
            transform: translateX(5px);
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
        
        .form-group {
            margin-bottom: 1rem;
        }
        
        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--text-primary);
        }
        
        .form-select {
            width: 100%;
            padding: 0.75rem 1rem;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            color: var(--text-primary);
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        
        .form-select:focus {
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
                <h1 class="text-3xl font-bold mb-2">Assigner des Matières</h1>
                <p class="text-gray-400">
                    Enseignant: <?php echo htmlspecialchars($teacher['first_name'] . ' ' . $teacher['last_name']); ?>
                </p>
            </div>
            <button class="btn-primary" onclick="window.location.href='admin_teachers.php'">
                <i class="fas fa-arrow-left mr-2"></i>
                Retour
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

        <?php if (isset($_SESSION['error'])): ?>
            <div class="bg-red-500/20 border border-red-500/30 rounded-lg p-4 mb-6 flex items-center gap-3">
                <i class="fas fa-exclamation-circle text-red-500"></i>
                <span><?php echo htmlspecialchars($_SESSION['error']); ?></span>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <!-- Teacher Info -->
        <div class="card">
            <h3 class="text-lg font-semibold mb-4">Informations de l'enseignant</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <span class="text-gray-400 text-sm">Nom complet</span>
                    <p class="font-semibold"><?php echo htmlspecialchars($teacher['first_name'] . ' ' . $teacher['last_name']); ?></p>
                </div>
                <div>
                    <span class="text-gray-400 text-sm">Username</span>
                    <p class="font-semibold"><?php echo htmlspecialchars($teacher['username']); ?></p>
                </div>
                <div>
                    <span class="text-gray-400 text-sm">Spécialité</span>
                    <p class="font-semibold"><?php echo htmlspecialchars($teacher['sub_department_name'] ?: 'Non spécifié'); ?></p>
                </div>
            </div>
        </div>

        <!-- Add New Assignment -->
        <div class="card">
            <h3 class="text-lg font-semibold mb-4">Ajouter une nouvelle assignation</h3>
            <form method="POST">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="form-group">
                        <label class="form-label">Matière <span class="text-red-500">*</span></label>
                        <select class="form-select" name="subject_id" required>
                            <option value="">Sélectionnez une matière</option>
                            <?php foreach ($subjects as $subject): ?>
                                <option value="<?php echo $subject['id']; ?>">
                                    <?php echo htmlspecialchars($subject['name'] . ' (' . $subject['code'] . ')'); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Niveau <span class="text-red-500">*</span></label>
                        <select class="form-select" name="level_id" required>
                            <option value="">Sélectionnez un niveau</option>
                            <?php foreach ($levels as $level): ?>
                                <option value="<?php echo $level['id']; ?>">
                                    <?php echo htmlspecialchars($level['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group flex items-end">
                        <button type="submit" name="add_assignment" class="btn-primary w-full">
                            <i class="fas fa-plus mr-2"></i>
                            Ajouter
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Current Assignments -->
        <div class="card">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold">Assignations actuelles</h3>
                <span class="subject-badge"><?php echo count($current_assignments); ?></span>
            </div>
            
            <?php if (empty($current_assignments)): ?>
                <div class="text-center py-8 text-gray-400">
                    <i class="fas fa-book-open text-4xl mb-3 opacity-50"></i>
                    <p>Aucune matière assignée</p>
                </div>
            <?php else: ?>
                <div class="space-y-3">
                    <?php foreach ($current_assignments as $assignment): ?>
                        <div class="assignment-item">
                            <div class="flex items-center gap-3">
                                <div>
                                    <div class="font-semibold"><?php echo htmlspecialchars($assignment['subject_name']); ?></div>
                                    <div class="text-sm text-gray-400"><?php echo htmlspecialchars($assignment['subject_code']); ?></div>
                                </div>
                                <span class="level-badge"><?php echo htmlspecialchars($assignment['level_name']); ?></span>
                            </div>
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="assignment_id" value="<?php echo $assignment['id']; ?>">
                                <button type="submit" name="remove_assignment" class="btn-danger" 
                                        onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette assignation?');">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Available Assignments -->
        <?php if (!empty($available_assignments)): ?>
            <div class="card">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold">Matières disponibles</h3>
                    <span class="subject-badge"><?php echo count($available_assignments); ?></span>
                </div>
                
                <div class="space-y-3">
                    <?php foreach ($available_assignments as $assignment): ?>
                        <div class="assignment-item opacity-75">
                            <div class="flex items-center gap-3">
                                <div>
                                    <div class="font-semibold"><?php echo htmlspecialchars($assignment['subject_name']); ?></div>
                                    <div class="text-sm text-gray-400"><?php echo htmlspecialchars($assignment['subject_code']); ?></div>
                                </div>
                                <span class="level-badge"><?php echo htmlspecialchars($assignment['level_name']); ?></span>
                            </div>
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="subject_id" value="<?php echo $assignment['subject_id']; ?>">
                                <input type="hidden" name="level_id" value="<?php echo $assignment['level_id']; ?>">
                                <button type="submit" name="add_assignment" class="btn-success">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('open');
        }
    </script>
</body>
</html>