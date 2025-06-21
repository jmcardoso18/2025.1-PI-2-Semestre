<?php
session_start();
require_once '../Conexao.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['tipoUsuario'] != 1) { // 1 = cliente (ajuste conforme seu sistema)
    header('Location: ../usuario/login_view.php');
    exit;
}

$conexao = new conexao();
$pdo = $conexao->getPDO();

// ID do cliente logado
$id_cliente = $_SESSION['id_usuario'] ?? null;

if (!$id_cliente) {
    echo "Erro: Cliente não identificado.";
    exit;
}

// Query para pegar pedidos (vendas) do cliente
$sql = "
    SELECT v.id_venda, v.data_venda, v.status_pagamento, p.descricao AS produto, pv.quantidade
    FROM venda v
    JOIN produtovenda pv ON v.id_venda = pv.id_venda
    JOIN Produtos p ON pv.codigo_produto = p.codigo_produto
    WHERE v.id_cliente = :id_cliente
    ORDER BY v.data_venda DESC
";

$stmt = $pdo->prepare($sql);
$stmt->execute([':id_cliente' => $id_cliente]);
$pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Meus Pedidos - MVS Info</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f7fa;
            margin: 0;
        }

        header {
            background-color: #1976f2;
            color: white;
            padding: 12px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        header h1 {
            margin: 0;
        }

        nav a {
            color: white;
            margin-left: 15px;
            text-decoration: none;
            padding: 8px 14px;
            border-radius: 5px;
            background-color: #1976f2;
            transition: background-color 0.3s ease;
        }

        nav a:hover {
            background-color: #155dc1;
        }

        .container {
            max-width: 900px;
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

        .pedido {
            background-color: #fafafa;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px 20px;
            margin-bottom: 15px;
        }

        .pedido h3 {
            margin-top: 0;
            color: #1976f2;
        }

        .status {
            font-weight: bold;
            color: #1976f2;
        }

        .btn {
            padding: 8px 14px;
            border: none;
            border-radius: 5px;
            background-color: #1976f2;
            color: white;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            margin-top: 15px;
        }

        .btn:hover {
            background-color: #155dc1;
        }
    </style>
</head>

<body>
    <header>
        <h1>MVS Info - Meus Pedidos</h1>
        <nav>
            <a href="area-cliente.php" class="btn">Perfil</a>
            <a href="orcamento.php" class="btn">Orçamento</a>
            <a href="pedido.php" class="btn">Pedidos</a>
        </nav>
    </header>

    <main class="container">
        <h2>Pedidos em andamento</h2>

        <?php if (count($pedidos) > 0): ?>
            <?php foreach ($pedidos as $pedido): ?>
                <div class="pedido">
                    <h3>Produto: <?= htmlspecialchars($pedido['produto']) ?></h3>
                    <p>Quantidade: <?= intval($pedido['quantidade']) ?></p>
                    <p>Status: <span class="status"><?= htmlspecialchars($pedido['status_pagamento']) ?></span></p>
                    <p>Data da Venda: <?= date('d/m/Y', strtotime($pedido['data_venda'])) ?></p>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Você não tem pedidos registrados.</p>
        <?php endif; ?>

        <button type="button" class="btn" onclick="history.back()">Voltar</button>
    </main>
</body>

</html>
