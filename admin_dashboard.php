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

// معالجة الموافقة على الغياب
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['approve_absence'])) {
    $absence_id = $_POST['absence_id'];
    $teacher_message = $_POST['teacher_message'] ?? '';
    
    // تحديث حالة الغياب إلى موافق عليه
    $stmt = $conn->prepare("UPDATE absences SET status = 'approved' WHERE id = ?");
    $stmt->bind_param("i", $absence_id);
    $stmt->execute();
    
    // جلب معلومات الغياب لإرسال الإشعارات
    $stmt = $conn->prepare("
        SELECT a.*, s.first_name, s.last_name, sub.name as subject_name, 
               d.name as department_name, l.name as level_name
        FROM absences a
        JOIN students s ON a.student_id = s.id
        JOIN subjects sub ON a.subject_id = sub.id
        JOIN levels l ON s.level_id = l.id
        JOIN users u ON s.user_id = u.id
        JOIN departments d ON u.department_id = d.id
        WHERE a.id = ?
    ");
    $stmt->bind_param("i", $absence_id);
    $stmt->execute();
    $absence = $stmt->get_result()->fetch_assoc();
    
    // إرسال إشعار للطالب
    $student_notification = "Votre absence du " . date('d/m/Y', strtotime($absence['date'])) . 
                           " en " . $absence['subject_name'] . " a été approuvée.";
    $stmt = $conn->prepare("INSERT INTO notifications (user_id, message) VALUES (?, ?)");
    $stmt->bind_param("is", $absence['student_id'], $student_notification);
    $stmt->execute();
    
    // إرسال إشعارات للمعلمين في نفس القسم والمادة
    $teacher_notification = "Absence approuvée: " . $absence['first_name'] . ' ' . $absence['last_name'] . 
                           " - " . $absence['subject_name'] . " - " . date('d/m/Y', strtotime($absence['date']));
    if ($teacher_message) {
        $teacher_notification .= "\nMessage: " . $teacher_message;
    }
    
    $stmt = $conn->prepare("
        INSERT INTO notifications (user_id, message)
        SELECT DISTINCT u.id, ?
        FROM users u
        JOIN teachers t ON u.id = t.user_id
        JOIN teacher_subject_levels tsl ON t.id = tsl.teacher_id
        WHERE u.role = 'teacher' 
        AND u.department_id = ?
        AND tsl.subject_id = ?
        AND tsl.level_id = ?
    ");
    $stmt->bind_param("siii", $teacher_notification, $department_id, $absence['subject_id'], $absence['level_id']);
    $stmt->execute();
    
    $_SESSION['success'] = "Absence approuvée avec succès!";
    header("Location: admin_dashboard.php");
    exit();
}

// معالجة رفض الغياب
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reject_absence'])) {
    $absence_id = $_POST['absence_id'];
    $rejection_reason = $_POST['rejection_reason'] ?? '';
    
    // تحديث حالة الغياب إلى مرفوض مع سبب الرفض
    $stmt = $conn->prepare("UPDATE absences SET status = 'rejected', reason = ? WHERE id = ?");
    $stmt->bind_param("si", $rejection_reason, $absence_id);
    $stmt->execute();
    
    // جلب معلومات الغياب لإرسال الإشعار للطالب
    $stmt = $conn->prepare("
        SELECT a.*, s.first_name, s.last_name, sub.name as subject_name, u.id as student_user_id
        FROM absences a
        JOIN students s ON a.student_id = s.id
        JOIN subjects sub ON a.subject_id = sub.id
        JOIN users u ON s.user_id = u.id
        WHERE a.id = ?
    ");
    $stmt->bind_param("i", $absence_id);
    $stmt->execute();
    $absence = $stmt->get_result()->fetch_assoc();
    
    // إرسال إشعار للطالب
    $student_notification = "Votre absence du " . date('d/m/Y', strtotime($absence['date'])) . 
                           " en " . $absence['subject_name'] . " a été rejetée.";
    if ($rejection_reason) {
        $student_notification .= " Raison: " . $rejection_reason;
    }
    
    $stmt = $conn->prepare("INSERT INTO notifications (user_id, message) VALUES (?, ?)");
    $stmt->bind_param("is", $absence['student_user_id'], $student_notification);
    $stmt->execute();
    
    $_SESSION['success'] = "Absence rejetée avec succès!";
    header("Location: admin_dashboard.php");
    exit();
}

// جلب إحصائيات القسم - تصحيح العلاقات
$stmt = $conn->prepare("
    SELECT 
        COUNT(DISTINCT s.id) as total_students,
        COUNT(DISTINCT t.id) as total_teachers,
        COUNT(a.id) as total_absences,
        SUM(CASE WHEN a.status = 'pending' THEN 1 ELSE 0 END) as pending_absences,
        SUM(CASE WHEN a.status = 'approved' THEN 1 ELSE 0 END) as approved_absences,
        SUM(CASE WHEN a.status = 'rejected' THEN 1 ELSE 0 END) as rejected_absences
    FROM users u
    LEFT JOIN students s ON u.id = s.user_id
    LEFT JOIN teachers t ON u.id = t.user_id
    LEFT JOIN absences a ON s.id = a.student_id
    WHERE u.department_id = ? AND u.role IN ('student', 'teacher')
");
$stmt->bind_param("i", $department_id);
$stmt->execute();
$stats = $stmt->get_result()->fetch_assoc();

// جلب الغيابات في انتظار الموافقة - تصحيح العلاقات
$stmt = $conn->prepare("
    SELECT a.*, s.first_name, s.last_name, sub.name as subject_name, 
           l.name as level_name, sd.name as sub_department_name
    FROM absences a
    JOIN students s ON a.student_id = s.id
    JOIN subjects sub ON a.subject_id = sub.id
    JOIN levels l ON s.level_id = l.id
    JOIN users u ON s.user_id = u.id
    JOIN sub_departments sd ON u.sub_department_id = sd.id
    WHERE a.status = 'pending' AND u.department_id = ?
    ORDER BY a.date DESC
");
$stmt->bind_param("i", $department_id);
$stmt->execute();
$pending_absences = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// جلب أحدث الغيابات التي تمت معالجتها - تصحيح العلاقات
$stmt = $conn->prepare("
    SELECT a.*, s.first_name, s.last_name, sub.name as subject_name, 
           l.name as level_name, sd.name as sub_department_name
    FROM absences a
    JOIN students s ON a.student_id = s.id
    JOIN subjects sub ON a.subject_id = sub.id
    JOIN levels l ON s.level_id = l.id
    JOIN users u ON s.user_id = u.id
    JOIN sub_departments sd ON u.sub_department_id = sd.id
    WHERE a.status IN ('approved', 'rejected') AND u.department_id = ?
    ORDER BY a.date DESC
    LIMIT 10
");
$stmt->bind_param("i", $department_id);
$stmt->execute();
$recent_absences = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// جلب معلومات القسم
$stmt = $conn->prepare("SELECT name, description FROM departments WHERE id = ?");
$stmt->bind_param("i", $department_id);
$stmt->execute();
$department = $stmt->get_result()->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="fr" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord Administrateur - Justifly</title>
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
            margin: 10% auto;
            padding: 2rem;
            border-radius: 16px;
            width: 80%;
            max-width: 500px;
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
                <div class="sidebar-item active">
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
                <h1 class="text-3xl font-bold mb-2">Tableau de bord Administrateur</h1>
                <p class="text-gray-400">Département: <?php echo htmlspecialchars($department['name']); ?></p>
            </div>
            <button class="md:hidden" onclick="toggleSidebar()">
                <i class="fas fa-bars text-2xl"></i>
            </button>
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
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="stat-card text-center">
                <i class="fas fa-user-graduate text-3xl text-blue-500 mb-3"></i>
                <h3 class="text-2xl font-bold"><?php echo $stats['total_students']; ?></h3>
                <p class="text-gray-400">Étudiants</p>
            </div>
            <div class="stat-card text-center">
                <i class="fas fa-chalkboard-teacher text-3xl text-green-500 mb-3"></i>
                <h3 class="text-2xl font-bold"><?php echo $stats['total_teachers']; ?></h3>
                <p class="text-gray-400">Enseignants</p>
            </div>
            <div class="stat-card text-center">
                <i class="fas fa-calendar-times text-3xl text-yellow-500 mb-3"></i>
                <h3 class="text-2xl font-bold"><?php echo $stats['pending_absences']; ?></h3>
                <p class="text-gray-400">En attente</p>
            </div>
            <div class="stat-card text-center">
                <i class="fas fa-check-circle text-3xl text-purple-500 mb-3"></i>
                <h3 class="text-2xl font-bold"><?php echo $stats['approved_absences']; ?></h3>
                <p class="text-gray-400">Approuvées</p>
            </div>
        </div>

        <!-- Pending Absences -->
        <div class="mb-8">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold">Absences en attente de validation</h2>
                <span class="status-badge status-pending"><?php echo count($pending_absences); ?></span>
            </div>
            
            <div class="table-container">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="border-b border-gray-700">
                            <tr>
                                <th class="text-left p-4">Étudiant</th>
                                <th class="text-left p-4">Matière</th>
                                <th class="text-left p-4">Date</th>
                                <th class="text-left p-4">Motif</th>
                                <th class="text-left p-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($pending_absences)): ?>
                                <tr>
                                    <td colspan="5" class="text-center p-8 text-gray-400">
                                        Aucune absence en attente
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($pending_absences as $absence): ?>
                                    <tr class="border-b border-gray-800 hover:bg-gray-800/50">
                                        <td class="p-4">
                                            <div>
                                                <div class="font-semibold"><?php echo htmlspecialchars($absence['first_name'] . ' ' . $absence['last_name']); ?></div>
                                                <div class="text-sm text-gray-400"><?php echo htmlspecialchars($absence['level_name'] . ' - ' . $absence['sub_department_name']); ?></div>
                                            </div>
                                        </td>
                                        <td class="p-4"><?php echo htmlspecialchars($absence['subject_name']); ?></td>
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
                                        <td class="p-4">
                                            <div class="flex gap-2">
                                                <button class="btn-success" onclick="approveAbsence(<?php echo $absence['id']; ?>)">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                                <button class="btn-danger" onclick="rejectAbsence(<?php echo $absence['id']; ?>)">
                                                    <i class="fas fa-times"></i>
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

        <!-- Recent Absences -->
        <div class="mb-8">
            <h2 class="text-xl font-bold mb-4">Absences récemment traitées</h2>
            
            <div class="table-container">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="border-b border-gray-700">
                            <tr>
                                <th class="text-left p-4">Étudiant</th>
                                <th class="text-left p-4">Matière</th>
                                <th class="text-left p-4">Date</th>
                                <th class="text-left p-4">Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($recent_absences)): ?>
                                <tr>
                                    <td colspan="4" class="text-center p-8 text-gray-400">
                                        Aucune absence traitée récemment
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($recent_absences as $absence): ?>
                                    <tr class="border-b border-gray-800 hover:bg-gray-800/50">
                                        <td class="p-4">
                                            <div>
                                                <div class="font-semibold"><?php echo htmlspecialchars($absence['first_name'] . ' ' . $absence['last_name']); ?></div>
                                                <div class="text-sm text-gray-400"><?php echo htmlspecialchars($absence['level_name']); ?></div>
                                            </div>
                                        </td>
                                        <td class="p-4"><?php echo htmlspecialchars($absence['subject_name']); ?></td>
                                        <td class="p-4"><?php echo date('d/m/Y', strtotime($absence['date'])); ?></td>
                                        <td class="p-4">
                                            <span class="status-badge status-<?php echo $absence['status']; ?>">
                                                <?php 
                                                switch($absence['status']) {
                                                    case 'approved': echo 'Approuvée'; break;
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
    </div>

    <!-- Approve Modal -->
    <div id="approveModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('approveModal')">&times;</span>
            <h2 class="text-xl font-bold mb-4">Approuver l'absence</h2>
            <form id="approveForm" method="POST">
                <input type="hidden" name="absence_id" id="approveAbsenceId">
                <div class="form-group mb-4">
                    <label class="block text-sm font-medium mb-2">Message pour les enseignants (optionnel)</label>
                    <textarea class="w-full p-3 rounded-lg bg-gray-800 border border-gray-700 text-white" 
                              name="teacher_message" rows="3" placeholder="Ajoutez un message pour les enseignants..."></textarea>
                </div>
                <div class="flex gap-3">
                    <button type="submit" name="approve_absence" class="btn-primary flex-1">
                        <i class="fas fa-check mr-2"></i>Approuver
                    </button>
                    <button type="button" onclick="closeModal('approveModal')" class="btn-danger flex-1">
                        Annuler
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Reject Modal -->
    <div id="rejectModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('rejectModal')">&times;</span>
            <h2 class="text-xl font-bold mb-4">Rejeter l'absence</h2>
            <form id="rejectForm" method="POST">
                <input type="hidden" name="absence_id" id="rejectAbsenceId">
                <div class="form-group mb-4">
                    <label class="block text-sm font-medium mb-2">Raison du rejet <span class="text-red-500">*</span></label>
                    <textarea class="w-full p-3 rounded-lg bg-gray-800 border border-gray-700 text-white" 
                              name="rejection_reason" rows="3" required placeholder="Expliquez la raison du rejet..."></textarea>
                </div>
                <div class="flex gap-3">
                    <button type="submit" name="reject_absence" class="btn-danger flex-1">
                        <i class="fas fa-times mr-2"></i>Rejeter
                    </button>
                    <button type="button" onclick="closeModal('rejectModal')" class="btn-primary flex-1">
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

        function approveAbsence(id) {
            document.getElementById('approveAbsenceId').value = id;
            document.getElementById('approveModal').style.display = 'block';
        }

        function rejectAbsence(id) {
            document.getElementById('rejectAbsenceId').value = id;
            document.getElementById('rejectModal').style.display = 'block';
        }

        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const approveModal = document.getElementById('approveModal');
            const rejectModal = document.getElementById('rejectModal');
            if (event.target === approveModal) {
                approveModal.style.display = 'none';
            }
            if (event.target === rejectModal) {
                rejectModal.style.display = 'none';
            }
        }
    </script>
</body>
</html>