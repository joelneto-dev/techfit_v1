// frontend/src/App.jsx
import { BrowserRouter, Routes, Route } from 'react-router-dom';
import SystemAuthPage from './pages/Login';
import DashboardAluno from './pages/DashboardAluno';
import AdminPanel from './pages/AdminPanel';
import EmailLogin from './pages/EmailLogin';
import EmailBox from './pages/EmailBox';

function App() {
  return (
    <BrowserRouter>
      <Routes>
        <Route path="/" element={<SystemAuthPage />} />
        <Route path="/dashboard-aluno" element={<DashboardAluno />} />
        <Route path="/admin-panel" element={<AdminPanel />} />
        <Route path="/email-login" element={<EmailLogin />} />
        <Route path="/email-box" element={<EmailBox />} />
      </Routes>
    </BrowserRouter>
  );
}

export default App;