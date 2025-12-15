import React, { useState, useEffect, useRef } from 'react';
import { useNavigate } from 'react-router-dom';
import { 
  Menu, MailOpen, Search, Camera, UserPlus, LogOut, 
  Pencil, Inbox, Star, Send, AlertCircle, Square, 
  ChevronDown, RotateCw, EllipsisVertical, ArrowLeft, 
  Archive, Trash2, Mail
} from 'lucide-react';

// Dados mockados de emails
const mockEmailsData = [
  {
    id: 5,
    sender: "Bruno Augusto de Moraes",
    subject: "Qual a boa do final de semana?",
    preview: "Fala João, beleza? Cara, depois dessa semana a gente merece...",
    body: "Fala João, beleza?\n\nCara, depois dessa semana a gente merece uma folga. Tô pensando em assar uma carne aqui em casa no domingo. Junta a galera e vem.\n\nJá chamei o Samuel também, o papai do ano, pra ele relaxar um pouco antes da correria.\n\nZero estresse, só resenha e comida boa. Nada de formativa hahaha.\n\nMe dá um toque se rolar.\n\nAbraço,\nBrunão.",
    date: "10:00",
    read: false,
    folder: "starred",
    selected: false
  },
  {
    id: 6,
    sender: "Samuel Costa",
    subject: "Convite pro chá revelação",
    preview: "Fala pessoal! O mistério vai acabar: Rhavena ou Oliver? Queremos vocês...",
    body: "Fala pessoal!\n\nO mistério vai acabar: Rhavena ou Oliver? Façam suas apostas!\n\nQueremos vocês com a gente pra descobrir. Sábado agora, às 15h, aqui em casa. Apareçam pra comer um bolo e dar risada com a gente.\n\nQuem acertar ganha um ponto extra na avaliativa de PHP.\n\nEspero vocês!\n\nAbraço,\nSamuel.",
    date: "09:30",
    read: false,
    folder: "starred",
    selected: false
  }
];

// Componente principal
export default function Email() {
  const navigate = useNavigate();
  const [sidebarOpen, setSidebarOpen] = useState(false);
  const [profileOpen, setProfileOpen] = useState(false);
  const [currentFolder, setCurrentFolder] = useState('inbox');
  const [searchTerm, setSearchTerm] = useState('');
  const [selectedEmailId, setSelectedEmailId] = useState(null);
  const [refreshing, setRefreshing] = useState(false);
  const [userData, setUserData] = useState(null);
  const [emailsData, setEmailsData] = useState([...mockEmailsData]);
  
  const profileRef = useRef(null);
  const avatarRef = useRef(null);

  // Carregar dados do usuário e mensagens do backend
  useEffect(() => {
    const fetchEmailsAndUserData = async () => {
      try {
        // Obter email do sessionStorage
        const userEmail = sessionStorage.getItem('email_session');
        
        if (!userEmail) {
          // Se não houver email na sessão, redirecionar para login
          navigate('/email-login');
          return;
        }

        // Buscar mensagens do backend
        const response = await fetch(`http://localhost/techfit-sistema/backend/api/mensagens.php?user_email=${encodeURIComponent(userEmail)}`);
        const data = await response.json();

        if (data.success && data.mensagens) {
          // Mapear mensagens do backend para o formato do frontend
          const mappedEmails = data.mensagens.map((msg, index) => ({
            id: msg.id,
            sender: "TechFit Sistema",
            subject: msg.assunto,
            preview: msg.corpo.substring(0, 100) + '...',
            body: msg.corpo,
            date: new Date(msg.data_envio).toLocaleString('pt-BR'),
            read: msg.lida,
            folder: 'inbox',
            selected: false
          }));

          // Adicionar emails mockados (amigos) junto com os do sistema
          setEmailsData([...mappedEmails, ...mockEmailsData]);

          // Dados do usuário (extrair do email)
          const nome = userEmail.split('@')[0];
          setUserData({
            nome: nome.charAt(0).toUpperCase() + nome.slice(1),
            sobrenome: '',
            email: userEmail
          });
        }
      } catch (error) {
        console.error('Erro ao buscar mensagens:', error);
        // Manter emails mockados em caso de erro
      }
    };

    fetchEmailsAndUserData();
  }, [navigate]);

  // Fechar dropdown ao clicar fora
  useEffect(() => {
    const handleClickOutside = (event) => {
      if (profileOpen && 
          profileRef.current && 
          !profileRef.current.contains(event.target) &&
          avatarRef.current &&
          !avatarRef.current.contains(event.target)) {
        setProfileOpen(false);
      }
    };

    document.addEventListener('mousedown', handleClickOutside);
    return () => document.removeEventListener('mousedown', handleClickOutside);
  }, [profileOpen]);

  // Filtrar emails
  const filteredEmails = emailsData.filter(email => {
    const matchesFolder = email.folder === currentFolder;
    const matchesSearch = searchTerm === '' || 
      email.subject.toLowerCase().includes(searchTerm.toLowerCase()) ||
      email.sender.toLowerCase().includes(searchTerm.toLowerCase()) ||
      email.body.toLowerCase().includes(searchTerm.toLowerCase());
    return matchesFolder && matchesSearch;
  });

  // Atualizar contador de não lidos
  const unreadCount = emailsData.filter(e => e.folder === 'inbox' && !e.read).length;

  // Iniciais do usuário
  const userInitials = userData ? 
    `${userData.nome.charAt(0)}${userData.sobrenome.charAt(0)}`.toUpperCase() : 
    "JV";

  // Funções de interação
  const handleRefresh = () => {
    setRefreshing(true);
    setTimeout(() => setRefreshing(false), 500);
  };

  const handleFolderChange = (folder) => {
    setCurrentFolder(folder);
    setSelectedEmailId(null);
    if (window.innerWidth <= 768) {
      setSidebarOpen(false);
    }
  };

  const handleEmailClick = (id) => {
    setSelectedEmailId(id);
    
    // Marcar como lido
    setEmailsData(prev => prev.map(email => 
      email.id === id ? { ...email, read: true } : email
    ));
  };

  const handleLogout = () => {
    if (window.confirm('Deseja realmente sair? (Simulação)')) {
      // Apenas recarrega a página ou reseta estado na demo
      window.location.reload();
    }
  };

  const selectedEmail = selectedEmailId 
    ? emailsData.find(e => e.id === selectedEmailId) 
    : null;

  // Estilos CSS em constante
  const styles = {
    root: {
      '--primary': '#0b57d0',
      '--primary-bg': '#c2e7ff',
      '--bg-body': '#f6f8fc',
      '--bg-white': '#ffffff',
      '--text-main': '#1f1f1f',
      '--text-muted': '#444746',
      '--border': '#e7e7e7',
      '--hover-bg': '#f2f2f2',
      '--selected-bg': '#c2dbff',
      '--sidebar-width': '256px'
    }
  };

  return (
    <div className="email-container" style={styles.root}>
      {/* Header */}
      <header>
        <div className="logo-area">
          <button 
            className="menu-btn" 
            onClick={() => setSidebarOpen(!sidebarOpen)}
          >
            <Menu size={24} />
          </button>
          <div className="logo-text">
            <MailOpen size={32} style={{ color: '#0b57d0' }} />
            <span>Email</span>
          </div>
        </div>

        <div className="search-container">
          <Search size={20} style={{ color: '#5f6368' }} />
          <input 
            type="text" 
            className="search-input" 
            placeholder="Pesquisar e-mail"
            value={searchTerm}
            onChange={(e) => setSearchTerm(e.target.value)}
          />
        </div>

        <div className="user-area">
          <div 
            className="avatar" 
            ref={avatarRef}
            onClick={() => setProfileOpen(!profileOpen)}
          >
            {userInitials}
          </div>
          
          {/* Profile Dropdown */}
          <div 
            className={`profile-dropdown ${profileOpen ? 'show' : ''}`} 
            ref={profileRef}
          >
            <div className="profile-card">
              <div className="profile-header">
                <div className="profile-avatar-large">
                  {userInitials}
                  <div className="camera-icon">
                    <Camera size={16} />
                  </div>
                </div>
                <div className="profile-name">
                  {userData ? `${userData.nome} ${userData.sobrenome}` : 'Carregando...'}
                </div>
                <div className="profile-email">
                  {userData ? userData.email : ''}
                </div>
                <button 
                  className="manage-account-btn"
                  onClick={() => alert('Funcionalidade simulada!')}
                >
                  Gerenciar sua Conta
                </button>
              </div>
            </div>
            
            <div className="profile-options">
              <div 
                className="option-item" 
                onClick={() => alert('Funcionalidade simulada!')}
              >
                <UserPlus size={20} />
                Adicionar outra conta
              </div>
            </div>

            <div className="sign-out-card">
              <div className="sign-out-btn" onClick={handleLogout}>
                <LogOut size={20} />
                Sair
              </div>
            </div>

            <div className="profile-footer">
              <span className="footer-link">Política de Privacidade</span>
              <span>•</span>
              <span className="footer-link">Termos de Serviço</span>
            </div>
          </div>
        </div>
      </header>

      <div className="main-container">
        {/* Sidebar */}
        <aside className={`${sidebarOpen ? 'show' : ''}`} id="sidebar">
          <button className="compose-btn">
            <Pencil size={20} />
            Escrever
          </button>

          <div 
            className={`nav-item interactive ${currentFolder === 'inbox' ? 'active' : ''}`}
            onClick={() => handleFolderChange('inbox')}
          >
            <Inbox size={20} />
            <span>Entrada</span>
            <span style={{ marginLeft: 'auto', fontSize: '12px' }}>
              {unreadCount > 0 ? unreadCount : ''}
            </span>
          </div>
          
          <div 
            className={`nav-item interactive ${currentFolder === 'starred' ? 'active' : ''}`}
            onClick={() => handleFolderChange('starred')}
          >
            <Star size={20} />
            <span>Com estrela</span>
          </div>
          
          <div className="nav-item">
            <Send size={20} />
            <span>Enviados</span>
          </div>
          <div className="nav-item">
            <AlertCircle size={20} />
            <span>Spam</span>
          </div>
        </aside>

        {/* Main Content */}
        <main>
          {!selectedEmailId ? (
            <div id="inbox-view">
              {/* Toolbar */}
              <div className="toolbar">
                <div className="checkbox-group">
                  <div className="icon-box" id="master-checkbox-icon">
                    <Square size={18} />
                  </div>
                  <ChevronDown size={12} />
                </div>
                
                <button 
                  className="tool-btn" 
                  onClick={handleRefresh}
                  title="Atualizar"
                >
                  <RotateCw 
                    size={18} 
                    className={refreshing ? 'spinning' : ''}
                  />
                </button>
                
                <div className="tool-btn-static">
                  <EllipsisVertical size={18} />
                </div>
              </div>

              {/* Lista de Emails */}
              <div id="email-list" className="email-list">
                {filteredEmails.length === 0 ? (
                  <div style={{ padding: '40px', textAlign: 'center', color: '#5f6368' }}>
                    {currentFolder === 'starred' ? (
                      <div style={{ display: 'flex', flexDirection: 'column', alignItems: 'center' }}>
                        <Star size={48} style={{ marginBottom: '10px' }} />
                        <p>Nenhuma mensagem com estrela.</p>
                      </div>
                    ) : (
                      <div style={{ display: 'flex', flexDirection: 'column', alignItems: 'center' }}>
                        <Inbox size={48} style={{ marginBottom: '10px' }} />
                        <p>Nenhum e-mail encontrado.</p>
                      </div>
                    )}
                  </div>
                ) : (
                  filteredEmails.map(email => (
                    <div 
                      key={email.id}
                      className={`email-row ${email.read ? '' : 'unread'} ${selectedEmailId === email.id ? 'selected' : ''}`}
                      onClick={() => handleEmailClick(email.id)}
                    >
                      <div className="row-checkbox">
                        <Square size={20} />
                      </div>
                      <div className="sender">{email.sender}</div>
                      <div className="content-preview">
                        <span className="subject">{email.subject}</span>
                        - {email.preview}
                      </div>
                      <div className="date">{email.date}</div>
                    </div>
                  ))
                )}
              </div>
            </div>
          ) : (
             selectedEmail && (
              <div className="email-detail" style={{ display: 'flex' }}>
                <div className="detail-toolbar">
                  <button 
                    className="icon-btn interactive" 
                    onClick={() => setSelectedEmailId(null)}
                    title="Voltar"
                  >
                    <ArrowLeft size={20} />
                  </button>
                  <button className="icon-btn" title="Arquivar">
                    <Archive size={20} />
                  </button>
                  <button className="icon-btn" title="Excluir">
                    <Trash2 size={20} />
                  </button>
                  <button className="icon-btn" title="Marcar como não lida">
                    <Mail size={20} />
                  </button>
                </div>
                
                <div id="detail-content">
                  <div className="detail-header">
                    <h2 className="detail-subject">{selectedEmail.subject}</h2>
                    <div className="detail-meta">
                      <div className="sender-info">
                        <div className="sender-avatar">
                          {selectedEmail.sender.charAt(0).toUpperCase()}
                        </div>
                        <div>
                          <strong>{selectedEmail.sender}</strong>
                          <div style={{ fontSize: '12px', color: '#5e5e5e' }}>para mim</div>
                        </div>
                      </div>
                      <div style={{ fontSize: '12px', color: '#5e5e5e' }}>{selectedEmail.date}</div>
                    </div>
                  </div>
                  <div 
                    className="detail-body" 
                    dangerouslySetInnerHTML={{ __html: selectedEmail.body }}
                  />
                  {/* Adicionar botão de ativação se for email de ativação */}
                  {selectedEmail.subject && selectedEmail.subject.includes('Ativação') && (
                    <div style={{ padding: '0 30px 30px 30px' }}>
                      <button
                        onClick={() => {
                          // Extrair código do corpo do email
                          const codeMatch = selectedEmail.body.match(/Código de Ativação:\s*(\d{8})/);
                          const code = codeMatch ? codeMatch[1] : '';
                          if (code) {
                            // Redirecionar para login com o código
                            navigate(`/login?action=verify&code=${code}`);
                          } else {
                            alert('Código de ativação não encontrado no email.');
                          }
                        }}
                        style={{
                          display: 'inline-block',
                          backgroundColor: '#0500ff',
                          color: 'white',
                          padding: '12px 24px',
                          borderRadius: '8px',
                          border: 'none',
                          fontWeight: '600',
                          fontSize: '14px',
                          cursor: 'pointer',
                          marginTop: '10px'
                        }}
                      >
                        Ativar Conta Agora
                      </button>
                    </div>
                  )}
                </div>
              </div>
            )
          )}
        </main>
      </div>

      {/* CSS Padrão sem atributo jsx */}
      <style>{`
        * {
          box-sizing: border-box;
          margin: 0;
          padding: 0;
          font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
        }

        .email-container {
          background-color: var(--bg-body);
          color: var(--text-main);
          height: 100vh;
          display: flex;
          flex-direction: column;
          overflow: hidden;
        }

        /* --- HEADER --- */
        header {
          background-color: var(--bg-body);
          padding: 8px 16px;
          display: flex;
          align-items: center;
          justify-content: space-between;
          height: 64px;
          z-index: 50;
        }

        .logo-area {
          display: flex;
          align-items: center;
          gap: 12px;
          min-width: 230px;
        }

        .menu-btn {
          background: none;
          border: none;
          cursor: default; /* Alterado de pointer */
          padding: 8px;
          border-radius: 50%;
          display: flex;
          align-items: center;
          justify-content: center;
        }

        /* Hover removido */
        /* .menu-btn:hover { background-color: rgba(0,0,0,0.1); } */

        .logo-text {
          font-size: 22px;
          color: #5e5e5e;
          display: flex;
          align-items: center;
          gap: 8px;
        }
        
        .logo-text span {
          color: var(--text-main);
          font-weight: 500;
        }

        .search-container {
          flex: 1;
          max-width: 720px;
          background: #eaf1fb;
          border-radius: 24px;
          padding: 10px 20px;
          display: flex;
          align-items: center;
          gap: 10px;
          transition: 0.2s;
        }

        .search-container:focus-within {
          background: white;
          box-shadow: 0 1px 2px rgba(0,0,0,0.1), 0 2px 6px rgba(0,0,0,0.1);
        }

        .search-input {
          border: none;
          background: transparent;
          width: 100%;
          outline: none;
          font-size: 16px;
          color: var(--text-main);
        }

        /* --- USER AREA --- */
        .user-area {
          min-width: 100px;
          display: flex;
          justify-content: flex-end;
          padding-right: 10px;
          position: relative;
        }

        .avatar {
          width: 32px;
          height: 32px;
          background-color: purple;
          color: white;
          border-radius: 50%;
          display: flex;
          align-items: center;
          justify-content: center;
          font-weight: bold;
          cursor: pointer;
          transition: box-shadow 0.2s;
          user-select: none;
        }

        .avatar:hover {
          box-shadow: 0 0 0 4px rgba(0,0,0,0.05);
        }

        /* Menu de Perfil */
        .profile-dropdown {
          position: absolute;
          top: 50px;
          right: 10px;
          width: 350px;
          background: #e9eef6;
          border-radius: 28px;
          box-shadow: 0 4px 8px 3px rgba(0,0,0,0.15), 0 1px 3px rgba(0,0,0,0.3);
          padding: 8px;
          display: none;
          z-index: 2000;
          flex-direction: column;
          animation: fadeIn 0.2s ease-out;
        }

        .profile-dropdown.show {
          display: flex;
        }

        @keyframes fadeIn {
          from { opacity: 0; transform: scale(0.95) translate(10px, -10px); }
          to { opacity: 1; transform: scale(1) translate(0, 0); }
        }

        .profile-card {
          background-color: white;
          border-radius: 24px;
          padding: 20px;
          display: flex;
          flex-direction: column;
          align-items: center;
          box-shadow: 0 1px 2px rgba(0,0,0,0.1);
          margin-bottom: 4px;
        }

        .profile-header {
          display: flex;
          flex-direction: column;
          align-items: center;
          width: 100%;
          text-align: center;
        }

        .profile-avatar-large {
          width: 80px;
          height: 80px;
          background-color: purple;
          color: white;
          border-radius: 50%;
          display: flex;
          align-items: center;
          justify-content: center;
          font-size: 32px;
          font-weight: 500;
          margin-bottom: 12px;
          position: relative;
        }

        .camera-icon {
          position: absolute;
          bottom: 0;
          right: 0;
          background: white;
          border-radius: 50%;
          padding: 6px;
          color: #444;
          box-shadow: 0 1px 3px rgba(0,0,0,0.2);
          cursor: default; /* Alterado de pointer */
          display: flex;
          align-items: center;
          justify-content: center;
        }

        .profile-name {
          font-size: 18px;
          font-weight: 500;
          color: #1f1f1f;
          margin-bottom: 2px;
        }

        .profile-email {
          font-size: 14px;
          color: #5e5e5e;
          margin-bottom: 20px;
        }

        .manage-account-btn {
          background: transparent;
          border: 1px solid #747775;
          color: #1f1f1f;
          padding: 8px 24px;
          border-radius: 100px;
          font-weight: 500;
          font-size: 14px;
          cursor: default; /* Alterado de pointer */
          transition: 0.2s;
          text-decoration: none;
          display: inline-block;
        }

        /* Hover removido */
        /* .manage-account-btn:hover { background-color: rgba(0,0,0,0.05); } */

        .profile-options {
          background: white;
          border-radius: 24px;
          margin-bottom: 4px;
          overflow: hidden;
          padding: 5px 0;
        }

        .option-item {
          display: flex;
          align-items: center;
          gap: 15px;
          padding: 12px 20px;
          cursor: default; /* Alterado de pointer */
          color: #444;
          font-size: 14px;
          transition: 0.2s;
        }

        /* Hover removido */
        /* .option-item:hover { background-color: rgba(0,0,0,0.05); } */

        .sign-out-card {
          background: white;
          border-radius: 24px;
          overflow: hidden;
          display: flex;
        }

        .sign-out-btn {
          flex: 1;
          display: flex;
          align-items: center;
          justify-content: center;
          gap: 10px;
          padding: 15px;
          cursor: pointer;
          font-size: 14px;
          color: #d93025;
          font-weight: 500;
        }

        .sign-out-btn:hover {
          background-color: #fce8e6;
        }

        .profile-footer {
          display: flex;
          justify-content: center;
          gap: 10px;
          padding: 12px;
          font-size: 12px;
          color: #5e5e5e;
        }

        .footer-link {
          text-decoration: none;
          color: #5e5e5e;
          padding: 4px;
          border-radius: 4px;
          cursor: default; /* Alterado de pointer */
        }

        /* Hover removido */
        /* .footer-link:hover { background-color: rgba(0,0,0,0.05); } */

        /* --- LAYOUT PRINCIPAL --- */
        .main-container {
          display: flex;
          flex: 1;
          overflow: hidden;
          position: relative;
        }

        /* --- SIDEBAR --- */
        aside {
          width: var(--sidebar-width);
          padding: 16px 10px;
          display: flex;
          flex-direction: column;
          gap: 5px;
          transition: transform 0.3s ease;
        }

        .compose-btn {
          background-color: var(--primary-bg);
          color: #001d35;
          border: none;
          border-radius: 16px;
          padding: 16px 24px;
          font-weight: 600;
          display: flex;
          align-items: center;
          gap: 12px;
          cursor: default; /* Alterado de pointer */
          width: fit-content;
          margin-bottom: 16px;
          transition: 0.2s;
          box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        /* Hover removido */
        /* .compose-btn:hover { background-color: #b0d8ff; box-shadow: 0 2px 5px rgba(0,0,0,0.15); } */

        .nav-item {
          display: flex;
          align-items: center;
          gap: 18px;
          padding: 8px 12px 8px 26px;
          border-radius: 0 20px 20px 0;
          cursor: default;
          color: var(--text-main);
          font-weight: 500;
          font-size: 14px;
        }

        .nav-item.interactive {
          cursor: pointer;
        }
        
        .nav-item.interactive:hover {
          background-color: #eaebef;
        }

        .nav-item.active {
          background-color: #d3e3fd;
          color: #001d35;
          font-weight: 700;
          cursor: default;
        }

        /* --- CONTENT AREA --- */
        main {
          flex: 1;
          background-color: var(--bg-white);
          border-radius: 16px 16px 0 0;
          margin-right: 16px;
          display: flex;
          overflow: hidden;
          box-shadow: 0 0 10px rgba(0,0,0,0.02);
        }

        #inbox-view {
          display: flex;
          flex-direction: column;
          width: 100%;
          height: 100%;
        }

        /* Toolbar estilo Gmail */
        .toolbar {
          padding: 0 16px;
          display: flex;
          align-items: center;
          gap: 16px;
          border-bottom: 1px solid var(--border);
          color: var(--text-muted);
          height: 48px;
          flex-shrink: 0;
          background-color: var(--bg-white);
          border-radius: 16px 16px 0 0;
        }

        .checkbox-group {
          display: flex;
          align-items: center;
          cursor: default; /* Alterado de pointer */
          padding: 4px;
          border-radius: 4px;
          gap: 4px;
        }
        
        /* Hover removido */
        /* .checkbox-group:hover { background-color: rgba(0,0,0,0.05); } */
        
        .icon-box {
          display: flex;
          align-items: center;
          justify-content: center;
        }

        .tool-btn {
          background: none;
          border: none;
          cursor: pointer;
          color: var(--text-muted);
          padding: 8px;
          border-radius: 50%;
          display: flex;
          align-items: center;
          justify-content: center;
          width: 34px;
          height: 34px;
          transition: 0.2s;
        }

        .tool-btn:hover {
          background-color: rgba(32,33,36,0.059);
          color: #1f1f1f;
        }

        .tool-btn-static {
          background: none;
          border: none;
          cursor: default;
          color: var(--text-muted);
          padding: 8px;
          border-radius: 50%;
          display: flex;
          align-items: center;
          justify-content: center;
          width: 34px;
          height: 34px;
        }

        @keyframes spin-once {
          from { transform: rotate(0deg); }
          to { transform: rotate(360deg); }
        }
        
        .spinning {
          animation: spin-once 0.5s ease-in-out;
        }

        /* Lista de Emails */
        .email-list {
          flex: 1;
          width: 100%;
          overflow-y: auto;
          display: flex;
          flex-direction: column;
        }

        .email-row {
          display: grid;
          grid-template-columns: 40px 200px 1fr 100px;
          align-items: center;
          padding: 8px 16px;
          border-bottom: 1px solid var(--border);
          cursor: pointer;
          transition: 0.1s;
          position: relative;
        }
        
        .email-row:hover {
          background-color: var(--hover-bg);
          box-shadow: inset 1px 0 0 #dadce0, inset -1px 0 0 #dadce0, 0 1px 2px 0 rgba(60,64,67,.3), 0 1px 3px 1px rgba(60,64,67,.15);
          z-index: 1;
        }
        
        .email-row.selected {
          background-color: var(--selected-bg) !important;
        }

        .email-row.unread {
          background-color: #f4f7fc;
          font-weight: 700;
        }
        
        .email-row.selected.unread {
          background-color: var(--selected-bg) !important;
        }
        
        .email-row.unread .subject, .email-row.unread .sender {
          color: black;
        }

        .row-checkbox {
          width: 34px;
          height: 34px;
          display: flex;
          align-items: center;
          justify-content: center;
          border-radius: 50%;
          cursor: default; /* Alterado de pointer */
          color: #5f6368;
        }
        
        /* Hover removido */
        /* .row-checkbox:hover { background-color: rgba(0,0,0,0.05); } */
        
        .sender {
          white-space: nowrap;
          overflow: hidden;
          text-overflow: ellipsis;
          padding-right: 10px;
          font-size: 14px;
        }

        .content-preview {
          display: flex;
          align-items: center;
          font-size: 14px;
          color: var(--text-muted);
          white-space: nowrap;
          overflow: hidden;
          text-overflow: ellipsis;
        }

        .subject {
          color: var(--text-main);
          margin-right: 5px;
        }

        .date {
          text-align: right;
          font-size: 12px;
          color: var(--text-main);
          font-weight: 500;
        }

        /* Detalhe do Email */
        .email-detail {
          flex: 1;
          flex-direction: column;
          background: white;
          height: 100%;
          overflow-y: auto;
        }

        .detail-toolbar {
          padding: 10px 20px;
          border-bottom: 1px solid var(--border);
          display: flex;
          gap: 15px;
        }

        .icon-btn {
          background: none;
          border: none;
          cursor: default;
          color: var(--text-muted);
          padding: 8px;
          border-radius: 50%;
          display: flex;
          align-items: center;
          justify-content: center;
        }
        
        .icon-btn svg {
          width: 20px;
          height: 20px;
        }

        .icon-btn.interactive {
          cursor: pointer;
        }

        .icon-btn.interactive:hover {
          background-color: rgba(0,0,0,0.1);
          color: black;
        }

        .detail-header {
          padding: 20px 30px;
        }

        .detail-subject {
          font-size: 22px;
          margin-bottom: 20px;
          font-weight: 400;
        }

        .detail-meta {
          display: flex;
          justify-content: space-between;
          align-items: center;
          margin-bottom: 20px;
        }

        .sender-info {
          display: flex;
          gap: 10px;
          align-items: center;
        }

        .sender-avatar {
          width: 40px;
          height: 40px;
          border-radius: 50%;
          background: #0b57d0;
          color: white;
          display: flex;
          align-items: center;
          justify-content: center;
          font-size: 18px;
        }

        .detail-body {
          padding: 0 30px 30px 30px;
          line-height: 1.6;
          color: #222;
          white-space: pre-wrap;
        }

        /* --- RESPONSIVIDADE --- */
        @media (max-width: 768px) {
          .email-container {
            background: white;
          }
          
          header {
            padding: 8px;
          }
          
          .logo-text span {
            display: none;
          }
          
          .search-container {
            margin: 0 10px;
          }
          
          .logo-area {
            min-width: auto;
          }
          
          aside {
            position: fixed;
            left: -100%;
            top: 0;
            height: 100%;
            background: white;
            z-index: 100;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
          }
          
          aside.show {
            left: 0;
          }
          
          main {
            margin: 0;
            border-radius: 0;
          }
          
          .email-row {
            grid-template-columns: 1fr auto;
            grid-template-rows: auto auto;
            gap: 5px;
            padding: 15px;
          }
          
          .row-checkbox {
            display: none;
          }
          
          .toolbar {
            display: none;
          }
          
          .sender {
            grid-column: 1;
            font-weight: bold;
          }
          
          .date {
            grid-column: 2;
            grid-row: 1;
          }
          
          .content-preview {
            grid-column: 1 / -1;
            grid-row: 2;
          }
          
          .profile-dropdown {
            position: fixed;
            top: 60px;
            right: 10px;
            left: 10px;
            width: auto;
          }
        }
      `}</style>
    </div>
  );
}