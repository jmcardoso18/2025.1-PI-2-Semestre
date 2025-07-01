<?php
session_start();
require_once '../Conexao.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['tipoUsuario'] != 1) {
    header('Location: ../usuario/login_view.php');
    exit;
}

$conexao = new conexao();
$pdo = $conexao->getPdo();

$idCliente = $_SESSION['id_usuario'] ?? null;

if (!$idCliente) {
    echo "Usuário não identificado.";
    exit;
}

$produtos = $_POST['produtos'] ?? [];

if (empty($produtos)) {
    echo "Nenhum produto foi enviado.";
    exit;
}

try {
    $pdo->beginTransaction();

    // Buscar tipo de operação "Orçamento"
    $stmtTipo = $pdo->prepare("SELECT id_tipo_operacao FROM tipo_operacao WHERE descricao = 'Orçamento' LIMIT 1");
    $stmtTipo->execute();
    $tipoOperacao = $stmtTipo->fetchColumn();

    if (!$tipoOperacao) {
        throw new Exception("Tipo de operação 'Orçamento' não encontrado.");
    }

    // Criar operação com status inicial
    $stmtOperacao = $pdo->prepare("
        INSERT INTO operacao (fk_tipo_operacao_id_tipo_operacao, fk_cliente_id_cliente, data_operacao, valor_total_compra, status_pagamento)
        VALUES (:tipoOperacao, :cliente, NOW(), 0, 'Pendente')
    ");
    $stmtOperacao->execute([
        ':tipoOperacao' => $tipoOperacao,
        ':cliente' => $idCliente
    ]);

    $idOperacao = $pdo->lastInsertId();
    $valorTotalOperacao = 0;

    foreach ($produtos as $item) {
        $produtoId = $item['produto_id'] ?? null;
        $quantidade = intval($item['quantidade'] ?? 0);

        if (!$produtoId || $quantidade <= 0) {
            throw new Exception("Produto inválido ou quantidade menor que 1.");
        }

        $valorUnitario = 0;
        $valorTotal = $valorUnitario * $quantidade;

        // Evita duplicação
        $stmtCheck = $pdo->prepare("SELECT quantidade FROM operacao_produto WHERE id_operacao = :idOperacao AND id_produto = :produtoId");
        $stmtCheck->execute([
            ':idOperacao' => $idOperacao,
            ':produtoId' => $produtoId
        ]);

        $produtoExistente = $stmtCheck->fetchColumn();

        if ($produtoExistente !== false) {
            $novaQuantidade = $produtoExistente + $quantidade;
            $stmtUpdate = $pdo->prepare("
                UPDATE operacao_produto SET quantidade = :novaQtd WHERE id_operacao = :idOperacao AND id_produto = :produtoId
            ");
            $stmtUpdate->execute([
                ':novaQtd' => $novaQuantidade,
                ':idOperacao' => $idOperacao,
                ':produtoId' => $produtoId
            ]);
        } else {
            $stmtItem = $pdo->prepare("
                INSERT INTO operacao_produto (id_operacao, id_produto, quantidade, valor_unitario, valor_total_produtos)
                VALUES (:idOperacao, :produtoId, :quantidade, :valorUnitario, :valorTotal)
            ");
            $stmtItem->execute([
                ':idOperacao' => $idOperacao,
                ':produtoId' => $produtoId,
                ':quantidade' => $quantidade,
                ':valorUnitario' => $valorUnitario,
                ':valorTotal' => $valorTotal
            ]);
        }

        $valorTotalOperacao += $valorTotal;
    }

    // Atualiza o total da operação
    $stmtUpdateTotal = $pdo->prepare("UPDATE operacao SET valor_total_compra = :valorTotal WHERE id_operacao = :idOperacao");
    $stmtUpdateTotal->execute([
        ':valorTotal' => $valorTotalOperacao,
        ':idOperacao' => $idOperacao
    ]);

    $pdo->commit();

    header('Location: orcamento.php?success=1');
    exit;

} catch (Exception $e) {
    $pdo->rollBack();
    echo "Erro ao processar orçamento: " . $e->getMessage();
    exit;
}
