<!DOCTYPE html>
<html lang="fr" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Justifly - Gestion des Absences Universitaires</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&family=Cairo:wght@400;600&family=Inter:wght@400;500;600;700&family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/particles.js/2.0.0/particles.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lottie-web/5.9.6/lottie.min.js"></script>
    <link rel="stylesheet" href="css/index.css">
</head>
<body class="bg-gray-50">
    <!-- Navbar -->
    <nav class="bg-blue-900 text-white shadow-lg fixed w-full z-50">
        <div class="container mx-auto px-4 py-3">
            <div class="flex justify-between items-center">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="#" class="flex items-center">
                        <i class="fas fa-calendar-check text-2xl text-orange-400 mr-2"></i>
                        <span class="text-xl font-bold font-amiri">Justifly</span>
                    </a>
                </div>
                
                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#home" class="nav-link">Accueil</a>
                    <a href="#about" class="nav-link">À propos</a>
                    <a href="#features" class="nav-link">Fonctionnalités</a>
                    <a href="#contact" class="nav-link">Contact</a>
                    <a href="login.php" class="bg-orange-400 hover:bg-orange-500 text-white px-4 py-2 rounded-lg transition duration-300">Se connecter</a>
                    <a href="#ai-assistant" class="flex items-center text-orange-400">
                        <i class="fas fa-robot mr-1"></i>
                        <span>Assistant IA</span>
                    </a>
                    <!-- Language Selector -->
                    <div class="language-selector relative">
                        <button class="flex items-center text-white">
                            <span class="fi fi-fr mr-2"></span>
                            <span>Français</span>
                            <i class="fas fa-chevron-down text-xs"></i>
                        </button>
                        <div class="language-dropdown hidden absolute right-0 mt-2 w-40 bg-white rounded-md shadow-lg z-50">
                            <a href="#" data-lang="fr" class="block px-4 py-2 text-gray-800 hover:bg-blue-100 flex items-center">
                                <span class="fi fi-fr mr-2"></span> Français
                            </a>
                            <a href="#" data-lang="ar" class="block px-4 py-2 text-gray-800 hover:bg-blue-100 flex items-center rtl">
                                <span class="fi fi-sa mr-2"></span> العربية
                            </a>
                            <a href="#" data-lang="en" class="block px-4 py-2 text-gray-800 hover:bg-blue-100 flex items-center">
                                <span class="fi fi-gb mr-2"></span> English
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Mobile Menu Button -->
                <div class="md:hidden flex items-center">
                    <button id="mobile-menu-button" class="outline-none mobile-menu-button text-amber-500 text-2xl">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>
            </div>
            
            <!-- Mobile Menu -->
            <div id="mobile-menu" class="mobile-menu hidden md:hidden bg-blue-900 bg-opacity-90 backdrop-blur-md mt-2 rounded-lg">
                <div class="px-2 pt-2 pb-3 space-y-6">
                    <a href="#home" class="block px-3 py-2 rounded-md text-base font-medium text-white hover:text-amber-500 transition-colors duration-300">Accueil</a>
                    <a href="#about" class="block px-3 py-2 rounded-md text-base font-medium text-white hover:text-amber-500 transition-colors duration-300">À propos</a>
                    <a href="#features" class="block px-3 py-2 rounded-md text-base font-medium text-white hover:text-amber-500 transition-colors duration-300">Fonctionnalités</a>
                    <a href="#contact" class="block px-3 py-2 rounded-md text-base font-medium text-white hover:text-amber-500 transition-colors duration-300">Contact</a>
                    <a href="login.php" class="block px-3 py-2 rounded-md text-base font-medium bg-amber-500 hover:bg-amber-600 text-white transition-all duration-300">Se connecter</a>
                    <a href="#ai-assistant" class="block px-3 py-2 rounded-md text-base font-medium bg-purple-500 hover:bg-purple-600 text-white transition-all duration-300 flex items-center">
                        <i class="fas fa-robot mr-2"></i> Assistant IA
                    </a>
                    
                    <!-- Mobile Language Selector -->
                    <div class="px-3 py-2">
                        <select class="block w-full bg-blue-700 text-white border border-blue-600 rounded-md py-2 px-3 focus:outline-none focus:ring-2 focus:ring-amber-500">
                            <option value="fr">Français</option>
                            <option value="ar">العربية</option>
                            <option value="en">English</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </nav>

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
    <section id="features" class="py-20 bg-white">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl md:text-4xl font-bold text-center text-blue-900 mb-4 fade-in-up">Fonctionnalités</h2>
            <p class="text-lg text-gray-600 text-center mb-16 max-w-2xl mx-auto fade-in-up">Découvrez comment Justifly simplifie la gestion des absences pour tous les acteurs universitaires</p>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Student Card -->
                <div class="feature-card glassmorphism rounded-xl shadow-lg p-8 transition duration-500 hover:border-amber-500 border-2 border-transparent slide-in-stagger" style="--stagger-delay: 0.1s;">
                    <div class="text-center mb-6">
                        <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4 icon-pulse">
                            <i class="fas fa-user-graduate text-3xl text-blue-900"></i>
                        </div>
                        <h3 class="text-xl font-bold text-blue-900 mb-2">Étudiants</h3>
                    </div>
                    <p class="text-gray-600 mb-6">Soumettez et suivez vos absences en un clic avec une interface intuitive et conviviale.</p>
                    <ul class="space-y-3 mb-6">
                        <li class="flex items-start">
                            <i class="fas fa-check text-green-500 mt-1 mr-2"></i>
                            <span>Soumission rapide des justificatifs</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check text-green-500 mt-1 mr-2"></i>
                            <span>Suivi en temps réel des demandes</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check text-green-500 mt-1 mr-2"></i>
                            <span>Notifications instantanées</span>
                        </li>
                    </ul>
                    <div class="text-center">
                        <a href="#" class="inline-block text-amber-500 font-medium hover:text-amber-600">En savoir plus →</a>
                    </div>
                </div>
                
                <!-- Teacher Card -->
                <div class="feature-card glassmorphism rounded-xl shadow-lg p-8 transition duration-500 hover:border-amber-500 border-2 border-transparent slide-in-stagger" style="--stagger-delay: 0.2s;">
                    <div class="text-center mb-6">
                        <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4 icon-pulse">
                            <i class="fas fa-chalkboard-teacher text-3xl text-green-600"></i>
                        </div>
                        <h3 class="text-xl font-bold text-blue-900 mb-2">Enseignants</h3>
                    </div>
                    <p class="text-gray-600 mb-6">Gérez les absences approuvées avec des rapports personnalisés et des outils analytiques.</p>
                    <ul class="space-y-3 mb-6">
                        <li class="flex items-start">
                            <i class="fas fa-check text-green-500 mt-1 mr-2"></i>
                            <span>Validation simplifiée des absences</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check text-green-500 mt-1 mr-2"></i>
                            <span>Génération automatique de rapports</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check text-green-500 mt-1 mr-2"></i>
                            <span>Tableau de bord personnalisé</span>
                        </li>
                    </ul>
                    <div class="text-center">
                        <a href="#" class="inline-block text-amber-500 font-medium hover:text-amber-600">En savoir plus →</a>
                    </div>
                </div>
                
                <!-- Admin Card -->
                <div class="feature-card glassmorphism rounded-xl shadow-lg p-8 transition duration-500 hover:border-amber-500 border-2 border-transparent slide-in-stagger" style="--stagger-delay: 0.3s;">
                    <div class="text-center mb-6">
                        <div class="w-20 h-20 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4 icon-pulse">
                            <i class="fas fa-user-shield text-3xl text-purple-600"></i>
                        </div>
                        <h3 class="text-xl font-bold text-blue-900 mb-2">Administrateurs</h3>
                    </div>
                    <p class="text-gray-600 mb-6">Contrôlez les demandes et les utilisateurs efficacement avec des outils puissants.</p>
                    <ul class="space-y-3 mb-6">
                        <li class="flex items-start">
                            <i class="fas fa-check text-green-500 mt-1 mr-2"></i>
                            <span>Gestion centralisée des utilisateurs</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check text-green-500 mt-1 mr-2"></i>
                            <span>Statistiques détaillées</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check text-green-500 mt-1 mr-2"></i>
                            <span>Export des données</span>
                        </li>
                    </ul>
                    <div class="text-center">
                        <a href="#" class="inline-block text-amber-500 font-medium hover:text-amber-600">En savoir plus →</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Video Section -->
    <section id="video" class="py-20 bg-gray-100">
        <div class="container mx-auto px-6">
            <h2 class="text-3xl font-bold text-center text-blue-900 mb-4 fade-in">Justifly en action</h2>
            <p class="text-lg text-gray-700 text-center mb-12 fade-in">Découvrez comment Justifly transforme la gestion des absences.</p>
            
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
    <section id="testimonials" class="py-20 bg-blue-900 text-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold mb-4 fade-in-up">Témoignages</h2>
                <p class="text-lg opacity-80 max-w-2xl mx-auto fade-in-up">Ce que nos utilisateurs disent de Justifly</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Testimonial 1 -->
                <div class="testimonial-card glassmorphism rounded-xl p-8 slide-in-left-stagger" style="--stagger-delay: 0.1s;">
                    <div class="flex items-center mb-6">
                        <div class="w-16 h-16 rounded-full bg-blue-700 flex items-center justify-center text-2xl font-bold mr-4">AM</div>
                        <div>
                            <h4 class="font-bold">Amine Mouhoub</h4>
                            <p class="text-blue-200 text-sm">Étudiant en Informatique</p>
                        </div>
                    </div>
                    <p class="mb-6">"Justifly a simplifié ma vie étudiante. Je peux maintenant soumettre mes justificatifs d'absence en quelques secondes et suivre leur statut en temps réel."</p>
                    <div class="flex text-yellow-400 star-rating">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        
                    </div>
                </div>
                
                <!-- Testimonial 2 -->
                <div class="testimonial-card glassmorphism rounded-xl p-8 fade-in-stagger" style="--stagger-delay: 0.2s;">
                    <div class="flex items-center mb-6">
                        <div class="w-16 h-16 rounded-full bg-blue-700 flex items-center justify-center text-2xl font-bold mr-4">PD</div>
                        <div>
                            <h4 class="font-bold">Prof. Djilali</h4>
                            <p class="text-blue-200 text-sm">Enseignant en Mathématiques</p>
                        </div>
                    </div>
                    <p class="mb-6">"En tant qu'enseignant, Justifly m'a fait gagner un temps précieux. Je recevois les demandes directement dans mon espace et je peux les traiter rapidement avec toutes les informations nécessaires."</p>
                    <div class="flex text-yellow-400 star-rating">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                    </div>
                </div>
                
                <!-- Testimonial 3 -->
                <div class="testimonial-card glassmorphism rounded-xl p-8 slide-in-right-stagger" style="--stagger-delay: 0.3s;">
                    <div class="flex items-center mb-6">
                        <div class="w-16 h-16 rounded-full bg-blue-700 flex items-center justify-center text-2xl font-bold mr-4">SA</div>
                        <div>
                            <h4 class="font-bold">Sara Amrani</h4>
                            <p class="text-blue-200 text-sm">Administratrice</p>
                        </div>
                    </div>
                    <p class="mb-6">"Le tableau de bord administratif de Justifly est exceptionnel. Je peux générer des rapports détaillés et avoir une vue d'ensemble des absences dans toute l'université."</p>
                    <div class="flex text-yellow-400 star-rating">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Us Section -->
    <section id="about" class="py-20 bg-white">
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row items-center">
                <div class="md:w-1/2 mb-10 md:mb-0 md:pr-10">
                    <h2 class="text-3xl md:text-4xl font-bold text-blue-900 mb-6 fade-in-up">À propos de nous</h2>
                    <p class="text-gray-600 mb-6 slide-in-left">Justifly est un projet de fin d'études développé par des étudiants en informatique de l'Université d'Aïn Témouchent, sous la supervision de Prof. Mered Djalel.</p>
                    <p class="text-gray-600 mb-8 slide-in-left">Notre objectif était de créer une solution numérique qui simplifie et modernise la gestion des absences dans les établissements universitaires, en répondant aux besoins des trois principaux acteurs : étudiants, enseignants et administrateurs.</p>
                    
                    <h3 class="text-xl font-bold text-blue-900 mb-4 fade-in-up">L'équipe de développement :</h3>
                    <ul class="space-y-3 mb-8">
                        <li class="flex items-center slide-in-stagger" style="--stagger-delay: 0.1s;">
                            <i class="fas fa-laptop-code text-amber-500 mr-3"></i>
                            <span>Benyoub Imene - Développeuse Front-End</span>
                        </li>
                        <li class="flex items-center slide-in-stagger" style="--stagger-delay: 0.2s;">
                            <i class="fas fa-server text-green-500 mr-3"></i>
                            <span>Fekih Naïma - Développeuse Back-End</span>
                        </li>
                        <li class="flex items-center slide-in-stagger" style="--stagger-delay: 0.3s;">
                            <i class="fas fa-database text-purple-500 mr-3"></i>
                            <span>Bouizem Assia - Développeuse Back-End</span>
                        </li>
                    </ul>
                    
                    <a href="#" class="inline-block bg-amber-500 hover:bg-amber-600 text-white font-bold py-3 px-8 rounded-full transition-all duration-300 transform hover:scale-105 fade-in">En savoir plus</a>
                </div>
                
                <div class="md:w-1/2">
                    <div class="glassmorphism rounded-xl p-8 shadow-lg slide-in-right">
                        <div class="grid grid-cols-2 gap-6">
                            <div class="bg-white p-6 rounded-lg shadow-md text-center">
                                <div class="text-4xl font-bold text-blue-900 mb-2 counter" data-count=" 500 "> 0 </div> +
                                <div class="text-gray-600">Étudiants actifs</div>
                            </div>
                            <div class="bg-white p-6 rounded-lg shadow-md text-center">
                                <div class="text-4xl font-bold text-blue-900 mb-2 counter" data-count="50">0</div> +
                                <div class="text-gray-600">Enseignants</div>
                            </div>
                            <div class="bg-white p-6 rounded-lg shadow-md text-center">
                                <div class="text-4xl font-bold text-blue-900 mb-2 counter" data-count="1000">0</div> +
                                <div class="text-gray-600">Demandes traitées</div>
                            </div>
                            <div class="bg-white p-6 rounded-lg shadow-md text-center">
                                <div class="text-4xl font-bold text-blue-900 mb-2 counter" data-count="24">0</div> +
                                <div class="text-gray-600">Temps moyen de réponse</div>
                            </div>
                        </div>
                        
                        <div class="mt-8 bg-white p-6 rounded-lg shadow-md">
                            <h4 class="font-bold text-blue-900 mb-3">Technologies utilisées :</h4>
                            <div class="flex flex-wrap gap-3">
                                <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm">HTML5</span>
                                <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm">CSS3</span>
                                <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm">JavaScript</span>
                                <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm">PHP</span>
                                <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm">MySQL</span>
                                <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm">Laravel</span>
                                <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm">Tailwind CSS</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="py-20 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-blue-900 mb-4 fade-in">Contactez-nous</h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto fade-in">Vous avez des questions ou des suggestions ? Notre équipe est là pour vous aider.</p>
            </div>
            
            <div class="max-w-4xl mx-auto glassmorphism rounded-xl shadow-lg overflow-hidden">
                <div class="md:flex">
                    <div class="md:w-1/2 bg-blue-900 text-white p-8">
                        <h3 class="text-xl font-bold mb-6 fade-in">Informations de contact</h3>
                        
                        <div class="space-y-6">
                            <div class="flex items-start slide-in-left">
                                <i class="fas fa-map-marker-alt text-amber-500 mt-1 mr-4"></i>
                                <div>
                                    <h4 class="font-bold">Adresse</h4>
                                    <p class="text-blue-200">Université d'Aïn Témouchent, Département d'Informatique</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start slide-in-left">
                                <i class="fas fa-envelope text-amber-500 mt-1 mr-4"></i>
                                <div>
                                    <h4 class="font-bold">Email</h4>
                                    <p class="text-blue-200">contact@justifly.edu.dz</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start slide-in-left">
                                <i class="fas fa-phone-alt text-amber-500 mt-1 mr-4"></i>
                                <div>
                                    <h4 class="font-bold">Téléphone</h4>
                                    <p class="text-blue-200">+213 123 456 789</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-10">
                            <h4 class="font-bold mb-4 fade-in">Suivez-nous</h4>
                            <div class="flex space-x-4">
                                <a href="#" class="w-10 h-10 rounded-full bg-blue-800 flex items-center justify-center hover:bg-amber-500 transition duration-300">
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                                <a href="#" class="w-10 h-10 rounded-full bg-blue-800 flex items-center justify-center hover:bg-amber-500 transition duration-300">
                                    <i class="fab fa-twitter"></i>
                                </a>
                                <a href="#" class="w-10 h-10 rounded-full bg-blue-800 flex items-center justify-center hover:bg-amber-500 transition duration-300">
                                    <i class="fab fa-linkedin-in"></i>
                                </a>
                                <a href="#" class="w-10 h-10 rounded-full bg-blue-800 flex items-center justify-center hover:bg-amber-500 transition duration-300">
                                    <i class="fab fa-instagram"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="md:w-1/2 p-8">
                        <form>
                            <div class="mb-6 slide-in-right">
                                <label for="name" class="block text-gray-700 font-medium mb-2">Nom complet</label>
                                <input type="text" id="name" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                            </div>
                            
                            <div class="mb-6 slide-in-right">
                                <label for="email" class="block text-gray-700 font-medium mb-2">Email</label>
                                <input type="email" id="email" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                            </div>
                            
                            <div class="mb-6 slide-in-right">
                                <label for="subject" class="block text-gray-700 font-medium mb-2">Sujet</label>
                                <input type="text" id="subject" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                            </div>
                            
                            <div class="mb-6 slide-in-right">
                                <label for="message" class="block text-gray-700 font-medium mb-2">Message</label>
                                <textarea id="message" rows="4" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent"></textarea>
                            </div>
                            
                            <button type="submit" class="w-full bg-amber-500 hover:bg-amber-600 text-white font-bold py-3 px-4 rounded-lg transition-all duration-300 transform hover:scale-105 fade-in">Envoyer le message</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- AI Assistant Banner -->
    <section id="ai-assistant" class="py-12 bg-gradient-to-r from-blue-900 to-blue-700 text-white">
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row items-center justify-between">
                <div class="md:w-2/3 mb-6 md:mb-0 slide-in-left">
                    <h3 class="text-2xl font-bold mb-2">Assistant IA Justifly</h3>
                    <p class="opacity-90">Notre assistant intelligent peut répondre à vos questions en temps réel et vous guider dans l'utilisation de la plateforme.</p>
                </div>
                <button class="bg-amber-500 hover:bg-amber-600 text-white font-bold py-3 px-8 rounded-lg transition-all duration-300 transform hover:scale-105 flex items-center pulse">
                    <i class="fas fa-robot mr-2"></i>
                    Essayer l'assistant
                </button>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-100 text-gray-700 pt-12 pb-6">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
                <div>
                    <h4 class="text-lg font-bold text-blue-900 mb-4">Justifly</h4>
                    <p class="mb-4">La solution numérique pour la gestion des absences universitaires.</p>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-500 hover:text-orange-400"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="text-gray-500 hover:text-orange-400"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-gray-500 hover:text-orange-400"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#" class="text-gray-500 hover:text-orange-400"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
                
                <div>
                    <h4 class="text-lg font-bold text-blue-900 mb-4">Liens rapides</h4>
                    <ul class="space-y-2">
                        <li><a href="#home" class="hover:text-orange-400 transition duration-300">Accueil</a></li>
                        <li><a href="#about" class="hover:text-orange-400 transition duration-300">À propos</a></li>
                        <li><a href="#features" class="hover:text-orange-400 transition duration-300">Fonctionnalités</a></li>
                        <li><a href="#contact" class="hover:text-orange-400 transition duration-300">Contact</a></li>
                        <li><a href="login.php" class="hover:text-orange-400 transition duration-300">Connexion</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="text-lg font-bold text-blue-900 mb-4">Ressources</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="hover:text-orange-400 transition duration-300">Documentation</a></li>
                        <li><a href="#" class="hover:text-orange-400 transition duration-300">FAQ</a></li>
                        <li><a href="#" class="hover:text-orange-400 transition duration-300">Centre d'aide</a></li>
                        <li><a href="#" class="hover:text-orange-400 transition duration-300">Blog</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="text-lg font-bold text-blue-900 mb-4">Légal</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="hover:text-orange-400 transition duration-300">Conditions d'utilisation</a></li>
                        <li><a href="#" class="hover:text-orange-400 transition duration-300">Politique de confidentialité</a></li>
                        <li><a href="#" class="hover:text-orange-400 transition duration-300">Cookies</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-gray-200 pt-6 flex flex-col md:flex-row justify-between items-center">
                <p class="mb-4 md:mb-0">© 2023 Justifly. Tous droits réservés.</p>
                
                <div class="flex items-center">
                    <span class="mr-3">Langue :</span>
                    <select class="bg-transparent border border-gray-300 rounded px-3 py-1 focus:outline-none focus:ring-2 focus:ring-orange-400">
                        <option value="fr">Français</option>
                        <option value="ar">العربية</option>
                        <option value="en">English</option>
                    </select>
                </div>
            </div>
        </div>
    </footer>

    <script src="js/index.js"></script>
</body>
</html>