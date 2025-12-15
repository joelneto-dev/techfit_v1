import React, { useState, useEffect, useRef } from 'react';
import { useNavigate, useLocation } from 'react-router-dom';

const SystemAuthPage = () => {
  const navigate = useNavigate();
  const location = useLocation();
  // Estados para os formulários
  const [activeForm, setActiveForm] = useState('login-form');
  const [lastCodeSentTime, setLastCodeSentTime] = useState(0);
  const [isDizzy, setIsDizzy] = useState(false);
  const [shakeScore, setShakeScore] = useState(0);
  const [lastMousePos, setLastMousePos] = useState({ x: 0, y: 0 });
  
  // Estados para os inputs
  const [loginEmail, setLoginEmail] = useState('');
  const [loginPassword, setLoginPassword] = useState('');
  const [verifyId, setVerifyId] = useState('');
  const [verifyEmail, setVerifyEmail] = useState('');
  const [newPassword, setNewPassword] = useState('');
  const [confirmPassword, setConfirmPassword] = useState('');
  const [forgotEmail, setForgotEmail] = useState('');
  const [forgotCode, setForgotCode] = useState('');
  const [termsAccepted, setTermsAccepted] = useState(false);
  
  // Estados para mostrar/ocultar senhas
  const [showLoginPassword, setShowLoginPassword] = useState(false);
  const [showNewPassword, setShowNewPassword] = useState(false);
  const [showConfirmPassword, setShowConfirmPassword] = useState(false);
  
  // Refs
  const activeEyeBtnRef = useRef(null);
  const activeInputIdRef = useRef(null);
  const sendCodeBtnRef = useRef(null);
  const emailInputRef = useRef(null);
  
  // Constantes
  const COOLDOWN_SECONDS = 15;
  const SHAKE_THRESHOLD = 8000;
  const DECAY_RATE = 50;

  // Inicializar ícones Lucide
  useEffect(() => {
    if (window.lucide && typeof window.lucide.createIcons === 'function') {
      window.lucide.createIcons();
    }
  }, []);

  // Verificar URL para ativação automática
  useEffect(() => {
    const searchParams = new URLSearchParams(location.search);
    const action = searchParams.get('action');
    const code = searchParams.get('code');
    
    if (action === 'verify' && code) {
      // Mudar para o formulário de verificação e preencher o código
      setActiveForm('first-access-step-1');
      setVerifyId(code);
    }
  }, [location]);

  // Timer para shake score
  useEffect(() => {
    const interval = setInterval(() => {
      if (shakeScore > 0 && !isDizzy) {
        setShakeScore(prev => Math.max(0, prev - DECAY_RATE));
      }
    }, 100);
    
    return () => clearInterval(interval);
  }, [shakeScore, isDizzy]);

  // Atualizar botão de enviar código quando voltar para forgot-form
  useEffect(() => {
    if (activeForm === 'forgot-form') {
      updateSendCodeButton();
      updateForwardButton();
    }
  }, [activeForm]);

  // Funções de validação
  const validateEmail = (email) => {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
  };

  const validateTechFitId = (id) => {
    return /^\d{4}-\d{2}$/.test(id);
  };

  // Formatação do Código de Ativação (8 dígitos)
  const handleIdInput = (value) => {
    let formatted = value.replace(/\D/g, '');
    if (formatted.length > 8) formatted = formatted.slice(0, 8);
    setVerifyId(formatted);
  };

  // Mostrar/ocultar formulários
  const showForm = (formId) => {
    setActiveForm(formId);
    resetDizzyState();
  };

  // Login
  const handleLogin = async (e) => {
    e.preventDefault();
    let isValid = true;
    
    if (!loginEmail.trim()) {
      isValid = false;
      alert('Por favor, insira seu email.');
      return;
    } else if (!validateEmail(loginEmail.trim())) {
      isValid = false;
      alert('Insira um email válido.');
      return;
    }
    
    if (!loginPassword) {
      isValid = false;
      alert('Por favor, insira sua senha.');
      return;
    }

    if (isValid) {
      try {
        const response = await fetch('http://localhost/techfit-sistema/backend/api/auth.php?action=login', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            action: 'login',
            email: loginEmail.trim(),
            senha: loginPassword
          })
        });

        const data = await response.json();

        if (data.success) {
          // Verificar status da conta
          if (data.status === 'ativo') {
            // Salvar dados no localStorage
            localStorage.setItem('user_id', data.user_id);
            localStorage.setItem('user_email', data.email);
            localStorage.setItem('user_nome', data.nome);
            localStorage.setItem('user_plano', data.plano);
            localStorage.setItem('user_tema', data.tema || 'light');
            
            alert('Login realizado com sucesso!');
            // Redirecionar para dashboard
            navigate('/dashboard-aluno');
          } else if (data.status === 'pendente') {
            alert('Sua conta ainda não foi ativada. Por favor, acesse seu e-mail interno para obter o código de ativação.');
            // Opcional: redirecionar para email-login
            // navigate('/email-login');
          }
        } else {
          alert(data.message || 'Erro ao fazer login. Verifique suas credenciais.');
        }
      } catch (error) {
        console.error('Erro ao fazer login:', error);
        alert('Erro ao conectar com o servidor. Tente novamente.');
      }
    }
  };

  // Verificação e Ativação (Etapa 1)
  const handleVerification = async (e) => {
    e.preventDefault();
    let isValid = true;

    if (!verifyId.trim()) {
      isValid = false;
      alert('Código de ativação obrigatório.');
      return;
    }

    if (!verifyEmail.trim()) {
      isValid = false;
      alert('Email obrigatório.');
      return;
    } else if (!validateEmail(verifyEmail.trim())) {
      isValid = false;
      alert('Email inválido.');
      return;
    }

    if (isValid) {
      try {
        const response = await fetch('http://localhost/techfit-sistema/backend/api/auth.php?action=activate', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            action: 'activate',
            email: verifyEmail.trim(),
            codigo_ativacao: verifyId.trim()
          })
        });

        const data = await response.json();

        if (data.success) {
          alert('Conta ativada com sucesso! Agora você pode fazer login.');
          // Limpar campos e voltar para tela de login
          setVerifyId('');
          setVerifyEmail('');
          setLoginEmail(verifyEmail.trim());
          showForm('login-form');
        } else {
          alert(data.message || 'Erro ao ativar conta. Verifique os dados.');
        }
      } catch (error) {
        console.error('Erro ao ativar conta:', error);
        alert('Erro ao conectar com o servidor. Tente novamente.');
      }
    }
  };

  // Ativação (Etapa 2)
  const handleActivation = (e) => {
    e.preventDefault();
    let isValid = true;

    if (!newPassword) {
      isValid = false;
      alert('Defina uma senha.');
    } else if (newPassword.length < 8) {
      isValid = false;
      alert('A senha deve ter no mínimo 8 caracteres.');
    }

    if (!confirmPassword) {
      isValid = false;
      alert('Confirme sua senha.');
    } else if (confirmPassword !== newPassword) {
      isValid = false;
      alert('As senhas não coincidem.');
    }

    if (!termsAccepted) {
      isValid = false;
      alert('Você precisa aceitar os termos e condições.');
    }

    if (isValid) {
      alert('Conta ativada com sucesso! Bem-vindo ao TechFit.');
      showForm('login-form');
    }
  };

  // Esqueci a senha - Enviar código
  const handleForgotSubmit = (e) => {
    e.preventDefault();
    
    if (!forgotEmail.trim()) {
      alert('Insira seu email cadastrado.');
      return;
    } else if (!validateEmail(forgotEmail.trim())) {
      alert('Email inválido.');
      return;
    }
    
    setLastCodeSentTime(Date.now());
    showForm('forgot-code-form');
  };

  // Esqueci a senha - Verificar código
  const handleCodeSubmit = (e) => {
    e.preventDefault();
    
    if (!forgotCode.trim() || forgotCode.length < 6) {
      alert('Insira o código de 6 dígitos.');
      return;
    }

    alert('Código validado com sucesso! Redirecionando para redefinir senha...');
    showForm('login-form');
  };

  // Atualizar botão de avanço
  const updateForwardButton = () => {
    // Implementação visual será feita no JSX
  };

  // Atualizar botão de enviar código
  const updateSendCodeButton = () => {
    const now = Date.now();
    const elapsed = Math.floor((now - lastCodeSentTime) / 1000);
    const remaining = COOLDOWN_SECONDS - elapsed;

    if (sendCodeBtnRef.current && emailInputRef.current) {
      const span = sendCodeBtnRef.current.querySelector('span');
      
      if (remaining > 0) {
        sendCodeBtnRef.current.disabled = true;
        emailInputRef.current.disabled = true;
        span.innerText = `Aguarde (${remaining}s)`;
        
        // Configurar intervalo para contagem regressiva
        const interval = setInterval(() => {
          const currentNow = Date.now();
          const currentElapsed = Math.floor((currentNow - lastCodeSentTime) / 1000);
          const currentRemaining = COOLDOWN_SECONDS - currentElapsed;

          if (currentRemaining <= 0) {
            clearInterval(interval);
            sendCodeBtnRef.current.disabled = false;
            emailInputRef.current.disabled = false;
            span.innerText = 'Enviar Código';
          } else {
            span.innerText = `Aguarde (${currentRemaining}s)`;
          }
        }, 1000);
        
        sendCodeBtnRef.current.dataset.interval = interval;
      }
    }
  };

  // Interatividade dos olhos
  const togglePasswordVisibility = (type, btn) => {
    if (isDizzy) return;
    
    switch (type) {
      case 'login':
        setShowLoginPassword(!showLoginPassword);
        break;
      case 'new':
        setShowNewPassword(!showNewPassword);
        break;
      case 'confirm':
        setShowConfirmPassword(!showConfirmPassword);
        break;
    }
    
    if (activeEyeBtnRef.current && activeEyeBtnRef.current !== btn) {
      closeEye();
    }
    
    if (btn) {
      activeEyeBtnRef.current = btn;
    }
  };

  const closeEye = () => {
    if (activeEyeBtnRef.current) {
      activeEyeBtnRef.current = null;
    }
    activeInputIdRef.current = null;
  };

  const resetDizzyState = () => {
    closeEye();
    setIsDizzy(false);
    setShakeScore(0);
  };

  // Efeito de mouse para os olhos
  const handleMouseMove = (e) => {
    if (!activeEyeBtnRef.current || isDizzy) return;
    
    const dist = Math.hypot(e.clientX - lastMousePos.x, e.clientY - lastMousePos.y);
    setLastMousePos({ x: e.clientX, y: e.clientY });
    setShakeScore(prev => prev + dist);
    
    if (shakeScore > SHAKE_THRESHOLD) {
      triggerDizzyMode();
    }
  };

  const triggerDizzyMode = () => {
    if (!activeEyeBtnRef.current) return;
    setIsDizzy(true);
    
    setTimeout(() => {
      if (!isDizzy) return;
      setTimeout(() => {
        closeEye();
        setIsDizzy(false);
        setShakeScore(0);
      }, 3000);
    }, 500);
  };

  // SVGs dos olhos
  const closedEyeSvg = (
    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
      <path d="M2 10s3.5 6 10 6 10-6 10-6"/>
      <path d="M12 16v2"/><path d="M7 15l-1 2"/><path d="M17 15l1 2"/>
    </svg>
  );

  const openEyeSvg = (
    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" className="eye-svg">
      <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/>
      <circle className="eye-pupil" cx="12" cy="12" r="3" fill="currentColor" stroke="none" />
    </svg>
  );

  const spiralEyeSvg = (
    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" className="eye-svg">
      <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/>
      <g className="spiral-group-enter">
        <path className="dizzy-spiral-path" d="M12 12.5 a 0.5 0.5 0 1 1 0 -1 a 1.5 1.5 0 1 1 0 3 a 2.5 2.5 0 1 1 0 -5" fill="none" stroke="currentColor" strokeWidth="1.3" />
      </g>
    </svg>
  );

  return (
    <div className="bg-white h-screen overflow-hidden flex flex-col lg:flex-row" onMouseMove={handleMouseMove}>
      
      {/* ================= ESQUERDA: FORMULÁRIOS ================= */}
      <div className="w-full lg:w-1/2 h-full flex flex-col p-8 lg:p-16 overflow-y-auto relative">
        
        {/* Logo */}
        <div className="flex items-center cursor-pointer mb-12" onClick={() => window.location.href = 'index.html'}>
          <img src="media/images/brand/logo-dark.webp" alt="TechFit" className="h-8 w-auto" />
        </div>

        {/* Container Centralizado */}
        <div className="max-w-md w-full mx-auto my-auto">
          
          {/* === 1. LOGIN === */}
          <div id="login-form" className={`form-section ${activeForm === 'login-form' ? 'fade-in' : 'hidden-form'}`}>
            <h1 className="text-4xl font-bold text-gray-900 mb-3">Bem-vindo de volta!</h1>
            <p className="text-gray-500 mb-8">Por favor, insira seus dados para entrar.</p>

            <form id="form-login-element" onSubmit={handleLogin} className="space-y-5" noValidate>
              <div className="input-group">
                <label className="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Email</label>
                <input 
                  id="login-email" 
                  type="email" 
                  placeholder="exemplo@email.com" 
                  className="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-[#0500ff] focus:ring-2 focus:ring-[#0500ff]/20 outline-none transition-all placeholder-gray-300 text-gray-700 bg-gray-50/50 focus:bg-white"
                  value={loginEmail}
                  onChange={(e) => setLoginEmail(e.target.value)}
                />
              </div>

              <div className="input-group">
                <div className="flex justify-between items-center mb-2">
                  <label className="block text-xs font-bold text-gray-500 uppercase tracking-wider">Senha</label>
                  <button type="button" onClick={() => showForm('forgot-form')} className="text-xs font-bold text-[#0500ff] hover:underline">Esqueceu?</button>
                </div>
                <div className="relative">
                  <input 
                    id="login-password" 
                    type={showLoginPassword ? "text" : "password"} 
                    placeholder="••••••••" 
                    className="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-[#0500ff] focus:ring-2 focus:ring-[#0500ff]/20 outline-none transition-all placeholder-gray-300 text-gray-700 bg-gray-50/50 focus:bg-white pr-12"
                    value={loginPassword}
                    onChange={(e) => setLoginPassword(e.target.value)}
                  />
                  <button 
                    type="button" 
                    id="btn-login-eye" 
                    onClick={(e) => togglePasswordVisibility('login', e.currentTarget)}
                    className="absolute right-4 top-3.5 text-gray-400 hover:text-[#0500ff] transition-colors focus:outline-none"
                  >
                    {showLoginPassword ? openEyeSvg : closedEyeSvg}
                  </button>
                </div>
              </div>

              <button type="submit" className="w-full bg-[#0500ff] hover:bg-[#0400cc] text-white font-bold py-4 rounded-xl shadow-[0_4px_14px_0_rgba(5,0,255,0.39)] transition-transform hover:scale-[1.02] active:scale-[0.98]">
                Entrar
              </button>
            </form>

            <div className="relative my-8">
              <div className="absolute inset-0 flex items-center"><div className="w-full border-t border-gray-200"></div></div>
              <div className="relative flex justify-center text-sm"><span className="px-2 bg-white text-gray-400">ou</span></div>
            </div>

            <div className="grid grid-cols-2 gap-4">
              <button className="flex items-center justify-center gap-2 px-4 py-3 border border-gray-200 rounded-xl hover:bg-gray-50 transition-colors font-medium text-gray-700 text-sm">
                <img src="https://www.svgrepo.com/show/475656/google-color.svg" className="w-5 h-5" alt="Google" /> Google
              </button>
              <button className="flex items-center justify-center gap-2 px-4 py-3 border border-gray-200 rounded-xl hover:bg-gray-50 transition-colors font-medium text-gray-700 text-sm">
                <img src="https://www.svgrepo.com/show/475633/apple-color.svg" className="w-5 h-5" alt="Apple" /> Apple
              </button>
            </div>

            <p className="text-center mt-8 text-sm text-gray-500">
              Primeiro acesso? 
              <button onClick={() => showForm('first-access-step-1')} className="text-[#0500ff] font-bold hover:underline ml-1">Clique aqui</button>
            </p>
          </div>

          {/* === 2. PRIMEIRO ACESSO - ETAPA 1 (VERIFICAÇÃO) === */}
          <div id="first-access-step-1" className={`form-section ${activeForm === 'first-access-step-1' ? 'fade-in' : 'hidden-form'}`}>
            <h1 className="text-4xl font-bold text-gray-900 mb-3">Primeiro Acesso</h1>
            <p className="text-gray-500 mb-8">Identifique-se para configurar sua conta.</p>

            <form onSubmit={handleVerification} className="space-y-5" noValidate>
              {/* Campo Código de Ativação */}
              <div className="input-group">
                <label className="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Código de Ativação</label>
                <input 
                  id="verify-id" 
                  type="text" 
                  placeholder="Ex: 12345678" 
                  maxLength="8" 
                  className="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-[#0500ff] focus:ring-2 focus:ring-[#0500ff]/20 outline-none transition-all placeholder-gray-300 text-gray-700 bg-gray-50/50 focus:bg-white font-mono tracking-wide"
                  value={verifyId}
                  onChange={(e) => handleIdInput(e.target.value)}
                />
              </div>

              {/* Campo Email */}
              <div className="input-group">
                <label className="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Confirme seu Email</label>
                <input 
                  id="verify-email" 
                  type="email" 
                  placeholder="seu@email.com" 
                  className="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-[#0500ff] focus:ring-2 focus:ring-[#0500ff]/20 outline-none transition-all placeholder-gray-300 text-gray-700 bg-gray-50/50 focus:bg-white"
                  value={verifyEmail}
                  onChange={(e) => setVerifyEmail(e.target.value)}
                />
              </div>

              <button type="submit" className="w-full bg-[#0500ff] hover:bg-[#0400cc] text-white font-bold py-4 rounded-xl shadow-[0_4px_14px_0_rgba(5,0,255,0.39)] transition-transform hover:scale-[1.02] active:scale-[0.98]">
                Verificar
              </button>
            </form>

            <p className="text-center mt-8 text-sm text-gray-500">
              Já possui senha? 
              <button onClick={() => showForm('login-form')} className="text-[#0500ff] font-bold hover:underline ml-1">Fazer Login</button>
            </p>
          </div>

          {/* === 3. PRIMEIRO ACESSO - ETAPA 2 (CRIAÇÃO DE SENHA) === */}
          <div id="first-access-step-2" className={`form-section ${activeForm === 'first-access-step-2' ? 'fade-in' : 'hidden-form'}`}>
            <div className="mb-6">
              <button onClick={() => showForm('first-access-step-1')} className="flex items-center gap-2 text-gray-500 hover:text-[#0500ff] transition-colors text-sm font-bold">
                <i data-lucide="arrow-left" className="w-4 h-4"></i> Voltar
              </button>
            </div>

            <h1 className="text-4xl font-bold text-gray-900 mb-3">Definir Senha</h1>
            <p className="text-gray-500 mb-8">Crie uma senha segura para acessar sua conta.</p>

            <form onSubmit={handleActivation} className="space-y-5" noValidate>
              
              {/* Email (Desabilitado) */}
              <div className="input-group">
                <label className="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Email Confirmado</label>
                <input 
                  id="confirmed-email" 
                  type="email" 
                  disabled 
                  className="w-full px-4 py-3 rounded-lg border border-gray-200 bg-gray-100 text-gray-500 cursor-not-allowed select-none font-medium"
                  value={verifyEmail}
                  readOnly
                />
              </div>

              {/* Senha */}
              <div className="input-group">
                <label className="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Senha</label>
                <div className="relative">
                  <input 
                    id="new-password" 
                    type={showNewPassword ? "text" : "password"} 
                    placeholder="Mínimo 8 caracteres" 
                    className="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-[#0500ff] focus:ring-2 focus:ring-[#0500ff]/20 outline-none transition-all placeholder-gray-300 text-gray-700 bg-gray-50/50 focus:bg-white pr-12"
                    value={newPassword}
                    onChange={(e) => setNewPassword(e.target.value)}
                  />
                  <button 
                    type="button" 
                    id="btn-new-eye" 
                    onClick={(e) => togglePasswordVisibility('new', e.currentTarget)}
                    className="absolute right-4 top-3.5 text-gray-400 hover:text-[#0500ff] transition-colors focus:outline-none"
                  >
                    {showNewPassword ? openEyeSvg : closedEyeSvg}
                  </button>
                </div>
              </div>

              {/* Confirmar Senha */}
              <div className="input-group">
                <label className="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Confirmar Senha</label>
                <div className="relative">
                  <input 
                    id="confirm-password" 
                    type={showConfirmPassword ? "text" : "password"} 
                    placeholder="Repita a senha" 
                    className="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-[#0500ff] focus:ring-2 focus:ring-[#0500ff]/20 outline-none transition-all placeholder-gray-300 text-gray-700 bg-gray-50/50 focus:bg-white pr-12"
                    value={confirmPassword}
                    onChange={(e) => setConfirmPassword(e.target.value)}
                  />
                  <button 
                    type="button" 
                    id="btn-confirm-eye" 
                    onClick={(e) => togglePasswordVisibility('confirm', e.currentTarget)}
                    className="absolute right-4 top-3.5 text-gray-400 hover:text-[#0500ff] transition-colors focus:outline-none"
                  >
                    {showConfirmPassword ? openEyeSvg : closedEyeSvg}
                  </button>
                </div>
              </div>

              {/* Termos */}
              <div className="relative flex items-center gap-2 input-group">
                <div id="terms-arrow" className={`absolute -left-8 top-1/2 -translate-y-1/2 text-[#ef4444] ${termsAccepted ? 'hidden' : ''}`}>
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                    <path d="M5 12h14"/>
                    <path d="m12 5 7 7-7 7"/>
                  </svg>
                </div>
                
                <input 
                  type="checkbox" 
                  id="terms" 
                  className="w-4 h-4 text-[#0500ff] rounded border-gray-300 focus:ring-[#0500ff]"
                  checked={termsAccepted}
                  onChange={(e) => setTermsAccepted(e.target.checked)}
                />
                <label htmlFor="terms" className="text-sm text-gray-500 cursor-pointer select-none">
                  Eu concordo com os <a href="termos.html" target="_blank" rel="noopener noreferrer" className="text-gray-900 underline">Termos</a> e <a href="termos.html" target="_blank" rel="noopener noreferrer" className="text-gray-900 underline">Política</a>.
                </label>
              </div>

              <button type="submit" className="w-full bg-[#0500ff] hover:bg-[#0400cc] text-white font-bold py-4 rounded-xl shadow-[0_4px_14px_0_rgba(5,0,255,0.39)] transition-transform hover:scale-[1.02] active:scale-[0.98]">
                Ativar Conta
              </button>
            </form>
          </div>

          {/* === 4A. RECUPERAÇÃO - PASSO 1 (DIGITAR EMAIL) === */}
          <div id="forgot-form" className={`form-section ${activeForm === 'forgot-form' ? 'fade-in' : 'hidden-form'}`}>
            <div className="mb-6 flex justify-between items-center">
              <button onClick={() => showForm('login-form')} className="flex items-center gap-2 text-gray-500 hover:text-[#0500ff] transition-colors text-sm font-bold">
                <i data-lucide="arrow-left" className="w-4 h-4"></i> Voltar ao Login
              </button>

              {/* Novo Botão: Ir para Código (Inicia oculto) */}
              <button 
                id="btn-forward-to-code" 
                onClick={() => showForm('forgot-code-form')}
                className={`flex items-center gap-2 text-gray-500 hover:text-[#0500ff] transition-colors text-sm font-bold ${lastCodeSentTime > 0 ? '' : 'hidden'}`}
              >
                Digitar Código <i data-lucide="arrow-right" className="w-4 h-4"></i>
              </button>
            </div>

            <h1 className="text-4xl font-bold text-gray-900 mb-3">Recuperar Senha</h1>
            <p className="text-gray-500 mb-8">Digite seu e-mail e enviaremos um código para redefinir sua senha.</p>

            <form onSubmit={handleForgotSubmit} className="space-y-5" noValidate>
              <div className="input-group">
                <label className="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Email Cadastrado</label>
                <input 
                  id="forgot-email" 
                  type="email" 
                  placeholder="exemplo@email.com" 
                  className="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-[#0500ff] focus:ring-2 focus:ring-[#0500ff]/20 outline-none transition-all placeholder-gray-300 text-gray-700 bg-gray-50/50 focus:bg-white"
                  value={forgotEmail}
                  onChange={(e) => setForgotEmail(e.target.value)}
                  ref={emailInputRef}
                />
              </div>

              <div className="flex justify-center pt-2">
                <button 
                  id="btn-send-code" 
                  type="submit" 
                  className="text-[#0500ff] font-bold hover:underline text-sm uppercase tracking-wide transition-all disabled:opacity-50 disabled:cursor-not-allowed disabled:no-underline"
                  ref={sendCodeBtnRef}
                >
                  <span>Enviar Código</span>
                </button>
              </div>
            </form>
          </div>

          {/* === 4B. RECUPERAÇÃO - PASSO 2 (DIGITAR CÓDIGO) === */}
          <div id="forgot-code-form" className={`form-section ${activeForm === 'forgot-code-form' ? 'fade-in' : 'hidden-form'}`}>
            <div className="mb-6">
              <button onClick={() => showForm('forgot-form')} className="flex items-center gap-2 text-gray-500 hover:text-[#0500ff] transition-colors text-sm font-bold">
                <i data-lucide="arrow-left" className="w-4 h-4"></i> Voltar
              </button>
            </div>

            <h1 className="text-4xl font-bold text-gray-900 mb-3">Recuperar Senha</h1>
            <p className="text-gray-500 mb-8">Digite seu e-mail e enviaremos um código para redefinir sua senha.</p>

            <form onSubmit={handleCodeSubmit} className="space-y-5" noValidate>
              <div className="input-group">
                <label className="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Código Enviado</label>
                <input 
                  id="forgot-code" 
                  type="text" 
                  placeholder="000000" 
                  maxLength="6" 
                  className="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-[#0500ff] focus:ring-2 focus:ring-[#0500ff]/20 outline-none transition-all placeholder-gray-300 text-gray-700 bg-gray-50/50 focus:bg-white font-mono tracking-widest text-center"
                  value={forgotCode}
                  onChange={(e) => setForgotCode(e.target.value)}
                />
              </div>

              <div className="flex justify-center pt-2">
                <button type="submit" className="text-[#0500ff] font-bold hover:underline text-sm uppercase tracking-wide transition-all">
                  <span>Verificar Código</span>
                </button>
              </div>
            </form>
          </div>

        </div>
        
        {/* Footer simples */}
        <div className="mt-auto text-center lg:text-left text-xs text-gray-400 pt-8">
          &copy; 2024 TechFit. Todos os direitos reservados.
        </div>
      </div>

      {/* ================= DIREITA: IMAGEM DESTAQUE ================= */}
      <div className="hidden md:block md:w-1/2 relative bg-gray-100">
        <img
          src="media/images/login/login-bg.webp"
          alt="Gym Aesthetic"
          className="absolute inset-0 w-full h-full object-cover"
        />
        <div
          className="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"
        ></div>
        <div className="absolute bottom-12 left-12 right-12 text-white">
          <div className="bg-white/10 backdrop-blur-md p-6 rounded-2xl border border-white/20 inline-block max-w-md">
            <h3 className="text-2xl md:text-3xl font-bold text-white mb-4 leading-tight font-serif italic">
              "A disciplina é a ponte entre metas e realizações."
            </h3>
            <div className="flex items-center gap-3">
              <div className="w-8 h-0.5 bg-[#0500ff]"></div>
              <span className="text-sm font-bold text-gray-300 tracking-wider uppercase">TechFit Philosophy</span>
            </div>
          </div>
        </div>
      </div>

      <style>{`
        /* Fonte Inter */
        body {
          font-family: 'Inter', sans-serif;
        }

        /* Estilos de Erro (Validação) */
        .input-error {
          border-color: #ef4444 !important;
          background-color: #fef2f2 !important;
        }
        .input-error:focus {
          box-shadow: 0 0 0 2px rgba(239, 68, 68, 0.2) !important;
        }
        .error-message {
          color: #ef4444;
          font-size: 0.75rem;
          margin-top: 0.25rem;
          font-weight: 500;
          display: block;
          animation: fadeIn 0.3s ease-in-out;
        }

        /* Animação suave para troca de formulários */
        .fade-in {
          animation: fadeIn 0.4s ease-in-out forwards;
        }

        @keyframes fadeIn {
          from {
            opacity: 0;
            transform: translateY(10px);
          }
          to {
            opacity: 1;
            transform: translateY(0);
          }
        }

        .hidden-form {
          display: none;
        }

        /* 1. Comportamento Normal da Pupila */
        .eye-pupil {
          transition: transform 0.1s cubic-bezier(0.25, 0.46, 0.45, 0.94);
          transform-origin: 12px 12px;
        }

        /* 2. Animação de SAÍDA da Pupila */
        .pupil-vanish {
          transition: all 0.5s ease-in !important;
          transform: translate(0px, 0px) rotate(720deg) scale(0.1) !important;
          opacity: 0;
        }

        /* 3. Animação de GIRO INFINITO da Espiral */
        @keyframes spinSpiral {
          from { transform: rotate(0deg); }
          to { transform: rotate(360deg); }
        }

        .dizzy-spiral-path {
          transform-origin: 12px 12.5px;
          animation: spinSpiral 1.5s linear infinite;
        }

        /* 4. Animação de ENTRADA da Espiral */
        @keyframes spiralGrow {
          from { transform: scale(0); opacity: 0; }
          to { transform: scale(1); opacity: 1; }
        }

        .spiral-group-enter {
          transform-origin: 12px 12px;
          animation: spiralGrow 0.4s ease-out forwards;
        }
      `}</style>
    </div>
  );
};

export default SystemAuthPage;