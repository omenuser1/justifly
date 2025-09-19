<?php
require_once 'config.php';
session_start();

// التحقق من تسجيل دخول الطالب
if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'student') {
    header("Location: index2.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// معالجة تحديث حالة الإشعارات إلى "مقروء"
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mark_as_read'])) {
    $notification_id = $_POST['notification_id'];
    $stmt = $conn->prepare("UPDATE notifications SET is_read = 1 WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $notification_id, $user_id);
    $stmt->execute();
    header("Location: notifications.php");
    exit();
}

// معالجة حذف إشعار
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_notification'])) {
    $notification_id = $_POST['notification_id'];
    $stmt = $conn->prepare("DELETE FROM notifications WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $notification_id, $user_id);
    $stmt->execute();
    header("Location: notifications.php");
    exit();
}

// معالجة تحديث جميع الإشعارات إلى "مقروء"
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mark_all_read'])) {
    $stmt = $conn->prepare("UPDATE notifications SET is_read = 1 WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    header("Location: notifications.php");
    exit();
}

// جلب جميع إشعارات الطالب
$stmt = $conn->prepare("
    SELECT * FROM notifications 
    WHERE user_id = ? 
    ORDER BY created_at DESC
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$notifications = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// حساب عدد الإشعارات غير المقروءة
$unread_count = 0;
foreach ($notifications as $notification) {
    if (!$notification['is_read']) {
        $unread_count++;
    }
}

// تحديث عدد الإشعارات في الجلسة
$_SESSION['unread_notifications'] = $unread_count;
?>

<!DOCTYPE html>
<html lang="fr" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications - Justifly</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* نفس أنماط CSS من الصفحات السابقة */
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
        
        .notifications-container {
            background: var(--bg-card);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            padding: 2rem;
            max-width: 800px;
            margin: 0 auto;
        }
        
        .notification-item {
            background: rgba(255, 255, 255, 0.03);
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            border-left: 4px solid transparent;
            transition: all 0.3s ease;
            position: relative;
        }
        
        .notification-item:hover {
            background: rgba(255, 255, 255, 0.05);
            transform: translateX(5px);
        }
        
        .notification-item.unread {
            border-left-color: var(--accent-color);
            background: rgba(102, 126, 234, 0.05);
        }
        
        .notification-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 0.5rem;
        }
        
        .notification-time {
            font-size: 0.875rem;
            color: var(--text-secondary);
        }
        
        .notification-message {
            color: var(--text-primary);
            line-height: 1.6;
            margin-bottom: 1rem;
        }
        
        .notification-actions {
            display: flex;
            gap: 0.5rem;
        }
        
        .btn-small {
            padding: 0.375rem 0.75rem;
            border-radius: 8px;
            font-size: 0.875rem;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .btn-read {
            background: rgba(16, 185, 129, 0.2);
            color: #10b981;
        }
        
        .btn-read:hover {
            background: rgba(16, 185, 129, 0.3);
        }
        
        .btn-delete {
            background: rgba(239, 68, 68, 0.2);
            color: #ef4444;
        }
        
        .btn-delete:hover {
            background: rgba(239, 68, 68, 0.3);
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
            background: var(--accent-color);
            color: white;
            border-radius: 9999px;
            padding: 0.25rem 0.75rem;
            font-size: 0.875rem;
            font-weight: 600;
        }
        
        .empty-state {
            text-align: center;
            padding: 3rem;
            color: var(--text-secondary);
        }
        
        .empty-state i {
            font-size: 4rem;
            margin-bottom: 1rem;
            opacity: 0.5;
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
            
            .notifications-container {
                padding: 1.5rem;
            }
            
            .notification-header {
                flex-direction: column;
                gap: 0.5rem;
            }
            
            .notification-actions {
                justify-content: flex-end;
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
                    <p class="text-sm text-gray-400">Étudiant</p>
                </div>
            </div>
            
            <nav>
                <div class="sidebar-item" onclick="window.location.href='student_dashboard.php'">
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
                <div class="sidebar-item active">
                    <i class="fas fa-bell"></i>
                    <span>Notifications</span>
                    <?php if ($unread_count > 0): ?>
                        <span class="notification-badge"><?php echo $unread_count; ?></span>
                    <?php endif; ?>
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
                <h1 class="text-3xl font-bold mb-2">Notifications</h1>
                <p class="text-gray-400">Restez informé des dernières actualités</p>
            </div>
            <button class="md:hidden" onclick="toggleSidebar()">
                <i class="fas fa-bars text-2xl"></i>
            </button>
        </div>

        <!-- Notifications Container -->
        <div class="notifications-container">
            <!-- Header Actions -->
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="text-xl font-semibold">Toutes les notifications</h2>
                    <p class="text-sm text-gray-400">
                        <?php echo $unread_count; ?> non lue(s) sur <?php echo count($notifications); ?>
                    </p>
                </div>
                <?php if ($unread_count > 0): ?>
                    <form method="POST" style="display: inline;">
                        <button type="submit" name="mark_all_read" class="btn-small btn-read">
                            <i class="fas fa-check-double mr-2"></i>
                            Tout marquer comme lu
                        </button>
                    </form>
                <?php endif; ?>
            </div>

            <!-- Notifications List -->
            <div class="notifications-list">
                <?php if (empty($notifications)): ?>
                    <div class="empty-state">
                        <i class="fas fa-bell-slash"></i>
                        <h3 class="text-lg font-semibold mb-2">Aucune notification</h3>
                        <p>Vous n'avez aucune notification pour le moment.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($notifications as $notification): ?>
                        <div class="notification-item <?php echo !$notification['is_read'] ? 'unread' : ''; ?>">
                            <div class="notification-header">
                                <div class="flex items-center gap-2">
                                    <?php if (!$notification['is_read']): ?>
                                        <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
                                    <?php endif; ?>
                                    <span class="font-semibold">
                                        <?php 
                                        // تحديد أيقونة بناءً على محتوى الرسالة
                                        if (strpos($notification['message'], 'absence') !== false) {
                                            echo '<i class="fas fa-calendar-times text-blue-500"></i>';
                                        } elseif (strpos($notification['message'], 'approuvée') !== false) {
                                            echo '<i class="fas fa-check-circle text-green-500"></i>';
                                        } elseif (strpos($notification['message'], 'rejetée') !== false) {
                                            echo '<i class="fas fa-times-circle text-red-500"></i>';
                                        } else {
                                            echo '<i class="fas fa-info-circle text-purple-500"></i>';
                                        }
                                        ?>
                                    </span>
                                </div>
                                <span class="notification-time">
                                    <?php 
                                    $date = new DateTime($notification['created_at']);
                                    $now = new DateTime();
                                    $interval = $date->diff($now);
                                    
                                    if ($interval->days == 0) {
                                        echo "Aujourd'hui à " . $date->format('H:i');
                                    } elseif ($interval->days == 1) {
                                        echo "Hier à " . $date->format('H:i');
                                    } elseif ($interval->days < 7) {
                                        echo "Il y a " . $interval->days . " jours";
                                    } else {
                                        echo $date->format('d/m/Y à H:i');
                                    }
                                    ?>
                                </span>
                            </div>
                            
                            <div class="notification-message">
                                <?php echo htmlspecialchars($notification['message']); ?>
                            </div>
                            
                            <div class="notification-actions">
                                <?php if (!$notification['is_read']): ?>
                                    <form method="POST" style="display: inline;">
                                        <input type="hidden" name="notification_id" value="<?php echo $notification['id']; ?>">
                                        <button type="submit" name="mark_as_read" class="btn-small btn-read">
                                            <i class="fas fa-check mr-1"></i>
                                            Marquer comme lu
                                        </button>
                                    </form>
                                <?php endif; ?>
                                
                                <form method="POST" style="display: inline;" 
                                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette notification?');">
                                    <input type="hidden" name="notification_id" value="<?php echo $notification['id']; ?>">
                                    <button type="submit" name="delete_notification" class="btn-small btn-delete">
                                        <i class="fas fa-trash mr-1"></i>
                                        Supprimer
                                    </button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('open');
        }

        // تحديث عدد الإشعارات في القائمة الجانبية بشكل فوري
        function updateNotificationBadge() {
            const badge = document.querySelector('.notification-badge');
            const unreadItems = document.querySelectorAll('.notification-item.unread').length;
            
            if (unreadItems > 0) {
                if (badge) {
                    badge.textContent = unreadItems;
                } else {
                    const activeItem = document.querySelector('.sidebar-item.active');
                    const badgeElement = document.createElement('span');
                    badgeElement.className = 'notification-badge';
                    badgeElement.textContent = unreadItems;
                    activeItem.appendChild(badgeElement);
                }
            } else if (badge) {
                badge.remove();
            }
        }

        // استدعاء الدالة عند تحميل الصفحة
        document.addEventListener('DOMContentLoaded', updateNotificationBadge);

        // Theme toggle (si nécessaire)
        const themeToggle = document.getElementById('theme-toggle');
        if (themeToggle) {
            themeToggle.addEventListener('change', () => {
                const theme = themeToggle.checked ? 'light' : 'dark';
                document.documentElement.setAttribute('data-theme', theme);
                localStorage.setItem('theme', theme);
            });
        }
    </script>
</body>
</html>