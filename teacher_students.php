<?php
require_once 'config.php';
session_start();

// التحقق من تسجيل دخول المعلم
if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'teacher') {
    header("Location: loggin-e.php");
    exit();
}

$teacher_id = $_SESSION['user_id'];

// جلب بيانات المعلم
$stmt = $conn->prepare("
    SELECT t.*, u.username, u.email 
    FROM teachers t
    JOIN users u ON t.user_id = u.id
    WHERE t.user_id = ?
");
$stmt->bind_param("i", $teacher_id);
$stmt->execute();
$teacher = $stmt->get_result()->fetch_assoc();

// جلب المواد والمستويات التي يدرسها المعلم
$stmt = $conn->prepare("
    SELECT tsl.*, s.name as subject_name, s.code as subject_code, l.name as level_name
    FROM teacher_subject_levels tsl
    JOIN subjects s ON tsl.subject_id = s.id
    JOIN levels l ON tsl.level_id = l.id
    WHERE tsl.teacher_id = ?
    ORDER BY s.name, l.name
");
$stmt->bind_param("i", $teacher['id']);
$stmt->execute();
$teacher_subjects_levels = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// تجميع المواد والمستويات الفريدة
$subjects = [];
$levels = [];
foreach ($teacher_subjects_levels as $item) {
    if (!isset($subjects[$item['subject_id']])) {
        $subjects[$item['subject_id']] = [
            'id' => $item['subject_id'],
            'name' => $item['subject_name'],
            'code' => $item['subject_code']
        ];
    }
    
    if (!isset($levels[$item['level_id']])) {
        $levels[$item['level_id']] = [
            'id' => $item['level_id'],
            'name' => $item['level_name']
        ];
    }
}

// معالجة الفلاتر
$filter_level = $_GET['filter_level'] ?? '';
$filter_subject = $_GET['filter_subject'] ?? '';
$search = $_GET['search'] ?? '';

// جلب الطلاب
$sql = "
    SELECT DISTINCT s.*, u.username, u.email, l.name as level_name, sd.name as sub_department_name
    FROM students s
    JOIN users u ON s.user_id = u.id
    JOIN levels l ON s.level_id = l.id
    LEFT JOIN sub_departments sd ON u.sub_department_id = sd.id
    WHERE s.level_id IN (
        SELECT level_id FROM teacher_subject_levels WHERE teacher_id = ?
    )
";

$params = [$teacher['id']];
$types = "i";

if ($filter_level) {
    $sql .= " AND s.level_id = ?";
    $params[] = $filter_level;
    $types .= "i";
}

if ($search) {
    $sql .= " AND (s.first_name LIKE ? OR s.last_name LIKE ? OR u.username LIKE ?)";
    $search_param = "%$search%";
    $params[] = $search_param;
    $params[] = $search_param;
    $params[] = $search_param;
    $types .= "sss";
}

$sql .= " ORDER BY s.first_name, s.last_name";

$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$students = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// حساب إحصائيات الغيابات لكل طالب
foreach ($students as &$student) {
    $stmt = $conn->prepare("
        SELECT 
            COUNT(*) as total_absences,
            SUM(CASE WHEN status = 'approved' THEN 1 ELSE 0 END) as approved_absences,
            SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending_absences
        FROM absences 
        WHERE student_id = ? AND subject_id IN (
            SELECT subject_id FROM teacher_subject_levels WHERE teacher_id = ?
        )
    ");
    $stmt->bind_param("ii", $student['id'], $teacher['id']);
    $stmt->execute();
    $absence_stats = $stmt->get_result()->fetch_assoc();
    
    $student['total_absences'] = $absence_stats['total_absences'];
    $student['approved_absences'] = $absence_stats['approved_absences'];
    $student['pending_absences'] = $absence_stats['pending_absences'];
}
?>

<!DOCTYPE html>
<html lang="fr" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Étudiants - Justifly</title>
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
        
        .student-card {
            background: var(--bg-card);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            padding: 1.5rem;
            transition: all 0.3s ease;
        }
        
        .student-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
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
                    <p class="text-sm text-gray-400">Enseignant</p>
                </div>
            </div>
            
            <nav>
                <div class="sidebar-item" onclick="window.location.href='teacher_dashboard.php'">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Tableau de bord</span>
                </div>
                <div class="sidebar-item" onclick="window.location.href='teacher_absences.php'">
                    <i class="fas fa-calendar-times"></i>
                    <span>Absences</span>
                </div>
                <div class="sidebar-item" onclick="window.location.href='teacher_schedule.php'">
                    <i class="fas fa-calendar-alt"></i>
                    <span>Emploi du temps</span>
                </div>
                <div class="sidebar-item active">
                    <i class="fas fa-user-graduate"></i>
                    <span>Étudiants</span>
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
                <h1 class="text-3xl font-bold mb-2">Étudiants</h1>
                <p class="text-gray-400">Gérez vos étudiants et suivez leurs absences</p>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-gray-800/50 rounded-lg p-4 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-2">Niveau</label>
                    <select class="form-select" id="levelFilter" onchange="applyFilters()">
                        <option value="">Tous les niveaux</option>
                        <?php foreach ($levels as $level): ?>
                            <option value="<?php echo $level['id']; ?>" <?php echo $filter_level == $level['id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($level['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Rechercher</label>
                    <input type="text" class="form-input" id="searchInput" placeholder="Nom, prénom, username..." value="<?php echo htmlspecialchars($search); ?>">
                </div>
                <div class="flex items-end">
                    <button class="btn-primary w-full" onclick="applyFilters()">
                        <i class="fas fa-search mr-2"></i>Filtrer
                    </button>
                </div>
            </div>
        </div>

        <!-- Students Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php if (empty($students)): ?>
                <div class="col-span-full text-center py-12">
                    <i class="fas fa-user-slash text-6xl text-gray-400 mb-4"></i>
                    <h3 class="text-xl font-semibold text-gray-400">Aucun étudiant trouvé</h3>
                </div>
            <?php else: ?>
                <?php foreach ($students as $student): ?>
                    <div class="student-card">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-indigo-600 rounded-full flex items-center justify-center text-white font-bold">
                                    <?php echo strtoupper(substr($student['first_name'], 0, 1) . substr($student['last_name'], 0, 1)); ?>
                                </div>
                                <div>
                                    <h3 class="font-bold"><?php echo htmlspecialchars($student['first_name'] . ' ' . $student['last_name']); ?></h3>
                                    <p class="text-sm text-gray-400">@<?php echo htmlspecialchars($student['username']); ?></p>
                                </div>
                            </div>
                            <i class="fas fa-user-graduate text-purple-400"></i>
                        </div>
                        
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-400">Niveau:</span>
                                <span class="font-semibold"><?php echo htmlspecialchars($student['level_name']); ?></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-400">Spécialité:</span>
                                <span class="font-semibold"><?php echo htmlspecialchars($student['sub_department_name'] ?: 'Non spécifiée'); ?></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-400">Email:</span>
                                <span class="font-semibold"><?php echo htmlspecialchars($student['email']); ?></span>
                            </div>
                        </div>
                        
                        <div class="mt-4 pt-4 border-t border-gray-700">
                            <div class="grid grid-cols-3 gap-4 text-center">
                                <div>
                                    <div class="text-2xl font-bold text-blue-400"><?php echo $student['total_absences']; ?></div>
                                    <div class="text-xs text-gray-400">Total</div>
                                </div>
                                <div>
                                    <div class="text-2xl font-bold text-green-400"><?php echo $student['approved_absences']; ?></div>
                                    <div class="text-xs text-gray-400">Approuvées</div>
                                </div>
                                <div>
                                    <div class="text-2xl font-bold text-yellow-400"><?php echo $student['pending_absences']; ?></div>
                                    <div class="text-xs text-gray-400">En attente</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-4 flex gap-2">
                            <button class="btn-primary flex-1" onclick="viewStudentDetails(<?php echo $student['id']; ?>)">
                                <i class="fas fa-eye mr-2"></i>Détails
                            </button>
                            <button class="bg-gray-700 hover:bg-gray-600 flex-1 px-3 py-2 rounded-lg transition-colors" onclick="viewStudentAbsences(<?php echo $student['id']; ?>)">
                                <i class="fas fa-calendar-times mr-2"></i>Absences
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('open');
        }

        function applyFilters() {
            const level = document.getElementById('levelFilter').value;
            const search = document.getElementById('searchInput').value;
            
            const params = new URLSearchParams();
            if (level) params.append('filter_level', level);
            if (search) params.append('search', search);
            
            window.location.href = 'teacher_students.php?' + params.toString();
        }

        function viewStudentDetails(studentId) {
            // Placeholder for viewing student details
            alert('Fonctionnalité de détails des étudiants en développement');
        }

        function viewStudentAbsences(studentId) {
            window.location.href = 'teacher_absences.php?student_id=' + studentId;
        }
    </script>
</body>
</html>