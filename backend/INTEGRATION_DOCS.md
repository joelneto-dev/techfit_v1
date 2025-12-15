# Documentação de Integração - Fluxo de Matrícula e Perfil

## Alterações Implementadas

### 1. Banco de Dados (database/schema.sql)

Foram adicionados os seguintes campos na tabela `usuarios`:

```sql
- cpf VARCHAR(14)              -- CPF do usuário
- data_nascimento DATE          -- Data de nascimento
- telefone VARCHAR(15)          -- Telefone/celular
- cep VARCHAR(9)                -- CEP
- estado VARCHAR(2)             -- UF (sigla do estado)
- cidade VARCHAR(60)            -- Cidade
- rua VARCHAR(120)              -- Logradouro
- numero VARCHAR(10)            -- Número do endereço
- bairro VARCHAR(60)            -- Bairro
- objetivo VARCHAR(50)          -- Objetivo do aluno
- ciclo_plano VARCHAR(20)       -- Ciclo do plano (monthly/annual)
- metodo_pagamento VARCHAR(20)  -- Método de pagamento escolhido
- preferencia_tema VARCHAR(20)  -- Tema preferido (light/dark)
```

**Importante:** Execute o seguinte comando SQL para atualizar o banco existente:

```sql
ALTER TABLE usuarios 
ADD COLUMN cpf VARCHAR(14) DEFAULT NULL AFTER senha,
ADD COLUMN data_nascimento DATE DEFAULT NULL AFTER cpf,
ADD COLUMN telefone VARCHAR(15) DEFAULT NULL AFTER data_nascimento,
ADD COLUMN cep VARCHAR(9) DEFAULT NULL AFTER telefone,
ADD COLUMN estado VARCHAR(2) DEFAULT NULL AFTER cep,
ADD COLUMN cidade VARCHAR(60) DEFAULT NULL AFTER estado,
ADD COLUMN rua VARCHAR(120) DEFAULT NULL AFTER cidade,
ADD COLUMN numero VARCHAR(10) DEFAULT NULL AFTER rua,
ADD COLUMN bairro VARCHAR(60) DEFAULT NULL AFTER numero,
ADD COLUMN objetivo VARCHAR(50) DEFAULT NULL AFTER bairro,
ADD COLUMN ciclo_plano VARCHAR(20) DEFAULT 'monthly' AFTER objetivo,
ADD COLUMN metodo_pagamento VARCHAR(20) DEFAULT NULL AFTER ciclo_plano;
```

---

### 2. API de Matrícula (backend/api/matricula.php)

#### Alterações:
- **Recebe e valida** todos os novos campos do formulário
- **INSERT expandido** para salvar todos os dados completos
- **Compatível** com o formulário existente

#### Exemplo de Requisição POST:

```json
{
  "nome": "João Silva",
  "email": "joao@example.com",
  "senha": "12345678",
  "plano": "black",
  "peso": null,
  "altura": null,
  "cpf": "123.456.789-00",
  "data_nascimento": "1990-05-15",
  "telefone": "(11) 98765-4321",
  "cep": "01234-567",
  "estado": "SP",
  "cidade": "São Paulo",
  "rua": "Rua Exemplo",
  "numero": "100",
  "bairro": "Centro",
  "objetivo": "hipertrofia",
  "ciclo_plano": "monthly",
  "metodo_pagamento": "credit"
}
```

#### Resposta de Sucesso (201):

```json
{
  "success": true,
  "message": "Matrícula concluída. Verifique seu e-mail interno.",
  "user_id": 123,
  "email": "joao@example.com"
}
```

---

### 3. API de Perfil (backend/api/perfil.php)

Nova API criada para gerenciar dados do usuário e preferências.

#### 3.1 GET - Buscar Dados do Perfil

**Endpoint:** `GET /backend/api/perfil.php?user_id={id}`

**Exemplo de Requisição:**
```
GET /backend/api/perfil.php?user_id=1
```

**Resposta de Sucesso (200):**

```json
{
  "success": true,
  "data": {
    "id": 1,
    "nome": "João Silva",
    "email": "joao@example.com",
    "cpf": "123.456.789-00",
    "data_nascimento": "1990-05-15",
    "telefone": "(11) 98765-4321",
    "cep": "01234-567",
    "estado": "SP",
    "cidade": "São Paulo",
    "rua": "Rua Exemplo",
    "numero": "100",
    "bairro": "Centro",
    "objetivo": "hipertrofia",
    "plano": "black",
    "ciclo_plano": "monthly",
    "metodo_pagamento": "credit",
    "peso": null,
    "altura": null,
    "preferencia_tema": "light",
    "status": "ativo",
    "data_cadastro": "2025-12-15 10:30:00",
    "data_ativacao": "2025-12-15 11:00:00"
  }
}
```

#### 3.2 POST/PUT - Atualizar Dados do Perfil

**Endpoint:** `POST /backend/api/perfil.php` ou `PUT /backend/api/perfil.php`

**Exemplo de Requisição (Atualizar Tema):**

```json
{
  "user_id": 1,
  "preferencia_tema": "dark"
}
```

**Exemplo de Requisição (Atualizar Múltiplos Campos):**

```json
{
  "user_id": 1,
  "preferencia_tema": "dark",
  "peso": 75.5,
  "altura": 1.75,
  "telefone": "(11) 91234-5678"
}
```

**Resposta de Sucesso (200):**

```json
{
  "success": true,
  "message": "Perfil atualizado com sucesso",
  "data": {
    "id": 1,
    "nome": "João Silva",
    "email": "joao@example.com",
    "cpf": "123.456.789-00",
    "data_nascimento": "1990-05-15",
    "telefone": "(11) 91234-5678",
    "cep": "01234-567",
    "estado": "SP",
    "cidade": "São Paulo",
    "rua": "Rua Exemplo",
    "numero": "100",
    "bairro": "Centro",
    "objetivo": "hipertrofia",
    "plano": "black",
    "ciclo_plano": "monthly",
    "metodo_pagamento": "credit",
    "peso": 75.5,
    "altura": 1.75,
    "preferencia_tema": "dark",
    "status": "ativo",
    "data_cadastro": "2025-12-15 10:30:00",
    "data_ativacao": "2025-12-15 11:00:00"
  }
}
```

#### Campos Permitidos para Atualização:
- `nome`
- `cpf`
- `data_nascimento`
- `telefone`
- `cep`
- `estado`
- `cidade`
- `rua`
- `numero`
- `bairro`
- `objetivo`
- `plano`
- `ciclo_plano`
- `metodo_pagamento`
- `peso`
- `altura`
- `preferencia_tema`

---

### 4. Formulário de Matrícula (public_html/matricula.php)

#### Alterações no JavaScript:
- **Coleta todos os campos** do formulário (incluindo endereço completo, objetivo, ciclo e método de pagamento)
- **Envia dados completos** para a API de matrícula
- **Compatível** com a estrutura existente

---

## Exemplos de Uso

### Exemplo 1: Buscar Dados do Perfil (JavaScript)

```javascript
async function buscarPerfil(userId) {
  try {
    const response = await fetch(`/backend/api/perfil.php?user_id=${userId}`);
    const data = await response.json();
    
    if (data.success) {
      console.log('Dados do usuário:', data.data);
      console.log('Tema preferido:', data.data.preferencia_tema);
    }
  } catch (error) {
    console.error('Erro ao buscar perfil:', error);
  }
}
```

### Exemplo 2: Alterar Tema do Usuário

```javascript
async function alterarTema(userId, novoTema) {
  try {
    const response = await fetch('/backend/api/perfil.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({
        user_id: userId,
        preferencia_tema: novoTema // 'light' ou 'dark'
      })
    });
    
    const data = await response.json();
    
    if (data.success) {
      console.log('Tema atualizado com sucesso!');
      // Aplicar o tema na interface
      document.body.className = novoTema === 'dark' ? 'dark-theme' : 'light-theme';
    }
  } catch (error) {
    console.error('Erro ao atualizar tema:', error);
  }
}
```

### Exemplo 3: Atualizar Dados de Endereço

```javascript
async function atualizarEndereco(userId, endereco) {
  try {
    const response = await fetch('/backend/api/perfil.php', {
      method: 'PUT',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({
        user_id: userId,
        cep: endereco.cep,
        estado: endereco.estado,
        cidade: endereco.cidade,
        rua: endereco.rua,
        numero: endereco.numero,
        bairro: endereco.bairro
      })
    });
    
    const data = await response.json();
    
    if (data.success) {
      console.log('Endereço atualizado!');
    }
  } catch (error) {
    console.error('Erro ao atualizar endereço:', error);
  }
}
```

---

## Tratamento de Erros

### Erros Comuns da API de Perfil:

| Código | Mensagem | Descrição |
|--------|----------|-----------|
| 400 | user_id é obrigatório | O parâmetro user_id não foi fornecido |
| 400 | Nenhum campo para atualizar | Tentou atualizar sem fornecer nenhum campo válido |
| 404 | Usuário não encontrado | O user_id fornecido não existe no banco |
| 405 | Método não permitido | Tentou usar método HTTP não suportado (ex: DELETE) |
| 500 | Erro no banco de dados | Erro interno do servidor |

---

## Segurança

1. **Validação de Entrada:** Todos os dados são sanitizados antes de serem inseridos no banco
2. **Prepared Statements:** Uso de PDO com bindParam para prevenir SQL Injection
3. **CORS:** Configurado para aceitar requisições do frontend
4. **Senha:** Não é retornada nas consultas GET do perfil

---

## Próximos Passos Recomendados

1. Implementar validação de CPF único no backend
2. Adicionar verificação de email duplicado
3. Implementar upload de foto de perfil
4. Criar endpoint para alteração de senha
5. Adicionar logs de auditoria para mudanças no perfil

---

**Data de Implementação:** 15 de dezembro de 2025  
**Versão:** 1.0.0
