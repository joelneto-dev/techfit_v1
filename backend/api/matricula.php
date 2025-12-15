<?php
/**
 * API de Matrícula - TechFit
 * Processa o cadastro de novos usuários e envia e-mail de ativação
 */

// Configurar tratamento global de erros para garantir JSON sempre
set_error_handler(function($errno, $errstr, $errfile, $errline) {
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
});

register_shutdown_function(function() {
    $error = error_get_last();
    if ($error !== null && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
        header('Content-Type: application/json');
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Erro fatal no servidor',
            'error' => $error['message']
        ]);
    }
});

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Responder OPTIONS para CORS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once '../config/database.php';

// Apenas aceitar POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método não permitido']);
    exit();
}

try {
    // Receber dados do POST
    $data = json_decode(file_get_contents('php://input'), true);
    
    // Validar campos obrigatórios
    if (empty($data['nome']) || empty($data['email'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Campos obrigatórios: nome e email']);
        exit();
    }
    
    $nome = trim($data['nome']);
    $email = trim(strtolower($data['email']));
    // Senha inicial = 8 primeiros dígitos do CPF (sem formatação)
    $cpf_limpo = isset($data['cpf']) ? preg_replace('/\D/', '', $data['cpf']) : '';
    $senha = !empty($cpf_limpo) && strlen($cpf_limpo) >= 8 ? substr($cpf_limpo, 0, 8) : (isset($data['senha']) ? $data['senha'] : '12345678');
    $plano = isset($data['plano']) ? $data['plano'] : 'Basic';
    $peso = isset($data['peso']) ? $data['peso'] : null;
    $altura = isset($data['altura']) ? $data['altura'] : null;
    
    // Novos campos do formulário de matrícula
    $cpf = isset($data['cpf']) ? trim($data['cpf']) : null;
    $data_nascimento = isset($data['data_nascimento']) ? $data['data_nascimento'] : null;
    $telefone = isset($data['telefone']) ? trim($data['telefone']) : null;
    $cep = isset($data['cep']) ? trim($data['cep']) : null;
    $estado = isset($data['estado']) ? trim($data['estado']) : null;
    $cidade = isset($data['cidade']) ? trim($data['cidade']) : null;
    $rua = isset($data['rua']) ? trim($data['rua']) : null;
    $numero = isset($data['numero']) ? trim($data['numero']) : null;
    $bairro = isset($data['bairro']) ? trim($data['bairro']) : null;
    $objetivo = isset($data['objetivo']) ? $data['objetivo'] : null;
    $ciclo_plano = isset($data['ciclo_plano']) ? $data['ciclo_plano'] : 'monthly';
    $metodo_pagamento = isset($data['metodo_pagamento']) ? $data['metodo_pagamento'] : null;
    
    // Validar formato de email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Formato de e-mail inválido']);
        exit();
    }
    
    // Verificar se o email já está cadastrado
    $stmt = $conn->prepare("SELECT id FROM usuarios WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    
    if ($stmt->rowCount() > 0) {
        http_response_code(409);
        echo json_encode(['success' => false, 'message' => 'Este e-mail já está cadastrado']);
        exit();
    }
    
    // Gerar código de ativação (8 dígitos)
    $codigo_ativacao = str_pad(rand(0, 99999999), 8, '0', STR_PAD_LEFT);
    
    // Hash da senha
    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
    
    // Inserir novo usuário
    $stmt = $conn->prepare("
        INSERT INTO usuarios (
            nome, email, senha, plano, peso, altura, 
            cpf, data_nascimento, telefone, cep, estado, cidade, 
            rua, numero, bairro, objetivo, ciclo_plano, metodo_pagamento,
            status, codigo_ativacao
        ) 
        VALUES (
            :nome, :email, :senha, :plano, :peso, :altura,
            :cpf, :data_nascimento, :telefone, :cep, :estado, :cidade,
            :rua, :numero, :bairro, :objetivo, :ciclo_plano, :metodo_pagamento,
            'pendente', :codigo_ativacao
        )
    ");
    
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':senha', $senha_hash);
    $stmt->bindParam(':plano', $plano);
    $stmt->bindParam(':peso', $peso);
    $stmt->bindParam(':altura', $altura);
    $stmt->bindParam(':cpf', $cpf);
    $stmt->bindParam(':data_nascimento', $data_nascimento);
    $stmt->bindParam(':telefone', $telefone);
    $stmt->bindParam(':cep', $cep);
    $stmt->bindParam(':estado', $estado);
    $stmt->bindParam(':cidade', $cidade);
    $stmt->bindParam(':rua', $rua);
    $stmt->bindParam(':numero', $numero);
    $stmt->bindParam(':bairro', $bairro);
    $stmt->bindParam(':objetivo', $objetivo);
    $stmt->bindParam(':ciclo_plano', $ciclo_plano);
    $stmt->bindParam(':metodo_pagamento', $metodo_pagamento);
    $stmt->bindParam(':codigo_ativacao', $codigo_ativacao);
    
    $stmt->execute();
    $user_id = $conn->lastInsertId();
    
    // Criar mensagem de ativação no correio eletrônico
    $assunto = "Ativação de Conta - TechFit";
    $corpo = "Olá, {$nome}!\n\n";
    $corpo .= "Bem-vindo(a) à TechFit! Seu cadastro foi realizado com sucesso.\n\n";
    $corpo .= "Para ativar sua conta, utilize o código abaixo:\n\n";
    $corpo .= "Código de Ativação: {$codigo_ativacao}\n\n";
    $corpo .= "Acesse a página de ativação e insira este código junto com seu e-mail.\n\n";
    $corpo .= "Detalhes do seu plano:\n";
    $corpo .= "- Plano: {$plano}\n";
    if ($peso) $corpo .= "- Peso: {$peso} kg\n";
    if ($altura) $corpo .= "- Altura: {$altura} m\n";
    $corpo .= "\nAproveite todos os benefícios da TechFit!\n\n";
    $corpo .= "Atenciosamente,\nEquipe TechFit";
    
    $stmt = $conn->prepare("
        INSERT INTO correio_eletronico (destinatario_email, assunto, corpo) 
        VALUES (:email, :assunto, :corpo)
    ");
    
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':assunto', $assunto);
    $stmt->bindParam(':corpo', $corpo);
    $stmt->execute();
    
    // Retornar sucesso
    http_response_code(201);
    echo json_encode([
        'success' => true,
        'message' => 'Matrícula concluída. Verifique seu e-mail interno.',
        'user_id' => $user_id,
        'email' => $email
    ]);
    
} catch (PDOException $e) {
    error_log("Erro PDO na matrícula: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Erro ao processar matrícula no banco de dados',
        'error' => $e->getMessage()
    ]);
} catch (ErrorException $e) {
    error_log("Erro PHP na matrícula: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Erro de execução no servidor',
        'error' => $e->getMessage()
    ]);
} catch (Exception $e) {
    error_log("Erro geral na matrícula: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Erro interno do servidor',
        'error' => $e->getMessage()
    ]);
} catch (Throwable $e) {
    error_log("Erro crítico na matrícula: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Erro crítico no servidor',
        'error' => $e->getMessage()
    ]);
}