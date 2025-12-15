<?php
// Início da sessão para possível uso
session_start();

// Configurações de header para evitar cache
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
?>
<!DOCTYPE html>
<html lang="pt-BR" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Security-Policy" content="default-src 'self' https://cdn.tailwindcss.com https://unpkg.com https://fonts.googleapis.com https://fonts.gstatic.com https://img.icons8.com https://viacep.com.br data: blob:; script-src 'self' https://cdn.tailwindcss.com https://unpkg.com 'unsafe-inline'; style-src 'self' 'unsafe-inline' https://cdn.tailwindcss.com https://fonts.googleapis.com; img-src 'self' data: https://img.icons8.com; font-src 'self' https://fonts.gstatic.com data:; connect-src 'self' https://viacep.com.br http://localhost:* http://127.0.0.1:*">
    <title>Matrícula - TechFit</title>
    <link rel="icon" type="image/x-icon" href="media/images/brand/favicon-main.ico">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        
        /* Estilos para seleção de Pagamento */
        .payment-method-radio:checked + div {
            border-color: #0500ff;
            background-color: #f0f4ff;
            color: #0500ff;
        }
        
        /* Animação suave entre etapas */
        .step-content {
            transition: opacity 0.3s ease-in-out, transform 0.3s ease-in-out;
        }
        .hidden-step {
            display: none;
            opacity: 0;
            transform: translateX(20px);
        }
        .active-step {
            display: block;
            opacity: 1;
            transform: translateX(0);
            animation: fadeIn 0.4s ease-out forwards;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateX(10px); }
            to { opacity: 1; transform: translateX(0); }
        }
        /* Estilos de Erro */
        .input-error {
            border-color: #ef4444 !important;
            background-color: #fef2f2 !important;
        }
        .error-message {
            display: none;
            color: #ef4444;
            font-size: 0.75rem;
            margin-top: 0.25rem;
            margin-left: 0.25rem;
            font-weight: 500;
        }
        .input-group.has-error .error-message {
            display: flex;
            align-items: center;
            gap: 4px;
        }
        
        /* Toggle Switch Animation */
        .toggle-checkbox:checked {
            right: 0;
            border-color: #0500ff;
        }
        .toggle-checkbox:checked + .toggle-label {
            background-color: #0500ff;
        }
        
        /* Preços com transição suave */
        .price-text {
            transition: all 0.3s ease;
        }
    </style>
    <script src="js/api-service.js"></script>
</head>
<body class="bg-[#f4f4f4] text-gray-900 font-sans">

    <!-- Header Simplificado -->
    <header class="fixed w-full z-50 bg-black/90 backdrop-blur-md py-4 border-b border-gray-800">
        <div class="container mx-auto px-4 md:px-8 flex justify-between items-center">
            <a href="index.php" class="flex items-center cursor-pointer hover:opacity-80 transition-opacity">
                <img src="media/images/brand/logo-light.webp" alt="TechFit" class="h-8 md:h-10 w-auto" onerror="this.style.display='none'; this.nextElementSibling.style.display='block'">
                <span class="text-white font-bold text-2xl tracking-tighter hidden">TECH<span class="text-[#0500ff]">FIT</span></span>
            </a>
            <a href="index.php" class="text-white hover:text-[#0500ff] font-medium text-sm transition-colors flex items-center gap-2">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                <span>Voltar ao início</span>
            </a>
        </div>
    </header>

    <main class="pt-24 pb-20">
        <div class="container mx-auto px-4 md:px-8 max-w-6xl">
            
            <!-- Título -->
            <div class="text-center mb-12 mt-8">
                <h1 class="text-3xl md:text-5xl font-bold mb-4">Escolha seu plano</h1>
                <p class="text-gray-600 text-lg">Sem taxas de matrícula. Cancelamento gratuito em até 7 dias.</p>
            </div>

            <div class="flex flex-col lg:flex-row gap-8 items-start">
                
                <!-- Coluna da Esquerda: Seleção de Planos -->
                <div class="w-full lg:w-5/12 space-y-6">
                    
                    <!-- Toggle de Ciclo (Mensal / Anual) -->
                    <div class="bg-white p-2 rounded-xl shadow-sm border border-gray-200 flex relative">
                        <div class="w-1/2 text-center py-2 cursor-pointer rounded-lg transition-all duration-300 bg-gray-100 text-gray-500 font-medium hover:bg-gray-200" id="btn-monthly" onclick="setCycle('monthly')">
                            Mensal
                        </div>
                        <div class="w-1/2 text-center py-2 cursor-pointer rounded-lg transition-all duration-300 text-gray-500 font-medium hover:bg-gray-50 relative overflow-hidden" id="btn-annual" onclick="setCycle('annual')">
                            Anual
                            <span class="absolute top-0 right-0 bg-green-100 text-green-700 text-[9px] px-1.5 py-0.5 rounded-bl-md font-bold">-15%</span>
                        </div>
                    </div>

                    <!-- Hidden input para armazenar o ciclo selecionado -->
                    <input type="hidden" id="selected-cycle" value="monthly">

                    <!-- Container de Planos (Adaptável) -->
                    <div id="plans-container" class="space-y-4">
                        
                        <!-- PLANO 1: SMART (Estilo Light) -->
                        <!-- Para adicionar novos planos light, basta duplicar este label e alterar valores/textos -->
                        <label class="cursor-pointer block group relative">
                            <input type="radio" name="plan" value="smart" class="peer sr-only" onchange="updateSummary()">
                            
                            <!-- Card Styling: peer-checked controla o estado ativo -->
                            <div class="bg-white rounded-2xl p-6 border-2 border-transparent transition-all duration-300 shadow-sm hover:shadow-md relative overflow-hidden peer-checked:border-[#0500ff] peer-checked:bg-[#f0f4ff]">
                                
                                <div class="absolute top-4 right-4 w-6 h-6 rounded-full bg-[#0500ff] text-white flex items-center justify-center opacity-0 transform scale-0 transition-all duration-300 peer-checked:opacity-100 peer-checked:scale-100">
                                    <i data-lucide="check" class="w-4 h-4"></i>
                                </div>
                                
                                <h3 class="text-xl font-bold text-gray-900 mb-1 plan-title">Plano Smart</h3>
                                <p class="text-gray-500 text-sm mb-4 cycle-desc" data-text-monthly="Sem fidelidade." data-text-annual="Fidelidade 12 meses.">Sem fidelidade.</p>
                                
                                <div class="flex items-baseline gap-1 mb-4">
                                    <span class="text-sm text-gray-500">R$</span>
                                    <!-- Preços definidos via data-attributes para JS ler dinamicamente -->
                                    <span class="text-4xl font-black text-gray-900 price-text" data-monthly="99,90" data-annual="89,90">99,90</span>
                                    <span class="text-sm text-gray-500">/mês</span>
                                </div>
                                
                                <ul class="space-y-2 text-sm text-gray-600">
                                    <li class="flex items-center gap-2"><i data-lucide="check" class="w-4 h-4 text-green-500"></i> Acesso à unidade escolhida</li>
                                    <li class="flex items-center gap-2"><i data-lucide="check" class="w-4 h-4 text-green-500"></i> Acesso 24 horas</li>
                                    <li class="flex items-center gap-2"><i data-lucide="x" class="w-4 h-4 text-red-300"></i> Sem acesso a outras unidades</li>
                                </ul>
                            </div>
                        </label>

                        <!-- PLANO 2: BLACK (Estilo Dark) -->
                        <!-- Para adicionar novos planos dark, basta duplicar este label e alterar valores/textos -->
                        <label class="cursor-pointer block group relative">
                            <input type="radio" name="plan" value="black" class="peer sr-only" checked onchange="updateSummary()">
                            
                            <div class="bg-black text-white rounded-2xl p-6 border-2 border-transparent shadow-[0_0_20px_rgba(5,0,255,0.15)] relative overflow-hidden transition-colors peer-checked:border-[#0500ff]">
                                <div class="absolute top-0 left-0 bg-[#0500ff] text-white text-xs font-bold px-3 py-1 rounded-br-lg">MAIS VENDIDO</div>
                                
                                <div class="absolute top-4 right-4 w-6 h-6 rounded-full bg-[#0500ff] text-white flex items-center justify-center opacity-0 transform scale-0 transition-all duration-300 peer-checked:opacity-100 peer-checked:scale-100">
                                    <i data-lucide="check" class="w-4 h-4"></i>
                                </div>
                                
                                <h3 class="text-xl font-bold mb-1 plan-title">Plano Black</h3>
                                <p class="text-gray-400 text-sm mb-4 cycle-desc" data-text-monthly="A experiência completa." data-text-annual="Fidelidade 12 meses.">A experiência completa.</p>
                                
                                <div class="flex items-baseline gap-1 mb-4">
                                    <span class="text-sm text-gray-400">R$</span>
                                    <span class="text-4xl font-black text-white price-text" data-monthly="129,90" data-annual="109,90">129,90</span>
                                    <span class="text-sm text-gray-400">/mês</span>
                                </div>
                                
                                <ul class="space-y-2 text-sm text-gray-300">
                                    <li class="flex items-center gap-2"><i data-lucide="check" class="w-4 h-4 text-[#0500ff]"></i> Acesso ilimitado a todas as unidades</li>
                                    <li class="flex items-center gap-2"><i data-lucide="check" class="w-4 h-4 text-[#0500ff]"></i> Leve 5 amigos por mês</li>
                                    <li class="flex items-center gap-2"><i data-lucide="check" class="w-4 h-4 text-[#0500ff]"></i> Cadeira de massagem</li>
                                    <li class="flex items-center gap-2"><i data-lucide="check" class="w-4 h-4 text-[#0500ff]"></i> Camiseta exclusiva TechFit</li>
                                </ul>
                            </div>
                        </label>
                        
                    </div>

                    <!-- Resumo Mobile -->
                    <div class="lg:hidden bg-white p-4 rounded-xl shadow-sm border border-gray-200 mt-6">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-gray-600">Total a pagar hoje:</span>
                            <span class="font-bold text-xl" id="mobile-total">R$ 129,90</span>
                        </div>
                        <p class="text-xs text-green-600 font-medium">1ª mensalidade só daqui a 30 dias!</p>
                    </div>
                </div>

                <!-- Coluna da Direita: Formulário -->
                <div class="w-full lg:w-7/12">
                    <div class="bg-white rounded-3xl shadow-xl p-6 md:p-8 border border-gray-100 relative overflow-hidden">
                        
                        <!-- Barra de Progresso Superior -->
                        <div class="flex items-center justify-between mb-8 px-2 relative">
                             <!-- Linha Conectora Fundo -->
                            <div class="absolute top-5 left-0 w-full px-12 h-0.5 pointer-events-none z-0">
                                <div class="w-full h-full bg-gray-200 rounded-full"></div>
                            </div>
                            <!-- Linha Conectora Progresso -->
                            <div class="absolute top-5 left-0 w-full px-12 h-0.5 pointer-events-none z-0">
                                <div id="progress-bar" class="h-full bg-[#0500ff] w-0 transition-all duration-500 ease-out rounded-full"></div>
                            </div>

                            <!-- Step 1 -->
                            <div class="flex flex-col items-center relative z-10 w-1/3">
                                <div id="step-indicator-1" class="w-10 h-10 rounded-full bg-[#0500ff] text-white flex items-center justify-center font-bold text-sm transition-all duration-300 shadow-md ring-4 ring-white">
                                    1
                                </div>
                                <span id="step-text-1" class="text-xs font-bold text-[#0500ff] mt-2 transition-colors">Dados</span>
                            </div>
                            
                            <!-- Step 2 -->
                            <div class="flex flex-col items-center relative z-10 w-1/3">
                                <div id="step-indicator-2" class="w-10 h-10 rounded-full bg-gray-200 text-gray-500 flex items-center justify-center font-bold text-sm transition-all duration-300 border-2 border-transparent ring-4 ring-white">
                                    2
                                </div>
                                <span id="step-text-2" class="text-xs font-medium text-gray-400 mt-2 transition-colors">Pagamento</span>
                            </div>

                            <!-- Step 3 -->
                            <div class="flex flex-col items-center relative z-10 w-1/3">
                                <div id="step-indicator-3" class="w-10 h-10 rounded-full bg-gray-200 text-gray-500 flex items-center justify-center font-bold text-sm transition-all duration-300 border-2 border-transparent ring-4 ring-white">
                                    3
                                </div>
                                <span id="step-text-3" class="text-xs font-medium text-gray-400 mt-2 transition-colors">Confirmar</span>
                            </div>
                        </div>

                        <form id="registration-form" onsubmit="handleRegister(event)" novalidate>
                            
                            <!-- ETAPA 1: DADOS PESSOAIS -->
                            <div id="step-1" class="step-content active-step">
                                <h3 class="text-xl font-bold mb-6">Quem é você?</h3>
                                
                                <div class="grid md:grid-cols-2 gap-4">
                                    <div class="input-group">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Nome</label>
                                        <input type="text" id="input-nome" maxlength="50" oninput="maskName(this); clearError(this)" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-3 focus:outline-none focus:border-[#0500ff] focus:ring-1 focus:ring-[#0500ff] transition-all" placeholder="Seu nome">
                                        <span class="error-message">Nome é obrigatório</span>
                                    </div>
                                    <div class="input-group">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Sobrenome</label>
                                        <input type="text" id="input-sobrenome" maxlength="50" oninput="maskName(this); clearError(this)" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-3 focus:outline-none focus:border-[#0500ff] focus:ring-1 focus:ring-[#0500ff] transition-all" placeholder="Seu sobrenome">
                                        <span class="error-message">Sobrenome é obrigatório</span>
                                    </div>
                                    
                                    <div class="input-group">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">CPF</label>
                                        <input type="text" id="cpf" maxlength="14" oninput="maskCPF(this); clearError(this)" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-3 focus:outline-none focus:border-[#0500ff] focus:ring-1 focus:ring-[#0500ff] transition-all" placeholder="000.000.000-00">
                                        <span class="error-message">CPF inválido</span>
                                    </div>
                                    <div class="input-group">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Data de Nascimento</label>
                                        <input type="date" id="input-dob" oninput="clearError(this)" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-3 focus:outline-none focus:border-[#0500ff] focus:ring-1 focus:ring-[#0500ff] transition-all">
                                        <span class="error-message">Data inválida. Idade mínima de 16 anos.</span>
                                    </div>
                                    
                                    <div class="input-group">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Celular</label>
                                        <input type="tel" id="phone" maxlength="15" oninput="maskPhone(this); clearError(this)" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-3 focus:outline-none focus:border-[#0500ff] focus:ring-1 focus:ring-[#0500ff] transition-all" placeholder="(00) 00000-0000">
                                        <span class="error-message">Celular inválido</span>
                                    </div>
                                    <div class="input-group">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">E-mail</label>
                                        <!-- A validação ativa foi removida daqui, agora ocorre somente no goToStep(2) -->
                                        <input type="email" id="input-email" maxlength="100" oninput="clearError(this)" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-3 focus:outline-none focus:border-[#0500ff] focus:ring-1 focus:ring-[#0500ff] transition-all" placeholder="seu@email.com">
                                        <!-- Mensagem de erro padrão. O conteúdo será sobrescrito pelo JS -->
                                        <span class="error-message">E-mail inválido</span>
                                    </div>

                                    <!-- ENDEREÇO COMPLETO -->
                                    <div class="col-span-2">
                                        <div class="flex items-center justify-between mb-1">
                                            <label class="block text-sm font-medium text-gray-700">Endereço</label>
                                            <span id="cep-loading" class="text-xs text-[#0500ff] hidden items-center gap-1">
                                                <i data-lucide="loader-2" class="w-3 h-3 animate-spin"></i> Buscando CEP...
                                            </span>
                                        </div>
                                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                            <!-- CEP -->
                                            <div class="input-group col-span-2 md:col-span-1">
                                                <input type="text" id="input-cep" maxlength="9" oninput="maskCEP(this); clearError(this)" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-3 focus:outline-none focus:border-[#0500ff] focus:ring-1 focus:ring-[#0500ff] transition-all" placeholder="CEP">
                                                <span class="error-message">CEP inválido</span>
                                            </div>
                                            <!-- Estado -->
                                            <div class="input-group col-span-2 md:col-span-1">
                                                <input type="text" id="input-state" oninput="clearError(this)" maxlength="2" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-3 focus:outline-none focus:border-[#0500ff] focus:ring-1 focus:ring-[#0500ff] transition-all" placeholder="UF">
                                                <span class="error-message">UF obrigatório</span>
                                            </div>
                                            <!-- Cidade -->
                                            <div class="input-group col-span-2">
                                                <input type="text" id="input-city" maxlength="60" oninput="clearError(this)" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-3 focus:outline-none focus:border-[#0500ff] focus:ring-1 focus:ring-[#0500ff] transition-all" placeholder="Cidade">
                                                <span class="error-message">Cidade obrigatória</span>
                                            </div>
                                            <!-- Rua -->
                                            <div class="input-group col-span-2 md:col-span-3">
                                                <input type="text" id="input-street" maxlength="120" oninput="clearError(this)" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-3 focus:outline-none focus:border-[#0500ff] focus:ring-1 focus:ring-[#0500ff] transition-all" placeholder="Rua / Avenida">
                                                <span class="error-message">Rua obrigatória</span>
                                            </div>
                                            <!-- Número -->
                                            <div class="input-group col-span-1">
                                                <input type="text" id="input-number" maxlength="10" oninput="maskAddressNumber(this); clearError(this)" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-3 focus:outline-none focus:border-[#0500ff] focus:ring-1 focus:ring-[#0500ff] transition-all" placeholder="Nº">
                                                <span class="error-message">Nº</span>
                                            </div>
                                            <!-- Bairro -->
                                            <div class="input-group col-span-2 md:col-span-4">
                                                <input type="text" id="input-neighborhood" maxlength="60" oninput="clearError(this)" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-3 focus:outline-none focus:border-[#0500ff] focus:ring-1 focus:ring-[#0500ff] transition-all" placeholder="Bairro">
                                                <span class="error-message">Bairro obrigatório</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- NOVO CAMPO: Objetivo -->
                                    <div class="col-span-2 input-group">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Qual seu principal objetivo?</label>
                                        <div class="relative">
                                            <select id="input-goal" onchange="clearError(this)" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-3 appearance-none focus:outline-none focus:border-[#0500ff] focus:ring-1 focus:ring-[#0500ff] transition-all cursor-pointer text-gray-700">
                                                <option value="" disabled selected>Selecione uma opção</option>
                                                <option value="emagrecimento">Emagrecimento</option>
                                                <option value="hipertrofia">Ganho de Massa Muscular</option>
                                                <option value="saude">Saúde e Bem-estar</option>
                                                <option value="condicionamento">Condicionamento Físico</option>
                                                <option value="social">Socialização</option>
                                            </select>
                                            <i data-lucide="chevron-down" class="absolute right-4 top-3.5 w-5 h-5 text-gray-400 pointer-events-none"></i>
                                        </div>
                                        <span class="error-message">Selecione um objetivo</span>
                                    </div>
                                </div>

                                <div class="mt-8 pt-6 border-t border-gray-100">
                                    <button type="button" onclick="goToStep(2)" class="w-full bg-[#0500ff] hover:bg-[#0400cc] text-white font-bold py-4 rounded-xl shadow-lg transition-all transform hover:scale-[1.01] flex items-center justify-center gap-2">
                                        <span>Continuar para Pagamento</span>
                                        <i data-lucide="arrow-right" class="w-5 h-5"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- ETAPA 2: PAGAMENTO -->
                            <div id="step-2" class="step-content hidden-step">
                                <h3 class="text-xl font-bold mb-6">Forma de pagamento</h3>

                                <!-- Seletor de Método de Pagamento -->
                                <div class="grid grid-cols-3 gap-3 mb-6">
                                    <label class="cursor-pointer">
                                        <input type="radio" name="payment_method" value="credit" class="payment-method-radio hidden" checked onchange="togglePaymentFields()">
                                        <div class="border border-gray-200 rounded-xl p-3 flex flex-col items-center justify-center gap-2 h-24 hover:bg-gray-50 transition-all text-gray-500 font-medium">
                                            <i data-lucide="credit-card" class="w-6 h-6"></i>
                                            <span class="text-xs">Cartão</span>
                                        </div>
                                    </label>
                                    <label class="cursor-pointer">
                                        <input type="radio" name="payment_method" value="pix" class="payment-method-radio hidden" onchange="togglePaymentFields()">
                                        <div class="border border-gray-200 rounded-xl p-3 flex flex-col items-center justify-center gap-2 h-24 hover:bg-gray-50 transition-all text-gray-500 font-medium">
                                            <img src="https://img.icons8.com/color/48/pix.png" alt="Pix icon" class="w-6 h-6 grayscale opacity-60" style="filter: none;">
                                            <span class="text-xs">Pix</span>
                                        </div>
                                    </label>
                                    <label class="cursor-pointer">
                                        <input type="radio" name="payment_method" value="boleto" class="payment-method-radio hidden" onchange="togglePaymentFields()">
                                        <div class="border border-gray-200 rounded-xl p-3 flex flex-col items-center justify-center gap-2 h-24 hover:bg-gray-50 transition-all text-gray-500 font-medium">
                                            <i data-lucide="barcode" class="w-6 h-6"></i>
                                            <span class="text-xs">Boleto</span>
                                        </div>
                                    </label>
                                </div>

                                <!-- CAMPOS: CARTÃO DE CRÉDITO -->
                                <div id="payment-credit-fields" class="block">
                                    <div class="bg-blue-50 p-4 rounded-lg mb-6 flex items-start gap-3 border border-blue-100">
                                        <i data-lucide="shield-check" class="w-5 h-5 text-[#0500ff] mt-0.5 shrink-0"></i>
                                        <p class="text-sm text-blue-900">
                                            Ambiente seguro. Primeira cobrança em <span class="font-bold" id="cobranca-data-credit">30 dias</span>.
                                        </p>
                                    </div>
                                    <div class="grid gap-4">
                                        <div class="input-group">
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Número do Cartão</label>
                                            <div class="relative">
                                                <input type="text" id="card_number" maxlength="19" oninput="maskCard(this); clearError(this)" class="credit-input w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-3 pl-11 focus:outline-none focus:border-[#0500ff] focus:ring-1 focus:ring-[#0500ff] transition-all" placeholder="0000 0000 0000 0000">
                                                <i data-lucide="credit-card" class="w-5 h-5 text-gray-400 absolute left-3 top-3.5"></i>
                                            </div>
                                            <span class="error-message">Inválido</span>
                                        </div>
                                        <div class="grid grid-cols-2 gap-4">
                                            <div class="input-group">
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Validade</label>
                                                <input type="text" id="card_expiry" maxlength="5" oninput="maskExpiry(this); clearError(this)" class="credit-input w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-3 focus:outline-none focus:border-[#0500ff] focus:ring-1 focus:ring-[#0500ff] transition-all" placeholder="MM/AA">
                                                <span class="error-message">Data inválida</span>
                                            </div>
                                            <div class="input-group">
                                                <label class="block text-sm font-medium text-gray-700 mb-1">CVV</label>
                                                <input type="text" id="card_cvv" maxlength="4" oninput="maskCVV(this); clearError(this)" class="credit-input w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-3 focus:outline-none focus:border-[#0500ff] focus:ring-1 focus:ring-[#0500ff] transition-all" placeholder="123">
                                                <span class="error-message">Inválido</span>
                                            </div>
                                        </div>
                                        <div class="input-group">
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Nome no Cartão</label>
                                            <input type="text" id="card_name" maxlength="100" oninput="maskName(this); clearError(this)" class="credit-input w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-3 focus:outline-none focus:border-[#0500ff] focus:ring-1 focus:ring-[#0500ff] transition-all" placeholder="Como impresso no cartão">
                                            <span class="error-message">Obrigatório</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- CAMPOS: PIX -->
                                <div id="payment-pix-fields" class="hidden">
                                    <div class="bg-gray-50 p-6 rounded-xl border border-gray-200 text-center">
                                        <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mx-auto mb-4 shadow-sm">
                                            <img src="https://img.icons8.com/color/48/pix.png" alt="Pix icon" class="w-8 h-8">
                                        </div>
                                        <h4 class="font-bold text-gray-900 mb-2">Pagamento via Pix</h4>
                                        <p class="text-sm text-gray-500 mb-4">O código QR Code será enviado para o seu e-mail cadastrado logo após a confirmação. A aprovação é imediata.</p>
                                        <div class="text-xs bg-green-50 text-green-700 px-3 py-2 rounded-lg inline-block font-medium">
                                            <i data-lucide="tag" class="w-3 h-3 inline mr-1"></i> 5% de desconto na primeira mensalidade
                                        </div>
                                    </div>
                                </div>

                                <!-- CAMPOS: BOLETO -->
                                <div id="payment-boleto-fields" class="hidden">
                                    <div class="bg-gray-50 p-6 rounded-xl border border-gray-200 text-center">
                                        <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mx-auto mb-4 shadow-sm">
                                            <i data-lucide="barcode" class="w-8 h-8 text-gray-600"></i>
                                        </div>
                                        <h4 class="font-bold text-gray-900 mb-2">Pagamento via Boleto</h4>
                                        <p class="text-sm text-gray-500">O boleto será enviado para o seu e-mail cadastrado logo após a confirmação. O prazo de compensação é de até 3 dias úteis.</p>
                                    </div>
                                </div>

                                <!-- Botões de Navegação Step 2 -->
                                <div class="mt-8 pt-6 border-t border-gray-100 flex gap-3 flex-col-reverse md:flex-row">
                                    <button type="button" onclick="goToStep(1)" class="w-full md:w-1/3 bg-white hover:bg-gray-50 text-gray-700 font-bold py-4 rounded-xl border border-gray-200 transition-colors flex items-center justify-center gap-2">
                                        <i data-lucide="arrow-left" class="w-4 h-4"></i>
                                        <span>Voltar</span>
                                    </button>
                                    <button type="button" onclick="goToStep(3)" class="w-full md:w-2/3 bg-[#0500ff] hover:bg-[#0400cc] text-white font-bold py-4 rounded-xl shadow-lg transition-all transform hover:scale-[1.01] flex items-center justify-center gap-2">
                                        <span>Revisar Matrícula</span>
                                        <i data-lucide="arrow-right" class="w-5 h-5"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- ETAPA 3: CONFIRMAÇÃO (NOVO) -->
                            <div id="step-3" class="step-content hidden-step">
                                <h3 class="text-xl font-bold mb-6">Confirme seus dados</h3>
                                
                                <div class="bg-white border border-gray-100 rounded-xl shadow-sm p-5 mb-6">
                                    <div class="flex justify-between items-center pb-4 border-b border-gray-100 mb-4">
                                        <div>
                                            <p class="text-xs text-gray-500 uppercase tracking-wide font-bold">Plano Escolhido</p>
                                            <p class="text-xl font-bold text-gray-900" id="review-plan-name">Plano Black</p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-xs text-gray-500 uppercase tracking-wide font-bold">Valor</p>
                                            <p class="text-xl font-bold text-[#0500ff]" id="review-plan-price">R$ 129,90</p>
                                        </div>
                                    </div>

                                    <div class="space-y-3 text-sm">
                                        <div class="flex justify-between">
                                            <span class="text-gray-500">Nome:</span>
                                            <span class="font-medium text-gray-900" id="review-name">...</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-500">Nascimento:</span>
                                            <span class="font-medium text-gray-900" id="review-dob">...</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-500">E-mail:</span>
                                            <span class="font-medium text-gray-900" id="review-email">...</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-500">Pagamento:</span>
                                            <span class="font-medium text-gray-900 flex items-center gap-1">
                                                <span id="review-payment-method">Cartão de Crédito</span>
                                                <i data-lucide="check-circle" class="w-3 h-3 text-green-500"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="bg-blue-50 p-4 rounded-lg mb-8 text-center">
                                    <p class="text-sm text-blue-800">
                                        Ao confirmar, você aceita os <a href="#" class="underline font-bold">Termos de Uso</a> e o <a href="#" class="underline font-bold">Contrato de Adesão</a>.
                                    </p>
                                </div>

                                <div class="flex gap-3 flex-col-reverse md:flex-row">
                                    <button type="button" onclick="goToStep(2)" class="w-full md:w-1/3 bg-white hover:bg-gray-50 text-gray-700 font-bold py-4 rounded-xl border border-gray-200 transition-colors flex items-center justify-center gap-2">
                                        <i data-lucide="arrow-left" class="w-4 h-4"></i>
                                        <span>Voltar</span>
                                    </button>
                                    
                                    <button type="submit" class="w-full md:w-2/3 bg-[#0500ff] hover:bg-[#0400cc] text-white font-bold py-4 rounded-xl shadow-lg transition-all transform hover:scale-[1.01] flex items-center justify-center gap-2">
                                        <span>Finalizar Matrícula</span>
                                        <i data-lucide="check" class="w-5 h-5"></i>
                                    </button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>

            </div>
        </div>
    </main>

    <!-- FOOTER ADAPTADO À IMAGEM ANEXA (TechFit) -->
    <footer class="bg-[#fcfcfc] border-t border-gray-200 pt-10 pb-16 mt-12">
        <div class="container mx-auto px-4 md:px-8 max-w-6xl">
            
            <!-- Linha Superior (Disclaimer) -->
            <p class="text-sm text-gray-600 mb-8 max-w-4xl">
                É proibido condicionar descontos à aquisição dos seguros. Todos os descontos estão relacionados à aquisição do produto.
            </p>

            <div class="flex flex-col md:flex-row justify-between gap-12">
                
                <!-- Coluna Esquerda: Pagamento e Legal -->
                <div class="w-full md:w-8/12 space-y-8">
                    
                    <!-- Formas de Pagamento -->
                    <div>
                        <h4 class="text-gray-800 font-bold text-lg mb-4">Formas de pagamentos</h4>
                        <div class="flex flex-wrap items-center gap-4">
                            <!-- Ícones replicando a aparência da imagem -->
                            <img src="https://img.icons8.com/color/48/visa.png" alt="Visa" class="h-6 w-auto">
                            <img src="https://img.icons8.com/color/48/mastercard.png" alt="Mastercard" class="h-6 w-auto">
                            <img src="https://img.icons8.com/color/48/elo.png" alt="Elo" class="h-6 w-auto">
                            <img src="https://img.icons8.com/color/48/american-express.png" alt="American Express" class="h-6 w-auto">
                            <img src="https://img.icons8.com/color/48/diners-club.png" alt="Diners Club" class="h-6 w-auto opacity-70">
                            <img src="https://img.icons8.com/color/48/mercado-pago.png" alt="Mercado Pago" class="h-6 w-auto opacity-70">
                            <img src="https://img.icons8.com/color/48/nubank.png" alt="NuBank" class="h-6 w-auto">
                            <img src="https://img.icons8.com/color/48/pix.png" alt="Pix" class="h-6 w-auto">
                            <i data-lucide="credit-card" class="w-6 h-6 text-gray-800"></i> <!-- Hipercard genérico -->
                        </div>
                    </div>

                    <!-- Textos Legais/Rodapé Inferior (Adaptados à TechFit) -->
                    <div class="text-xs text-gray-500 space-y-3 pt-4 border-t border-gray-100">
                        <p>TECHFIT HOLDING S.A., com sede em Avenida da Inovação, nº 350, Bairro Tecnológico, São Paulo/SP, 01000-000, inscrita no CNPJ/MF sob o nº. 12.345.678/0001-90.</p>
                        <p>LOJA ONLINE TECHFIT, operada pela WEBFIT E-COMMERCE LTDA, com endereço na Rua do Treino, 200, Bloco C - Campinas - SP, CEP 13000-000, inscrita no CNPJ sob o nº. 98.765.432/0002-10 e IE 123.456.789.10.</p>
                        <p><a href="#" class="text-gray-600 hover:underline">Contato Eletrônico</a> para compras na Loja Online.</p>
                        <p class="mt-4">Este website é melhor visualizado nas últimas versões dos navegadores Google Chrome, Mozilla Firefox, Microsoft Edge e Safari.</p>
                    </div>
                </div>

                <!-- Coluna Direita: Ajuda/Contato (Destaque TechFit) -->
                <div class="w-full md:w-4/12 md:text-right space-y-4">
                    <h4 class="text-[#0500ff] font-bold text-xl mb-2">Precisa de Ajuda?</h4>
                    <p class="text-2xl font-extrabold text-gray-800">0800 007 2611</p>
                    
                    <div class="text-sm space-y-1 text-gray-600">
                        <a href="#" class="block hover:text-[#0500ff] transition-colors">Termos e Condições</a>
                        <a href="#" class="block hover:text-[#0500ff] transition-colors">Central de Atendimento</a>
                        <a href="#" class="block hover:text-[#0500ff] transition-colors">FAQ TechFit</a>
                    </div>
                </div>
            </div>

            <!-- Copyright removido daqui. -->
        </div>
    </footer>


    <script>
        // Inicializar ícones
        if (window.lucide && typeof window.lucide.createIcons === 'function') {
            lucide.createIcons();
        }

        // Data futura para cobrança
        const date = new Date();
        date.setDate(date.getDate() + 30);
        const options = { day: 'numeric', month: 'long' };
        
        // Pode haver múltiplos elementos de data agora
        const dateEl = document.getElementById('cobranca-data-credit');
        if(dateEl) dateEl.textContent = date.toLocaleDateString('pt-BR', options);

        // --- SISTEMA DE ERROS VISUAIS ---

        function showError(input, customMessage) {
            if (!input) return;
            const group = input.closest('.input-group');
            if (group) {
                group.classList.add('has-error');
                input.classList.add('input-error');
                
                const msgSpan = group.querySelector('.error-message');
                if (msgSpan) {
                    msgSpan.style.display = 'flex';
                    // Sobrescreve a mensagem com a mensagem personalizada
                    msgSpan.innerHTML = `<i data-lucide="alert-circle" class="w-3 h-3"></i> ${customMessage}`;
                    lucide.createIcons();
                }
            }
        }

        function clearError(input) {
            if (!input) return;
            const group = input.closest('.input-group');
            if (group) {
                group.classList.remove('has-error');
                input.classList.remove('input-error');
                const msgSpan = group.querySelector('.error-message');
                if (msgSpan) msgSpan.style.display = 'none';
            }
        }

        // --- LÓGICA DE PAGAMENTO DINÂMICO ---
        function togglePaymentFields() {
            const method = document.querySelector('input[name="payment_method"]:checked').value;
            const creditFields = document.getElementById('payment-credit-fields');
            const pixFields = document.getElementById('payment-pix-fields');
            const boletoFields = document.getElementById('payment-boleto-fields');

            // Resetar visibilidade
            creditFields.classList.add('hidden');
            pixFields.classList.add('hidden');
            boletoFields.classList.add('hidden');

            // Mostrar o selecionado
            if (method === 'credit') {
                creditFields.classList.remove('hidden');
            } else if (method === 'pix') {
                pixFields.classList.remove('hidden');
            } else if (method === 'boleto') {
                boletoFields.classList.remove('hidden');
            }
            
            lucide.createIcons();
        }

        // --- SISTEMA DE CICLO MENSAL/ANUAL (AGORA ADAPTÁVEL) ---
        function setCycle(cycle) {
            const btnMonthly = document.getElementById('btn-monthly');
            const btnAnnual = document.getElementById('btn-annual');
            const hiddenInput = document.getElementById('selected-cycle');
            
            hiddenInput.value = cycle;

            // UI Toggle Logic
            if (cycle === 'monthly') {
                btnMonthly.classList.add('bg-white', 'shadow-sm', 'text-gray-900');
                btnMonthly.classList.remove('bg-gray-100', 'text-gray-500');
                btnAnnual.classList.remove('bg-white', 'shadow-sm', 'text-gray-900');
                btnAnnual.classList.add('text-gray-500');
            } else {
                btnAnnual.classList.add('bg-white', 'shadow-sm', 'text-gray-900');
                btnAnnual.classList.remove('text-gray-500');
                btnMonthly.classList.remove('bg-white', 'shadow-sm', 'text-gray-900');
                btnMonthly.classList.add('bg-gray-100', 'text-gray-500');
            }

            // Atualiza preços e descrições dinamicamente para TODOS os planos
            const plans = document.querySelectorAll('#plans-container label');
            
            plans.forEach(plan => {
                const priceEl = plan.querySelector('.price-text');
                const descEl = plan.querySelector('.cycle-desc');
                
                if (priceEl) {
                    const price = cycle === 'monthly' ? priceEl.dataset.monthly : priceEl.dataset.annual;
                    if (price) priceEl.textContent = price;
                }
                
                if (descEl) {
                    const desc = cycle === 'monthly' ? descEl.dataset.textMonthly : descEl.dataset.textAnnual;
                    if (desc) descEl.textContent = desc;
                }
            });

            updateSummary();
        }

        // --- SISTEMA DE ETAPAS (WIZARD 3 STEPS) ---
        
        async function goToStep(step) {
            const step1 = document.getElementById('step-1');
            const step2 = document.getElementById('step-2');
            const step3 = document.getElementById('step-3');
            
            const progressBar = document.getElementById('progress-bar');
            
            // Validações antes de avançar
            if (step === 2) {
                if (!validateStep1()) return;
                
                // Verificar se email ou CPF já existem no banco
                const cadastroDisponivel = await verificarCadastroExistente();
                if (!cadastroDisponivel) {
                    // Mostrar mensagem e impedir continuação
                    return;
                }
            }
            if (step === 3) {
                if (!validateStep2()) return;
                populateReview(); // Preencher resumo
            }

            // Ocultar todos
            step1.classList.remove('active-step'); step1.classList.add('hidden-step'); step1.style.display = 'none';
            step2.classList.remove('active-step'); step2.classList.add('hidden-step'); step2.style.display = 'none';
            step3.classList.remove('active-step'); step3.classList.add('hidden-step'); step3.style.display = 'none';

            // Mostrar o destino
            let targetStep;
            let progressWidth = '0%';

            if (step === 1) {
                targetStep = step1;
                progressWidth = '0%';
                updateIndicators(1);
            } else if (step === 2) {
                targetStep = step2;
                progressWidth = '50%';
                updateIndicators(2);
            } else {
                targetStep = step3;
                progressWidth = '100%';
                updateIndicators(3);
            }

            // Animação de entrada
            targetStep.style.display = 'block';
            requestAnimationFrame(() => {
                targetStep.classList.remove('hidden-step');
                targetStep.classList.add('active-step');
            });
            
            progressBar.style.width = progressWidth;
        }

        function updateIndicators(step) {
            const i1 = document.getElementById('step-indicator-1');
            const i2 = document.getElementById('step-indicator-2');
            const i3 = document.getElementById('step-indicator-3');
            const t1 = document.getElementById('step-text-1');
            const t2 = document.getElementById('step-text-2');
            const t3 = document.getElementById('step-text-3');

            // Resetar estilos base
            const inactiveBg = 'bg-gray-200'; const inactiveText = 'text-gray-500';
            const activeBg = 'bg-[#0500ff]'; const activeText = 'text-white';
            const doneBg = 'bg-green-500';
            
            // Lógica de Estado
            // Step 1
            if (step > 1) {
                i1.className = `w-10 h-10 rounded-full ${doneBg} text-white flex items-center justify-center font-bold text-sm transition-all duration-300 shadow-md ring-4 ring-white`;
                i1.innerHTML = '<i data-lucide="check" class="w-4 h-4"></i>';
                t1.className = 'text-xs font-medium text-green-600 mt-2 transition-colors';
            } else {
                i1.className = `w-10 h-10 rounded-full ${activeBg} text-white flex items-center justify-center font-bold text-sm transition-all duration-300 shadow-md ring-4 ring-white`;
                i1.textContent = '1';
                t1.className = 'text-xs font-bold text-[#0500ff] mt-2 transition-colors';
            }

            // Step 2
            if (step > 2) {
                i2.className = `w-10 h-10 rounded-full ${doneBg} text-white flex items-center justify-center font-bold text-sm transition-all duration-300 shadow-md ring-4 ring-white`;
                i2.innerHTML = '<i data-lucide="check" class="w-4 h-4"></i>';
                t2.className = 'text-xs font-medium text-green-600 mt-2 transition-colors';
            } else if (step === 2) {
                i2.className = `w-10 h-10 rounded-full ${activeBg} text-white flex items-center justify-center font-bold text-sm transition-all duration-300 shadow-md ring-4 ring-white`;
                i2.textContent = '2';
                t2.className = 'text-xs font-bold text-[#0500ff] mt-2 transition-colors';
            } else {
                i2.className = `w-10 h-10 rounded-full ${inactiveBg} ${inactiveText} flex items-center justify-center font-bold text-sm transition-all duration-300 border-2 border-transparent ring-4 ring-white`;
                i2.textContent = '2';
                t2.className = 'text-xs font-medium text-gray-400 mt-2 transition-colors';
            }

            // Step 3
            if (step === 3) {
                i3.className = `w-10 h-10 rounded-full ${activeBg} text-white flex items-center justify-center font-bold text-sm transition-all duration-300 shadow-md ring-4 ring-white`;
                i3.textContent = '3';
                t3.className = 'text-xs font-bold text-[#0500ff] mt-2 transition-colors';
            } else {
                i3.className = `w-10 h-10 rounded-full ${inactiveBg} ${inactiveText} flex items-center justify-center font-bold text-sm transition-all duration-300 border-2 border-transparent ring-4 ring-white`;
                i3.textContent = '3';
                t3.className = 'text-xs font-medium text-gray-400 mt-2 transition-colors';
            }
            lucide.createIcons();
        }

        // Nova função de validação de Data de Nascimento e Idade
        function isValidDateOfBirth(dateString) {
            if (!dateString) return false;

            const dob = new Date(dateString + 'T00:00:00'); // Adiciona T00:00:00 para evitar problemas de fuso
            const today = new Date();
            today.setHours(0, 0, 0, 0); // Normaliza a data de hoje

            // 1. Não pode ser uma data futura
            if (dob > today) {
                return false;
            }

            // 2. Deve ter pelo menos 16 anos (data limite superior)
            const minAgeDate = new Date();
            minAgeDate.setFullYear(minAgeDate.getFullYear() - 16);
            if (dob > minAgeDate) {
                return false; // Idade mínima não atingida (muito jovem)
            }

            // 3. Não pode ser muito antiga (limite para 100 anos - data limite inferior)
            const maxAgeDate = new Date();
            maxAgeDate.setFullYear(maxAgeDate.getFullYear() - 100);
            if (dob < maxAgeDate) {
                return false; // Idade máxima excedida (muito velho)
            }

            return true;
        }

        function validateStep1() {
            let isValid = true;
            
            // Validação Nome e Sobrenome
            const nome = document.getElementById('input-nome');
            if (nome.value.trim().length < 2) { showError(nome, 'Muito curto'); isValid = false; }
            
            const sobrenome = document.getElementById('input-sobrenome');
            if (sobrenome.value.trim().length < 2) { showError(sobrenome, 'Muito curto'); isValid = false; }
            
            // Validação CPF
            const cpf = document.getElementById('cpf');
            if (!isValidCPF(cpf.value)) { showError(cpf, 'CPF inválido'); isValid = false; }

            // Validação Data de Nascimento
            const dobInput = document.getElementById('input-dob');
            if (!isValidDateOfBirth(dobInput.value)) {
                showError(dobInput, 'Data inválida. Idade mínima de 16 anos e máxima de 100 anos.');
                isValid = false;
            }
            
            // Validação Celular
            const phone = document.getElementById('phone');
            if (phone.value.length < 14) { showError(phone, 'Inválido'); isValid = false; }
            
            // --- NOVA VALIDAÇÃO DE E-MAIL ---
            const email = document.getElementById('input-email');
            const emailValue = email.value.trim();
            
            // Regex para verificar a ESTRUTURA básica (letras, números, ponto, hífen e underline)
            const structuralRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
            // Regex para detectar caracteres proibidos (acentos, cedilha, e espaços)
            const accentOrSpaceRegex = /[áéíóúçãõñàèìòù\s]/i; 

            if (!emailValue) {
                showError(email, 'E-mail é obrigatório'); 
                isValid = false; 
            } else if (accentOrSpaceRegex.test(emailValue)) {
                // Erro específico para acentos/caracteres fora do padrão de e-mail
                showError(email, 'E-mail inválido (não use acentos ou caracteres especiais)'); 
                isValid = false; 
            } else if (!structuralRegex.test(emailValue)) {
                // Erro genérico de estrutura (falta @, ponto final, caracteres inválidos que não são acentos, etc.)
                showError(email, 'E-mail inválido'); 
                isValid = false;
            }
            // --- FIM VALIDAÇÃO E-MAIL ---

            // Campos de Endereço e Objetivo (mantidos)
            const cep = document.getElementById('input-cep');
            if (cep.value.length < 9) { showError(cep, 'Incompleto'); isValid = false; }

            const street = document.getElementById('input-street');
            if (street.value.trim().length < 3) { showError(street, 'Obrigatório'); isValid = false; }

            const number = document.getElementById('input-number');
            if (number.value.trim().length < 1) { showError(number, 'Obrigatório'); isValid = false; }

            const neighborhood = document.getElementById('input-neighborhood');
            if (neighborhood.value.trim().length < 2) { showError(neighborhood, 'Obrigatório'); isValid = false; }

            const city = document.getElementById('input-city');
            if (city.value.trim().length < 2) { showError(city, 'Obrigatório'); isValid = false; }

            const state = document.getElementById('input-state');
            if (state.value.trim().length < 2) { showError(state, 'Obrigatório'); isValid = false; }

            const goal = document.getElementById('input-goal');
            if (goal.value === "") { showError(goal, 'Selecione uma opção'); isValid = false; }

            return isValid;
        }

        // Nova função para verificar cadastro duplicado
        async function verificarCadastroExistente() {
            const email = document.getElementById('input-email').value.trim();
            const cpf = document.getElementById('cpf').value.trim();

            try {
                // Simulação de chamada de API. Substituir por sua implementação real.
                /*
                const response = await fetch('/backend/api/auth/check-existing', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ email, cpf })
                });

                const result = await response.json();
                */
                const result = { cadastro_existe: false }; // Simulação: Sempre disponível
                
                if (result.cadastro_existe) {
                    // Mostrar mensagem de erro no campo específico
                    if (result.campo === 'email') {
                        const emailInput = document.getElementById('input-email');
                        showError(emailInput, result.message);
                    } else if (result.campo === 'cpf') {
                        const cpfInput = document.getElementById('cpf');
                        showError(cpfInput, result.message);
                    }

                    // Mostrar alerta visual adicional
                    const alertDiv = document.createElement('div');
                    alertDiv.className = 'fixed top-4 right-4 bg-red-500 text-white px-6 py-4 rounded-lg shadow-lg z-50 flex items-center gap-3';
                    alertDiv.innerHTML = `
                        <i data-lucide="alert-circle" class="w-5 h-5"></i>
                        <div>
                            <strong>Cadastro Existente</strong>
                            <p class="text-sm">${result.message}</p>
                        </div>
                    `;
                    document.body.appendChild(alertDiv);
                    lucide.createIcons();
                    
                    setTimeout(() => alertDiv.remove(), 6000);
                    
                    return false;
                }

                return true;

            } catch (error) {
                console.error('Erro ao verificar cadastro:', error);
                // Em caso de erro na verificação, permitir continuar (fallback)
                return true;
            }
        }

        function validateStep2() {
            const method = document.querySelector('input[name="payment_method"]:checked').value;
            
            // Se for Pix ou Boleto, não há inputs obrigatórios neste passo
            if (method === 'pix' || method === 'boleto') return true;

            // Se for Cartão, valida os campos
            let isValid = true;
            const card = document.getElementById('card_number');
            if (card.value.length < 19) { showError(card, 'Incompleto'); isValid = false; }

            const expiry = document.getElementById('card_expiry');
            if (expiry.value.length < 5) { showError(expiry, 'Inválido'); isValid = false; }

            const cvv = document.getElementById('card_cvv');
            if (cvv.value.length < 3) { showError(cvv, 'Inválido'); isValid = false; }

            const cardName = document.getElementById('card_name');
            if (cardName.value.trim().length < 3) { showError(cardName, 'Obrigatório'); isValid = false; }

            return isValid;
        }

        // --- POPULAR RESUMO (ADAPTÁVEL) ---
        function populateReview() {
            const cycle = document.getElementById('selected-cycle').value;
            const name = document.getElementById('input-nome').value;
            const surname = document.getElementById('input-sobrenome').value;
            const email = document.getElementById('input-email').value;
            const dob = document.getElementById('input-dob').value;
            const method = document.querySelector('input[name="payment_method"]:checked').value;

            // Formata DOB para exibição (necessário '+ 'T00:00:00' para corrigir fuso horário)
            const formattedDob = dob ? new Date(dob + 'T00:00:00').toLocaleDateString('pt-BR') : 'Não informado';

            // Buscar dados do plano selecionado dinamicamente
            const selectedPlanInput = document.querySelector('input[name="plan"]:checked');
            const selectedPlanLabel = selectedPlanInput.closest('label');
            const planTitle = selectedPlanLabel.querySelector('.plan-title').textContent;
            const priceText = selectedPlanLabel.querySelector('.price-text').textContent;

            document.getElementById('review-name').textContent = `${name} ${surname}`;
            document.getElementById('review-email').textContent = email;
            document.getElementById('review-dob').textContent = formattedDob;
            
            const planNameEl = document.getElementById('review-plan-name');
            const planPriceEl = document.getElementById('review-plan-price');

            // Define nome com sufixo do ciclo
            planNameEl.textContent = planTitle + (cycle === 'monthly' ? ' (Mensal)' : ' (Anual)');
            planPriceEl.textContent = 'R$ ' + priceText;

            const methodEl = document.getElementById('review-payment-method');
            if(method === 'credit') methodEl.textContent = 'Cartão de Crédito';
            else if(method === 'pix') methodEl.textContent = 'Pix';
            else methodEl.textContent = 'Boleto Bancário';
        }

        // --- MÁSCARAS DE INPUT ---
        function maskName(input) {
            let value = input.value;
            const prepositions = ['de', 'da', 'do', 'das', 'dos'];
            let words = value.toLowerCase().split(' ');
            words = words.map((word, index) => {
                if (word.length === 0) return word;
                if (index > 0 && prepositions.includes(word)) {
                    return word;
                }
                return word.charAt(0).toUpperCase() + word.slice(1);
            });
            input.value = words.join(' ');
        }
        
        function maskCPF(input) {
            let value = input.value.replace(/\D/g, "");
            value = value.substring(0, 11);
            value = value.replace(/(\d{3})(\d)/, "$1.$2");
            value = value.replace(/(\d{3})(\d)/, "$1.$2");
            value = value.replace(/(\d{3})(\d{1,2})$/, "$1-$2");
            input.value = value;
        }
        
        function isValidCPF(cpf) {
            const cpfDigits = cpf.replace(/\D/g, '');
            return cpfDigits.length === 11;
        }
        
        function maskPhone(input) {
            let value = input.value.replace(/\D/g, "");
            value = value.replace(/^(\d{2})(\d)/g, "($1) $2");
            value = value.replace(/(\d)(\d{4})$/, "$1-$2");
            input.value = value;
        }

        function maskCEP(input) {
            let value = input.value.replace(/\D/g, "");
            value = value.replace(/^(\d{5})(\d)/, "$1-$2");
            input.value = value;
            const cleanCep = value.replace(/\D/g, "");
            if (cleanCep.length === 8) {
                fetchAddress(cleanCep);
            }
        }

        function maskAddressNumber(input) {
            if (!input || !input.value) return;
            let val = input.value.toUpperCase();
            val = val.replace(/[^0-9A-Z]/g, '');
            let valid = "";
            let sawLetter = false;
            for (let char of val) {
                if (/[0-9]/.test(char)) {
                    if (!sawLetter) valid += char;
                } else if (/[A-Z]/.test(char)) {
                    sawLetter = true;
                    valid += char;
                }
            }
            input.value = valid;
        }

        async function fetchAddress(cep) {
            if (!cep) return;
            const cleanCep = cep.replace(/\D/g, '');
            if (cleanCep.length !== 8) return;

            const streetInput = document.getElementById('input-street');
            const neighborhoodInput = document.getElementById('input-neighborhood');
            const cityInput = document.getElementById('input-city');
            const stateInput = document.getElementById('input-state');
            const numberInput = document.getElementById('input-number');
            const loadingIndicator = document.getElementById('cep-loading');
            
            if (!streetInput || !neighborhoodInput || !cityInput || !stateInput) return;

            if (loadingIndicator) {
                loadingIndicator.classList.remove('hidden');
                loadingIndicator.style.display = 'flex';
            }
            
            streetInput.disabled = true;
            neighborhoodInput.disabled = true;

            try {
                const response = await fetch(`https://viacep.com.br/ws/${cleanCep}/json/`);
                if (!response.ok) throw new Error(`Erro HTTP: ${response.status}`);
                const data = await response.json();
                
                // Usando modal customizado em vez de alert
                if (data.erro) {
                    console.warn('CEP não encontrado. Preencha o endereço manualmente.');
                    streetInput.value = '';
                    neighborhoodInput.value = '';
                    cityInput.value = '';
                    stateInput.value = '';
                    streetInput.focus();
                } else {
                    streetInput.value = data.logradouro || '';
                    neighborhoodInput.value = data.bairro || '';
                    cityInput.value = data.localidade || '';
                    stateInput.value = data.uf || '';
                    if (numberInput) numberInput.focus();
                    clearError(streetInput);
                    clearError(neighborhoodInput);
                    clearError(cityInput);
                    clearError(stateInput);
                }
            } catch (error) {
                console.error('Erro ao buscar CEP:', error);
                // Usando modal customizado em vez de alert
                console.error('Erro ao buscar CEP. Verifique sua conexão e tente novamente, ou preencha manualmente.');
            } finally {
                if (loadingIndicator) {
                    loadingIndicator.classList.add('hidden');
                    loadingIndicator.style.display = 'none';
                }
                streetInput.disabled = false;
                neighborhoodInput.disabled = false;
            }
        }
        
        function maskCard(input) {
            let value = input.value.replace(/\D/g, "");
            value = value.replace(/(\d{4})(\d)/, "$1 $2");
            value = value.replace(/(\d{4})(\d)/, "$1 $2");
            value = value.replace(/(\d{4})(\d)/, "$1 $2");
            input.value = value;
        }
        
        function maskExpiry(input) {
            let value = input.value.replace(/\D/g, "");
            if (value.length >= 2) value = value.replace(/^(\d{2})(\d)/, "$1/$2");
            input.value = value;
        }
        
        function maskCVV(input) { input.value = input.value.replace(/\D/g, ""); }

        // --- LÓGICA GERAL ATUALIZADA (ADAPTÁVEL) ---

        function updateSummary() {
            // Busca o input selecionado
            const selectedPlan = document.querySelector('input[name="plan"]:checked');
            if (!selectedPlan) return;

            // Busca o label pai para pegar os dados
            const container = selectedPlan.closest('label');
            const priceEl = container.querySelector('.price-text');
            const mobileTotal = document.getElementById('mobile-total');
            
            if (mobileTotal && priceEl) {
                // Atualiza o total mobile com o texto que está visível no card agora
                mobileTotal.textContent = `R$ ${priceEl.textContent}`;
            }
        }

        // Inicializar com estado padrão
        setCycle('monthly');
        
        // Inicializar método de pagamento ao carregar
        document.addEventListener('DOMContentLoaded', () => {
            lucide.createIcons();
            const defaultPayment = document.querySelector('input[name="payment_method"]');
            if (defaultPayment) {
                defaultPayment.checked = true;
                togglePaymentFields();
            }
            updateSummary(); // Garantir que mobile total inicie correto
        });

        function handleRegister(e) {
            e.preventDefault();
            
            // Coletar todos os dados do formulário
            const userData = {
                nome: document.getElementById('input-nome').value.trim(),
                sobrenome: document.getElementById('input-sobrenome').value.trim(),
                cpf: document.getElementById('cpf').value.trim(),
                dataNascimento: document.getElementById('input-dob').value.trim(), // NOVO CAMPO
                telefone: document.getElementById('phone').value.trim(),
                email: document.getElementById('input-email').value.trim(),
                cep: document.getElementById('input-cep').value.trim(),
                estado: document.getElementById('input-state').value.trim(),
                cidade: document.getElementById('input-city').value.trim(),
                rua: document.getElementById('input-street').value.trim(),
                numero: document.getElementById('input-number').value.trim(),
                bairro: document.getElementById('input-neighborhood').value.trim(),
                objetivo: document.getElementById('input-goal').value,
                plano: document.querySelector('input[name="plan"]:checked').value,
                ciclo: document.getElementById('selected-cycle').value,
                pagamento: document.querySelector('input[name="payment_method"]:checked').value
            };

            // Submeter
            const btn = e.target.querySelector('button[type="submit"]');
            
            btn.disabled = true;
            btn.innerHTML = '<div class="w-5 h-5 border-2 border-white/30 border-t-white rounded-full animate-spin"></div> Processando...';
            
            // Enviar para API Backend
            (async () => {
                try {
                    // Preparar dados para envio
                    const cadastroData = {
                        nome: `${userData.nome} ${userData.sobrenome}`,
                        email: userData.email,
                        senha: userData.cpf.replace(/\D/g, '').substring(0, 8), // Senha inicial = 8 primeiros dígitos do CPF
                        plano: userData.plano,
                        peso: null,
                        altura: null
                    };

                    // Fazer requisição para backend/api/cadastro.php
                    const response = await fetch('../backend/api/cadastro.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(cadastroData)
                    });

                    const data = await response.json();

                    if (data.success) {
                        btn.classList.remove('bg-[#0500ff]', 'hover:bg-[#0400cc]');
                        btn.classList.add('bg-green-500', 'hover:bg-green-600');
                        btn.innerHTML = '<i data-lucide="check" class="w-5 h-5"></i> Matrícula Confirmada!';
                        lucide.createIcons();

                        // Exibir mensagem de sucesso
                        const successDiv = document.createElement('div');
                        successDiv.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-4 rounded-lg shadow-lg z-50';
                        successDiv.innerHTML = `<strong>Sucesso!</strong> Matrícula feita! Verifique seu e-mail no sistema de login.`;
                        document.body.appendChild(successDiv);
                        
                        setTimeout(() => successDiv.remove(), 3000);

                        // Redirecionar para página de email login do React
                        setTimeout(() => {
                            window.location.href = `http://localhost:5173/email-login`;
                        }, 2000);
                    } else {
                        throw new Error(data.message || 'Erro ao processar matrícula');
                    }
                } catch (error) {
                    console.error('Erro ao processar:', error);
                    const errorDiv = document.createElement('div');
                    errorDiv.className = 'fixed top-4 right-4 bg-red-500 text-white px-6 py-4 rounded-lg shadow-lg z-50';
                    errorDiv.innerHTML = `<strong>Erro:</strong> ${error.message || 'Erro ao processar matrícula. Tente novamente.'}`;
                    document.body.appendChild(errorDiv);
                    
                    setTimeout(() => errorDiv.remove(), 5000);
                    
                    btn.disabled = false;
                    btn.classList.add('bg-[#0500ff]', 'hover:bg-[#0400cc]');
                    btn.classList.remove('bg-green-500', 'hover:bg-green-600');
                    btn.innerHTML = '<span>Finalizar Matrícula</span><i data-lucide="check" class="w-5 h-5"></i>';
                    lucide.createIcons();
                }
            })();
        }

        function resetForm() {
            document.getElementById('registration-form').reset();
            document.querySelectorAll('.input-error').forEach(el => el.classList.remove('input-error'));
            document.querySelectorAll('.has-error').forEach(el => el.classList.remove('has-error'));
            document.querySelectorAll('.error-message').forEach(el => el.style.display = 'none');
            document.querySelector('input[name="plan"][value="black"]').checked = true;
            document.querySelector('input[name="payment_method"][value="credit"]').checked = true;
            togglePaymentFields();
            goToStep(1);
            const submitBtn = document.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.classList.remove('bg-green-500', 'hover:bg-green-600');
                submitBtn.classList.add('bg-[#0500ff]', 'hover:bg-[#0400cc]');
                submitBtn.innerHTML = '<span>Finalizar Matrícula</span><i data-lucide="check" class="w-5 h-5"></i>';
                lucide.createIcons();
            }
            updateSummary();
        }
    </script>
</body>
</html>