# Documentação - Integração Frontend com Backend

## Alterações Implementadas no Frontend

### 1. Login.jsx - Ativação de Conta

#### Alterações:
- ✅ **Campo renomeado**: "TechFit ID" → "Código de Ativação"
- ✅ **Formato ajustado**: Aceita 8 dígitos (ex: `12345678`) ao invés de formato `XXXX-XX`
- ✅ **Placeholder atualizado**: `Ex: 12345678`
- ✅ **maxLength**: 8 caracteres

#### Função handleVerification:
A função já está implementada e envia corretamente:
```javascript
{
  email: verifyEmail.trim(),
  codigo_ativacao: verifyId.trim()
}
```

Para a rota: `backend/api/auth.php?action=activate`

#### Redirecionamento Automático:
Quando o usuário clica em um e-mail de ativação, o código é preenchido automaticamente através da URL:
```
/login?action=verify&code={CODIGO}
```

---

### 2. EmailLogin.jsx - Login do Fake Email

#### Alterações:
- ✅ **Função implementada**: `handleNext()` (substitui `handleLogin`)
- ✅ **Integração com backend**: `backend/api/auth.php?action=check-email`

#### Fluxo de Funcionamento:

```javascript
// 1. Validação de email
if (!trimmedEmail || !trimmedEmail.includes('@') || !trimmedEmail.includes('.')) {
    setIsError(true);
    showStatus('error', 'Digite um e-mail válido.');
    return;
}

// 2. Verificar no backend
const response = await fetch('http://localhost/techfit-sistema/backend/api/auth.php?action=check-email', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
        action: 'check-email',
        email: trimmedEmail
    })
});

const data = await response.json();

// 3. Se existe, salvar sessão e redirecionar
if (data.success && data.exists) {
    sessionStorage.setItem('email_session', trimmedEmail);
    navigate('/email-box');
} else {
    showStatus('error', 'E-mail não encontrado no sistema.');
}
```

---

### 3. EmailBox.jsx - Caixa de Entrada

#### Alterações:
- ✅ **Obtenção de email**: `sessionStorage.getItem('email_session')`
- ✅ **Fetch de mensagens**: `backend/api/mensagens.php?email={email}`
- ✅ **Mapeamento de dados**: Mensagens do backend + emails mockados de amigos

#### Estrutura do useEffect:

```javascript
useEffect(() => {
    const fetchEmailsAndUserData = async () => {
        // 1. Obter email da sessão
        const userEmail = sessionStorage.getItem('email_session');
        
        if (!userEmail) {
            navigate('/email-login');
            return;
        }

        // 2. Buscar mensagens do backend
        const response = await fetch(
            `http://localhost/techfit-sistema/backend/api/mensagens.php?email=${encodeURIComponent(userEmail)}`
        );
        const data = await response.json();

        if (data.success && data.mensagens) {
            // 3. Mapear mensagens para formato do frontend
            const mappedEmails = data.mensagens.map((msg) => ({
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

            // 4. Combinar com emails mockados
            setEmailsData([...mappedEmails, ...mockEmailsData]);
        }
    };

    fetchEmailsAndUserData();
}, [navigate]);
```

#### Botão de Ativação:

Quando o email contém "Ativação" no assunto, exibe botão que redireciona para:
```javascript
navigate(`/login?action=verify&code=${codigo_extraido}`);
```

O código é extraído do corpo do email via regex:
```javascript
const codeMatch = selectedEmail.body.match(/Código de Ativação:\s*(\d{8})/);
```

---

### 4. DashboardAluno.jsx - Dashboard do Aluno

#### Alterações:
- ✅ **Buscar perfil**: `backend/api/perfil.php?user_id={id}`
- ✅ **Salvar tema**: `POST backend/api/perfil.php`
- ✅ **localStorage**: Salva `user_id` e `user_tema`

#### Buscar Dados do Usuário:

```javascript
useEffect(() => {
    const fetchUserData = async () => {
        try {
            const userId = localStorage.getItem('user_id');
            
            if (!userId) {
                console.warn('Usuário não logado, usando dados mock');
                return;
            }

            const response = await fetch(
                `http://localhost/techfit-sistema/backend/api/perfil.php?user_id=${userId}`
            );
            const data = await response.json();

            if (data.success) {
                // Atualizar estado com dados reais
                setUserData({
                    name: data.data.nome,
                    plan: data.data.plano || 'Basic',
                    avatar: `https://api.dicebear.com/7.x/avataaars/svg?seed=${data.data.nome}&backgroundColor=0500ff`,
                    weight: data.data.peso || 78,
                    height: data.data.altura ? Math.round(data.data.altura * 100) : 182,
                    // ... outros campos
                });

                // Sincronizar tema
                if (data.data.preferencia_tema && data.data.preferencia_tema !== currentTheme) {
                    setCurrentTheme(data.data.preferencia_tema);
                    localStorage.setItem('user_tema', data.data.preferencia_tema);
                }
            }
        } catch (error) {
            console.error('Erro ao buscar dados:', error);
        }
    };

    fetchUserData();
}, []);
```

#### Troca de Tema (Salvar no Backend):

```javascript
<button onClick={async () => {
    const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
    
    // 1. Atualizar estado e localStorage
    setCurrentTheme(newTheme);
    localStorage.setItem('user_tema', newTheme);
    
    // 2. Salvar no backend
    try {
        const userId = localStorage.getItem('user_id');
        if (userId) {
            await fetch('http://localhost/techfit-sistema/backend/api/perfil.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    user_id: userId,
                    preferencia_tema: newTheme
                })
            });
        }
    } catch (error) {
        console.error('Erro ao salvar tema:', error);
    }
}}>
```

---

## Fluxo Completo de Matrícula e Ativação

```
1. Usuário preenche formulário em public_html/matricula.php
   ↓
2. Dados enviados para backend/api/matricula.php
   - Todos os campos são salvos (CPF, endereço, objetivo, etc.)
   - Código de ativação gerado (8 dígitos)
   - Email enviado para correio_eletronico
   ↓
3. Usuário faz login no Email Login (EmailLogin.jsx)
   - Verifica se email existe via auth.php?action=check-email
   - Salva email no sessionStorage
   ↓
4. Caixa de entrada carrega (EmailBox.jsx)
   - Busca mensagens via mensagens.php?email={email}
   - Exibe email de ativação com botão
   ↓
5. Usuário clica em "Ativar Conta Agora"
   - Redireciona para /login?action=verify&code={CODIGO}
   - Campo de código preenchido automaticamente
   ↓
6. Usuário confirma dados e ativa conta
   - Envia para auth.php?action=activate
   - Conta ativada, status alterado para "ativo"
   ↓
7. Usuário faz login normal
   - auth.php?action=login
   - Salva user_id no localStorage
   ↓
8. Dashboard carrega (DashboardAluno.jsx)
   - Busca perfil via perfil.php?user_id={id}
   - Exibe dados completos do usuário
   - Permite trocar tema (salva no backend)
```

---

## URLs das APIs Utilizadas

### Backend Base:
```
http://localhost/techfit-sistema/backend/api/
```

### Endpoints:

| Rota | Método | Descrição |
|------|--------|-----------|
| `auth.php?action=check-email` | POST | Verifica se email existe |
| `auth.php?action=activate` | POST | Ativa conta do usuário |
| `auth.php?action=login` | POST | Login do usuário |
| `mensagens.php?email={email}` | GET | Busca mensagens do email |
| `perfil.php?user_id={id}` | GET | Busca dados do perfil |
| `perfil.php` | POST/PUT | Atualiza dados do perfil |
| `matricula.php` | POST | Cadastro de novos usuários |

---

## Dados Armazenados

### localStorage:
```javascript
localStorage.setItem('user_id', userId);           // ID do usuário logado
localStorage.setItem('user_tema', theme);          // Tema preferido (light/dark)
localStorage.setItem('techfit_active_tab', tab);   // Aba ativa no dashboard
```

### sessionStorage:
```javascript
sessionStorage.setItem('email_session', email);    // Email para fake email system
```

---

## Tratamento de Erros

### EmailLogin.jsx:
```javascript
try {
    // Fetch...
} catch (error) {
    console.error('Erro ao verificar e-mail:', error);
    showStatus('error', 'Erro ao conectar com o servidor. Tente novamente.');
}
```

### EmailBox.jsx:
```javascript
try {
    // Fetch...
} catch (error) {
    console.error('Erro ao buscar mensagens:', error);
    // Mantém emails mockados em caso de erro
}
```

### DashboardAluno.jsx:
```javascript
try {
    // Fetch...
} catch (error) {
    console.error('Erro ao buscar dados:', error);
    // Continua com dados mockados
}
```

---

## Próximos Passos

1. ✅ Testar fluxo completo de matrícula → ativação → login
2. ✅ Verificar se o tema persiste após logout/login
3. ✅ Validar se os dados do perfil são exibidos corretamente
4. ⚠️ Implementar logout completo (limpar localStorage e sessionStorage)
5. ⚠️ Adicionar loading states nos fetch
6. ⚠️ Implementar retry automático em caso de erro de rede

---

**Data de Implementação:** 15 de dezembro de 2025  
**Versão:** 2.0.0  
**Status:** ✅ Totalmente Integrado
