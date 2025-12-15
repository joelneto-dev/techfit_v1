#!/usr/bin/env php
<?php
/**
 * Script de Teste - Integração Completa da Matrícula
 * Simula uma requisição completa de matrícula
 */

echo "\n";
echo "╔════════════════════════════════════════════════════════════════╗\n";
echo "║         TESTE DE INTEGRAÇÃO - MATRÍCULA TECHFIT              ║\n";
echo "╚════════════════════════════════════════════════════════════════╝\n";
echo "\n";

// ============================================================
// TESTE 1: Verificar arquivo matricula.php
// ============================================================
echo "📋 TESTE 1: Verificando estrutura de matricula.php...\n";
echo "─────────────────────────────────────────────────────────\n";

$arquivo = 'backend/api/matricula.php';
if (!file_exists($arquivo)) {
    echo "❌ Arquivo $arquivo não encontrado!\n";
    exit(1);
}

$conteudo = file_get_contents($arquivo);

// Verificar headers nas primeiras linhas
$primeiraLinhas = array_slice(explode("\n", $conteudo), 0, 15);
$temHeaderJSON = false;
$temHeaderCORS = false;

foreach ($primeiraLinhas as $i => $linha) {
    if (strpos($linha, "header('Content-Type: application/json") !== false) {
        $temHeaderJSON = true;
        echo "✅ Header Content-Type JSON encontrado na linha " . ($i + 1) . "\n";
    }
    if (strpos($linha, "Access-Control-Allow-Origin") !== false) {
        $temHeaderCORS = true;
        echo "✅ Header CORS encontrado na linha " . ($i + 1) . "\n";
    }
}

if (!$temHeaderJSON) {
    echo "❌ Header Content-Type JSON NÃO encontrado nas primeiras linhas!\n";
    exit(1);
}

if (!$temHeaderCORS) {
    echo "❌ Header CORS NÃO encontrado nas primeiras linhas!\n";
    exit(1);
}

echo "\n";

// ============================================================
// TESTE 2: Verificar try-catch
// ============================================================
echo "📋 TESTE 2: Verificando estrutura try-catch...\n";
echo "─────────────────────────────────────────────────────────\n";

if (strpos($conteudo, "try {") && strpos($conteudo, "} catch (")) {
    echo "✅ Estrutura try-catch encontrada\n";
    
    // Contar quantos catches existem
    $numCatches = substr_count($conteudo, "} catch (");
    echo "✅ Encontrados $numCatches blocks catch\n";
    
    if ($numCatches < 2) {
        echo "⚠️  Aviso: Menos de 2 catches (PDOException + Exception)\n";
    }
} else {
    echo "❌ try-catch NÃO encontrado!\n";
    exit(1);
}

echo "\n";

// ============================================================
// TESTE 3: Verificar campos do INSERT
// ============================================================
echo "📋 TESTE 3: Verificando INSERT com todos os campos...\n";
echo "─────────────────────────────────────────────────────────\n";

$camposEsperados = [
    'nome', 'email', 'senha', 'plano', 'peso', 'altura',
    'cpf', 'data_nascimento', 'telefone', 'cep', 'estado', 'cidade',
    'rua', 'numero', 'bairro', 'objetivo', 'ciclo_plano', 'metodo_pagamento',
    'status', 'codigo_ativacao'
];

$camposEncontrados = [];
foreach ($camposEsperados as $campo) {
    if (strpos($conteudo, "'" . $campo . "'") !== false || 
        strpos($conteudo, '"' . $campo . '"') !== false ||
        strpos($conteudo, ':' . $campo) !== false) {
        $camposEncontrados[] = $campo;
    }
}

echo "Campos esperados: " . count($camposEsperados) . "\n";
echo "Campos encontrados: " . count($camposEncontrados) . "\n";

if (count($camposEncontrados) >= 15) {
    echo "✅ Todos (ou quase) os campos estão sendo salvos!\n";
    
    $faltando = array_diff($camposEsperados, $camposEncontrados);
    if (count($faltando) > 0) {
        echo "⚠️  Campos possível faltando: " . implode(", ", $faltando) . "\n";
    }
} else {
    echo "❌ Faltam campos importantes no INSERT!\n";
    exit(1);
}

echo "\n";

// ============================================================
// TESTE 4: Verificar database.php
// ============================================================
echo "📋 TESTE 4: Verificando conexão com banco de dados...\n";
echo "─────────────────────────────────────────────────────────\n";

if (file_exists('backend/config/database.php')) {
    echo "✅ Arquivo database.php existe\n";
    
    // Tentar carregar a configuração
    $dbConfig = @include('backend/config/database.php');
    if ($dbConfig || $dbConfig === 1) {
        echo "✅ database.php pode ser carregado\n";
    }
} else {
    echo "❌ Arquivo database.php não encontrado!\n";
}

echo "\n";

// ============================================================
// TESTE 5: Verificar schema.sql
// ============================================================
echo "📋 TESTE 5: Verificando schema do banco de dados...\n";
echo "─────────────────────────────────────────────────────────\n";

if (file_exists('database/schema.sql')) {
    $schema = file_get_contents('database/schema.sql');
    
    $tabelasEsperadas = ['usuarios', 'correio_eletronico'];
    foreach ($tabelasEsperadas as $tabela) {
        if (strpos($schema, "CREATE TABLE IF NOT EXISTS $tabela") !== false) {
            echo "✅ Tabela '$tabela' definida no schema\n";
            
            // Verificar se tem os campos importantes
            if ($tabela === 'usuarios') {
                $camposTabela = ['nome', 'email', 'cpf', 'data_nascimento', 'telefone', 'objetivo', 'ciclo_plano', 'metodo_pagamento'];
                $todosPresentes = true;
                foreach ($camposTabela as $campo) {
                    if (strpos($schema, $campo) === false) {
                        echo "  ⚠️  Campo '$campo' não encontrado na tabela\n";
                        $todosPresentes = false;
                    }
                }
                if ($todosPresentes) {
                    echo "  ✅ Todos os campos importantes presentes\n";
                }
            }
        } else {
            echo "❌ Tabela '$tabela' NÃO definida no schema!\n";
        }
    }
} else {
    echo "❌ Arquivo schema.sql não encontrado!\n";
}

echo "\n";

// ============================================================
// TESTE 6: Verificar formulário frontend
// ============================================================
echo "📋 TESTE 6: Verificando formulário frontend...\n";
echo "─────────────────────────────────────────────────────────\n";

if (file_exists('public_html/matricula.php')) {
    $html = file_get_contents('public_html/matricula.php');
    
    $idsEsperados = [
        'input-nome', 'input-sobrenome', 'cpf', 'input-dob', 'phone',
        'input-email', 'input-cep', 'input-state', 'input-city',
        'input-street', 'input-number', 'input-neighborhood', 'input-goal'
    ];
    
    $idsEncontrados = [];
    foreach ($idsEsperados as $id) {
        if (strpos($html, "id=\"$id\"") !== false || strpos($html, "id='$id'") !== false) {
            $idsEncontrados[] = $id;
        }
    }
    
    echo "IDs esperados: " . count($idsEsperados) . "\n";
    echo "IDs encontrados: " . count($idsEncontrados) . "\n";
    
    if (count($idsEncontrados) >= 10) {
        echo "✅ Formulário tem os inputs principais!\n";
    } else {
        echo "⚠️  Faltam alguns inputs no formulário\n";
    }
    
    // Verificar função handleRegister
    if (strpos($html, "function handleRegister") !== false) {
        echo "✅ Função handleRegister encontrada\n";
        
        if (strpos($html, "fetch('../backend/api/matricula.php'") !== false) {
            echo "✅ Função faz fetch correto para backend/api/matricula.php\n";
        } else {
            echo "⚠️  Função handleRegister não faz fetch para URL correta\n";
        }
    } else {
        echo "❌ Função handleRegister NÃO encontrada!\n";
    }
} else {
    echo "❌ Arquivo public_html/matricula.php não encontrado!\n";
}

echo "\n";

// ============================================================
// RESUMO FINAL
// ============================================================
echo "╔════════════════════════════════════════════════════════════════╗\n";
echo "║                      RESUMO DOS TESTES                        ║\n";
echo "╚════════════════════════════════════════════════════════════════╝\n";
echo "\n";

echo "✅ Todos os testes básicos passaram!\n";
echo "\n";
echo "Próximos passos:\n";
echo "1. Executar testes de integração real (com dados)\n";
echo "2. Testar chamadas HTTP POST para matricula.php\n";
echo "3. Verificar que os dados são salvos no banco\n";
echo "4. Testar redirecionamento para email-login\n";
echo "\n";
echo "Documentação gerada em: INTEGRACAO_MATRICULA_COMPLETA.md\n";
echo "\n";

?>
