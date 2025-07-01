<?php
session_start();
require_once '../Conexao.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['tipoUsuario'] != 1) {
    header("Location: ../usuario/login_view.php");
    exit;
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "ID do orçamento inválido.";
    exit;
}

$id_orcamento = (int) $_GET['id'];
$id_cliente = $_SESSION['id_usuario'] ?? null;

if (!$id_cliente) {
    echo "Usuário não identificado.";
    exit;
}

$conexao = new conexao();
$pdo = $conexao->getPdo();

// Verifica se o orçamento pertence ao cliente logado e é do tipo 'Orçamento'
$sqlCheck = "
    SELECT o.*
    FROM operacao o
    WHERE o.id_operacao = :id_orcamento
      AND o.fk_usuario_id_usuario = :id_cliente
      AND o.fk_tipo_operacao_id_tipo_operacao = (
          SELECT id_tipo_operacao FROM tipo_operacao WHERE descricao = 'Orçamento' LIMIT 1
      )
";
$stmtCheck = $pdo->prepare($sqlCheck);
$stmtCheck->execute([':id_orcamento' => $id_orcamento, ':id_cliente' => $id_cliente]);
$orcamento = $stmtCheck->fetch(PDO::FETCH_ASSOC);

if (!$orcamento) {
    echo "Orçamento não encontrado ou acesso negado.";
    exit;
}

// Busca apenas os campos necessários
$sqlProdutos = "
    SELECT p.descricao AS produto, op.quantidade, op.valor_total_produtos
    FROM operacao_produto op
    JOIN produtos p ON p.id_produto = op.id_produto
    WHERE op.id_operacao = :id_orcamento
";
$stmtProdutos = $pdo->prepare($sqlProdutos);
$stmtProdutos->execute([':id_orcamento' => $id_orcamento]);
$produtos = $stmtProdutos->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Detalhes do Orçamento - MVS Info</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
<style>
    body {
        background-color: #f5f7fa;
        font-family: Arial, sans-serif;
    }
    .navbar {
        background-color: #1976f2;
        color: white;
        padding: 12px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .navbar a {
        color: white;
        margin-left: 15px;
        text-decoration: none;
    }
    .container {
        max-width: 1000px;
        margin: 40px auto;
        background: white;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    h2 {
        color: #1976f2;
        margin-bottom: 20px;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }
    th, td {
        padding: 12px;
        border-bottom: 1px solid #ddd;
        text-align: left;
    }
    th {
        background-color: #f0f0f0;
    }
    .btn {
        padding: 8px 14px;
        border: none;
        border-radius: 5px;
        background-color: #1976f2;
        color: white;
        text-decoration: none;
        cursor: pointer;
    }
    .btn:hover {
        background-color: #155dc1;
    }
</style>
</head>
<body>

<div class="navbar">
    <div><strong>MVS Info - Área do Cliente</strong></div>
    <div>
        <a href="area-cliente.php">Perfil</a>
        <a href="../logout.php">Sair</a>
    </div>
</div>

<div class="container">
    <h2>Detalhes do Orçamento #<?= htmlspecialchars($orcamento['id_operacao']) ?></h2>
    <p><strong>Data:</strong> <?= date('d/m/Y', strtotime($orcamento['data_operacao'])) ?></p>
    <p><strong>Prazo de Entrega:</strong> <?= htmlspecialchars($orcamento['prazo_entrega'] ?? '-') ?></p>
    <p><strong>Status do Pagamento:</strong> <?= htmlspecialchars($orcamento['status_pagamento']) ?></p>
    <p><strong>Valor Total:</strong> R$ <?= number_format($orcamento['valor_total_compra'], 2, ',', '.') ?></p>

    <h4 class="mt-4">Produtos</h4>
    <?php if (count($produtos) > 0): ?>
    <table>
        <thead>
            <tr>
                <th>Produto</th>
                <th>Quantidade</th>
                <th>Valor Total (R$)</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($produtos as $p): ?>
            <tr>
                <td><?= htmlspecialchars($p['produto']) ?></td>
                <td><?= (int)$p['quantidade'] ?></td>
                <td><?= number_format($p['valor_total_produtos'], 2, ',', '.') ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php else: ?>
    <p>Nenhum produto encontrado neste orçamento.</p>
    <?php endif; ?>

    <a href="orcamento.php" class="btn">Voltar aos Orçamentos</a>
</div>

</body>
</html>
