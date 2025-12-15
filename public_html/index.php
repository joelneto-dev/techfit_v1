<?php
// main.php
require 'header.php';
?>

    <section class="relative min-h-screen flex items-center pt-20 bg-black text-white overflow-hidden">
        <div class="absolute inset-0 z-0">
            <div class="media-container w-full h-full">
                <img src="media/images/index/main-bg.webp" alt="Gym Background" class="w-full h-full object-cover opacity-80" loading="eager" decoding="async" width="1920" height="1080" onerror="this.parentElement.classList.add('skeleton-active')">
                <div class="skeleton-overlay">
                    <div class="skeleton-dark w-full h-full"></div>
                </div>
            </div>
            <div class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-black/60"></div>
            <div class="absolute inset-0 bg-gradient-to-r from-black via-black/50 to-transparent"></div>
        </div>

        <div class="container mx-auto px-4 md:px-8 relative z-10 flex flex-col md:flex-row items-center">
            <div class="w-full md:w-2/3 lg:w-1/2 space-y-6 pt-10 md:pt-0">
                <h1 class="text-4xl md:text-6xl lg:text-7xl font-bold leading-tight">
                    A evolução do <br/>
                    <span class="text-[#0500ff]">seu treino.</span>
                </h1>
                
                <p class="text-lg text-gray-300 max-w-lg">
                    Muito mais que aparelhos. Uma academia completa com estrutura de ponta, ambiente motivador e profissionais que realmente acompanham sua evolução.
                </p>
                
                <div class="flex flex-col sm:flex-row gap-4 pt-4">
                    <button id="hero-cta" type="button" class="bg-[#0500ff] hover:bg-[#0400cc] text-white font-bold shadow-[0_0_15px_rgba(5,0,255,0.5)] px-8 py-4 rounded-full text-base transition-transform hover:scale-105 flex items-center justify-center gap-2">
                        Matricule-se Agora
                    </button>
                </div>
                
                <div class="pt-8 flex items-center gap-4 text-sm text-gray-400">
                    <div class="flex -space-x-3">
                        <div class="w-10 h-10 rounded-full border-2 border-black bg-pink-500 overflow-hidden">
                            <div class="media-container w-full h-full">
                                <img src="https://api.dicebear.com/9.x/avataaars/svg?seed=Felix" alt="Member" loading="lazy" decoding="async" class="w-full h-full object-cover" onerror="this.parentElement.classList.add('skeleton-active')">
                                <div class="skeleton-overlay">
                                    <div class="avatar-skeleton"></div>
                                </div>
                            </div>
                        </div>
                        <div class="w-10 h-10 rounded-full border-2 border-black bg-cyan-400 overflow-hidden">
                            <div class="media-container w-full h-full">
                                <img src="https://api.dicebear.com/9.x/avataaars/svg?seed=Aneka" alt="Member" loading="lazy" decoding="async" class="w-full h-full object-cover" onerror="this.parentElement.classList.add('skeleton-active')">
                                <div class="skeleton-overlay">
                                    <div class="avatar-skeleton"></div>
                                </div>
                            </div>
                        </div>
                        <div class="w-10 h-10 rounded-full border-2 border-black bg-yellow-400 overflow-hidden">
                            <div class="media-container w-full h-full">
                                <img src="https://api.dicebear.com/9.x/avataaars/svg?seed=Josy" alt="Member" loading="lazy" decoding="async" class="w-full h-full object-cover" onerror="this.parentElement.classList.add('skeleton-active')">
                                <div class="skeleton-overlay">
                                    <div class="avatar-skeleton"></div>
                                </div>
                            </div>
                        </div>
                        <div class="w-10 h-10 rounded-full border-2 border-black bg-green-500 overflow-hidden">
                            <div class="media-container w-full h-full">
                                <img src="https://api.dicebear.com/9.x/avataaars/svg?seed=Tyler" alt="Member" loading="lazy" decoding="async" class="w-full h-full object-cover" onerror="this.parentElement.classList.add('skeleton-active')">
                                <div class="skeleton-overlay">
                                    <div class="avatar-skeleton"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <p>Mais de <span class="text-white font-bold">12 mil</span> alunos ativos.</p>
                </div>
            </div>
        </div>
        
        <div class="absolute -bottom-20 -right-20 w-96 h-96 bg-[#0500ff] rounded-full blur-[150px] opacity-40 pointer-events-none"></div>
    </section>

    <section class="bg-black py-10 border-b border-gray-800">
        <div class="container mx-auto px-4 mb-6">
            <p class="text-center text-gray-500 uppercase tracking-widest text-xs font-bold">Equipamentos de alta performance</p>
        </div>
        <div class="flex justify-center flex-wrap gap-10 md:gap-16 items-center px-4">
            <a href="https://www.lifefitness.com" target="_blank" rel="noopener noreferrer" class="transition-transform hover:scale-105" aria-label="Visitar site da Life Fitness">
                <img src="media/images/index/life_fitness.svg" alt="Life Fitness" loading="lazy" class="h-5 md:h-7 w-auto brightness-0 invert opacity-60 hover:opacity-100 transition-opacity duration-300">
            </a>
            <a href="https://www.lifefitness.com/en-us/hammer-strength" target="_blank" rel="noopener noreferrer" class="transition-transform hover:scale-105" aria-label="Visitar site da Hammer Strength">
                <img src="media/images/index/hammer_strength.svg" alt="Hammer Strength" loading="lazy" class="h-6 md:h-8 w-auto brightness-0 invert opacity-60 hover:opacity-100 transition-opacity duration-300">
            </a>
            <a href="https://www.roguefitness.com" target="_blank" rel="noopener noreferrer" class="transition-transform hover:scale-105" aria-label="Visitar site da Rogue Fitness">
                <img src="media/images/index/rogue.svg" alt="Rogue" loading="lazy" class="h-6 md:h-8 w-auto brightness-0 invert opacity-60 hover:opacity-100 transition-opacity duration-300">
            </a>
            <a href="https://eleiko.com" target="_blank" rel="noopener noreferrer" class="transition-transform hover:scale-105" aria-label="Visitar site da Eleiko">
                <img src="media/images/index/eleiko.svg" alt="Eleiko" loading="lazy" class="h-5 md:h-7 w-auto brightness-0 invert opacity-60 hover:opacity-100 transition-opacity duration-300">
            </a>
            <a href="https://www.keiser.com" target="_blank" rel="noopener noreferrer" class="transition-transform hover:scale-105" aria-label="Visitar site da Keiser">
                <img src="media/images/index/keiser.svg" alt="Keiser" loading="lazy" class="h-6 md:h-8 w-auto brightness-0 invert opacity-60 hover:opacity-100 transition-opacity duration-300">
            </a>
            <a href="https://www.precor.com" target="_blank" rel="noopener noreferrer" class="transition-transform hover:scale-105" aria-label="Visitar site da Precor">
                <img src="media/images/index/precor.svg" alt="Precor" loading="lazy" class="h-5 md:h-7 w-auto brightness-0 invert opacity-60 hover:opacity-100 transition-opacity duration-300">
            </a>
            <a href="https://www.matrixfitness.com" target="_blank" rel="noopener noreferrer" class="transition-transform hover:scale-105" aria-label="Visitar site da Matrix">
                <img src="media/images/index/matrix.svg" alt="Matrix" loading="lazy" class="h-6 md:h-8 w-auto brightness-0 invert opacity-60 hover:opacity-100 transition-opacity duration-300">
            </a>
        </div>
    </section>

    <section class="bg-white py-20">
        <div class="container mx-auto px-4 md:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <h2 class="text-3xl md:text-5xl font-bold mb-6 text-black">Motivos para treinar na <span class="text-[#0500ff]">TechFit?</span></h2>
                <p class="text-gray-600 text-lg">Toque nos cards para descobrir.</p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <div class="flip-card group h-80 perspective-1000 cursor-pointer" onclick="toggleCard(this)">
                    <div class="flip-card-inner relative w-full h-full">
                        <div class="flip-card-front bg-black border border-gray-900 shadow-xl">
                            <div class="text-white font-black text-9xl tracking-tighter">1</div>
                            <div class="absolute bottom-6 right-6 text-white opacity-60">
                                <i data-lucide="rotate-cw" class="w-6 h-6"></i>
                            </div>
                        </div>
                        <div class="flip-card-back bg-gray-50 text-gray-900 border border-gray-200 px-6">
                            <h3 class="text-2xl font-bold mb-4 text-[#0500ff] text-center">Instrutores</h3>
                            <p class="text-gray-600 leading-relaxed text-center text-sm md:text-base">
                                Aqui você não treina sozinho. Nossa equipe está sempre no salão para corrigir sua postura, ajustar cargas e motivar cada série.
                            </p>
                            <div class="absolute bottom-6 left-6 text-gray-400 opacity-60">
                                <i data-lucide="rotate-cw" class="w-6 h-6"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flip-card group h-80 perspective-1000 cursor-pointer" onclick="toggleCard(this)">
                    <div class="flip-card-inner relative w-full h-full">
                        <div class="flip-card-front bg-black border border-gray-900 shadow-xl">
                            <div class="text-white font-black text-9xl tracking-tighter">2</div>
                            <div class="absolute bottom-6 right-6 text-white opacity-60">
                                <i data-lucide="rotate-cw" class="w-6 h-6"></i>
                            </div>
                        </div>
                        <div class="flip-card-back bg-gray-50 text-gray-900 border border-gray-200 px-6">
                            <h3 class="text-2xl font-bold mb-4 text-[#0500ff] text-center">Ambiente Exclusivo</h3>
                            <p class="text-gray-600 leading-relaxed text-center text-sm md:text-base">
                                Chega de academias monótonas. Unimos design moderno, iluminação planejada e a melhor playlist para dar um gás extra no seu treino.
                            </p>
                            <div class="absolute bottom-6 left-6 text-gray-400 opacity-60">
                                <i data-lucide="rotate-cw" class="w-6 h-6"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flip-card group h-80 perspective-1000 cursor-pointer" onclick="toggleCard(this)">
                    <div class="flip-card-inner relative w-full h-full">
                        <div class="flip-card-front bg-black border border-gray-900 shadow-xl">
                            <div class="text-white font-black text-9xl tracking-tighter">3</div>
                            <div class="absolute bottom-6 right-6 text-white opacity-60">
                                <i data-lucide="rotate-cw" class="w-6 h-6"></i>
                            </div>
                        </div>
                        <div class="flip-card-back bg-gray-50 text-gray-900 border border-gray-200 px-6">
                            <h3 class="text-2xl font-bold mb-4 text-[#0500ff] text-center">Estrutura Completa</h3>
                            <p class="text-gray-600 leading-relaxed text-center text-sm md:text-base">
                                Variedade de equipamentos de força e cardio das melhores marcas do mundo. Treine com conforto e sem perder tempo em filas.
                            </p>
                            <div class="absolute bottom-6 left-6 text-gray-400 opacity-60">
                                <i data-lucide="rotate-cw" class="w-6 h-6"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flip-card group h-80 perspective-1000 cursor-pointer" onclick="toggleCard(this)">
                    <div class="flip-card-inner relative w-full h-full">
                        <div class="flip-card-front bg-black border border-gray-900 shadow-xl">
                            <div class="text-white font-black text-9xl tracking-tighter">4</div>
                            <div class="absolute bottom-6 right-6 text-white opacity-60">
                                <i data-lucide="rotate-cw" class="w-6 h-6"></i>
                            </div>
                        </div>
                        <div class="flip-card-back bg-gray-50 text-gray-900 border border-gray-200 px-6">
                            <h3 class="text-2xl font-bold mb-4 text-[#0500ff] text-center">Sem Fidelidade</h3>
                            <p class="text-gray-600 leading-relaxed text-center text-sm md:text-base">
                                Liberdade total para sua rotina. Cancele ou congele seu plano quando quiser, sem multas ou letras miúdas.
                            </p>
                            <div class="absolute bottom-6 left-6 text-gray-400 opacity-60">
                                <i data-lucide="rotate-cw" class="w-6 h-6"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flip-card group h-80 perspective-1000 cursor-pointer" onclick="toggleCard(this)">
                    <div class="flip-card-inner relative w-full h-full">
                        <div class="flip-card-front bg-black border border-gray-900 shadow-xl">
                            <div class="text-white font-black text-9xl tracking-tighter">5</div>
                            <div class="absolute bottom-6 right-6 text-white opacity-60">
                                <i data-lucide="rotate-cw" class="w-6 h-6"></i>
                            </div>
                        </div>
                        <div class="flip-card-back bg-gray-50 text-gray-900 border border-gray-200 px-6">
                            <h3 class="text-2xl font-bold mb-4 text-[#0500ff] text-center">Aulas Inclusas</h3>
                            <p class="text-gray-600 leading-relaxed text-center text-sm md:text-base">
                                Yoga, Spinning, FitDance e muito mais. Todas as aulas coletivas já estão inclusas no seu plano, sem custo extra.
                            </p>
                            <div class="absolute bottom-6 left-6 text-gray-400 opacity-60">
                                <i data-lucide="rotate-cw" class="w-6 h-6"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flip-card group h-80 perspective-1000 cursor-pointer" onclick="toggleCard(this)">
                    <div class="flip-card-inner relative w-full h-full">
                        <div class="flip-card-front bg-black border border-gray-900 shadow-xl">
                            <div class="text-white font-black text-9xl tracking-tighter">6</div>
                            <div class="absolute bottom-6 right-6 text-white opacity-60">
                                <i data-lucide="rotate-cw" class="w-6 h-6"></i>
                            </div>
                        </div>
                        <div class="flip-card-back bg-[#0500ff] text-white border border-[#0500ff] px-6">
                            <h3 class="text-3xl font-black mb-0 text-white text-center">Precisa de mais?</h3>
                            <div class="absolute bottom-6 left-6 text-white opacity-60">
                                <i data-lucide="rotate-cw" class="w-6 h-6"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-black py-20 relative">
        <div class="relative">
            <div class="pl-4 md:pl-8 lg:pl-[calc((100vw-1280px)/2+2rem)] xl:pl-[calc((100vw-1536px)/2+2rem)]">
                <div class="relative">
                    <div class="absolute left-0 top-0 z-20 h-[320px] flex pointer-events-none bg-black">
                        <div class="w-[240px] md:w-[320px] h-full bg-[#0500ff] rounded-2xl p-8 flex flex-col justify-center shadow-2xl overflow-hidden group cursor-pointer hover:scale-[1.02] transition-transform pointer-events-auto relative">
                            <div class="absolute top-0 right-0 w-32 h-32 bg-white opacity-10 rounded-full blur-2xl -mr-10 -mt-10"></div>
                            <div class="absolute bottom-0 left-0 w-24 h-24 bg-black opacity-10 rounded-full blur-xl -ml-10 -mb-10"></div>
                            <h3 class="text-3xl md:text-4xl font-bold text-white mb-4 leading-tight">
                                Mais de 30<br/>modalidades
                            </h3>
                            <div class="flex items-center gap-2 text-white font-bold mt-auto group-hover:translate-x-2 transition-transform">
                                <span>Ver disponibilidade</span>
                                <i data-lucide="arrow-right" class="w-5 h-5"></i>
                            </div>
                        </div>
                        <div class="w-4 md:w-6 h-full bg-black"></div>
                    </div>

                    <div id="modalities-container" class="flex overflow-x-auto gap-4 md:gap-6 pb-8 pr-[260px] md:pr-[344px] scrollbar-hide relative" style="-ms-overflow-style: none; scrollbar-width: none; scroll-behavior: auto;">
                        <div class="min-w-[240px] md:min-w-[320px] h-[320px] bg-white rounded-2xl overflow-hidden shrink-0 snap-start flex flex-col group cursor-pointer hover:shadow-[0_0_20px_rgba(255,255,255,0.1)] transition-all"></div>
                        <div class="min-w-[240px] md:min-w-[320px] h-[320px] bg-white rounded-2xl overflow-hidden shrink-0 flex flex-col group cursor-pointer hover:shadow-[0_0_20px_rgba(255,255,255,0.1)] transition-shadow duration-200">
                            <div class="h-[75%] overflow-hidden relative">
                                <div class="media-container w-full h-full">
                                    <img src="media/images/index/cards/musculacao.webp" alt="Musculação" class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-filter duration-300" loading="lazy" decoding="async" width="320" height="240" onerror="this.parentElement.classList.add('skeleton-active')" />
                                    <div class="skeleton-overlay">
                                        <div class="skeleton w-full h-full"></div>
                                    </div>
                                </div>
                                <div class="absolute inset-0 opacity-[0.15] pointer-events-none" style="background-image: url('data:image/svg+xml,%3Csvg viewBox=%220 0 400 400%22 xmlns=%22http://www.w3.org/2000/svg%22%3E%3Cfilter id=%22noiseFilter%22%3E%3CfeTurbulence type=%22fractalNoise%22 baseFrequency=%220.9%22 numOctaves=%224%22 stitchTiles=%22stitch%22/%3E%3C/filter%3E%3Crect width=%22100%25%22 height=%22100%25%22 filter=%22url(%23noiseFilter)%22/%3E%3C/svg%3E');"></div>
                            </div>
                            <div class="h-[25%] flex items-center px-6 bg-white">
                                <h4 class="text-black font-bold text-xl group-hover:text-[#0500ff] transition-colors duration-200">Musculação</h4>
                            </div>
                        </div>
                        <div class="min-w-[240px] md:min-w-[320px] h-[320px] bg-white rounded-2xl overflow-hidden shrink-0 flex flex-col group cursor-pointer hover:shadow-[0_0_20px_rgba(255,255,255,0.1)] transition-shadow duration-200">
                            <div class="h-[75%] overflow-hidden relative">
                                <div class="media-container w-full h-full">
                                    <img src="media/images/index/cards/pilates.webp" alt="Pilates" class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-filter duration-300" onerror="this.parentElement.classList.add('skeleton-active')" />
                                    <div class="skeleton-overlay">
                                        <div class="skeleton w-full h-full"></div>
                                    </div>
                                </div>
                                <div class="absolute inset-0 opacity-[0.15] pointer-events-none" style="background-image: url('data:image/svg+xml,%3Csvg viewBox=%220 0 400 400%22 xmlns=%22http://www.w3.org/2000/svg%22%3E%3Cfilter id=%22noiseFilter%22%3E%3CfeTurbulence type=%22fractalNoise%22 baseFrequency=%220.9%22 numOctaves=%224%22 stitchTiles=%22stitch%22/%3E%3C/filter%3E%3Crect width=%22100%25%22 height=%22100%25%22 filter=%22url(%23noiseFilter)%22/%3E%3C/svg%3E');"></div>
                            </div>
                            <div class="h-[25%] flex items-center px-6 bg-white">
                                <h4 class="text-black font-bold text-xl group-hover:text-[#0500ff] transition-colors duration-200">Pilates</h4>
                            </div>
                        </div>
                        <div class="min-w-[240px] md:min-w-[320px] h-[320px] bg-white rounded-2xl overflow-hidden shrink-0 flex flex-col group cursor-pointer hover:shadow-[0_0_20px_rgba(255,255,255,0.1)] transition-shadow duration-200">
                            <div class="h-[75%] overflow-hidden relative">
                                <div class="media-container w-full h-full">
                                    <img src="media/images/index/cards/natacao.webp" alt="Natação" class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-filter duration-300" onerror="this.parentElement.classList.add('skeleton-active')" />
                                    <div class="skeleton-overlay">
                                        <div class="skeleton w-full h-full"></div>
                                    </div>
                                </div>
                                <div class="absolute inset-0 opacity-[0.15] pointer-events-none" style="background-image: url('data:image/svg+xml,%3Csvg viewBox=%220 0 400 400%22 xmlns=%22http://www.w3.org/2000/svg%22%3E%3Cfilter id=%22noiseFilter%22%3E%3CfeTurbulence type=%22fractalNoise%22 baseFrequency=%220.9%22 numOctaves=%224%22 stitchTiles=%22stitch%22/%3E%3C/filter%3E%3Crect width=%22100%25%22 height=%22100%25%22 filter=%22url(%23noiseFilter)%22/%3E%3C/svg%3E');"></div>
                            </div>
                            <div class="h-[25%] flex items-center px-6 bg-white">
                                <h4 class="text-black font-bold text-xl group-hover:text-[#0500ff] transition-colors duration-200">Natação</h4>
                            </div>
                        </div>
                        <div class="min-w-[240px] md:min-w-[320px] h-[320px] bg-white rounded-2xl overflow-hidden shrink-0 flex flex-col group cursor-pointer hover:shadow-[0_0_20px_rgba(255,255,255,0.1)] transition-shadow duration-200">
                            <div class="h-[75%] overflow-hidden relative">
                                <div class="media-container w-full h-full">
                                    <img src="media/images/index/cards/cross_training.webp" alt="Cross Training" class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-filter duration-300" onerror="this.parentElement.classList.add('skeleton-active')" />
                                    <div class="skeleton-overlay">
                                        <div class="skeleton w-full h-full"></div>
                                    </div>
                                </div>
                                <div class="absolute inset-0 opacity-[0.15] pointer-events-none" style="background-image: url('data:image/svg+xml,%3Csvg viewBox=%220 0 400 400%22 xmlns=%22http://www.w3.org/2000/svg%22%3E%3Cfilter id=%22noiseFilter%22%3E%3CfeTurbulence type=%22fractalNoise%22 baseFrequency=%220.9%22 numOctaves=%224%22 stitchTiles=%22stitch%22/%3E%3C/filter%3E%3Crect width=%22100%25%22 height=%22100%25%22 filter=%22url(%23noiseFilter)%22/%3E%3C/svg%3E');"></div>
                            </div>
                            <div class="h-[25%] flex items-center px-6 bg-white">
                                <h4 class="text-black font-bold text-xl group-hover:text-[#0500ff] transition-colors duration-200">Cross Training</h4>
                            </div>
                        </div>
                        <div class="min-w-[240px] md:min-w-[320px] h-[320px] bg-white rounded-2xl overflow-hidden shrink-0 flex flex-col group cursor-pointer hover:shadow-[0_0_20px_rgba(255,255,255,0.1)] transition-shadow duration-200">
                            <div class="h-[75%] overflow-hidden relative">
                                <div class="media-container w-full h-full">
                                    <img src="media/images/index/cards/yoga.webp" alt="Yoga" class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-filter duration-300" onerror="this.parentElement.classList.add('skeleton-active')" />
                                    <div class="skeleton-overlay">
                                        <div class="skeleton w-full h-full"></div>
                                    </div>
                                </div>
                                <div class="absolute inset-0 opacity-[0.15] pointer-events-none" style="background-image: url('data:image/svg+xml,%3Csvg viewBox=%220 0 400 400%22 xmlns=%22http://www.w3.org/2000/svg%22%3E%3Cfilter id=%22noiseFilter%22%3E%3CfeTurbulence type=%22fractalNoise%22 baseFrequency=%220.9%22 numOctaves=%224%22 stitchTiles=%22stitch%22/%3E%3C/filter%3E%3Crect width=%22100%25%22 height=%22100%25%22 filter=%22url(%23noiseFilter)%22/%3E%3C/svg%3E');"></div>
                            </div>
                            <div class="h-[25%] flex items-center px-6 bg-white">
                                <h4 class="text-black font-bold text-xl group-hover:text-[#0500ff] transition-colors duration-200">Yoga</h4>
                            </div>
                        </div>
                        <div class="min-w-[240px] md:min-w-[320px] h-[320px] bg-white rounded-2xl overflow-hidden shrink-0 flex flex-col group cursor-pointer hover:shadow-[0_0_20px_rgba(255,255,255,0.1)] transition-shadow duration-200">
                            <div class="h-[75%] overflow-hidden relative">
                                <div class="media-container w-full h-full">
                                    <img src="media/images/index/cards/boxe.webp" alt="Boxe" class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-filter duration-300" onerror="this.parentElement.classList.add('skeleton-active')" />
                                    <div class="skeleton-overlay">
                                        <div class="skeleton w-full h-full"></div>
                                    </div>
                                </div>
                                <div class="absolute inset-0 opacity-[0.15] pointer-events-none" style="background-image: url('data:image/svg+xml,%3Csvg viewBox=%220 0 400 400%22 xmlns=%22http://www.w3.org/2000/svg%22%3E%3Cfilter id=%22noiseFilter%22%3E%3CfeTurbulence type=%22fractalNoise%22 baseFrequency=%220.9%22 numOctaves=%224%22 stitchTiles=%22stitch%22/%3E%3C/filter%3E%3Crect width=%22100%25%22 height=%22100%25%22 filter=%22url(%23noiseFilter)%22/%3E%3C/svg%3E');"></div>
                            </div>
                            <div class="h-[25%] flex items-center px-6 bg-white">
                                <h4 class="text-black font-bold text-xl group-hover:text-[#0500ff] transition-colors duration-200">Boxe</h4>
                            </div>
                        </div>
                        <div class="min-w-[240px] md:min-w-[320px] h-[320px] bg-white rounded-2xl overflow-hidden shrink-0 flex flex-col group cursor-pointer hover:shadow-[0_0_20px_rgba(255,255,255,0.1)] transition-shadow duration-200">
                            <div class="h-[75%] overflow-hidden relative">
                                <div class="media-container w-full h-full">
                                    <img src="media/images/index/cards/spinning.webp" alt="Spinning" class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-filter duration-300" onerror="this.parentElement.classList.add('skeleton-active')" />
                                    <div class="skeleton-overlay">
                                        <div class="skeleton w-full h-full"></div>
                                    </div>
                                </div>
                                <div class="absolute inset-0 opacity-[0.15] pointer-events-none" style="background-image: url('data:image/svg+xml,%3Csvg viewBox=%220 0 400 400%22 xmlns=%22http://www.w3.org/2000/svg%22%3E%3Cfilter id=%22noiseFilter%22%3E%3CfeTurbulence type=%22fractalNoise%22 baseFrequency=%220.9%22 numOctaves=%224%22 stitchTiles=%22stitch%22/%3E%3C/filter%3E%3Crect width=%22100%25%22 height=%22100%25%22 filter=%22url(%23noiseFilter)%22/%3E%3C/svg%3E');"></div>
                            </div>
                            <div class="h-[25%] flex items-center px-6 bg-white">
                                <h4 class="text-black font-bold text-xl group-hover:text-[#0500ff] transition-colors duration-200">Spinning</h4>
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-4 mt-2 pl-[256px] md:pl-[344px]">
                        <button type="button" onclick="scrollModalities('left')" aria-label="Rolar modalidades para esquerda" class="w-12 h-12 rounded-full bg-white flex items-center justify-center hover:bg-[#0500ff] hover:text-white transition-colors text-black shadow-lg">
                            <i data-lucide="arrow-left" class="w-6 h-6"></i>
                        </button>
                        <button type="button" onclick="scrollModalities('right')" aria-label="Rolar modalidades para direita" class="w-12 h-12 rounded-full bg-white flex items-center justify-center hover:bg-[#0500ff] hover:text-white transition-colors text-black shadow-lg">
                            <i data-lucide="arrow-right" class="w-6 h-6"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-[#f4f4f4] py-24">
        <div class="container mx-auto px-4 md:px-8">
            <h2 class="text-3xl md:text-5xl font-bold mb-16 text-black text-center">Como funciona a TechFit?</h2>

            <div class="grid md:grid-cols-3 gap-8 mb-12">
                <div class="bg-white rounded-[2rem] p-8 md:p-10 shadow-sm flex flex-col h-full hover:shadow-lg transition-shadow duration-300">
                    <div class="mb-6 relative">
                        <div class="w-16 h-16 bg-[#0500ff]/10 rounded-2xl flex items-center justify-center">
                            <i data-lucide="clipboard-list" class="w-8 h-8 text-[#0500ff]" stroke-width="1.5"></i>
                        </div>
                    </div>
                    <h3 class="text-xl md:text-2xl font-bold mb-4">01. Faça sua matrícula</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Realize seu cadastro 100% online ou visite nossa recepção. Sem taxas escondidas e aprovação imediata para começar.
                    </p>
                </div>

                <div class="bg-white rounded-[2rem] p-8 md:p-10 shadow-sm flex flex-col h-full hover:shadow-lg transition-shadow duration-300">
                    <div class="mb-6">
                        <div class="w-16 h-16 bg-[#0500ff]/10 rounded-2xl flex items-center justify-center">
                            <i data-lucide="credit-card" class="w-8 h-8 text-[#0500ff]" stroke-width="1.5"></i>
                        </div>
                    </div>
                    <h3 class="text-xl md:text-2xl font-bold mb-4">02. Escolha seu plano</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Selecione entre o plano Smart (econômico) ou Black (acesso total). Flexibilidade para mudar quando quiser.
                    </p>
                </div>

                <div class="bg-white rounded-[2rem] p-8 md:p-10 shadow-sm flex flex-col h-full hover:shadow-lg transition-shadow duration-300">
                    <div class="mb-6">
                        <div class="w-16 h-16 bg-[#0500ff]/10 rounded-2xl flex items-center justify-center">
                            <i data-lucide="dumbbell" class="w-8 h-8 text-[#0500ff]" stroke-width="1.5"></i>
                        </div>
                    </div>
                    <h3 class="text-xl md:text-2xl font-bold mb-4">03. Comece a treinar</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Acesso liberado via QR Code, código único do app ou digital na catraca. Baixe o app, monte seu treino e aproveite nossa estrutura completa.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-black text-white py-24 overflow-hidden relative">
        <div class="container mx-auto px-4 md:px-8 flex flex-col lg:flex-row items-center gap-12">
            <div class="lg:w-1/2 relative z-10">
                <div class="media-container w-full max-w-[170px] mb-4">
                    <img src="media/images/brand/logo-ecosys.webp" alt="Logo App TechFit" loading="lazy" decoding="async" class="w-full max-w-[170px] mb-4" onerror="this.parentElement.classList.add('skeleton-active')">
                    <div class="skeleton-overlay">
                        <div class="skeleton-dark w-full h-12"></div>
                    </div>
                </div>
                <h2 class="text-4xl md:text-5xl font-bold mb-6 leading-tight">
                    Seu treino completo<br/>
                    <span id="dynamic-icon" class="w-8 h-8 inline-block mr-2 align-middle text-[#0500ff] transition-all duration-500" style="display: inline-flex; transform-origin: center; transform: rotateY(0deg); opacity: 1;"></span><span id="dynamic-text" aria-live="polite" class="inline-block">na palma da mão</span><span id="cursor" class="animate-pulse" style="color: #0500ff; font-weight: 300; letter-spacing: -0.15em;">|</span>
                </h2>
                <p class="text-gray-400 text-lg mb-8">
                    Esqueça as fichas de papel amassadas. Com nosso app exclusivo, você consulta seus exercícios, cargas e histórico de forma simples e rápida.
                </p>
                <div class="space-y-4">
                    <div class="flex items-start gap-4">
                        <div class="p-2 bg-gray-800 rounded-lg text-[#ffffff]"><i data-lucide="smartphone" class="w-5 h-5"></i></div>
                        <div>
                            <h4 class="font-bold text-lg">Ficha Digital</h4>
                            <p class="text-gray-400 text-sm">Visualize a execução correta dos exercícios e registre suas cargas.</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4">
                        <div class="p-2 bg-gray-800 rounded-lg text-[#ffffff]"><i data-lucide="calendar" class="w-5 h-5"></i></div>
                        <div>
                            <h4 class="font-bold text-lg">Grade de Aulas</h4>
                            <p class="text-gray-400 text-sm">Confira os horários das aulas coletivas e reserve seu lugar.</p>
                        </div>
                    </div>
                </div>
                <div class="mt-10 flex flex-col sm:flex-row gap-4">
                    <a href="https://play.google.com" target="_blank" rel="noopener noreferrer" class="hover:opacity-80 transition-opacity">
                        <div class="media-container h-12 w-auto">
                            <img src="media/images/brand/google-play.svg" alt="Disponível no Google Play" loading="lazy" decoding="async" class="h-12 w-auto" onerror="this.parentElement.classList.add('skeleton-active')">
                            <div class="skeleton-overlay">
                                <div class="skeleton-dark w-32 h-12"></div>
                            </div>
                        </div>
                    </a>
                    <a href="https://www.apple.com/br/app-store/" target="_blank" rel="noopener noreferrer" class="hover:opacity-80 transition-opacity">
                        <div class="media-container h-12 w-auto">
                            <img src="media/images/brand/app-store.svg" alt="Disponível na App Store" loading="lazy" decoding="async" class="h-12 w-auto" onerror="this.parentElement.classList.add('skeleton-active')">
                            <div class="skeleton-overlay">
                                <div class="skeleton-dark w-32 h-12"></div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>

            <div class="lg:w-1/2 relative">
                <div class="absolute -inset-4 bg-[#0500ff] rounded-full blur-[200px] opacity-100"></div>
                <div class="relative rounded-3xl overflow-hidden border border-gray-800 shadow-2xl bg-gray-900 aspect-video lg:aspect-square flex items-center justify-center group">
                    <div class="media-container w-full h-full">
                        <video id="app-video" src="media/videos/app-demo.mp4" autoplay loop muted playsinline class="object-cover w-full h-full opacity-80 group-hover:opacity-90 transition-opacity" preload="metadata" onerror="this.parentElement.classList.add('skeleton-active')"></video>
                        <div class="skeleton-overlay">
                            <div class="skeleton-video w-full h-full"></div>
                        </div>
                    </div>
                    <div class="absolute inset-0 bg-gradient-to-t from-black to-transparent opacity-70"></div>
                    <button id="video-toggle-btn" type="button" aria-label="Pausar vídeo" class="absolute bottom-4 right-4 z-30 bg-white/10 hover:bg-white/20 text-white w-10 h-10 rounded-full flex items-center justify-center transition-colors">
                        <i data-lucide="pause" class="w-5 h-5"></i>
                    </button>
                    <div class="absolute bottom-8 left-8 right-8">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="media-container h-10 w-auto">
                                <img src="media/images/brand/logo-app.webp" alt="TechFit App" loading="lazy" decoding="async" class="h-10 w-auto" onerror="this.parentElement.classList.add('skeleton-active')">
                                <div class="skeleton-overlay">
                                    <div class="skeleton-dark w-24 h-10"></div>
                                </div>
                            </div>
                        </div>
                        <p class="text-gray-300 text-sm">Acompanhe seu progresso, agende aulas e gerencie sua conta com facilidade.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-white py-24">
        <div class="container mx-auto px-4 md:px-8">
            <div class="max-w-4xl mx-auto">
                <h2 class="text-3xl md:text-5xl font-bold mb-12 text-black text-center">
                    Dúvidas Frequentes
                </h2>
                
                <div class="space-y-4" id="faq-container">
                    <div class="faq-item border border-gray-200 rounded-2xl overflow-hidden transition-all duration-300 hover:bg-gray-50">
                        <button type="button" class="w-full px-6 py-5 flex items-center justify-between text-left focus:outline-none" onclick="toggleFaq(this)" aria-expanded="false">
                            <span class="text-lg md:text-xl font-bold text-gray-900 pr-8">Qual o horário de funcionamento?</span>
                            <span class="faq-icon flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center bg-gray-100 text-gray-500 transition-colors duration-300">
                                <i data-lucide="plus" class="w-4 h-4"></i>
                            </span>
                        </button>
                        <div class="faq-answer max-h-0 overflow-hidden transition-all duration-300 ease-in-out opacity-0">
                            <div class="px-6 pb-6 text-gray-600 leading-relaxed">
                                Nossas unidades possuem acesso simplificado via biometria digital. Funcionamos de segunda a sexta das 06h às 23h, e aos finais de semana e feriados das 08h às 18h.
                            </div>
                        </div>
                    </div>

                    <div class="faq-item border border-gray-200 rounded-2xl overflow-hidden transition-all duration-300 hover:bg-gray-50">
                        <button type="button" class="w-full px-6 py-5 flex items-center justify-between text-left focus:outline-none" onclick="toggleFaq(this)" aria-expanded="false">
                            <span class="text-lg md:text-xl font-bold text-gray-900 pr-8">Posso treinar em qualquer unidade TechFit?</span>
                            <span class="faq-icon flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center bg-gray-100 text-gray-500 transition-colors duration-300">
                                <i data-lucide="plus" class="w-4 h-4"></i>
                            </span>
                        </button>
                        <div class="faq-answer max-h-0 overflow-hidden transition-all duration-300 ease-in-out opacity-0">
                            <div class="px-6 pb-6 text-gray-600 leading-relaxed">
                                Sim! A partir do plano Smart, você tem acesso ilimitado a todas as unidades da rede em território nacional, sem custo adicional.
                            </div>
                        </div>
                    </div>

                    <div class="faq-item border border-gray-200 rounded-2xl overflow-hidden transition-all duration-300 hover:bg-gray-50">
                        <button type="button" class="w-full px-6 py-5 flex items-center justify-between text-left focus:outline-none" onclick="toggleFaq(this)" aria-expanded="false">
                            <span class="text-lg md:text-xl font-bold text-gray-900 pr-8">Existe fidelidade nos planos?</span>
                            <span class="faq-icon flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center bg-gray-100 text-gray-500 transition-colors duration-300">
                                <i data-lucide="plus" class="w-4 h-4"></i>
                            </span>
                        </button>
                        <div class="faq-answer max-h-0 overflow-hidden transition-all duration-300 ease-in-out opacity-0">
                            <div class="px-6 pb-6 text-gray-600 leading-relaxed">
                                Não acreditamos em prender você. Nossos planos não possuem fidelidade ou multa de cancelamento. Você é livre para cancelar quando quiser, basta avisar com 30 dias de antecedência.
                            </div>
                        </div>
                    </div>

                    <div class="faq-item border border-gray-200 rounded-2xl overflow-hidden transition-all duration-300 hover:bg-gray-50">
                        <button type="button" class="w-full px-6 py-5 flex items-center justify-between text-left focus:outline-none" onclick="toggleFaq(this)" aria-expanded="false">
                            <span class="text-lg md:text-xl font-bold text-gray-900 pr-8">Posso levar um convidado para treinar comigo?</span>
                            <span class="faq-icon flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center bg-gray-100 text-gray-500 transition-colors duration-300">
                                <i data-lucide="plus" class="w-4 h-4"></i>
                            </span>
                        </button>
                        <div class="faq-answer max-h-0 overflow-hidden transition-all duration-300 ease-in-out opacity-0">
                            <div class="px-6 pb-6 text-gray-600 leading-relaxed">
                                Clientes do plano Black têm direito a levar 5 convidados por mês para treinar em qualquer unidade, mediante agendamento prévio pelo app.
                            </div>
                        </div>
                    </div>

                    <div class="faq-item border border-gray-200 rounded-2xl overflow-hidden transition-all duration-300 hover:bg-gray-50">
                        <button type="button" class="w-full px-6 py-5 flex items-center justify-between text-left focus:outline-none" onclick="toggleFaq(this)" aria-expanded="false">
                            <span class="text-lg md:text-xl font-bold text-gray-900 pr-8">O que preciso para me matricular?</span>
                            <span class="faq-icon flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center bg-gray-100 text-gray-500 transition-colors duration-300">
                                <i data-lucide="plus" class="w-4 h-4"></i>
                            </span>
                        </button>
                        <div class="faq-answer max-h-0 overflow-hidden transition-all duration-300 ease-in-out opacity-0">
                            <div class="px-6 pb-6 text-gray-600 leading-relaxed">
                                É super simples. Você só precisa de um documento com foto (RG ou CNH) e um cartão de crédito válido. Você pode fazer tudo na recepção ou antecipar pelo site.
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-12 text-center">
                    <p class="text-gray-500 mb-4">Ainda tem dúvidas?</p>
                    <a href="https://wa.me/5511999999999?text=Olá,%20gostaria%20de%20tirar%20algumas%20dúvidas%20sobre%20a%20TechFit" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 text-[#0500ff] font-bold hover:underline">
                        Fale com nosso suporte no WhatsApp <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-[#f4f4f4] py-24">
        <div class="container mx-auto px-4 md:px-8">
            <div class="bg-[#0500ff] rounded-[3rem] p-8 md:p-16 flex flex-col lg:flex-row items-center justify-between gap-10 shadow-xl relative overflow-hidden">
                <div class="absolute -top-24 -right-24 w-64 h-64 bg-white opacity-20 rounded-full blur-3xl"></div>
                <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-black opacity-30 rounded-full blur-3xl"></div>

                <div class="lg:w-1/2 relative z-10">
                    <h2 class="text-3xl md:text-5xl font-bold text-white mb-6 leading-tight">
                        Comece sua transformação hoje.
                    </h2>
                    <p class="text-white/90 text-lg mb-8 font-medium">
                        Sem taxas de matrícula, sem fidelidade e com acesso ilimitado a todas as aulas coletivas.
                    </p>
                    <ul class="space-y-3 mb-8">
                        <li class="flex items-center gap-2 text-white font-semibold">
                            <div class="bg-white text-[#0500ff] rounded-full p-0.5"><i data-lucide="check" class="w-3 h-3"></i></div>
                            Acesso a todas unidades
                        </li>
                        <li class="flex items-center gap-2 text-white font-semibold">
                            <div class="bg-white text-[#0500ff] rounded-full p-0.5"><i data-lucide="check" class="w-3 h-3"></i></div>
                            Aulas de Yoga e Spinning
                        </li>
                        <li class="flex items-center gap-2 text-white font-semibold">
                            <div class="bg-white text-[#0500ff] rounded-full p-0.5"><i data-lucide="check" class="w-3 h-3"></i></div>
                            Planos a partir de R$ 99,90
                        </li>
                    </ul>
                </div>

                <div class="lg:w-auto relative z-10">
                    <button type="button" class="bg-white text-[#0500ff] px-10 py-5 rounded-full font-bold text-lg hover:bg-gray-100 transition-all hover:scale-105 shadow-lg whitespace-nowrap">
                        Quero ser TechFit
                    </button>
                </div>
            </div>
        </div>
    </section>

<?php
require 'footer.php';
?>