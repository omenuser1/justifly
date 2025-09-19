<?php
require_once 'config.php';
session_start();

// التحقق من تسجيل دخول المعلم
if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'teacher') {
    header("Location: loggin-e.php");
    exit();
}

$teacher_id = $_SESSION['user_id'];
$department_id = $_SESSION['department_id'];
$sub_department_id = $_SESSION['sub_department_id'];

// جلب بيانات المعلم
$stmt = $conn->prepare("
    SELECT t.*, u.username, u.email, d.name as department_name, sd.name as sub_department_name
    FROM teachers t
    JOIN users u ON t.user_id = u.id
    JOIN departments d ON u.department_id = d.id
    LEFT JOIN sub_departments sd ON u.sub_department_id = sd.id
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

// جلب الغيابات المقبولة فقط
$sql = "
    SELECT a.*, s.first_name, s.last_name, sub.name as subject_name, sub.code as subject_code, 
           l.name as level_name, u.id as student_user_id
    FROM absences a
    JOIN students s ON a.student_id = s.id
    JOIN subjects sub ON a.subject_id = sub.id
    JOIN levels l ON s.level_id = l.id
    JOIN users u ON s.user_id = u.id
    WHERE a.status = 'approved' AND a.subject_id IN (
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

// جلب الإشعارات غير المقروءة
$stmt = $conn->prepare("
    SELECT * FROM notifications 
    WHERE user_id = ? AND is_read = 0 
    ORDER BY created_at DESC
");
$stmt->bind_param("i", $teacher_id);
$stmt->execute();
$unread_notifications = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

$unread_count = count($unread_notifications);

// تحديث الإشعارات كمقروءة
if (isset($_GET['mark_as_read']) && $_GET['mark_as_read'] == 1) {
    $stmt = $conn->prepare("UPDATE notifications SET is_read = 1 WHERE user_id = ?");
    $stmt->bind_param("i", $teacher_id);
    $stmt->execute();
    
    $_SESSION['success'] = "Toutes les notifications ont été marquées comme lues.";
    header("Location: teacher_dashboard.php");
    exit();
}

// الحصول على إحصائيات الغيابات المقبولة فقط
$stmt = $conn->prepare("
    SELECT 
        COUNT(*) as total_absences
    FROM absences a
    WHERE a.status = 'approved' AND a.subject_id IN (
        SELECT subject_id FROM teacher_subject_levels WHERE teacher_id = ?
    )
");
$stmt->bind_param("i", $teacher['id']);
$stmt->execute();
$absence_stats = $stmt->get_result()->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="fr" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord Enseignant - Justifly</title>
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
        
        .stat-card {
            background: var(--bg-card);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            padding: 1.5rem;
            transition: all 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
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
        
        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: #ef4444;
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            font-weight: bold;
        }
        
        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        
        .status-approved {
            background: rgba(16, 185, 129, 0.2);
            color: #10b981;
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
        
        .notification-item {
            padding: 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
        }
        
        .notification-item:hover {
            background: rgba(255, 255, 255, 0.05);
        }
        
        .notification-item:last-child {
            border-bottom: none;
        }
        
        .notification-item.unread {
            background: rgba(102, 126, 234, 0.1);
            border-left: 3px solid var(--accent-color);
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
                <div class="sidebar-item active">
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
                <h1 class="text-3xl font-bold mb-2">Tableau de bord</h1>
                <p class="text-gray-400">Bienvenue, <?php echo htmlspecialchars($teacher['first_name'] . ' ' . $teacher['last_name']); ?></p>
            </div>
            <div class="flex items-center gap-4">
                <div class="relative" onclick="toggleNotifications()">
                    <i class="fas fa-bell text-2xl cursor-pointer hover:text-purple-400 transition-colors"></i>
                    <?php if ($unread_count > 0): ?>
                        <span class="notification-badge"><?php echo $unread_count; ?></span>
                    <?php endif; ?>
                </div>
                <button class="md:hidden" onclick="toggleSidebar()">
                    <i class="fas fa-bars text-2xl"></i>
                </button>
            </div>
        </div>

        <!-- Success Message -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="bg-green-500/20 border border-green-500/30 rounded-lg p-4 mb-6 flex items-center gap-3">
                <i class="fas fa-check-circle text-green-500"></i>
                <span><?php echo htmlspecialchars($_SESSION['success']); ?></span>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="stat-card text-center">
                <i class="fas fa-calendar-check text-3xl text-green-500 mb-3"></i>
                <h3 class="text-2xl font-bold"><?php echo $absence_stats['total_absences']; ?></h3>
                <p class="text-gray-400">Absences approuvées</p>
            </div>
            <div class="stat-card text-center">
                <i class="fas fa-book text-3xl text-blue-500 mb-3"></i>
                <h3 class="text-2xl font-bold"><?php echo count($subjects); ?></h3>
                <p class="text-gray-400">Matières</p>
            </div>
            <div class="stat-card text-center">
                <i class="fas fa-layer-group text-3xl text-purple-500 mb-3"></i>
                <h3 class="text-2xl font-bold"><?php echo count($levels); ?></h3>
                <p class="text-gray-400">Niveaux</p>
            </div>
            <div class="stat-card text-center">
                <i class="fas fa-user-graduate text-3xl text-yellow-500 mb-3"></i>
                <h3 class="text-2xl font-bold">
                    <?php 
                    // حساب عدد الطلاب الفريدين
                    $unique_students = array_unique(array_column($absences, 'student_id'));
                    echo count($unique_students);
                    ?>
                </h3>
                <p class="text-gray-400">Étudiants</p>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-gray-800/50 rounded-lg p-4 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
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
                <h2 class="text-xl font-bold">Absences approuvées des étudiants</h2>
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
                                    Aucune absence approuvée trouvée
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
                                        <span class="status-badge status-approved">
                                            Approuvée
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

    <!-- Notifications Panel -->
    <div id="notificationsPanel" class="fixed right-0 top-0 h-full w-80 bg-gray-800 z-50 transform translate-x-full transition-transform duration-300 shadow-lg">
        <div class="p-4 border-b border-gray-700 flex justify-between items-center">
            <h3 class="text-lg font-bold">Notifications</h3>
            <div class="flex gap-2">
                <?php if ($unread_count > 0): ?>
                    <a href="teacher_dashboard.php?mark_as_read=1" class="text-sm text-blue-400 hover:text-blue-300">
                        Marquer tout comme lu
                    </a>
                <?php endif; ?>
                <button onclick="toggleNotifications()" class="text-gray-400 hover:text-white">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        <div class="overflow-y-auto h-full pb-20">
            <?php if (empty($unread_notifications)): ?>
                <div class="p-8 text-center text-gray-400">
                    <i class="fas fa-bell-slash text-4xl mb-4"></i>
                    <p>Aucune notification</p>
                </div>
            <?php else: ?>
                <?php foreach ($unread_notifications as $notification): ?>
                    <div class="notification-item unread">
                        <div class="text-sm text-gray-400 mb-1">
                            <?php echo date('d/m/Y H:i', strtotime($notification['created_at'])); ?>
                        </div>
                        <div><?php echo htmlspecialchars($notification['message']); ?></div>
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

        function toggleNotifications() {
            const panel = document.getElementById('notificationsPanel');
            panel.classList.toggle('translate-x-full');
        }

        function applyFilters() {
            const subject = document.getElementById('subjectFilter').value;
            const level = document.getElementById('levelFilter').value;
            const search = document.getElementById('searchInput').value;
            
            const params = new URLSearchParams();
            if (subject) params.append('filter_subject', subject);
            if (level) params.append('filter_level', level);
            if (search) params.append('search', search);
            
            window.location.href = 'teacher_dashboard.php?' + params.toString();
        }

        // Close notifications when clicking outside
        document.addEventListener('click', function(event) {
            const panel = document.getElementById('notificationsPanel');
            const bellIcon = event.target.closest('.fa-bell');
            
            if (!panel.contains(event.target) && !bellIcon && !panel.classList.contains('translate-x-full')) {
                panel.classList.add('translate-x-full');
            }
        });
    </script>
</body>
</html>