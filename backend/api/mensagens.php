<?php
/**
 * API de Mensagens (Fake Email) - TechFit
 * Gerencia o correio eletrônico interno do sistema
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Responder OPTIONS para CORS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once '../config/database.php';

try {
    // ROTA GET: Retornar mensagens do usuário
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $user_email = isset($_GET['user_email']) ? trim(strtolower($_GET['user_email'])) : '';
        $user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;
        
        // Se fornecido user_id, buscar o email
        if ($user_id > 0 && empty($user_email)) {
            $stmt = $conn->prepare("SELECT email FROM usuarios WHERE id = :user_id");
            $stmt->bindParam(':user_id', $user_id);
            $stmt->execute();
            
            if ($stmt->rowCount() > 0) {
                $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
                $user_email = $usuario['email'];
            }
        }
        
        if (empty($user_email)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'E-mail ou ID do usuário é obrigatório']);
            exit();
        }
        
        // Buscar todas as mensagens do usuário
        $stmt = $conn->prepare("
            SELECT id, destinatario_email, assunto, corpo, lida, data_envio 
            FROM correio_eletronico 
            WHERE destinatario_email = :email 
            ORDER BY data_envio DESC
        ");
        $stmt->bindParam(':email', $user_email);
        $stmt->execute();
        
        $mensagens = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Contar mensagens não lidas
        $stmt = $conn->prepare("
            SELECT COUNT(*) as total_nao_lidas 
            FROM correio_eletronico 
            WHERE destinatario_email = :email AND lida = FALSE
        ");
        $stmt->bindParam(':email', $user_email);
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        
        http_response_code(200);
        echo json_encode([
            'success' => true,
            'total' => count($mensagens),
            'nao_lidas' => $resultado['total_nao_lidas'],
            'mensagens' => $mensagens
        ]);
        exit();
    }
    
    // ROTA POST: Marcar mensagem como lida
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (empty($data['mensagem_id'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'ID da mensagem é obrigatório']);
            exit();
        }
        
        $mensagem_id = intval($data['mensagem_id']);
        
        // Marcar como lida
        $stmt = $conn->prepare("UPDATE correio_eletronico SET lida = TRUE WHERE id = :id");
        $stmt->bindParam(':id', $mensagem_id);
        $stmt->execute();
        
        if ($stmt->rowCount() === 0) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Mensagem não encontrada']);
            exit();
        }
        
        http_response_code(200);
        echo json_encode([
            'success' => true,
            'message' => 'Mensagem marcada como lida'
        ]);
        exit();
    }
    
    // ROTA PUT: Atualizar todas as mensagens para lida
    if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
        $data = json_decode(file_get_contents('php://input'), true);
        
        $user_email = isset($data['user_email']) ? trim(strtolower($data['user_email'])) : '';
        
        if (empty($user_email)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'E-mail do usuário é obrigatório']);
            exit();
        }
        
        // Marcar todas como lidas
        $stmt = $conn->prepare("UPDATE correio_eletronico SET lida = TRUE WHERE destinatario_email = :email AND lida = FALSE");
        $stmt->bindParam(':email', $user_email);
        $stmt->execute();
        
        $total_atualizadas = $stmt->rowCount();
        
        http_response_code(200);
        echo json_encode([
            'success' => true,
            'message' => "Total de {$total_atualizadas} mensagens marcadas como lidas"
        ]);
        exit();
    }
    
    // Método não permitido
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método não permitido']);
    
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
?>
