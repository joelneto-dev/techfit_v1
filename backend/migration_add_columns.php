<?php
/**
 * Script de Migração - Adicionar Colunas na Tabela usuarios
 * Execute este arquivo uma vez para atualizar a estrutura do banco
 */

require_once 'config/database.php';

echo "Iniciando migração do banco de dados...\n";

try {
    // Verificar quais colunas existem
    $stmt = $conn->query("DESCRIBE usuarios");
    $existingColumns = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $existingColumns[] = $row['Field'];
    }
    
    // Definir colunas que precisam ser adicionadas
    $columnsToAdd = [
        'cpf' => "VARCHAR(14) DEFAULT NULL",
        'data_nascimento' => "DATE DEFAULT NULL",
        'telefone' => "VARCHAR(20) DEFAULT NULL",
        'cep' => "VARCHAR(10) DEFAULT NULL",
        'estado' => "VARCHAR(2) DEFAULT NULL",
        'cidade' => "VARCHAR(100) DEFAULT NULL",
        'rua' => "VARCHAR(200) DEFAULT NULL",
        'numero' => "VARCHAR(20) DEFAULT NULL",
        'bairro' => "VARCHAR(100) DEFAULT NULL",
        'objetivo' => "VARCHAR(50) DEFAULT NULL",
        'ciclo_plano' => "VARCHAR(20) DEFAULT 'monthly'",
        'metodo_pagamento' => "VARCHAR(20) DEFAULT NULL"
    ];
    
    $added = 0;
    $skipped = 0;
    
    foreach ($columnsToAdd as $columnName => $columnDefinition) {
        if (!in_array($columnName, $existingColumns)) {
            $sql = "ALTER TABLE usuarios ADD COLUMN $columnName $columnDefinition";
            $conn->exec($sql);
            echo "✅ Coluna '$columnName' adicionada com sucesso!\n";
            $added++;
        } else {
            echo "⏭️  Coluna '$columnName' já existe, pulando...\n";
            $skipped++;
        }
    }
    
    echo "\n========================================\n";
    echo "Migração concluída!\n";
    echo "Colunas adicionadas: $added\n";
    echo "Colunas já existentes: $skipped\n";
    echo "========================================\n";
    
} catch (PDOException $e) {
    echo "❌ ERRO: " . $e->getMessage() . "\n";
    exit(1);
}
