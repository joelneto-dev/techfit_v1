<?php
/**
 * API de Perfil - TechFit
 * Gerencia dados do usuário e preferências
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
    // ROTA GET: Retornar dados do perfil
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;
        
        if ($user_id <= 0) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'ID de usuário inválido']);
            exit();
        }
        
        $stmt = $conn->prepare("
            SELECT id, nome, email, plano, peso, altura, preferencia_tema, status, data_cadastro, data_ativacao 
            FROM usuarios 
            WHERE id = :user_id
        ");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        
        if ($stmt->rowCount() === 0) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Usuário não encontrado']);
            exit();
        }
        
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        
        http_response_code(200);
        echo json_encode([
            'success' => true,
            'data' => [
                'id' => $usuario['id'],
                'nome' => $usuario['nome'],
                'email' => $usuario['email'],
                'plano' => $usuario['plano'],
                'peso' => $usuario['peso'],
                'altura' => $usuario['altura'],
                'preferencia_tema' => $usuario['preferencia_tema'],
                'status' => $usuario['status'],
                'data_cadastro' => $usuario['data_cadastro'],
                'data_ativacao' => $usuario['data_ativacao']
            ]
        ]);
        exit();
    }
    
    // ROTA POST/PUT: Atualizar dados do perfil
    if ($_SERVER['REQUEST_METHOD'] === 'POST' || $_SERVER['REQUEST_METHOD'] === 'PUT') {
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (empty($data['user_id'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'ID de usuário é obrigatório']);
            exit();
        }
        
        $user_id = intval($data['user_id']);
        
        // Verificar se o usuário existe
        $stmt = $conn->prepare("SELECT id FROM usuarios WHERE id = :user_id");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        
        if ($stmt->rowCount() === 0) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Usuário não encontrado']);
            exit();
        }
        
        // Construir query de atualização dinâmica
        $campos_atualizacao = [];
        $valores = [':user_id' => $user_id];
        
        // Campos permitidos para atualização
        $campos_permitidos = ['nome', 'plano', 'peso', 'altura', 'preferencia_tema'];
        
        foreach ($campos_permitidos as $campo) {
            if (isset($data[$campo])) {
                // Tratamento especial para tema
                if ($campo === 'preferencia_tema') {
                    $campos_atualizacao[] = "preferencia_tema = :preferencia_tema";
                    $valores[':preferencia_tema'] = $data[$campo];
                } else {
                    $campos_atualizacao[] = "{$campo} = :{$campo}";
                    $valores[":{$campo}"] = $data[$campo];
                }
            }
        }
        
        if (empty($campos_atualizacao)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Nenhum campo para atualizar']);
            exit();
        }
        
        $sql = "UPDATE usuarios SET " . implode(', ', $campos_atualizacao) . " WHERE id = :user_id";
        
        $stmt = $conn->prepare($sql);
        $stmt->execute($valores);
        
        // Buscar dados atualizados
        $stmt = $conn->prepare("
            SELECT id, nome, email, plano, peso, altura, preferencia_tema, status 
            FROM usuarios 
            WHERE id = :user_id
        ");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        $usuario_atualizado = $stmt->fetch(PDO::FETCH_ASSOC);
        
        http_response_code(200);
        echo json_encode([
            'success' => true,
            'message' => 'Perfil atualizado com sucesso',
            'data' => $usuario_atualizado
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
