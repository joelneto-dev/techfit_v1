# 🗄️ Sistema de Banco de Dados Automático - TechFit

## ✅ Configuração Implementada

O sistema agora possui **criação e verificação automática** do banco de dados e tabelas!

### 🔄 Como Funciona

Quando qualquer API é acessada pela primeira vez, o arquivo `backend/config/database.php` automaticamente:

1. **Conecta ao MySQL** (sem especificar banco)
2. **Verifica se o banco `techfit_db` existe**
   - ❌ Se não existir → Cria automaticamente
   - ✅ Se existir → Apenas conecta
3. **Verifica se as tabelas existem**
   - ❌ Se não existirem → Cria todas as tabelas necessárias
   - ✅ Se existirem → Apenas conecta
4. **Insere dados de exemplo** (se for primeira execução)

---

## 🚀 Como Usar

### 1️⃣ Configure as Credenciais

Edite o arquivo [`backend/config/database.php`](backend/config/database.php):

```php
$host = 'localhost';
$db_name = 'techfit_db';
$username = 'root';          // ← Seu usuário MySQL
$password = '1234';          // ← Sua senha MySQL
```

### 2️⃣ Inicie o Servidor PHP

Certifique-se de que o MySQL está rodando (XAMPP, WAMP, MAMP, etc)

### 3️⃣ Teste a Conexão

Acesse no navegador:
```
http://localhost/techfit-sistema/backend/test-database.php
```

Você verá um painel visual com:
- ✅ Status da conexão
- ✅ Verificação do banco de dados
- ✅ Lista de tabelas criadas
- ✅ Contadores de registros
- ✅ Estrutura da tabela `usuarios`

### 4️⃣ Use Qualquer API

Todas as APIs já funcionarão automaticamente:
```
http://localhost/techfit-sistema/backend/api/cadastro.php
http://localhost/techfit-sistema/backend/api/auth.php
http://localhost/techfit-sistema/backend/api/perfil.php
http://localhost/techfit-sistema/backend/api/mensagens.php
```

---

## 📊 Estrutura Criada Automaticamente

### Banco de Dados
- **Nome:** `techfit_db`
- **Charset:** utf8mb4_unicode_ci

### Tabelas

#### 1. `usuarios`
```sql
- id (INT, PK, Auto Increment)
- nome (VARCHAR 100)
- email (VARCHAR 100, UNIQUE)
- senha (VARCHAR 255) - Hash bcrypt
- plano (VARCHAR 50) - Default: 'Basic'
- peso (DECIMAL 5,2)
- altura (DECIMAL 5,2)
- preferencia_tema (VARCHAR 20) - Default: 'light'
- status (VARCHAR 20) - Default: 'pendente'
- codigo_ativacao (VARCHAR 20)
- data_cadastro (TIMESTAMP)
- data_ativacao (TIMESTAMP)
```

#### 2. `correio_eletronico`
```sql
- id (INT, PK, Auto Increment)
- destinatario_email (VARCHAR 100, Indexed)
- assunto (VARCHAR 200)
- corpo (TEXT)
- lida (BOOLEAN) - Default: FALSE
- data_envio (TIMESTAMP)
```

#### 3. `alunos`
```sql
- id (INT, PK, Auto Increment)
- nome (VARCHAR 100)
- email (VARCHAR 100)
- plano (VARCHAR 50)
```

---

## 🔧 Logs e Debugging

Os logs são salvos automaticamente:

```php
// Quando o banco é criado:
✅ Banco de dados 'techfit_db' criado automaticamente!

// Quando as tabelas são criadas:
✅ Tabelas criadas automaticamente no banco 'techfit_db'!
```

Verifique os logs em:
- **XAMPP:** `xampp/apache/logs/error.log`
- **WAMP:** `wamp/logs/php_error.log`

---

## ⚠️ Troubleshooting

### Erro: "Access denied for user"
```
❌ Solução: Verifique username e password em database.php
```

### Erro: "Unknown database"
```
✅ Normal! O sistema criará o banco automaticamente na primeira execução
```

### Erro: "Can't connect to MySQL server"
```
❌ Solução: Inicie o MySQL (XAMPP/WAMP Control Panel)
```

### Tabelas não foram criadas
```
✅ Verifique permissões do usuário MySQL:
   GRANT ALL PRIVILEGES ON *.* TO 'root'@'localhost';
```

---

## 🔐 Segurança

### ⚠️ Importante para Produção

Antes de colocar em produção, **desabilite a criação automática**:

1. Execute o sistema uma vez localmente
2. Exporte o banco de dados via phpMyAdmin
3. Na produção, importe o SQL manualmente
4. Comente a lógica de criação automática em `database.php`

---

## 📝 Manutenção

### Adicionar Nova Tabela

Edite `backend/config/database.php` e adicione:

```php
$conn->exec("
    CREATE TABLE IF NOT EXISTS nova_tabela (
        id INT AUTO_INCREMENT PRIMARY KEY,
        campo VARCHAR(100) NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
");
```

### Resetar o Banco

Via phpMyAdmin ou terminal:
```sql
DROP DATABASE techfit_db;
```

Na próxima execução, tudo será recriado automaticamente!

---

## 🎯 Vantagens da Automação

✅ **Zero configuração manual** - Funciona "out of the box"
✅ **Desenvolvimento rápido** - Novos desenvolvedores começam imediatamente  
✅ **Sem erros de digitação** - SQL garantido e testado
✅ **Portabilidade** - Funciona em qualquer ambiente PHP/MySQL
✅ **Logs claros** - Fácil debug de problemas

---

**Sistema pronto para uso! 🚀**
