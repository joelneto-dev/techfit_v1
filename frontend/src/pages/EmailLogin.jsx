import React, { useState, useRef, useEffect } from 'react';
import { useNavigate } from 'react-router-dom';
// Importa os ícones Lucide para React
import { Mail, AlertCircle, ChevronDown, CheckCircle } from 'lucide-react';

// Estilos CSS do arquivo original para garantir que o design seja idêntico
const globalStyles = `
    :root {
        --primary: #0b57d0;
        --text-main: #1f1f1f;
        --text-muted: #444746;
        --border: #747775;
        --bg-body: #f0f4f9;
    }

    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
        font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
    }

    /* APLICADO AO NOVO WRAPPER */
    .app-wrapper {
        background-color: var(--bg-body);
        color: var(--text-main);
        height: 100vh;
        width: 100%; /* Garante que o fundo preencha tudo */
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
    }

    .login-card {
        background-color: white;
        width: 100%;
        max-width: 450px;
        min-height: 500px;
        padding: 48px 40px 36px;
        border-radius: 28px;
        display: flex;
        flex-direction: column;
        transition: height 0.3s ease;
    }

    /* Responsividade para telas muito pequenas */
    @media (max-width: 480px) {
        /* APLICADO AO NOVO WRAPPER */
        .app-wrapper {
            background-color: white;
            align-items: flex-start;
        }
        .login-card {
            padding: 24px;
            border-radius: 0;
            box-shadow: none;
            min-height: 100vh;
        }
    }

    .logo-area {
        display: flex;
        align-items: center;
        justify-content: flex-start;
        gap: 10px;
        margin-bottom: 16px;
    }

    .logo-text {
        font-size: 24px;
        font-weight: 500;
        color: var(--text-main);
    }

    .header-text {
        text-align: center;
        margin-bottom: 40px;
    }

    .header-text h1 {
        font-size: 24px;
        font-weight: 400;
        margin-bottom: 10px;
        color: var(--text-main);
    }

    .header-text p {
        font-size: 16px;
        color: var(--text-main);
    }

    /* Input flutuante estilo Material */
    .input-group {
        position: relative;
        margin-bottom: 16px;
    }

    .input-field {
        width: 100%;
        padding: 13px 15px;
        font-size: 16px;
        border: 1px solid var(--border);
        border-radius: 4px;
        outline: none;
        color: var(--text-main);
        background: transparent;
        z-index: 1;
        transition: 0.2s;
    }

    .input-field:focus {
        border: 2px solid var(--primary);
        padding: 12px 14px;
    }

    .input-label {
        position: absolute;
        left: 14px;
        top: 14px;
        padding: 0 4px;
        background-color: white;
        color: var(--text-muted);
        font-size: 16px;
        transition: 0.2s;
        pointer-events: none;
    }

    /* Estado flutuante do label */
    .input-field:focus ~ .input-label,
    .input-field:not(:placeholder-shown) ~ .input-label {
        top: -10px;
        font-size: 12px;
        color: var(--primary);
        font-weight: 500;
    }
    
    .input-field:not(:focus):not(:placeholder-shown) ~ .input-label {
        color: var(--text-muted);
    }

    /* --- ESTILOS DE ERRO --- */
    .input-group.error .input-field {
        border-color: #d93025 !important;
    }
    
    .input-group.error .input-field:focus {
        border-color: #d93025 !important;
    }

    .input-group.error .input-label,
    .input-group.error .input-field:focus ~ .input-label,
    .input-group.error .input-field:not(:placeholder-shown) ~ .input-label {
        color: #d93025 !important;
    }

    .error-msg {
        color: #d93025;
        font-size: 12px;
        margin-top: 6px;
        display: none;
        align-items: center;
        gap: 5px;
    }
    
    /* Mostrar mensagem de erro forçada pelo React */
    .error-msg.active {
        display: flex;
    }

    .forgot-link {
        color: var(--primary);
        font-weight: 500;
        text-decoration: none;
        font-size: 14px;
        margin-top: 8px;
        display: inline-block;
        border-radius: 4px;
        padding: 2px 4px;
        cursor: default;
    }

    .info-text {
        font-size: 14px;
        color: var(--text-muted);
        margin-top: 30px;
        line-height: 1.5;
    }

    .info-text .static-link {
        color: var(--primary);
        text-decoration: none;
        font-weight: 500;
        cursor: default;
    }

    .actions {
        margin-top: auto;
        padding-top: 40px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .btn-secondary {
        color: var(--primary);
        background: transparent;
        border: none;
        font-weight: 500;
        font-size: 14px;
        padding: 10px 20px;
        border-radius: 20px;
        cursor: default;
        transition: 0.2s;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .btn-primary {
        background-color: var(--primary);
        color: white;
        border: none;
        font-weight: 500;
        font-size: 14px;
        padding: 10px 24px;
        border-radius: 20px;
        cursor: pointer;
        transition: 0.2s;
        position: relative;
        overflow: hidden;
    }

    .btn-primary:hover {
        background-color: #0a4dbe;
        box-shadow: 0 1px 3px rgba(0,0,0,0.3);
    }

    .spinner {
        display: none;
        width: 20px;
        height: 20px;
        border: 3px solid rgba(255,255,255,0.3);
        border-radius: 50%;
        border-top-color: white;
        animation: spin 1s ease-in-out infinite;
    }
    
    /* Mostrar spinner forçada pelo React */
    .btn-primary.loading .btn-text {
        display: none;
    }
    .btn-primary.loading .spinner {
        display: block;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }
    
    /* Estilo para a mensagem de status (substitui o alert) */
    .status-message {
        margin: 10px 0;
        padding: 10px;
        border-radius: 8px;
        font-size: 14px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .status-success {
        background-color: #e6f7e9; /* Light green background */
        color: #1e8e3e; /* Dark green text */
        border: 1px solid #c8e6c9;
    }
    
    .status-error {
        background-color: #fce8e6; /* Light red background */
        color: #d93025; /* Dark red text */
        border: 1px solid #fbb8b8;
    }


    /* Footer externo */
    .footer {
        margin-top: 24px;
        display: flex;
        justify-content: space-between;
        width: 100%;
        max-width: 450px;
        font-size: 12px;
        color: #1f1f1f;
    }

    .footer-left {
        display: flex;
        align-items: center;
    }

    .footer-static-item {
        background: transparent;
        border: none;
        font-size: 12px;
        padding: 8px;
        border-radius: 4px;
        color: #1f1f1f;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        cursor: default;
    }

    .footer-right {
        display: flex;
        gap: 20px;
    }
`;

function App() {
    const navigate = useNavigate();
    const [email, setEmail] = useState('');
    const [isLoading, setIsLoading] = useState(false);
    const [isError, setIsError] = useState(false);
    const [statusMessage, setStatusMessage] = useState({ type: null, text: '' });

    const emailInputRef = useRef(null);
    const nextBtnRef = useRef(null);

    // Substitui o 'alert' por uma mensagem visível na UI, conforme as regras
    const showStatus = (type, text) => {
        setStatusMessage({ type, text });
        if (type === 'success') {
            console.log("Login de Email: " + text);
        }
    }

    const handleLogin = async (e) => {
        e.preventDefault();
        
        const trimmedEmail = email.trim();
        
        // Validação: Campo vazio OU formato inválido
        if (!trimmedEmail || !trimmedEmail.includes('@') || !trimmedEmail.includes('.')) {
            setIsError(true);
            showStatus('error', 'Digite um e-mail válido.');
            emailInputRef.current.focus();
            return;
        }

        // Limpa erro anterior e inicia loading
        setIsError(false);
        setIsLoading(true);
        setStatusMessage({ type: null, text: '' }); // Limpa mensagem de status

        try {
            // Fazer fetch para backend/api/auth.php (check-email)
            const response = await fetch('http://localhost/techfit-sistema/backend/api/auth.php?action=check-email', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    action: 'check-email',
                    email: trimmedEmail
                })
            });

            const data = await response.json();

            setIsLoading(false);

            if (data.success && data.exists) {
                // E-mail existe no banco de dados
                showStatus('success', 'E-mail validado! Redirecionando...');
                
                // Salvar email no sessionStorage
                sessionStorage.setItem('email_session', trimmedEmail);
                
                // Redirecionar para /email-box após 1 segundo
                setTimeout(() => {
                    navigate('/email-box');
                }, 1000);
            } else {
                // E-mail não existe
                setIsError(true);
                showStatus('error', 'E-mail não encontrado no sistema.');
                emailInputRef.current.focus();
            }
        } catch (error) {
            console.error('Erro ao verificar e-mail:', error);
            setIsLoading(false);
            setIsError(true);
            showStatus('error', 'Erro ao conectar com o servidor. Tente novamente.');
        }
    }

    // Efeito para limpar o erro ao digitar (igual ao JS original)
    useEffect(() => {
        if (isError) {
            // Este useEffect garante que o erro será revalidado/limpo ao digitar
            const timer = setTimeout(() => {
                setIsError(false);
                setStatusMessage({ type: null, text: '' });
            }, 100); 
            return () => clearTimeout(timer);
        }
    }, [email]);

    // Lógica de loop de foco (Input <-> Botão)
    useEffect(() => {
        const nextBtn = nextBtnRef.current;
        const emailInput = emailInputRef.current;

        if (!nextBtn || !emailInput) return;

        // Botão -> Input (após Tab)
        const handleNextBtnKeydown = (e) => {
            if (e.key === 'Tab' && !e.shiftKey) {
                e.preventDefault();
                emailInput.focus();
            }
        };

        // Input -> Botão (após Shift+Tab)
        const handleEmailInputKeydown = (e) => {
            if (e.key === 'Tab' && e.shiftKey) {
                e.preventDefault();
                nextBtn.focus();
            }
        };

        nextBtn.addEventListener('keydown', handleNextBtnKeydown);
        emailInput.addEventListener('keydown', handleEmailInputKeydown);

        return () => {
            nextBtn.removeEventListener('keydown', handleNextBtnKeydown);
            emailInput.removeEventListener('keydown', handleEmailInputKeydown);
        };
    }, []);

    // Classe CSS condicional para o grupo de input
    const inputGroupClass = `input-group ${isError ? 'error' : ''}`;
    const nextBtnClass = `btn-primary ${isLoading ? 'loading' : ''}`;

    // Renderiza a UI
    return (
        <>
            {/* Inject CSS styles */}
            <style>{globalStyles}</style>
            
            <div className="app-wrapper"> {/* NOVO WRAPPER para aplicar estilos de fundo e centramento */}
                <div className="login-card" id="loginCard">
                    <div className="logo-area">
                        <Mail style={{ color: 'var(--primary)', width: '32px', height: '32px' }} />
                        <span className="logo-text">Email</span>
                    </div>

                    <div className="header-text" id="headerText">
                        <h1>Fazer login</h1>
                        <p>Use sua Conta Email</p>
                    </div>
                    
                    {statusMessage.type && (
                        <div className={`status-message status-${statusMessage.type}`}>
                            {statusMessage.type === 'success' ? (
                                <CheckCircle style={{ width: '20px', height: '20px' }} />
                            ) : (
                                <AlertCircle style={{ width: '20px', height: '20px' }} />
                            )}
                            {statusMessage.text}
                        </div>
                    )}


                    <form onSubmit={handleLogin} noValidate>
                        <div className={inputGroupClass}>
                            <input 
                                type="email" 
                                id="emailInput" 
                                className="input-field" 
                                placeholder=" " 
                                required 
                                tabIndex="1"
                                value={email}
                                onChange={(e) => setEmail(e.target.value)}
                                ref={emailInputRef}
                            />
                            <label htmlFor="emailInput" className="input-label">Digite seu Email</label>
                            <div className={`error-msg ${isError ? 'active' : ''}`} id="emailError">
                                <AlertCircle style={{ width: '14px', height: '14px' }} />
                                {isError ? statusMessage.text : 'Digite um e-mail válido.'}
                            </div>
                        </div>

                        <span className="forgot-link">Esqueceu seu e-mail?</span>

                        <div className="info-text">
                            Não está no seu computador? Use o modo Convidado para fazer login com privacidade.{' '}
                            <span className="static-link">Saiba mais</span>
                        </div>

                        <div className="actions">
                            <button type="button" className="btn-secondary" disabled={isLoading}>
                                Criar conta
                            </button>
                            
                            <button 
                                type="submit" 
                                className={nextBtnClass} 
                                id="nextBtn" 
                                tabIndex="2"
                                disabled={isLoading}
                                ref={nextBtnRef}
                            >
                                <span className="btn-text">Avançar</span>
                                <div className="spinner"></div>
                            </button>
                        </div>
                    </form>
                </div>

                <div className="footer">
                    <div className="footer-left">
                        <div className="footer-static-item">
                            Português (Brasil)
                            <ChevronDown style={{ width: '14px', height: '14px' }} />
                        </div>
                    </div>
                    <div className="footer-right">
                        <span className="footer-static-item">Ajuda</span>
                        <span className="footer-static-item">Privacidade</span>
                        <span className="footer-static-item">Termos</span>
                    </div>
                </div>
            </div>
        </>
    );
}

export default App;