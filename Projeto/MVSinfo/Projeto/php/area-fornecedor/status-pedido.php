<?php
session_start();
require_once '../Conexao.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['tipoUsuario'] != 2) {
    header('Location: /Projeto/MVSinfo/Projeto/usuario/login_view.php');
    exit;
}

$conexao = new conexao();
$pdo = $conexao->getPdo();

$fornecedorId = $_SESSION['id_usuario'] ?? null;

if (!$fornecedorId) {
    echo "Erro: Fornecedor não identificado.";
    exit;
}

$sql = "
    SELECT
      o.id_operacao,
      o.data_operacao,
      o.status_pagamento,
      u.razao_social AS cliente,
      p.descricao AS produto,
      op.quantidade
    FROM operacao o
    JOIN usuario u ON o.fk_usuario_id_usuario = u.id_usuario
    JOIN operacao_produto op ON o.id_operacao = op.id_operacao
    JOIN produtos p ON op.id_produto = p.id_produto
    JOIN fornecedor_categoria fc ON p.fk_categoria_id_categoria = fc.id_categoria
    WHERE fc.id_fornecedor = :id_fornecedor
      AND o.fk_tipo_operacao_id_tipo_operacao IN (1, 2) -- 1=Venda, 2=Compra (ajuste se quiser outro filtro)
    ORDER BY o.data_operacao DESC
";

$stmt = $pdo->prepare($sql);
$stmt->execute([':id_fornecedor' => $fornecedorId]);
$compras = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>MVS Info - Status do Pedido</title>
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

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #f0f0f0;
        }

        .status-aprovado {
            color: green;
            font-weight: bold;
        }

        .status-pendente {
            color: orange;
            font-weight: bold;
        }

        .status-rejeitado {
            color: red;
            font-weight: bold;
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
        <div><strong>MVS Info - Área do Fornecedor</strong></div>
        <div>
            <a href="area-fornecedor.php">Perfil</a>
            <a href="propostas.php">Propostas</a>
            <a href="status-pedido.php">Pedidos</a>
            <a href="../logout.php">Sair</a>
        </div>
    </div>

    <div class="container">
        <h2>Status dos Pedidos</h2>

        <?php if (count($compras) > 0) : ?>
            <table>
                <thead>
                    <tr>
                        <th>Cliente</th>
                        <th>Data da Operação</th>
                        <th>Produto</th>
                        <th>Quantidade</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($compras as $compra) : ?>
                        <?php
                        $statusClass = '';
                        switch (strtolower($compra['status_pagamento'])) {
                            case 'pago':
                            case 'aprovado':
                                $statusClass = 'status-aprovado';
                                break;
                            case 'pendente':
                                $statusClass = 'status-pendente';
                                break;
                            case 'rejeitado':
                            case 'cancelado':
                                $statusClass = 'status-rejeitado';
                                break;
                            default:
                                $statusClass = '';
                        }
                        ?>
                        <tr>
                            <td><?= htmlspecialchars($compra['cliente']) ?></td>
                            <td><?= date('d/m/Y', strtotime($compra['data_operacao'])) ?></td>
                            <td><?= htmlspecialchars($compra['produto']) ?></td>
                            <td><?= intval($compra['quantidade']) ?></td>
                            <td class="<?= $statusClass ?>"><?= htmlspecialchars($compra['status_pagamento']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else : ?>
            <p>Nenhum pedido encontrado para você.</p>
        <?php endif; ?>

        <br>
        <a href="area-fornecedor.php" class="btn">Voltar</a>
    </div>

</body>

</html>
