
document.addEventListener('DOMContentLoaded', function() {
    // Navbar animation
    const navbar = document.querySelector('nav');
    navbar.style.transform = 'translateY(0)';
    
    // Mobile menu toggle
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');
    
    mobileMenuButton.addEventListener('click', function() {
        mobileMenu.classList.toggle('open');
        mobileMenu.classList.toggle('hidden');
    });
    
    // Close mobile menu when clicking outside
    document.addEventListener('click', function(event) {
        if (!mobileMenu.contains(event.target) && event.target !== mobileMenuButton) {
            mobileMenu.classList.remove('open');
            mobileMenu.classList.add('hidden');
        }
    });
    
    // Scroll animations
    const animateOnScroll = function() {
        const elements = document.querySelectorAll('.slide-in-left, .slide-in-right, .fade-in, .fade-in-up, .slide-in-stagger, .slide-in-left-stagger, .slide-in-right-stagger');
        
        elements.forEach(element => {
            const elementPosition = element.getBoundingClientRect().top;
            const screenPosition = window.innerHeight / 1.3;
            
            if (elementPosition < screenPosition) {
                element.style.opacity = '1';
                element.style.transform = 'translate(0, 0)';
            }
        });
        
        // Navbar shadow on scroll
        if (window.scrollY > 10) {
            navbar.classList.add('shadow-lg');
        } else {
            navbar.classList.remove('shadow-lg');
        }
    };
    
    // Counter animation for About Us section
    const counters = document.querySelectorAll('.counter');
    let countersAnimated = false;
    
    const animateCounters = function() {
        const aboutSection = document.getElementById('about');
        const sectionPosition = aboutSection.getBoundingClientRect().top;
        const screenPosition = window.innerHeight / 1.3;
        
        if (sectionPosition < screenPosition && !countersAnimated) {
            counters.forEach((counter, index) => {
                const target = parseInt(counter.getAttribute('data-count'));
                let count = 0;
                const duration = 3000; // 3 seconds
                const increment = target / (duration / 16); // Approx 60fps
                const startTime = performance.now();
                
                const updateCounter = (currentTime) => {
                    const elapsedTime = currentTime - startTime;
                    const progress = Math.min(elapsedTime / duration, 1);
                    const easedProgress = 1 - Math.pow(1 - progress, 3); // Ease-out effect
                    count = easedProgress * target;
                    
                    if (progress < 1) {
                        counter.textContent = Math.ceil(count);
                        requestAnimationFrame(updateCounter);
                    } else {
                        counter.textContent = target;
                    }
                };
                
                // Set CSS variable for animation delay
                counter.style.setProperty('--counter-delay', `${index * 0.2}s`);
                requestAnimationFrame(updateCounter);
            });
            countersAnimated = true; // Prevent re-animation
        }
    };
    
    // Set star animation delays for Testimonials
    const starContainers = document.querySelectorAll('.star-rating');
    starContainers.forEach(container => {
        const stars = container.querySelectorAll('i');
        stars.forEach((star, index) => {
            star.style.setProperty('--star-delay', `${index * 0.2}s`);
        });
    });
    
    window.addEventListener('scroll', () => {
        animateOnScroll();
        animateCounters();
    });
    animateOnScroll(); // Run once on load
    animateCounters(); // Check counters on load
    
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
    
    // Language selector functionality
    const languageLinks = document.querySelectorAll('.language-dropdown a');
    languageLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const lang = this.getAttribute('data-lang');
            localStorage.setItem('preferredLanguage', lang);
            
            // Update UI to show selected language
            const langText = this.textContent.trim();
            const flagClass = this.querySelector('span').className;
            
            const languageSelector = document.querySelector('.language-selector button');
            languageSelector.innerHTML = `<span class="${flagClass} mr-2"></span><span>${langText}</span><i class="fas fa-chevron-down text-xs"></i>`;
            
            // Hide dropdown
            document.querySelector('.language-dropdown').classList.add('hidden');
            
            // Update mobile selector
            const mobileSelect = document.querySelector('#mobile-menu select');
            if (mobileSelect) {
                mobileSelect.value = lang;
            }
            
            changeLanguage(lang);
        });
    });
    
    // Mobile language selector
    const mobileSelect = document.querySelector('#mobile-menu select');
    if (mobileSelect) {
        mobileSelect.addEventListener('change', function() {
            const lang = this.value;
            localStorage.setItem('preferredLanguage', lang);
            changeLanguage(lang);
            
            // Update desktop selector
            let flagClass, langText;
            switch (lang) {
                case 'fr':
                    flagClass = 'fi fi-fr';
                    langText = 'Français';
                    break;
                case 'ar':
                    flagClass = 'fi fi-sa';
                    langText = 'العربية';
                    break;
                case 'en':
                    flagClass = 'fi fi-gb';
                    langText = 'English';
                    break;
            }
            const languageSelector = document.querySelector('.language-selector button');
            if (languageSelector) {
                languageSelector.innerHTML = `<span class="${flagClass} mr-2"></span><span>${langText}</span><i class="fas fa-chevron-down text-xs"></i>`;
            }
        });
    }
    
    // Check for preferred language in localStorage
    const preferredLanguage = localStorage.getItem('preferredLanguage') || 'fr';
    if (preferredLanguage) {
        let flagClass, langText;
        
        switch(preferredLanguage) {
            case 'fr':
                flagClass = 'fi fi-fr';
                langText = 'Français';
                break;
            case 'ar':
                flagClass = 'fi fi-sa';
                langText = 'العربية';
                break;
            case 'en':
                flagClass = 'fi fi-gb';
                langText = 'English';
                break;
        }
        
        const languageSelector = document.querySelector('.language-selector button');
        if (languageSelector) {
            languageSelector.innerHTML = `<span class="${flagClass} mr-2"></span><span>${langText}</span><i class="fas fa-chevron-down text-xs"></i>`;
        }
        
        const mobileSelect = document.querySelector('#mobile-menu select');
        if (mobileSelect) {
            mobileSelect.value = preferredLanguage;
        }
        
        changeLanguage(preferredLanguage);
    }
    
    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            
            const targetId = this.getAttribute('href');
            if (targetId === '#') return;
            
            const targetElement = document.querySelector(targetId);
            if (targetElement) {
                window.scrollTo({
                    top: targetElement.offsetTop - 80,
                    behavior: 'smooth'
                });
                
                // Close mobile menu if open
                if (!mobileMenu.classList.contains('hidden')) {
                    mobileMenu.classList.remove('open');
                    mobileMenu.classList.add('hidden');
                }
            }
        });
    });
    
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
                subtitle: "Découvrez comment Justifly simplifie la gestion des absences pour tous les acteurs universitaires.",
                student: {
                    title: "Étudiants",
                    desc: "Soumettez et suivez vos absences en un clic avec une interface intuitive et conviviale.",
                    points: [
                        "Soumission rapide des justificatifs",
                        "Suivi en temps réel des demandes",
                        "Notifications instantanées"
                    ]
                },
                teacher: {
                    title: "Enseignants",
                    desc: "Gérez les absences approuvées avec des rapports personnalisés et des outils analytiques.",
                    points: [
                        "Validation simplifiée des absences",
                        "Génération automatique de rapports",
                        "Tableau de bord personnalisé"
                    ]
                },
                admin: {
                    title: "Administrateurs",
                    desc: "Contrôlez les demandes et les utilisateurs efficacement avec des outils puissants.",
                    points: [
                        "Gestion centralisée des utilisateurs",
                        "Statistiques détaillées",
                        "Export des données"
                    ]
                }
            },
            video: {
                title: "Justifly en action",
                desc: "Découvrez comment Justifly transforme la gestion des absences."
            },
            testimonials: {
                title: "Témoignages",
                subtitle: "Ce que nos utilisateurs disent de Justifly",
                student: {
                    quote: "Justifly a simplifié ma vie étudiante. Je peux maintenant soumettre mes justificatifs d'absence en quelques secondes et suivre leur statut en temps réel.",
                    name: "Amine Mouhoub",
                    role: "Étudiant en Informatique"
                },
                teacher: {
                    quote: "En tant qu'enseignant, Justifly m'a fait gagner un temps précieux. Je reçois les demandes directement dans mon espace et je peux les traiter rapidement avec toutes les informations nécessaires.",
                    name: "Prof. Djilali",
                    role: "Enseignant en Mathématiques"
                },
                admin: {
                    quote: "Le tableau de bord administratif de Justifly est exceptionnel. Je peux générer des rapports détaillés et avoir une vue d'ensemble des absences dans toute l'université.",
                    name: "Sara Amrani",
                    role: "Administratrice"
                }
            },
            about: {
                title: "À propos de nous",
                text: "Justifly est un projet de fin d'études développé par des étudiants en informatique de l'Université d'Aïn Témouchent, sous la supervision de Prof. Mered Djalel.",
                goal: "Notre objectif était de créer une solution numérique qui simplifie et modernise la gestion des absences dans les établissements universitaires, en répondant aux besoins des trois principaux acteurs : étudiants, enseignants et administrateurs.",
                team: "L'équipe de développement :",
                members: [
                    "Benyoub Imene - Développeuse Front-End",
                    "Fekih Naïma - Développeuse Back-End",
                    "Bouizem Assia - Développeuse Back-End"
                ],
                more: "En savoir plus"
            },
            contact: {
                title: "Contactez-nous",
                subtitle: "Vous avez des questions ou des suggestions ? Notre équipe est là pour vous aider.",
                info: "Informations de contact",
                address: "Université d'Aïn Témouchent, Département d'Informatique",
                email: "contact@justifly.edu.dz",
                phone: "+213 123 456 789",
                follow: "Suivez-nous",
                form: {
                    name: "Nom complet",
                    email: "Email",
                    subject: "Sujet",
                    message: "Message",
                    send: "Envoyer le message"
                }
            },
            ai: {
                title: "Assistant IA Justifly",
                desc: "Notre assistant intelligent peut répondre à vos questions en temps réel et vous guider dans l'utilisation de la plateforme.",
                cta: "Essayer l'assistant"
            },
            footer: {
                slogan: "La solution numérique pour la gestion des absences universitaires.",
                quickLinks: "Liens rapides",
                resources: "Ressources",
                legal: "Légal",
                copyright: "© 2025 Justifly. Tous droits réservés."
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
                subtitle: "اكتشف كيف يبسط جاستيفلاي إدارة الغيابات لجميع الأطراف الجامعية.",
                student: {
                    title: "الطلاب",
                    desc: "قدّم وتتبع غياباتك بنقرة واحدة مع واجهة سهلة ومريحة.",
                    points: [
                        "تقديم سريع للمبررات",
                        "تتبع الطلبات في الوقت الفعلي",
                        "إشعارات فورية"
                    ]
                },
                teacher: {
                    title: "المعلمون",
                    desc: "إدارة الغيابات المعتمدة مع تقارير مخصصة وأدوات تحليلية.",
                    points: [
                        "تأكيد مبسط للغيابات",
                        "إنشاء تقارير تلقائية",
                        "لوحة تحكم مخصصة"
                    ]
                },
                admin: {
                    title: "المسؤولون",
                    desc: "تحكم في الطلبات والمستخدمين بكفاءة مع أدوات قوية.",
                    points: [
                        "إدارة مركزية للمستخدمين",
                        "إحصائيات مفصلة",
                        "تصدير البيانات"
                    ]
                }
            },
            video: {
                title: "جاستيفلاي في العمل",
                desc: "اكتشف كيف يحول جاستيفلاي إدارة الغيابات."
            },
            testimonials: {
                title: "الشهادات",
                subtitle: "ماذا يقول مستخدمونا عن جاستيفلاي",
                student: {
                    quote: "جاستيفلاي جعل حياتي الجامعية أسهل. يمكنني الآن تقديم مبررات الغياب في ثوان ومتابعة حالتها في الوقت الفعلي.",
                    name: "أمين محوب",
                    role: "طالب في المعلوماتية"
                },
                teacher: {
                    quote: "كمعلم، جاستيفلاي وفر لي وقتاً ثميناً. أتلقى الطلبات مباشرة في مساحتي ويمكنني معالجتها بسرعة مع كل المعلومات اللازمة.",
                    name: "البروفيسور جيلالي",
                    role: "مدرس الرياضيات"
                },
                admin: {
                    quote: "لوحة التحكم الإدارية في جاستيفلاي رائعة. يمكنني إنشاء تقارير مفصلة والحصول على رؤية شاملة للغيابات في الجامعة بأكملها.",
                    name: "سارة عمراني",
                    role: "مسؤولة إدارية"
                }
            },
            about: {
                title: "معلومات عنا",
                text: "جاستيفلاي هو مشروع تخرج تم تطويره من قبل طلاب المعلوماتية في جامعة عين تموشنت، تحت إشراف الأستاذ مراد جلال.",
                goal: "كان هدفنا إنشاء حل رقمي يبسط ويحدث إدارة الغيابات في المؤسسات الجامعية، مع تلبية احتياجات الجهات الثلاث الرئيسية: الطلاب، المعلمون، والمسؤولون.",
                team: "فريق التطوير:",
                members: [
                    "بنيوب إيمان - مطورة الواجهة الأمامية",
                    "فقيه نعيمة - مطورة الخلفية",
                    "بوزيم آسيا - مطورة الخلفية"
                ],
                more: "معرفة المزيد"
            },
            contact: {
                title: "اتصل بنا",
                subtitle: "هل لديك أسئلة أو اقتراحات؟ فريقنا هنا لمساعدتك.",
                info: "معلومات الاتصال",
                address: "جامعة عين تموشنت، قسم المعلوماتية",
                email: "contact@justifly.edu.dz",
                phone: "+213 123 456 789",
                follow: "تابعونا",
                form: {
                    name: "الاسم الكامل",
                    email: "البريد الإلكتروني",
                    subject: "الموضوع",
                    message: "الرسالة",
                    send: "إرسال الرسالة"
                }
            },
            ai: {
                title: "مساعد جاستيفلاي الذكي",
                desc: "يمكن لمساعدنا الذكي الإجابة على أسئلتك في الوقت الفعلي وإرشادك في استخدام المنصة.",
                cta: "جرب المساعد"
            },
            footer: {
                slogan: "الحل الرقمي لإدارة الغيابات الجامعية.",
                quickLinks: "روابط سريعة",
                resources: "موارد",
                legal: "قانوني",
                copyright: "© 2025 جاستيفلاي. جميع الحقوق محفوظة."
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
                subtitle: "Discover how Justifly simplifies absence management for all university stakeholders.",
                student: {
                    title: "Students",
                    desc: "Submit and track your absences with one click using an intuitive and user-friendly interface.",
                    points: [
                        "Quick submission of justifications",
                        "Real-time request tracking",
                        "Instant notifications"
                    ]
                },
                teacher: {
                    title: "Teachers",
                    desc: "Manage approved absences with customized reports and analytical tools.",
                    points: [
                        "Simplified absence validation",
                        "Automatic report generation",
                        "Customized dashboard"
                    ]
                },
                admin: {
                    title: "Administrators",
                    desc: "Control requests and users efficiently with powerful tools.",
                    points: [
                        "Centralized user management",
                        "Detailed statistics",
                        "Data export"
                    ]
                }
            },
            video: {
                title: "Justifly in Action",
                desc: "Discover how Justifly transforms absence management."
            },
            testimonials: {
                title: "Testimonials",
                subtitle: "What our users say about Justifly",
                student: {
                    quote: "Justifly has simplified my student life. I can now submit absence justifications in seconds and track their status in real-time.",
                    name: "Amine Mouhoub",
                    role: "Computer Science Student"
                },
                teacher: {
                    quote: "As a teacher, Justifly has saved me precious time. I receive requests directly in my space and can process them quickly with all the necessary information.",
                    name: "Prof. Djilali",
                    role: "Mathematics Teacher"
                },
                admin: {
                    quote: "Justifly's administrative dashboard is outstanding. I can generate detailed reports and get a comprehensive view of absences across the entire university.",
                    name: "Sara Amrani",
                    role: "Administrator"
                }
            },
            about: {
                title: "About Us",
                text: "Justifly is a graduation project developed by computer science students at Aïn Témouchent University, under the supervision of Prof. Mered Djalel.",
                goal: "Our goal was to create a digital solution that simplifies and modernizes absence management in academic institutions, addressing the needs of three main stakeholders: students, teachers, and administrators.",
                team: "Development Team:",
                members: [
                    "Benyoub Imene - Front-End Developer",
                    "Fekih Naïma - Back-End Developer",
                    "Bouizem Assia - Back-End Developer"
                ],
                more: "Learn More"
            },
            contact: {
                title: "Contact Us",
                subtitle: "Have questions or suggestions? Our team is here to help.",
                info: "Contact Information",
                address: "Aïn Témouchent University, Department of Computer Science",
                email: "contact@justifly.edu.dz",
                phone: "+213 123 456 789",
                follow: "Follow Us",
                form: {
                    name: "Full Name",
                    email: "Email",
                    subject: "Subject",
                    message: "Message",
                    send: "Send Message"
                }
            },
            ai: {
                title: "Justifly AI Assistant",
                desc: "Our intelligent assistant can answer your questions in real-time and guide you through using the platform.",
                cta: "Try the Assistant"
            },
            footer: {
                slogan: "The digital solution for university absence management.",
                quickLinks: "Quick Links",
                resources: "Resources",
                legal: "Legal",
                copyright: "© 2025 Justifly. All Rights Reserved."
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
        document.querySelector('#features > div > p').textContent = translation.features.subtitle;
        document.querySelectorAll('.feature-card')[0].querySelector('h3').textContent = translation.features.student.title;
        document.querySelectorAll('.feature-card')[0].querySelector('p').textContent = translation.features.student.desc;
        document.querySelectorAll('.feature-card')[0].querySelectorAll('li span').forEach((span, index) => span.textContent = translation.features.student.points[index]);
        document.querySelectorAll('.feature-card')[1].querySelector('h3').textContent = translation.features.teacher.title;
        document.querySelectorAll('.feature-card')[1].querySelector('p').textContent = translation.features.teacher.desc;
        document.querySelectorAll('.feature-card')[1].querySelectorAll('li span').forEach((span, index) => span.textContent = translation.features.teacher.points[index]);
        document.querySelectorAll('.feature-card')[2].querySelector('h3').textContent = translation.features.admin.title;
        document.querySelectorAll('.feature-card')[2].querySelector('p').textContent = translation.features.admin.desc;
        document.querySelectorAll('.feature-card')[2].querySelectorAll('li span').forEach((span, index) => span.textContent = translation.features.admin.points[index]);
        
        // Video
        document.querySelector('#video h2').textContent = translation.video.title;
        document.querySelector('#video p').textContent = translation.video.desc;
        
        // Testimonials
        document.querySelector('#testimonials h2').textContent = translation.testimonials.title;
        document.querySelector('#testimonials > div > div > p').textContent = translation.testimonials.subtitle;
        document.querySelectorAll('.testimonial-card')[0].querySelector('p:nth-of-type(1)').textContent = translation.testimonials.student.quote;
        document.querySelectorAll('.testimonial-card')[0].querySelector('h4').textContent = translation.testimonials.student.name;
        document.querySelectorAll('.testimonial-card')[0].querySelector('p:nth-of-type(2)').textContent = translation.testimonials.student.role;
        document.querySelectorAll('.testimonial-card')[1].querySelector('p:nth-of-type(1)').textContent = translation.testimonials.teacher.quote;
        document.querySelectorAll('.testimonial-card')[1].querySelector('h4').textContent = translation.testimonials.teacher.name;
        document.querySelectorAll('.testimonial-card')[1].querySelector('p:nth-of-type(2)').textContent = translation.testimonials.teacher.role;
        document.querySelectorAll('.testimonial-card')[2].querySelector('p:nth-of-type(1)').textContent = translation.testimonials.admin.quote;
        document.querySelectorAll('.testimonial-card')[2].querySelector('h4').textContent = translation.testimonials.admin.name;
        document.querySelectorAll('.testimonial-card')[2].querySelector('p:nth-of-type(2)').textContent = translation.testimonials.admin.role;
        
        // About
        document.querySelector('#about h2').textContent = translation.about.title;
        document.querySelector('#about p:nth-of-type(1)').textContent = translation.about.text;
        document.querySelector('#about p:nth-of-type(2)').textContent = translation.about.goal;
        document.querySelector('#about h3').textContent = translation.about.team;
        document.querySelectorAll('#about ul li span').forEach((span, index) => span.textContent = translation.about.members[index]);
        document.querySelector('#about a').textContent = translation.about.more;
        
        // Contact
        document.querySelector('#contact h2').textContent = translation.contact.title;
        document.querySelector('#contact > div > div > p').textContent = translation.contact.subtitle;
        document.querySelector('#contact h3:nth-of-type(1)').textContent = translation.contact.info;
        document.querySelector('#contact .space-y-6 div:nth-of-type(1) p').textContent = translation.contact.address;
        document.querySelector('#contact .space-y-6 div:nth-of-type(2) p').textContent = translation.contact.email;
        document.querySelector('#contact .space-y-6 div:nth-of-type(3) p').textContent = translation.contact.phone;
        document.querySelector('#contact h4:nth-of-type(2)').textContent = translation.contact.follow;
        document.querySelector('#contact label[for="name"]').textContent = translation.contact.form.name;
        document.querySelector('#contact label[for="email"]').textContent = translation.contact.form.email;
        document.querySelector('#contact label[for="subject"]').textContent = translation.contact.form.subject;
        document.querySelector('#contact label[for="message"]').textContent = translation.contact.form.message;
        document.querySelector('#contact button[type="submit"]').textContent = translation.contact.form.send;
        
        // AI Assistant
        document.querySelector('#ai-assistant h3').textContent = translation.ai.title;
        document.querySelector('#ai-assistant p').textContent = translation.ai.desc;
        document.querySelector('#ai-assistant button').innerHTML = `<i class="fas fa-robot mr-2"></i> ${translation.ai.cta}`;
        
        // Footer
        document.querySelector('footer h4:nth-of-type(1)').textContent = translation.footer.slogan.split('.')[0];
        document.querySelector('footer h4:nth-of-type(2)').textContent = translation.footer.quickLinks;
        document.querySelector('footer h4:nth-of-type(3)').textContent = translation.footer.resources;
        document.querySelector('footer h4:nth-of-type(4)').textContent = translation.footer.legal;
        document.querySelector('footer .border-t p').textContent = translation.footer.copyright;
    }
});