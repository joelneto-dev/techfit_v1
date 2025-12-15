<?php
/**
 * Teste de Validação da API de Matrícula
 * Verifica se os headers e try-catch estão funcionando corretamente
 */

// Capturar output para debug
ob_start();

// Teste 1: Verificar se os headers são JSON
echo "=== TESTE 1: Verificar Headers ===\n";
$headers = @get_headers('../backend/api/matricula.php', 1);
if (is_array($headers) && isset($headers['Content-Type'])) {
    echo "✓ Header Content-Type: " . $headers['Content-Type'] . "\n";
    if (strpos($headers['Content-Type'], 'application/json') !== false) {
        echo "✓ Content-Type é JSON!\n";
    } else {
        echo "✗ Content-Type NÃO é JSON!\n";
    }
} else {
    echo "✗ Não foi possível verificar headers\n";
}

echo "\n=== TESTE 2: Verificar estrutura do arquivo ===\n";
$conteudo = file_get_contents('../backend/api/matricula.php');
$linhas = array_slice(explode("\n", $conteudo), 0, 10);

foreach ($linhas as $i => $linha) {
    if (strpos($linha, 'header') !== false) {
        echo "Linha " . ($i + 1) . ": " . trim($linha) . "\n";
    }
}

echo "\n=== TESTE 3: Testar requisição OPTIONS ===\n";
$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL => 'http://localhost/backend/api/matricula.php',
    CURLOPT_CUSTOMREQUEST => 'OPTIONS',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HEADER => true,
]);

$response = curl_exec($ch);
$info = curl_getinfo($ch);
curl_close($ch);

echo "HTTP Status: " . $info['http_code'] . "\n";
if ($info['http_code'] === 200) {
    echo "✓ OPTIONS retorna 200 (CORS está configurado)\n";
} else {
    echo "✗ OPTIONS retorna " . $info['http_code'] . "\n";
}

echo "\n=== TESTE 4: Testar requisição POST vazia ===\n";
$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL => 'http://localhost/backend/api/matricula.php',
    CURLOPT_POST => true,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
    CURLOPT_POSTFIELDS => json_encode([])
]);

$response = curl_exec($ch);
$info = curl_getinfo($ch);
curl_close($ch);

echo "HTTP Status: " . $info['http_code'] . "\n";
echo "Content-Type: " . $info['content_type'] . "\n";

if (strpos($response, '<') === 0) {
    echo "✗ ERRO: Resposta começa com '<' (HTML ao invés de JSON)\n";
    echo "Resposta: " . substr($response, 0, 100) . "...\n";
} else {
    echo "✓ Resposta não começa com '<'\n";
    $json = json_decode($response, true);
    if (json_last_error() === JSON_ERROR_NONE) {
        echo "✓ Resposta é JSON válido!\n";
        echo "Resposta: " . json_encode($json) . "\n";
    } else {
        echo "✗ Resposta NÃO é JSON válido\n";
        echo "Erro JSON: " . json_last_error_msg() . "\n";
        echo "Resposta: " . substr($response, 0, 200) . "...\n";
    }
}

echo "\n=== TESTES CONCLUÍDOS ===\n";
?>
