<?php
/**
 * Script de Teste - Verificar Conexão e Estrutura do Banco
 * Acesse: http://localhost/techfit-sistema/backend/test-database.php
 */

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teste de Banco de Dados - TechFit</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .container {
            background: white;
            border-radius: 20px;
            padding: 40px;
            max-width: 800px;
            width: 100%;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        h1 { 
            color: #0500ff;
            margin-bottom: 10px;
            font-size: 32px;
        }
        .subtitle {
            color: #666;
            margin-bottom: 30px;
            font-size: 16px;
        }
        .test-item {
            background: #f8f9fa;
            border-left: 4px solid #ddd;
            padding: 15px 20px;
            margin-bottom: 15px;
            border-radius: 8px;
            transition: all 0.3s;
        }
        .test-item.success {
            background: #d4edda;
            border-color: #28a745;
        }
        .test-item.error {
            background: #f8d7da;
            border-color: #dc3545;
        }
        .test-item.warning {
            background: #fff3cd;
            border-color: #ffc107;
        }
        .test-title {
            font-weight: bold;
            margin-bottom: 5px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .test-message {
            color: #666;
            font-size: 14px;
        }
        .icon {
            display: inline-block;
            width: 20px;
            height: 20px;
            line-height: 20px;
            text-align: center;
            border-radius: 50%;
            font-size: 12px;
        }
        .icon.success { background: #28a745; color: white; }
        .icon.error { background: #dc3545; color: white; }
        .icon.warning { background: #ffc107; color: white; }
        .code {
            background: #2d2d2d;
            color: #f8f8f2;
            padding: 15px;
            border-radius: 8px;
            font-family: 'Courier New', monospace;
            font-size: 13px;
            margin-top: 10px;
            overflow-x: auto;
        }
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
            margin-top: 30px;
        }
        .stat-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 12px;
            text-align: center;
        }
        .stat-number {
            font-size: 32px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .stat-label {
            font-size: 12px;
            opacity: 0.9;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            text-align: center;
            color: #666;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🚀 Teste de Banco de Dados</h1>
        <p class="subtitle">Verificando conexão e estrutura do sistema TechFit</p>

        <?php
        $tests = [];
        $totalTests = 0;
        $passedTests = 0;

        // Teste 1: Incluir arquivo de configuração
        $totalTests++;
        try {
            require_once 'config/database.php';
            $tests[] = [
                'status' => 'success',
                'title' => 'Arquivo de Configuração',
                'message' => 'database.php carregado com sucesso'
            ];
            $passedTests++;
        } catch (Exception $e) {
            $tests[] = [
                'status' => 'error',
                'title' => 'Arquivo de Configuração',
                'message' => 'Erro ao carregar: ' . $e->getMessage()
            ];
        }

        // Teste 2: Verificar conexão
        $totalTests++;
        if (isset($conn) && $conn instanceof PDO) {
            $tests[] = [
                'status' => 'success',
                'title' => 'Conexão com MySQL',
                'message' => 'Conectado ao servidor MySQL com sucesso'
            ];
            $passedTests++;
        } else {
            $tests[] = [
                'status' => 'error',
                'title' => 'Conexão com MySQL',
                'message' => 'Falha ao conectar com o servidor MySQL'
            ];
        }

        // Teste 3: Verificar banco de dados
        $totalTests++;
        if (isset($conn)) {
            try {
                $stmt = $conn->query("SELECT DATABASE() as db_name");
                $result = $stmt->fetch();
                $tests[] = [
                    'status' => 'success',
                    'title' => 'Banco de Dados',
                    'message' => "Banco '{$result['db_name']}' selecionado corretamente"
                ];
                $passedTests++;
            } catch (PDOException $e) {
                $tests[] = [
                    'status' => 'error',
                    'title' => 'Banco de Dados',
                    'message' => 'Erro: ' . $e->getMessage()
                ];
            }
        }

        // Teste 4: Verificar tabelas
        $expectedTables = ['usuarios', 'correio_eletronico', 'alunos'];
        $totalTests++;
        if (isset($conn)) {
            try {
                $stmt = $conn->query("SHOW TABLES");
                $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
                
                $missingTables = array_diff($expectedTables, $tables);
                
                if (empty($missingTables)) {
                    $tests[] = [
                        'status' => 'success',
                        'title' => 'Estrutura de Tabelas',
                        'message' => 'Todas as ' . count($expectedTables) . ' tabelas necessárias foram criadas: ' . implode(', ', $tables)
                    ];
                    $passedTests++;
                } else {
                    $tests[] = [
                        'status' => 'warning',
                        'title' => 'Estrutura de Tabelas',
                        'message' => 'Tabelas faltando: ' . implode(', ', $missingTables)
                    ];
                }
            } catch (PDOException $e) {
                $tests[] = [
                    'status' => 'error',
                    'title' => 'Estrutura de Tabelas',
                    'message' => 'Erro ao verificar tabelas: ' . $e->getMessage()
                ];
            }
        }

        // Teste 5: Verificar estrutura da tabela usuarios
        $totalTests++;
        if (isset($conn)) {
            try {
                $stmt = $conn->query("DESCRIBE usuarios");
                $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
                $requiredColumns = ['id', 'nome', 'email', 'senha', 'status', 'codigo_ativacao', 'preferencia_tema'];
                $missingColumns = array_diff($requiredColumns, $columns);
                
                if (empty($missingColumns)) {
                    $tests[] = [
                        'status' => 'success',
                        'title' => 'Tabela Usuarios',
                        'message' => 'Estrutura completa com ' . count($columns) . ' colunas'
                    ];
                    $passedTests++;
                } else {
                    $tests[] = [
                        'status' => 'error',
                        'title' => 'Tabela Usuarios',
                        'message' => 'Colunas faltando: ' . implode(', ', $missingColumns)
                    ];
                }
            } catch (PDOException $e) {
                $tests[] = [
                    'status' => 'error',
                    'title' => 'Tabela Usuarios',
                    'message' => 'Erro: ' . $e->getMessage()
                ];
            }
        }

        // Teste 6: Contar registros
        if (isset($conn)) {
            try {
                $stmt = $conn->query("SELECT 
                    (SELECT COUNT(*) FROM usuarios) as usuarios,
                    (SELECT COUNT(*) FROM correio_eletronico) as emails,
                    (SELECT COUNT(*) FROM alunos) as alunos
                ");
                $counts = $stmt->fetch();
            } catch (PDOException $e) {
                $counts = ['usuarios' => 0, 'emails' => 0, 'alunos' => 0];
            }
        }

        // Exibir resultados dos testes
        foreach ($tests as $test) {
            $iconClass = $test['status'];
            $icon = $test['status'] === 'success' ? '✓' : ($test['status'] === 'error' ? '✗' : '!');
            
            echo "<div class='test-item {$test['status']}'>";
            echo "<div class='test-title'>";
            echo "<span class='icon {$iconClass}'>{$icon}</span>";
            echo $test['title'];
            echo "</div>";
            echo "<div class='test-message'>{$test['message']}</div>";
            echo "</div>";
        }
        ?>

        <?php if (isset($counts)): ?>
        <div class="stats">
            <div class="stat-card">
                <div class="stat-number"><?= $counts['usuarios'] ?></div>
                <div class="stat-label">Usuários</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?= $counts['emails'] ?></div>
                <div class="stat-label">E-mails</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?= $counts['alunos'] ?></div>
                <div class="stat-label">Alunos</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?= $passedTests ?>/<?= $totalTests ?></div>
                <div class="stat-label">Testes OK</div>
            </div>
        </div>
        <?php endif; ?>

        <div class="footer">
            <strong>Status Geral:</strong> 
            <?php if ($passedTests === $totalTests): ?>
                ✅ Tudo funcionando perfeitamente!
            <?php elseif ($passedTests > 0): ?>
                ⚠️ Alguns problemas encontrados
            <?php else: ?>
                ❌ Sistema precisa de configuração
            <?php endif; ?>
            <br><br>
            <small>TechFit Sistema © 2025 | Backend em PHP com PDO</small>
        </div>
    </div>
</body>
</html>
