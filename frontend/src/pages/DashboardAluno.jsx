import React, { useState, useEffect, useRef, useCallback } from 'react';
import { 
  LayoutGrid, LineChart, Dumbbell, Calendar, MessageCircle, User, Sparkles, 
  SlidersHorizontal, X, Flame, Zap, PlayCircle, ScanLine, ZoomIn, Clock, 
  BarChart2, Heart, Trophy, ClipboardList, Send, History, Archive, Trash2, 
  Sun, Moon, Bell, Edit3, UserCog, Ruler, HeartPulse, FileText, Activity, 
  ChevronRight, CheckCircle, AlertCircle
} from 'lucide-react';

// --- DADOS ESTÁTICOS ---

const MOCK_USER = {
  name: "Carlos Silva",
  plan: "Titanium",
  avatar: "https://api.dicebear.com/7.x/avataaars/svg?seed=Carlos&backgroundColor=0500ff",
  streak: 12,
  xp: 2850,
  level: 5,
  nextWorkout: "Peito & Tríceps",
  weight: 78,
  height: 182,
  calories: 840
};

const MOCK_WEEK_DATA = [
  { day: 'Seg', date: '01', status: 'done' }, 
  { day: 'Ter', date: '02', status: 'done' },
  { day: 'Qua', date: '03', status: 'missed' },
  { day: 'Qui', date: '04', status: 'done' },
  { day: 'Sex', date: '05', status: 'done' },
  { day: 'Sáb', date: '06', status: 'rest' },
  { day: 'Dom', date: '07', status: 'current' },
];

const MOCK_PERFORMANCE = {
  chartData: [45, 70, 30, 85, 60, 90, 50], 
  stats: {
      totalHours: "32h",
      avgHeartRate: "145",
      records: 3
  },
  physicalAssessments: [
      { title: "Antropometria", date: "15 Dez 2024", result: "Gordura Corporal: 18%", status: "done", icon: "ruler" },
      { title: "Avaliação Cardiorrespiratória", date: "10 Out 2024", result: "VO2 Max Excelente", status: "done", icon: "heart-pulse" },
      { title: "Avaliação Postural", date: "10 Out 2024", result: "Desvio leve à direita", status: "done", icon: "user" },
      { title: "Anamnese", date: "01 Set 2024", result: "Histórico atualizado", status: "done", icon: "file-text" },
      { title: "Avaliação Neuromotora", date: "Pendente", result: "Necessário agendar", status: "pending", icon: "activity" }
  ]
};

const MOCK_BANNERS = [
  { 
      id: 1, 
      tag: "Evento",
      title: "Desafio de Verão 2025", 
      subtitle: "Participe do circuito funcional e ganhe prêmios exclusivos.", 
      gradient: "from-orange-600 to-red-600", 
      image: "https://images.unsplash.com/photo-1534438327276-14e5300c3a48?q=80&w=1600&auto=format&fit=crop" 
  },
  { 
      id: 2, 
      tag: "Novidade",
      title: "Nova Área de CrossFit", 
      subtitle: "Equipamentos de ponta já disponíveis no 2º andar.", 
      gradient: "from-blue-600 to-indigo-600", 
      image: "https://images.unsplash.com/photo-1517836357463-d25dfeac3438?q=80&w=1600&auto=format&fit=crop" 
  },
  { 
      id: 3, 
      tag: "Parceiro",
      title: "20% OFF em Suplementos", 
      subtitle: "Apresente seu app na loja NutriLife e garanta o desconto.", 
      gradient: "from-emerald-600 to-green-600", 
      image: "https://images.unsplash.com/photo-1579722821273-0f6c7d44362f?q=80&w=1600&auto=format&fit=crop" 
  }
];

const MOCK_CLASSES = [
  { title: "CrossFit Avançado", time: "18:00", instructor: "Ana B.", color: "bg-orange-500", day: "Hoje" },
  { title: "Yoga Flow", time: "07:00", instructor: "Pedro S.", color: "bg-blue-500", day: "Amanhã" },
  { title: "Spinning Extremo", time: "19:30", instructor: "Carla M.", color: "bg-pink-500", day: "Qua" }
];

const MOCK_WORKOUTS = [
  { title: "Peito & Tríceps", type: "Força", time: "45 min", level: "Intermediário", color: "blue" },
  { title: "Costas & Bíceps", type: "Força", time: "50 min", level: "Avançado", color: "indigo" },
  { title: "Leg Day (Inferiores)", type: "Resistência", time: "60 min", level: "Difícil", color: "orange" },
  { title: "HIIT Cardio", type: "Cardio", time: "25 min", level: "Iniciante", color: "pink" },
  { title: "Mobilidade & Core", type: "Flexibilidade", time: "30 min", level: "Todos", color: "purple" },
];

// --- TEMAS ---
const THEME_STYLES = {
  dark: {
      id: 'dark',
      bg: "bg-zinc-950",
      text: "text-zinc-100", 
      textSec: "text-zinc-400",
      card: "bg-zinc-900",
      cardBorder: "border-zinc-700", 
      hover: "hover:bg-zinc-800",
      inputBg: "bg-zinc-950",
      divider: "border-zinc-700", 
      accent: "text-[#0500ff]",
      accentBg: "bg-[#0500ff]",
      highlight: "bg-[#0500ff]/10",
      buttonText: "text-white",
      calDone: "border-[#0500ff]/50 text-[#0500ff] bg-[#0500ff]/10",
      calCurrent: "bg-[#0500ff] text-white border-[#0500ff]",
      calMissed: "border-red-500/50 text-red-500 bg-red-500/10",
      calPending: "border-zinc-700 text-zinc-500 bg-transparent",
      chatUserBg: "bg-[#0500ff]",
      chatBotBg: "bg-zinc-800"
  },
  light: {
      id: 'light',
      bg: "bg-slate-50",
      text: "text-slate-900", 
      textSec: "text-slate-500",
      card: "bg-white", 
      cardBorder: "border-slate-300", 
      hover: "hover:bg-slate-100",
      inputBg: "bg-slate-50",
      divider: "border-slate-300",
      accent: "text-[#0500ff]",
      accentBg: "bg-[#0500ff]",
      highlight: "bg-[#0500ff]/10",
      buttonText: "text-white",
      calDone: "border-[#0500ff] text-[#0500ff] bg-[#0500ff]/10",
      calCurrent: "bg-[#0500ff] text-white border-[#0500ff]",
      calMissed: "border-red-400 text-red-500 bg-red-50",
      calPending: "border-slate-300 text-slate-400 bg-transparent",
      chatUserBg: "bg-[#0500ff]",
      chatBotBg: "bg-slate-200"
  }
};

// --- HELPER COMPONENT: Icon Mapper ---
const Icon = ({ name, ...props }) => {
  const icons = {
    'layout-grid': LayoutGrid, 'line-chart': LineChart, 'dumbbell': Dumbbell, 'calendar': Calendar,
    'message-circle': MessageCircle, 'user': User, 'sparkles': Sparkles, 'sliders-horizontal': SlidersHorizontal,
    'x': X, 'flame': Flame, 'zap': Zap, 'play-circle': PlayCircle, 'scan-line': ScanLine, 'zoom-in': ZoomIn,
    'clock': Clock, 'bar-chart-2': BarChart2, 'heart': Heart, 'trophy': Trophy, 'clipboard-list': ClipboardList,
    'send': Send, 'history': History, 'archive': Archive, 'trash-2': Trash2, 'sun': Sun, 'moon': Moon,
    'bell': Bell, 'edit-3': Edit3, 'user-cog': UserCog, 'ruler': Ruler, 'heart-pulse': HeartPulse,
    'file-text': FileText, 'activity': Activity
  };
  const LucideIcon = icons[name] || Activity;
  return <LucideIcon {...props} />;
};

export default function App() {
  // --- STATE ---
  const [currentTab, setCurrentTab] = useState(() => localStorage.getItem('techfit_active_tab') || 'dashboard');
  const [currentTheme, setCurrentTheme] = useState(() => localStorage.getItem('user_tema') || 'light');
  const [isQrModalOpen, setIsQrModalOpen] = useState(false);
  const [isLoading, setIsLoading] = useState(true);
  const [sidebarWidth, setSidebarWidth] = useState(320);
  const [contextMenu, setContextMenu] = useState(null);
  const [bannerIndex, setBannerIndex] = useState(0);
  const [userData, setUserData] = useState(MOCK_USER); // Estado para dados reais do usuário

  // Chat State
  const [chatHistory, setChatHistory] = useState(() => JSON.parse(localStorage.getItem('techfit_chat_history')) || [
    { id: 1, sender: 'bot', text: 'Olá, Carlos! Sou o assistente virtual da TechFit. Como posso ajudar você hoje?' }
  ]);
  const [archivedChats, setArchivedChats] = useState(() => JSON.parse(localStorage.getItem('techfit_archived_chats')) || []);
  const [viewingArchivedId, setViewingArchivedId] = useState(null);
  const [inputValue, setInputValue] = useState('');

  // Refs
  const bannerRef = useRef(null);
  const chatEndRef = useRef(null);
  const chatContainerRef = useRef(null);
  const sidebarRef = useRef(null);

  const theme = THEME_STYLES[currentTheme];

  // --- CONSTANTS ---
  const DEFAULT_SIDEBAR_WIDTH = 320;
  const COLLAPSED_SIDEBAR_WIDTH = 110;

  // --- EFFECTS ---

  // Buscar dados do usuário do backend
  useEffect(() => {
    const fetchUserData = async () => {
      try {
        const userId = localStorage.getItem('user_id');
        
        if (!userId) {
          console.warn('Usuário não logado, usando dados mock');
          return;
        }

        const response = await fetch(`http://localhost/techfit-sistema/backend/api/perfil.php?user_id=${userId}`);
        const data = await response.json();

        if (data.success) {
          // Atualizar estado do usuário com dados reais
          setUserData({
            name: data.data.nome,
            plan: data.data.plano || 'Basic',
            avatar: `https://api.dicebear.com/7.x/avataaars/svg?seed=${data.data.nome}&backgroundColor=0500ff`,
            streak: 12, // Manter mock por enquanto
            xp: 2850,
            level: 5,
            nextWorkout: "Peito & Tríceps",
            weight: data.data.peso || 78,
            height: data.data.altura ? Math.round(data.data.altura * 100) : 182,
            calories: 840
          });

          // Atualizar tema se diferente do localStorage
          if (data.data.preferencia_tema && data.data.preferencia_tema !== currentTheme) {
            setCurrentTheme(data.data.preferencia_tema);
            localStorage.setItem('user_tema', data.data.preferencia_tema);
          }
        }
      } catch (error) {
        console.error('Erro ao buscar dados do usuário:', error);
      }
    };

    fetchUserData();
  }, []);

  useEffect(() => {
    // Theme toggle
    if (currentTheme === 'dark') document.documentElement.classList.add('dark');
    else document.documentElement.classList.remove('dark');
  }, [currentTheme]);

  useEffect(() => {
    localStorage.setItem('techfit_active_tab', currentTab);
    startLoadingSequence();
  }, [currentTab]);

  useEffect(() => {
    // Banner Slider Interval
    if (currentTab !== 'dashboard') return;
    const interval = setInterval(() => {
        setBannerIndex(prev => (prev + 1) % MOCK_BANNERS.length);
    }, 5000);
    return () => clearInterval(interval);
  }, [currentTab]);

  useEffect(() => {
      // Scroll banner when index changes
      if (bannerRef.current) {
          const width = bannerRef.current.clientWidth;
          bannerRef.current.scrollTo({ left: width * bannerIndex, behavior: 'smooth' });
      }
  }, [bannerIndex]);

  useEffect(() => {
      // Chat persistence
      localStorage.setItem('techfit_chat_history', JSON.stringify(chatHistory));
      if (!viewingArchivedId) scrollToBottom();
  }, [chatHistory, viewingArchivedId]);

  useEffect(() => {
      localStorage.setItem('techfit_archived_chats', JSON.stringify(archivedChats));
  }, [archivedChats]);

  // Sidebar Toggling Logic (No manual drag)
  const toggleSidebar = useCallback(() => {
      setSidebarWidth(prev => prev === DEFAULT_SIDEBAR_WIDTH ? COLLAPSED_SIDEBAR_WIDTH : DEFAULT_SIDEBAR_WIDTH);
  }, []);

  // --- ACTIONS ---

  const startLoadingSequence = () => {
      setIsLoading(true);
      setTimeout(() => setIsLoading(false), 800);
  };

  const scrollToBottom = () => {
      chatEndRef.current?.scrollIntoView({ behavior: 'smooth' });
  };

  const handleSendMessage = () => {
      if (!inputValue.trim()) return;
      
      const newMsg = { id: Date.now(), sender: 'user', text: inputValue.trim() };
      setChatHistory(prev => [...prev, newMsg]);
      setInputValue('');

      setTimeout(() => {
          let response = "";
          const lowerText = newMsg.text.toLowerCase();

          if (lowerText.includes('oi') || lowerText.includes('olá')) {
              response = "Olá! Tudo bem? Posso ajudar com horários, planos ou treinos?";
          } else if (lowerText.includes('horário') || lowerText.includes('abre') || lowerText.includes('funcionamento')) {
              response = "Estamos abertos de Segunda a Sexta das 06h às 23h, e aos finais de semana das 08h às 18h.";
          } else if (lowerText.includes('preço') || lowerText.includes('plano') || lowerText.includes('pagamento')) {
              response = "Você está no plano Titanium! Para ver faturas ou mudar de plano, acesse a aba 'Perfil'.";
          } else if (lowerText.includes('treino') || lowerText.includes('ficha')) {
              response = "Seu treino atual é focado em Hipertrofia. Você pode ver os detalhes na aba 'Treinos'.";
          } else {
              response = "Entendi. Como essa questão é mais específica, vou encaminhar para um de nossos profissionais. Eles entrarão em contato o mais rápido possível!";
          }
          setChatHistory(prev => [...prev, { id: Date.now() + 1, sender: 'bot', text: response }]);
      }, 1000);
  };

  const handleEndChat = () => {
      if (chatHistory.length <= 1) return;
      const summary = new Date().toLocaleString('pt-BR', { day: '2-digit', month: '2-digit', hour: '2-digit', minute: '2-digit' });
      const lastMsg = chatHistory[chatHistory.length - 1].text;
      const newArchive = {
          id: Date.now(),
          summary,
          preview: lastMsg.length > 30 ? lastMsg.substring(0, 30) + '...' : lastMsg,
          messages: [...chatHistory]
      };
      setArchivedChats(prev => [newArchive, ...prev]);
      setChatHistory([{ id: Date.now(), sender: 'bot', text: 'Conversa encerrada. Iniciei um novo atendimento. Como posso ajudar?' }]);
  };

  const handleContextMenu = (e, id) => {
      e.preventDefault();
      setContextMenu({ x: e.clientX, y: e.clientY, id });
  };

  const deleteArchivedChat = () => {
      if (!contextMenu) return;
      setArchivedChats(prev => prev.filter(c => c.id !== contextMenu.id));
      if (viewingArchivedId === contextMenu.id) setViewingArchivedId(null);
      setContextMenu(null);
  };

  // --- STYLES & SKELETONS ---
  const skeletonClass = "bg-zinc-200 dark:bg-zinc-800 animate-pulse";
  const blurOpacities = {
      streak: currentTheme === 'dark' ? 'bg-orange-500/20' : 'bg-orange-500/50',
      streakHover: currentTheme === 'dark' ? 'group-hover:bg-orange-500/40' : 'group-hover:bg-orange-500/70',
      suggestion: currentTheme === 'dark' ? 'from-[#0500ff]/40' : 'from-[#0500ff]/60',
      qrcode: 'bg-green-500/20',
      qrcodeHover: 'group-hover:bg-green-500/40',
      calories: currentTheme === 'dark' ? 'bg-pink-500/20' : 'bg-pink-500/50',
      caloriesHover: currentTheme === 'dark' ? 'group-hover:bg-pink-500/40' : 'group-hover:bg-pink-500/70'
  };

  // --- RENDER HELPERS ---

  const renderSkeleton = () => {
      switch(currentTab) {
          case 'dashboard': return (
              <div className="grid grid-cols-1 md:grid-cols-12 gap-6 md:gap-8 fade-in">
                  <div className={`col-span-1 md:col-span-12 h-72 md:h-96 ${skeletonClass} rounded-[2rem] md:rounded-[2.5rem]`}></div>
                  <div className="col-span-1 md:col-span-8 flex flex-col gap-6 md:gap-8">
                      <div className={`col-span-1 md:col-span-8 h-[140px] md:h-[160px] ${skeletonClass} rounded-[2.5rem]`}></div>
                      <div className="col-span-1 md:col-span-4 grid grid-cols-2 gap-4"><div className={`h-32 ${skeletonClass} rounded-[2rem]`}></div><div className={`h-32 ${skeletonClass} rounded-[2rem]`}></div></div>
                      <div className={`h-[260px] md:h-[280px] ${skeletonClass} rounded-[2.5rem]`}></div>
                  </div>
                  <div className="col-span-1 md:col-span-4 flex flex-col gap-6 md:gap-8 h-full">
                      <div className={`flex-1 min-h-[280px] ${skeletonClass} rounded-[2.5rem]`}></div>
                      <div className={`h-40 ${skeletonClass} rounded-[2.5rem]`}></div>
                  </div>
              </div>
          );
          case 'workouts': return (
              <div className="fade-in space-y-8">
                  <div className="space-y-3"><div className={`h-10 w-64 ${skeletonClass} rounded-xl`}></div><div className={`h-6 w-96 ${skeletonClass} rounded-lg`}></div></div>
                  <div className="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 md:gap-8">
                      {[1, 2, 3, 4, 5, 6].map((i) => <div key={i} className={`h-80 ${skeletonClass} rounded-[2.5rem]`}></div>)}
                  </div>
              </div>
          );
          case 'performance': return (
              <div className="fade-in space-y-8">
                  <div className="space-y-3"><div className={`h-10 w-64 ${skeletonClass} rounded-xl`}></div><div className={`h-6 w-80 ${skeletonClass} rounded-lg`}></div></div>
                  <div className={`h-[300px] md:h-[400px] ${skeletonClass} rounded-[2.5rem]`}></div>
                  <div className="grid grid-cols-1 lg:grid-cols-3 gap-6 md:gap-8">
                      <div className="lg:col-span-1 flex flex-col gap-4"><div className={`h-32 ${skeletonClass} rounded-[2rem]`}></div><div className={`h-32 ${skeletonClass} rounded-[2rem]`}></div><div className={`h-32 ${skeletonClass} rounded-[2rem]`}></div></div>
                      <div className={`lg:col-span-2 h-[400px] ${skeletonClass} rounded-[2.5rem]`}></div>
                  </div>
              </div>
          );
          case 'schedule': return (
              <div className="fade-in h-full flex flex-col">
                  <div className="flex justify-between mb-8"><div className="space-y-3"><div className={`h-10 w-64 ${skeletonClass} rounded-xl`}></div><div className={`h-6 w-80 ${skeletonClass} rounded-lg`}></div></div></div>
                  <div className={`${skeletonClass} rounded-[2.5rem] p-10 flex-1 min-h-[600px]`}></div>
              </div>
          );
          case 'chat': return (
              <div className="fade-in flex flex-col h-[calc(100vh-240px)] md:h-[calc(100vh-200px)] max-w-6xl mx-auto">
                  <div className="flex-1 flex flex-col gap-4 p-4">
                      <div className={`self-start w-2/3 h-16 ${skeletonClass} rounded-2xl rounded-tl-none`}></div>
                      <div className={`self-end w-1/2 h-12 ${skeletonClass} rounded-2xl rounded-tr-none`}></div>
                      <div className={`self-start w-3/4 h-20 ${skeletonClass} rounded-2xl rounded-tl-none`}></div>
                  </div>
                  <div className={`h-20 ${skeletonClass} rounded-[2rem] mt-4`}></div>
              </div>
          );
          case 'profile': return (
              <div className="fade-in max-w-[1200px] mx-auto mt-4 md:mt-8">
                  <div className="grid grid-cols-1 lg:grid-cols-3 gap-6 md:gap-10">
                      <div className={`h-[500px] ${skeletonClass} rounded-[2.5rem]`}></div>
                      <div className={`lg:col-span-2 h-[400px] ${skeletonClass} rounded-[2.5rem]`}></div>
                  </div>
              </div>
          );
          default: return null;
      }
  };

  // --- VIEWS (Converted to Render Functions to avoid re-mounting on parent state change) ---

  const renderDashboardView = () => {
      // Lógica de Lotação (Cores dinâmicas baseadas na porcentagem)
      const occupancyRate = 72; // Valor simulado. Tente mudar para 40 (verde) ou 90 (vermelho) para testar!
      
      let occupancyColor = "text-green-500";
      let dotColor = "bg-green-500";
      let dotBg = "bg-green-500/10";

      if (occupancyRate > 50 && occupancyRate <= 85) {
          occupancyColor = "text-orange-500";
          dotColor = "bg-orange-500";
          dotBg = "bg-orange-500/10";
      } else if (occupancyRate > 85) {
          occupancyColor = "text-red-500";
          dotColor = "bg-red-500";
          dotBg = "bg-red-500/10";
      }

      return (
      <div className="grid grid-cols-1 md:grid-cols-12 gap-6 md:gap-8 fade-in zoom-in pb-12">
          {/* Banner Slider */}
          <div className={`col-span-1 md:col-span-12 relative group rounded-[2rem] md:rounded-[2.5rem] overflow-hidden border ${theme.cardBorder} bg-black h-72 md:h-96`}>
              <div ref={bannerRef} className="flex overflow-x-auto no-scrollbar snap-x snap-mandatory scroll-smooth h-full">
                  {MOCK_BANNERS.map((banner) => (
                      <div key={banner.id} className="snap-center shrink-0 w-full h-full relative overflow-hidden select-none">
                          <img src={banner.image} alt={banner.title} className="absolute inset-0 w-full h-full object-cover transform hover:scale-105 transition-transform duration-[4s] opacity-80" />
                          <div className="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent backdrop-blur-[2px]"></div>
                          <div className="absolute inset-0 p-8 md:p-12 flex flex-col justify-end text-white z-10">
                              <span className={`inline-block px-3 py-1 md:px-4 md:py-1.5 bg-gradient-to-r ${banner.gradient} rounded-lg text-xs md:text-sm font-bold uppercase tracking-wider mb-2 md:mb-4 w-fit shadow-lg border border-white/10 backdrop-blur-md`}>{banner.tag}</span>
                              <h3 className="text-2xl md:text-4xl xl:text-6xl font-extrabold mb-2 md:mb-3 leading-tight max-w-4xl drop-shadow-lg text-white tracking-tight">{banner.title}</h3>
                              <p className="text-zinc-300 font-medium max-w-2xl text-base md:text-xl drop-shadow-md line-clamp-2 md:line-clamp-none">{banner.subtitle}</p>
                          </div>
                      </div>
                  ))}
              </div>
              <div className="absolute bottom-6 right-6 md:bottom-8 md:right-10 flex gap-2 md:gap-3 z-10">
                   {MOCK_BANNERS.map((_, i) => (
                      <div 
                          key={i} 
                          onClick={() => setBannerIndex(i)} 
                          className={`h-1.5 md:h-2 rounded-full transition-all duration-700 ease-in-out cursor-pointer hover:bg-white/80 ${i === bannerIndex ? 'bg-white w-8 md:w-10' : 'w-2 md:w-3 bg-white/40'}`}
                      ></div>
                   ))}
              </div>
          </div>

          <div className="col-span-1 md:col-span-8 flex flex-col gap-6 md:gap-8">
              <div className="grid grid-cols-1 md:grid-cols-12 gap-6 md:gap-8">
                  {/* Calendar Strip */}
                  <div className={`col-span-1 md:col-span-8 ${theme.card} rounded-[2.5rem] p-6 md:p-8 border ${theme.cardBorder} flex flex-col justify-center min-h-[140px] md:min-h-[160px] overflow-x-auto no-scrollbar`}>
                      <div className="flex justify-between items-center w-full px-2 min-w-max md:min-w-0 gap-4 md:gap-0">
                          {MOCK_WEEK_DATA.map((day, i) => {
                              let styleClass = theme.calPending;
                              if (day.status === 'done') styleClass = theme.calDone;
                              else if (day.status === 'current') styleClass = theme.calCurrent;
                              else if (day.status === 'missed') styleClass = theme.calMissed;
                              return (
                                  <div key={i} className="flex flex-col items-center gap-3 group cursor-pointer relative shrink-0">
                                      <span className={`text-[10px] md:text-xs font-bold tracking-wide ${theme.textSec} group-hover:${theme.text} transition-colors uppercase`}>{day.day}</span>
                                      <div className={`w-12 h-12 md:w-14 md:h-14 rounded-full flex items-center justify-center text-base md:text-lg font-bold border-2 transition-all duration-300 ${styleClass}`}>{day.date}</div>
                                  </div>
                              );
                          })}
                      </div>
                  </div>

                  {/* Stats Cards */}
                  <div className="col-span-1 md:col-span-4 grid grid-cols-2 md:flex md:flex-col gap-4">
                      <div className={`col-span-1 flex-1 ${theme.card} rounded-[2rem] px-4 py-4 md:px-6 md:py-4 border ${theme.cardBorder} flex items-center gap-3 md:gap-4 relative overflow-hidden group cursor-pointer`}>
                          <div className={`absolute -right-4 -bottom-4 w-24 h-24 ${blurOpacities.streak} ${blurOpacities.streakHover} blur-2xl pointer-events-none transition-all duration-500 opacity-50 group-hover:opacity-100 group-hover:blur-3xl`}></div>
                          <div className="relative z-10 shrink-0 p-2.5 md:p-3 rounded-2xl bg-orange-500/10 text-orange-500"><Flame className="w-6 h-6 md:w-[28px] md:h-[28px] fill-orange-500" /></div>
                          <div className="flex flex-col leading-none z-10 min-w-0"><span className={`text-2xl md:text-3xl font-extrabold ${theme.text}`}>{userData.streak}</span><span className={`text-[10px] md:text-xs ${theme.textSec} font-bold uppercase tracking-wider mt-1 truncate`}>Dias Seguidos</span></div>
                      </div>
                      <div className={`col-span-1 flex-1 ${theme.card} rounded-[2rem] px-4 py-4 md:px-6 md:py-4 border ${theme.cardBorder} flex items-center gap-3 md:gap-4 relative overflow-hidden group cursor-pointer`}>
                          <div className={`absolute -right-4 -bottom-4 w-24 h-24 ${blurOpacities.calories} ${blurOpacities.caloriesHover} blur-2xl pointer-events-none transition-all duration-500 opacity-50 group-hover:opacity-100 group-hover:blur-3xl`}></div>
                          <div className="relative z-10 shrink-0 p-2.5 md:p-3 rounded-2xl bg-pink-500/10 text-pink-500"><Zap className="w-6 h-6 md:w-[28px] md:h-[28px] fill-pink-500" /></div>
                          <div className="flex flex-col leading-none z-10 min-w-0"><span className={`text-2xl md:text-3xl font-extrabold ${theme.text}`}>{userData.calories}</span><span className={`text-[10px] md:text-xs ${theme.textSec} font-bold uppercase tracking-wider mt-1 truncate`}>Kcal Queimadas</span></div>
                      </div>
                  </div>
              </div>

              {/* Suggestion Card */}
              <div className={`${theme.card} rounded-[2.5rem] p-8 md:p-10 relative overflow-hidden border ${theme.cardBorder} flex flex-col justify-center min-h-[260px] md:min-h-[280px] group cursor-pointer transition-all`}>
                  <div className={`absolute top-0 right-0 w-[500px] h-[500px] bg-gradient-to-br ${blurOpacities.suggestion} to-transparent rounded-full blur-3xl -translate-y-1/2 translate-x-1/3 pointer-events-none transition-all duration-500 opacity-50 group-hover:blur-[60px] group-hover:opacity-100`}></div>
                  <div className="relative z-10">
                      <div className="flex items-center gap-3 mb-4 md:mb-5"><span className={`px-3 py-1 md:px-4 md:py-1.5 rounded-full ${theme.highlight} ${theme.accent} text-xs md:text-sm font-bold uppercase tracking-wider`}>Sugestão do dia</span></div>
                      <h2 className={`text-3xl md:text-5xl font-extrabold ${theme.text} mb-2 md:mb-3 tracking-tight`}>{userData.nextWorkout}</h2>
                      <p className={`${theme.textSec} max-w-2xl text-lg md:text-xl font-light leading-relaxed mb-8 md:mb-10`}>Foco em hipertrofia controlada. Prepare-se para 45 minutos intensos.</p>
                      <button className={`${theme.accentBg} ${theme.buttonText} px-8 py-4 md:px-10 md:py-5 rounded-full font-bold text-base md:text-lg flex items-center gap-3 hover:opacity-90 hover:scale-105 transition-all w-full md:w-fit justify-center`}><PlayCircle width="24" />Começar Treino Agora</button>
                  </div>
              </div>
          </div>

          <div className="col-span-1 md:col-span-4 flex flex-col gap-6 md:gap-8 h-full">
              {/* Check-in Card */}
              <div onClick={() => setIsQrModalOpen(true)} className={`flex-1 bg-zinc-900 ${theme.cardBorder} border rounded-[2.5rem] p-6 flex flex-col items-center justify-center text-center relative overflow-hidden cursor-pointer group transition-all duration-300 min-h-[280px] md:min-h-[300px]`}>
                  <div className={`absolute -bottom-4 left-0 right-0 h-40 ${blurOpacities.qrcode} blur-3xl pointer-events-none transition-all duration-500 ${blurOpacities.qrcodeHover}`}></div>
                  <div className="relative z-10 flex flex-col items-center w-full">
                      <div className="flex items-center gap-2 mb-6 opacity-90"><div className="w-8 h-8 flex items-center justify-center bg-green-500/10 rounded-md backdrop-blur-sm"><ScanLine width="16" className="text-green-500" /></div><span className="text-sm font-bold uppercase tracking-widest text-white">Realizar Check-in</span></div>
                      <div className="bg-white p-4 rounded-3xl shadow-2xl relative overflow-hidden group-hover:scale-105 transition-transform duration-300"><img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=TechFit_User_Carlos_Silva" alt="QR Code" className="w-36 h-36 md:w-40 md:h-40 mix-blend-multiply" /><div className="absolute inset-0 flex items-center justify-center bg-black/40 text-white font-bold tracking-widest text-lg opacity-0 group-hover:opacity-100 transition-opacity duration-300 backdrop-blur-[3px] rounded-3xl"><div className="flex flex-col items-center gap-1"><ZoomIn width="24" />ZOOM</div></div></div>
                      <div className="mt-6"><h3 className="text-xl font-bold tracking-tight mb-1 text-white">Acesso Liberado</h3><p className="text-zinc-400 text-sm font-medium transition-colors">Toque para ampliar</p></div>
                  </div>
              </div>
              {/* Occupancy Card */}
              <div className={`h-fit ${theme.card} rounded-[2.5rem] p-6 md:p-8 border ${theme.cardBorder} flex items-center justify-between relative overflow-hidden`}>
                  <div className="flex items-center gap-3 md:gap-4"><div className={`w-12 h-12 md:w-14 md:h-14 rounded-full ${dotBg} flex items-center justify-center shrink-0`}><div className={`w-3 h-3 md:w-4 md:h-4 rounded-full ${dotColor}`}></div></div><div className="leading-tight"><h3 className={`font-bold ${theme.text} text-base md:text-lg`}>Lotação</h3><p className={`${theme.textSec} text-xs md:text-sm font-medium mt-0.5`}>há 2m</p></div></div>
                  <div className="text-right"><span className={`${occupancyColor} font-bold text-3xl md:text-4xl tracking-tight`}>{occupancyRate}%</span></div>
              </div>
          </div>
      </div>
  );
  };

  const renderWorkoutsView = () => (
      <div className="fade-in slide-up space-y-8">
          <div><h2 className={`text-3xl md:text-4xl font-extrabold ${theme.text} tracking-tight`}>Catálogo de Treinos</h2><p className={`${theme.textSec} text-lg md:text-xl mt-2`}>Todos os treinos disponíveis no seu plano.</p></div>
          <div className="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 md:gap-8">
              {MOCK_WORKOUTS.map((workout, i) => (
                  <div key={i} className={`${theme.card} border ${theme.cardBorder} p-6 md:p-8 rounded-[2.5rem] hover:border-[#0500ff] transition-all duration-300 group relative overflow-hidden cursor-pointer`}>
                      <div className={`absolute top-6 right-6 md:top-8 md:right-8 px-3 py-1 md:px-4 md:py-1.5 rounded-full bg-${workout.color}-500/10 text-${workout.color}-600 text-xs md:text-sm font-bold uppercase tracking-wider`}>{workout.type}</div>
                      <div className="mb-8 md:mb-10 mt-2"><div className={`w-12 h-12 md:w-14 md:h-14 rounded-2xl ${theme.bg} flex items-center justify-center ${theme.textSec} mb-4 md:mb-6 border ${theme.cardBorder} group-hover:scale-110 transition-transform`}><Dumbbell width="24" /></div><h3 className={`text-xl md:text-2xl font-bold ${theme.text}`}>{workout.title}</h3></div>
                      <div className={`flex items-center gap-6 text-sm ${theme.textSec} border-t ${theme.divider} pt-6 font-medium`}><span className="flex items-center gap-2"><Clock width="16" /> {workout.time}</span><span className="flex items-center gap-2"><BarChart2 width="16" /> {workout.level}</span></div>
                  </div>
              ))}
          </div>
      </div>
  );

  const renderPerformanceView = () => (
      <div className="fade-in slide-up space-y-8">
          <div>
              <h2 className={`text-3xl md:text-4xl font-extrabold ${theme.text} tracking-tight`}>Desempenho</h2>
              <p className={`${theme.textSec} text-lg md:text-xl mt-2`}>Seus resultados e avaliações físicas.</p>
          </div>
          <div className={`${theme.card} border ${theme.cardBorder} rounded-[2.5rem] p-8 md:p-10 relative overflow-hidden`}>
              <div className="flex items-center justify-between mb-8">
                  <h3 className={`text-xl font-bold ${theme.text}`}>Intensidade Semanal</h3>
                  <div className="flex gap-2"><span className="w-3 h-3 rounded-full bg-[#0500ff]"></span><span className={`text-xs ${theme.textSec}`}>Volume de Treino</span></div>
              </div>
              <div className="h-64 flex items-end justify-between gap-2 md:gap-4 px-2">
                  {MOCK_PERFORMANCE.chartData.map((val, i) => (
                      <div key={i} className="flex flex-col items-center gap-3 w-full group cursor-pointer">
                          <div className="w-full max-w-[60px] bg-[#0500ff]/10 rounded-t-2xl relative overflow-hidden transition-all duration-300 group-hover:bg-[#0500ff]/20" style={{ height: `${val}%` }}>
                              <div className="absolute bottom-0 left-0 w-full bg-[#0500ff] transition-all duration-500 ease-out" style={{ height: '100%' }}></div>
                          </div>
                          <span className={`text-xs font-bold ${theme.textSec}`}>{['Seg','Ter','Qua','Qui','Sex','Sáb','Dom'][i]}</span>
                      </div>
                  ))}
              </div>
          </div>
          <div className="grid grid-cols-1 lg:grid-cols-3 gap-6 md:gap-8">
              <div className="lg:col-span-1 flex flex-col gap-4">
                   <div className={`${theme.card} border ${theme.cardBorder} rounded-[2rem] p-6 flex items-center gap-4 hover:border-[#0500ff] transition-colors group`}><div className="w-12 h-12 rounded-xl bg-blue-500/10 text-blue-500 flex items-center justify-center shrink-0"><Clock width="24" /></div><div><p className={`text-2xl font-bold ${theme.text}`}>{MOCK_PERFORMANCE.stats.totalHours}</p><p className={`text-xs ${theme.textSec} uppercase font-bold tracking-wider`}>Horas Treinadas</p></div></div>
                   <div className={`${theme.card} border ${theme.cardBorder} rounded-[2rem] p-6 flex items-center gap-4 hover:border-[#0500ff] transition-colors group`}><div className="w-12 h-12 rounded-xl bg-red-500/10 text-red-500 flex items-center justify-center shrink-0"><Heart width="24" /></div><div><p className={`text-2xl font-bold ${theme.text}`}>{MOCK_PERFORMANCE.stats.avgHeartRate} <span className="text-sm font-normal text-zinc-500">bpm</span></p><p className={`text-xs ${theme.textSec} uppercase font-bold tracking-wider`}>Frequência Média</p></div></div>
                   <div className={`${theme.card} border ${theme.cardBorder} rounded-[2rem] p-6 flex items-center gap-4 hover:border-[#0500ff] transition-colors group`}><div className="w-12 h-12 rounded-xl bg-yellow-500/10 text-yellow-500 flex items-center justify-center shrink-0"><Trophy width="24" /></div><div><p className={`text-2xl font-bold ${theme.text}`}>{MOCK_PERFORMANCE.stats.records}</p><p className={`text-xs ${theme.textSec} uppercase font-bold tracking-wider`}>Recordes Batidos</p></div></div>
              </div>
              <div className={`lg:col-span-2 ${theme.card} border ${theme.cardBorder} rounded-[2.5rem] p-8 md:p-10`}>
                  <h3 className={`text-xl font-bold ${theme.text} mb-6 flex items-center gap-3`}><ClipboardList className="text-[#0500ff]" /> Avaliações Físicas</h3>
                  <div className="space-y-4">
                      {MOCK_PERFORMANCE.physicalAssessments.map((assessment, i) => (
                          <div key={i} className={`p-6 rounded-3xl ${theme.inputBg} border ${theme.cardBorder} hover:border-[#0500ff]/30 transition-colors group flex flex-col md:flex-row md:items-center justify-between gap-4`}>
                              <div className="flex items-center gap-4">
                                  <div className="w-12 h-12 rounded-2xl bg-zinc-200 dark:bg-zinc-800 flex items-center justify-center text-zinc-500"><Icon name={assessment.icon} width="20" /></div>
                                  <div><p className={`font-bold ${theme.text} text-lg`}>{assessment.title}</p><p className={`text-xs ${theme.textSec} uppercase font-bold tracking-wider mt-1`}>{assessment.date}</p></div>
                              </div>
                              <div className="flex flex-col md:items-end gap-2 pl-16 md:pl-0">
                                  <span className={`text-sm font-medium ${theme.textSec}`}>{assessment.result}</span>
                                  {assessment.status === 'done' ? <span className="px-3 py-1 rounded-lg bg-green-500/10 text-green-600 text-xs font-bold w-fit border border-green-500/20">CONCLUÍDO</span> : <span className="px-3 py-1 rounded-lg bg-orange-500/10 text-orange-600 text-xs font-bold w-fit border border-orange-500/20 cursor-pointer hover:bg-orange-500/20">AGENDAR</span>}
                              </div>
                          </div>
                      ))}
                  </div>
              </div>
          </div>
      </div>
  );

  const renderScheduleView = () => (
      <div className="fade-in slide-up h-full flex flex-col">
          <div className="flex items-center justify-between mb-8"><div><h2 className={`text-3xl md:text-4xl font-extrabold ${theme.text}`}>Agenda Semanal</h2><p className={`${theme.textSec} text-lg md:text-xl mt-2`}>Visualize as aulas disponíveis na unidade.</p></div></div>
          <div className={`${theme.card} border ${theme.cardBorder} rounded-[2.5rem] p-6 md:p-10 flex-1`}>
              <div className={`grid grid-cols-5 gap-2 md:gap-4 mb-8 md:mb-10 text-center border-b ${theme.divider} pb-8 md:pb-10`}>
                  {['Seg', 'Ter', 'Qua', 'Qui', 'Sex'].map((day, i) => (
                      <div key={i} className={`flex flex-col items-center opacity-${i === 1 ? '100' : '50'} transition-opacity hover:opacity-100 cursor-pointer`}><span className={`text-xs md:text-sm font-bold ${theme.textSec} uppercase mb-2 md:mb-3 tracking-wider`}>{day}</span><div className={`w-10 h-10 md:w-14 md:h-14 rounded-xl md:rounded-2xl flex items-center justify-center ${i === 1 ? `${theme.accentBg} text-white font-bold` : `${theme.text} font-semibold hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors`}`}><span className="text-base md:text-lg">{18 + i}</span></div></div>
                  ))}
              </div>
              <div className="space-y-4 md:space-y-6">
                  {MOCK_CLASSES.map((aula, i) => (
                      <div key={i} className={`flex items-center gap-4 md:gap-8 p-4 md:p-6 rounded-3xl ${theme.inputBg} border ${theme.cardBorder} hover:border-[#0500ff]/30 transition-colors group cursor-pointer`}>
                          <div className="w-16 md:w-20 text-center shrink-0"><span className={`${theme.text} font-mono text-xl md:text-2xl font-bold block`}>{aula.time}</span><span className={`text-[10px] md:text-xs ${theme.textSec} uppercase font-bold tracking-wide`}>{aula.day}</span></div>
                          <div className={`h-10 md:h-12 w-1.5 ${aula.color} rounded-full shrink-0`}></div>
                          <div className="flex-1 min-w-0"><h4 className={`${theme.text} font-bold text-lg md:text-xl truncate group-hover:text-[#0500ff] transition-colors`}>{aula.title}</h4><p className={`${theme.textSec} text-sm md:text-base font-medium mt-1`}>Instrutor {aula.instructor}</p></div>
                          <div className={`hidden md:block px-4 py-2 rounded-xl border ${theme.cardBorder} ${theme.textSec} text-sm font-semibold bg-${theme.bg}`}>Confirmado</div>
                      </div>
                  ))}
              </div>
          </div>
      </div>
  );

  const renderChatView = () => {
      let displayMessages = chatHistory;
      let isArchivedMode = false;
      if (viewingArchivedId) {
          const archived = archivedChats.find(c => c.id === viewingArchivedId);
          if (archived) {
              displayMessages = archived.messages;
              isArchivedMode = true;
          }
      }

      return (
          <div className="fade-in slide-up h-[calc(100vh-240px)] md:h-[calc(100vh-200px)] flex flex-col max-w-6xl mx-auto">
              <div className="mb-4 flex justify-between items-end"><div><h2 className={`text-3xl font-extrabold ${theme.text}`}>Fale Conosco</h2><p className={theme.textSec}>Tire dúvidas sobre horários, planos ou treinos.</p></div></div>
              <div className={`${theme.card} border ${theme.cardBorder} rounded-[2rem] flex flex-1 overflow-hidden relative`}>
                  <div className={`hidden md:flex flex-col w-72 border-r ${theme.divider} bg-zinc-50/50 dark:bg-zinc-900/50`}>
                      <div className={`p-4 border-b ${theme.divider} flex justify-between items-center`}><h3 className={`font-bold ${theme.text} text-xs uppercase tracking-wider flex items-center gap-2`}><History width="14" /> Histórico</h3></div>
                      <div className="flex-1 overflow-y-auto p-3 space-y-2">
                           <button onClick={() => setViewingArchivedId(null)} className={`w-full text-left p-3 rounded-xl transition-all border ${!isArchivedMode ? 'bg-white dark:bg-zinc-800 shadow-sm border-zinc-200 dark:border-zinc-700' : 'border-transparent hover:bg-zinc-200 dark:hover:bg-zinc-800'}`}>
                              <div className={`font-bold ${theme.text} text-sm flex items-center gap-2`}><div className="w-2 h-2 rounded-full bg-green-500 animate-pulse"></div> Ativo Agora</div>
                           </button>
                           {archivedChats.length > 0 && <div className={`h-px ${theme.divider} my-2`}></div>}
                           {archivedChats.map(chat => (
                              <button key={chat.id} onClick={() => setViewingArchivedId(chat.id)} onContextMenu={(e) => handleContextMenu(e, chat.id)} className={`w-full text-left p-3 rounded-xl transition-all border relative group ${viewingArchivedId === chat.id ? 'bg-white dark:bg-zinc-800 shadow-sm border-zinc-200 dark:border-zinc-700' : 'border-transparent hover:bg-zinc-200 dark:hover:bg-zinc-800'}`}>
                                  <div className="flex justify-between items-center mb-1"><span className={`text-xs font-bold ${theme.textSec}`}>{chat.summary}</span>{viewingArchivedId === chat.id && <div className="text-[#0500ff]"><MessageCircle width="12" /></div>}</div>
                                  <div className={`text-xs ${theme.text} truncate opacity-80`}>"{chat.preview}"</div>
                              </button>
                           ))}
                      </div>
                  </div>
                  <div className="flex-1 flex flex-col relative min-w-0 bg-white dark:bg-zinc-950/50">
                      {!isArchivedMode && chatHistory.length > 1 && (
                          <div className="absolute top-4 left-0 right-0 flex justify-center z-10 pointer-events-none px-4">
                              <div className="bg-blue-50 dark:bg-blue-900/20 border border-blue-100 dark:border-blue-800/50 text-blue-600 dark:text-blue-300 text-xs px-4 py-2 rounded-full shadow-sm pointer-events-auto backdrop-blur-md flex items-center gap-2 animate-bounce-slow">
                                  <span>Dúvida resolvida?</span>
                                  <button onClick={handleEndChat} className="underline font-bold hover:text-blue-800 dark:hover:text-blue-100 transition-colors">Encerrar e Salvar</button>
                              </div>
                          </div>
                      )}
                      <div id="chat-container" ref={chatContainerRef} className="flex-1 overflow-y-auto p-6 md:p-8 space-y-6 scroll-smooth">
                          {displayMessages.map(msg => (
                              <div key={msg.id} className={`flex ${msg.sender === 'user' ? 'justify-end' : 'justify-start'}`}>
                                  <div className={`max-w-[80%] md:max-w-[70%] p-4 rounded-2xl text-sm md:text-base leading-relaxed shadow-sm ${msg.sender === 'user' ? `${theme.chatUserBg} text-white rounded-br-sm` : `${theme.chatBotBg} ${theme.text} rounded-bl-sm`}`}>{msg.text}</div>
                              </div>
                          ))}
                          <div ref={chatEndRef}></div>
                      </div>
                      <div className={`p-4 md:p-6 border-t ${theme.divider} ${theme.inputBg} relative z-20`}>
                          {isArchivedMode ? (
                               <div className={`p-4 rounded-2xl bg-zinc-100 dark:bg-zinc-800/50 border border-dashed ${theme.cardBorder} text-center text-sm ${theme.textSec} flex items-center justify-center gap-2`}><Archive width="16" /><span>Esta conversa está finalizada.</span><button onClick={() => setViewingArchivedId(null)} className="text-[#0500ff] font-bold hover:underline">Voltar para o chat ativo</button></div>
                          ) : (
                               <div className="flex items-center gap-3">
                                  <input type="text" value={inputValue} onChange={(e) => setInputValue(e.target.value)} onKeyDown={(e) => e.key === 'Enter' && handleSendMessage()} placeholder="Digite sua mensagem..." className={`flex-1 p-4 rounded-2xl ${theme.bg} border ${theme.cardBorder} ${theme.text} focus:outline-none focus:border-[#0500ff] transition-colors`} />
                                  <button onClick={handleSendMessage} className={`p-4 rounded-2xl ${theme.accentBg} text-white hover:opacity-90 transition-opacity`}><Send width="20" /></button>
                              </div>
                          )}
                      </div>
                  </div>
              </div>
          </div>
      );
  };

  const renderProfileView = () => (
      <div className="fade-in slide-up max-w-[1200px] mx-auto mt-4 md:mt-8">
          <div className="grid grid-cols-1 lg:grid-cols-3 gap-6 md:gap-10">
              <div className={`${theme.card} border ${theme.cardBorder} rounded-[2.5rem] p-8 md:p-10 text-center relative overflow-hidden h-fit`}>
                  <div className="absolute top-0 left-0 w-full h-32 md:h-40 bg-gradient-to-b from-[#0500ff]/20 to-transparent"></div>
                  <div className="relative z-10">
                      <img src={userData.avatar} alt="Profile" className={`w-32 h-32 md:w-40 md:h-40 rounded-full border-[6px] ${theme.bg} mx-auto mb-6 hover:scale-105 transition-transform cursor-pointer`} />
                      <h2 className={`text-3xl md:text-4xl font-extrabold ${theme.text} tracking-tight`}>{userData.name}</h2>
                      <p className={`${theme.accent} text-base md:text-lg font-semibold mb-6 md:mb-8`}>{userData.plan} Member</p>
                      <div className="grid grid-cols-2 gap-4 md:gap-6 mb-2">
                          <div className={`p-4 md:p-5 rounded-2xl ${theme.inputBg}`}><span className={`block text-2xl md:text-3xl font-bold ${theme.text}`}>{userData.weight}</span><span className={`text-xs md:text-sm font-bold ${theme.textSec} uppercase tracking-wider`}>kg</span></div>
                          <div className={`p-4 md:p-5 rounded-2xl ${theme.inputBg}`}><span className={`block text-2xl md:text-3xl font-bold ${theme.text}`}>{userData.height}</span><span className={`text-xs md:text-sm font-bold ${theme.textSec} uppercase tracking-wider`}>cm</span></div>
                      </div>
                  </div>
              </div>
              <div className="lg:col-span-2 space-y-6 md:space-y-8">
                  <div className={`${theme.card} border ${theme.cardBorder} rounded-[2.5rem] p-6 md:p-10`}>
                      <h3 className={`text-xl md:text-2xl font-bold ${theme.text} mb-6 md:mb-8 flex items-center gap-3`}><UserCog className="text-[#0500ff]" />Informações da Conta</h3>
                      <div className="space-y-4 md:space-y-6">
                          <div className={`p-4 md:p-6 rounded-2xl border ${theme.cardBorder} flex flex-col md:flex-row justify-between md:items-center gap-4 hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors cursor-pointer group`}><div><p className={`text-xs ${theme.textSec} uppercase font-bold tracking-wider mb-1`}>E-mail</p><p className={`${theme.text} text-base md:text-lg font-medium group-hover:text-[#0500ff] transition-colors`}>carlos.silva@email.com</p></div><Edit3 className={`${theme.textSec} opacity-0 group-hover:opacity-100 transition-opacity self-end md:self-auto`} /></div>
                          <div className={`p-4 md:p-6 rounded-2xl border ${theme.cardBorder} flex flex-col md:flex-row justify-between md:items-center gap-4 hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors`}><div><p className={`text-xs ${theme.textSec} uppercase font-bold tracking-wider mb-1`}>Plano Ativo</p><p className={`${theme.text} text-base md:text-lg font-medium`}>Titanium Anual (Renova em Dez/25)</p></div><span className="text-green-600 text-sm font-bold px-3 py-1 bg-green-500/10 rounded-lg border border-green-500/20 w-fit">ATIVO</span></div>
                      </div>
                  </div>
              </div>
          </div>
      </div>
  );

  // --- APP LAYOUT ---

  const renderNavItem = (id, IconComponent, label, disabled) => {
      const isActive = currentTab === id;
      // Define o limite de largura para considerar a barra encolhida (110px é o mínimo)
      const isCollapsed = sidebarWidth < 150; 
      const textOpacity = isCollapsed ? '0' : '1';
      
      // Ajusta o layout para centralizar o ícone e remover padding horizontal na visualização encolhida
      const layoutClasses = isCollapsed
          ? 'justify-center px-0 py-4'
          : 'items-center gap-5 px-5 py-4';

      return (
          <button 
              key={id}
              onClick={() => !disabled && setCurrentTab(id)} 
              disabled={disabled}
              // Aplica flex e classes de layout dinâmicas. Note: hover:bg-slate-100 dark:hover:bg-zinc-800
              className={`flex w-full rounded-2xl transition-all duration-200 ease-out overflow-hidden ${isActive ? `${theme.card} ${theme.text} font-bold border ${theme.cardBorder} shadow-sm` : disabled ? `cursor-not-allowed opacity-50 ${theme.textSec}` : `${theme.textSec} hover:${theme.text} hover:bg-slate-100 dark:hover:bg-zinc-800`} ${layoutClasses}`} 
              title={label}
          >
              <IconComponent 
                  // Remove scale-110 para manter o tamanho uniforme
                  className={`transition-colors duration-300 ${isActive ? 'text-[#0500ff]' : ''} shrink-0`} 
                  width="24" 
                  height="24" 
              />
              <div 
                  className="flex items-center justify-between flex-1 overflow-hidden transition-all duration-300" 
                  style={{ opacity: textOpacity }}
              >
                  <span className="text-base font-medium tracking-wide whitespace-nowrap">{label}</span>
                  {disabled && <span className={`text-[9px] font-extrabold px-1.5 py-0.5 rounded border ${theme.divider} uppercase tracking-wider`}>Em Breve</span>}
              </div>
          </button>
      );
  };

  const renderMobileNavItem = (id, IconComponent, label) => {
      const isActive = currentTab === id;
      return (
          <button key={id} onClick={() => setCurrentTab(id)} className="flex flex-col items-center justify-center gap-1.5 w-16 h-full transition-all duration-300 group pt-2">
              <div className={`${isActive ? '-translate-y-1' : ''} transition-transform duration-300 p-1.5 rounded-xl ${isActive ? theme.highlight : 'bg-transparent'}`}>
                  <IconComponent className={`${isActive ? theme.accent : theme.textSec} transition-colors`} width="24" strokeWidth={isActive ? 2.5 : 2} />
              </div>
              <span className={`text-[10px] font-medium tracking-wide ${isActive ? theme.text : theme.textSec} ${isActive ? 'opacity-100' : 'opacity-70'}`}>{label}</span>
          </button>
      );
  };

  return (
      <>
          <style>{`
            ::-webkit-scrollbar { width: 10px; height: 10px; }
            ::-webkit-scrollbar-track { background: transparent; }
            ::-webkit-scrollbar-thumb { background: #52525b; border-radius: 5px; border: 2px solid transparent; background-clip: content-box; }
            ::-webkit-scrollbar-thumb:hover { background-color: #71717a; }
            .no-scrollbar::-webkit-scrollbar { display: none; }
            .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
            .fade-in { animation: fadeIn 0.5s ease-out forwards; }
            .slide-up { animation: slideUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
            .modal-enter { animation: modalIn 0.2s ease-out forwards; }
            @keyframes modalIn { from { opacity: 0; transform: scale(0.95); } to { opacity: 1; transform: scale(1); } }
            @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
            @keyframes slideUp { from { transform: translateY(30px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
            .pb-safe { padding-bottom: env(safe-area-inset-bottom); }
            .animate-bounce-slow { animation: bounce 3s infinite; }
          `}</style>

          <div className={`min-h-screen ${theme.bg} ${theme.text} font-sans flex transition-colors duration-300 overflow-hidden`}>
              
              {/* SIDEBAR */}
              <aside 
                  id="app-sidebar"
                  ref={sidebarRef}
                  style={{ width: `${sidebarWidth}px`, transition: 'width 0.3s ease' }}
                  className={`hidden lg:flex static inset-y-0 left-0 z-40 ${theme.bg} p-8 flex-col border-r ${theme.divider} flex-shrink-0 relative group/sidebar ease-out`}
              >
                  <div 
                      onDoubleClick={toggleSidebar}
                      // Alterado: hover:bg-[#0500ff] -> hover:bg-zinc-500/50 e cursor: pointer
                      className="absolute top-0 right-0 w-[6px] h-full cursor-pointer z-50 hover:bg-zinc-500/50 transition-colors opacity-0 hover:opacity-100" 
                      title="Duplo clique para ajustar"
                  ></div>

                  <div className="flex items-center gap-4 px-2 mb-12 overflow-hidden">
                      <div className="w-10 h-10 shrink-0 rounded-xl bg-[#0500ff] flex items-center justify-center text-white"><Dumbbell width="20" /></div>
                      <span className={`font-bold text-2xl tracking-tight ${theme.text} whitespace-nowrap transition-opacity duration-300`} style={{ opacity: sidebarWidth < 200 ? 0 : 1 }}>TechFit</span>
                  </div>

                  <nav className="space-y-3 flex-1">
                      {renderNavItem('dashboard', LayoutGrid, 'Início')}
                      {renderNavItem('performance', LineChart, 'Desempenho')}
                      {renderNavItem('workouts', Dumbbell, 'Treinos')}
                      {renderNavItem('schedule', Calendar, 'Agenda')}
                      {renderNavItem('chat', MessageCircle, 'Chat')}
                      {renderNavItem('fitai', Sparkles, 'FitAI', true)}
                  </nav>

                  <div className={`mt-auto pt-8 border-t ${theme.divider}`}>
                      <button onClick={() => setCurrentTab('profile')} className={`flex items-center gap-4 w-full p-3 rounded-2xl transition-all duration-200 ease-out cursor-pointer group text-left relative overflow-hidden border ${currentTab === 'profile' ? `${theme.card} ${theme.text} ${theme.cardBorder}` : `border-transparent ${theme.textSec} hover:${theme.text} hover:bg-slate-100 dark:hover:bg-zinc-800/50`}`}>
                          <img src={userData.avatar} alt="Avatar" className={`w-12 h-12 shrink-0 rounded-full border-2 ${theme.divider} group-hover:border-[#0500ff] transition-colors relative z-10`} />
                          <div className="flex-1 overflow-hidden relative z-10 transition-opacity duration-300" style={{ opacity: sidebarWidth < 200 ? 0 : 1 }}>
                              <p className={`text-base font-bold ${currentTab === 'profile' ? theme.text : ''} truncate group-hover:text-[#0500ff] transition-colors`}>{userData.name}</p>
                              <p className={`text-sm ${theme.textSec} truncate`}>{userData.plan}</p>
                          </div>
                          <SlidersHorizontal width="20" className={`${theme.textSec} shrink-0 group-hover:text-[#0500ff] transition-colors relative z-10`} style={{ opacity: sidebarWidth < 200 ? 0 : 1 }} />
                      </button>
                  </div>
              </aside>

              {/* MAIN CONTENT */}
              <main className="flex-1 h-screen overflow-y-auto relative scroll-smooth">
                  <header className={`sticky top-0 z-30 px-4 md:px-8 py-4 md:py-6 ${theme.bg}/95 backdrop-blur-2xl flex items-center justify-between border-b ${theme.divider}`}>
                      <div>
                          <h1 className={`text-2xl md:text-3xl font-bold ${theme.text} tracking-tight`}>
                              {currentTab === 'dashboard' ? (
                                  <>
                                      {new Date().getHours() < 12 ? 'Bom dia' : new Date().getHours() < 18 ? 'Boa tarde' : 'Boa noite'}, <span className="text-[#0500ff]">{userData.name.split(' ')[0]}.</span>
                                  </>
                              ) : (
                                  currentTab.charAt(0).toUpperCase() + currentTab.slice(1)
                              )}
                          </h1>
                      </div>
                      <div className="flex items-center gap-4 md:gap-6">
                          <button onClick={async () => {
                              const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
                              setCurrentTheme(newTheme);
                              localStorage.setItem('user_tema', newTheme);
                              
                              // Salvar preferência no backend
                              try {
                                  const userId = localStorage.getItem('user_id');
                                  if (userId) {
                                      await fetch('http://localhost/techfit-sistema/backend/api/perfil.php', {
                                          method: 'POST',
                                          headers: {
                                              'Content-Type': 'application/json'
                                          },
                                          body: JSON.stringify({
                                              user_id: userId,
                                              preferencia_tema: newTheme
                                          })
                                      });
                                  }
                              } catch (error) {
                                  console.error('Erro ao salvar tema:', error);
                              }
                          }} className={`w-10 h-10 md:w-12 md:h-12 rounded-2xl border ${theme.cardBorder} flex items-center justify-center ${theme.textSec} hover:${theme.text} hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-all`} title="Alternar Tema">
                              {currentTheme === 'dark' ? <Sun width="20" /> : <Moon width="20" />}
                          </button>
                          <div className="relative">
                              <button className={`w-10 h-10 md:w-12 md:h-12 rounded-2xl border ${theme.cardBorder} flex items-center justify-center ${theme.textSec} hover:${theme.text} hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-all`}>
                                  <Bell width="20" />
                                  <span className="absolute top-3 right-3 w-2 h-2 bg-red-500 rounded-full animate-pulse"></span>
                              </button>
                          </div>
                      </div>
                  </header>

                  <div className="px-4 md:px-8 pb-32 md:pb-16 pt-6 md:pt-8 max-w-[1920px] mx-auto w-full">
                      {isLoading ? renderSkeleton() : (
                          <>
                              {currentTab === 'dashboard' && renderDashboardView()}
                              {currentTab === 'performance' && renderPerformanceView()}
                              {currentTab === 'workouts' && renderWorkoutsView()}
                              {currentTab === 'schedule' && renderScheduleView()}
                              {currentTab === 'chat' && renderChatView()}
                              {currentTab === 'profile' && renderProfileView()}
                          </>
                      )}
                  </div>
              </main>

              {/* MOBILE BOTTOM NAV */}
              <nav className={`fixed bottom-0 left-0 right-0 z-40 ${theme.card} border-t ${theme.divider} pb-safe flex justify-around items-center lg:hidden h-[88px] px-6 pb-2 transition-transform duration-300`}>
                  {renderMobileNavItem('dashboard', LayoutGrid, 'Início')}
                  {renderMobileNavItem('performance', LineChart, 'Desempenho')}
                  {renderMobileNavItem('workouts', Dumbbell, 'Treinos')}
                  {renderMobileNavItem('chat', MessageCircle, 'Chat')}
                  {renderMobileNavItem('schedule', Calendar, 'Agenda')}
                  {renderMobileNavItem('profile', User, 'Perfil')}
              </nav>

              {/* QR MODAL */}
              {isQrModalOpen && (
                  <div onClick={() => setIsQrModalOpen(false)} className="fixed inset-0 z-50 bg-black/80 backdrop-blur-sm flex items-center justify-center fade-in p-4 cursor-pointer">
                      <div onClick={(e) => e.stopPropagation()} className={`${theme.card} p-10 rounded-[2.5rem] border ${theme.cardBorder} flex flex-col items-center relative max-w-md w-full shadow-2xl modal-enter cursor-default`}>
                          <button onClick={() => setIsQrModalOpen(false)} className={`absolute top-6 right-6 p-2 rounded-xl hover:bg-zinc-100 dark:hover:bg-zinc-800 ${theme.textSec} hover:${theme.text} transition-all`}><X width="24" /></button>
                          <h3 className={`text-3xl font-extrabold ${theme.text} mb-2 tracking-tight`}>Passe Digital</h3>
                          <p className={`${theme.textSec} mb-8 text-center text-lg`}>Aproxime este código da catraca</p>
                          <div className="bg-white p-6 rounded-[2rem] shadow-inner mb-8 w-full aspect-square flex items-center justify-center"><img src="https://api.qrserver.com/v1/create-qr-code/?size=400x400&data=TechFit_User_Carlos_Silva" alt="QR Code" className="w-full h-full mix-blend-multiply" /></div>
                          <div className="flex flex-col items-center gap-2"><p className={`text-sm ${theme.textSec} font-bold uppercase tracking-widest`}>TechFit ID</p><p className={`text-2xl font-mono font-bold ${theme.accent}`}>1284-99</p></div>
                      </div>
                  </div>
              )}

              {/* CONTEXT MENU */}
              {contextMenu && (
                  <>
                      <div className="fixed inset-0 z-[90]" onClick={() => setContextMenu(null)}></div>
                      <div style={{ top: contextMenu.y, left: contextMenu.x }} className="fixed z-[100] bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg shadow-xl p-1 w-48 animate-in fade-in zoom-in-95 duration-100">
                          <button onClick={deleteArchivedChat} className="w-full text-left px-3 py-2 text-sm text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-md flex items-center gap-2">
                              <Trash2 width="14" /> Excluir conversa
                          </button>
                      </div>
                  </>
              )}
          </div>
      </>
  );
}