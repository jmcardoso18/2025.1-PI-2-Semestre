<?php
session_start();
require_once '../Conexao.php';

// Verifica se o usuário está logado como cliente
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['tipoUsuario'] != 1) {
    header('Location: ../usuario/login_view.php');
    exit;
}

$conexao = new conexao();
$pdo = $conexao->getPdo();

$id_cliente = $_SESSION['id_usuario'] ?? null;

if (!$id_cliente) {
    echo "Erro: Cliente não identificado.";
    exit;
}

// Consulta para buscar os pedidos do cliente do tipo "Venda"
$sql = "
    SELECT o.id_operacao, o.data_operacao, o.status_pagamento,
           p.descricao AS produto, op.quantidade
    FROM operacao o
    JOIN operacao_produto op ON o.id_operacao = op.id_operacao
    JOIN produtos p ON op.id_produto = p.id_produto
    WHERE o.fk_usuario_id_usuario = :id_cliente
      AND o.fk_tipo_operacao_id_tipo_operacao = (
          SELECT id_tipo_operacao FROM tipo_operacao WHERE descricao = 'Venda' LIMIT 1
      )
    ORDER BY o.data_operacao DESC
";

$stmt = $pdo->prepare($sql);
$stmt->execute([':id_cliente' => $id_cliente]);
$pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <title>Pedidos - MVS Info</title>
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
            padding: 10px 20px;
            background-color: #1976f2;
            color: white;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            cursor: pointer;
            margin-top: 20px;
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
        <h2>Meus Pedidos (Vendas)</h2>

        <?php if (count($pedidos) > 0): ?>
            <?php foreach ($pedidos as $pedido): ?>
                <div class="pedido">
                    <h3><?= htmlspecialchars($pedido['produto']) ?></h3>
                    <p>Quantidade: <?= intval($pedido['quantidade']) ?></p>
                    <p>Status: <span class="status"><?= htmlspecialchars($pedido['status_pagamento']) ?></span></p>
                    <p>Data da Venda: <?= date('d/m/Y', strtotime($pedido['data_operacao'])) ?></p>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Você ainda não tem pedidos registrados.</p>
        <?php endif; ?>

        <a href="area-cliente.php" class="btn">Voltar ao Perfil</a>
    </div>

</body>
</html>
