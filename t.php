<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Justifly - Gestion des Absences Universitaires</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; overflow-x: hidden; }
        .gradient-bg { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .card-shadow { box-shadow: 0 10px 25px rgba(0,0,0,0.1); }
        .brand-blue { color: #667eea; }
        .brand-purple { color: #764ba2; }
        .hover-lift { transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); }
        .hover-lift:hover { transform: translateY(-8px) scale(1.02); box-shadow: 0 20px 40px rgba(0,0,0,0.15); }
        .fade-in { opacity: 0; animation: fadeIn 0.8s ease forwards; }
        @keyframes fadeIn { to { opacity: 1; } }
        .rtl { direction: rtl; text-align: right; }
        
        /* Minimalist Hero Styles */
        .minimalist-hero {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 50%, #cbd5e1 100%);
            position: relative;
            overflow: hidden;
            min-height: 100vh;
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
            background: linear-gradient(45deg, rgba(102, 126, 234, 0.1), rgba(118, 75, 162, 0.1));
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .shape-1 {
            width: 200px;
            height: 200px;
            top: 10%;
            left: 5%;
            animation: floatSlow 20s ease-in-out infinite;
        }
        
        .shape-2 {
            width: 150px;
            height: 150px;
            top: 60%;
            right: 10%;
            animation: floatMedium 15s ease-in-out infinite reverse;
        }
        
        .shape-3 {
            width: 100px;
            height: 100px;
            top: 30%;
            right: 30%;
            animation: floatFast 12s ease-in-out infinite;
        }
        
        .shape-4 {
            width: 80px;
            height: 80px;
            bottom: 20%;
            left: 20%;
            animation: floatSlow 18s ease-in-out infinite reverse;
        }
        
        @keyframes floatSlow {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-30px) rotate(180deg); }
        }
        
        @keyframes floatMedium {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(90deg); }
        }
        
        @keyframes floatFast {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-40px) rotate(270deg); }
        }
        
        .glass-card {
            background: rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 24px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.1);
        }
        
        .hero-content {
            position: relative;
            z-index: 10;
            animation: slideUp 1s ease-out;
        }
        
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .gradient-text {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: gradientShift 3s ease-in-out infinite;
        }
        
        @keyframes gradientShift {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }
        
        .modern-button {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(102, 126, 234, 0.4);
        }
        
        .glass-button {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 16px;
            color: #1f2937;
            font-weight: 600;
            padding: 16px 32px;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .glass-button:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        }
        
        .feature-badge {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 50px;
            padding: 8px 20px;
            font-size: 14px;
            font-weight: 500;
            color: #4b5563;
            display: inline-flex;
            align-items: center;
            margin: 4px;
            transition: all 0.3s ease;
            animation: fadeInUp 0.6s ease-out;
        }
        
        .feature-badge:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .hero-visual {
            position: relative;
            z-index: 10;
            animation: slideInRight 1s ease-out 0.3s both;
        }
        
        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        .dashboard-preview {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 24px;
            padding: 32px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
            transform: perspective(1000px) rotateY(-5deg) rotateX(5deg);
            transition: all 0.4s ease;
        }
        
        .dashboard-preview:hover {
            transform: perspective(1000px) rotateY(0deg) rotateX(0deg) translateY(-10px);
            box-shadow: 0 35px 70px rgba(0, 0, 0, 0.2);
        }
        
        .metric-card {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            border-radius: 16px;
            padding: 20px;
            text-align: center;
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.5);
        }
        
        .metric-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
        }
        
        .progress-ring {
            width: 60px;
            height: 60px;
            margin: 0 auto 12px;
            position: relative;
        }
        
        .progress-ring svg {
            width: 100%;
            height: 100%;
            transform: rotate(-90deg);
        }
        
        .progress-ring circle {
            fill: none;
            stroke-width: 4;
            stroke-linecap: round;
        }
        
        .progress-ring .bg {
            stroke: #e5e7eb;
        }
        
        .progress-ring .progress {
            stroke: #667eea;
            stroke-dasharray: 157;
            stroke-dashoffset: 157;
            animation: progressAnimation 2s ease-out forwards;
        }
        
        @keyframes progressAnimation {
            to {
                stroke-dashoffset: 39.25; /* 75% of 157 */
            }
        }
        
        .floating-elements {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 5;
        }
        
        .floating-icon {
            position: absolute;
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            animation: floatIcon 6s ease-in-out infinite;
        }
        
        .icon-1 {
            top: 15%;
            left: 15%;
            animation-delay: 0s;
        }
        
        .icon-2 {
            top: 25%;
            right: 20%;
            animation-delay: 2s;
        }
        
        .icon-3 {
            bottom: 30%;
            left: 10%;
            animation-delay: 4s;
        }
        
        .icon-4 {
            bottom: 15%;
            right: 15%;
            animation-delay: 1s;
        }
        
        @keyframes floatIcon {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-15px) rotate(5deg); }
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
            margin-top: 24px;
        }
        
        .stat-item {
            text-align: center;
            padding: 16px;
            background: rgba(255, 255, 255, 0.5);
            border-radius: 12px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            transition: all 0.3s ease;
        }
        
        .stat-item:hover {
            transform: translateY(-3px);
            background: rgba(255, 255, 255, 0.7);
        }
        
        .pulse-dot {
            width: 12px;
            height: 12px;
            background: #10b981;
            border-radius: 50%;
            display: inline-block;
            margin-right: 8px;
            animation: pulse 2s infinite;
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
        
        /* Interactive Elements */
        .interactive-card {
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            cursor: pointer;
        }
        
        .interactive-card:hover {
            transform: translateY(-10px) rotateY(5deg);
            box-shadow: 0 25px 50px rgba(0,0,0,0.2);
        }
        
        .interactive-card:hover .card-icon {
            transform: scale(1.2) rotate(360deg);
        }
        
        .card-icon {
            transition: all 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        
        /* Navigation Animation */
        .nav-item {
            position: relative;
            transition: all 0.3s ease;
        }
        
        .nav-item::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 50%;
            width: 0;
            height: 2px;
            background: linear-gradient(90deg, #667eea, #764ba2);
            transition: all 0.3s ease;
            transform: translateX(-50%);
        }
        
        .nav-item:hover::after {
            width: 100%;
        }
        
        /* Scroll Animations */
        .slide-in-left {
            opacity: 0;
            transform: translateX(-50px);
            transition: all 0.8s ease;
        }
        
        .slide-in-right {
            opacity: 0;
            transform: translateX(50px);
            transition: all 0.8s ease;
        }
        
        .slide-in-up {
            opacity: 0;
            transform: translateY(50px);
            transition: all 0.8s ease;
        }
        
        .animate-in {
            opacity: 1;
            transform: translate(0);
        }
        
        /* Dashboard Animations */
        .dashboard-stat {
            transition: all 0.3s ease;
        }
        
        .dashboard-stat:hover {
            transform: scale(1.1);
            background: rgba(255,255,255,0.2);
            border-radius: 10px;
        }
        
        .progress-bar {
            width: 0;
            height: 4px;
            background: linear-gradient(90deg, #10b981, #3b82f6);
            border-radius: 2px;
            animation: fillProgress 2s ease-out forwards;
        }
        
        @keyframes fillProgress {
            to { width: 95%; }
        }
        
        /* Button Hover Effects */
        .btn-gradient {
            background: linear-gradient(45deg, #667eea, #764ba2);
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
        }
        
        .btn-gradient::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }
        
        .btn-gradient:hover::before {
            left: 100%;
        }
        
        /* Loading Animation */
        .loading-dots {
            display: inline-block;
        }
        
        .loading-dots::after {
            content: '';
            animation: dots 1.5s steps(4, end) infinite;
        }
        
        @keyframes dots {
            0%, 20% { content: ''; }
            40% { content: '.'; }
            60% { content: '..'; }
            80%, 100% { content: '...'; }
        }
        
        /* Particle Effect */
        .particles {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
        }
        
        .particle {
            position: absolute;
            width: 4px;
            height: 4px;
            background: rgba(102, 126, 234, 0.3);
            border-radius: 50%;
            animation: particleFloat 8s linear infinite;
        }
        
        @keyframes particleFloat {
            0% {
                opacity: 0;
                transform: translateY(100vh) scale(0);
            }
            10% {
                opacity: 1;
            }
            90% {
                opacity: 1;
            }
            100% {
                opacity: 0;
                transform: translateY(-100px) scale(1);
            }
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0 flex items-center">
                        <div class="w-10 h-10 gradient-bg rounded-lg flex items-center justify-center mr-3">
                            <span class="text-white font-bold text-xl">J</span>
                        </div>
                        <span class="text-2xl font-bold brand-blue">Justifly</span>
                    </div>
                </div>
                
                <div class="hidden md:block">
                    <div class="ml-10 flex items-baseline space-x-8">
                        <a href="#accueil" class="nav-link nav-item text-gray-900 hover:text-blue-600 px-3 py-2 font-medium">Accueil</a>
                        <a href="#fonctionnalites" class="nav-link nav-item text-gray-600 hover:text-blue-600 px-3 py-2 font-medium">Fonctionnalités</a>
                        <a href="#contact" class="nav-link nav-item text-gray-600 hover:text-blue-600 px-3 py-2 font-medium">Contact</a>
                    </div>
                </div>
                
                <div class="flex items-center space-x-4">
                    <select id="languageSelect" class="bg-gray-100 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="fr">🇫🇷 Français</option>
                        <option value="en">🇬🇧 English</option>
                        <option value="ar">🇸🇦 العربية</option>
                    </select>
                    <button class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition duration-300">
                        <span class="login-text">Connexion</span>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="accueil" class="minimalist-hero flex items-center relative">
        <!-- Floating Shapes -->
        <div class="floating-shapes">
            <div class="shape shape-1"></div>
            <div class="shape shape-2"></div>
            <div class="shape shape-3"></div>
            <div class="shape shape-4"></div>
        </div>
        
        <!-- Floating Icons -->
        <div class="floating-elements">
            <div class="floating-icon icon-1">📊</div>
            <div class="floating-icon icon-2">✅</div>
            <div class="floating-icon icon-3">📋</div>
            <div class="floating-icon icon-4">🎯</div>
        </div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid lg:grid-cols-2 gap-16 items-center">
                <!-- Left Content -->
                <div class="hero-content">
                    <!-- Status Badge -->
                    <div class="inline-flex items-center glass-card px-6 py-3 mb-8">
                        <div class="pulse-dot"></div>
                        <span class="text-sm font-medium text-gray-700">Plateforme nouvelle génération</span>
                    </div>
                    
                    <!-- Main Title -->
                    <h1 class="text-6xl lg:text-7xl font-black mb-6 leading-tight">
                        <span class="block text-gray-900">Simplifiez</span>
                        <span class="block gradient-text">vos absences</span>
                        <span class="block text-gray-700">universitaires</span>
                    </h1>
                    
                    <!-- Subtitle -->
                    <p class="text-xl lg:text-2xl text-gray-600 mb-8 leading-relaxed max-w-lg hero-subtitle">
                        Une solution moderne et intuitive pour gérer efficacement toutes vos absences académiques
                    </p>
                    
                    <!-- Feature Badges -->
                    <div class="flex flex-wrap mb-10">
                        <div class="feature-badge">
                            <svg class="w-4 h-4 mr-2 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            Validation instantanée
                        </div>
                        <div class="feature-badge">
                            <svg class="w-4 h-4 mr-2 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            Interface moderne
                        </div>
                        <div class="feature-badge">
                            <svg class="w-4 h-4 mr-2 text-purple-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd"></path>
                            </svg>
                            Temps réel
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row gap-4">
                        <button class="modern-button cta-primary group">
                            <span class="relative z-10 flex items-center justify-center">
                                Commencer gratuitement
                                <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                </svg>
                            </span>
                        </button>
                        <button class="glass-button cta-secondary group">
                            <span class="flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1.586a1 1 0 01.707.293l2.414 2.414a1 1 0 00.707.293H15M9 10V9a2 2 0 012-2h2a2 2 0 012 2v1M9 10v5a2 2 0 002 2h2a2 2 0 002-2v-5"></path>
                                </svg>
                                Découvrir la démo
                            </span>
                        </button>
                    </div>
                </div>
                
                <!-- Right Visual -->
                <div class="hero-visual">
                    <div class="dashboard-preview">
                        <!-- Dashboard Header -->
                        <div class="flex items-center justify-between mb-6">
                            <div class="flex items-center">
                                <div class="w-12 h-12 gradient-bg rounded-full flex items-center justify-center mr-4">
                                    <span class="text-white font-bold text-lg">M</span>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-900 dashboard-welcome">Marie Dubois</h3>
                                    <p class="text-sm text-gray-500">Étudiante en L3 Informatique</p>
                                </div>
                            </div>
                            <div class="flex items-center text-sm text-gray-500">
                                <div class="pulse-dot mr-2"></div>
                                En ligne
                            </div>
                        </div>
                        
                        <!-- Progress Ring -->
                        <div class="text-center mb-6">
                            <div class="progress-ring">
                                <svg>
                                    <circle class="bg" cx="30" cy="30" r="25"></circle>
                                    <circle class="progress" cx="30" cy="30" r="25"></circle>
                                </svg>
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <span class="text-lg font-bold text-gray-900">95%</span>
                                </div>
                            </div>
                            <p class="text-sm text-gray-600 stat-3">Taux de présence</p>
                        </div>
                        
                        <!-- Stats Grid -->
                        <div class="stats-grid">
                            <div class="stat-item">
                                <div class="text-2xl font-bold text-blue-600">5</div>
                                <div class="text-xs text-gray-500 stat-1">Ce mois</div>
                            </div>
                            <div class="stat-item">
                                <div class="text-2xl font-bold text-orange-500">2</div>
                                <div class="text-xs text-gray-500 stat-2">En attente</div>
                            </div>
                            <div class="stat-item">
                                <div class="text-2xl font-bold text-green-500">18</div>
                                <div class="text-xs text-gray-500">Approuvées</div>
                            </div>
                        </div>
                        
                        <!-- Recent Activity -->
                        <div class="mt-6">
                            <h4 class="font-medium text-gray-900 mb-3 recent-title">Activité récente</h4>
                            <div class="space-y-2">
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <div class="flex items-center">
                                        <div class="w-2 h-2 bg-green-500 rounded-full mr-3"></div>
                                        <span class="text-sm text-gray-700">Mathématiques</span>
                                    </div>
                                    <span class="text-xs text-green-600 font-medium status-approved">Approuvée</span>
                                </div>
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <div class="flex items-center">
                                        <div class="w-2 h-2 bg-yellow-500 rounded-full mr-3"></div>
                                        <span class="text-sm text-gray-700">Physique</span>
                                    </div>
                                    <span class="text-xs text-yellow-600 font-medium status-pending">En attente</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Particles -->
        <div id="particles" class="particles"></div>
    </section>

    <!-- Features Section -->
    <section id="fonctionnalites" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4 features-title">Fonctionnalités principales</h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto features-subtitle">
                    Découvrez comment Justifly révolutionne la gestion des absences dans votre université
                </p>
            </div>
            
            <div class="grid md:grid-cols-3 gap-8">
                <div class="text-center p-8 rounded-xl card-shadow interactive-card bg-white slide-in-left">
                    <div class="w-16 h-16 gradient-bg rounded-full flex items-center justify-center mx-auto mb-6 card-icon">
                        <span class="text-white text-2xl">📋</span>
                    </div>
                    <h3 class="text-xl font-semibold mb-4 text-gray-900 feature-1-title">Déclaration simplifiée</h3>
                    <p class="text-gray-600 feature-1-desc mb-6">Déclarez vos absences en quelques clics avec notre interface intuitive et rapide.</p>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="text-sm text-gray-500 mb-2">Temps moyen</div>
                        <div class="text-2xl font-bold text-blue-600">30 sec</div>
                    </div>
                </div>
                
                <div class="text-center p-8 rounded-xl card-shadow interactive-card bg-white slide-in-up">
                    <div class="w-16 h-16 gradient-bg rounded-full flex items-center justify-center mx-auto mb-6 card-icon">
                        <span class="text-white text-2xl">📊</span>
                    </div>
                    <h3 class="text-xl font-semibold mb-4 text-gray-900 feature-2-title">Suivi en temps réel</h3>
                    <p class="text-gray-600 feature-2-desc mb-6">Consultez le statut de vos demandes et suivez vos absences en temps réel.</p>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="text-sm text-gray-500 mb-2">Mises à jour</div>
                        <div class="text-2xl font-bold text-green-600">Instantanées</div>
                    </div>
                </div>
                
                <div class="text-center p-8 rounded-xl card-shadow interactive-card bg-white slide-in-right">
                    <div class="w-16 h-16 gradient-bg rounded-full flex items-center justify-center mx-auto mb-6 card-icon">
                        <span class="text-white text-2xl">✅</span>
                    </div>
                    <h3 class="text-xl font-semibold mb-4 text-gray-900 feature-3-title">Validation automatique</h3>
                    <p class="text-gray-600 feature-3-desc mb-6">Système intelligent de validation avec notifications automatiques.</p>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="text-sm text-gray-500 mb-2">Taux d'approbation</div>
                        <div class="text-2xl font-bold text-purple-600">95%</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Dashboard Preview -->
    <section class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4 dashboard-title">Interface moderne et intuitive</h2>
                <p class="text-xl text-gray-600 dashboard-subtitle">Découvrez notre tableau de bord conçu pour les étudiants</p>
            </div>
            
            <div class="bg-white rounded-2xl card-shadow p-8">
                <div class="bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg p-6 text-white mb-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-2xl font-bold dashboard-welcome">Bienvenue, Marie Dubois</h3>
                        <span class="bg-white bg-opacity-20 px-3 py-1 rounded-full text-sm">Étudiante L3</span>
                    </div>
                    <div class="grid grid-cols-3 gap-4">
                        <div class="text-center">
                            <div class="text-3xl font-bold">5</div>
                            <div class="text-sm opacity-90 stat-1">Absences ce mois</div>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-bold">2</div>
                            <div class="text-sm opacity-90 stat-2">En attente</div>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-bold">95%</div>
                            <div class="text-sm opacity-90 stat-3">Taux de présence</div>
                        </div>
                    </div>
                </div>
                
                <div class="grid md:grid-cols-2 gap-6">
                    <div class="border border-gray-200 rounded-lg p-6 slide-in-left interactive-card">
                        <h4 class="font-semibold text-gray-900 mb-4 recent-title">Absences récentes</h4>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-all duration-300 cursor-pointer hover:scale-105">
                                <div>
                                    <div class="font-medium">Mathématiques</div>
                                    <div class="text-sm text-gray-600">15 Nov 2024</div>
                                </div>
                                <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-medium status-approved animate-pulse">Approuvée</span>
                            </div>
                            <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-all duration-300 cursor-pointer hover:scale-105">
                                <div>
                                    <div class="font-medium">Physique</div>
                                    <div class="text-sm text-gray-600">12 Nov 2024</div>
                                </div>
                                <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-xs font-medium status-pending">
                                    <span class="loading-dots">En attente</span>
                                </span>
                            </div>
                            <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-all duration-300 cursor-pointer hover:scale-105">
                                <div>
                                    <div class="font-medium">Chimie</div>
                                    <div class="text-sm text-gray-600">10 Nov 2024</div>
                                </div>
                                <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-xs font-medium">En révision</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="border border-gray-200 rounded-lg p-6 slide-in-right">
                        <h4 class="font-semibold text-gray-900 mb-4 quick-actions-title">Actions rapides</h4>
                        <div class="space-y-3">
                            <button class="w-full btn-gradient text-white p-4 rounded-lg font-medium transition duration-300 action-declare hover:scale-105 transform">
                                <span class="flex items-center justify-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    Déclarer une absence
                                </span>
                            </button>
                            <button class="w-full border border-gray-300 hover:bg-gray-50 text-gray-700 p-4 rounded-lg font-medium transition duration-300 action-history hover:scale-105 transform hover:border-blue-300">
                                <span class="flex items-center justify-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Voir l'historique
                                </span>
                            </button>
                            <button class="w-full border border-gray-300 hover:bg-gray-50 text-gray-700 p-4 rounded-lg font-medium transition duration-300 action-justification hover:scale-105 transform hover:border-purple-300">
                                <span class="flex items-center justify-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                                    </svg>
                                    Ajouter justificatif
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="py-20 bg-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4 contact-title">Contactez-nous</h2>
                <p class="text-xl text-gray-600 contact-subtitle">Notre équipe est là pour vous accompagner</p>
            </div>
            
            <div class="bg-gray-50 rounded-2xl p-8">
                <div class="grid md:grid-cols-2 gap-8">
                    <div>
                        <h3 class="text-xl font-semibold mb-6 text-gray-900 contact-info-title">Informations de contact</h3>
                        <div class="space-y-4">
                            <div class="flex items-center">
                                <span class="text-2xl mr-4">📧</span>
                                <div>
                                    <div class="font-medium">Email</div>
                                    <div class="text-gray-600">support@justifly.fr</div>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <span class="text-2xl mr-4">📞</span>
                                <div>
                                    <div class="font-medium">Téléphone</div>
                                    <div class="text-gray-600">+33 1 23 45 67 89</div>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <span class="text-2xl mr-4">🏢</span>
                                <div>
                                    <div class="font-medium">Adresse</div>
                                    <div class="text-gray-600">123 Rue de l'Université<br>75005 Paris, France</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <h3 class="text-xl font-semibold mb-6 text-gray-900 demo-title">Demander une démo</h3>
                        <form class="space-y-4">
                            <input type="text" placeholder="Nom complet" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 name-input">
                            <input type="email" placeholder="Email universitaire" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 email-input">
                            <input type="text" placeholder="Université" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 university-input">
                            <textarea placeholder="Message" rows="4" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 message-input"></textarea>
                            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white p-3 rounded-lg font-medium transition duration-300 submit-demo">
                                Envoyer la demande
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-4 gap-8">
                <div>
                    <div class="flex items-center mb-4">
                        <div class="w-8 h-8 gradient-bg rounded-lg flex items-center justify-center mr-2">
                            <span class="text-white font-bold">J</span>
                        </div>
                        <span class="text-xl font-bold">Justifly</span>
                    </div>
                    <p class="text-gray-400 footer-desc">La solution moderne pour la gestion des absences universitaires.</p>
                </div>
                
                <div>
                    <h4 class="font-semibold mb-4 footer-product-title">Produit</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-white transition duration-300 footer-features">Fonctionnalités</a></li>
                        <li><a href="#" class="hover:text-white transition duration-300 footer-pricing">Tarifs</a></li>
                        <li><a href="#" class="hover:text-white transition duration-300 footer-demo">Démo</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="font-semibold mb-4 footer-support-title">Support</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-white transition duration-300 footer-help">Aide</a></li>
                        <li><a href="#" class="hover:text-white transition duration-300 footer-contact">Contact</a></li>
                        <li><a href="#" class="hover:text-white transition duration-300 footer-faq">FAQ</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="font-semibold mb-4 footer-legal-title">Légal</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-white transition duration-300 footer-privacy">Confidentialité</a></li>
                        <li><a href="#" class="hover:text-white transition duration-300 footer-terms">Conditions</a></li>
                        <li><a href="#" class="hover:text-white transition duration-300 footer-cookies">Cookies</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400">
                <p class="footer-copyright">&copy; 2024 Justifly. Tous droits réservés.</p>
            </div>
        </div>
    </footer>

    <script>
        // Language translations
        const translations = {
            fr: {
                // Navigation
                'Accueil': 'Accueil',
                'Fonctionnalités': 'Fonctionnalités',
                'Contact': 'Contact',
                'Connexion': 'Connexion',
                
                // Hero section
                'Gérez vos absences\nintelligemment': 'Gérez vos absences\nintelligemment',
                'Justifly simplifie la gestion des absences universitaires avec une plateforme moderne, intuitive et complète.': 'Justifly simplifie la gestion des absences universitaires avec une plateforme moderne, intuitive et complète.',
                'Commencer maintenant': 'Commencer maintenant',
                'Voir la démo': 'Voir la démo',
                
                // Features
                'Fonctionnalités principales': 'Fonctionnalités principales',
                'Découvrez comment Justifly révolutionne la gestion des absences dans votre université': 'Découvrez comment Justifly révolutionne la gestion des absences dans votre université',
                'Déclaration simplifiée': 'Déclaration simplifiée',
                'Déclarez vos absences en quelques clics avec notre interface intuitive et rapide.': 'Déclarez vos absences en quelques clics avec notre interface intuitive et rapide.',
                'Suivi en temps réel': 'Suivi en temps réel',
                'Consultez le statut de vos demandes et suivez vos absences en temps réel.': 'Consultez le statut de vos demandes et suivez vos absences en temps réel.',
                'Validation automatique': 'Validation automatique',
                'Système intelligent de validation avec notifications automatiques.': 'Système intelligent de validation avec notifications automatiques.',
                
                // Dashboard
                'Interface moderne et intuitive': 'Interface moderne et intuitive',
                'Découvrez notre tableau de bord conçu pour les étudiants': 'Découvrez notre tableau de bord conçu pour les étudiants',
                'Bienvenue, Marie Dubois': 'Bienvenue, Marie Dubois',
                'Absences ce mois': 'Absences ce mois',
                'En attente': 'En attente',
                'Taux de présence': 'Taux de présence',
                'Absences récentes': 'Absences récentes',
                'Approuvée': 'Approuvée',
                'Actions rapides': 'Actions rapides',
                'Déclarer une absence': 'Déclarer une absence',
                'Voir l\'historique': 'Voir l\'historique',
                'Ajouter justificatif': 'Ajouter justificatif',
                
                // Contact
                'Contactez-nous': 'Contactez-nous',
                'Notre équipe est là pour vous accompagner': 'Notre équipe est là pour vous accompagner',
                'Informations de contact': 'Informations de contact',
                'Demander une démo': 'Demander une démo',
                'Nom complet': 'Nom complet',
                'Email universitaire': 'Email universitaire',
                'Université': 'Université',
                'Message': 'Message',
                'Envoyer la demande': 'Envoyer la demande',
                
                // Footer
                'La solution moderne pour la gestion des absences universitaires.': 'La solution moderne pour la gestion des absences universitaires.',
                'Produit': 'Produit',
                'Tarifs': 'Tarifs',
                'Démo': 'Démo',
                'Support': 'Support',
                'Aide': 'Aide',
                'FAQ': 'FAQ',
                'Légal': 'Légal',
                'Confidentialité': 'Confidentialité',
                'Conditions': 'Conditions',
                'Cookies': 'Cookies',
                '© 2024 Justifly. Tous droits réservés.': '© 2024 Justifly. Tous droits réservés.'
            },
            en: {
                // Navigation
                'Accueil': 'Home',
                'Fonctionnalités': 'Features',
                'Contact': 'Contact',
                'Connexion': 'Login',
                
                // Hero section
                'Gérez vos absences\nintelligemment': 'Manage your absences\nintelligently',
                'Justifly simplifie la gestion des absences universitaires avec une plateforme moderne, intuitive et complète.': 'Justifly simplifies university absence management with a modern, intuitive and comprehensive platform.',
                'Commencer maintenant': 'Get Started',
                'Voir la démo': 'View Demo',
                
                // Features
                'Fonctionnalités principales': 'Key Features',
                'Découvrez comment Justifly révolutionne la gestion des absences dans votre université': 'Discover how Justifly revolutionizes absence management in your university',
                'Déclaration simplifiée': 'Simplified Declaration',
                'Déclarez vos absences en quelques clics avec notre interface intuitive et rapide.': 'Declare your absences in just a few clicks with our intuitive and fast interface.',
                'Suivi en temps réel': 'Real-time Tracking',
                'Consultez le statut de vos demandes et suivez vos absences en temps réel.': 'Check the status of your requests and track your absences in real time.',
                'Validation automatique': 'Automatic Validation',
                'Système intelligent de validation avec notifications automatiques.': 'Intelligent validation system with automatic notifications.',
                
                // Dashboard
                'Interface moderne et intuitive': 'Modern and Intuitive Interface',
                'Découvrez notre tableau de bord conçu pour les étudiants': 'Discover our dashboard designed for students',
                'Bienvenue, Marie Dubois': 'Welcome, Marie Dubois',
                'Absences ce mois': 'Absences this month',
                'En attente': 'Pending',
                'Taux de présence': 'Attendance rate',
                'Absences récentes': 'Recent Absences',
                'Approuvée': 'Approved',
                'Actions rapides': 'Quick Actions',
                'Déclarer une absence': 'Declare Absence',
                'Voir l\'historique': 'View History',
                'Ajouter justificatif': 'Add Justification',
                
                // Contact
                'Contactez-nous': 'Contact Us',
                'Notre équipe est là pour vous accompagner': 'Our team is here to support you',
                'Informations de contact': 'Contact Information',
                'Demander une démo': 'Request a Demo',
                'Nom complet': 'Full Name',
                'Email universitaire': 'University Email',
                'Université': 'University',
                'Message': 'Message',
                'Envoyer la demande': 'Send Request',
                
                // Footer
                'La solution moderne pour la gestion des absences universitaires.': 'The modern solution for university absence management.',
                'Produit': 'Product',
                'Tarifs': 'Pricing',
                'Démo': 'Demo',
                'Support': 'Support',
                'Aide': 'Help',
                'FAQ': 'FAQ',
                'Légal': 'Legal',
                'Confidentialité': 'Privacy',
                'Conditions': 'Terms',
                'Cookies': 'Cookies',
                '© 2024 Justifly. Tous droits réservés.': '© 2024 Justifly. All rights reserved.'
            },
            ar: {
                // Navigation
                'Accueil': 'الرئيسية',
                'Fonctionnalités': 'المميزات',
                'Contact': 'اتصل بنا',
                'Connexion': 'تسجيل الدخول',
                
                // Hero section
                'Gérez vos absences\nintelligemment': 'إدارة غياباتك\nبذكاء',
                'Justifly simplifie la gestion des absences universitaires avec une plateforme moderne, intuitive et complète.': 'جستيفلاي يبسط إدارة الغيابات الجامعية بمنصة حديثة وبديهية وشاملة.',
                'Commencer maintenant': 'ابدأ الآن',
                'Voir la démo': 'مشاهدة العرض التوضيحي',
                
                // Features
                'Fonctionnalités principales': 'المميزات الرئيسية',
                'Découvrez comment Justifly révolutionne la gestion des absences dans votre université': 'اكتشف كيف يحدث جستيفلاي ثورة في إدارة الغيابات في جامعتك',
                'Déclaration simplifiée': 'إعلان مبسط',
                'Déclarez vos absences en quelques clics avec notre interface intuitive et rapide.': 'أعلن عن غياباتك بنقرات قليلة مع واجهتنا البديهية والسريعة.',
                'Suivi en temps réel': 'متابعة في الوقت الفعلي',
                'Consultez le statut de vos demandes et suivez vos absences en temps réel.': 'تحقق من حالة طلباتك وتابع غياباتك في الوقت الفعلي.',
                'Validation automatique': 'التحقق التلقائي',
                'Système intelligent de validation avec notifications automatiques.': 'نظام ذكي للتحقق مع إشعارات تلقائية.',
                
                // Dashboard
                'Interface moderne et intuitive': 'واجهة حديثة وبديهية',
                'Découvrez notre tableau de bord conçu pour les étudiants': 'اكتشف لوحة التحكم المصممة للطلاب',
                'Bienvenue, Marie Dubois': 'مرحباً، ماري دوبوا',
                'Absences ce mois': 'الغيابات هذا الشهر',
                'En attente': 'في الانتظار',
                'Taux de présence': 'معدل الحضور',
                'Absences récentes': 'الغيابات الأخيرة',
                'Approuvée': 'موافق عليها',
                'Actions rapides': 'إجراءات سريعة',
                'Déclarer une absence': 'إعلان غياب',
                'Voir l\'historique': 'عرض التاريخ',
                'Ajouter justificatif': 'إضافة مبرر',
                
                // Contact
                'Contactez-nous': 'اتصل بنا',
                'Notre équipe est là pour vous accompagner': 'فريقنا هنا لدعمك',
                'Informations de contact': 'معلومات الاتصال',
                'Demander une démo': 'طلب عرض توضيحي',
                'Nom complet': 'الاسم الكامل',
                'Email universitaire': 'البريد الإلكتروني الجامعي',
                'Université': 'الجامعة',
                'Message': 'الرسالة',
                'Envoyer la demande': 'إرسال الطلب',
                
                // Footer
                'La solution moderne pour la gestion des absences universitaires.': 'الحل الحديث لإدارة الغيابات الجامعية.',
                'Produit': 'المنتج',
                'Tarifs': 'الأسعار',
                'Démo': 'عرض توضيحي',
                'Support': 'الدعم',
                'Aide': 'المساعدة',
                'FAQ': 'الأسئلة الشائعة',
                'Légal': 'قانوني',
                'Confidentialité': 'الخصوصية',
                'Conditions': 'الشروط',
                'Cookies': 'ملفات تعريف الارتباط',
                '© 2024 Justifly. Tous droits réservés.': '© 2024 جستيفلاي. جميع الحقوق محفوظة.'
            }
        };

        // Language switching functionality
        const languageSelect = document.getElementById('languageSelect');
        
        languageSelect.addEventListener('change', function() {
            const selectedLang = this.value;
            const currentTranslations = translations[selectedLang];
            
            // Update HTML lang attribute
            document.documentElement.lang = selectedLang;
            
            // Add RTL class for Arabic
            if (selectedLang === 'ar') {
                document.body.classList.add('rtl');
            } else {
                document.body.classList.remove('rtl');
            }
            
            // Update all translatable elements
            Object.keys(currentTranslations).forEach(key => {
                const elements = document.querySelectorAll(`[class*="${getClassFromText(key)}"]`);
                elements.forEach(element => {
                    if (element.tagName === 'INPUT' || element.tagName === 'TEXTAREA') {
                        element.placeholder = currentTranslations[key];
                    } else {
                        element.textContent = currentTranslations[key];
                    }
                });
            });
            
            // Update navigation links
            updateNavigation(selectedLang);
            updateHeroSection(selectedLang);
            updateFeatures(selectedLang);
            updateDashboard(selectedLang);
            updateContact(selectedLang);
            updateFooter(selectedLang);
        });
        
        function getClassFromText(text) {
            return text.toLowerCase().replace(/[^a-z0-9]/g, '');
        }
        
        function updateNavigation(lang) {
            const navLinks = document.querySelectorAll('.nav-link');
            const loginBtn = document.querySelector('.login-text');
            
            if (lang === 'fr') {
                navLinks[0].textContent = 'Accueil';
                navLinks[1].textContent = 'Fonctionnalités';
                navLinks[2].textContent = 'Contact';
                loginBtn.textContent = 'Connexion';
            } else if (lang === 'en') {
                navLinks[0].textContent = 'Home';
                navLinks[1].textContent = 'Features';
                navLinks[2].textContent = 'Contact';
                loginBtn.textContent = 'Login';
            } else if (lang === 'ar') {
                navLinks[0].textContent = 'الرئيسية';
                navLinks[1].textContent = 'المميزات';
                navLinks[2].textContent = 'اتصل بنا';
                loginBtn.textContent = 'تسجيل الدخول';
            }
        }
        
        function updateHeroSection(lang) {
            const heroTitle = document.querySelector('.hero-content h1');
            const heroSubtitle = document.querySelector('.hero-subtitle');
            const ctaPrimary = document.querySelector('.cta-primary span');
            const ctaSecondary = document.querySelector('.cta-secondary span');
            
            if (lang === 'fr') {
                heroTitle.innerHTML = '<span class="block text-gray-900">Simplifiez</span><span class="block gradient-text">vos absences</span><span class="block text-gray-700">universitaires</span>';
                heroSubtitle.textContent = 'Une solution moderne et intuitive pour gérer efficacement toutes vos absences académiques';
                ctaPrimary.innerHTML = 'Commencer gratuitement<svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>';
                ctaSecondary.innerHTML = '<svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1.586a1 1 0 01.707.293l2.414 2.414a1 1 0 00.707.293H15M9 10V9a2 2 0 012-2h2a2 2 0 012 2v1M9 10v5a2 2 0 002 2h2a2 2 0 002-2v-5"></path></svg>Découvrir la démo';
            } else if (lang === 'en') {
                heroTitle.innerHTML = '<span class="block text-gray-900">Simplify</span><span class="block gradient-text">your absences</span><span class="block text-gray-700">management</span>';
                heroSubtitle.textContent = 'A modern and intuitive solution to efficiently manage all your academic absences';
                ctaPrimary.innerHTML = 'Get Started Free<svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>';
                ctaSecondary.innerHTML = '<svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1.586a1 1 0 01.707.293l2.414 2.414a1 1 0 00.707.293H15M9 10V9a2 2 0 012-2h2a2 2 0 012 2v1M9 10v5a2 2 0 002 2h2a2 2 0 002-2v-5"></path></svg>Discover Demo';
            } else if (lang === 'ar') {
                heroTitle.innerHTML = '<span class="block text-gray-900">بسّط</span><span class="block gradient-text">إدارة غياباتك</span><span class="block text-gray-700">الجامعية</span>';
                heroSubtitle.textContent = 'حل حديث وبديهي لإدارة جميع غياباتك الأكاديمية بكفاءة';
                ctaPrimary.innerHTML = 'ابدأ مجاناً<svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>';
                ctaSecondary.innerHTML = '<svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1.586a1 1 0 01.707.293l2.414 2.414a1 1 0 00.707.293H15M9 10V9a2 2 0 012-2h2a2 2 0 012 2v1M9 10v5a2 2 0 002 2h2a2 2 0 002-2v-5"></path></svg>اكتشف العرض التوضيحي';
            }
        }
        
        function updateFeatures(lang) {
            const featuresTitle = document.querySelector('.features-title');
            const featuresSubtitle = document.querySelector('.features-subtitle');
            const feature1Title = document.querySelector('.feature-1-title');
            const feature1Desc = document.querySelector('.feature-1-desc');
            const feature2Title = document.querySelector('.feature-2-title');
            const feature2Desc = document.querySelector('.feature-2-desc');
            const feature3Title = document.querySelector('.feature-3-title');
            const feature3Desc = document.querySelector('.feature-3-desc');
            
            if (lang === 'fr') {
                featuresTitle.textContent = 'Fonctionnalités principales';
                featuresSubtitle.textContent = 'Découvrez comment Justifly révolutionne la gestion des absences dans votre université';
                feature1Title.textContent = 'Déclaration simplifiée';
                feature1Desc.textContent = 'Déclarez vos absences en quelques clics avec notre interface intuitive et rapide.';
                feature2Title.textContent = 'Suivi en temps réel';
                feature2Desc.textContent = 'Consultez le statut de vos demandes et suivez vos absences en temps réel.';
                feature3Title.textContent = 'Validation automatique';
                feature3Desc.textContent = 'Système intelligent de validation avec notifications automatiques.';
            } else if (lang === 'en') {
                featuresTitle.textContent = 'Key Features';
                featuresSubtitle.textContent = 'Discover how Justifly revolutionizes absence management in your university';
                feature1Title.textContent = 'Simplified Declaration';
                feature1Desc.textContent = 'Declare your absences in just a few clicks with our intuitive and fast interface.';
                feature2Title.textContent = 'Real-time Tracking';
                feature2Desc.textContent = 'Check the status of your requests and track your absences in real time.';
                feature3Title.textContent = 'Automatic Validation';
                feature3Desc.textContent = 'Intelligent validation system with automatic notifications.';
            } else if (lang === 'ar') {
                featuresTitle.textContent = 'المميزات الرئيسية';
                featuresSubtitle.textContent = 'اكتشف كيف يحدث جستيفلاي ثورة في إدارة الغيابات في جامعتك';
                feature1Title.textContent = 'إعلان مبسط';
                feature1Desc.textContent = 'أعلن عن غياباتك بنقرات قليلة مع واجهتنا البديهية والسريعة.';
                feature2Title.textContent = 'متابعة في الوقت الفعلي';
                feature2Desc.textContent = 'تحقق من حالة طلباتك وتابع غياباتك في الوقت الفعلي.';
                feature3Title.textContent = 'التحقق التلقائي';
                feature3Desc.textContent = 'نظام ذكي للتحقق مع إشعارات تلقائية.';
            }
        }
        
        function updateDashboard(lang) {
            const dashboardTitle = document.querySelector('.dashboard-title');
            const dashboardSubtitle = document.querySelector('.dashboard-subtitle');
            const dashboardWelcome = document.querySelector('.dashboard-welcome');
            const stat1 = document.querySelector('.stat-1');
            const stat2 = document.querySelector('.stat-2');
            const stat3 = document.querySelector('.stat-3');
            const recentTitle = document.querySelector('.recent-title');
            const statusApproved = document.querySelector('.status-approved');
            const statusPending = document.querySelector('.status-pending');
            const quickActionsTitle = document.querySelector('.quick-actions-title');
            const actionDeclare = document.querySelector('.action-declare');
            const actionHistory = document.querySelector('.action-history');
            const actionJustification = document.querySelector('.action-justification');
            
            if (lang === 'fr') {
                dashboardTitle.textContent = 'Interface moderne et intuitive';
                dashboardSubtitle.textContent = 'Découvrez notre tableau de bord conçu pour les étudiants';
                dashboardWelcome.textContent = 'Bienvenue, Marie Dubois';
                stat1.textContent = 'Absences ce mois';
                stat2.textContent = 'En attente';
                stat3.textContent = 'Taux de présence';
                recentTitle.textContent = 'Absences récentes';
                statusApproved.textContent = 'Approuvée';
                statusPending.textContent = 'En attente';
                quickActionsTitle.textContent = 'Actions rapides';
                actionDeclare.textContent = 'Déclarer une absence';
                actionHistory.textContent = 'Voir l\'historique';
                actionJustification.textContent = 'Ajouter justificatif';
            } else if (lang === 'en') {
                dashboardTitle.textContent = 'Modern and Intuitive Interface';
                dashboardSubtitle.textContent = 'Discover our dashboard designed for students';
                dashboardWelcome.textContent = 'Welcome, Marie Dubois';
                stat1.textContent = 'Absences this month';
                stat2.textContent = 'Pending';
                stat3.textContent = 'Attendance rate';
                recentTitle.textContent = 'Recent Absences';
                statusApproved.textContent = 'Approved';
                statusPending.textContent = 'Pending';
                quickActionsTitle.textContent = 'Quick Actions';
                actionDeclare.textContent = 'Declare Absence';
                actionHistory.textContent = 'View History';
                actionJustification.textContent = 'Add Justification';
            } else if (lang === 'ar') {
                dashboardTitle.textContent = 'واجهة حديثة وبديهية';
                dashboardSubtitle.textContent = 'اكتشف لوحة التحكم المصممة للطلاب';
                dashboardWelcome.textContent = 'مرحباً، ماري دوبوا';
                stat1.textContent = 'الغيابات هذا الشهر';
                stat2.textContent = 'في الانتظار';
                stat3.textContent = 'معدل الحضور';
                recentTitle.textContent = 'الغيابات الأخيرة';
                statusApproved.textContent = 'موافق عليها';
                statusPending.textContent = 'في الانتظار';
                quickActionsTitle.textContent = 'إجراءات سريعة';
                actionDeclare.textContent = 'إعلان غياب';
                actionHistory.textContent = 'عرض التاريخ';
                actionJustification.textContent = 'إضافة مبرر';
            }
        }
        
        function updateContact(lang) {
            const contactTitle = document.querySelector('.contact-title');
            const contactSubtitle = document.querySelector('.contact-subtitle');
            const contactInfoTitle = document.querySelector('.contact-info-title');
            const demoTitle = document.querySelector('.demo-title');
            const nameInput = document.querySelector('.name-input');
            const emailInput = document.querySelector('.email-input');
            const universityInput = document.querySelector('.university-input');
            const messageInput = document.querySelector('.message-input');
            const submitDemo = document.querySelector('.submit-demo');
            
            if (lang === 'fr') {
                contactTitle.textContent = 'Contactez-nous';
                contactSubtitle.textContent = 'Notre équipe est là pour vous accompagner';
                contactInfoTitle.textContent = 'Informations de contact';
                demoTitle.textContent = 'Demander une démo';
                nameInput.placeholder = 'Nom complet';
                emailInput.placeholder = 'Email universitaire';
                universityInput.placeholder = 'Université';
                messageInput.placeholder = 'Message';
                submitDemo.textContent = 'Envoyer la demande';
            } else if (lang === 'en') {
                contactTitle.textContent = 'Contact Us';
                contactSubtitle.textContent = 'Our team is here to support you';
                contactInfoTitle.textContent = 'Contact Information';
                demoTitle.textContent = 'Request a Demo';
                nameInput.placeholder = 'Full Name';
                emailInput.placeholder = 'University Email';
                universityInput.placeholder = 'University';
                messageInput.placeholder = 'Message';
                submitDemo.textContent = 'Send Request';
            } else if (lang === 'ar') {
                contactTitle.textContent = 'اتصل بنا';
                contactSubtitle.textContent = 'فريقنا هنا لدعمك';
                contactInfoTitle.textContent = 'معلومات الاتصال';
                demoTitle.textContent = 'طلب عرض توضيحي';
                nameInput.placeholder = 'الاسم الكامل';
                emailInput.placeholder = 'البريد الإلكتروني الجامعي';
                universityInput.placeholder = 'الجامعة';
                messageInput.placeholder = 'الرسالة';
                submitDemo.textContent = 'إرسال الطلب';
            }
        }
        
        function updateFooter(lang) {
            const footerDesc = document.querySelector('.footer-desc');
            const footerProductTitle = document.querySelector('.footer-product-title');
            const footerFeatures = document.querySelector('.footer-features');
            const footerPricing = document.querySelector('.footer-pricing');
            const footerDemo = document.querySelector('.footer-demo');
            const footerSupportTitle = document.querySelector('.footer-support-title');
            const footerHelp = document.querySelector('.footer-help');
            const footerContact = document.querySelector('.footer-contact');
            const footerFaq = document.querySelector('.footer-faq');
            const footerLegalTitle = document.querySelector('.footer-legal-title');
            const footerPrivacy = document.querySelector('.footer-privacy');
            const footerTerms = document.querySelector('.footer-terms');
            const footerCookies = document.querySelector('.footer-cookies');
            const footerCopyright = document.querySelector('.footer-copyright');
            
            if (lang === 'fr') {
                footerDesc.textContent = 'La solution moderne pour la gestion des absences universitaires.';
                footerProductTitle.textContent = 'Produit';
                footerFeatures.textContent = 'Fonctionnalités';
                footerPricing.textContent = 'Tarifs';
                footerDemo.textContent = 'Démo';
                footerSupportTitle.textContent = 'Support';
                footerHelp.textContent = 'Aide';
                footerContact.textContent = 'Contact';
                footerFaq.textContent = 'FAQ';
                footerLegalTitle.textContent = 'Légal';
                footerPrivacy.textContent = 'Confidentialité';
                footerTerms.textContent = 'Conditions';
                footerCookies.textContent = 'Cookies';
                footerCopyright.textContent = '© 2024 Justifly. Tous droits réservés.';
            } else if (lang === 'en') {
                footerDesc.textContent = 'The modern solution for university absence management.';
                footerProductTitle.textContent = 'Product';
                footerFeatures.textContent = 'Features';
                footerPricing.textContent = 'Pricing';
                footerDemo.textContent = 'Demo';
                footerSupportTitle.textContent = 'Support';
                footerHelp.textContent = 'Help';
                footerContact.textContent = 'Contact';
                footerFaq.textContent = 'FAQ';
                footerLegalTitle.textContent = 'Legal';
                footerPrivacy.textContent = 'Privacy';
                footerTerms.textContent = 'Terms';
                footerCookies.textContent = 'Cookies';
                footerCopyright.textContent = '© 2024 Justifly. All rights reserved.';
            } else if (lang === 'ar') {
                footerDesc.textContent = 'الحل الحديث لإدارة الغيابات الجامعية.';
                footerProductTitle.textContent = 'المنتج';
                footerFeatures.textContent = 'المميزات';
                footerPricing.textContent = 'الأسعار';
                footerDemo.textContent = 'عرض توضيحي';
                footerSupportTitle.textContent = 'الدعم';
                footerHelp.textContent = 'المساعدة';
                footerContact.textContent = 'اتصل بنا';
                footerFaq.textContent = 'الأسئلة الشائعة';
                footerLegalTitle.textContent = 'قانوني';
                footerPrivacy.textContent = 'الخصوصية';
                footerTerms.textContent = 'الشروط';
                footerCookies.textContent = 'ملفات تعريف الارتباط';
                footerCopyright.textContent = '© 2024 جستيفلاي. جميع الحقوق محفوظة.';
            }
        }

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

        // Form submission handler
        document.querySelector('form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Get current language
            const currentLang = document.getElementById('languageSelect').value;
            let message = '';
            
            if (currentLang === 'fr') {
                message = 'Merci pour votre demande ! Notre équipe vous contactera bientôt.';
            } else if (currentLang === 'en') {
                message = 'Thank you for your request! Our team will contact you soon.';
            } else if (currentLang === 'ar') {
                message = 'شكراً لطلبك! سيتواصل معك فريقنا قريباً.';
            }
            
            alert(message);
            this.reset();
        });

        // Create floating particles
        function createParticles() {
            const particlesContainer = document.getElementById('particles');
            const particleCount = 30;
            
            for (let i = 0; i < particleCount; i++) {
                const particle = document.createElement('div');
                particle.className = 'particle';
                particle.style.left = Math.random() * 100 + '%';
                particle.style.animationDelay = Math.random() * 8 + 's';
                particle.style.animationDuration = (Math.random() * 3 + 5) + 's';
                particlesContainer.appendChild(particle);
            }
        }

        // Enhanced scroll animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-in');
                    
                    // Add staggered animation for cards
                    if (entry.target.classList.contains('interactive-card')) {
                        const cards = entry.target.parentElement.querySelectorAll('.interactive-card');
                        cards.forEach((card, index) => {
                            setTimeout(() => {
                                card.classList.add('animate-in');
                            }, index * 200);
                        });
                    }
                }
            });
        }, observerOptions);

        // Observe elements with animation classes
        document.querySelectorAll('.slide-in-left, .slide-in-right, .slide-in-up, .interactive-card').forEach(element => {
            observer.observe(element);
        });

        // Interactive dashboard stats
        document.querySelectorAll('.dashboard-stat').forEach(stat => {
            stat.addEventListener('click', function() {
                this.style.transform = 'scale(1.1) rotate(5deg)';
                setTimeout(() => {
                    this.style.transform = '';
                }, 300);
            });
        });

        // Button click effects
        document.querySelectorAll('button').forEach(button => {
            button.addEventListener('click', function(e) {
                const ripple = document.createElement('span');
                const rect = this.getBoundingClientRect();
                const size = Math.max(rect.width, rect.height);
                const x = e.clientX - rect.left - size / 2;
                const y = e.clientY - rect.top - size / 2;
                
                ripple.style.width = ripple.style.height = size + 'px';
                ripple.style.left = x + 'px';
                ripple.style.top = y + 'px';
                ripple.classList.add('ripple');
                
                this.appendChild(ripple);
                
                setTimeout(() => {
                    ripple.remove();
                }, 600);
            });
        });

        // Add ripple effect styles
        const rippleStyle = document.createElement('style');
        rippleStyle.textContent = `
            .ripple {
                position: absolute;
                border-radius: 50%;
                background: rgba(255, 255, 255, 0.6);
                transform: scale(0);
                animation: ripple-animation 0.6s linear;
                pointer-events: none;
            }
            
            @keyframes ripple-animation {
                to {
                    transform: scale(4);
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(rippleStyle);

        // Initialize animations when page loads
        window.addEventListener('load', function() {
            createParticles();
        });

        // Mouse parallax effect for hero section
        document.addEventListener('mousemove', function(e) {
            const shapes = document.querySelectorAll('.shape');
            const mouseX = e.clientX / window.innerWidth;
            const mouseY = e.clientY / window.innerHeight;
            
            shapes.forEach((shape, index) => {
                const speed = (index + 1) * 0.3;
                const x = (mouseX - 0.5) * speed * 30;
                const y = (mouseY - 0.5) * speed * 30;
                
                shape.style.transform = `translate(${x}px, ${y}px)`;
            });
        });

        // Interactive card tilt effect
        document.querySelectorAll('.interactive-card').forEach(card => {
            card.addEventListener('mousemove', function(e) {
                const rect = this.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;
                const centerX = rect.width / 2;
                const centerY = rect.height / 2;
                const rotateX = (y - centerY) / 10;
                const rotateY = (centerX - x) / 10;
                
                this.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) translateZ(10px)`;
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'perspective(1000px) rotateX(0) rotateY(0) translateZ(0)';
            });
        });
    </script>
<script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'96c016ea94d93c43',t:'MTc1NDY2NzM4OC4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script></body>
</html>
