<?php
session_start();
require_once '../Conexao.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['tipoUsuario'] != 1) {
    header('Location: ../usuario/login_view.php');
    exit;
}

$conexao = new conexao();
$pdo = $conexao->getPdo();

$idProposta = $_GET['id'] ?? null;
$idUsuario = $_SESSION['id_usuario'] ?? null;

if (!$idProposta || !$idUsuario) {
    echo "Erro: dados incompletos.";
    exit;
}

// Recupera a proposta
$sqlProposta = "SELECT * FROM operacao WHERE id_operacao = ? AND fk_usuario_id_usuario = ? AND fk_tipo_operacao_id_tipo_operacao = 3";
$stmt = $pdo->prepare($sqlProposta);
$stmt->execute([$idProposta, $idUsuario]);
$proposta = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$proposta) {
    echo "Proposta não encontrada ou não autorizada.";
    exit;
}

// Cria nova operação do tipo Pedido (id_tipo_operacao = 1)
$sqlInsert = "INSERT INTO operacao (fk_usuario_id_usuario, fk_transportadora_id_transportadora, fk_tipo_operacao_id_tipo_operacao, data_operacao, prazo_entrega, status_pagamento, valor_total_compra)
              VALUES (?, ?, 1, NOW(), ?, 'Pendente', ?)";
$stmtInsert = $pdo->prepare($sqlInsert);
$stmtInsert->execute([
    $proposta['fk_usuario_id_usuario'],
    $proposta['fk_transportadora_id_transportadora'],
    $proposta['prazo_entrega'],
    $proposta['valor_total_compra']
]);

$novoPedidoId = $pdo->lastInsertId();

// Copia os produtos da proposta para o novo pedido
$sqlItens = "SELECT id_produto, quantidade FROM operacao_produto WHERE id_operacao = ?";
$stmtItens = $pdo->prepare($sqlItens);
$stmtItens->execute([$idProposta]);
$produtos = $stmtItens->fetchAll(PDO::FETCH_ASSOC);

foreach ($produtos as $produto) {
    $stmtInsertProduto = $pdo->prepare("INSERT INTO operacao_produto (id_operacao, id_produto, quantidade) VALUES (?, ?, ?)");
    $stmtInsertProduto->execute([$novoPedidoId, $produto['id_produto'], $produto['quantidade']]);
}

header("Location: minhas-operacoes.php?aprovado=1");
exit;
