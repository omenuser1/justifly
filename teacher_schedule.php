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
$schedule = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// تجميع حسب اليوم
$week_schedule = [];
$days = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'];

foreach ($days as $day) {
    $week_schedule[$day] = [];
}

// إضافة الحصص (بيانات وهمية للعرض)
$time_slots = [
    '08:00 - 10:00',
    '10:15 - 12:15',
    '13:30 - 15:30',
    '15:45 - 17:45'
];

foreach ($schedule as $item) {
    $day_index = array_rand($days);
    $time_slot = $time_slots[array_rand($time_slots)];
    
    $week_schedule[$days[$day_index]][] = [
        'time' => $time_slot,
        'subject' => $item['subject_name'],
        'level' => $item['level_name'],
        'code' => $item['subject_code']
    ];
}

// ترتيب الحصص حسب الوقت
foreach ($week_schedule as $day => &$slots) {
    usort($slots, function($a, $b) {
        return strcmp($a['time'], $b['time']);
    });
}
?>

<!DOCTYPE html>
<html lang="fr" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Emploi du temps - Justifly</title>
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
        
        .schedule-card {
            background: var(--bg-card);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
        }
        
        .schedule-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
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
        
        .time-slot {
            background: var(--accent-color);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 8px;
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
                <div class="sidebar-item active">
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
                <h1 class="text-3xl font-bold mb-2">Emploi du temps</h1>
                <p class="text-gray-400">Votre emploi du temps hebdomadaire</p>
            </div>
        </div>

        <!-- Schedule Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <?php foreach ($week_schedule as $day => $slots): ?>
                <div class="schedule-card">
                    <h3 class="text-xl font-bold mb-4 flex items-center gap-2">
                        <i class="fas fa-calendar-day text-purple-400"></i>
                        <?php echo htmlspecialchars($day); ?>
                    </h3>
                    
                    <?php if (empty($slots)): ?>
                        <div class="text-center py-8 text-gray-400">
                            <i class="fas fa-coffee text-3xl mb-2"></i>
                            <p>Aucun cours prévu</p>
                        </div>
                    <?php else: ?>
                        <div class="space-y-3">
                            <?php foreach ($slots as $slot): ?>
                                <div class="flex items-center justify-between p-3 bg-gray-800/30 rounded-lg">
                                    <div class="flex items-center gap-3">
                                        <span class="time-slot"><?php echo htmlspecialchars($slot['time']); ?></span>
                                        <div>
                                            <div class="font-semibold"><?php echo htmlspecialchars($slot['subject']); ?></div>
                                            <div class="text-sm text-gray-400">
                                                <?php echo htmlspecialchars($slot['level']); ?> • <?php echo htmlspecialchars($slot['code']); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <i class="fas fa-chalkboard-teacher text-purple-400"></i>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Summary Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">
            <div class="schedule-card text-center">
                <i class="fas fa-book text-3xl text-blue-500 mb-3"></i>
                <h3 class="text-2xl font-bold"><?php echo count($schedule); ?></h3>
                <p class="text-gray-400">Matières enseignées</p>
            </div>
            <div class="schedule-card text-center">
                <i class="fas fa-clock text-3xl text-green-500 mb-3"></i>
                <h3 class="text-2xl font-bold">
                    <?php 
                    $total_hours = 0;
                    foreach ($week_schedule as $day => $slots) {
                        $total_hours += count($slots) * 2;
                    }
                    echo $total_hours;
                    ?>
                </h3>
                <p class="text-gray-400">Heures par semaine</p>
            </div>
            <div class="schedule-card text-center">
                <i class="fas fa-layer-group text-3xl text-purple-500 mb-3"></i>
                <h3 class="text-2xl font-bold">
                    <?php 
                    $unique_levels = array_unique(array_column($schedule, 'level_name'));
                    echo count($unique_levels);
                    ?>
                </h3>
                <p class="text-gray-400">Niveaux différents</p>
            </div>
        </div>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('open');
        }
    </script>
</body>
</html>