// frontend/src/App.jsx
import { BrowserRouter, Routes, Route } from 'react-router-dom';
import Login from './pages/Login';

function App() {
  return (
    <BrowserRouter>
      <Routes>
        {/* Rota raiz (/) carrega o Login */}
        <Route path="/" element={<Login />} />
        
        {/* Futuramente teremos:
        <Route path="/dashboard" element={<Dashboard />} /> 
        */}
      </Routes>
    </BrowserRouter>
  );
}

export default App;