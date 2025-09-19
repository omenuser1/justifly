<!DOCTYPE html>
<html lang="fr" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Justifly - Gestion des absences universitaires</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&family=Cairo:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/particles.js/2.0.0/particles.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lottie-web/5.9.6/lottie.min.js"></script>
    <style>
        :root {
            --primary: #1E3A8A;
            --secondary: #10B981;
            --accent: #F59E0B;
            --ai: #8B5CF6;
            --neutral-light: #F3F4F6;
            --neutral-dark: #374151;
        }
        
        body {
            font-family: 'Roboto', sans-serif;
            scroll-behavior: smooth;
            overflow-x: hidden;
        }
        
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Inter', sans-serif;
        }
        
        .glassmorphism {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        
        .hero-gradient {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        }
        
        .typewriter {
            overflow: hidden;
            border-right: 3px solid var(--accent);
            white-space: nowrap;
            margin: 0 auto;
            letter-spacing: 0.15em;
            animation: typing 1.5s steps(40, end), blink-caret 0.75s step-end infinite;
        }
        
        @keyframes typing {
            from { width: 0 }
            to { width: 100% }
        }
        
        @keyframes blink-caret {
            from, to { border-color: transparent }
            50% { border-color: var(--accent) }
        }
        
        .pulse {
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); box-shadow: 0 0 20px var(--accent); }
            100% { transform: scale(1); }
        }
        
        .slide-in-left {
            animation: slideInLeft 1s forwards;
        }
        
        .slide-in-right {
            animation: slideInRight 1s forwards;
        }
        
        @keyframes slideInLeft {
            from { transform: translateX(-100px); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        
        @keyframes slideInRight {
            from { transform: translateX(100px); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        
        .fade-in {
            animation: fadeIn 1s forwards;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        .flip-card {
            perspective: 1000px;
        }
        
        .flip-card-inner {
            transition: transform 0.6s;
            transform-style: preserve-3d;
        }
        
        .flip-card:hover .flip-card-inner {
            transform: rotateY(180deg);
        }
        
        .flip-card-front, .flip-card-back {
            backface-visibility: hidden;
        }
        
        .flip-card-back {
            transform: rotateY(180deg);
        }
        
        .carousel-item {
            transition: transform 0.5s ease;
        }
        
        .rtl {
            direction: rtl;
            font-family: 'Amiri', serif;
        }
        
        .rtl body {
            font-family: 'Cairo', sans-serif;
        }
        
        .mobile-menu {
            transform: translateX(100%);
            transition: transform 0.3s ease-out;
        }
        
        .mobile-menu.open {
            transform: translateX(0);
        }
        
        #particles-js {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            z-index: 0;
        }
        
        .hero-content {
            position: relative;
            z-index: 1;
        }
        
        .language-selector {
            transition: all 0.3s ease;
        }
        
        .language-selector:hover .language-dropdown {
            display: block;
        }
        
        .language-dropdown {
            display: none;
            animation: fadeIn 0.3s ease;
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Navbar -->
    <nav id="navbar" class="fixed top-0 left-0 w-full h-20 bg-blue-900 bg-opacity-90 backdrop-blur-md flex items-center justify-between px-6 z-50 shadow-md transition-all duration-300 transform -translate-y-full">
        <div class="flex items-center">
            <i class="fas fa-calendar-check text-3xl text-amber-500 mr-3"></i>
            <span class="text-white text-2xl font-bold font-inter">Justifly</span>
        </div>
        
        <div class="hidden md:flex items-center space-x-8">
            <a href="#home" class="text-white hover:text-amber-500 transition-colors duration-300">Accueil</a>
            <a href="#about" class="text-white hover:text-amber-500 transition-colors duration-300">À propos</a>
            <a href="#features" class="text-white hover:text-amber-500 transition-colors duration-300">Fonctionnalités</a>
            <a href="#contact" class="text-white hover:text-amber-500 transition-colors duration-300">Contact</a>
            
            <div class="language-selector relative ml-4">
                <button class="flex items-center text-white">
                    <span class="mr-2">Français</span>
                    <i class="fas fa-chevron-down text-xs"></i>
                </button>
                <div class="language-dropdown absolute right-0 mt-2 w-40 bg-white rounded-md shadow-lg py-1 z-50">
                    <a href="#" data-lang="fr" class="block px-4 py-2 text-gray-800 hover:bg-blue-100 flex items-center">
                        <span class="mr-2">🇫🇷</span> Français
                    </a>
                    <a href="#" data-lang="ar" class="block px-4 py-2 text-gray-800 hover:bg-blue-100 flex items-center rtl">
                        <span class="mr-2">🇸🇦</span> العربية
                    </a>
                    <a href="#" data-lang="en" class="block px-4 py-2 text-gray-800 hover:bg-blue-100 flex items-center">
                        <span class="mr-2">🇬🇧</span> English
                    </a>
                </div>
            </div>
            
            <a href="login.php" class="bg-amber-500 hover:bg-amber-600 text-white px-4 py-2 rounded-md transition-all duration-300 transform hover:scale-105 hover:shadow-lg">Se connecter</a>
            <a href="#ai-assistant" class="bg-purple-500 hover:bg-purple-600 text-white px-4 py-2 rounded-md transition-all duration-300 transform hover:scale-105 hover:shadow-lg flex items-center">
                <i class="fas fa-robot mr-2"></i> Assistant IA
            </a>
        </div>
        
        <button id="mobile-menu-button" class="md:hidden text-amber-500 text-2xl">
            <i class="fas fa-bars"></i>
        </button>
    </nav>
    
    <!-- Mobile Menu -->
    <div id="mobile-menu" class="mobile-menu fixed top-20 right-0 w-64 h-full bg-blue-900 bg-opacity-90 backdrop-blur-md z-40 p-6 overflow-y-auto">
        <div class="flex flex-col space-y-6">
            <a href="#home" class="text-white hover:text-amber-500 transition-colors duration-300">Accueil</a>
            <a href="#about" class="text-white hover:text-amber-500 transition-colors duration-300">À propos</a>
            <a href="#features" class="text-white hover:text-amber-500 transition-colors duration-300">Fonctionnalités</a>
            <a href="#contact" class="text-white hover:text-amber-500 transition-colors duration-300">Contact</a>
            
            <div class="language-selector">
                <button class="flex items-center text-white mb-2">
                    <span class="mr-2">Langue: Français</span>
                    <i class="fas fa-chevron-down text-xs"></i>
                </button>
                <div class="language-dropdown ml-4">
                    <a href="#" data-lang="fr" class="block py-2 text-white hover:text-amber-500 flex items-center">
                        <span class="mr-2">🇫🇷</span> Français
                    </a>
                    <a href="#" data-lang="ar" class="block py-2 text-white hover:text-amber-500 flex items-center rtl">
                        <span class="mr-2">🇸🇦</span> العربية
                    </a>
                    <a href="#" data-lang="en" class="block py-2 text-white hover:text-amber-500 flex items-center">
                        <span class="mr-2">🇬🇧</span> English
                    </a>
                </div>
            </div>
            
            <a href="login.php" class="bg-amber-500 hover:bg-amber-600 text-white px-4 py-2 rounded-md transition-all duration-300">Se connecter</a>
            <a href="#ai-assistant" class="bg-purple-500 hover:bg-purple-600 text-white px-4 py-2 rounded-md transition-all duration-300 flex items-center">
                <i class="fas fa-robot mr-2"></i> Assistant IA
            </a>
        </div>
    </div>

    <!-- Hero Section -->
    <section id="home" class="hero-gradient min-h-screen flex items-center justify-center relative overflow-hidden pt-20">
        <div id="particles-js"></div>
        <div class="container mx-auto px-6 hero-content">
            <div class="flex flex-col md:flex-row items-center">
                <div class="md:w-1/2 mb-12 md:mb-0">
                    <h1 class="text-4xl md:text-5xl font-bold text-white mb-6 typewriter">Justifly : Gérez vos absences universitaires avec élégance</h1>
                    <p class="text-xl text-white mb-8 slide-in-left">Une plateforme intuitive pour étudiants, enseignants et administrateurs.</p>
                    <button class="bg-amber-500 hover:bg-amber-600 text-white font-bold py-3 px-8 rounded-full text-lg pulse transition-all duration-300">
                        Démarrez maintenant
                    </button>
                </div>
                <div class="md:w-1/2 flex justify-center">
                    <div id="lottie-animation" class="w-full max-w-md"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-20 bg-gray-50">
        <div class="container mx-auto px-6">
            <h2 class="text-3xl font-bold text-center text-blue-900 mb-16 fade-in">Fonctionnalités</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Student Card -->
                <div class="glassmorphism p-8 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 slide-in-left">
                    <div class="bg-emerald-500 text-white w-16 h-16 rounded-full flex items-center justify-center mb-6 mx-auto">
                        <i class="fas fa-user-graduate text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-center text-blue-900 mb-4">Étudiants</h3>
                    <p class="text-gray-700 text-center">Soumettez et suivez vos absences en un clic.</p>
                </div>
                
                <!-- Teacher Card -->
                <div class="glassmorphism p-8 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 fade-in">
                    <div class="bg-blue-900 text-white w-16 h-16 rounded-full flex items-center justify-center mb-6 mx-auto">
                        <i class="fas fa-chalkboard-teacher text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-center text-blue-900 mb-4">Enseignants</h3>
                    <p class="text-gray-700 text-center">Gérez les absences approuvées avec des rapports personnalisés.</p>
                </div>
                
                <!-- Admin Card -->
                <div class="glassmorphism p-8 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 slide-in-right">
                    <div class="bg-amber-500 text-white w-16 h-16 rounded-full flex items-center justify-center mb-6 mx-auto">
                        <i class="fas fa-user-shield text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-center text-blue-900 mb-4">Administrateurs</h3>
                    <p class="text-gray-700 text-center">Contrôlez les demandes et les utilisateurs efficacement.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Video Section -->
    <section id="video" class="py-20 bg-gray-100">
        <div class="container mx-auto px-6">
            <h2 class="text-3xl font-bold text-center text-blue-900 mb-4">Justifly en action</h2>
            <p class="text-lg text-gray-700 text-center mb-12">Découvrez comment Justifly transforme la gestion des absences.</p>
            
            <div class="max-w-4xl mx-auto glassmorphism rounded-xl overflow-hidden relative">
                <div class="aspect-w-16 aspect-h-9">
                    <video poster="https://via.placeholder.com/800x450" class="w-full">
                        <source src="https://via.placeholder.com/video.mp4" type="video/mp4">
                        Votre navigateur ne supporte pas la vidéo.
                    </video>
                </div>
                <button class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-amber-500 hover:bg-amber-600 text-white rounded-full w-16 h-16 flex items-center justify-center pulse">
                    <i class="fas fa-play text-xl"></i>
                </button>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section id="testimonials" class="py-20 bg-white">
        <div class="container mx-auto px-6">
            <h2 class="text-3xl font-bold text-center text-blue-900 mb-16">Témoignages</h2>
            
            <div class="max-w-4xl mx-auto relative">
                <div class="carousel-container overflow-hidden">
                    <div class="carousel-track flex transition-transform duration-500">
                        <!-- Testimonial 1 -->
                        <div class="carousel-item min-w-full px-4">
                            <div class="glassmorphism p-8 rounded-xl shadow-lg">
                                <div class="bg-emerald-500 text-white w-16 h-16 rounded-full flex items-center justify-center mb-6 mx-auto">
                                    <i class="fas fa-user-circle text-3xl"></i>
                                </div>
                                <p class="text-gray-700 text-center mb-6">"Justifly rend la gestion des absences tellement simple !"</p>
                                <p class="text-emerald-500 font-bold text-center">Étudiant en Informatique</p>
                            </div>
                        </div>
                        
                        <!-- Testimonial 2 -->
                        <div class="carousel-item min-w-full px-4">
                            <div class="glassmorphism p-8 rounded-xl shadow-lg">
                                <div class="bg-blue-900 text-white w-16 h-16 rounded-full flex items-center justify-center mb-6 mx-auto">
                                    <i class="fas fa-user-circle text-3xl"></i>
                                </div>
                                <p class="text-gray-700 text-center mb-6">"Un outil intuitif pour suivre les absences des étudiants."</p>
                                <p class="text-blue-900 font-bold text-center">Professeur M. Djalel</p>
                            </div>
                        </div>
                        
                        <!-- Testimonial 3 -->
                        <div class="carousel-item min-w-full px-4">
                            <div class="glassmorphism p-8 rounded-xl shadow-lg">
                                <div class="bg-amber-500 text-white w-16 h-16 rounded-full flex items-center justify-center mb-6 mx-auto">
                                    <i class="fas fa-user-circle text-3xl"></i>
                                </div>
                                <p class="text-gray-700 text-center mb-6">"Gérer les demandes n'a jamais été aussi rapide."</p>
                                <p class="text-amber-500 font-bold text-center">Administrateur Université</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <button class="carousel-prev absolute left-0 top-1/2 transform -translate-y-1/2 bg-white rounded-full w-10 h-10 flex items-center justify-center shadow-md hover:bg-gray-100">
                    <i class="fas fa-chevron-left text-blue-900"></i>
                </button>
                <button class="carousel-next absolute right-0 top-1/2 transform -translate-y-1/2 bg-white rounded-full w-10 h-10 flex items-center justify-center shadow-md hover:bg-gray-100">
                    <i class="fas fa-chevron-right text-blue-900"></i>
                </button>
                
                <div class="flex justify-center mt-6 space-x-2">
                    <button class="carousel-dot w-3 h-3 rounded-full bg-gray-300 hover:bg-blue-900"></button>
                    <button class="carousel-dot w-3 h-3 rounded-full bg-gray-300 hover:bg-blue-900"></button>
                    <button class="carousel-dot w-3 h-3 rounded-full bg-gray-300 hover:bg-blue-900"></button>
                </div>
            </div>
        </div>
    </section>

    <!-- About Us Section -->
    <section id="about" class="py-20 bg-gray-50">
        <div class="container mx-auto px-6">
            <h2 class="text-3xl font-bold text-center text-blue-900 mb-16">À propos de nous</h2>
            
            <div class="glassmorphism p-8 rounded-xl shadow-lg max-w-4xl mx-auto mb-12">
                <p class="text-gray-700 mb-8">Ce projet est un projet de fin d'études pour les étudiants en informatique de l'Université d'Aïn Témouchent. Ce projet a été développé par trois étudiants :</p>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Team Member 1 -->
                    <div class="flip-card h-64">
                        <div class="flip-card-inner h-full">
                            <div class="flip-card-front bg-blue-900 rounded-xl shadow-lg flex flex-col items-center justify-center p-6 h-full">
                                <div class="bg-white w-24 h-24 rounded-full flex items-center justify-center mb-4">
                                    <i class="fas fa-user text-3xl text-blue-900"></i>
                                </div>
                                <h3 class="text-white font-bold text-xl">Benyoub Imene</h3>
                                <p class="text-white opacity-80">Développeuse Front-End</p>
                            </div>
                            <div class="flip-card-back bg-white rounded-xl shadow-lg flex flex-col items-center justify-center p-6 h-full border-2 border-blue-900">
                                <h3 class="text-blue-900 font-bold text-xl mb-2">Benyoub Imene</h3>
                                <p class="text-gray-700 text-center">Spécialisée en interfaces utilisateur et expérience utilisateur.</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Team Member 2 -->
                    <div class="flip-card h-64">
                        <div class="flip-card-inner h-full">
                            <div class="flip-card-front bg-blue-900 rounded-xl shadow-lg flex flex-col items-center justify-center p-6 h-full">
                                <div class="bg-white w-24 h-24 rounded-full flex items-center justify-center mb-4">
                                    <i class="fas fa-user text-3xl text-blue-900"></i>
                                </div>
                                <h3 class="text-white font-bold text-xl">Fekih Naïma</h3>
                                <p class="text-white opacity-80">Développeuse Back-End</p>
                            </div>
                            <div class="flip-card-back bg-white rounded-xl shadow-lg flex flex-col items-center justify-center p-6 h-full border-2 border-blue-900">
                                <h3 class="text-blue-900 font-bold text-xl mb-2">Fekih Naïma</h3>
                                <p class="text-gray-700 text-center">Spécialisée en architecture logicielle et bases de données.</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Team Member 3 -->
                    <div class="flip-card h-64">
                        <div class="flip-card-inner h-full">
                            <div class="flip-card-front bg-blue-900 rounded-xl shadow-lg flex flex-col items-center justify-center p-6 h-full">
                                <div class="bg-white w-24 h-24 rounded-full flex items-center justify-center mb-4">
                                    <i class="fas fa-user text-3xl text-blue-900"></i>
                                </div>
                                <h3 class="text-white font-bold text-xl">Bouizem Assia</h3>
                                <p class="text-white opacity-80">Développeuse Back-End</p>
                            </div>
                            <div class="flip-card-back bg-white rounded-xl shadow-lg flex flex-col items-center justify-center p-6 h-full border-2 border-blue-900">
                                <h3 class="text-blue-900 font-bold text-xl mb-2">Bouizem Assia</h3>
                                <p class="text-gray-700 text-center">Spécialisée en sécurité informatique et API développement.</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <p class="text-gray-700 mt-8">Cette application a été développée sous la supervision de <span class="font-bold">Prof. Mered Djalel</span>.</p>
            </div>
            
            <div class="text-center">
                <a href="about.php" class="inline-block bg-blue-900 hover:bg-blue-800 text-white font-bold py-3 px-8 rounded-full text-lg transition-all duration-300 transform hover:scale-105">
                    En savoir plus
                </a>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="py-20 bg-gray-100">
        <div class="container mx-auto px-6">
            <h2 class="text-3xl font-bold text-center text-blue-900 mb-16">Contactez-nous</h2>
            
            <div class="max-w-4xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="glassmorphism p-8 rounded-xl shadow-lg">
                    <h3 class="text-xl font-bold text-blue-900 mb-4">Informations de contact</h3>
                    <ul class="space-y-4">
                        <li class="flex items-start">
                            <i class="fas fa-map-marker-alt text-amber-500 mt-1 mr-4"></i>
                            <span class="text-gray-700">Université d'Aïn Témouchent, Algérie</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-envelope text-amber-500 mt-1 mr-4"></i>
                            <span class="text-gray-700">contact@justifly.edu.dz</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-phone text-amber-500 mt-1 mr-4"></i>
                            <span class="text-gray-700">+213 123 456 789</span>
                        </li>
                    </ul>
                    
                    <div class="mt-8">
                        <h3 class="text-xl font-bold text-blue-900 mb-4">Suivez-nous</h3>
                        <div class="flex space-x-4">
                            <a href="#" class="bg-blue-900 text-white w-10 h-10 rounded-full flex items-center justify-center hover:bg-blue-800 transition-colors duration-300">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="#" class="bg-blue-400 text-white w-10 h-10 rounded-full flex items-center justify-center hover:bg-blue-500 transition-colors duration-300">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="#" class="bg-pink-600 text-white w-10 h-10 rounded-full flex items-center justify-center hover:bg-pink-700 transition-colors duration-300">
                                <i class="fab fa-instagram"></i>
                            </a>
                            <a href="#" class="bg-blue-600 text-white w-10 h-10 rounded-full flex items-center justify-center hover:bg-blue-700 transition-colors duration-300">
                                <i class="fab fa-linkedin-in"></i>
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="glassmorphism p-8 rounded-xl shadow-lg">
                    <h3 class="text-xl font-bold text-blue-900 mb-4">Envoyez-nous un message</h3>
                    <form>
                        <div class="mb-4">
                            <label for="name" class="block text-gray-700 mb-2">Nom complet</label>
                            <input type="text" id="name" class="w-full px-4 py-2 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-amber-500">
                        </div>
                        <div class="mb-4">
                            <label for="email" class="block text-gray-700 mb-2">Email</label>
                            <input type="email" id="email" class="w-full px-4 py-2 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-amber-500">
                        </div>
                        <div class="mb-4">
                            <label for="message" class="block text-gray-700 mb-2">Message</label>
                            <textarea id="message" rows="4" class="w-full px-4 py-2 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-amber-500"></textarea>
                        </div>
                        <button type="submit" class="bg-amber-500 hover:bg-amber-600 text-white font-bold py-2 px-6 rounded-md transition-all duration-300 transform hover:scale-105">
                            Envoyer
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- AI Assistant Modal -->
    <div id="ai-assistant-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-hidden">
            <div class="bg-purple-600 text-white p-4 flex justify-between items-center">
                <h3 class="text-xl font-bold flex items-center">
                    <i class="fas fa-robot mr-2"></i> Assistant Justifly
                </h3>
                <button id="close-ai-modal" class="text-white hover:text-gray-200">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="p-6 h-96 overflow-y-auto">
                <div class="chat-messages space-y-4">
                    <div class="flex justify-start">
                        <div class="bg-gray-100 rounded-lg p-4 max-w-xs md:max-w-md">
                            <p>Bonjour ! Je suis l'assistant virtuel de Justifly. Comment puis-je vous aider aujourd'hui ?</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="p-4 border-t border-gray-200">
                <div class="flex">
                    <input type="text" placeholder="Posez votre question..." class="flex-grow px-4 py-2 rounded-l-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-purple-500">
                    <button class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-r-md transition-colors duration-300">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-12">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <div class="flex items-center mb-4">
                        <i class="fas fa-calendar-check text-2xl text-amber-500 mr-3"></i>
                        <span class="text-xl font-bold font-inter">Justifly</span>
                    </div>
                    <p class="text-gray-300">La solution ultime pour la gestion des absences universitaires.</p>
                </div>
                
                <div>
                    <h3 class="text-lg font-bold mb-4">Liens rapides</h3>
                    <ul class="space-y-2">
                        <li><a href="#home" class="text-gray-300 hover:text-amber-500 transition-colors duration-300">Accueil</a></li>
                        <li><a href="#about" class="text-gray-300 hover:text-amber-500 transition-colors duration-300">À propos</a></li>
                        <li><a href="#features" class="text-gray-300 hover:text-amber-500 transition-colors duration-300">Fonctionnalités</a></li>
                        <li><a href="#contact" class="text-gray-300 hover:text-amber-500 transition-colors duration-300">Contact</a></li>
                    </ul>
                </div>
                
                <div>
                    <h3 class="text-lg font-bold mb-4">Ressources</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-300 hover:text-amber-500 transition-colors duration-300">Documentation</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-amber-500 transition-colors duration-300">FAQ</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-amber-500 transition-colors duration-300">Politique de confidentialité</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-amber-500 transition-colors duration-300">Conditions d'utilisation</a></li>
                    </ul>
                </div>
                
                <div>
                    <h3 class="text-lg font-bold mb-4">Newsletter</h3>
                    <p class="text-gray-300 mb-4">Abonnez-vous pour recevoir les dernières mises à jour.</p>
                    <div class="flex">
                        <input type="email" placeholder="Votre email" class="px-4 py-2 rounded-l-md text-gray-800 w-full focus:outline-none">
                        <button class="bg-amber-500 hover:bg-amber-600 px-4 py-2 rounded-r-md transition-colors duration-300">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400">
                <p>© 2025 Justifly. Tous droits réservés.</p>
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Navbar animation
            const navbar = document.getElementById('navbar');
            navbar.style.transform = 'translateY(0)';
            
            // Mobile menu toggle
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const mobileMenu = document.getElementById('mobile-menu');
            
            mobileMenuButton.addEventListener('click', function() {
                mobileMenu.classList.toggle('open');
            });
            
            // Close mobile menu when clicking outside
            document.addEventListener('click', function(event) {
                if (!mobileMenu.contains(event.target) && event.target !== mobileMenuButton) {
                    mobileMenu.classList.remove('open');
                }
            });
            
            // Scroll animations
            const animateOnScroll = function() {
                const elements = document.querySelectorAll('.slide-in-left, .slide-in-right, .fade-in');
                
                elements.forEach(element => {
                    const elementPosition = element.getBoundingClientRect().top;
                    const screenPosition = window.innerHeight / 1.3;
                    
                    if (elementPosition < screenPosition) {
                        element.style.opacity = '1';
                        element.style.transform = 'translateX(0)';
                    }
                });
                
                // Navbar shadow on scroll
                if (window.scrollY > 10) {
                    navbar.classList.add('shadow-lg');
                } else {
                    navbar.classList.remove('shadow-lg');
                }
            };
            
            window.addEventListener('scroll', animateOnScroll);
            animateOnScroll(); // Run once on load
            
            // Lottie animation
            const lottieAnimation = lottie.loadAnimation({
                container: document.getElementById('lottie-animation'),
                renderer: 'svg',
                loop: true,
                autoplay: true,
                path: 'https://assets5.lottiefiles.com/packages/lf20_3pgxyh.json'
            });
            
            // Particles.js
            particlesJS('particles-js', {
                particles: {
                    number: {
                        value: 80,
                        density: {
                            enable: true,
                            value_area: 800
                        }
                    },
                    color: {
                        value: ['#1E3A8A', '#10B981', '#F59E0B']
                    },
                    shape: {
                        type: 'circle',
                        stroke: {
                            width: 0,
                            color: '#000000'
                        }
                    },
                    opacity: {
                        value: 0.5,
                        random: true,
                        anim: {
                            enable: true,
                            speed: 1,
                            opacity_min: 0.1,
                            sync: false
                        }
                    },
                    size: {
                        value: 3,
                        random: true,
                        anim: {
                            enable: true,
                            speed: 2,
                            size_min: 0.3,
                            sync: false
                        }
                    },
                    line_linked: {
                        enable: true,
                        distance: 150,
                        color: '#1E3A8A',
                        opacity: 0.4,
                        width: 1
                    },
                    move: {
                        enable: true,
                        speed: 1,
                        direction: 'none',
                        random: true,
                        straight: false,
                        out_mode: 'out',
                        bounce: false,
                        attract: {
                            enable: false,
                            rotateX: 600,
                            rotateY: 1200
                        }
                    }
                },
                interactivity: {
                    detect_on: 'canvas',
                    events: {
                        onhover: {
                            enable: true,
                            mode: 'grab'
                        },
                        onclick: {
                            enable: true,
                            mode: 'push'
                        },
                        resize: true
                    },
                    modes: {
                        grab: {
                            distance: 140,
                            line_linked: {
                                opacity: 1
                            }
                        },
                        bubble: {
                            distance: 400,
                            size: 40,
                            duration: 2,
                            opacity: 8,
                            speed: 3
                        },
                        push: {
                            particles_nb: 4
                        }
                    }
                },
                retina_detect: true
            });
            
            // Testimonial carousel
            const carouselTrack = document.querySelector('.carousel-track');
            const carouselItems = document.querySelectorAll('.carousel-item');
            const prevButton = document.querySelector('.carousel-prev');
            const nextButton = document.querySelector('.carousel-next');
            const dots = document.querySelectorAll('.carousel-dot');
            
            let currentIndex = 0;
            const itemWidth = carouselItems[0].clientWidth;
            const totalItems = carouselItems.length;
            
            function updateCarousel() {
                carouselTrack.style.transform = `translateX(-${currentIndex * itemWidth}px)`;
                
                dots.forEach((dot, index) => {
                    if (index === currentIndex) {
                        dot.classList.add('bg-blue-900');
                        dot.classList.remove('bg-gray-300');
                    } else {
                        dot.classList.add('bg-gray-300');
                        dot.classList.remove('bg-blue-900');
                    }
                });
            }
            
            nextButton.addEventListener('click', () => {
                currentIndex = (currentIndex + 1) % totalItems;
                updateCarousel();
            });
            
            prevButton.addEventListener('click', () => {
                currentIndex = (currentIndex - 1 + totalItems) % totalItems;
                updateCarousel();
            });
            
            dots.forEach((dot, index) => {
                dot.addEventListener('click', () => {
                    currentIndex = index;
                    updateCarousel();
                });
            });
            
            // Auto-advance carousel
            let carouselInterval = setInterval(() => {
                currentIndex = (currentIndex + 1) % totalItems;
                updateCarousel();
            }, 5000);
            
            // Pause carousel on hover
            const carouselContainer = document.querySelector('.carousel-container');
            carouselContainer.addEventListener('mouseenter', () => {
                clearInterval(carouselInterval);
            });
            
            carouselContainer.addEventListener('mouseleave', () => {
                carouselInterval = setInterval(() => {
                    currentIndex = (currentIndex + 1) % totalItems;
                    updateCarousel();
                }, 5000);
            });
            
            // AI Assistant Modal
            const aiAssistantButton = document.querySelector('a[href="#ai-assistant"]');
            const aiModal = document.getElementById('ai-assistant-modal');
            const closeAiModal = document.getElementById('close-ai-modal');
            
            if (aiAssistantButton) {
                aiAssistantButton.addEventListener('click', function(e) {
                    e.preventDefault();
                    aiModal.classList.remove('hidden');
                });
            }
            
            closeAiModal.addEventListener('click', function() {
                aiModal.classList.add('hidden');
            });
            
            // Close modal when clicking outside
            aiModal.addEventListener('click', function(e) {
                if (e.target === aiModal) {
                    aiModal.classList.add('hidden');
                }
            });
            
            // Video play button
            const video = document.querySelector('video');
            const videoPlayButton = document.querySelector('#video button');
            
            if (videoPlayButton) {
                videoPlayButton.addEventListener('click', function() {
                    if (video.paused) {
                        video.play();
                        videoPlayButton.innerHTML = '<i class="fas fa-pause"></i>';
                    } else {
                        video.pause();
                        videoPlayButton.innerHTML = '<i class="fas fa-play"></i>';
                    }
                });
            }
            
            // Language toggle
            const languageLinks = document.querySelectorAll('[data-lang]');
            
            languageLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const lang = this.getAttribute('data-lang');
                    changeLanguage(lang);
                    
                    // Update language selector text
                    const flag = this.querySelector('span').textContent;
                    const languageName = this.textContent.trim().split(' ')[1];
                    
                    document.querySelectorAll('.language-selector button span').forEach(span => {
                        span.textContent = languageName;
                    });
                    
                    // Close dropdown
                    document.querySelectorAll('.language-dropdown').forEach(dropdown => {
                        dropdown.style.display = 'none';
                    });
                    
                    // Store preference in localStorage
                    localStorage.setItem('justifly-language', lang);
                });
            });
            
            // Check for saved language preference
            const savedLanguage = localStorage.getItem('justifly-language');
            if (savedLanguage) {
                changeLanguage(savedLanguage);
                
                // Update selector text
                let languageName = 'Français';
                if (savedLanguage === 'ar') languageName = 'العربية';
                if (savedLanguage === 'en') languageName = 'English';
                
                document.querySelectorAll('.language-selector button span').forEach(span => {
                    span.textContent = languageName;
                });
            }
            
            // Language content
            const translations = {
                fr: {
                    navbar: {
                        home: "Accueil",
                        about: "À propos",
                        features: "Fonctionnalités",
                        contact: "Contact",
                        login: "Se connecter",
                        assistant: "Assistant IA"
                    },
                    hero: {
                        title: "Justifly : Gérez vos absences universitaires avec élégance",
                        subtitle: "Une plateforme intuitive pour étudiants, enseignants et administrateurs.",
                        cta: "Démarrez maintenant"
                    },
                    features: {
                        title: "Fonctionnalités",
                        student: {
                            title: "Étudiants",
                            desc: "Soumettez et suivez vos absences en un clic."
                        },
                        teacher: {
                            title: "Enseignants",
                            desc: "Gérez les absences approuvées avec des rapports personnalisés."
                        },
                        admin: {
                            title: "Administrateurs",
                            desc: "Contrôlez les demandes et les utilisateurs efficacement."
                        }
                    },
                    video: {
                        title: "Justifly en action",
                        desc: "Découvrez comment Justifly transforme la gestion des absences."
                    },
                    testimonials: {
                        title: "Témoignages",
                        student: {
                            quote: "Justifly rend la gestion des absences tellement simple !",
                            role: "Étudiant en Informatique"
                        },
                        teacher: {
                            quote: "Un outil intuitif pour suivre les absences des étudiants.",
                            role: "Professeur M. Djalel"
                        },
                        admin: {
                            quote: "Gérer les demandes n'a jamais été aussi rapide.",
                            role: "Administrateur Université"
                        }
                    },
                    about: {
                        title: "À propos de nous",
                        text: "Ce projet est un projet de fin d'études pour les étudiants en informatique de l'Université d'Aïn Témouchent. Ce projet a été développé par trois étudiants :",
                        more: "En savoir plus"
                    },
                    contact: {
                        title: "Contactez-nous",
                        info: "Informations de contact",
                        address: "Université d'Aïn Témouchent, Algérie",
                        email: "contact@justifly.edu.dz",
                        phone: "+213 123 456 789",
                        follow: "Suivez-nous",
                        message: "Envoyez-nous un message",
                        name: "Nom complet",
                        emailPlaceholder: "Votre email",
                        messagePlaceholder: "Votre message",
                        send: "Envoyer"
                    },
                    footer: {
                        slogan: "La solution ultime pour la gestion des absences universitaires.",
                        quickLinks: "Liens rapides",
                        resources: "Ressources",
                        newsletter: "Newsletter",
                        newsletterPlaceholder: "Votre email",
                        copyright: "© 2025 Justifly. Tous droits réservés."
                    },
                    ai: {
                        greeting: "Bonjour ! Je suis l'assistant virtuel de Justifly. Comment puis-je vous aider aujourd'hui ?",
                        placeholder: "Posez votre question..."
                    }
                },
                ar: {
                    navbar: {
                        home: "الرئيسية",
                        about: "معلومات عنا",
                        features: "المميزات",
                        contact: "اتصل بنا",
                        login: "تسجيل الدخول",
                        assistant: "مساعد الذكاء الاصطناعي"
                    },
                    hero: {
                        title: "جاستيفلاي: إدارة الغيابات الجامعية بأناقة",
                        subtitle: "منصة سهلة للطلاب والمعلمين والمسؤولين.",
                        cta: "ابدأ الآن"
                    },
                    features: {
                        title: "المميزات",
                        student: {
                            title: "الطلاب",
                            desc: "قدّم وتتبع غياباتك بنقرة واحدة."
                        },
                        teacher: {
                            title: "المعلمون",
                            desc: "إدارة الغيابات المعتمدة مع تقارير مخصصة."
                        },
                        admin: {
                            title: "المسؤولون",
                            desc: "تحكم في الطلبات والمستخدمين بكفاءة."
                        }
                    },
                    video: {
                        title: "جاستيفلاي في العمل",
                        desc: "اكتشف كيف يحول جاستيفلاي إدارة الغيابات."
                    },
                    testimonials: {
                        title: "الشهادات",
                        student: {
                            quote: "جاستيفلاي يجعل إدارة الغيابات سهلة للغاية!",
                            role: "طالب في المعلوماتية"
                        },
                        teacher: {
                            quote: "أداة سهلة لمتابعة غيابات الطلاب.",
                            role: "الأستاذ مراد جلال"
                        },
                        admin: {
                            quote: "لم يكن إدارة الطلبات بهذه السرعة من قبل.",
                            role: "مسؤول الجامعة"
                        }
                    },
                    about: {
                        title: "معلومات عنا",
                        text: "هذا المشروع هو مشروع نهاية الدراسة لطلاب المعلوماتية في جامعة عين تموشنت. تم تطوير هذا المشروع من قبل ثلاثة طلاب:",
                        more: "معرفة المزيد"
                    },
                    contact: {
                        title: "اتصل بنا",
                        info: "معلومات الاتصال",
                        address: "جامعة عين تموشنت، الجزائر",
                        email: "contact@justifly.edu.dz",
                        phone: "+213 123 456 789",
                        follow: "تابعونا",
                        message: "أرسل لنا رسالة",
                        name: "الاسم الكامل",
                        emailPlaceholder: "بريدك الإلكتروني",
                        messagePlaceholder: "رسالتك",
                        send: "إرسال"
                    },
                    footer: {
                        slogan: "الحل الأمثل لإدارة الغيابات الجامعية.",
                        quickLinks: "روابط سريعة",
                        resources: "موارد",
                        newsletter: "النشرة الإخبارية",
                        newsletterPlaceholder: "بريدك الإلكتروني",
                        copyright: "© 2025 جاستيفلاي. جميع الحقوق محفوظة."
                    },
                    ai: {
                        greeting: "مرحباً! أنا المساعد الافتراضي لجاستيفلاي. كيف يمكنني مساعدتك اليوم؟",
                        placeholder: "اطرح سؤالك..."
                    }
                },
                en: {
                    navbar: {
                        home: "Home",
                        about: "About",
                        features: "Features",
                        contact: "Contact",
                        login: "Login",
                        assistant: "AI Assistant"
                    },
                    hero: {
                        title: "Justifly: Manage University Absences with Elegance",
                        subtitle: "An intuitive platform for students, teachers, and administrators.",
                        cta: "Get Started"
                    },
                    features: {
                        title: "Features",
                        student: {
                            title: "Students",
                            desc: "Submit and track your absences with one click."
                        },
                        teacher: {
                            title: "Teachers",
                            desc: "Manage approved absences with custom reports."
                        },
                        admin: {
                            title: "Administrators",
                            desc: "Control requests and users efficiently."
                        }
                    },
                    video: {
                        title: "Justifly in Action",
                        desc: "Discover how Justifly transforms absence management."
                    },
                    testimonials: {
                        title: "Testimonials",
                        student: {
                            quote: "Justifly makes absence management so simple!",
                            role: "Computer Science Student"
                        },
                        teacher: {
                            quote: "An intuitive tool to track student absences.",
                            role: "Prof. M. Djalel"
                        },
                        admin: {
                            quote: "Managing requests has never been faster.",
                            role: "University Administrator"
                        }
                    },
                    about: {
                        title: "About Us",
                        text: "This project is a graduation project for computer science students at Aïn Témouchent University. This project was developed by three students:",
                        more: "Learn More"
                    },
                    contact: {
                        title: "Contact Us",
                        info: "Contact Information",
                        address: "Aïn Témouchent University, Algeria",
                        email: "contact@justifly.edu.dz",
                        phone: "+213 123 456 789",
                        follow: "Follow Us",
                        message: "Send Us a Message",
                        name: "Full Name",
                        emailPlaceholder: "Your Email",
                        messagePlaceholder: "Your Message",
                        send: "Send"
                    },
                    footer: {
                        slogan: "The ultimate solution for university absence management.",
                        quickLinks: "Quick Links",
                        resources: "Resources",
                        newsletter: "Newsletter",
                        newsletterPlaceholder: "Your Email",
                        copyright: "© 2025 Justifly. All Rights Reserved."
                    },
                    ai: {
                        greeting: "Hello! I'm Justifly's virtual assistant. How can I help you today?",
                        placeholder: "Ask your question..."
                    }
                }
            };
            
            function changeLanguage(lang) {
                // Set HTML direction for RTL languages
                if (lang === 'ar') {
                    document.documentElement.dir = 'rtl';
                    document.documentElement.lang = 'ar';
                    document.body.classList.add('rtl');
                } else {
                    document.documentElement.dir = 'ltr';
                    document.documentElement.lang = lang;
                    document.body.classList.remove('rtl');
                }
                
                const translation = translations[lang];
                
                // Navbar
                document.querySelectorAll('a[href="#home"]').forEach(el => el.textContent = translation.navbar.home);
                document.querySelectorAll('a[href="#about"]').forEach(el => el.textContent = translation.navbar.about);
                document.querySelectorAll('a[href="#features"]').forEach(el => el.textContent = translation.navbar.features);
                document.querySelectorAll('a[href="#contact"]').forEach(el => el.textContent = translation.navbar.contact);
                document.querySelectorAll('a[href="login.php"]').forEach(el => el.textContent = translation.navbar.login);
                document.querySelectorAll('a[href="#ai-assistant"]').forEach(el => {
                    const icon = el.querySelector('i');
                    el.innerHTML = icon ? `<i class="${icon.className} mr-2"></i> ${translation.navbar.assistant}` : translation.navbar.assistant;
                });
                
                // Hero
                document.querySelector('#home h1').textContent = translation.hero.title;
                document.querySelector('#home p').textContent = translation.hero.subtitle;
                document.querySelector('#home button').textContent = translation.hero.cta;
                
                // Features
                document.querySelector('#features h2').textContent = translation.features.title;
                document.querySelectorAll('.flip-card')[0].querySelector('.flip-card-front h3').textContent = translation.features.student.title;
                document.querySelectorAll('.flip-card')[0].querySelector('.flip-card-back p').textContent = translation.features.student.desc;
                document.querySelectorAll('.flip-card')[1].querySelector('.flip-card-front h3').textContent = translation.features.teacher.title;
                document.querySelectorAll('.flip-card')[1].querySelector('.flip-card-back p').textContent = translation.features.teacher.desc;
                document.querySelectorAll('.flip-card')[2].querySelector('.flip-card-front h3').textContent = translation.features.admin.title;
                document.querySelectorAll('.flip-card')[2].querySelector('.flip-card-back p').textContent = translation.features.admin.desc;
                
                // Video
                document.querySelector('#video h2').textContent = translation.video.title;
                document.querySelector('#video p').textContent = translation.video.desc;
                
                // Testimonials
                document.querySelector('#testimonials h2').textContent = translation.testimonials.title;
                document.querySelectorAll('.carousel-item')[0].querySelector('p:nth-of-type(1)').textContent = translation.testimonials.student.quote;
                document.querySelectorAll('.carousel-item')[0].querySelector('p:nth-of-type(2)').textContent = translation.testimonials.student.role;
                document.querySelectorAll('.carousel-item')[1].querySelector('p:nth-of-type(1)').textContent = translation.testimonials.teacher.quote;
                document.querySelectorAll('.carousel-item')[1].querySelector('p:nth-of-type(2)').textContent = translation.testimonials.teacher.role;
                document.querySelectorAll('.carousel-item')[2].querySelector('p:nth-of-type(1)').textContent = translation.testimonials.admin.quote;
                document.querySelectorAll('.carousel-item')[2].querySelector('p:nth-of-type(2)').textContent = translation.testimonials.admin.role;
                
                // About
                document.querySelector('#about h2').textContent = translation.about.title;
                document.querySelector('#about .glassmorphism p:first-of-type').textContent = translation.about.text;
                document.querySelector('#about a').textContent = translation.about.more;
                
                // Contact
                document.querySelector('#contact h2').textContent = translation.contact.title;
                document.querySelector('#contact h3:first-of-type').textContent = translation.contact.info;
                document.querySelector('#contact ul li:first-of-type span').textContent = translation.contact.address;
                document.querySelector('#contact ul li:nth-of-type(2) span').textContent = translation.contact.email;
                document.querySelector('#contact ul li:nth-of-type(3) span').textContent = translation.contact.phone;
                document.querySelector('#contact h3:nth-of-type(2)').textContent = translation.contact.follow;
                document.querySelector('#contact h3:nth-of-type(3)').textContent = translation.contact.message;
                document.querySelector('#contact label[for="name"]').textContent = translation.contact.name;
                document.querySelector('#contact label[for="email"]').textContent = translation.contact.emailPlaceholder;
                document.querySelector('#contact label[for="message"]').textContent = translation.contact.messagePlaceholder;
                document.querySelector('#contact button[type="submit"]').textContent = translation.contact.send;
                
                // Footer
                document.querySelector('footer p:first-of-type').textContent = translation.footer.slogan;
                document.querySelector('footer h3:first-of-type').textContent = translation.footer.quickLinks;
                document.querySelector('footer h3:nth-of-type(2)').textContent = translation.footer.resources;
                document.querySelector('footer h3:nth-of-type(3)').textContent = translation.footer.newsletter;
                document.querySelector('footer input[type="email"]').placeholder = translation.footer.newsletterPlaceholder;
                document.querySelector('footer .border-t p').textContent = translation.footer.copyright;
                
                // AI Assistant
                const aiGreeting = document.querySelector('.chat-messages div p');
                if (aiGreeting) aiGreeting.textContent = translation.ai.greeting;
                const aiInput = document.querySelector('#ai-assistant-modal input');
                if (aiInput) aiInput.placeholder = translation.ai.placeholder;
            }
        });
    </script>
</body>
</html>
