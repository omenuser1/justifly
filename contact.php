<!DOCTYPE html>
<html lang="fr" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contactez-nous - Justifly</title>
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
            backdrop-filter: blur(10px);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
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
        
        .glass-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 40px rgba(102, 126, 234, 0.2);
            border-color: rgba(102, 126, 234, 0.3);
        }
        
        .nav-item {
            position: relative;
            transition: all 0.3s ease;
            font-size: 14px; /* أضف هذا السطر */
        }
        
        .nav-item::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 50%;
            width: 0;
            height: 2px;
            background: var(--gradient-primary);
            transition: all 0.3s ease;
            transform: translateX(-50%);
        }
        
        .nav-item:hover::after {
            width: 100%;
        }
        
        .contact-icon {
            width: 80px;
            height: 80px;
            background: rgba(102, 126, 234, 0.1);
            border: 2px solid rgba(102, 126, 234, 0.3);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
            overflow: hidden;
        }
        
        .contact-icon::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            background: radial-gradient(circle, rgba(255,255,255,0.3) 0%, transparent 70%);
            transform: translate(-50%, -50%);
            transition: all 0.5s ease;
        }
        
        .contact-icon:hover::before {
            width: 100px;
            height: 100px;
        }
        
        .contact-icon:hover {
            transform: rotateY(180deg) scale(1.1);
            background: rgba(102, 126, 234, 0.2);
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
        }
        
        .contact-icon i {
            font-size: 2.5rem;
            z-index: 1;
            transition: all 0.3s ease;
        }
        
        .contact-icon:hover i {
            transform: scale(1.2);
        }
        
        .form-input {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 16px 20px;
            color: var(--text-primary);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .form-input:focus {
            outline: none;
            border-color: var(--accent-color);
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.2);
            background: rgba(255, 255, 255, 0.08);
            transform: translateY(-2px);
        }
        
        .form-input::placeholder {
            color: var(--text-secondary);
        }
        
        .gradient-text {
            background: var(--gradient-secondary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .social-link {
            width: 60px;
            height: 60px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
            overflow: hidden;
        }
        
        .social-link::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s ease;
        }
        
        .social-link:hover::before {
            left: 100%;
        }
        
        .social-link:hover {
            background: rgba(102, 126, 234, 0.2);
            border-color: var(--accent-color);
            transform: translateY(-5px) rotate(5deg);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
        }
        
        .social-link i {
            font-size: 1.5rem;
            transition: all 0.3s ease;
        }
        
        .social-link:hover i {
            transform: scale(1.2) rotate(5deg);
        }
        
        .faq-item {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 24px;
            margin-bottom: 16px;
            transition: all 0.3s ease;
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }
        
        .faq-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(102, 126, 234, 0.1), transparent);
            transition: left 0.5s ease;
        }
        
        .faq-item:hover::before {
            left: 100%;
        }
        
        .faq-item:hover {
            background: var(--bg-card-hover);
            border-color: rgba(102, 126, 234, 0.3);
            transform: translateX(10px);
        }
        
        .faq-question {
            font-weight: 600;
            color: var(--text-primary);
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 1.1rem;
        }
        
        .faq-answer {
            color: var(--text-secondary);
            margin-top: 16px;
            display: none;
            animation: fadeIn 0.3s ease;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .faq-item.active .faq-answer {
            display: block;
        }
        
        .faq-item.active .faq-question i {
            transform: rotate(180deg);
        }
        
        .map-container {
            height: 450px;
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 20px;
            overflow: hidden;
            position: relative;
            transition: all 0.3s ease;
        }
        
        .map-container:hover {
            transform: scale(1.02);
            box-shadow: 0 15px 35px rgba(102, 126, 234, 0.2);
        }
        
        .map-placeholder {
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            position: relative;
        }
        
        .pulse-dot {
            width: 20px;
            height: 20px;
            background: var(--success-color);
            border-radius: 50%;
            position: absolute;
            animation: pulse 2s infinite;
        }
        
        .pulse-dot:nth-child(1) {
            top: 30%;
            left: 40%;
            animation-delay: 0s;
        }
        
        .pulse-dot:nth-child(2) {
            top: 60%;
            left: 60%;
            animation-delay: 0.5s;
        }
        
        .pulse-dot:nth-child(3) {
            top: 45%;
            left: 25%;
            animation-delay: 1s;
        }
        
        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
            }
            70% {
                box-shadow: 0 0 0 10px rgba(16, 185, 129, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(16, 185, 129, 0);
            }
        }
        
        .floating-shape {
            position: absolute;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(102, 126, 234, 0.1) 0%, transparent 70%);
            animation: float 20s infinite ease-in-out;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px) translateX(0px) rotate(0deg); }
            33% { transform: translateY(-30px) translateX(30px) rotate(120deg); }
            66% { transform: translateY(30px) translateX(-30px) rotate(240deg); }
        }
        
        .contact-stat {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 24px;
            text-align: center;
            transition: all 0.3s ease;
        }
        
        .contact-stat:hover {
            transform: translateY(-5px);
            background: rgba(102, 126, 234, 0.1);
            border-color: rgba(102, 126, 234, 0.3);
        }
        
        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .typing-effect {
            border-right: 2px solid var(--accent-color);
            animation: typing 3s steps(40, end), blink 0.75s step-end infinite;
            white-space: nowrap;
            overflow: hidden;
        }
        
        @keyframes typing {
            from { width: 0 }
            to { width: 100% }
        }
        
        @keyframes blink {
            from, to { border-color: transparent }
            50% { border-color: var(--accent-color); }
        }
        
        .submit-btn {
            position: relative;
            overflow: hidden;
            background: var(--gradient-primary);
            transition: all 0.3s ease;
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
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(102, 126, 234, 0.4);
        }
        
        .particle {
            position: absolute;
            width: 4px;
            height: 4px;
            background: rgba(102, 126, 234, 0.6);
            border-radius: 50%;
            pointer-events: none;
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
            
            /* Grilles */
            .grid-cols-2 { grid-template-columns: 1fr !important; }
            .grid-cols-3 { grid-template-columns: 1fr !important; }
            .grid-cols-4 { grid-template-columns: repeat(2, 1fr) !important; }
            
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
            
            /* Stats */
            .stat-number { font-size: 2rem !important; }
            
            /* Boutons */
            .modern-button, .glass-button { 
                padding: 12px 20px !important; 
                font-size: 14px !important;
            }
            
            /* Icônes */
            .feature-icon { 
                width: 60px !important; 
                height: 60px !important;
            }
            .feature-icon i { font-size: 1.5rem !important; }
            .testimonial-card img { 
                width: 40px !important; 
                height: 40px !important;
            }
            .step-number { 
                width: 40px !important; 
                height: 40px !important;
                font-size: 1.2rem !important;
            }
            
            /* Animations */
            .floating-shapes, .floating-icon, .interactive-bg { 
                display: none;
            }
            
            /* Navigation - Cacher le menu desktop sur mobile */
            nav .ml-10 {
                display: none !important;
            }
            
            /* Style du menu mobile */
            #mobile-menu {
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                z-index: 50;
                border-top: 1px solid rgba(255, 255, 255, 0.1);
            }
        }
        
        /* Responsive pour les mobiles */
        @media (max-width: 480px) {
            /* Titres */
            .text-7xl { font-size: 2.5rem !important; }
            .text-8xl { font-size: 3rem !important; }
            .text-5xl { font-size: 2rem !important; }
            
            /* Grilles */
            .grid-cols-4 { grid-template-columns: 1fr !important; }
            
            /* Stats */
            .stat-number { font-size: 1.5rem !important; }
            
            /* Espacements */
            .py-20 { padding-top: 2rem !important; padding-bottom: 2rem !important; }
            .mb-16 { margin-bottom: 1.5rem !important; }
            
            /* Cartes */
            .user-type-card, .feature-card, .testimonial-card { 
                padding: 1rem !important; 
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
    <!-- Floating shapes for visual interest -->
    <div class="floating-shape w-96 h-96 top-10 right-10 opacity-20"></div>
    <div class="floating-shape w-64 h-64 bottom-10 left-10 opacity-20" style="animation-delay: 5s;"></div>
    <div class="floating-shape w-80 h-80 top-1/2 left-1/2 opacity-15" style="animation-delay: 2s;"></div>
    
    <!-- Particles container -->
    <div id="particles"></div>
    
    <!-- Navigation -->
    <nav class="sticky top-0 z-50 border-b backdrop-filter backdrop-blur-lg shadow-lg" style="background-color: var(--nav-bg); border-color: var(--nav-border);">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0 flex items-center">
                        <div class="w-10 h-10 gradient-bg rounded-lg flex items-center justify-center mr-3">
                            <span class="text-white font-bold text-xl">J</span>
                        </div>
                        <span class="text-2xl font-bold gradient-text">Justifly</span>
                    </div>
                </div>
                
                <div class="hidden md:block">
                    <div class="ml-10 flex items-baseline space-x-8">
                        <a href="index.php" class="nav-item px-3 py-2 font-medium" style="color: var(--text-secondary);">Accueil</a>
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
                <a href="index.php" class="block px-3 py-2 font-medium" style="color: var(--text-secondary);">Accueil</a>
                <a href="about_us.php" class="block px-3 py-2 font-medium" style="color: var(--text-secondary);">À propos</a>
                <a href="features.php" class="block px-3 py-2 font-medium" style="color: var(--text-secondary);">Fonctionnalités</a>
                <a href="contact.php" class="block px-3 py-2 font-medium" style="color: var(--text-secondary);">Contact</a>
                <a href="chatbot/chatbot.html" class="block px-3 py-2 font-medium" style="color: var(--text-secondary);">AI assistante</a>
                <a href="login.php" class="block gradient-bg hover:opacity-90 text-white px-4 py-2 rounded-lg font-medium transition duration-300 transform hover:scale-105 mt-2">Se connecter</a>
            </div>
        </div>
    </nav>
    
    <!-- Main Content -->
    <main class="min-h-screen py-12 relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Hero Section -->
            <div class="text-center mb-16">
                <h1 class="text-6xl font-bold mb-4">
                    <span class="gradient-text">Contactez-nous</span>
                </h1>
                <p class="text-xl max-w-2xl mx-auto typing-effect" style="color: var(--text-secondary);">
                    Nous sommes là pour répondre à toutes vos questions et vous accompagner dans l'utilisation de Justifly
                </p>
                <div class="w-32 h-1 gradient-bg mx-auto rounded mt-6"></div>
            </div>

            <div class="grid lg:grid-cols-3 gap-8 mb-16">
                <!-- Contact Information -->
                <div class="lg:col-span-1 space-y-6">
                    <div class="glass-card p-6 transform hover:scale-105 transition-transform">
                        <div class="contact-icon">
                            <i class="fas fa-map-marker-alt text-red-400"></i>
                        </div>
                        <h3 class="text-xl font-semibold mb-2" style="color: var(--text-primary);">Adresse</h3>
                        <p style="color: var(--text-secondary);">
                            Université d'Aïn Témouchent<br>
                            Faculté des Sciences <br>
                            Département mathematique et Informatique<br>
                            Aïn Témouchent, Algérie
                        </p>
                    </div>
                    <div class="glass-card p-6 transform hover:scale-105 transition-transform">
                        <div class="contact-icon">
                            <i class="fas fa-phone text-green-400"></i>
                        </div>
                        <h3 class="text-xl font-semibold mb-2" style="color: var(--text-primary);">Téléphone</h3>
                        <p style="color: var(--text-secondary);">
                            +213 34 21 45 67<br>
                            <span class="text-sm" style="color: var(--success-color);">
                                <i class="fas fa-circle text-xs mr-2"></i>En ligne
                            </span>
                        </p>
                    </div>
                    <div class="glass-card p-6 transform hover:scale-105 transition-transform">
                        <div class="contact-icon">
                            <i class="fas fa-envelope text-blue-400"></i>
                        </div>
                        <h3 class="text-xl font-semibold mb-2" style="color: var(--text-primary);">Email</h3>
                        <p style="color: var(--text-secondary);">
                            support@justifly.dz<br>
                            
                        </p>
                    </div>

                </div>
                <!-- Contact Form -->
                <div class="lg:col-span-2">
                    <div class="glass-card p-8">
                        <h2 class="text-3xl font-bold mb-6" style="color: var(--text-primary);">Envoyez-nous un message</h2>
                        <form id="contactForm" class="space-y-6">
                            <div class="grid md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium mb-2" style="color: var(--text-secondary);">Nom complet</label>
                                    <input type="text" class="form-input w-full" placeholder="Votre nom" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-2" style="color: var(--text-secondary);">Email</label>
                                    <input type="email" class="form-input w-full" placeholder="votre@email.com" required>
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium mb-2" style="color: var(--text-secondary);">Sujet</label>
                                <select class="form-input w-full" required>
                                    <option value="">Choisissez un sujet</option>
                                    <option value="support">Support Technique</option>
                                    <option value="information">Demande d'Information</option>
                                    <option value="collaboration">Collaboration</option>
                                    <option value="feedback">Feedback</option>
                                    <option value="other">Autre</option>
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium mb-2" style="color: var(--text-secondary);">Message</label>
                                <textarea class="form-input w-full" rows="5" placeholder="Votre message..." required></textarea>
                            </div>
                            
                            <div class="flex items-center">
                                <input type="checkbox" id="newsletter" class="mr-3 w-4 h-4" style="accent-color: var(--accent-color);">
                                <label for="newsletter" class="text-sm" style="color: var(--text-secondary);">
                                    Je souhaite recevoir la newsletter de Justifly
                                </label>
                            </div>
                            
                            <button type="submit" class="submit-btn w-full text-white py-4 px-6 rounded-lg font-medium text-lg">
                                Envoyer le message
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <!-- Map Section -->
            <section class="mb-16">
                <div class="map-container">
                    <div class="map-placeholder">
                        <div class="pulse-dot"></div>
                        <div class="pulse-dot"></div>
                        <div class="pulse-dot"></div>
                        <i class="fas fa-map-marked-alt text-7xl mb-4" style="color: var(--accent-color);"></i>
                        <p class="text-2xl font-semibold" style="color: var(--text-primary);">Université d'Aïn Témouchent</p>
                        <p style="color: var(--text-muted);">Localisation interactive</p>
                        <div class="mt-4 flex space-x-4">
                            <span class="px-4 py-2 rounded-full text-sm" style="background-color: rgba(139, 92, 246, 0.2); color: #a78bfa;">
                                <i class="fas fa-graduation-cap mr-2"></i>Campus Principal
                            </span>
                            <span class="px-4 py-2 rounded-full text-sm" style="background-color: rgba(59, 130, 246, 0.2); color: #60a5fa;">
                                <i class="fas fa-building mr-2"></i>Faculté des Sciences
                            </span>
                        </div>
                    </div>
                </div>
            </section>
            <!-- FAQ Section -->
            <section>
                <div class="glass-card p-8">
                    <h2 class="text-4xl font-bold mb-8 text-center" style="color: var(--text-primary);">Questions Fréquentes</h2>
                    
                    <div class="max-w-4xl mx-auto space-y-4">
                        <div class="faq-item">
                            <div class="faq-question">
                                <span>Comment puis-je m'inscrire à Justifly ?</span>
                                <i class="fas fa-chevron-down transition-transform duration-300"></i>
                            </div>
                            <div class="faq-answer">
                                Pour vous inscrire, contactez votre administrateur universitaire et entrer votre information. Notre équipe vous guidera à travers le processus d'inscription étape par étape. L'inscription est entièrement gratuite pour tous les étudiants de l'université et les enseignants .
                            </div>
                        </div>
                        <div class="faq-item">
                            <div class="faq-question">
                                <span>Justifly est-il gratuit pour les étudiants ?</span>
                                <i class="fas fa-chevron-down transition-transform duration-300"></i>
                            </div>
                            <div class="faq-answer">
                                Oui, Justifly est entièrement gratuit pour les étudiants. La plateforme est financée par l'université pour faciliter la gestion des absences. Aucun coût n'est facturé aux étudiants pour l'utilisation de toutes les fonctionnalités.
                            </div>
                        </div>
                        <div class="faq-item">
                            <div class="faq-question">
                                <span>Comment déclarer une absence rapidement ?</span>
                                <i class="fas fa-chevron-down transition-transform duration-300"></i>
                            </div>
                            <div class="faq-answer">
                                Connectez-vous à votre compte étudiant, cliquez sur "ajouter une justification" dans le tableau de bord, sélectionnez le cours et la date, remplissez le formulaire avec les détails nécessaires, téléchargez vos justificatifs  nécessaire, et soumettez. 
                            </div>
                        </div>
                        <div class="faq-item">
                            <div class="faq-question">
                                <span>Quels types de justificatifs sont acceptés ?</span>
                                <i class="fas fa-chevron-down transition-transform duration-300"></i>
                            </div>
                            <div class="faq-answer">
                                Nous acceptons les certificats médicaux, les convocations officielles, les documents administratifs, les attestations de décès, les ordres de mission, et tout autre justificatif officiel approuvé par l'université. Les documents doivent être clairs, lisibles et datés.
                            </div>
                        </div>
                        <div class="faq-item">
                            <div class="faq-question">
                                <span>Comment contacter le support technique en cas d'urgence ?</span>
                                <i class="fas fa-chevron-down transition-transform duration-300"></i>
                            </div>
                            <div class="faq-answer">
                                Pour les urgences, appelez notre ligne directe au +213 34 21 45 67 disponible de dimanche a jeudi. Pour les demandes non urgentes, utilisez notre formulaire de contact, envoyez un email à support@justifly.dz.
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </main>
    
    <!-- Footer -->
    <footer class="py-8 border-t mt-12" style="background-color: var(--bg-secondary); border-color: var(--border-color); color: var(--text-secondary);">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="flex items-center mb-4 md:mb-0">
                    <div class="w-8 h-8 gradient-bg rounded-lg flex items-center justify-center mr-2">
                        <span class="text-white font-bold">J</span>
                    </div>
                    <span class="text-lg font-bold" style="color: var(--text-primary);">Justifly</span>
                </div>
                
                <div class="text-center md:text-left">
                    <p>&copy; 2025 Justifly. Tous droits réservés.</p>
                </div>
                
                <div class="flex space-x-4 mt-4 md:mt-0">
                    <a href="#" class="transition" style="color: var(--text-secondary);"><i class="fab fa-linkedin"></i></a>
                    <a href="#" class="transition" style="color: var(--text-secondary);"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="transition" style="color: var(--text-secondary);"><i class="fab fa-facebook"></i></a>
                    <a href="#" class="transition" style="color: var(--text-secondary);"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
        </div>
    </footer>
    
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
        
        // Create floating particles
        function createParticles() {
            const particlesContainer = document.getElementById('particles');
            const particleCount = 30;
            
            for (let i = 0; i < particleCount; i++) {
                const particle = document.createElement('div');
                particle.className = 'particle';
                particle.style.left = Math.random() * 100 + '%';
                particle.style.top = Math.random() * 100 + '%';
                particle.style.animationDelay = Math.random() * 5 + 's';
                particle.style.animationDuration = (Math.random() * 10 + 10) + 's';
                particlesContainer.appendChild(particle);
            }
        }
        
        // Animate particles
        function animateParticles() {
            const particles = document.querySelectorAll('.particle');
            particles.forEach(particle => {
                const duration = Math.random() * 10 + 10;
                const delay = Math.random() * 5;
                
                particle.style.animation = `floatParticle ${duration}s ${delay}s infinite ease-in-out`;
            });
        }
        
        // Add particle animation keyframes
        const style = document.createElement('style');
        style.textContent = `
            @keyframes floatParticle {
                0%, 100% {
                    transform: translateY(0) translateX(0);
                    opacity: 0;
                }
                10% {
                    opacity: 1;
                }
                90% {
                    opacity: 1;
                }
                100% {
                    transform: translateY(-100vh) translateX(50px);
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(style);
        
        // FAQ Toggle
        document.querySelectorAll('.faq-question').forEach(question => {
            question.addEventListener('click', () => {
                const faqItem = question.parentElement;
                const wasActive = faqItem.classList.contains('active');
                
                // Close all FAQ items
                document.querySelectorAll('.faq-item').forEach(item => {
                    item.classList.remove('active');
                });
                
                // Open clicked item if it wasn't active
                if (!wasActive) {
                    faqItem.classList.add('active');
                }
            });
        });
        
        // Form submission with enhanced feedback
        document.getElementById('contactForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const button = this.querySelector('button[type="submit"]');
            const originalText = button.innerHTML;
            
            // Show loading state
            button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Envoi en cours...';
            button.disabled = true;
            
            // Simulate form submission
            setTimeout(() => {
                // Show success state
                button.innerHTML = '<i class="fas fa-check mr-2"></i>Message envoyé !';
                button.style.background = 'linear-gradient(135deg, #10b981, #059669)';
                
                // Reset form
                this.reset();
                
                // Reset button after 3 seconds
                setTimeout(() => {
                    button.innerHTML = originalText;
                    button.style.background = '';
                    button.disabled = false;
                }, 3000);
            }, 1500);
        });
        
        // Add input focus effects
        document.querySelectorAll('.form-input').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.style.transform = 'scale(1.02)';
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.style.transform = 'scale(1)';
            });
        });
        
        // Smooth scrolling for navigation links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
        
        // Initialize particles on load
        window.addEventListener('load', () => {
            createParticles();
            animateParticles();
        });
        
        // Add hover effect to contact cards
        document.querySelectorAll('.glass-card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-5px) scale(1.02)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0) scale(1)';
            });
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