<!DOCTYPE html>
<html lang="fr" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JustiFly - Tableau de bord Enseignant</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="icon" href="images/favicon.ico">
</head>
<body class="bg-gray-100 font-cairo">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <div class="hidden md:flex md:flex-shrink-0">
            <div class="flex flex-col w-64 bg-blue-900">
                <div class="flex items-center justify-center h-16 px-4 bg-blue-900 border-b border-blue-800">
                    <img src="images/logo.svg" alt="JustiFly Logo" class="h-8 mr-2">
                    <span class="text-white font-bold text-lg">JustiFly</span>
                </div>
                <div class="flex flex-col flex-grow pt-5 pb-4 overflow-y-auto">
                    <div class="flex items-center px-4 py-3 space-x-3">
                        <img class="h-10 w-10 rounded-full" src="https://via.placeholder.com/40" alt="Profile">
                        <div>
                            <p class="text-white font-medium">Prof. Martin</p>
                            <p class="text-blue-200 text-sm">Enseignant</p>
                        </div>
                    </div>
                    <div class="mt-5 flex-1">
                        <nav class="px-2 space-y-1">
                            <a href="#" class="bg-blue-800 text-white group flex items-center px-2 py-3 text-sm font-medium rounded-md">
                                <i class="fas fa-home mr-3 text-blue-300"></i>
                                Tableau de bord
                            </a>
                            <a href="#" class="text-blue-200 hover:bg-blue-800 hover:text-white group flex items-center px-2 py-3 text-sm font-medium rounded-md">
                                <i class="fas fa-list mr-3 text-blue-300"></i>
                                Demandes d'absence
                            </a>
                            <a href="#" class="text-blue-200 hover:bg-blue-800 hover:text-white group flex items-center px-2 py-3 text-sm font-medium rounded-md">
                                <i class="fas fa-users mr-3 text-blue-300"></i>
                                Mes étudiants
                            </a>
                            <a href="notifications.html" class="text-blue-200 hover:bg-blue-800 hover:text-white group flex items-center px-2 py-3 text-sm font-medium rounded-md">
                                <i class="fas fa-bell mr-3 text-blue-300"></i>
                                Notifications
                            </a>
                            <a href="profile.html" class="text-blue-200 hover:bg-blue-800 hover:text-white group flex items-center px-2 py-3 text-sm font-medium rounded-md">
                                <i class="fas fa-user mr-3 text-blue-300"></i>
                                Mon profil
                            </a>
                        </nav>
                    </div>
                </div>
                <div class="p-4 border-t border-blue-800">
                    <button class="flex items-center text-blue-200 hover:text-white text-sm font-medium w-full">
                        <i class="fas fa-sign-out-alt mr-2"></i>
                        Déconnexion
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile sidebar -->
        <div class="md:hidden">
            <div class="fixed inset-0 z-40">
                <div class="hidden fixed inset-0 bg-gray-600 bg-opacity-75" id="mobile-sidebar-backdrop"></div>
                <div class="hidden fixed inset-y-0 left-0 flex max-w-xs w-full" id="mobile-sidebar">
                    <div class="flex flex-col w-64 bg-blue-900">
                        <div class="flex items-center justify-between h-16 px-4 bg-blue-900 border-b border-blue-800">
                            <div class="flex items-center">
                                <img src="images/logo.svg" alt="JustiFly Logo" class="h-8 mr-2">
                                <span class="text-white font-bold text-lg">JustiFly</span>
                            </div>
                            <button class="text-white" id="close-mobile-sidebar">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <div class="flex flex-col flex-grow pt-5 pb-4 overflow-y-auto">
                            <div class="flex items-center px-4 py-3 space-x-3">
                                <img class="h-10 w-10 rounded-full" src="https://via.placeholder.com/40" alt="Profile">
                                <div>
                                    <p class="text-white font-medium">Prof. Martin</p>
                                    <p class="text-blue-200 text-sm">Enseignant</p>
                                </div>
                            </div>
                            <div class="mt-5 flex-1">
                                <nav class="px-2 space-y-1">
                                    <a href="#" class="bg-blue-800 text-white group flex items-center px-2 py-3 text-sm font-medium rounded-md">
                                        <i class="fas fa-home mr-3 text-blue-300"></i>
                                        Tableau de bord
                                    </a>
                                    <a href="#" class="text-blue-200 hover:bg-blue-800 hover:text-white group flex items-center px-2 py-3 text-sm font-medium rounded-md">
                                        <i class="fas fa-list mr-3 text-blue-300"></i>
                                        Demandes d'absence
                                    </a>
                                    <a href="#" class="text-blue-200 hover:bg-blue-800 hover:text-white group flex items-center px-2 py-3 text-sm font-medium rounded-md">
                                        <i class="fas fa-users mr-3 text-blue-300"></i>
                                        Mes étudiants
                                    </a>
                                    <a href="notifications.html" class="text-blue-200 hover:bg-blue-800 hover:text-white group flex items-center px-2 py-3 text-sm font-medium rounded-md">
                                        <i class="fas fa-bell mr-3 text-blue-300"></i>
                                        Notifications
                                    </a>
                                    <a href="profile.html" class="text-blue-200 hover:bg-blue-800 hover:text-white group flex items-center px-2 py-3 text-sm font-medium rounded-md">
                                        <i class="fas fa-user mr-3 text-blue-300"></i>
                                        Mon profil
                                    </a>
                                </nav>
                            </div>
                        </div>
                        <div class="p-4 border-t border-blue-800">
                            <button class="flex items-center text-blue-200 hover:text-white text-sm font-medium w-full">
                                <i class="fas fa-sign-out-alt mr-2"></i>
                                Déconnexion
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <div class="flex flex-col flex-1 overflow-hidden">
            <!-- Top navigation -->
            <div class="flex items-center justify-between h-16 bg-white border-b border-gray-200 px-4">
                <div class="flex items-center">
                    <button class="text-gray-500 md:hidden" id="open-mobile-sidebar">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h1 class="text-xl font-bold text-gray-800 ml-2 md:ml-4">Tableau de bord</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <button class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-bell"></i>
                    </button>
                    <div class="relative">
                        <button class="flex items-center space-x-2 focus:outline-none" id="user-menu-button">
                            <span class="text-gray-700">Prof. Martin</span>
                            <img class="h-8 w-8 rounded-full" src="https://via.placeholder.com/40" alt="Profile">
                        </button>
                        <div class="hidden origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none" id="user-menu">
                            <div class="py-1">
                                <a href="profile.html" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Mon profil</a>
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Paramètres</a>
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Déconnexion</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main content area -->
            <div class="flex-1 overflow-auto p-4 bg-gray-100">
                <div class="grid grid-cols-1 gap-6">
                    <!-- Stats cards -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                        <div class="bg-white rounded-lg shadow p-6">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-blue-100 text-blue-600 mr-4">
                                    <i class="fas fa-file-alt"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Demandes totales</p>
                                    <p class="text-2xl font-bold text-gray-800">47</p>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white rounded-lg shadow p-6">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-green-100 text-green-600 mr-4">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Approuvées</p>
                                    <p class="text-2xl font-bold text-gray-800">32</p>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white rounded-lg shadow p-6">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-orange-100 text-orange-600 mr-4">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">En attente</p>
                                    <p class="text-2xl font-bold text-gray-800">8</p>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white rounded-lg shadow p-6">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-red-100 text-red-600 mr-4">
                                    <i class="fas fa-times-circle"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Rejetées</p>
                                    <p class="text-2xl font-bold text-gray-800">7</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Absence requests table -->
                    <div class="bg-white rounded-lg shadow overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <div class="flex items-center justify-between">
                                <h2 class="text-xl font-bold text-gray-800">Demandes d'absence récentes</h2>
                                <div class="flex space-x-3">
                                    <div class="relative">
                                        <select class="appearance-none bg-white border border-gray-300 rounded-md pl-3 pr-8 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                            <option>Tous les cours</option>
                                            <option>Mathématiques</option>
                                            <option>Physique</option>
                                            <option>Chimie</option>
                                        </select>
                                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                            <i class="fas fa-chevron-down text-xs"></i>
                                        </div>
                                    </div>
                                    <div class="relative">
                                        <select class="appearance-none bg-white border border-gray-300 rounded-md pl-3 pr-8 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                            <option>Tous les statuts</option>
                                            <option>Approuvé</option>
                                            <option>En attente</option>
                                            <option>Rejeté</option>
                                        </select>
                                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                            <i class="fas fa-chevron-down text-xs"></i>
                                        </div>
                                    </div>
                                    <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium flex items-center">
                                        <i class="fas fa-download mr-2"></i>
                                        Exporter
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-blue-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Étudiant
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Cours
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Date
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Raison
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Statut
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    <img class="h-10 w-10 rounded-full" src="https://via.placeholder.com/40" alt="">
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">Marie Dupont</div>
                                                    <div class="text-sm text-gray-500">marie.dupont@univ.edu</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">Mathématiques</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">15 Mars 2023</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-900">Raison médicale</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Approuvé
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <button class="text-blue-600 hover:text-blue-900 mr-3">Voir</button>
                                            <button class="text-gray-600 hover:text-gray-900">Historique</button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    <img class="h-10 w-10 rounded-full" src="https://via.placeholder.com/40" alt="">
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">Jean Leroy</div>
                                                    <div class="text-sm text-gray-500">jean.leroy@univ.edu</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">Physique</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">14 Mars 2023</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-900">Problèmes de transport</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-orange-100 text-orange-800">
                                                En attente
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <button class="text-green-600 hover:text-green-900 mr-2">Approuver</button>
                                            <button class="text-red-600 hover:text-red-900 mr-2">Rejeter</button>
                                            <button class="text-blue-600 hover:text-blue-900">Voir</button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    <img class="h-10 w-10 rounded-full" src="https://via.placeholder.com/40" alt="">
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">Sophie Martin</div>
                                                    <div class="text-sm text-gray-500">sophie.martin@univ.edu</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">Chimie</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">12 Mars 2023</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-900">Raison familiale</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                Rejeté
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <button class="text-blue-600 hover:text-blue-900 mr-3">Voir</button>
                                            <button class="text-gray-600 hover:text-gray-900">Historique</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="bg-white px-6 py-3 border-t border-gray-200">
                            <div class="flex items-center justify-between">
                                <div class="text-sm text-gray-500">
                                    Affichage <span class="font-medium">1</span> à <span class="font-medium">3</span> sur <span class="font-medium">8</span> résultats
                                </div>
                                <div class="flex space-x-2">
                                    <button class="px-3 py-1 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                        Précédent
                                    </button>
                                    <button class="px-3 py-1 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                        Suivant
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="js/main.js"></script>
</body>
</html>