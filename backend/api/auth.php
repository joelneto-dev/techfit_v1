<?php
/**
 * API de Autenticação - TechFit
 * Gerencia login, ativação de conta e verificação de email
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Responder OPTIONS para CORS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once '../config/database.php';

// Obter a ação da rota (query string ou corpo da requisição)
$action = isset($_GET['action']) ? $_GET['action'] : '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    if (isset($data['action'])) {
        $action = $data['action'];
    }
}

try {
    // ROTA: LOGIN
    if ($action === 'login' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (empty($data['email']) || empty($data['senha'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'E-mail e senha são obrigatórios']);
            exit();
        }
        
        $email = trim(strtolower($data['email']));
        $senha = $data['senha'];
        
        // Buscar usuário no banco
        $stmt = $conn->prepare("SELECT id, nome, email, senha, status, plano, peso, altura, preferencia_tema FROM usuarios WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        
        if ($stmt->rowCount() === 0) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'E-mail ou senha incorretos']);
            exit();
        }
        
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Verificar senha
        if (!password_verify($senha, $usuario['senha'])) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'E-mail ou senha incorretos']);
            exit();
        }
        
        // Login bem-sucedido
        unset($usuario['senha']); // Remover senha da resposta
        
        http_response_code(200);
        echo json_encode([
            'success' => true,
            'message' => 'Login realizado com sucesso',
            'user_id' => $usuario['id'],
            'email' => $usuario['email'],
            'nome' => $usuario['nome'],
            'status' => $usuario['status'],
            'plano' => $usuario['plano'],
            'peso' => $usuario['peso'],
            'altura' => $usuario['altura'],
            'tema' => $usuario['preferencia_tema']
        ]);
        exit();
    }
    
    // ROTA: ATIVAÇÃO DE CONTA
    if ($action === 'activate' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (empty($data['email']) || empty($data['codigo_ativacao'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'E-mail e código de ativação são obrigatórios']);
            exit();
        }
        
        $email = trim(strtolower($data['email']));
        $codigo_ativacao = trim($data['codigo_ativacao']);
        
        // Buscar usuário com código de ativação
        $stmt = $conn->prepare("SELECT id, nome, status FROM usuarios WHERE email = :email AND codigo_ativacao = :codigo");
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':codigo', $codigo_ativacao);
        $stmt->execute();
        
        if ($stmt->rowCount() === 0) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'E-mail ou código de ativação inválidos']);
            exit();
        }
        
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Verificar se já está ativo
        if ($usuario['status'] === 'ativo') {
            http_response_code(200);
            echo json_encode([
                'success' => true,
                'message' => 'Esta conta já está ativa',
                'user_id' => $usuario['id']
            ]);
            exit();
        }
        
        // Ativar conta
        $stmt = $conn->prepare("UPDATE usuarios SET status = 'ativo', data_ativacao = NOW() WHERE id = :id");
        $stmt->bindParam(':id', $usuario['id']);
        $stmt->execute();
        
        // Enviar mensagem de boas-vindas
        $assunto = "Conta Ativada com Sucesso!";
        $corpo = "Olá, {$usuario['nome']}!\n\n";
        $corpo .= "Sua conta foi ativada com sucesso! 🎉\n\n";
        $corpo .= "Agora você tem acesso completo a todas as funcionalidades da TechFit.\n\n";
        $corpo .= "Aproveite sua jornada fitness!\n\n";
        $corpo .= "Atenciosamente,\nEquipe TechFit";
        
        $stmt = $conn->prepare("INSERT INTO correio_eletronico (destinatario_email, assunto, corpo) VALUES (:email, :assunto, :corpo)");
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':assunto', $assunto);
        $stmt->bindParam(':corpo', $corpo);
        $stmt->execute();
        
        http_response_code(200);
        echo json_encode([
            'success' => true,
            'message' => 'Conta ativada com sucesso!',
            'user_id' => $usuario['id']
        ]);
        exit();
    }
    
    // ROTA: VERIFICAR SE EMAIL EXISTE
    if ($action === 'check-email') {
        $email = '';
        
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $email = isset($_GET['email']) ? trim(strtolower($_GET['email'])) : '';
        } else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = json_decode(file_get_contents('php://input'), true);
            $email = isset($data['email']) ? trim(strtolower($data['email'])) : '';
        }
        
        if (empty($email)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'E-mail é obrigatório']);
            exit();
        }
        
        $stmt = $conn->prepare("SELECT id FROM usuarios WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        
        $exists = $stmt->rowCount() > 0;
        
        http_response_code(200);
        echo json_encode([
            'success' => true,
            'exists' => $exists
        ]);
        exit();
    }
    
    // Ação não reconhecida
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Ação não reconhecida. Use: login, activate ou check-email'
    ]);
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Erro no banco de dados',
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
