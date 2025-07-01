<?php
require_once '../../Conexao.php';
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['tipoUsuario'] != 3) {
    header('Location: ../usuario/login_view.php');
    exit;
}

$conexao = new Conexao();
$pdo = $conexao->getPdo();

$sql = "
    SELECT o.id_operacao, o.data_operacao, o.prazo_entrega, o.status_pagamento, o.valor_total_compra,
           u.razao_social AS cliente_fornecedor, u.tipo_usuario, t.descricao AS transportadora
    FROM operacao o
    JOIN usuario u ON o.fk_usuario_id_usuario = u.id_usuario
    LEFT JOIN transportadora t ON o.fk_transportadora_id_transportadora = t.id_transportadora
    WHERE (o.status_pagamento IS NULL OR o.status_pagamento = '')
      AND o.fk_tipo_operacao_id_tipo_operacao = 3
    ORDER BY o.data_operacao DESC
";

$stmt = $pdo->prepare($sql);
$stmt->execute();
$operacoes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Admin - Operações com Status Vazio</title>
    <link rel="stylesheet" href="../css/styles.css">
    <style>
        body { font-family: Arial, sans-serif; background: #f5f7fa; margin: 0; }
        .navbar {
            background: #1976f2;
            color: white;
            padding: 12px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .navbar a {
            color: white;
            text-decoration: none;
            margin-left: 15px;
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
        }
        th, td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #f0f0f0;
        }
        .btn-voltar {
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #1976f2;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
        }
        .btn-editar {
            background-color: #4CAF50;
            color: white;
            padding: 6px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            font-size: 14px;
        }
        .btn-editar:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

<div class="navbar">
    <strong>Admin - Operações com Status Vazio (Tipo 3)</strong>
    <div>
        <a href="../area-admin.php">Menu</a>
        <a href="../../logout.php">Sair</a>
    </div>
</div>

<div class="container">
    <h2>Operações com Status de Pagamento Vazio (Tipo 3)</h2>

    <?php if (count($operacoes) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Cliente / Fornecedor</th>
                    <th>Tipo Usuário</th>
                    <th>Data</th>
                    <th>Prazo</th>
                    <th>Status</th>
                    <th>Transportadora</th>
                    <th>Valor Total</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($operacoes as $op): ?>
                    <tr>
                        <td><?= $op['id_operacao'] ?></td>
                        <td><?= htmlspecialchars($op['cliente_fornecedor']) ?></td>
                        <td><?= htmlspecialchars($op['tipo_usuario']) ?></td>
                        <td><?= date('d/m/Y', strtotime($op['data_operacao'])) ?></td>
                        <td><?= htmlspecialchars($op['prazo_entrega']) ?></td>
                        <td><?= htmlspecialchars($op['status_pagamento'] ?? '---') ?></td>
                        <td><?= htmlspecialchars($op['transportadora'] ?? '-') ?></td>
                        <td>R$ <?= number_format($op['valor_total_compra'], 2, ',', '.') ?></td>
                        <td>
                            <a href="editar-operacao.php?id=<?= $op['id_operacao'] ?>" class="btn-editar">Editar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Nenhuma operação encontrada com status de pagamento vazio e tipo 3.</p>
    <?php endif; ?>

    <a href="../area-admin.php" class="btn-voltar">Voltar ao Menu</a>
</div>

</body>
</html>
