<!DOCTYPE html>
<html lang="fr" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Justifly - Gestion des Absences Universitaires</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* ===== متغيرات الألوان ===== */
        :root {
            /* Dark Mode  */
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

        /* Light Mode - */
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
        
        .glass-button {
            background: var(--bg-card);
            backdrop-filter: blur(10px);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            color: var(--text-primary);
            font-weight: 600;
            padding: 16px 32px;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .glass-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, transparent 100%);
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .glass-button:hover::before {
            opacity: 1;
        }
        
        .glass-button:hover {
            background: var(--bg-card-hover);
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
        }
        
        .feature-card {
            background: var(--bg-card);
            backdrop-filter: blur(20px);
            border: 1px solid var(--border-color);
            border-radius: 20px;
            padding: 32px;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            height: 100%;
            position: relative;
            overflow: hidden;
            transform-style: preserve-3d;
        }
        
        .feature-card::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(102, 126, 234, 0.1) 0%, transparent 70%);
            opacity: 0;
            transition: opacity 0.5s ease;
            transform: rotate(45deg);
        }
        
        .feature-card:hover::before {
            opacity: 1;
        }
        
        .feature-card:hover {
            transform: translateY(-15px) rotateX(5deg) rotateY(5deg);
            box-shadow: 0 25px 50px rgba(102, 126, 234, 0.3);
            border-color: rgba(102, 126, 234, 0.3);
        }
        
        .user-type-card {
            background: var(--bg-card);
            backdrop-filter: blur(20px);
            border: 1px solid var(--border-color);
            border-radius: 24px;
            padding: 40px;
            transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            cursor: pointer;
            position: relative;
            overflow: hidden;
            transform-style: preserve-3d;
        }
        
        .user-type-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1), rgba(118, 75, 162, 0.1));
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .user-type-card:hover::before {
            opacity: 1;
        }
        
        .user-type-card:hover {
            transform: translateY(-20px) rotateX(10deg) rotateY(10deg) scale(1.02);
            box-shadow: 0 30px 60px rgba(102, 126, 234, 0.4);
            border-color: rgba(102, 126, 234, 0.5);
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
        
        .stat-number {
            font-size: 3rem;
            font-weight: 700;
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            display: inline-block;
            position: relative;
        }
        
        .stat-number::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 100%;
            height: 2px;
            background: var(--gradient-primary);
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }
        
        .stat-number:hover::after {
            transform: scaleX(1);
        }
        
        .testimonial-card {
            background: var(--bg-card);
            backdrop-filter: blur(20px);
            border: 1px solid var(--border-color);
            border-radius: 20px;
            padding: 24px;
            transition: all 0.4s ease;
            position: relative;
            overflow: hidden;
        }
        
        .testimonial-card::before {
            content: '"';
            position: absolute;
            top: -20px;
            left: 10px;
            font-size: 120px;
            color: rgba(102, 126, 234, 0.1);
            font-family: Georgia, serif;
        }
        
        .testimonial-card:hover {
            transform: translateY(-10px) rotateX(5deg);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
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
        
        .pulse-dot {
            width: 12px;
            height: 12px;
            background: var(--success-color);
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
        
        .particle {
            position: absolute;
            width: 4px;
            height: 4px;
            background: rgba(102, 126, 234, 0.5);
            border-radius: 50%;
            pointer-events: none;
        }
        
        .glow-text {
            text-shadow: 0 0 20px rgba(102, 126, 234, 0.5);
        }
        
        .section-title {
            position: relative;
            display: inline-block;
        }
        
        .section-title::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            width: 60px;
            height: 4px;
            background: var(--gradient-primary);
            border-radius: 2px;
            transform: translateX(-50%);
        }
        
        .feature-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.2), rgba(118, 75, 162, 0.2));
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 24px;
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
        }
        
        .feature-icon::before {
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
        
        .feature-card:hover .feature-icon::before {
            width: 100px;
            height: 100px;
        }
        
        .feature-card:hover .feature-icon {
            transform: rotateY(180deg);
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.3), rgba(118, 75, 162, 0.3));
        }
        
        .step-number {
            width: 60px;
            height: 60px;
            background: var(--gradient-primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            font-weight: 700;
            margin: 0 auto 20px;
            position: relative;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
            transition: all 0.3s ease;
        }
        
        .step-number::before {
            content: '';
            position: absolute;
            top: -5px;
            left: -5px;
            right: -5px;
            bottom: -5px;
            background: var(--gradient-primary);
            border-radius: 50%;
            z-index: -1;
            opacity: 0.5;
            animation: pulseRing 2s infinite;
        }
        
        @keyframes pulseRing {
            0% {
                transform: scale(1);
                opacity: 0.5;
            }
            100% {
                transform: scale(1.3);
                opacity: 0;
            }
        }
        
        .cta-section {
            position: relative;
            overflow: hidden;
        }
        
        .cta-section::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(102, 126, 234, 0.2) 0%, transparent 70%);
            animation: rotate 30s linear infinite;
        }
        
        @keyframes rotate {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .text-glow {
            animation: textGlow 2s ease-in-out infinite alternate;
        }
        
        @keyframes textGlow {
            from {
                text-shadow: 0 0 10px rgba(102, 126, 234, 0.5);
            }
            to {
                text-shadow: 0 0 20px rgba(102, 126, 234, 0.8), 0 0 30px rgba(118, 75, 162, 0.6);
            }
        }
        
        .floating-shape {
            position: absolute;
            border-radius: 50%;
            background: linear-gradient(45deg, rgba(102, 126, 234, 0.1), rgba(118, 75, 162, 0.1));
            backdrop-filter: blur(20px);
            animation: float 20s infinite ease-in-out;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0) translateX(0) rotate(0deg); }
            33% { transform: translateY(-30px) translateX(20px) rotate(120deg); }
            66% { transform: translateY(20px) translateX(-20px) rotate(240deg); }
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
        
        .loader {
            width: 50px;
            height: 50px;
            border: 3px solid rgba(255, 255, 255, 0.1);
            border-top-color: var(--accent-color);
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
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
            
            /* Navigation - CORRECTION DU MENU */
            nav .ml-10 {
                display: flex !important;
                flex-direction: column !important;
                margin-left: 0 !important;
                margin-top: 1rem;
                gap: 0.5rem;
            }
            
            nav .ml-10 .nav-item {
                padding: 0.5rem 0;
                text-align: center;
            }
            
            nav .ml-10 a {
                display: block;
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
            
            /* Navigation ajustement pour très petits écrans */
            nav .ml-10 {
                font-size: 0.9rem;
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
                        <a href="#home" class="nav-item px-3 py-2 font-medium" style="color: var(--text-secondary);">Accueil</a>
                        <a href="about_us.php" class="nav-item px-3 py-2 font-medium" style="color: var(--text-secondary);">À propos</a>
                        <a href="features.php" class="nav-item px-3 py-2 font-medium" style="color: var(--text-secondary);">Fonctionnalités</a>
                        <a href="contact.php" class="nav-item px-3 py-2 font-medium" style="color: var(--text-secondary);">Contact</a>
                        <a href="chatbot/chatbot.html" class="nav-item px-3 py-2 font-medium" style="color: var(--text-secondary);">AI assistante</a>
                        <a href="index2.php" class="gradient-bg hover:opacity-90 text-white px-4 py-2 rounded-lg font-medium transition duration-300 transform hover:scale-105">Se connecter</a>
                    </div>
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
                <a href="#home" class="block px-3 py-2 font-medium" style="color: var(--text-secondary);">Accueil</a>
                <a href="about_us.php" class="block px-3 py-2 font-medium" style="color: var(--text-secondary);">À propos</a>
                <a href="features.php" class="block px-3 py-2 font-medium" style="color: var(--text-secondary);">Fonctionnalités</a>
                <a href="contact.php" class="block px-3 py-2 font-medium" style="color: var(--text-secondary);">Contact</a>
                <a href="chatbot/chatbot.html" class="block px-3 py-2 font-medium" style="color: var(--text-secondary);">AI assistante</a>
                <a href="login.php" class="block gradient-bg hover:opacity-90 text-white px-4 py-2 rounded-lg font-medium transition duration-300 transform hover:scale-105 mt-2">Se connecter</a>
            </div>
        </div>
    </nav>
    
    <!-- Hero Section -->
    <section id="home" class="minimalist-hero flex items-center relative">
        <!-- Floating Shapes -->
        <div class="floating-shapes">
            <div class="shape shape-1"></div>
            <div class="shape shape-2"></div>
            <div class="shape shape-3"></div>
            <div class="shape shape-4"></div>
        </div>
        
        <!-- Floating Icons -->
        <div class="floating-elements">
            <div class="floating-icon icon-1"><i class="fas fa-graduation-cap text-blue-400"></i></div>
            <div class="floating-icon icon-2"><i class="fas fa-chalkboard-teacher text-purple-400"></i></div>
            <div class="floating-icon icon-3"><i class="fas fa-users text-green-400"></i></div>
            <div class="floating-icon icon-4"><i class="fas fa-chart-line text-orange-400"></i></div>
        </div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 py-20">
            <div class="text-center mb-16">
                <!-- Status Badge -->
                <div class="inline-flex items-center glass-card px-6 py-3 mb-8 transform hover:scale-105 transition-transform duration-300">
                    <div class="pulse-dot"></div>
                    <span class="text-sm font-medium" style="color: var(--text-secondary);">Plateforme de confiance pour 50+ universités</span>
                </div>
                
                <!-- Main Title -->
                <h1 class="text-7xl lg:text-8xl font-black mb-6 leading-tight glow-text">
                    <span class="block" style="color: var(--text-primary);">Gérez vos</span>
                    <span class="block gradient-text text-glow">Absences</span>
                    <span class="block" style="color: var(--text-primary);">Intelligemment</span>
                </h1>
                
                <!-- Subtitle -->
                <p class="text-xl lg:text-2xl mb-12 leading-relaxed max-w-3xl mx-auto" style="color: var(--text-secondary);">
                    Justifly simplifie la gestion des absences universitaires avec une plateforme moderne, intuitive et complète.
                </p>
                
                <!-- User Type Selection -->
                <div class="grid md:grid-cols-2 gap-8 max-w-4xl mx-auto mb-16">
                    <div class="user-type-card" onclick="window.location.href='index2.php'">
                        <div class="text-7xl mb-6 transform hover:scale-110 transition-transform duration-300">👨‍🎓</div>
                        <h3 class="text-3xl font-bold mb-4" style="color: var(--text-primary);">Pour les Étudiants</h3>
                        <p class="mb-6" style="color: var(--text-secondary);">Déclarez vos absences, suivez votre présence et gérez votre dossier académique avec notre interface intuitive.</p>
                        <div class="flex items-center font-medium transform hover:translate-x-2 transition-transform duration-300" style="color: var(--accent-color);">
                            Commencer
                            <i class="fas fa-arrow-right ml-2"></i>
                        </div>
                    </div>
                    
                    <div class="user-type-card" onclick="window.location.href='index2.php'">
                        <div class="text-7xl mb-6 transform hover:scale-110 transition-transform duration-300">👩‍🏫</div>
                        <h3 class="text-3xl font-bold mb-4" style="color: var(--text-primary);">Pour les Enseignants</h3>
                        <p class="mb-6" style="color: var(--text-secondary);">Examinez les demandes d'absence, suivez la présence en classe et générez des rapports sans effort.</p>
                        <div class="flex items-center font-medium transform hover:translate-x-2 transition-transform duration-300" style="color: var(--accent-hover);">
                            Commencer
                            <i class="fas fa-arrow-right ml-2"></i>
                        </div>
                    </div>
                </div>
                
                <!-- Stats -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-8 max-w-4xl mx-auto">
                    <div class="transform hover:scale-110 transition-transform duration-300">
                        <div class="stat-number">50K+</div>
                        <div style="color: var(--text-secondary);">Étudiants actifs</div>
                    </div>
                    <div class="transform hover:scale-110 transition-transform duration-300">
                        <div class="stat-number">5K+</div>
                        <div style="color: var(--text-secondary);">Enseignants</div>
                    </div>
                    <div class="transform hover:scale-110 transition-transform duration-300">
                        <div class="stat-number">98%</div>
                        <div style="color: var(--text-secondary);">Satisfaction</div>
                    </div>
                    <div class="transform hover:scale-110 transition-transform duration-300">
                        <div class="stat-number">24/7</div>
                        <div style="color: var(--text-secondary);">Support</div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Features Section -->
    <section id="features" class="py-20 relative overflow-hidden" style="background-color: var(--bg-secondary);">
        <div class="floating-shape w-96 h-96 top-0 right-0 opacity-20"></div>
        <div class="floating-shape w-64 h-64 bottom-0 left-0 opacity-20" style="animation-delay: 5s;"></div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center mb-16">
                <h2 class="text-5xl font-bold mb-4 section-title" style="color: var(--text-primary);">Fonctionnalités Puissantes</h2>
                <p class="text-xl max-w-2xl mx-auto" style="color: var(--text-secondary);">Découvrez les outils qui révolutionnent la gestion des absences</p>
            </div>
            
            <div class="grid md:grid-cols-3 gap-8">
                <!-- Student Features -->
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-user-graduate text-white text-3xl"></i>
                    </div>
                    <h3 class="text-2xl font-semibold mb-4" style="color: var(--text-primary);">Portail Étudiant</h3>
                    <ul class="space-y-3" style="color: var(--text-secondary);">
                        <li class="flex items-start transform hover:translate-x-2 transition-transform duration-300">
                            <i class="fas fa-check text-green-400 mt-1 mr-3"></i>
                            <span>Déclaration rapide d'absence</span>
                        </li>
                        <li class="flex items-start transform hover:translate-x-2 transition-transform duration-300">
                            <i class="fas fa-check text-green-400 mt-1 mr-3"></i>
                            <span>Suivi en temps réel</span>
                        </li>
                        <li class="flex items-start transform hover:translate-x-2 transition-transform duration-300">
                            <i class="fas fa-check text-green-400 mt-1 mr-3"></i>
                            <span>Historique des présences</span>
                        </li>
                        <li class="flex items-start transform hover:translate-x-2 transition-transform duration-300">
                            <i class="fas fa-check text-green-400 mt-1 mr-3"></i>
                            <span>Téléchargement de documents</span>
                        </li>
                    </ul>
                </div>
                
                <!-- Teacher Features -->
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-chalkboard-teacher text-white text-3xl"></i>
                    </div>
                    <h3 class="text-2xl font-semibold mb-4" style="color: var(--text-primary);">Tableau de Bord Enseignant</h3>
                    <ul class="space-y-3" style="color: var(--text-secondary);">
                        <li class="flex items-start transform hover:translate-x-2 transition-transform duration-300">
                            <i class="fas fa-check text-green-400 mt-1 mr-3"></i>
                            <span>Examen des demandes</span>
                        </li>
                        <li class="flex items-start transform hover:translate-x-2 transition-transform duration-300">
                            <i class="fas fa-check text-green-400 mt-1 mr-3"></i>
                            <span>Présence en classe</span>
                        </li>
                        <li class="flex items-start transform hover:translate-x-2 transition-transform duration-300">
                            <i class="fas fa-check text-green-400 mt-1 mr-3"></i>
                            <span>Génération de rapports</span>
                        </li>
                        <li class="flex items-start transform hover:translate-x-2 transition-transform duration-300">
                            <i class="fas fa-check text-green-400 mt-1 mr-3"></i>
                            <span>Outils de communication</span>
                        </li>
                    </ul>
                </div>
                
                <!-- Admin Features -->
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-cogs text-white text-3xl"></i>
                    </div>
                    <h3 class="text-2xl font-semibold mb-4" style="color: var(--text-primary);">Contrôle Administrateur</h3>
                    <ul class="space-y-3" style="color: var(--text-secondary);">
                        <li class="flex items-start transform hover:translate-x-2 transition-transform duration-300">
                            <i class="fas fa-check text-green-400 mt-1 mr-3"></i>
                            <span>Configuration système</span>
                        </li>
                        <li class="flex items-start transform hover:translate-x-2 transition-transform duration-300">
                            <i class="fas fa-check text-green-400 mt-1 mr-3"></i>
                            <span>Gestion des utilisateurs</span>
                        </li>
                        <li class="flex items-start transform hover:translate-x-2 transition-transform duration-300">
                            <i class="fas fa-check text-green-400 mt-1 mr-3"></i>
                            <span>Tableau de bord analytique</span>
                        </li>
                        <li class="flex items-start transform hover:translate-x-2 transition-transform duration-300">
                            <i class="fas fa-check text-green-400 mt-1 mr-3"></i>
                            <span>APIs d'intégration</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
    
    <!-- How It Works Section -->
    <section id="how-it-works" class="py-20 relative overflow-hidden" style="background-color: var(--bg-primary);">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center mb-16">
                <h2 class="text-5xl font-bold mb-4 section-title" style="color: var(--text-primary);">Comment Ça Marche</h2>
                <p class="text-xl max-w-2xl mx-auto" style="color: var(--text-secondary);">Étapes simples pour gérer les absences efficacement</p>
            </div>
            
            <div class="grid md:grid-cols-3 gap-8">
                <div class="text-center transform hover:scale-105 transition-transform duration-300">
                    <div class="step-number">1</div>
                    <h3 class="text-2xl font-semibold mb-3" style="color: var(--text-primary);">Déclaration</h3>
                    <p style="color: var(--text-secondary);">Les étudiants fournissent tous les détails nécessaires et les documents justificatifs pour leurs absences.</p>
                </div>
                
                <div class="text-center transform hover:scale-105 transition-transform duration-300">
                    <div class="step-number">2</div>
                    <h3 class="text-2xl font-semibold mb-3" style="color: var(--text-primary);">Validation</h3>
                    <p style="color: var(--text-secondary);">Les administrateurs examinent les documents et valident les demandes d'absence.</p>
                </div>
                
                <div class="text-center transform hover:scale-105 transition-transform duration-300">
                    <div class="step-number">3</div>
                    <h3 class="text-2xl font-semibold mb-3" style="color: var(--text-primary);">Suivi</h3>
                    <p style="color: var(--text-secondary);">Les enseignants peuvent voir le statut des absences des étudiants et gérer leur présence.</p>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Testimonials Section -->
    <section id="testimonials" class="py-20 relative overflow-hidden" style="background-color: var(--bg-secondary);">
        <div class="floating-shape w-80 h-80 top-1/4 left-1/4 opacity-10"></div>
        <div class="floating-shape w-64 h-64 bottom-1/4 right-1/4 opacity-10" style="animation-delay: 3s;"></div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center mb-16">
                <h2 class="text-5xl font-bold mb-4 section-title" style="color: var(--text-primary);">Ce Que Disent Nos Utilisateurs</h2>
                <p class="text-xl max-w-2xl mx-auto" style="color: var(--text-secondary);">Retours d'expérience réels d'étudiants et d'enseignants</p>
            </div>
            
            <div class="grid md:grid-cols-3 gap-8">
                <div class="testimonial-card transform hover:scale-105 transition-transform duration-300">
                    <div class="flex items-center mb-4">
                        <img src="https://picsum.photos/seed/student1/60/60.jpg" alt="Student" class="w-12 h-12 rounded-full mr-4 border-2 border-blue-500">
                        <div>
                            <h4 class="font-semibold" style="color: var(--text-primary);">ali mohammed</h4>
                            <p class="text-sm" style="color: var(--text-muted);">Étudiante en Informatique</p>
                        </div>
                    </div>
                    <p class="italic" style="color: var(--text-secondary);">"Ce site simplifie énormément la gestion de mes absences ! Je peux vérifier mes absences et soumettre mes justificatifs en ligne sans difficulté. L'interface est intuitive et je reçois des notifications instantanées. Je le recommande à tous les étudiants !"</p>
                    <div class="flex mt-4">
                        <i class="fas fa-star text-yellow-400"></i>
                        <i class="fas fa-star text-yellow-400"></i>
                        <i class="fas fa-star text-yellow-400"></i>
                        <i class="fas fa-star text-yellow-400"></i>
                        <i class="fas fa-star text-yellow-400"></i>
                    </div>
                </div>
                
                <div class="testimonial-card transform hover:scale-105 transition-transform duration-300">
                    <div class="flex items-center mb-4">
                        <img src="https://picsum.photos/seed/teacher1/60/60.jpg" alt="Teacher" class="w-12 h-12 rounded-full mr-4 border-2 border-purple-500">
                        <div>
                            <h4 class="font-semibold" style="color: var(--text-primary);">Prof. Hassini</h4>
                            <p class="text-sm" style="color: var(--text-muted);">prof de mathematique</p>
                        </div>
                    </div>
                    <p class="italic" style="color: var(--text-secondary);">"En tant qu'enseignant, ce système me fait gagner un temps précieux. Le suivi des présences est simple. Les rapports sont clairs et m'aident à suivre la participation en cours. Un outil parfait pour les professeurs !"</p>
                    <div class="flex mt-4">
                        <i class="fas fa-star text-yellow-400"></i>
                        <i class="fas fa-star text-yellow-400"></i>
                        <i class="fas fa-star text-yellow-400"></i>
                        <i class="fas fa-star text-yellow-400"></i>
                        <i class="fas fa-star text-yellow-400"></i>
                    </div>
                </div>
                
                <div class="testimonial-card transform hover:scale-105 transition-transform duration-300">
                    <div class="flex items-center mb-4">
                        <img src="https://picsum.photos/seed/admin1/60/60.jpg" alt="Admin" class="w-12 h-12 rounded-full mr-4 border-2 border-green-500">
                        <div>
                            <h4 class="font-semibold" style="color: var(--text-primary);">khadra hellal</h4>
                            <p class="text-sm" style="color: var(--text-muted);">chef departement</p>
                        </div>
                    </div>
                    <p class="italic" style="color: var(--text-secondary);">"Une plateforme excellente pour la gestion des absences universitaires ! Le tableau de bord admin offre une vue complète, et le système automatise la plupart des tâches, réduisant le travail manuel. Sécurisé, efficace et bien organisé—idéal pour les établissements académiques"</p>
                    <div class="flex mt-4">
                        <i class="fas fa-star text-yellow-400"></i>
                        <i class="fas fa-star text-yellow-400"></i>
                        <i class="fas fa-star text-yellow-400"></i>
                        <i class="fas fa-star text-yellow-400"></i>
                        <i class="fas fa-star text-yellow-400"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- CTA Section -->
    <section class="py-20 cta-section relative overflow-hidden" style="background-color: var(--bg-primary);">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
            <h2 class="text-5xl font-bold mb-6 text-glow" style="color: var(--text-primary);">Rejoignez la Révolution de la Gestion des Absences</h2>
            <p class="text-xl mb-8" style="color: var(--text-primary); opacity: 0.9;">Faites partie des milliers d'étudiants et d'enseignants qui utilisent déjà Justifly</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <button class="modern-button transform hover:scale-105 transition-transform duration-300">
                    Commencer Maintenant
                </button>
                <button class="glass-button transform hover:scale-105 transition-transform duration-300">
                    En Savoir Plus
                </button>
            </div>
        </div>
    </section>
    
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
        
        // Interactive background that follows mouse
        document.addEventListener('mousemove', (e) => {
            const x = e.clientX / window.innerWidth;
            const y = e.clientY / window.innerHeight;
            document.documentElement.style.setProperty('--mouse-x', `${x * 100}%`);
            document.documentElement.style.setProperty('--mouse-y', `${y * 100}%`);
        });
        
        // Create floating particles
        function createParticles() {
            const particlesContainer = document.createElement('div');
            particlesContainer.style.position = 'fixed';
            particlesContainer.style.top = '0';
            particlesContainer.style.left = '0';
            particlesContainer.style.width = '100%';
            particlesContainer.style.height = '100%';
            particlesContainer.style.pointerEvents = 'none';
            particlesContainer.style.zIndex = '1';
            document.body.appendChild(particlesContainer);
            for (let i = 0; i < 50; i++) {
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
        
        // Parallax effect for floating shapes
        window.addEventListener('scroll', () => {
            const scrolled = window.pageYOffset;
            const shapes = document.querySelectorAll('.shape');
            
            shapes.forEach((shape, index) => {
                const speed = 0.5 + (index * 0.1);
                shape.style.transform = `translateY(${scrolled * speed}px)`;
            });
        });
        
        // Add hover effects to feature cards
        document.querySelectorAll('.feature-card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-15px) rotateX(5deg) rotateY(5deg)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0) rotateX(0) rotateY(0)';
            });
        });
        
        // Animate numbers on scroll
        const observerOptions = {
            threshold: 0.5,
            rootMargin: '0px'
        };
        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const statNumbers = entry.target.querySelectorAll('.stat-number');
                    statNumbers.forEach(stat => {
                        const finalValue = stat.textContent;
                        let currentValue = 0;
                        const increment = finalValue.match(/\d+/)[0] / 50;
                        
                        const timer = setInterval(() => {
                            currentValue += increment;
                            if (currentValue >= finalValue.match(/\d+/)[0]) {
                                stat.textContent = finalValue;
                                clearInterval(timer);
                            } else {
                                stat.textContent = Math.floor(currentValue) + finalValue.replace(/\d+/, '');
                            }
                        }, 30);
                    });
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);
        
        // Observe the hero section for number animation
        const heroSection = document.querySelector('.minimalist-hero');
        if (heroSection) {
            observer.observe(heroSection);
        }
        
        // Initialize particles on load
        window.addEventListener('load', () => {
            createParticles();
            animateParticles();
        });
        
        // Add 3D tilt effect to cards
        document.querySelectorAll('.user-type-card, .feature-card, .testimonial-card').forEach(card => {
            card.addEventListener('mousemove', (e) => {
                const rect = card.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;
                const centerX = rect.width / 2;
                const centerY = rect.height / 2;
                const rotateX = (y - centerY) / 10;
                const rotateY = (centerX - x) / 10;
                
                card.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) translateZ(10px)`;
            });
            
            card.addEventListener('mouseleave', () => {
                card.style.transform = 'perspective(1000px) rotateX(0) rotateY(0) translateZ(0)';
            });
        });
        
        // Add ripple effect to buttons
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