<?php
// header.php
?>
<!DOCTYPE html>
<html lang="pt-BR" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TechFit - A evolução do seu treino</title>
    <link rel="icon" type="image/x-icon" href="media/images/brand/favicon-main.ico">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
        #mobile-menu-overlay { opacity: 0; transition: opacity 0.3s ease-in-out; }
        #mobile-menu-overlay.opacity-100 { opacity: 1; }
        .skeleton {
            background: linear-gradient(90deg, #e0e0e0 25%, #f5f5f5 50%, #e0e0e0 75%);
            background-size: 200% 100%;
            animation: shimmer 1.5s infinite;
            border-radius: inherit;
        }
        .skeleton-dark {
            background: linear-gradient(90deg, #2a2a2a 25%, #3a3a3a 50%, #2a2a2a 75%);
            background-size: 200% 100%;
            animation: shimmer 1.5s infinite;
            border-radius: inherit;
        }
        .skeleton-video {
            background: linear-gradient(90deg, #1a1a1a 25%, #2a2a2a 50%, #1a1a1a 75%);
            background-size: 200% 100%;
            animation: shimmer 1.5s infinite;
            border-radius: inherit;
        }
        @keyframes shimmer {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }
        .media-container { position: relative; overflow: hidden; }
        .media-container img, .media-container video { transition: opacity 0.3s ease; }
        .media-container.skeleton-active img, .media-container.skeleton-active video { opacity: 0; }
        .skeleton-overlay {
            position: absolute; top: 0; left: 0; width: 100%; height: 100%;
            z-index: 1; display: none;
        }
        .media-container.skeleton-active .skeleton-overlay { display: block; }
        .avatar-skeleton {
            width: 100%; height: 100%; border-radius: 50%;
            background: linear-gradient(90deg, #e0e0e0 25%, #f5f5f5 50%, #e0e0e0 75%);
            background-size: 200% 100%; animation: shimmer 1.5s infinite;
        }
        .perspective-1000 { perspective: 1000px; }
        .flip-card-inner {
            position: relative; width: 100%; height: 100%; text-align: center;
            transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
            transform-style: preserve-3d;
        }
        .flip-card.flipped .flip-card-inner { transform: rotateY(180deg); }
        .flip-card-front, .flip-card-back {
            position: absolute; width: 100%; height: 100%;
            -webkit-backface-visibility: hidden; backface-visibility: hidden;
            border-radius: 1.5rem; display: flex; flex-direction: column;
            align-items: center; justify-content: center; padding: 2rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        .flip-card-back { transform: rotateY(180deg); }
    </style>
</head>
<body class="font-sans text-gray-900 overflow-x-hidden bg-[#f4f4f4]">

    <!-- ================= NAVBAR / HEADER ================= -->
    <header id="main-header" class="fixed w-full z-50 transition-all duration-700 ease-in-out bg-transparent py-5">
        <div class="container mx-auto px-4 md:px-8 flex justify-between items-center relative">
            
            <!-- Logo (Esquerda) -->
            <div class="flex items-center cursor-pointer z-20">
                <div class="media-container h-8 md:h-10 w-auto">
                    <img src="media/images/brand/logo-light.webp" alt="TechFit" loading="lazy" decoding="async" class="h-8 md:h-10 w-auto" onerror="this.parentElement.classList.add('skeleton-active')">
                    <div class="skeleton-overlay">
                        <div class="skeleton h-8 md:h-10 w-32"></div>
                    </div>
                </div>
            </div>

            <!-- Nav Desktop (Centro Absoluto) -->
            <nav role="navigation" aria-label="Menu principal" class="hidden lg:flex items-center gap-8 text-white text-sm font-medium absolute left-1/2 top-1/2 transform -translate-x-1/2 -translate-y-1/2 w-auto justify-center">
                <a href="#" class="hover:text-[#0500ff] transition-colors whitespace-nowrap">Sobre</a>
                <a href="#" class="hover:text-[#0500ff] transition-colors whitespace-nowrap">Estrutura</a>
                <a href="#" class="hover:text-[#0500ff] transition-colors whitespace-nowrap">Planos</a>
            </nav>

            <!-- Ações Desktop (Direita: Login e CTA) -->
            <div class="hidden lg:flex items-center justify-end z-20">
                <div class="flex items-center gap-4 bg-transparent transition-all duration-300">
                    <a href="login.html" class="text-white hover:text-[#0500ff] font-medium text-sm transition-colors flex items-center gap-2 py-2 whitespace-nowrap">
                        <i data-lucide="user" class="w-5 h-5"></i>
                        <span>Login</span>
                    </a>

                    <!-- Botão CTA da Navbar (Animação de slide) -->
                    <div id="navbar-cta-wrapper" class="overflow-hidden transition-all duration-500 ease-in-out max-w-0 opacity-0">
                        <button type="button" class="bg-[#0500ff] hover:bg-[#0400cc] text-white font-bold px-6 py-2.5 rounded-full text-sm transition-transform hover:scale-105 whitespace-nowrap ml-2">
                            Matricule-se
                        </button>
                    </div>
                </div>
            </div>

            <!-- Botão Mobile (Hambúrguer) -->
            <button id="mobile-menu-btn" type="button" aria-label="Abrir menu" aria-expanded="false" class="lg:hidden text-white z-50 ml-auto">
                <i data-lucide="menu" class="w-7 h-7"></i>
            </button>
        </div>

        <!-- Menu Mobile (Off-canvas) -->
        <div id="mobile-menu-overlay" class="hidden fixed inset-0 bg-black/50 z-40 lg:hidden transition-opacity duration-300"></div>
        <div id="mobile-menu" class="hidden fixed top-0 right-0 h-screen w-80 bg-black border-l border-gray-800 flex flex-col p-6 gap-6 text-white overflow-y-auto z-50 transform translate-x-full transition-transform duration-300 ease-in-out">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold">Menu</h3>
                <button id="mobile-menu-close" type="button" aria-label="Fechar menu" class="text-gray-400 hover:text-white">
                    <i data-lucide="x" class="w-6 h-6"></i>
                </button>
            </div>
            
            <nav role="navigation" aria-label="Menu móvel" class="flex flex-col gap-4">
                <a href="#" class="text-lg font-medium hover:text-[#0500ff] transition-colors">Sobre</a>
                <a href="#" class="text-lg font-medium hover:text-[#0500ff] transition-colors">Estrutura</a>
                <a href="#" class="text-lg font-medium hover:text-[#0500ff] transition-colors">Planos</a>
            </nav>
            
            <hr class="border-gray-800" />
            
            <div class="flex flex-col gap-4">
                <a href="login.html" class="flex items-center gap-3 text-lg font-medium hover:text-[#0500ff] transition-colors">
                    <i data-lucide="user" class="w-5 h-5"></i> Login
                </a>
            </div>
            
            <button type="button" class="bg-[#0500ff] hover:bg-[#0400cc] text-white font-bold shadow-[0_0_15px_rgba(5,0,255,0.5)] w-full py-3 rounded-full text-center mt-auto transition-colors">
                Matricule-se
            </button>
        </div>
    </header>