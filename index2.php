<!DOCTYPE html>
<html lang="fr" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Choisir votre type - Justifly</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* متغيرات الألوان الأساسية */
        :root {
            --bg-primary: #0f172a;
            --bg-secondary: #1e293b;
            --bg-card: rgba(255, 255, 255, 0.05);
            --text-primary: #e2e8f0;
            --text-secondary: #94a3b8;
            --accent-color: #667eea;
            --accent-hover: #7c3aed;
            --gradient-primary: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --gradient-secondary: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
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
            overflow: auto;
        }
        
        /* Animated Background */
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
        
        /* Floating particles */
        .particle {
            position: absolute;
            width: 4px;
            height: 4px;
            background: rgba(102, 126, 234, 0.3);
            border-radius: 50%;
            pointer-events: none;
            animation: particleFloat 15s infinite linear;
        }
        
        @keyframes particleFloat {
            0% {
                transform: translateY(100vh) translateX(0);
                opacity: 0;
            }
            10% {
                opacity: 1;
            }
            90% {
                opacity: 1;
            }
            100% {
                transform: translateY(-100vh) translateX(100px);
                opacity: 0;
            }
        }
        
        .selection-container {
            background: var(--bg-card);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 24px;
            padding: 2rem;
            width: 90%;
            max-width: 500px;
            max-height: 90vh;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.3);
            position: relative;
            overflow: hidden;
            z-index: 1;
            display: flex;
            flex-direction: column;
            margin: 2rem auto;
        }
        
        .selection-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--gradient-primary);
        }
        
        .scrollable-content {
            overflow-y: auto;
            padding-right: 0.5rem;
            flex: 1;
        }
        
        /* Custom scrollbar */
        .scrollable-content::-webkit-scrollbar {
            width: 6px;
        }
        
        .scrollable-content::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 3px;
        }
        
        .scrollable-content::-webkit-scrollbar-thumb {
            background: rgba(102, 126, 234, 0.3);
            border-radius: 3px;
        }
        
        .scrollable-content::-webkit-scrollbar-thumb:hover {
            background: rgba(102, 126, 234, 0.5);
        }
        
        .header-section {
            text-align: center;
            margin-bottom: 2rem;
            flex-shrink: 0;
        }
        
        .logo {
            width: 60px;
            height: 60px;
            background: var(--gradient-primary);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            font-size: 1.5rem;
            color: white;
            font-weight: bold;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
            transition: transform 0.3s ease;
        }
        
        .logo:hover {
            transform: scale(1.05) rotate(5deg);
        }
        
        .user-type-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        
        .user-type-card {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            padding: 1.5rem 1rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
            overflow: hidden;
        }
        
        .user-type-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: var(--gradient-primary);
            opacity: 0;
            transition: opacity 0.3s ease;
            z-index: -1;
        }
        
        .user-type-card:hover::before {
            opacity: 0.1;
        }
        
        .user-type-card:hover {
            transform: translateY(-5px) scale(1.02);
            border-color: var(--accent-color);
            box-shadow: 0 15px 30px rgba(102, 126, 234, 0.3);
        }
        
        .user-type-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            display: block;
            transition: transform 0.3s ease;
        }
        
        .user-type-card:hover .user-type-icon {
            transform: scale(1.1) rotateY(180deg);
        }
        
        .user-type-title {
            font-size: 1.125rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            color: var(--text-primary);
        }
        
        .user-type-description {
            font-size: 0.75rem;
            color: var(--text-secondary);
            line-height: 1.4;
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
        
        /* Theme switch */
        .theme-switch {
            position: absolute;
            top: 1rem;
            right: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            background: rgba(255, 255, 255, 0.05);
            padding: 0.5rem;
            border-radius: 50px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            z-index: 10;
        }
        
        .theme-switch input {
            display: none;
        }
        
        .theme-switch label {
            cursor: pointer;
            padding: 0.25rem 0.5rem;
            border-radius: 50px;
            transition: all 0.3s ease;
            font-size: 0.875rem;
        }
        
        .theme-switch input:checked + label {
            background: var(--accent-color);
            color: white;
        }
        
        .theme-switch label:hover {
            background: rgba(255, 255, 255, 0.1);
        }
        
        /* Selection animation */
        .selected {
            border-color: var(--accent-color) !important;
            background: rgba(102, 126, 234, 0.1) !important;
        }
        
        .continue-btn {
            width: 100%;
            padding: 0.875rem;
            background: var(--gradient-primary);
            border: none;
            border-radius: 12px;
            color: white;
            font-size: 0.875rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            opacity: 0.5;
            pointer-events: none;
            margin-top: 1rem;
            flex-shrink: 0;
        }
        
        .continue-btn.active {
            opacity: 1;
            pointer-events: auto;
        }
        
        .continue-btn.active::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: left 0.5s ease;
        }
        
        .continue-btn.active:hover::before {
            left: 100%;
        }
        
        .continue-btn.active:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        }
        
        /* Responsive */
        @media (max-width: 640px) {
            .selection-container {
                width: 95%;
                padding: 1.5rem;
                margin: 1rem auto;
                max-height: 85vh;
            }
            
            .user-type-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
            
            .logo {
                width: 50px;
                height: 50px;
                font-size: 1.25rem;
            }
            
            .theme-switch {
                top: 0.5rem;
                right: 0.5rem;
            }
            
            .back-link {
                top: 0.5rem;
                left: 0.5rem;
                font-size: 0.75rem;
                padding: 0.375rem 0.75rem;
            }
            
            /* Hide background animation on mobile for performance */
            .bg-animation {
                display: none;
            }
        }
        
        @media (max-height: 600px) {
            .selection-container {
                margin: 1rem auto;
                max-height: 95vh;
            }
            
            .header-section {
                margin-bottom: 1rem;
            }
            
            .user-type-grid {
                margin-bottom: 1rem;
            }
            
            .user-type-card {
                padding: 1rem 0.75rem;
            }
            
            .user-type-icon {
                font-size: 2rem;
                margin-bottom: 0.5rem;
            }
            
            .user-type-title {
                font-size: 1rem;
                margin-bottom: 0.25rem;
            }
            
            .user-type-description {
                font-size: 0.7rem;
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
    
    <div class="selection-container">
        <a href="index.php" class="back-link">
            <i class="fas fa-arrow-left"></i>
            Retour
        </a>
        
        <div class="theme-switch">
            <input type="checkbox" id="theme-toggle">
            <label for="theme-toggle">
                <i class="fas fa-sun"></i>
            </label>
            <label for="theme-toggle">
                <i class="fas fa-moon"></i>
            </label>
        </div>
        
        <div class="scrollable-content">
            <div class="header-section">
                <div class="logo">J</div>
                <h1 class="text-2xl font-bold mb-1">Bienvenue sur Justifly</h1>
                <p class="text-secondary text-sm">Choisissez votre type d'utilisateur pour continuer</p>
            </div>
            
            <div class="user-type-grid">
                <div class="user-type-card" data-type="student">
                    <i class="fas fa-user-graduate user-type-icon" style="color: #667eea;"></i>
                    <h3 class="user-type-title">Étudiant</h3>
                    <p class="user-type-description">
                        Accédez à votre espace étudiant pour déclarer vos absences, suivre votre présence et gérer votre dossier académique.
                    </p>
                </div>
                
                <div class="user-type-card" data-type="teacher">
                    <i class="fas fa-chalkboard-teacher user-type-icon" style="color: #f093fb;"></i>
                    <h3 class="user-type-title">Enseignant</h3>
                    <p class="user-type-description">
                        Gérez vos classes, examinez les demandes d'absence et suivez la présence de vos étudiants.
                    </p>
                </div>
            </div>
        </div>
        
        <button class="continue-btn" id="continue-btn">
            Continuer
        </button>
    </div>
    
    <script>
        // Create floating particles
        function createParticles() {
            const particlesContainer = document.createElement('div');
            particlesContainer.style.position = 'fixed';
            particlesContainer.style.top = '0';
            particlesContainer.style.left = '0';
            particlesContainer.style.width = '100%';
            particlesContainer.style.height = '100%';
            particlesContainer.style.pointerEvents = 'none';
            particlesContainer.style.zIndex = '0';
            
            for (let i = 0; i < 30; i++) {
                const particle = document.createElement('div');
                particle.className = 'particle';
                particle.style.left = Math.random() * 100 + '%';
                particle.style.animationDelay = Math.random() * 15 + 's';
                particle.style.animationDuration = (Math.random() * 10 + 15) + 's';
                particlesContainer.appendChild(particle);
            }
            
            document.body.appendChild(particlesContainer);
        }
        
        // Theme toggle
        const themeToggle = document.getElementById('theme-toggle');
        const html = document.documentElement;
        
        // Load saved theme
        const savedTheme = localStorage.getItem('theme') || 'dark';
        html.setAttribute('data-theme', savedTheme);
        themeToggle.checked = savedTheme === 'light';
        
        themeToggle.addEventListener('change', () => {
            const theme = themeToggle.checked ? 'light' : 'dark';
            html.setAttribute('data-theme', theme);
            localStorage.setItem('theme', theme);
        });
        
        // User type selection
        const userCards = document.querySelectorAll('.user-type-card');
        const continueBtn = document.getElementById('continue-btn');
        let selectedType = null;
        
        userCards.forEach(card => {
            card.addEventListener('click', () => {
                // Remove previous selection
                userCards.forEach(c => c.classList.remove('selected'));
                
                // Add selection to clicked card
                card.classList.add('selected');
                selectedType = card.dataset.type;
                
                // Enable continue button
                continueBtn.classList.add('active');
                
                // Add ripple effect
                createRipple(card, event);
            });
        });
        
        // Continue button action
        continueBtn.addEventListener('click', () => {
            if (selectedType) {
                // Save selection to localStorage
                localStorage.setItem('userType', selectedType);
                
                // Redirect to appropriate login page
                if (selectedType === 'student') {
                    window.location.href = 'loggin.php';
                } else if (selectedType === 'teacher') {
                    window.location.href = 'loggin-e.php';
                }
            }
        });
        
        // Create ripple effect
        function createRipple(element, event) {
            const ripple = document.createElement('span');
            const rect = element.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            const x = event.clientX - rect.left - size / 2;
            const y = event.clientY - rect.top - size / 2;
            
            ripple.style.width = ripple.style.height = size + 'px';
            ripple.style.left = x + 'px';
            ripple.style.top = y + 'px';
            ripple.style.position = 'absolute';
            ripple.style.borderRadius = '50%';
            ripple.style.background = 'rgba(255, 255, 255, 0.3)';
            ripple.style.transform = 'scale(0)';
            ripple.style.animation = 'ripple 0.6s linear';
            ripple.style.pointerEvents = 'none';
            
            element.appendChild(ripple);
            
            setTimeout(() => {
                ripple.remove();
            }, 600);
        }
        
        // Add ripple animation
        const style = document.createElement('style');
        style.textContent = `
            @keyframes ripple {
                to {
                    transform: scale(4);
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(style);
        
        // Add hover effect to logo
        const logo = document.querySelector('.logo');
        logo.addEventListener('mouseenter', () => {
            logo.style.boxShadow = '0 15px 40px rgba(102, 126, 234, 0.5)';
        });
        
        logo.addEventListener('mouseleave', () => {
            logo.style.boxShadow = '0 10px 30px rgba(102, 126, 234, 0.3)';
        });
        
        // Initialize particles on load
        window.addEventListener('load', () => {
            if (window.innerWidth > 640) {
                createParticles();
            }
        });
        
        // Interactive mouse effect
        document.addEventListener('mousemove', (e) => {
            const circles = document.querySelectorAll('.bg-circle');
            const x = e.clientX / window.innerWidth;
            const y = e.clientY / window.innerHeight;
            
            circles.forEach((circle, index) => {
                const speed = (index + 1) * 0.5;
                const xOffset = (x - 0.5) * speed * 20;
                const yOffset = (y - 0.5) * speed * 20;
                
                circle.style.transform += ` translate(${xOffset}px, ${yOffset}px)`;
            });
        });
        
        // Handle window resize
        window.addEventListener('resize', () => {
            // Adjust container height if needed
            const container = document.querySelector('.selection-container');
            const windowHeight = window.innerHeight;
            
            if (windowHeight < 600) {
                container.style.maxHeight = '95vh';
            } else {
                container.style.maxHeight = '90vh';
            }
        });
    </script>
</body>
</html>