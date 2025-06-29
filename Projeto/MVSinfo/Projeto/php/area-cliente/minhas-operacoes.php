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

if (!$idUsuario) {
    echo "Usuário não identificado.";
    exit;
}

$sql = "SELECT o.id_operacao, tp.descricao AS tipo_operacao, t.descricao AS transportadora, 
               o.data_operacao, o.status_pagamento
        FROM operacao o
        JOIN tipo_operacao tp ON tp.id_tipo_operacao = o.fk_tipo_operacao_id_tipo_operacao
        JOIN transportadora t ON t.id_transportadora = o.fk_transportadora_id_transportadora
        WHERE o.fk_usuario_id_usuario = ?
        ORDER BY o.data_operacao DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute([$idUsuario]);
$operacoes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8" />
    <title>Minhas Operações - MVS Info</title>
    <link rel="stylesheet" href="/Projeto/MVSinfo/Projeto/css/styles.css" />
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
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #1976f2;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table thead {
            background-color: #1976f2;
            color: white;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        tr:hover {
            background-color: #f0f0f0;
        }

        .btn {
            padding: 10px 20px;
            background-color: #1976f2;
            color: white;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            cursor: pointer;
            margin-top: 20px;
            display: inline-block;
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
            <a href="orcamento.php">Orçamento</a>
            <a href="minhas-operacoes.php">Minhas operações</a>
            <a href="pedido.php">Pedidos</a>
            <a href="../logout.php">Sair</a>
        </div>
    </div>

    <div class="container">
        <h2>Histórico de Operações</h2>

        <?php if (count($operacoes) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tipo</th>
                        <th>Transportadora</th>
                        <th>Data</th>
                        <th>Status Pagamento</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($operacoes as $op): ?>
                        <tr>
                            <td><?= htmlspecialchars($op['id_operacao']) ?></td>
                            <td><?= htmlspecialchars($op['tipo_operacao']) ?></td>
                            <td><?= htmlspecialchars($op['transportadora']) ?></td>
                            <td><?= date('d/m/Y', strtotime($op['data_operacao'])) ?></td>
                            <td><?= htmlspecialchars(ucfirst($op['status_pagamento'])) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Você ainda não fez nenhuma operação.</p>
        <?php endif; ?>

        <a href="area-cliente.php" class="btn">Voltar ao Perfil</a>
    </div>
</body>
</html>
