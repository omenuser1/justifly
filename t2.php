<!DOCTYPE html>
<html lang="fr" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Étudiant - Justifly</title>
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
        /* Light Mode - مستوحى من الصفحة الجديدة */
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
            backdrop-filter: blur(20px);
            border: 1px solid var(--border-color);
            border-radius: 24px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.2);
            position: relative;
            overflow: hidden;
            transition: background-color 0.3s ease, border-color 0.3s ease;
        }
        
        .glass-card::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(102, 126, 234, 0.1) 0%, transparent 70%);
            opacity: 0;
            transition: opacity 0.5s ease;
        }
        
        .glass-card:hover::before {
            opacity: 1;
        }
        
        .minimalist-hero {
            background: radial-gradient(ellipse at top, var(--bg-secondary) 0%, var(--bg-primary) 100%);
            position: relative;
            overflow: hidden;
            min-height: 100vh;
            transition: background-color 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .floating-shapes {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 1;
        }
        
        .shape {
            position: absolute;
            border-radius: 50%;
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            filter: blur(1px);
        }
        
        .shape-1 {
            width: 300px;
            height: 300px;
            top: 10%;
            left: 5%;
            animation: floatSlow 25s ease-in-out infinite;
            background: radial-gradient(circle, rgba(102, 126, 234, 0.3) 0%, transparent 70%);
        }
        
        .shape-2 {
            width: 250px;
            height: 250px;
            top: 60%;
            right: 10%;
            animation: floatMedium 20s ease-in-out infinite reverse;
            background: radial-gradient(circle, rgba(118, 75, 162, 0.3) 0%, transparent 70%);
        }
        
        .shape-3 {
            width: 200px;
            height: 200px;
            top: 30%;
            right: 30%;
            animation: floatFast 15s ease-in-out infinite;
            background:  radial-gradient(circle, rgba(240, 147, 251, 0.3) 0%, transparent 70%);
        }
        
        .shape-4 {
            width: 150px;
            height: 150px;
            bottom: 20%;
            left: 20%;
            animation: floatSlow 22s ease-in-out infinite reverse;
            background:  radial-gradient(circle, rgba(16, 185, 129, 0.3) 0%, transparent 70%);
        }
        
        @keyframes floatSlow {
            0%, 100% { transform: translateY(0px) translateX(0px) rotate(0deg) scale(1); }
            25% { transform: translateY(-30px) translateX(20px) rotate(90deg) scale(1.1); }
            50% { transform: translateY(0px) translateX(40px) rotate(180deg) scale(0.9); }
            75% { transform: translateY(30px) translateX(20px) rotate(270deg) scale(1.05); }
        }
        
        @keyframes floatMedium {
            0%, 100% { transform: translateY(0px) translateX(0px) rotate(0deg) scale(1); }
            33% { transform: translateY(-20px) translateX(-30px) rotate(120deg) scale(1.05); }
            66% { transform: translateY(20px) translateX(30px) rotate(240deg) scale(0.95); }
        }
        
        @keyframes floatFast {
            0%, 100% { transform: translateY(0px) translateX(0px) rotate(0deg) scale(1); }
            20% { transform: translateY(-40px) translateX(-20px) rotate(72deg) scale(1.1); }
            40% { transform: translateY(20px) translateX(30px) rotate(144deg) scale(0.9); }
            60% { transform: translateY(-20px) translateX(-10px) rotate(216deg) scale(1.05); }
            80% { transform: translateY(10px) translateX(20px) rotate(288deg) scale(0.95); }
        }
        
        .gradient-text {
            background: var(--gradient-secondary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: gradientShift 5s ease-in-out infinite;
        }
        
        @keyframes gradientShift {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }
        
        .modern-button {
            background: var(--gradient-primary);
            border: none;
            border-radius: 16px;
            color: white;
            font-weight: 600;
            padding: 16px 32px;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
            transform-style: preserve-3d;
            perspective: 1000px;
            width: 100%;
        }
        
        .modern-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: left 0.5s;
        }
        
        .modern-button:hover::before {
            left: 100%;
        }
        
        .modern-button:hover {
            transform: translateY(-5px) rotateX(10deg);
            box-shadow: 0 20px 40px rgba(102, 126, 234, 0.5);
        }
        
        .glass-input {
            background: var(--bg-card);
            backdrop-filter: blur(10px);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            color: var(--text-primary);
            font-weight: 500;
            padding: 16px 20px;
            font-size: 16px;
            width: 100%;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .glass-input:focus {
            outline: none;
            border-color: var(--accent-color);
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.2);
        }
        
        .floating-icon {
            position: absolute;
            width: 60px;
            height: 60px;
            background: var(--bg-card);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            animation: floatIcon 8s ease-in-out infinite;
            border: 1px solid var(--border-color);
        }
        
        .icon-1 {
            top: 15%;
            left: 10%;
            animation-delay: 0s;
        }
        
        .icon-2 {
            top: 25%;
            right: 15%;
            animation-delay: 2s;
        }
        
        .icon-3 {
            bottom: 30%;
            left: 8%;
            animation-delay: 4s;
        }
        
        .icon-4 {
            bottom: 15%;
            right: 12%;
            animation-delay: 1s;
        }
        
        @keyframes floatIcon {
            0%, 100% { transform: translateY(0px) rotate(0deg) scale(1); }
            25% { transform: translateY(-20px) rotate(5deg) scale(1.05); }
            50% { transform: translateY(0px) rotate(0deg) scale(1); }
            75% { transform: translateY(10px) rotate(-5deg) scale(0.95); }
        }
        
        .interactive-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            background: radial-gradient(circle at var(--mouse-x, 50%) var(--mouse-y, 50%), rgba(102, 126, 234, 0.1) 0%, transparent 50%);
            pointer-events: none;
            transition: all 0.3s ease;
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
            .text-7xl { font-size: 5rem !important; }
            .text-8xl { font-size: 6rem !important; }
            .max-w-7xl { padding-left: 2rem; padding-right: 2rem; }
        }
        
        /* Responsive pour les tablettes */
        @media (max-width: 768px) {
            /* Titres */
            .text-7xl { font-size: 3.5rem !important; }
            .text-8xl { font-size: 4rem !important; }
            .text-5xl { font-size: 2.5rem !important; }
            .text-3xl { font-size: 1.5rem !important; }
            .text-2xl { font-size: 1.25rem !important; }
            .text-xl { font-size: 1rem !important; }
            
            /* Espacements */
            .py-20 { padding-top: 3rem !important; padding-bottom: 3rem !important; }
            .mb-16 { margin-bottom: 2rem !important; }
            .mb-12 { margin-bottom: 1.5rem !important; }
            .mb-8 { margin-bottom: 1rem !important; }
            .px-4 { padding-left: 1rem !important; padding-right: 1rem !important; }
            
            /* Cartes */
            .p-32 { padding: 1.5rem !important; }
            .p-24 { padding: 1rem !important; }
            .p-40 { padding: 1.5rem !important; }
            
            /* Boutons */
            .modern-button { 
                padding: 12px 20px !important; 
                font-size: 14px !important;
            }
            
            /* Icônes */
            .floating-icon { 
                width: 50px !important; 
                height: 50px !important;
                font-size: 20px !important;
            }
        }
        
        /* Responsive pour les mobiles */
        @media (max-width: 480px) {
            /* Titres */
            .text-7xl { font-size: 2.5rem !important; }
            .text-8xl { font-size: 3rem !important; }
            .text-5xl { font-size: 2rem !important; }
            
            /* Espacements */
            .py-20 { padding-top: 2rem !important; padding-bottom: 2rem !important; }
            .mb-16 { margin-bottom: 1.5rem !important; }
            
            /* Cartes */
            .glass-card { 
                padding: 1.5rem !important; 
            }
            
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
    <div class="interactive-bg"></div>
    
    <!-- Navigation -->
    <nav class="sticky top-0 z-50 border-b backdrop-filter backdrop-blur-lg shadow-lg" style="background-color: var(--nav-bg); border-color: var(--nav-border);">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0 flex items-center">
                        <div class="w-10 h-10 gradient-bg rounded-lg flex items-center justify-center mr-3 transform hover:rotate-12 transition-transform duration-300">
                            <span class="text-white font-bold text-xl">J</span>
                        </div>
                        <span class="text-2xl font-bold gradient-text">Justifly</span>
                    </div>
                </div>
                
                <div class="hidden md:block">
                    <div class="ml-10 flex items-baseline space-x-8">
                        <a href="index.html" class="nav-item px-3 py-2 font-medium" style="color: var(--text-secondary);">Accueil</a>
                        <a href="about_us.php" class="nav-item px-3 py-2 font-medium" style="color: var(--text-secondary);">À propos</a>
                        <a href="features.php" class="nav-item px-3 py-2 font-medium" style="color: var(--text-secondary);">Fonctionnalités</a>
                        <a href="contact.php" class="nav-item px-3 py-2 font-medium" style="color: var(--text-secondary);">Contact</a>
                        <a href="chatbot/chatbot.html" class="nav-item px-3 py-2 font-medium" style="color: var(--text-secondary);">AI assistante</a>
                        <a href="login.php" class="gradient-bg hover:opacity-90 text-white px-4 py-2 rounded-lg font-medium transition duration-300 transform hover:scale-105">Se connecter</a>
                    </div>
                </div>
                
                <div class="flex items-center space-x-4">
                    <select class="rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" style="background-color: var(--select-bg); border-color: var(--select-border); color: var(--text-secondary);">
                        <option>🇫🇷 Français</option>
                        <option>🇬🇧 English</option>
                        <option>🇸🇦 العربية</option>
                    </select>
                    
                    <!-- Theme Switch -->
                    <div class="theme-switch-nav">
                        <span class="theme-label"><i class="fas fa-moon"></i></span>
                        <label class="switch">
                            <input type="checkbox" id="theme-toggle">
                            <span class="slider"></span>
                        </label>
                        <span class="theme-label"><i class="fas fa-sun"></i></span>
                    </div>
                </div>
                
                <!-- Bouton hamburger pour mobile -->
                <button id="mobile-menu-btn" class="md:hidden focus:outline-none" style="color: var(--text-secondary);">
                    <i class="fas fa-bars text-xl"></i>
                </button>
            </div>
        </div>
        
        <!-- Menu mobile -->
        <div id="mobile-menu" class="md:hidden hidden backdrop-filter backdrop-blur-lg" style="background-color: var(--bg-secondary); border-top-color: var(--border-color);">
            <div class="px-2 pt-2 pb-3 space-y-1">
                <a href="index.html" class="block px-3 py-2 font-medium" style="color: var(--text-secondary);">Accueil</a>
                <a href="about_us.php" class="block px-3 py-2 font-medium" style="color: var(--text-secondary);">À propos</a>
                <a href="features.php" class="block px-3 py-2 font-medium" style="color: var(--text-secondary);">Fonctionnalités</a>
                <a href="contact.php" class="block px-3 py-2 font-medium" style="color: var(--text-secondary);">Contact</a>
                <a href="chatbot/chatbot.html" class="block px-3 py-2 font-medium" style="color: var(--text-secondary);">AI assistante</a>
                <a href="login.php" class="block gradient-bg hover:opacity-90 text-white px-4 py-2 rounded-lg font-medium transition duration-300 transform hover:scale-105 mt-2">Se connecter</a>
            </div>
        </div>
    </nav>
    
    <!-- Login Section -->
    <section class="minimalist-hero relative">
        <!-- Floating Shapes -->
        <div class="floating-shapes">
            <div class="shape shape-1"></div>
            <div class="shape shape-2"></div>
            <div class="shape shape-3"></div>
            <div class="shape shape-4"></div>
        </div>
        
        <!-- Floating Icons -->
        <div class="floating-elements">
            <div class="floating-icon icon-1"><i class="fas fa-user-graduate text-blue-400"></i></div>
            <div class="floating-icon icon-2"><i class="fas fa-lock text-purple-400"></i></div>
            <div class="floating-icon icon-3"><i class="fas fa-shield-alt text-green-400"></i></div>
            <div class="floating-icon icon-4"><i class="fas fa-key text-orange-400"></i></div>
        </div>
        
        <div class="max-w-md w-full mx-auto p-8 relative z-10">
            <div class="glass-card p-8 transform transition-all duration-500 hover:scale-105">
                <div class="text-center mb-8">
                    <div class="w-20 h-20 gradient-bg rounded-full flex items-center justify-center mx-auto mb-6 transform hover:rotate-12 transition-transform duration-300">
                        <i class="fas fa-user-graduate text-white text-3xl"></i>
                    </div>
                    <h2 class="text-3xl font-bold gradient-text mb-2">Connexion Étudiant</h2>
                    <p class="text-lg" style="color: var(--text-secondary);">Accédez à votre espace étudiant</p>
                </div>
                
                <form id="login-form" class="space-y-6">
                    <div>
                        <label for="username" class="block mb-2 font-medium" style="color: var(--text-primary);">Nom d'utilisateur</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-user" style="color: var(--text-muted);"></i>
                            </div>
                            <input 
                                type="text" 
                                id="username" 
                                name="username" 
                                class="glass-input pl-10" 
                                placeholder="Entrez votre nom d'utilisateur"
                                required
                            >
                        </div>
                    </div>
                    
                    <div>
                        <label for="password" class="block mb-2 font-medium" style="color: var(--text-primary);">Mot de passe</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-lock" style="color: var(--text-muted);"></i>
                            </div>
                            <input 
                                type="password" 
                                id="password" 
                                name="password" 
                                class="glass-input pl-10" 
                                placeholder="Entrez votre mot de passe"
                                required
                            >
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                <button type="button" id="toggle-password" class="text-gray-500 hover:text-gray-700 focus:outline-none">
                                    <i class="fas fa-eye" id="eye-icon"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input 
                                id="remember-me" 
                                name="remember-me" 
                                type="checkbox" 
                                class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                style="background-color: var(--bg-card); border-color: var(--border-color);"
                            >
                            <label for="remember-me" class="ml-2 block text-sm" style="color: var(--text-secondary);">
                                Se souvenir de moi
                            </label>
                        </div>
                        
                        <div class="text-sm">
                            <a href="#" class="font-medium hover:underline" style="color: var(--accent-color);">
                                Mot de passe oublié?
                            </a>
                        </div>
                    </div>
                    
                    <div>
                        <button type="submit" class="modern-button">
                            Se connecter
                        </button>
                    </div>
                    
                    <div class="text-center mt-6">
                        <p class="text-sm" style="color: var(--text-secondary);">
                            Vous n'avez pas de compte? 
                            <a href="#" class="font-medium hover:underline" style="color: var(--accent-color);">
                                Créer un compte
                            </a>
                        </p>
                    </div>
                </form>
                
                <div class="mt-8 text-center">
                    <p class="text-sm" style="color: var(--text-muted);">
                        Ou connectez-vous avec
                    </p>
                    <div class="flex justify-center space-x-4 mt-4">
                        <a href="#" class="w-10 h-10 rounded-full flex items-center justify-center glass-card hover:scale-110 transition-transform duration-300">
                            <i class="fab fa-google text-red-500"></i>
                        </a>
                        <a href="#" class="w-10 h-10 rounded-full flex items-center justify-center glass-card hover:scale-110 transition-transform duration-300">
                            <i class="fab fa-facebook-f text-blue-600"></i>
                        </a>
                        <a href="#" class="w-10 h-10 rounded-full flex items-center justify-center glass-card hover:scale-110 transition-transform duration-300">
                            <i class="fab fa-microsoft text-blue-500"></i>
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="text-center mt-8">
                <a href="index.html" class="inline-flex items-center text-sm font-medium hover:underline" style="color: var(--text-secondary);">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Retour à l'accueil
                </a>
            </div>
        </div>
    </section>
    
    <script>
        // ===== التحكم في الوضع =====
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
        
        // Interactive background that follows mouse
        document.addEventListener('mousemove', (e) => {
            const x = e.clientX / window.innerWidth;
            const y = e.clientY / window.innerHeight;
            document.documentElement.style.setProperty('--mouse-x', `${x * 100}%`);
            document.documentElement.style.setProperty('--mouse-y', `${y * 100}%`);
        });
        
        // Toggle password visibility
        const togglePassword = document.getElementById('toggle-password');
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eye-icon');
        
        togglePassword.addEventListener('click', () => {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            
            // Toggle eye icon
            if (type === 'password') {
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            } else {
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            }
        });
        
        // Form submission
        const loginForm = document.getElementById('login-form');
        loginForm.addEventListener('submit', (e) => {
            e.preventDefault();
            
            // Get form values
            const username = document.getElementById('username').value;
            const password = document.getElementById('password').value;
            
            // Here you would typically send the data to your server for authentication
            console.log('Login attempt:', { username, password });
            
            // For demo purposes, we'll just show an alert
            alert(`Connexion en tant que: ${username}`);
            
            // In a real application, you would redirect to the student dashboard
            // window.location.href = 'student-dashboard.html';
        });
        
        // Fonctionnalité du menu mobile
        document.getElementById('mobile-menu-btn').addEventListener('click', function() {
            const mobileMenu = document.getElementById('mobile-menu');
            mobileMenu.classList.toggle('hidden');
        });
        
        // Fermer le menu mobile lors du clic sur un lien
        document.querySelectorAll('#mobile-menu a').forEach(link => {
            link.addEventListener('click', function() {
                const mobileMenu = document.getElementById('mobile-menu');
                mobileMenu.classList.add('hidden');
            });
        });
    </script>
</body>
</html>