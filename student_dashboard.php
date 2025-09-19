<?php
require_once 'config.php';
session_start();

// التحقق من تسجيل دخول الطالب
if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'student') {
    header("Location: index2.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$department_id = $_SESSION['department_id'];
$sub_department_id = $_SESSION['sub_department_id'];

// جلب بيانات الطالب مع معلومات المستوى والقسم
$stmt = $conn->prepare("
    SELECT s.*, u.username, u.email, l.name as level_name, d.name as department_name, sd.name as sub_department_name
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

// جلب غيابات الطالب مع معلومات المادة
$stmt = $conn->prepare("
    SELECT a.*, sub.name as subject_name, sub.code as subject_code
    FROM absences a
    JOIN subjects sub ON a.subject_id = sub.id
    WHERE a.student_id = ?
    ORDER BY a.date DESC
");
$stmt->bind_param("i", $student['id']);
$stmt->execute();
$all_absences = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// تقسيم الغيابات حسب الفصل الدراسي
$first_semester_absences = [];
$second_semester_absences = [];

foreach ($all_absences as $absence) {
    $month = date('m', strtotime($absence['date']));
    if ($month >= 9 || $month <= 1) { // سبتمبر - يناير (الفصل الأول)
        $first_semester_absences[] = $absence;
    } else { // فبراير - أغسطس (الفصل الثاني)
        $second_semester_absences[] = $absence;
    }
}

// حساب الإحصائيات
$total_absences = count($all_absences);
$pending_absences = count(array_filter($all_absences, fn($a) => $a['status'] === 'pending'));
$approved_absences = count(array_filter($all_absences, fn($a) => $a['status'] === 'approved'));
$rejected_absences = count(array_filter($all_absences, fn($a) => $a['status'] === 'rejected'));
?>

<!DOCTYPE html>
<html lang="fr" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord Étudiant - Justifly</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* ===== متغيرات الألوان ===== */
        :root {
            /* Dark Mode (الافتراضي) */
            --bg-primary: #0f172a;
            --bg-secondary: #1e293b;
            --bg-card: rgba(255, 255, 255, 0.05);
            --bg-card-hover: rgba(255, 255, 255, 0.1);
            --text-primary: #e2e8f0;
            --text-secondary: #94a3b8;
            --text-muted: #64748b;
            --border-color: rgba(255, 255, 255, 0.1);
            --gradient-primary: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --gradient-secondary: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
            --nav-bg: rgba(15, 23, 42, 0.8);
            --nav-border: rgba(31, 41, 55, 0.8);
            --select-bg: #1f2937;
            --select-border: #374151;
            --accent-color: #667eea;
            --accent-hover: #7c3aed;
            --success-color: #10b981;
            --shape-bg: rgba(102, 126, 234, 0.2);
            --shape-bg-2: rgba(118, 75, 162, 0.2);
            --shape-bg-3: rgba(240, 147, 251, 0.2);
            --shape-bg-4: rgba(16, 185, 129, 0.2);
        }

        /* Light Mode */
        [data-theme="light"] {
            --bg-primary: #f8fafc;
            --bg-secondary: #ffffff;
            --bg-card: rgba(255, 255, 255, 0.8);
            --bg-card-hover: rgba(241, 245, 249, 0.9);
            --text-primary: #1e293b;
            --text-secondary: #475569;
            --text-muted: #64748b;
            --border-color: rgba(226, 232, 240, 0.8);
            --gradient-primary: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --gradient-secondary: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
            --nav-bg: rgba(255, 255, 255, 0.9);
            --nav-border: rgba(241, 245, 249, 0.9);
            --select-bg: #f1f5f9;
            --select-border: #e2e8f0;
            --accent-color: #667eea;
            --accent-hover: #7c3aed;
            --success-color: #10b981;
            --shape-bg: rgba(102, 126, 234, 0.1);
            --shape-bg-2: rgba(118, 75, 162, 0.1);
            --shape-bg-3: rgba(240, 147, 251, 0.1);
            --shape-bg-4: rgba(16, 185, 129, 0.1);
        }

        /* ===== أنماط عامة ===== */
        body { 
            font-family: 'Inter', sans-serif; 
            overflow-x: hidden;
            background: var(--bg-primary);
            color: var(--text-primary);
            transition: background-color 0.3s ease, color 0.3s ease;
        }
        
        .gradient-bg { 
            background: var(--gradient-primary); 
        }
        
        .glass-card {
            background: var(--bg-card);
            backdrop-filter: blur(10px);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }
        
        .glass-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 40px rgba(102, 126, 234, 0.2);
            border-color: rgba(102, 126, 234, 0.3);
        }
        
        .gradient-text {
            background: var(--gradient-secondary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .sidebar {
            background: var(--bg-secondary);
            border-right: 1px solid var(--border-color);
            min-height: 100vh;
            position: fixed;
            width: 250px;
            z-index: 100;
            transition: transform 0.3s ease;
        }
        
        .main-content {
            margin-left: 250px;
            padding: 2rem;
            width: calc(100% - 250px);
            transition: all 0.3s ease;
        }
        
        .stat-card {
            background: var(--bg-card);
            backdrop-filter: blur(20px);
            border: 1px solid var(--border-color);
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
            border: 1px solid var(--border-color);
            border-radius: 16px;
            overflow: hidden;
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
        
        .btn-primary {
            background: var(--gradient-primary);
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            color: white;
            font-weight: 600;
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
            color: var(--text-secondary);
        }
        
        .sidebar-item:hover {
            background: rgba(102, 126, 234, 0.1);
            color: var(--accent-color);
        }
        
        .sidebar-item.active {
            background: var(--gradient-primary);
            color: white;
        }
        
        /* ===== السويتش (Toggle Switch) ===== */
        .theme-switch-nav {
            display: flex;
            align-items: center;
            background: var(--bg-card);
            backdrop-filter: blur(10px);
            border: 1px solid var(--border-color);
            border-radius: 50px;
            padding: 3px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .theme-label {
            margin: 0 5px;
            font-size: 14px;
            font-weight: 500;
            color: var(--text-secondary);
        }

        .switch {
            position: relative;
            display: inline-block;
            width: 40px;
            height: 20px;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: 0.4s;
            border-radius: 34px;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 14px;
            width: 14px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            transition: 0.4s;
            border-radius: 50%;
        }

        input:checked + .slider {
            background-color: var(--accent-color);
        }

        input:checked + .slider:before {
            transform: translateX(20px);
        }
        
        /* Responsive pour les écrans moyens */
        @media (max-width: 1024px) {
            .main-content {
                padding: 1.5rem;
            }
            
            .text-3xl { font-size: 1.75rem !important; }
        }
        
        /* Responsive pour les tablettes */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            
            .sidebar.open {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
                width: 100%;
                padding: 1rem;
            }
            
            /* Titres */
            .text-3xl { font-size: 1.5rem !important; }
            .text-xl { font-size: 1.25rem !important; }
            
            /* Grilles */
            .grid-cols-4 { grid-template-columns: repeat(2, 1fr) !important; }
            
            /* Espacements */
            .mb-8 { margin-bottom: 1rem !important; }
            .p-4 { padding: 0.75rem !important; }
            
            /* Cartes */
            .stat-card { padding: 1rem !important; }
            
            /* Boutons */
            .btn-primary { 
                padding: 0.5rem 1rem !important; 
                font-size: 0.875rem !important;
            }
        }
        
        /* Responsive pour les mobiles */
        @media (max-width: 480px) {
            /* Titres */
            .text-3xl { font-size: 1.25rem !important; }
            .text-xl { font-size: 1.125rem !important; }
            
            /* Grilles */
            .grid-cols-4 { grid-template-columns: 1fr !important; }
            .grid-cols-2 { grid-template-columns: 1fr !important; }
            
            /* Espacements */
            .mb-8 { margin-bottom: 0.75rem !important; }
            .p-4 { padding: 0.5rem !important; }
            
            /* Cartes */
            .stat-card { padding: 0.75rem !important; }
            
            /* Theme switch */
            .theme-switch-nav {
                padding: 2px;
            }
            
            .theme-label {
                font-size: 12px;
                margin: 0 3px;
            }
            
            .switch {
                width: 30px;
                height: 15px;
            }
            
            .slider:before {
                height: 11px;
                width: 11px;
                left: 2px;
                bottom: 2px;
            }
            
            input:checked + .slider:before {
                transform: translateX(15px);
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="p-4">
            <div class="flex items-center gap-3 mb-8">
                <div class="w-12 h-12 gradient-bg rounded-xl flex items-center justify-center text-white font-bold text-xl">
                    J
                </div>
                <div>
                    <h2 class="font-bold text-lg gradient-text">Justifly</h2>
                    <p class="text-sm" style="color: var(--text-secondary);">Étudiant</p>
                </div>
            </div>
            
            <nav>
                <div class="sidebar-item active">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Tableau de bord</span>
                </div>
                <div class="sidebar-item" onclick="window.location.href='add_absence.php'">
                    <i class="fas fa-plus-circle"></i>
                    <span>Déclarer absence</span>
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
                <h1 class="text-3xl font-bold mb-2">Tableau de bord</h1>
                <p style="color: var(--text-secondary);">Bienvenue, <?php echo htmlspecialchars($student['first_name'] . ' ' . $student['last_name']); ?></p>
            </div>
            <div class="flex items-center space-x-4">
                <!-- Theme Switch -->
                <div class="theme-switch-nav">
                    <span class="theme-label"><i class="fas fa-moon"></i></span>
                    <label class="switch">
                        <input type="checkbox" id="theme-toggle">
                        <span class="slider"></span>
                    </label>
                    <span class="theme-label"><i class="fas fa-sun"></i></span>
                </div>
                <button class="md:hidden" onclick="toggleSidebar()">
                    <i class="fas fa-bars text-2xl" style="color: var(--text-secondary);"></i>
                </button>
            </div>
        </div>

        <!-- Student Info Card -->
        <div class="stat-card mb-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <p class="text-sm" style="color: var(--text-secondary);">Département</p>
                    <p class="font-semibold"><?php echo htmlspecialchars($student['department_name']); ?></p>
                </div>
                <div>
                    <p class="text-sm" style="color: var(--text-secondary);">Spécialité</p>
                    <p class="font-semibold"><?php echo htmlspecialchars($student['sub_department_name']); ?></p>
                </div>
                <div>
                    <p class="text-sm" style="color: var(--text-secondary);">Niveau</p>
                    <p class="font-semibold"><?php echo htmlspecialchars($student['level_name']); ?></p>
                </div>
                <div>
                    <p class="text-sm" style="color: var(--text-secondary);">Email</p>
                    <p class="font-semibold"><?php echo htmlspecialchars($student['email']); ?></p>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="stat-card text-center">
                <i class="fas fa-calendar-times text-3xl mb-3" style="color: #667eea;"></i>
                <h3 class="text-2xl font-bold"><?php echo $total_absences; ?></h3>
                <p style="color: var(--text-secondary);">Total absences</p>
            </div>
            <div class="stat-card text-center">
                <i class="fas fa-clock text-3xl mb-3" style="color: #fbbf24;"></i>
                <h3 class="text-2xl font-bold"><?php echo $pending_absences; ?></h3>
                <p style="color: var(--text-secondary);">En attente</p>
            </div>
            <div class="stat-card text-center">
                <i class="fas fa-check-circle text-3xl mb-3" style="color: #10b981;"></i>
                <h3 class="text-2xl font-bold"><?php echo $approved_absences; ?></h3>
                <p style="color: var(--text-secondary);">Approuvées</p>
            </div>
            <div class="stat-card text-center">
                <i class="fas fa-times-circle text-3xl mb-3" style="color: #ef4444;"></i>
                <h3 class="text-2xl font-bold"><?php echo $rejected_absences; ?></h3>
                <p style="color: var(--text-secondary);">Rejetées</p>
            </div>
        </div>

        <!-- First Semester Absences -->
        <div class="mb-8">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-4 gap-3">
                <h2 class="text-xl font-bold">Absences - Premier semestre</h2>
                <button class="btn-primary" onclick="window.location.href='add_absence.php'">
                    <i class="fas fa-plus mr-2"></i>Déclarer absence
                </button>
            </div>
            
            <div class="table-container">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="border-b" style="border-color: var(--border-color);">
                            <tr>
                                <th class="text-left p-4">Date</th>
                                <th class="text-left p-4">Matière</th>
                                <th class="text-left p-4">Motif</th>
                                <th class="text-left p-4">Statut</th>
                                <th class="text-left p-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($first_semester_absences)): ?>
                                <tr>
                                    <td colspan="5" class="text-center p-8" style="color: var(--text-muted);">
                                        Aucune absence enregistrée pour le premier semestre
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($first_semester_absences as $absence): ?>
                                    <tr class="border-b hover:bg-gray-800/50" style="border-color: var(--border-color);">
                                        <td class="p-4"><?php echo date('d/m/Y', strtotime($absence['date'])); ?></td>
                                        <td class="p-4">
                                            <div>
                                                <div class="font-semibold"><?php echo htmlspecialchars($absence['subject_name']); ?></div>
                                                <div class="text-sm" style="color: var(--text-muted);"><?php echo htmlspecialchars($absence['subject_code']); ?></div>
                                            </div>
                                        </td>
                                        <td class="p-4"><?php echo htmlspecialchars($absence['reason'] ?: 'Non spécifié'); ?></td>
                                        <td class="p-4">
                                            <span class="status-badge status-<?php echo $absence['status']; ?>">
                                                <?php 
                                                switch($absence['status']) {
                                                    case 'pending': echo 'En attente'; break;
                                                    case 'approved': echo 'Approuvée'; break;
                                                    case 'rejected': echo 'Rejetée'; break;
                                                }
                                                ?>
                                            </span>
                                        </td>
                                        <td class="p-4">
                                            <div class="flex gap-2">
                                                <?php if ($absence['status'] === 'pending'): ?>
                                                    <button class="hover:text-blue-400" style="color: var(--accent-color);" onclick="editAbsence(<?php echo $absence['id']; ?>)">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button class="hover:text-red-400" style="color: #ef4444;" onclick="deleteAbsence(<?php echo $absence['id']; ?>)">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                <?php endif; ?>
                                                <?php if ($absence['justification_document']): ?>
                                                    <a href="uploads/<?php echo htmlspecialchars($absence['justification_document']); ?>" 
                                                       class="hover:text-green-400" style="color: #10b981;" download>
                                                        <i class="fas fa-download"></i>
                                                    </a>
                                                <?php endif; ?>
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

        <!-- Second Semester Absences -->
        <div class="mb-8">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-4 gap-3">
                <h2 class="text-xl font-bold">Absences - Deuxième semestre</h2>
                <button class="btn-primary" onclick="window.location.href='add_absence.php'">
                    <i class="fas fa-plus mr-2"></i>Déclarer absence
                </button>
            </div>
            
            <div class="table-container">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="border-b" style="border-color: var(--border-color);">
                            <tr>
                                <th class="text-left p-4">Date</th>
                                <th class="text-left p-4">Matière</th>
                                <th class="text-left p-4">Motif</th>
                                <th class="text-left p-4">Statut</th>
                                <th class="text-left p-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($second_semester_absences)): ?>
                                <tr>
                                    <td colspan="5" class="text-center p-8" style="color: var(--text-muted);">
                                        Aucune absence enregistrée pour le deuxième semestre
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($second_semester_absences as $absence): ?>
                                    <tr class="border-b hover:bg-gray-800/50" style="border-color: var(--border-color);">
                                        <td class="p-4"><?php echo date('d/m/Y', strtotime($absence['date'])); ?></td>
                                        <td class="p-4">
                                            <div>
                                                <div class="font-semibold"><?php echo htmlspecialchars($absence['subject_name']); ?></div>
                                                <div class="text-sm" style="color: var(--text-muted);"><?php echo htmlspecialchars($absence['subject_code']); ?></div>
                                            </div>
                                        </td>
                                        <td class="p-4"><?php echo htmlspecialchars($absence['reason'] ?: 'Non spécifié'); ?></td>
                                        <td class="p-4">
                                            <span class="status-badge status-<?php echo $absence['status']; ?>">
                                                <?php 
                                                switch($absence['status']) {
                                                    case 'pending': echo 'En attente'; break;
                                                    case 'approved': echo 'Approuvée'; break;
                                                    case 'rejected': echo 'Rejetée'; break;
                                                }
                                                ?>
                                            </span>
                                        </td>
                                        <td class="p-4">
                                            <div class="flex gap-2">
                                                <?php if ($absence['status'] === 'pending'): ?>
                                                    <button class="hover:text-blue-400" style="color: var(--accent-color);" onclick="editAbsence(<?php echo $absence['id']; ?>)">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button class="hover:text-red-400" style="color: #ef4444;" onclick="deleteAbsence(<?php echo $absence['id']; ?>)">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                <?php endif; ?>
                                                <?php if ($absence['justification_document']): ?>
                                                    <a href="uploads/<?php echo htmlspecialchars($absence['justification_document']); ?>" 
                                                       class="hover:text-green-400" style="color: #10b981;" download>
                                                        <i class="fas fa-download"></i>
                                                    </a>
                                                <?php endif; ?>
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
    </div>

    <script>
        // ===== التحكم في الوضع =====
        document.addEventListener('DOMContentLoaded', function() {
            const themeToggle = document.getElementById('theme-toggle');
            const html = document.documentElement;

            // تحقق من الوضع المحفوظ في المتصفح
            const currentTheme = localStorage.getItem('theme') || 'dark';
            html.setAttribute('data-theme', currentTheme);
            themeToggle.checked = currentTheme === 'light';

            // عند التبديل
            themeToggle.addEventListener('change', () => {
                const theme = themeToggle.checked ? 'light' : 'dark';
                html.setAttribute('data-theme', theme);
                localStorage.setItem('theme', theme);
            });
        });

        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('open');
        }

        function editAbsence(id) {
            window.location.href = `edit_absence.php?id=${id}`;
        }

        function deleteAbsence(id) {
            if (confirm('Êtes-vous sûr de vouloir supprimer cette absence?')) {
                fetch('delete_absence.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `id=${id}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Erreur lors de la suppression');
                    }
                });
            }
        }
    </script>
</body>
</html>