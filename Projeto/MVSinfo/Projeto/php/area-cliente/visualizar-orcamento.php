<?php
session_start();
require_once '../Conexao.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['tipoUsuario'] != 1) {
    header('Location: ../usuario/login_view.php');
    exit;
}

$conexao = new conexao();
$pdo = $conexao->getPdo();

$idUsuario = $_SESSION['id_usuario'] ?? null;
$idOrcamento = intval($_GET['id'] ?? 0);

if (!$idUsuario || $idOrcamento <= 0) {
    echo "Erro: Parâmetros inválidos.";
    exit;
}

// Buscar orçamento principal
$sqlOrcamento = "
    SELECT o.*, t.descricao AS tipo_operacao
    FROM operacao o
    JOIN tipo_operacao t ON o.fk_tipo_operacao_id_tipo_operacao = t.id_tipo_operacao
    WHERE o.id_operacao = :id AND o.fk_cliente_id_cliente = :cliente AND t.descricao = 'Orçamento'
";
$stmt = $pdo->prepare($sqlOrcamento);
$stmt->execute([':id' => $idOrcamento, ':cliente' => $idUsuario]);
$orcamento = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$orcamento) {
    echo "Orçamento não encontrado.";
    exit;
}

// Buscar produtos do orçamento
$sqlProdutos = "
    SELECT p.descricao, op.quantidade, op.valor_unitario, op.valor_total_produtos
    FROM operacao_produto op
    JOIN produtos p ON op.id_produto = p.id_produto
    WHERE op.id_operacao = :id
";
$stmtProdutos = $pdo->prepare($sqlProdutos);
$stmtProdutos->execute([':id' => $idOrcamento]);
$produtos = $stmtProdutos->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Detalhes do Orçamento</title>
    <link rel="stylesheet" href="/Projeto/MVSinfo/Projeto/css/styles.css">
    <style>
        body { background-color: #f5f7fa; font-family: Arial, sans-serif; }
        .navbar { background-color: #1976f2; color: white; padding: 12px 20px; display: flex; justify-content: space-between; align-items: center; }
        .navbar a { color: white; margin-left: 15px; text-decoration: none; }
        .container { max-width: 900px; margin: 40px auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        h2 { color: #1976f2; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; border-bottom: 1px solid #ddd; text-align: left; }
        th { background-color: #f0f0f0; }
        .btn { padding: 10px 18px; background-color: #1976f2; color: white; border: none; border-radius: 5px; text-decoration: none; cursor: pointer; }
        .btn:hover { background-color: #155dc1; }
        .info-box { margin-top: 15px; background-color: #eef2f7; padding: 15px; border-radius: 5px; }
        .info-box span { font-weight: bold; }
    </style>
</head>
<body>

<div class="navbar">
    <div><strong>MVS Info - Área do Cliente</strong></div>
    <div>
        <a href="area-cliente.php">Perfil</a>
        <a href="orcamento.php">Orçamentos</a>
        <a href="../logout.php">Sair</a>
    </div>
</div>

<div class="container">
    <h2>Detalhes do Orçamento #<?= htmlspecialchars($orcamento['id_operacao']) ?></h2>

    <div class="info-box">
        <p><span>Data:</span> <?= date('d/m/Y', strtotime($orcamento['data_operacao'])) ?></p>
        <p><span>Status:</span> <?= htmlspecialchars($orcamento['status_pagamento'] ?? 'Pendente') ?></p>
        <p><span>Prazo de Entrega:</span> <?= htmlspecialchars($orcamento['prazo_entrega'] ?? '-') ?></p>
        <p><span>Valor Total:</span> R$ <?= number_format($orcamento['valor_total_compra'], 2, ',', '.') ?></p>
    </div>

    <h3>Produtos Solicitados</h3>
    <table>
        <thead>
            <tr>
                <th>Descrição</th>
                <th>Quantidade</th>
                <th>Valor Unitário (R$)</th>
                <th>Subtotal (R$)</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($produtos as $prod): ?>
                <tr>
                    <td><?= htmlspecialchars($prod['descricao']) ?></td>
                    <td><?= htmlspecialchars($prod['quantidade']) ?></td>
                    <td><?= number_format($prod['valor_unitario'], 2, ',', '.') ?></td>
                    <td><?= number_format($prod['valor_total_produtos'], 2, ',', '.') ?></td>
                </tr>
            <?php endforeach; ?>
            <?php if (count($produtos) === 0): ?>
                <tr><td colspan="4">Nenhum produto vinculado a este orçamento.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <a href="orcamento.php" class="btn" style="margin-top: 20px;">Voltar aos Orçamentos</a>
</div>

</body>
</html>
