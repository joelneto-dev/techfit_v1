<?php
/**
 * Configuração do Banco de Dados - TechFit
 * Com verificação e criação automática do banco e tabelas
 */

$host = 'localhost';
$db_name = 'techfit_db';
$username = 'root';
$password = '1234'; // Altere para a senha do seu MySQL

try {
    // ETAPA 1: Conectar ao MySQL (sem especificar banco)
    $connSetup = new PDO("mysql:host=$host", $username, $password);
    $connSetup->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // ETAPA 2: Verificar se o banco existe
    $stmt = $connSetup->query("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '$db_name'");
    $dbExists = $stmt->rowCount() > 0;
    
    if (!$dbExists) {
        // Criar o banco de dados
        $connSetup->exec("CREATE DATABASE `$db_name` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        error_log("✅ Banco de dados '$db_name' criado automaticamente!");
    }
    
    // ETAPA 3: Conectar ao banco específico
    $conn = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    
    // ETAPA 4: Verificar se as tabelas existem
    $stmt = $conn->query("SHOW TABLES LIKE 'usuarios'");
    $tablesExist = $stmt->rowCount() > 0;
    
    if (!$tablesExist) {
        // Criar as tabelas
        $conn->exec("
            CREATE TABLE IF NOT EXISTS usuarios (
                id INT AUTO_INCREMENT PRIMARY KEY,
                nome VARCHAR(100) NOT NULL,
                email VARCHAR(100) NOT NULL UNIQUE,
                senha VARCHAR(255) NOT NULL,
                plano VARCHAR(50) DEFAULT 'Basic',
                peso DECIMAL(5,2) DEFAULT NULL,
                altura DECIMAL(5,2) DEFAULT NULL,
                preferencia_tema VARCHAR(20) DEFAULT 'light',
                status VARCHAR(20) DEFAULT 'pendente',
                codigo_ativacao VARCHAR(20) DEFAULT NULL,
                data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                data_ativacao TIMESTAMP NULL DEFAULT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ");
        
        $conn->exec("
            CREATE TABLE IF NOT EXISTS correio_eletronico (
                id INT AUTO_INCREMENT PRIMARY KEY,
                destinatario_email VARCHAR(100) NOT NULL,
                assunto VARCHAR(200) NOT NULL,
                corpo TEXT NOT NULL,
                lida BOOLEAN DEFAULT FALSE,
                data_envio TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                INDEX idx_destinatario (destinatario_email)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ");
        
        $conn->exec("
            CREATE TABLE IF NOT EXISTS alunos (
                id INT AUTO_INCREMENT PRIMARY KEY,
                nome VARCHAR(100) NOT NULL,
                email VARCHAR(100) NOT NULL,
                plano VARCHAR(50)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ");
        
        // Inserir dados de exemplo
        $conn->exec("
            INSERT INTO alunos (nome, email, plano) VALUES 
            ('Ana Clara', 'ana@email.com', 'Gold'),
            ('Bruno Silva', 'bruno@email.com', 'Basic');
        ");
        
        error_log("✅ Tabelas criadas automaticamente no banco '$db_name'!");
    }
    
} catch(PDOException $e) {
    // Log do erro
    error_log("❌ Erro de Conexão com o Banco de Dados: " . $e->getMessage());
    
    // Mensagem amigável em desenvolvimento
    if (php_sapi_name() !== 'cli') {
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode([
            'success' => false,
            'message' => 'Erro ao conectar com o banco de dados',
            'error' => $e->getMessage(),
            'hint' => 'Verifique se o MySQL está rodando e as credenciais em backend/config/database.php'
        ]);
        exit();
    }
}
?>