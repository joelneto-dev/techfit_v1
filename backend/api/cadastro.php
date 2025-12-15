<?php
/**
 * API de Cadastro/Matrícula - TechFit
 * Processa o cadastro de novos usuários e envia e-mail de ativação
 */

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
    if (empty($data['nome']) || empty($data['email']) || empty($data['senha'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Campos obrigatórios: nome, email e senha']);
        exit();
    }
    
    $nome = trim($data['nome']);
    $email = trim(strtolower($data['email']));
    $senha = $data['senha'];
    $plano = isset($data['plano']) ? $data['plano'] : 'Basic';
    $peso = isset($data['peso']) ? $data['peso'] : null;
    $altura = isset($data['altura']) ? $data['altura'] : null;
    
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
        INSERT INTO usuarios (nome, email, senha, plano, peso, altura, status, codigo_ativacao) 
        VALUES (:nome, :email, :senha, :plano, :peso, :altura, 'pendente', :codigo_ativacao)
    ");
    
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':senha', $senha_hash);
    $stmt->bindParam(':plano', $plano);
    $stmt->bindParam(':peso', $peso);
    $stmt->bindParam(':altura', $altura);
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
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Erro ao processar matrícula',
        'error' => $e->getMessage()
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Erro interno do servidor',
        'error' => $e->getMessage()
    ]);
}
