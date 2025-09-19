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

// معالجة طلبات التقارير
$report_type = $_GET['report_type'] ?? 'overview';
$start_date = $_GET['start_date'] ?? date('Y-m-d', strtotime('-30 days'));
$end_date = $_GET['end_date'] ?? date('Y-m-d');
$filter_subdept = $_GET['filter_subdept'] ?? '';
$filter_level = $_GET['filter_level'] ?? '';
$filter_subject = $_GET['filter_subject'] ?? '';

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
    SELECT l.id, l.name 
    FROM levels l
    JOIN sub_departments sd ON l.sub_department_id = sd.id
    WHERE sd.department_id = ?
    ORDER BY l.name
");
$stmt->bind_param("i", $department_id);
$stmt->execute();
$levels = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// جلب المواد للقسم
$stmt = $conn->prepare("
    SELECT s.id, s.name, s.code 
    FROM subjects s
    JOIN sub_departments sd ON s.sub_department_id = sd.id
    WHERE sd.department_id = ?
    ORDER BY s.name
");
$stmt->bind_param("i", $department_id);
$stmt->execute();
$subjects = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// دالة للحصول على إحصائيات عامة
function getGeneralStats($conn, $department_id, $start_date, $end_date, $filter_subdept, $filter_level) {
    // أولاً، نحصل على عدد المواد في القسم
    $subject_sql = "
        SELECT COUNT(DISTINCT id) as total_subjects
        FROM subjects
        WHERE sub_department_id IN (
            SELECT id FROM sub_departments WHERE department_id = ?
        )
    ";
    $subject_stmt = $conn->prepare($subject_sql);
    $subject_stmt->bind_param("i", $department_id);
    $subject_stmt->execute();
    $subject_result = $subject_stmt->get_result()->fetch_assoc();
    $total_subjects = $subject_result['total_subjects'];

    // الآن، الاستعلام الرئيسي للإحصائيات الأخرى
    $sql = "
        SELECT 
            COUNT(DISTINCT st.id) as total_students,
            COUNT(DISTINCT t.id) as total_teachers,
            COUNT(a.id) as total_absences,
            SUM(CASE WHEN a.status = 'pending' THEN 1 ELSE 0 END) as pending_absences,
            SUM(CASE WHEN a.status = 'approved' THEN 1 ELSE 0 END) as approved_absences,
            SUM(CASE WHEN a.status = 'rejected' THEN 1 ELSE 0 END) as rejected_absences
        FROM users u
        LEFT JOIN students st ON u.id = st.user_id
        LEFT JOIN teachers t ON u.id = t.user_id
        LEFT JOIN absences a ON st.id = a.student_id AND a.date BETWEEN ? AND ?
        WHERE u.department_id = ? AND u.role IN ('student', 'teacher')
    ";
    
    $params = [$start_date, $end_date, $department_id];
    $types = "ssi";
    
    if ($filter_subdept) {
        $sql .= " AND u.sub_department_id = ?";
        $params[] = $filter_subdept;
        $types .= "i";
    }
    
    if ($filter_level) {
        $sql .= " AND st.level_id = ?";
        $params[] = $filter_level;
        $types .= "i";
    }
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    
    // إضافة عدد المواد إلى النتيجة
    $result['total_subjects'] = $total_subjects;
    
    return $result;
}

// دالة للحصول على تقارير الغيابات حسب المادة
function getAbsencesBySubject($conn, $department_id, $start_date, $end_date, $filter_subdept, $filter_level) {
    $sql = "
        SELECT 
            sub.id,
            sub.name,
            sub.code,
            COUNT(a.id) as total_absences,
            SUM(CASE WHEN a.status = 'pending' THEN 1 ELSE 0 END) as pending,
            SUM(CASE WHEN a.status = 'approved' THEN 1 ELSE 0 END) as approved,
            SUM(CASE WHEN a.status = 'rejected' THEN 1 ELSE 0 END) as rejected
        FROM subjects sub
        LEFT JOIN absences a ON sub.id = a.subject_id AND a.date BETWEEN ? AND ?
        JOIN sub_departments sd ON sub.sub_department_id = sd.id
        WHERE sd.department_id = ?
    ";
    
    $params = [$start_date, $end_date, $department_id];
    $types = "ssi";
    
    if ($filter_subdept) {
        $sql .= " AND sd.id = ?";
        $params[] = $filter_subdept;
        $types .= "i";
    }
    
    if ($filter_level) {
        $sql .= " AND a.student_id IN (SELECT id FROM students WHERE level_id = ?)";
        $params[] = $filter_level;
        $types .= "i";
    }
    
    $sql .= " GROUP BY sub.id ORDER BY total_absences DESC";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

// دالة للحصول على تقارير الغيابات حسب المستوى
function getAbsencesByLevel($conn, $department_id, $start_date, $end_date, $filter_subdept) {
    $sql = "
        SELECT 
            l.id,
            l.name,
            COUNT(DISTINCT st.id) as total_students,
            COUNT(a.id) as total_absences,
            SUM(CASE WHEN a.status = 'pending' THEN 1 ELSE 0 END) as pending,
            SUM(CASE WHEN a.status = 'approved' THEN 1 ELSE 0 END) as approved,
            SUM(CASE WHEN a.status = 'rejected' THEN 1 ELSE 0 END) as rejected
        FROM levels l
        JOIN sub_departments sd ON l.sub_department_id = sd.id
        LEFT JOIN students st ON l.id = st.level_id
        LEFT JOIN absences a ON st.id = a.student_id AND a.date BETWEEN ? AND ?
        WHERE sd.department_id = ?
    ";
    
    $params = [$start_date, $end_date, $department_id];
    $types = "ssi";
    
    if ($filter_subdept) {
        $sql .= " AND sd.id = ?";
        $params[] = $filter_subdept;
        $types .= "i";
    }
    
    $sql .= " GROUP BY l.id ORDER BY l.name";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

// دالة للحصول على تقارير الغيابات حسب الطالب
function getAbsencesByStudent($conn, $department_id, $start_date, $end_date, $filter_subdept, $filter_level, $filter_subject) {
    $sql = "
        SELECT 
            st.id,
            st.first_name,
            st.last_name,
            l.name as level_name,
            sd.name as sub_department_name,
            COUNT(a.id) as total_absences,
            SUM(CASE WHEN a.status = 'pending' THEN 1 ELSE 0 END) as pending,
            SUM(CASE WHEN a.status = 'approved' THEN 1 ELSE 0 END) as approved,
            SUM(CASE WHEN a.status = 'rejected' THEN 1 ELSE 0 END) as rejected
        FROM students st
        JOIN users u ON st.user_id = u.id
        JOIN levels l ON st.level_id = l.id
        LEFT JOIN sub_departments sd ON u.sub_department_id = sd.id
        LEFT JOIN absences a ON st.id = a.student_id AND a.date BETWEEN ? AND ?
        WHERE u.department_id = ?
    ";
    
    $params = [$start_date, $end_date, $department_id];
    $types = "ssi";
    
    if ($filter_subdept) {
        $sql .= " AND u.sub_department_id = ?";
        $params[] = $filter_subdept;
        $types .= "i";
    }
    
    if ($filter_level) {
        $sql .= " AND st.level_id = ?";
        $params[] = $filter_level;
        $types .= "i";
    }
    
    if ($filter_subject) {
        $sql .= " AND a.subject_id = ?";
        $params[] = $filter_subject;
        $types .= "i";
    }
    
    $sql .= " GROUP BY st.id ORDER BY total_absences DESC LIMIT 50";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

// دالة للحصول على تقارير الغيابات اليومية
function getDailyAbsences($conn, $department_id, $start_date, $end_date, $filter_subdept) {
    $sql = "
        SELECT 
            DATE(a.date) as date,
            COUNT(a.id) as total_absences,
            SUM(CASE WHEN a.status = 'pending' THEN 1 ELSE 0 END) as pending,
            SUM(CASE WHEN a.status = 'approved' THEN 1 ELSE 0 END) as approved,
            SUM(CASE WHEN a.status = 'rejected' THEN 1 ELSE 0 END) as rejected
        FROM absences a
        JOIN students st ON a.student_id = st.id
        JOIN users u ON st.user_id = u.id
        WHERE a.date BETWEEN ? AND ? AND u.department_id = ?
    ";
    
    $params = [$start_date, $end_date, $department_id];
    $types = "ssi";
    
    if ($filter_subdept) {
        $sql .= " AND u.sub_department_id = ?";
        $params[] = $filter_subdept;
        $types .= "i";
    }
    
    $sql .= " GROUP BY DATE(a.date) ORDER BY date DESC";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

// الحصول على البيانات حسب نوع التقرير
$general_stats = getGeneralStats($conn, $department_id, $start_date, $end_date, $filter_subdept, $filter_level);
$absences_by_subject = getAbsencesBySubject($conn, $department_id, $start_date, $end_date, $filter_subdept, $filter_level);
$absences_by_level = getAbsencesByLevel($conn, $department_id, $start_date, $end_date, $filter_subdept);
$absences_by_student = getAbsencesByStudent($conn, $department_id, $start_date, $end_date, $filter_subdept, $filter_level, $filter_subject);
$daily_absences = getDailyAbsences($conn, $department_id, $start_date, $end_date, $filter_subdept);

// معالجة تصدير التقرير
if (isset($_GET['export']) && $_GET['export'] == 'csv') {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=rapport_' . date('Y-m-d') . '.csv');
    
    $output = fopen('php://output', 'w');
    
    // إضافة BOM ل UTF-8
    fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
    
    switch ($report_type) {
        case 'subject':
            fputcsv($output, ['Matière', 'Code', 'Total', 'En attente', 'Approuvées', 'Rejetées']);
            foreach ($absences_by_subject as $row) {
                fputcsv($output, [
                    $row['name'],
                    $row['code'],
                    $row['total_absences'],
                    $row['pending'],
                    $row['approved'],
                    $row['rejected']
                ]);
            }
            break;
            
        case 'level':
            fputcsv($output, ['Niveau', 'Nombre étudiants', 'Total absences', 'En attente', 'Approuvées', 'Rejetées']);
            foreach ($absences_by_level as $row) {
                fputcsv($output, [
                    $row['name'],
                    $row['total_students'],
                    $row['total_absences'],
                    $row['pending'],
                    $row['approved'],
                    $row['rejected']
                ]);
            }
            break;
            
        case 'student':
            fputcsv($output, ['Étudiant', 'Niveau', 'Spécialité', 'Total absences', 'En attente', 'Approuvées', 'Rejetées']);
            foreach ($absences_by_student as $row) {
                fputcsv($output, [
                    $row['first_name'] . ' ' . $row['last_name'],
                    $row['level_name'],
                    $row['sub_department_name'],
                    $row['total_absences'],
                    $row['pending'],
                    $row['approved'],
                    $row['rejected']
                ]);
            }
            break;
            
        case 'daily':
            fputcsv($output, ['Date', 'Total absences', 'En attente', 'Approuvées', 'Rejetées']);
            foreach ($daily_absences as $row) {
                fputcsv($output, [
                    $row['date'],
                    $row['total_absences'],
                    $row['pending'],
                    $row['approved'],
                    $row['rejected']
                ]);
            }
            break;
    }
    
    fclose($output);
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapports - Justifly</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
        
        .report-tab {
            padding: 0.75rem 1.5rem;
            border-radius: 12px 12px 0 0;
            cursor: pointer;
            transition: all 0.3s ease;
            border-bottom: 3px solid transparent;
        }
        
        .report-tab:hover {
            background: rgba(255, 255, 255, 0.05);
        }
        
        .report-tab.active {
            background: var(--bg-card);
            border-bottom-color: var(--accent-color);
        }
        
        .chart-container {
            position: relative;
            height: 300px;
            margin: 1rem 0;
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
                <div class="sidebar-item active">
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
                <h1 class="text-3xl font-bold mb-2">Rapports et Statistiques</h1>
                <p class="text-gray-400">Analysez les données de votre département</p>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-gray-800/50 rounded-lg p-4 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-2">Date début</label>
                    <input type="date" class="form-input" id="startDate" value="<?php echo htmlspecialchars($start_date); ?>">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Date fin</label>
                    <input type="date" class="form-input" id="endDate" value="<?php echo htmlspecialchars($end_date); ?>">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Spécialité</label>
                    <select class="form-select" id="subdeptFilter">
                        <option value="">Toutes</option>
                        <?php foreach ($sub_departments as $sub_dept): ?>
                            <option value="<?php echo $sub_dept['id']; ?>" <?php echo $filter_subdept == $sub_dept['id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($sub_dept['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Niveau</label>
                    <select class="form-select" id="levelFilter">
                        <option value="">Tous</option>
                        <?php foreach ($levels as $level): ?>
                            <option value="<?php echo $level['id']; ?>" <?php echo $filter_level == $level['id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($level['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Matière</label>
                    <select class="form-select" id="subjectFilter">
                        <option value="">Toutes</option>
                        <?php foreach ($subjects as $subject): ?>
                            <option value="<?php echo $subject['id']; ?>" <?php echo $filter_subject == $subject['id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($subject['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="flex items-end">
                    <button class="btn-primary w-full" onclick="applyFilters()">
                        <i class="fas fa-filter mr-2"></i>
                        Appliquer
                    </button>
                </div>
            </div>
        </div>

        <!-- General Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="stat-card text-center">
                <i class="fas fa-user-graduate text-3xl text-blue-500 mb-3"></i>
                <h3 class="text-2xl font-bold"><?php echo $general_stats['total_students']; ?></h3>
                <p class="text-gray-400">Étudiants</p>
            </div>
            <div class="stat-card text-center">
                <i class="fas fa-chalkboard-teacher text-3xl text-green-500 mb-3"></i>
                <h3 class="text-2xl font-bold"><?php echo $general_stats['total_teachers']; ?></h3>
                <p class="text-gray-400">Enseignants</p>
            </div>
            <div class="stat-card text-center">
                <i class="fas fa-book text-3xl text-purple-500 mb-3"></i>
                <h3 class="text-2xl font-bold"><?php echo $general_stats['total_subjects']; ?></h3>
                <p class="text-gray-400">Matières</p>
            </div>
            <div class="stat-card text-center">
                <i class="fas fa-calendar-times text-3xl text-yellow-500 mb-3"></i>
                <h3 class="text-2xl font-bold"><?php echo $general_stats['total_absences']; ?></h3>
                <p class="text-gray-400">Absences</p>
            </div>
        </div>

        <!-- Report Tabs -->
        <div class="bg-gray-800/50 rounded-t-lg p-2 mb-0">
            <div class="flex gap-2">
                <div class="report-tab <?php echo $report_type == 'overview' ? 'active' : ''; ?>" onclick="showReport('overview')">
                    <i class="fas fa-chart-pie mr-2"></i>Aperçu
                </div>
                <div class="report-tab <?php echo $report_type == 'subject' ? 'active' : ''; ?>" onclick="showReport('subject')">
                    <i class="fas fa-book mr-2"></i>Par matière
                </div>
                <div class="report-tab <?php echo $report_type == 'level' ? 'active' : ''; ?>" onclick="showReport('level')">
                    <i class="fas fa-layer-group mr-2"></i>Par niveau
                </div>
                <div class="report-tab <?php echo $report_type == 'student' ? 'active' : ''; ?>" onclick="showReport('student')">
                    <i class="fas fa-user-graduate mr-2"></i>Par étudiant
                </div>
                <div class="report-tab <?php echo $report_type == 'daily' ? 'active' : ''; ?>" onclick="showReport('daily')">
                    <i class="fas fa-calendar-day mr-2"></i>Journalier
                </div>
            </div>
        </div>

        <!-- Report Content -->
        <div class="table-container">
            <!-- Overview Report -->
            <div id="overview-report" class="report-content p-6" style="<?php echo $report_type != 'overview' ? 'display: none;' : ''; ?>">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-bold">Aperçu des absences</h2>
                    <button class="btn-success" onclick="exportReport('overview')">
                        <i class="fas fa-download mr-2"></i>Exporter
                    </button>
                </div>
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="stat-card">
                        <h3 class="text-lg font-semibold mb-4">Répartition des absences</h3>
                        <div class="chart-container">
                            <canvas id="absencePieChart"></canvas>
                        </div>
                    </div>
                    <div class="stat-card">
                        <h3 class="text-lg font-semibold mb-4">Évolution des absences</h3>
                        <div class="chart-container">
                            <canvas id="absenceLineChart"></canvas>
                        </div>
                    </div>
                </div>
                
                <div class="stat-card mt-6">
                    <h3 class="text-lg font-semibold mb-4">Top 5 des matières avec le plus d'absences</h3>
                    <div class="chart-container">
                        <canvas id="topSubjectsChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Subject Report -->
            <div id="subject-report" class="report-content p-6" style="<?php echo $report_type != 'subject' ? 'display: none;' : ''; ?>">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-bold">Absences par matière</h2>
                    <button class="btn-success" onclick="exportReport('subject')">
                        <i class="fas fa-download mr-2"></i>Exporter CSV
                    </button>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="border-b border-gray-700">
                            <tr>
                                <th class="text-left p-4">Matière</th>
                                <th class="text-left p-4">Code</th>
                                <th class="text-center p-4">Total</th>
                                <th class="text-center p-4">En attente</th>
                                <th class="text-center p-4">Approuvées</th>
                                <th class="text-center p-4">Rejetées</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($absences_by_subject as $subject): ?>
                                <tr class="border-b border-gray-800 hover:bg-gray-800/50">
                                    <td class="p-4 font-semibold"><?php echo htmlspecialchars($subject['name']); ?></td>
                                    <td class="p-4"><?php echo htmlspecialchars($subject['code']); ?></td>
                                    <td class="p-4 text-center"><?php echo $subject['total_absences']; ?></td>
                                    <td class="p-4 text-center">
                                        <span class="status-badge status-pending"><?php echo $subject['pending']; ?></span>
                                    </td>
                                    <td class="p-4 text-center">
                                        <span class="status-badge status-approved"><?php echo $subject['approved']; ?></span>
                                    </td>
                                    <td class="p-4 text-center">
                                        <span class="status-badge status-rejected"><?php echo $subject['rejected']; ?></span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Level Report -->
            <div id="level-report" class="report-content p-6" style="<?php echo $report_type != 'level' ? 'display: none;' : ''; ?>">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-bold">Absences par niveau</h2>
                    <button class="btn-success" onclick="exportReport('level')">
                        <i class="fas fa-download mr-2"></i>Exporter CSV
                    </button>
                </div>
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="stat-card">
                        <h3 class="text-lg font-semibold mb-4">Répartition par niveau</h3>
                        <div class="chart-container">
                            <canvas id="levelBarChart"></canvas>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="border-b border-gray-700">
                                <tr>
                                    <th class="text-left p-4">Niveau</th>
                                    <th class="text-center p-4">Étudiants</th>
                                    <th class="text-center p-4">Total absences</th>
                                    <th class="text-center p-4">Taux</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($absences_by_level as $level): ?>
                                    <tr class="border-b border-gray-800 hover:bg-gray-800/50">
                                        <td class="p-4 font-semibold"><?php echo htmlspecialchars($level['name']); ?></td>
                                        <td class="p-4 text-center"><?php echo $level['total_students']; ?></td>
                                        <td class="p-4 text-center"><?php echo $level['total_absences']; ?></td>
                                        <td class="p-4 text-center">
                                            <?php 
                                            $rate = $level['total_students'] > 0 ? 
                                                round(($level['total_absences'] / $level['total_students']) * 100, 1) : 0;
                                            echo $rate . '%';
                                            ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Student Report -->
            <div id="student-report" class="report-content p-6" style="<?php echo $report_type != 'student' ? 'display: none;' : ''; ?>">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-bold">Absences par étudiant</h2>
                    <button class="btn-success" onclick="exportReport('student')">
                        <i class="fas fa-download mr-2"></i>Exporter CSV
                    </button>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="border-b border-gray-700">
                            <tr>
                                <th class="text-left p-4">Étudiant</th>
                                <th class="text-left p-4">Niveau</th>
                                <th class="text-left p-4">Spécialité</th>
                                <th class="text-center p-4">Total</th>
                                <th class="text-center p-4">En attente</th>
                                <th class="text-center p-4">Approuvées</th>
                                <th class="text-center p-4">Rejetées</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($absences_by_student as $student): ?>
                                <tr class="border-b border-gray-800 hover:bg-gray-800/50">
                                    <td class="p-4">
                                        <div class="font-semibold"><?php echo htmlspecialchars($student['first_name'] . ' ' . $student['last_name']); ?></div>
                                    </td>
                                    <td class="p-4"><?php echo htmlspecialchars($student['level_name']); ?></td>
                                    <td class="p-4"><?php echo htmlspecialchars($student['sub_department_name']); ?></td>
                                    <td class="p-4 text-center"><?php echo $student['total_absences']; ?></td>
                                    <td class="p-4 text-center">
                                        <span class="status-badge status-pending"><?php echo $student['pending']; ?></span>
                                    </td>
                                    <td class="p-4 text-center">
                                        <span class="status-badge status-approved"><?php echo $student['approved']; ?></span>
                                    </td>
                                    <td class="p-4 text-center">
                                        <span class="status-badge status-rejected"><?php echo $student['rejected']; ?></span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Daily Report -->
            <div id="daily-report" class="report-content p-6" style="<?php echo $report_type != 'daily' ? 'display: none;' : ''; ?>">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-bold">Absences journalières</h2>
                    <button class="btn-success" onclick="exportReport('daily')">
                        <i class="fas fa-download mr-2"></i>Exporter CSV
                    </button>
                </div>
                
                <div class="stat-card mb-6">
                    <h3 class="text-lg font-semibold mb-4">Évolution journalière</h3>
                    <div class="chart-container" style="height: 400px;">
                        <canvas id="dailyChart"></canvas>
                    </div>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="border-b border-gray-700">
                            <tr>
                                <th class="text-left p-4">Date</th>
                                <th class="text-center p-4">Total</th>
                                <th class="text-center p-4">En attente</th>
                                <th class="text-center p-4">Approuvées</th>
                                <th class="text-center p-4">Rejetées</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($daily_absences as $day): ?>
                                <tr class="border-b border-gray-800 hover:bg-gray-800/50">
                                    <td class="p-4 font-semibold"><?php echo date('d/m/Y', strtotime($day['date'])); ?></td>
                                    <td class="p-4 text-center"><?php echo $day['total_absences']; ?></td>
                                    <td class="p-4 text-center">
                                        <span class="status-badge status-pending"><?php echo $day['pending']; ?></span>
                                    </td>
                                    <td class="p-4 text-center">
                                        <span class="status-badge status-approved"><?php echo $day['approved']; ?></span>
                                    </td>
                                    <td class="p-4 text-center">
                                        <span class="status-badge status-rejected"><?php echo $day['rejected']; ?></span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Chart.js configuration
        Chart.defaults.color = '#94a3b8';
        Chart.defaults.borderColor = 'rgba(255, 255, 255, 0.1)';

        // Initialize charts when page loads
        document.addEventListener('DOMContentLoaded', function() {
            initializeCharts();
        });

        function initializeCharts() {
            // Pie Chart for absence distribution
            const pieCtx = document.getElementById('absencePieChart');
            if (pieCtx) {
                new Chart(pieCtx, {
                    type: 'doughnut',
                    data: {
                        labels: ['En attente', 'Approuvées', 'Rejetées'],
                        datasets: [{
                            data: [
                                <?php echo $general_stats['pending_absences']; ?>,
                                <?php echo $general_stats['approved_absences']; ?>,
                                <?php echo $general_stats['rejected_absences']; ?>
                            ],
                            backgroundColor: [
                                'rgba(251, 191, 36, 0.8)',
                                'rgba(16, 185, 129, 0.8)',
                                'rgba(239, 68, 68, 0.8)'
                            ],
                            borderWidth: 0
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom'
                            }
                        }
                    }
                });
            }

            // Line Chart for absence evolution
            const lineCtx = document.getElementById('absenceLineChart');
            if (lineCtx) {
                new Chart(lineCtx, {
                    type: 'line',
                    data: {
                        labels: <?php echo json_encode(array_map(function($day) { return date('d/m', strtotime($day['date'])); }, array_slice($daily_absences, -7))); ?>,
                        datasets: [{
                            label: 'Absences',
                            data: <?php echo json_encode(array_map(function($day) { return $day['total_absences']; }, array_slice($daily_absences, -7))); ?>,
                            borderColor: 'rgba(102, 126, 234, 1)',
                            backgroundColor: 'rgba(102, 126, 234, 0.1)',
                            tension: 0.4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            }

            // Bar Chart for top subjects
            const topSubjectsCtx = document.getElementById('topSubjectsChart');
            if (topSubjectsCtx) {
                const topSubjects = <?php echo json_encode(array_slice($absences_by_subject, 0, 5)); ?>;
                new Chart(topSubjectsCtx, {
                    type: 'bar',
                    data: {
                        labels: topSubjects.map(s => s.name),
                        datasets: [{
                            label: 'Total absences',
                            data: topSubjects.map(s => s.total_absences),
                            backgroundColor: 'rgba(102, 126, 234, 0.8)'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            }

            // Level Bar Chart
            const levelBarCtx = document.getElementById('levelBarChart');
            if (levelBarCtx) {
                new Chart(levelBarCtx, {
                    type: 'bar',
                    data: {
                        labels: <?php echo json_encode(array_map(function($level) { return $level['name']; }, $absences_by_level)); ?>,
                        datasets: [{
                            label: 'Total absences',
                            data: <?php echo json_encode(array_map(function($level) { return $level['total_absences']; }, $absences_by_level)); ?>,
                            backgroundColor: 'rgba(102, 126, 234, 0.8)'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            }

            // Daily Chart
            const dailyCtx = document.getElementById('dailyChart');
            if (dailyCtx) {
                new Chart(dailyCtx, {
                    type: 'line',
                    data: {
                        labels: <?php echo json_encode(array_map(function($day) { return date('d/m', strtotime($day['date'])); }, $daily_absences)); ?>,
                        datasets: [
                            {
                                label: 'Total',
                                data: <?php echo json_encode(array_map(function($day) { return $day['total_absences']; }, $daily_absences)); ?>,
                                borderColor: 'rgba(102, 126, 234, 1)',
                                backgroundColor: 'rgba(102, 126, 234, 0.1)',
                                tension: 0.4
                            },
                            {
                                label: 'En attente',
                                data: <?php echo json_encode(array_map(function($day) { return $day['pending']; }, $daily_absences)); ?>,
                                borderColor: 'rgba(251, 191, 36, 1)',
                                backgroundColor: 'rgba(251, 191, 36, 0.1)',
                                tension: 0.4
                            },
                            {
                                label: 'Approuvées',
                                data: <?php echo json_encode(array_map(function($day) { return $day['approved']; }, $daily_absences)); ?>,
                                borderColor: 'rgba(16, 185, 129, 1)',
                                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                                tension: 0.4
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'top'
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            }
        }

        function showReport(type) {
            // Hide all reports
            document.querySelectorAll('.report-content').forEach(el => {
                el.style.display = 'none';
            });
            
            // Remove active class from all tabs
            document.querySelectorAll('.report-tab').forEach(el => {
                el.classList.remove('active');
            });
            
            // Show selected report
            document.getElementById(type + '-report').style.display = 'block';
            
            // Add active class to clicked tab
            event.target.closest('.report-tab').classList.add('active');
            
            // Update URL without reload
            const url = new URL(window.location);
            url.searchParams.set('report_type', type);
            window.history.pushState({}, '', url);
        }

        function applyFilters() {
            const params = new URLSearchParams();
            
            params.set('start_date', document.getElementById('startDate').value);
            params.set('end_date', document.getElementById('endDate').value);
            params.set('filter_subdept', document.getElementById('subdeptFilter').value);
            params.set('filter_level', document.getElementById('levelFilter').value);
            params.set('filter_subject', document.getElementById('subjectFilter').value);
            params.set('report_type', '<?php echo $report_type; ?>');
            
            window.location.href = 'admin_reports.php?' + params.toString();
        }

        function exportReport(type) {
            const params = new URLSearchParams(window.location.search);
            params.set('export', 'csv');
            params.set('report_type', type);
            
            window.location.href = 'admin_reports.php?' + params.toString();
        }

        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('open');
        }
    </script>
</body>
</html>