<!DOCTYPE html>
<html lang="fr" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>À propos - Justifly</title>
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
        
        .team-card {
            background: var(--bg-card);
            backdrop-filter: blur(10px);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 24px;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
            overflow: hidden;
        }
        
        .team-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(102, 126, 234, 0.1), transparent);
            transition: left 0.6s ease;
        }
        
        .team-card:hover::before {
            left: 100%;
        }
        
        .team-card:hover {
            transform: translateY(-8px) rotateX(5deg);
            box-shadow: 0 20px 40px rgba(102, 126, 234, 0.3);
        }
        
        .skill-bar {
            height: 6px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 3px;
            overflow: hidden;
            position: relative;
        }
        
        .skill-progress {
            height: 100%;
            background: var(--gradient-primary);
            border-radius: 3px;
            width: 0;
            transition: width 1.5s ease-out;
        }
        
        .gradient-text {
            background: var(--gradient-secondary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .icon-box {
            width: 60px;
            height: 60px;
            background: rgba(102, 126, 234, 0.1);
            border: 1px solid rgba(102, 126, 234, 0.3);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            transition: all 0.3s ease;
        }
        
        .icon-box:hover {
            background: rgba(102, 126, 234, 0.2);
            transform: rotateY(180deg);
        }
        
        .timeline-item {
            position: relative;
            padding-left: 40px;
            padding-bottom: 30px;
        }
        
        .timeline-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            width: 20px;
            height: 20px;
            background: var(--gradient-primary);
            border-radius: 50%;
            box-shadow: 0 0 20px rgba(102, 126, 234, 0.5);
        }
        
        .timeline-item::after {
            content: '';
            position: absolute;
            left: 9px;
            top: 20px;
            width: 2px;
            height: calc(100% + 10px);
            background: rgba(102, 126, 234, 0.3);
        }
        
        .timeline-item:last-child::after {
            display: none;
        }
        
        .floating-dots {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: -1;
        }
        
        .dot {
            position: absolute;
            width: 4px;
            height: 4px;
            background: rgba(147, 51, 234, 0.7);
            border-radius: 50%;
            box-shadow: 0 0 10px rgba(147, 51, 234, 0.5);
            animation: floatDot 15s infinite ease-in-out;
        }
        
        .dot-1 {
            top: 15%;
            left: 10%;
            animation-delay: 0s;
        }
        
        .dot-2 {
            top: 25%;
            right: 15%;
            animation-delay: 2s;
        }
        
        .dot-3 {
            bottom: 30%;
            left: 8%;
            animation-delay: 4s;
        }
        
        .dot-4 {
            bottom: 15%;
            right: 12%;
            animation-delay: 1s;
        }
        
        .dot-5 {
            top: 40%;
            left: 20%;
            animation-delay: 3s;
        }
        
        .dot-6 {
            top: 60%;
            right: 25%;
            animation-delay: 5s;
        }
        
        .dot-7 {
            bottom: 40%;
            left: 15%;
            animation-delay: 2.5s;
        }
        
        .dot-8 {
            top: 70%;
            right: 5%;
            animation-delay: 4.5s;
        }
        
        @keyframes floatDot {
            0%, 100% { 
                transform: translateY(0) translateX(0);
                opacity: 0.7;
            }
            25% { 
                transform: translateY(-20px) translateX(10px);
                opacity: 1;
            }
            50% { 
                transform: translateY(10px) translateX(-10px);
                opacity: 0.5;
            }
            75% { 
                transform: translateY(-10px) translateX(15px);
                opacity: 0.8;
            }
        }
        
        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
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
    <!-- Floating dots for visual interest -->
    <div class="floating-dots">
        <div class="dot dot-1"></div>
        <div class="dot dot-2"></div>
        <div class="dot dot-3"></div>
        <div class="dot dot-4"></div>
        <div class="dot dot-5"></div>
        <div class="dot dot-6"></div>
        <div class="dot dot-7"></div>
        <div class="dot dot-8"></div>
    </div>
    
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
                <h1 class="text-5xl font-bold mb-4" style="color: var(--text-primary);">À propos de nous</h1>
                <p class="text-xl max-w-2xl mx-auto" style="color: var(--text-secondary);">
                    Découvrez l'équipe derrière la révolution de la gestion des absences universitaires
                </p>
                <div class="w-32 h-1 gradient-bg mx-auto rounded mt-6"></div>
            </div>
            
            <!-- Project Timeline -->
            <section class="mb-16">
                <div class="glass-card p-8">
                    <div class="flex items-center mb-8">
                        <div class="icon-box">
                            <i class="fas fa-rocket text-2xl text-purple-400"></i>
                        </div>
                        <h2 class="text-3xl font-bold ml-4" style="color: var(--text-primary);">Notre Parcours</h2>
                    </div>
                    
                    <div class="grid md:grid-cols-2 gap-8">
                        <div class="timeline-item">
                            <h3 class="text-xl font-semibold mb-2" style="color: var(--text-primary);">Idéation</h3>
                            <p style="color: var(--text-secondary);">Identification du problème de gestion des absences et conception de la solution</p>
                        </div>
                        <div class="timeline-item">
                            <h3 class="text-xl font-semibold mb-2" style="color: var(--text-primary);">Développement</h3>
                            <p style="color: var(--text-secondary);">Création de l'application avec les technologies modernes</p>
                        </div>
                        <div class="timeline-item">
                            <h3 class="text-xl font-semibold mb-2" style="color: var(--text-primary);">Tests</h3>
                            <p style="color: var(--text-secondary);">Validation des fonctionnalités et optimisation des performances</p>
                        </div>
                        <div class="timeline-item">
                            <h3 class="text-xl font-semibold mb-2" style="color: var(--text-primary);">Lancement</h3>
                            <p style="color: var(--text-secondary);">Déploiement de la plateforme et formation des utilisateurs</p>
                        </div>
                    </div>
                </div>
            </section>
            
            <!-- Project Description -->
            <section class="mb-16">
                <div class="grid md:grid-cols-2 gap-8">
                    <div class="glass-card p-8">
                        <div class="flex items-center mb-6">
                            <div class="icon-box">
                                <i class="fas fa-lightbulb text-2xl text-yellow-400"></i>
                            </div>
                            <h2 class="text-2xl font-bold ml-4" style="color: var(--text-primary);">Le Concept</h2>
                        </div>
                        <p class="leading-relaxed" style="color: var(--text-secondary);">
                            Ce projet est un projet de fin d'études pour les étudiants en informatique de l'Université d'Aïn Témouchent. 
                            Ce projet a pour but de faciliter la gestion des absences des étudiants en utilisant une plateforme numérique.
                        </p>
                    </div>
                    
                    <div class="glass-card p-8">
                        <div class="flex items-center mb-6">
                            <div class="icon-box">
                                <i class="fas fa-target text-2xl text-green-400"></i>
                            </div>
                            <h2 class="text-2xl font-bold ml-4" style="color: var(--text-primary);">L'Objectif</h2>
                        </div>
                        <p class="leading-relaxed" style="color: var(--text-secondary);">
                            Nous avons développé cette application pour rendre le processus d'absence plus efficace et transparent, 
                            bénéficiant à la fois aux étudiants, aux enseignants et à l'administration.
                        </p>
                    </div>
                </div>
            </section>
            
            <!-- Professor Section -->
            <section class="mb-16">
                <div class="glass-card p-8">
                    <div class="flex flex-col md:flex-row items-center gap-8">
                        <div class="flex-shrink-0 text-center">
                            <div class="relative inline-block">
                                <img src="https://picsum.photos/seed/professor/200/200.jpg" alt="Professeur Merad Djalel Boudia" 
                                     class="w-40 h-40 rounded-full border-4 border-purple-500">
                                <div class="absolute -bottom-2 -right-2 w-12 h-12 gradient-bg rounded-full flex items-center justify-center">
                                    <i class="fas fa-graduation-cap text-white"></i>
                                </div>
                            </div>
                        </div>
                        <div class="text-center md:text-left">
                            <h3 class="text-2xl font-bold mb-2" style="color: var(--text-primary);">Merad Djalel Boudia</h3>
                            <p class="text-purple-400 mb-4">Professeur Superviseur</p>
                            <p class="leading-relaxed mb-4" style="color: var(--text-secondary);">
                                Le professeur superviseur a guidé et soutenu l'équipe tout au long du projet. 
                                Son expertise et ses conseils ont été essentiels pour la réussite de ce projet.
                            </p>
                            <div class="flex justify-center md:justify-start space-x-4">
                                <span class="px-3 py-1 bg-purple-500 bg-opacity-20 text-purple-400 rounded-full text-sm">
                                    <i class="fas fa-award mr-2"></i>Expert
                                </span>
                                <span class="px-3 py-1 bg-blue-500 bg-opacity-20 text-blue-400 rounded-full text-sm">
                                    <i class="fas fa-chalkboard-teacher mr-2"></i>Mentor
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            
            <!-- Team Section -->
            <section class="mb-16">
                <div class="glass-card p-8">
                    <div class="flex items-center mb-8">
                        <div class="icon-box">
                            <i class="fas fa-users text-2xl text-blue-400"></i>
                        </div>
                        <h2 class="text-3xl font-bold ml-4" style="color: var(--text-primary);">Notre Équipe</h2>
                    </div>
                    
                    <div class="grid md:grid-cols-3 gap-6">
                        <!-- Benyoub Imane -->
                        <div class="team-card">
                            <div class="text-center">
                                <div class="relative inline-block mb-4">
                                    <img src="https://picsum.photos/seed/imane/150/150.jpg" alt="Benyoub Imane" 
                                         class="w-32 h-32 rounded-full border-4 border-blue-500">
                                    <div class="absolute -bottom-2 -right-2 w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center">
                                        <i class="fas fa-code text-white text-sm"></i>
                                    </div>
                                </div>
                                <h3 class="text-xl font-semibold mb-2" style="color: var(--text-primary);">Benyoub Imane</h3>
                                <p class="text-blue-400 mb-4">Front End Developer</p>
                                
                                <div class="space-y-3 mb-4">
                                    <div>
                                        <div class="flex justify-between text-sm mb-1" style="color: var(--text-muted);">css</div>
                                        <div class="skill-bar">
                                            <div class="skill-progress" data-width="90"></div>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="flex justify-between text-sm mb-1" style="color: var(--text-muted);">UI/UX</div>
                                        <div class="skill-bar">
                                            <div class="skill-progress" data-width="85"></div>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="flex justify-between text-sm mb-1" style="color: var(--text-muted);">JavaScript</div>
                                        <div class="skill-bar">
                                            <div class="skill-progress" data-width="88"></div>
                                        </div>
                                    </div>
                                </div>
                                
                                <p class="text-sm leading-relaxed" style="color: var(--text-secondary);">
                                    Passionnée par la création d'interfaces intuitives et esthétiques.
                                </p>
                            </div>
                        </div>
                        
                        <!-- Bouezem Asia -->
                        <div class="team-card">
                            <div class="text-center">
                                <div class="relative inline-block mb-4">
                                    <img src="https://picsum.photos/seed/asia/150/150.jpg" alt="Bouezem Asia" 
                                         class="w-32 h-32 rounded-full border-4 border-purple-500">
                                    <div class="absolute -bottom-2 -right-2 w-10 h-10 bg-purple-500 rounded-full flex items-center justify-center">
                                        <i class="fas fa-server text-white text-sm"></i>
                                    </div>
                                </div>
                                <h3 class="text-xl font-semibold mb-2" style="color: var(--text-primary);">Bouezem Asia</h3>
                                <p class="text-purple-400 mb-4">Back End Developer</p>
                                
                                <div class="space-y-3 mb-4">
                                    <div>
                                        <div class="flex justify-between text-sm mb-1" style="color: var(--text-muted);">xampp</div>
                                        <div class="skill-bar">
                                            <div class="skill-progress" data-width="92"></div>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="flex justify-between text-sm mb-1" style="color: var(--text-muted);">Database</div>
                                        <div class="skill-bar">
                                            <div class="skill-progress" data-width="87"></div>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="flex justify-between text-sm mb-1" style="color: var(--text-muted);">API</div>
                                        <div class="skill-bar">
                                            <div class="skill-progress" data-width="90"></div>
                                        </div>
                                    </div>
                                </div>
                                
                                <p class="text-sm leading-relaxed" style="color: var(--text-secondary);">
                                    Spécialisée dans la gestion efficace des données et des systèmes.
                                </p>
                            </div>
                        </div>
                        
                        <!-- Fkih Naïma -->
                        <div class="team-card">
                            <div class="text-center">
                                <div class="relative inline-block mb-4">
                                    <img src="https://picsum.photos/seed/naima/150/150.jpg" alt="Fkih Naïma" 
                                         class="w-32 h-32 rounded-full border-4 border-green-500">
                                    <div class="absolute -bottom-2 -right-2 w-10 h-10 bg-green-500 rounded-full flex items-center justify-center">
                                        <i class="fas fa-cogs text-white text-sm"></i>
                                    </div>
                                </div>
                                <h3 class="text-xl font-semibold mb-2" style="color: var(--text-primary);">Fkih Naïma</h3>
                                <p class="text-green-400 mb-4">Back End Developer</p>
                                
                                <div class="space-y-3 mb-4">
                                    <div>
                                        <div class="flex justify-between text-sm mb-1" style="color: var(--text-muted);">php</div>
                                        <div class="skill-bar">
                                            <div class="skill-progress" data-width="89"></div>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="flex justify-between text-sm mb-1" style="color: var(--text-muted);">Security</div>
                                        <div class="skill-bar">
                                            <div class="skill-progress" data-width="85"></div>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="flex justify-between text-sm mb-1" style="color: var(--text-muted);">sql</div>
                                        <div class="skill-bar">
                                            <div class="skill-progress" data-width="82"></div>
                                        </div>
                                    </div>
                                </div>
                                
                                <p class="text-sm leading-relaxed" style="color: var(--text-secondary);">
                                    Expérimentée dans le développement d'applications web robustes.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            
            <!-- University Section -->
            <section>
                <div class="glass-card p-8">
                    <div class="flex flex-col md:flex-row items-center gap-8">
                        <div class="flex-shrink-0">
                            <div class="w-32 h-32 gradient-bg rounded-2xl flex items-center justify-center">
                                <i class="fas fa-university text-5xl text-white"></i>
                            </div>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold mb-4" style="color: var(--text-primary);">Université d'Aïn Témouchent</h2>
                            <p class="leading-relaxed mb-4" style="color: var(--text-secondary);">
                                Ce projet de fin d'études a été réalisé dans le cadre du programme d'informatique de l'Université d'Aïn Témouchent. 
                                Nous remercions l'université pour son soutien et les ressources mises à notre disposition.
                            </p>
                            <div class="flex flex-wrap gap-3">
                                <span class="px-3 py-1 bg-blue-500 bg-opacity-20 text-blue-400 rounded-full text-sm">
                                    <i class="fas fa-graduation-cap mr-2"></i>Informatique
                                </span>
                                <span class="px-3 py-1 bg-purple-500 bg-opacity-20 text-purple-400 rounded-full text-sm">
                                    <i class="fas fa-award mr-2"></i>Excellence
                                </span>
                                <span class="px-3 py-1 bg-green-500 bg-opacity-20 text-green-400 rounded-full text-sm">
                                    <i class="fas fa-star mr-2"></i>Innovation
                                </span>
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
        
        // Animate skill bars on scroll
        const observerOptions = {
            threshold: 0.5,
            rootMargin: '0px'
        };
        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const skillBars = entry.target.querySelectorAll('.skill-progress');
                    skillBars.forEach(bar => {
                        const width = bar.getAttribute('data-width');
                        setTimeout(() => {
                            bar.style.width = width + '%';
                        }, 200);
                    });
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);
        
        // Observe team cards for skill animation
        document.querySelectorAll('.team-card').forEach(card => {
            observer.observe(card);
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