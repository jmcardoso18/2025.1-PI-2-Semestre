<?php
session_start();
require_once '../Conexao.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['tipoUsuario'] != 1) {
    header("Location: ../usuario/login_view.php");
    exit;
}

$conexao = new Conexao();
$pdo = $conexao->getPdo();

$idUsuario = $_SESSION['id_usuario'] ?? null;
$produtos = $_POST['produtos'] ?? [];

if (!$idUsuario) {
    echo "Usuário não logado.";
    exit;
}

if (empty($produtos)) {
    echo "Nenhum produto selecionado.";
    exit;
}

try {
    $pdo->beginTransaction();

    // 1. Criar operação de orçamento (status_pagamento inicial como 'Pendente')
    $stmtOp = $pdo->prepare("
        INSERT INTO operacao (
            fk_usuario_id_usuario, 
            fk_tipo_operacao_id_tipo_operacao, 
            status_pagamento
        ) VALUES (
            :idUsuario, 
            (SELECT id_tipo_operacao FROM tipo_operacao WHERE descricao = 'Orçamento' LIMIT 1), 
            'Pendente'
        )
    ");
    $stmtOp->execute([':idUsuario' => $idUsuario]);
    $idOperacao = $pdo->lastInsertId();

    // 2. Inserir produtos relacionados à operação criada
    $stmtProduto = $pdo->prepare("
        INSERT INTO operacao_produto (
            id_operacao, 
            id_produto, 
            quantidade
        ) VALUES (
            :idOperacao, 
            :idProduto, 
            :quantidade
        )
    ");

    foreach ($produtos as $item) {
        $idProduto = intval($item['produto_id'] ?? 0);
        $quantidade = intval($item['quantidade'] ?? 0);
        if ($idProduto > 0 && $quantidade > 0) {
            $stmtProduto->execute([
                ':idOperacao' => $idOperacao,
                ':idProduto' => $idProduto,
                ':quantidade' => $quantidade
            ]);
        }
    }

    $pdo->commit();

    header("Location: area-cliente.php?msg=orcamento_criado");
    exit;

} catch (Exception $e) {
    $pdo->rollBack();
    echo "Erro ao criar orçamento: " . $e->getMessage();
    exit;
}
