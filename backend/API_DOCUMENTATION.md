# 📋 Documentação das APIs - TechFit Backend

## Estrutura do Banco de Dados

### Tabela: `usuarios`
- `id` - INT (Primary Key, Auto Increment)
- `nome` - VARCHAR(100)
- `email` - VARCHAR(100) (UNIQUE)
- `senha` - VARCHAR(255) (Hash)
- `plano` - VARCHAR(50) (Default: 'Basic')
- `peso` - DECIMAL(5,2)
- `altura` - DECIMAL(5,2)
- `preferencia_tema` - VARCHAR(20) (Default: 'light')
- `status` - VARCHAR(20) (Default: 'pendente')
- `codigo_ativacao` - VARCHAR(20)
- `data_cadastro` - TIMESTAMP
- `data_ativacao` - TIMESTAMP

### Tabela: `correio_eletronico` 
- `id` - INT (Primary Key, Auto Increment)
- `destinatario_email` - VARCHAR(100)
- `assunto` - VARCHAR(200)
- `corpo` - TEXT
- `lida` - BOOLEAN (Default: FALSE)
- `data_envio` - TIMESTAMP

---

## 🔧 APIs Implementadas

### 1. **matricula.php** - Matrícula de Novo Usuário

**Endpoint:** `POST /backend/api/matricula.php`

**Body (JSON):**
```json
{
  "nome": "João Silva",
  "email": "joao@email.com",
  "senha": "senha123",
  "plano": "Gold",
  "peso": 75.5,
  "altura": 1.75
}
```

**Campos Obrigatórios:** `nome`, `email`, `senha`

**Resposta de Sucesso (201):**
```json
{
  "success": true,
  "message": "Matrícula concluída. Verifique seu e-mail interno.",
  "user_id": 123,
  "email": "joao@email.com"
}
```

**Funcionalidades:**
- Valida formato de email
- Verifica duplicidade de email
- Gera código de ativação (8 dígitos)
- Hash da senha com `password_hash`
- Cria usuário com status 'pendente'
- Envia email de ativação no correio interno

---

### 2. **auth.php** - Autenticação e Ativação

#### 2.1 Login
**Endpoint:** `POST /backend/api/auth.php?action=login`

**Body (JSON):**
```json
{
  "action": "login",
  "email": "joao@email.com",
  "senha": "senha123"
}
```

**Resposta de Sucesso (200):**
```json
{
  "success": true,
  "message": "Login realizado com sucesso",
  "user_id": 123,
  "email": "joao@email.com",
  "nome": "João Silva",
  "status": "ativo",
  "plano": "Gold",
  "peso": 75.5,
  "altura": 1.75,
  "tema": "light"
}
```

#### 2.2 Ativação de Conta
**Endpoint:** `POST /backend/api/auth.php?action=activate`

**Body (JSON):**
```json
{
  "action": "activate",
  "email": "joao@email.com",
  "codigo_ativacao": "12345678"
}
```

**Resposta de Sucesso (200):**
```json
{
  "success": true,
  "message": "Conta ativada com sucesso!",
  "user_id": 123
}
```

**Funcionalidades:**
- Atualiza status para 'ativo'
- Registra data_ativacao
- Envia mensagem de boas-vindas no correio interno

#### 2.3 Verificar Email
**Endpoint:** `GET /backend/api/auth.php?action=check-email&email=joao@email.com`

Ou

**Endpoint:** `POST /backend/api/auth.php?action=check-email`

**Body (JSON):**
```json
{
  "action": "check-email",
  "email": "joao@email.com"
}
```

**Resposta (200):**
```json
{
  "success": true,
  "exists": true
}
```

---

### 3. **perfil.php** - Gerenciamento de Perfil

#### 3.1 Obter Dados do Perfil
**Endpoint:** `GET /backend/api/perfil.php?user_id=123`

**Resposta de Sucesso (200):**
```json
{
  "success": true,
  "data": {
    "id": 123,
    "nome": "João Silva",
    "email": "joao@email.com",
    "plano": "Gold",
    "peso": 75.5,
    "altura": 1.75,
    "preferencia_tema": "light",
    "status": "ativo",
    "data_cadastro": "2025-12-15 10:30:00",
    "data_ativacao": "2025-12-15 10:45:00"
  }
}
```

#### 3.2 Atualizar Perfil
**Endpoint:** `POST /backend/api/perfil.php` ou `PUT /backend/api/perfil.php`

**Body (JSON):**
```json
{
  "user_id": 123,
  "preferencia_tema": "dark",
  "peso": 76.0
}
```

**Campos Atualizáveis:** `nome`, `plano`, `peso`, `altura`, `preferencia_tema`

**Resposta de Sucesso (200):**
```json
{
  "success": true,
  "message": "Perfil atualizado com sucesso",
  "data": {
    "id": 123,
    "nome": "João Silva",
    "email": "joao@email.com",
    "plano": "Gold",
    "peso": 76.0,
    "altura": 1.75,
    "preferencia_tema": "dark",
    "status": "ativo"
  }
}
```

---

### 4. **mensagens.php** - Correio Eletrônico (Fake Email)

#### 4.1 Listar Mensagens
**Endpoint:** `GET /backend/api/mensagens.php?user_email=joao@email.com`

Ou

**Endpoint:** `GET /backend/api/mensagens.php?user_id=123`

**Resposta de Sucesso (200):**
```json
{
  "success": true,
  "total": 2,
  "nao_lidas": 1,
  "mensagens": [
    {
      "id": 1,
      "destinatario_email": "joao@email.com",
      "assunto": "Conta Ativada com Sucesso!",
      "corpo": "Olá, João Silva!...",
      "lida": false,
      "data_envio": "2025-12-15 10:45:00"
    },
    {
      "id": 2,
      "destinatario_email": "joao@email.com",
      "assunto": "Ativação de Conta - TechFit",
      "corpo": "Olá, João Silva! Bem-vindo(a)...",
      "lida": true,
      "data_envio": "2025-12-15 10:30:00"
    }
  ]
}
```

#### 4.2 Marcar Mensagem Como Lida
**Endpoint:** `POST /backend/api/mensagens.php`

**Body (JSON):**
```json
{
  "mensagem_id": 1
}
```

**Resposta de Sucesso (200):**
```json
{
  "success": true,
  "message": "Mensagem marcada como lida"
}
```

#### 4.3 Marcar Todas Como Lidas
**Endpoint:** `PUT /backend/api/mensagens.php`

**Body (JSON):**
```json
{
  "user_email": "joao@email.com"
}
```

**Resposta de Sucesso (200):**
```json
{
  "success": true,
  "message": "Total de 3 mensagens marcadas como lidas"
}
```

---

## 🔒 Segurança Implementada

- ✅ Senhas armazenadas com `password_hash()` (bcrypt)
- ✅ Proteção contra SQL Injection (prepared statements)
- ✅ Validação de formato de email
- ✅ Verificação de duplicidade de email
- ✅ Headers CORS configurados
- ✅ Validação de tipos de dados
- ✅ Códigos HTTP apropriados

---

## 📝 Notas Importantes

1. **Configuração do Banco:** Execute `database/schema.sql` no MySQL antes de usar as APIs
2. **Conexão:** Ajuste as credenciais em `backend/config/database.php`
3. **CORS:** Todas as APIs permitem `Access-Control-Allow-Origin: *`
4. **Formato:** Todas as respostas são em JSON
5. **Status HTTP:** As APIs seguem padrões REST (200, 201, 400, 401, 404, 500)

---

## 🧪 Testando as APIs

### Exemplo com cURL:

```bash
# Cadastro
curl -X POST http://localhost/backend/api/matricula.php \
  -H "Content-Type: application/json" \
  -d '{"nome":"João Silva","email":"joao@email.com","senha":"senha123","plano":"Gold"}'

# Login
curl -X POST http://localhost/backend/api/auth.php?action=login \
  -H "Content-Type: application/json" \
  -d '{"email":"joao@email.com","senha":"senha123"}'

# Listar Mensagens
curl -X GET "http://localhost/backend/api/mensagens.php?user_email=joao@email.com"
```

### Exemplo com JavaScript (fetch):

```javascript
// Cadastro
const response = await fetch('http://localhost/backend/api/matricula.php', {
  method: 'POST',
  headers: { 'Content-Type': 'application/json' },
  body: JSON.stringify({
    nome: 'João Silva',
    email: 'joao@email.com',
    senha: 'senha123',
    plano: 'Gold'
  })
});
const data = await response.json();
console.log(data);
```

---

## 📦 Arquivos Criados

- ✅ `backend/api/matricula.php` - Processamento de matrícula
- ✅ `backend/api/auth.php` - Autenticação e ativação
- ✅ `backend/api/perfil.php` - Gerenciamento de perfil
- ✅ `backend/api/mensagens.php` - Correio eletrônico interno
- ✅ `database/schema.sql` - Estrutura do banco atualizada
