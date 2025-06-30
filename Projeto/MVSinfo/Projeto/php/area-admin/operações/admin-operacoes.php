<?php
session_start();
require_once '../../Conexao.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['tipoUsuario'] != 3) {
    header('Location: ../usuario/login_view.php');
    exit;
}

$conexao = new conexao();
$pdo = $conexao->getPdo();

$tipo = strtolower($_GET['tipo'] ?? '');

$tiposValidos = ['compra', 'venda', 'cotacao'];
if (!in_array($tipo, $tiposValidos)) {
    echo "Tipo de operação inválido.";
    exit;
}

// Buscar o ID do tipo_operacao correspondente
$sqlTipo = "SELECT id_tipo_operacao FROM tipo_operacao WHERE LOWER(descricao) = :tipo";
$stmtTipo = $pdo->prepare($sqlTipo);
$stmtTipo->execute([':tipo' => $tipo]);
$tipoId = $stmtTipo->fetchColumn();

if (!$tipoId) {
    echo "Tipo de operação não encontrado no banco de dados.";
    exit;
}

// Buscar operações com base no tipo
$sqlOperacoes = "
    SELECT o.id_operacao, o.data_operacao, o.prazo_entrega, o.status_pagamento, o.valor_total_compra,
           u.razao_social AS cliente_fornecedor, t.descricao AS transportadora
    FROM operacao o
    JOIN usuario u ON o.fk_usuario_id_usuario = u.id_usuario
    LEFT JOIN transportadora t ON o.fk_transportadora_id_transportadora = t.id_transportadora
    WHERE o.fk_tipo_operacao_id_tipo_operacao = :tipoId
    ORDER BY o.data_operacao DESC
";
$stmt = $pdo->prepare($sqlOperacoes);
$stmt->execute([':tipoId' => $tipoId]);
$operacoes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Admin - <?= ucfirst($tipo) ?>s</title>
    <link rel="stylesheet" href="../css/styles.css">
    <style>
        body { font-family: Arial, sans-serif; background: #f5f7fa; margin: 0; }
        .navbar { background: #1976f2; color: white; padding: 12px 20px; display: flex; justify-content: space-between; align-items: center; }
        .navbar a { color: white; text-decoration: none; margin-left: 15px; }
        .container { max-width: 1000px; margin: 40px auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        h2 { color: #1976f2; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 12px; border-bottom: 1px solid #ddd; text-align: left; }
        th { background-color: #f0f0f0; }
        .status { font-weight: bold; text-transform: capitalize; }
        .status.Pago { color: green; }
        .status.Pendente { color: orange; }
        .status.Cancelado { color: red; }
        .btn-voltar { margin-top: 20px; padding: 10px 20px; background-color: #1976f2; color: white; text-decoration: none; border-radius: 5px; display: inline-block; }
    </style>
</head>
<body>
    <div class="navbar">
        <strong>Admin - <?= ucfirst($tipo) ?>s</strong>
        <div>
            <a href="../area-admin.php">Menu</a>
            <a href="../../logout.php">Sair</a>
        </div>
    </div>

    <div class="container">
        <h2>Lista de <?= ucfirst($tipo) ?>s</h2>

        <?php if (count($operacoes) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Cliente / Fornecedor</th>
                        <th>Data</th>
                        <th>Prazo</th>
                        <th>Status</th>
                        <th>Transportadora</th>
                        <th>Valor Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($operacoes as $op): ?>
                        <tr>
                            <td><?= htmlspecialchars($op['cliente_fornecedor']) ?></td>
                            <td><?= date('d/m/Y', strtotime($op['data_operacao'])) ?></td>
                            <td><?= htmlspecialchars($op['prazo_entrega']) ?></td>
                            <td class="status <?= htmlspecialchars($op['status_pagamento']) ?>"><?= htmlspecialchars($op['status_pagamento']) ?></td>
                            <td><?= htmlspecialchars($op['transportadora'] ?? '-') ?></td>
                            <td>R$ <?= number_format($op['valor_total_compra'], 2, ',', '.') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Nenhuma operação encontrada para esse tipo.</p>
        <?php endif; ?>

        <a href="../area-admin.php" class="btn-voltar">Voltar ao Menu</a>
    </div>
</body>
</html>
