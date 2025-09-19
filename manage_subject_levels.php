<?php
require_once 'config.php';
session_start();

// التحقق من تسجيل دخول الأدمن
if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'admin') {
    header("Location: admin_login.php");
    exit();
}

$subject_id = $_GET['subject_id'] ?? 0;

if (!$subject_id) {
    header("Location: admin_subjects.php");
    exit();
}

// معالجة إضافة مستوى
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_level_to_subject'])) {
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
        
        $_SESSION['success'] = "Niveau ajouté avec succès!";
    } else {
        $_SESSION['error'] = "Ce niveau est déjà assigné à cette matière.";
    }
    
    header("Location: manage_subject_levels.php?subject_id=$subject_id");
    exit();
}

// معالجة إزالة مستوى
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_level_from_subject'])) {
    $subject_level_id = $_POST['subject_level_id'];
    
    // حذف التخصيص
    $stmt = $conn->prepare("DELETE FROM subject_levels WHERE id = ?");
    $stmt->bind_param("i", $subject_level_id);
    $stmt->execute();
    
    $_SESSION['success'] = "Niveau supprimé avec succès!";
    header("Location: manage_subject_levels.php?subject_id=$subject_id");
    exit();
}

// جلب معلومات المادة
$stmt = $conn->prepare("
    SELECT s.*, sd.name as sub_department_name
    FROM subjects s
    JOIN sub_departments sd ON s.sub_department_id = sd.id
    WHERE s.id = ? AND sd.department_id = ?
");
$stmt->bind_param("ii", $subject_id, $_SESSION['department_id']);
$stmt->execute();
$subject = $stmt->get_result()->fetch_assoc();

if (!$subject) {
    $_SESSION['error'] = "Matière non trouvée";
    header("Location: admin_subjects.php");
    exit();
}

// جلب المستويات المتاحة في القسم
$stmt = $conn->prepare("
    SELECT l.id, l.name
    FROM levels l
    JOIN sub_departments sd ON l.sub_department_id = sd.id
    WHERE sd.department_id = ?
    ORDER BY l.name
");
$stmt->bind_param("i", $_SESSION['department_id']);
$stmt->execute();
$available_levels = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// جلب المستويات الحالية للمادة
$stmt = $conn->prepare("
    SELECT sl.id, sl.subject_id, sl.level_id, l.name as level_name
    FROM subject_levels sl
    JOIN levels l ON sl.level_id = l.id
    WHERE sl.subject_id = ?
    ORDER BY l.name
");
$stmt->bind_param("i", $subject_id);
$stmt->execute();
$current_levels = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// تحديد المستويات المتاحة (التي لم يتم تخصيصها بعد)
$levels_to_add = [];
foreach ($available_levels as $level) {
    $exists = false;
    foreach ($current_levels as $current) {
        if ($current['level_id'] == $level['id']) {
            $exists = true;
            break;
        }
    }
    if (!$exists) {
        $levels_to_add[] = $level;
    }
}
?>

<!DOCTYPE html>
<html lang="fr" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gérer les Niveaux - Justifly</title>
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
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg-primary);
            color: var(--text-primary);
        }
        
        .card {
            background: var(--bg-card);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 1rem;
        }
        
        .btn-success {
            background: rgba(16, 185, 129, 0.2);
            border: 1px solid rgba(16, 185, 129, 0.3);
            color: #10b981;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
        }
        
        .btn-danger {
            background: rgba(239, 68, 68, 0.2);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #ef4444;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
        }
        
        .level-badge {
            background: var(--accent-color);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="p-4">
        <!-- Subject Info -->
        <div class="card">
            <h3 class="text-lg font-semibold mb-2"><?php echo htmlspecialchars($subject['name']); ?></h3>
            <p class="text-gray-400">Code: <?php echo htmlspecialchars($subject['code']); ?></p>
            <p class="text-gray-400">Spécialité: <?php echo htmlspecialchars($subject['sub_department_name']); ?></p>
        </div>

        <!-- Success/Error Messages -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="bg-green-500/20 border border-green-500/30 rounded-lg p-4 mb-4 flex items-center gap-3">
                <i class="fas fa-check-circle text-green-500"></i>
                <span><?php echo htmlspecialchars($_SESSION['success']); ?></span>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="bg-red-500/20 border border-red-500/30 rounded-lg p-4 mb-4 flex items-center gap-3">
                <i class="fas fa-exclamation-circle text-red-500"></i>
                <span><?php echo htmlspecialchars($_SESSION['error']); ?></span>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <!-- Add Level Form -->
        <div class="card">
            <h4 class="font-semibold mb-3">Ajouter un niveau</h4>
            <form method="POST">
                <div class="flex gap-3">
                    <select name="level_id" class="flex-1 px-3 py-2 bg-gray-800 border border-gray-700 rounded-lg text-white" required>
                        <option value="">Sélectionnez un niveau</option>
                        <?php foreach ($levels_to_add as $level): ?>
                            <option value="<?php echo $level['id']; ?>"><?php echo htmlspecialchars($level['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                    <button type="submit" name="add_level_to_subject" class="btn-success">
                        <i class="fas fa-plus mr-2"></i>
                        Ajouter
                    </button>
                </div>
            </form>
        </div>

        <!-- Current Levels -->
        <div class="card">
            <h4 class="font-semibold mb-3">Niveaux actuels</h4>
            <?php if (empty($current_levels)): ?>
                <p class="text-gray-400 text-center py-4">Aucun niveau assigné</p>
            <?php else: ?>
                <div class="space-y-2">
                    <?php foreach ($current_levels as $level): ?>
                        <div class="flex justify-between items-center bg-gray-800/50 rounded-lg p-3">
                            <span class="level-badge"><?php echo htmlspecialchars($level['level_name']); ?></span>
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="subject_level_id" value="<?php echo $level['id']; ?>">
                                <button type="submit" name="remove_level_from_subject" class="btn-danger" 
                                        onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce niveau?');">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>