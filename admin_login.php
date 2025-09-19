<?php
require_once 'config.php';
session_start();

// إذا كان الأدمن مسجل الدخول بالفعل، إعادة التوجيه إلى لوحة التحكم
if (isset($_SESSION['loggedin']) && $_SESSION['role'] === 'admin') {
    header("Location: admin_dashboard.php");
    exit();
}

// معالجة تسجيل الدخول
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    
    if (empty($username) || empty($password)) {
        $error = "Veuillez remplir tous les champs.";
    } else {
        // التحقق من بيانات الأدمن
        $sql = "SELECT id, username, password, email, role, department_id, sub_department_id 
                FROM users 
                WHERE username = ? AND role = 'admin'";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            
            // التحقق من كلمة المرور
            if (password_verify($password, $user['password'])) {
                // تعيين بيانات الجلسة
                $_SESSION['loggedin'] = true;
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['department_id'] = $user['department_id'];
                $_SESSION['sub_department_id'] = $user['sub_department_id'];
                
                // تسجيل وقت تسجيل الدخول (اختياري)
                $login_time = date('Y-m-d H:i:s');
                // $stmt = $conn->prepare("INSERT INTO user_logs (user_id, action, action_time) VALUES (?, 'login', ?)");
                // $stmt->bind_param("is", $user['id'], $login_time);
                // $stmt->execute();
                
                header("Location: admin_dashboard.php");
                exit();
            } else {
                $error = "Mot de passe incorrect.";
            }
        } else {
            $error = "Nom d'utilisateur incorrect ou vous n'êtes pas administrateur.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Administrateur - Justifly</title>
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
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }
        
        .bg-animation {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            overflow: hidden;
        }
        
        .bg-circle {
            position: absolute;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(102, 126, 234, 0.1) 0%, transparent 70%);
            filter: blur(1px);
            animation: float 20s infinite ease-in-out;
        }
        
        .bg-circle:nth-child(1) {
            width: 300px;
            height: 300px;
            top: 10%;
            left: 5%;
            animation-delay: 0s;
            animation-duration: 25s;
        }
        
        .bg-circle:nth-child(2) {
            width: 200px;
            height: 200px;
            top: 60%;
            right: 10%;
            animation-delay: 5s;
            animation-duration: 20s;
            background: radial-gradient(circle, rgba(118, 75, 162, 0.1) 0%, transparent 70%);
        }
        
        .bg-circle:nth-child(3) {
            width: 150px;
            height: 150px;
            top: 30%;
            right: 30%;
            animation-delay: 2s;
            animation-duration: 22s;
            background: radial-gradient(circle, rgba(240, 147, 251, 0.1) 0%, transparent 70%);
        }
        
        .bg-circle:nth-child(4) {
            width: 100px;
            height: 100px;
            bottom: 20%;
            left: 20%;
            animation-delay: 7s;
            animation-duration: 18s;
            background: radial-gradient(circle, rgba(16, 185, 129, 0.1) 0%, transparent 70%);
        }
        
        @keyframes float {
            0%, 100% {
                transform: translateY(0) translateX(0) rotate(0deg) scale(1);
            }
            25% {
                transform: translateY(-20px) translateX(15px) rotate(90deg) scale(1.05);
            }
            50% {
                transform: translateY(0) translateX(30px) rotate(180deg) scale(0.95);
            }
            75% {
                transform: translateY(20px) translateX(15px) rotate(270deg) scale(1.02);
            }
        }
        
        .login-container {
            background: var(--bg-card);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            padding: 3rem;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
            position: relative;
            overflow: hidden;
            z-index: 1;
        }
        
        .login-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--gradient-primary);
        }
        
        .logo-section {
            text-align: center;
            margin-bottom: 2.5rem;
        }
        
        .logo {
            width: 80px;
            height: 80px;
            background: var(--gradient-primary);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 2rem;
            color: white;
            font-weight: bold;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
            transition: transform 0.3s ease;
        }
        
        .logo:hover {
            transform: scale(1.05) rotate(5deg);
        }
        
        .form-group {
            margin-bottom: 1.5rem;
            position: relative;
        }
        
        .form-input {
            width: 100%;
            padding: 1rem 1rem 1rem 3rem;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            color: var(--text-primary);
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        
        .form-input:focus {
            outline: none;
            border-color: var(--accent-color);
            background: rgba(255, 255, 255, 0.08);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.2);
        }
        
        .form-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-secondary);
            transition: all 0.3s ease;
        }
        
        .form-input:focus ~ .form-icon {
            color: var(--accent-color);
            transform: translateY(-50%) scale(1.1);
        }
        
        .submit-btn {
            width: 100%;
            padding: 1rem;
            background: var(--gradient-primary);
            border: none;
            border-radius: 12px;
            color: white;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 1rem;
            position: relative;
            overflow: hidden;
        }
        
        .submit-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: left 0.5s ease;
        }
        
        .submit-btn:hover::before {
            left: 100%;
        }
        
        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        }
        
        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 1.5rem 0;
            font-size: 0.875rem;
        }
        
        .checkbox-label {
            display: flex;
            align-items: center;
            color: var(--text-secondary);
            cursor: pointer;
            transition: color 0.3s ease;
        }
        
        .checkbox-label:hover {
            color: var(--text-primary);
        }
        
        .checkbox-label input {
            margin-right: 0.5rem;
            accent-color: var(--accent-color);
        }
        
        .back-link {
            position: absolute;
            top: 1rem;
            left: 1rem;
            color: var(--text-secondary);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.875rem;
            transition: all 0.3s ease;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.05);
            z-index: 10;
        }
        
        .back-link:hover {
            color: var(--accent-color);
            background: rgba(255, 255, 255, 0.1);
            transform: translateX(-3px);
        }
        
        .success-message, .error-message {
            display: none;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1rem;
            text-align: center;
            animation: slideIn 0.3s ease;
        }
        
        .success-message {
            background: rgba(16, 185, 129, 0.1);
            border: 1px solid rgba(16, 185, 129, 0.3);
            color: #10b981;
        }
        
        .error-message {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #ef4444;
        }
        
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .admin-badge {
            background: rgba(239, 68, 68, 0.2);
            color: #ef4444;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-left: 0.5rem;
        }
        
        @media (max-width: 480px) {
            .login-container {
                padding: 2rem;
                margin: 1rem;
            }
            
            .logo {
                width: 60px;
                height: 60px;
                font-size: 1.5rem;
            }
            
            .back-link {
                top: 0.5rem;
                left: 0.5rem;
                font-size: 0.75rem;
            }
            
            .bg-animation {
                display: none;
            }
        }
    </style>
</head>
<body>
    <!-- Animated Background -->
    <div class="bg-animation">
        <div class="bg-circle"></div>
        <div class="bg-circle"></div>
        <div class="bg-circle"></div>
        <div class="bg-circle"></div>
    </div>
    
    <div class="login-container">
        <a href="index.php" class="back-link">
            <i class="fas fa-arrow-left"></i>
            Retour
        </a>
        
        <div class="logo-section">
            <div class="logo">J</div>
            <h1 class="text-2xl font-bold mb-2">Justifly</h1>
            <p class="text-secondary">Connexion Administrateur <span class="admin-badge">ADMIN</span></p>
        </div>
        
        <?php if (isset($error)): ?>
            <div class="error-message" id="error-message">
                <i class="fas fa-exclamation-circle mr-2"></i>
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <input type="text" class="form-input" name="username" placeholder="Nom d'utilisateur" required>
                <i class="fas fa-user-shield form-icon"></i>
            </div>
            
            <div class="form-group">
                <input type="password" class="form-input" name="password" placeholder="Mot de passe" required>
                <i class="fas fa-lock form-icon"></i>
            </div>
            
            <div class="form-options">
                <label class="checkbox-label">
                    <input type="checkbox" name="remember"> Se souvenir
                </label>
                <a href="#" class="text-blue-400 hover:text-blue-300">Mot de passe oublié?</a>
            </div>
            
            <button type="submit" class="submit-btn">
                <i class="fas fa-sign-in-alt mr-2"></i>
                Se connecter
            </button>
        </form>
        
        <div class="text-center mt-6 text-sm text-gray-400">
            <p>Accès réservé aux administrateurs</p>
            <p class="mt-2">
                <a href="index2.php" class="text-blue-400 hover:text-blue-300">
                    <i class="fas fa-exchange-alt mr-1"></i>
                    Espace Étudiants/Enseignants
                </a>
            </p>
        </div>
    </div>
    
    <script>
        // Interactive mouse effect
        document.addEventListener('mousemove', (e) => {
            const circles = document.querySelectorAll('.bg-circle');
            const x = e.clientX / window.innerWidth;
            const y = e.clientY / window.innerHeight;
            
            circles.forEach((circle, index) => {
                const speed = (index + 1) * 0.5;
                const xOffset = (x - 0.5) * speed * 20;
                const yOffset = (y - 0.5) * speed * 20;
                
                circle.style.transform = `translate(${xOffset}px, ${yOffset}px)`;
            });
        });
        
        // Add input focus effects
        const inputs = document.querySelectorAll('.form-input');
        inputs.forEach(input => {
            input.addEventListener('focus', () => {
                input.parentElement.style.transform = 'scale(1.02)';
            });
            
            input.addEventListener('blur', () => {
                input.parentElement.style.transform = 'scale(1)';
            });
        });
        
        // Add hover effect to logo
        const logo = document.querySelector('.logo');
        logo.addEventListener('mouseenter', () => {
            logo.style.boxShadow = '0 15px 40px rgba(102, 126, 234, 0.5)';
        });
        
        logo.addEventListener('mouseleave', () => {
            logo.style.boxShadow = '0 10px 30px rgba(102, 126, 234, 0.3)';
        });
    </script>
</body>
</html>