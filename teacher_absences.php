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
$filter_subject = $_GET['filter_subject'] ?? '';
$filter_level = $_GET['filter_level'] ?? '';
$search = $_GET['search'] ?? '';
$status_filter = $_GET['status_filter'] ?? '';

// جلب جميع الغيابات
$sql = "
    SELECT a.*, s.first_name, s.last_name, sub.name as subject_name, sub.code as subject_code, 
           l.name as level_name, u.id as student_user_id
    FROM absences a
    JOIN students s ON a.student_id = s.id
    JOIN subjects sub ON a.subject_id = sub.id
    JOIN levels l ON s.level_id = l.id
    JOIN users u ON s.user_id = u.id
    WHERE a.subject_id IN (
        SELECT subject_id FROM teacher_subject_levels WHERE teacher_id = ?
    )
";

$params = [$teacher['id']];
$types = "i";

if ($filter_subject) {
    $sql .= " AND a.subject_id = ?";
    $params[] = $filter_subject;
    $types .= "i";
}

if ($filter_level) {
    $sql .= " AND s.level_id = ?";
    $params[] = $filter_level;
    $types .= "i";
}

if ($status_filter) {
    $sql .= " AND a.status = ?";
    $params[] = $status_filter;
    $types .= "s";
}

if ($search) {
    $sql .= " AND (s.first_name LIKE ? OR s.last_name LIKE ? OR sub.name LIKE ?)";
    $search_param = "%$search%";
    $params[] = $search_param;
    $params[] = $search_param;
    $params[] = $search_param;
    $types .= "sss";
}

$sql .= " ORDER BY a.date DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$absences = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Absences - Justifly</title>
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
        
        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        
        .status-pending {
            background: rgba(251, 191, 36, 0.2);
            color: #fbbf24;
        }
        
        .status-approved {
            background: rgba(16, 185, 129, 0.2);
            color: #10b981;
        }
        
        .status-rejected {
            background: rgba(239, 68, 68, 0.2);
            color: #ef4444;
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
                    <p class="text-sm text-gray-400">Enseignant</p>
                </div>
            </div>
            
            <nav>
                <div class="sidebar-item" onclick="window.location.href='teacher_dashboard.php'">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Tableau de bord</span>
                </div>
                <div class="sidebar-item active">
                    <i class="fas fa-calendar-times"></i>
                    <span>Absences</span>
                </div>
                <div class="sidebar-item" onclick="window.location.href='teacher_schedule.php'">
                    <i class="fas fa-calendar-alt"></i>
                    <span>Emploi du temps</span>
                </div>
                <div class="sidebar-item" onclick="window.location.href='teacher_students.php'">
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
                <h1 class="text-3xl font-bold mb-2">Gestion des Absences</h1>
                <p class="text-gray-400">Consultez toutes les absences de vos étudiants</p>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-gray-800/50 rounded-lg p-4 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-2">Matière</label>
                    <select class="form-select" id="subjectFilter" onchange="applyFilters()">
                        <option value="">Toutes les matières</option>
                        <?php foreach ($subjects as $subject): ?>
                            <option value="<?php echo $subject['id']; ?>" <?php echo $filter_subject == $subject['id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($subject['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
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
                    <label class="block text-sm font-medium mb-2">Statut</label>
                    <select class="form-select" id="statusFilter" onchange="applyFilters()">
                        <option value="">Tous les statuts</option>
                        <option value="pending" <?php echo $status_filter == 'pending' ? 'selected' : ''; ?>>En attente</option>
                        <option value="approved" <?php echo $status_filter == 'approved' ? 'selected' : ''; ?>>Approuvées</option>
                        <option value="rejected" <?php echo $status_filter == 'rejected' ? 'selected' : ''; ?>>Rejetées</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Rechercher</label>
                    <input type="text" class="form-input" id="searchInput" placeholder="Étudiant, matière..." value="<?php echo htmlspecialchars($search); ?>">
                </div>
                <div class="flex items-end">
                    <button class="btn-primary w-full" onclick="applyFilters()">
                        <i class="fas fa-search mr-2"></i>Filtrer
                    </button>
                </div>
            </div>
        </div>

        <!-- Absences Table -->
        <div class="table-container">
            <div class="p-4 border-b border-gray-700">
                <h2 class="text-xl font-bold">Liste des absences</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="border-b border-gray-700">
                        <tr>
                            <th class="text-left p-4">Étudiant</th>
                            <th class="text-left p-4">Matière</th>
                            <th class="text-left p-4">Niveau</th>
                            <th class="text-left p-4">Date</th>
                            <th class="text-left p-4">Motif</th>
                            <th class="text-center p-4">Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($absences)): ?>
                            <tr>
                                <td colspan="6" class="text-center p-8 text-gray-400">
                                    Aucune absence trouvée
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($absences as $absence): ?>
                                <tr class="border-b border-gray-800 hover:bg-gray-800/50">
                                    <td class="p-4">
                                        <div class="font-semibold"><?php echo htmlspecialchars($absence['first_name'] . ' ' . $absence['last_name']); ?></div>
                                    </td>
                                    <td class="p-4">
                                        <div>
                                            <div class="font-semibold"><?php echo htmlspecialchars($absence['subject_name']); ?></div>
                                            <div class="text-sm text-gray-400"><?php echo htmlspecialchars($absence['subject_code']); ?></div>
                                        </div>
                                    </td>
                                    <td class="p-4"><?php echo htmlspecialchars($absence['level_name']); ?></td>
                                    <td class="p-4"><?php echo date('d/m/Y', strtotime($absence['date'])); ?></td>
                                    <td class="p-4">
                                        <?php echo htmlspecialchars($absence['reason'] ?: 'Non spécifié'); ?>
                                        <?php if ($absence['justification_document']): ?>
                                            <br>
                                            <a href="uploads/<?php echo htmlspecialchars($absence['justification_document']); ?>" 
                                               class="text-blue-400 hover:text-blue-300 text-sm" download>
                                                <i class="fas fa-paperclip mr-1"></i>Voir document
                                            </a>
                                        <?php endif; ?>
                                    </td>
                                    <td class="p-4 text-center">
                                        <span class="status-badge status-<?php echo $absence['status']; ?>">
                                            <?php 
                                            switch($absence['status']) {
                                                case 'approved': echo 'Approuvée'; break;
                                                case 'pending': echo 'En attente'; break;
                                                case 'rejected': echo 'Rejetée'; break;
                                            }
                                            ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('open');
        }

        function applyFilters() {
            const subject = document.getElementById('subjectFilter').value;
            const level = document.getElementById('levelFilter').value;
            const status = document.getElementById('statusFilter').value;
            const search = document.getElementById('searchInput').value;
            
            const params = new URLSearchParams();
            if (subject) params.append('filter_subject', subject);
            if (level) params.append('filter_level', level);
            if (status) params.append('status_filter', status);
            if (search) params.append('search', search);
            
            window.location.href = 'teacher_absences.php?' + params.toString();
        }
    </script>
</body>
</html>