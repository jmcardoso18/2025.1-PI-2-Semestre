<?php
session_start();
require_once '../Conexao.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['tipoUsuario'] != 2) {
    header('Location: ../usuario/login_view.php');
    exit;
}

$conexao = new conexao();
$pdo = $conexao->getPdo();

$fornecedorId = $_SESSION['id_usuario'] ?? null;
if (!$fornecedorId) {
    echo "Erro: Usuário não identificado.";
    exit;
}

// Buscar todas as propostas (orçamentos) que tenham produtos do fornecedor logado
$stmtPropostas = $pdo->prepare("
    SELECT DISTINCT o.id_operacao, o.data_operacao, o.prazo_entrega, o.valor_total_compra
    FROM operacao o
    JOIN operacao_produto op ON o.id_operacao = op.id_operacao
    JOIN produtos p ON op.id_produto = p.id_produto
    JOIN fornecedor_categoria fc ON p.fk_categoria_id_categoria = fc.id_categoria
    WHERE fc.id_fornecedor = :fornecedor
    AND o.fk_tipo_operacao_id_tipo_operacao = 3
    ORDER BY o.data_operacao DESC
");
$stmtPropostas->execute([':fornecedor' => $fornecedorId]);
$propostas = $stmtPropostas->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>MVS Info - Propostas</title>
    <link rel="stylesheet" href="/Projeto/MVSinfo/Projeto/css/styles.css" />
    <style>
        body {
            background-color: #f5f7fa;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
        }
        .navbar {
            background-color: #1976f2;
            color: white;
            padding: 12px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }
        .navbar a {
            color: white;
            margin-left: 15px;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }
        .navbar a:hover {
            color: #cce0ff;
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
            font-weight: 700;
        }
        .proposta-item {
            margin-bottom: 30px;
            border: 1px solid #ddd;
            padding: 20px;
            border-radius: 8px;
            background: #fafafa;
        }
        .proposta-item h3 {
            margin: 0 0 10px;
            font-weight: 700;
            color: #0d47a1;
        }
        .proposta-item small {
            color: #666;
            font-weight: 400;
        }
        .proposta-item p {
            margin: 5px 0;
            font-size: 15px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            margin-bottom: 10px;
        }
        th, td {
            padding: 12px 10px;
            border-bottom: 1px solid #ddd;
            text-align: left;
            font-size: 14px;
        }
        th {
            background-color: #e3f2fd;
            font-weight: 700;
            color: #1976f2;
        }
        tbody tr:hover {
            background-color: #f1f9ff;
        }
        .btn {
            display: inline-block;
            padding: 10px 18px;
            margin-top: 10px;
            border: none;
            border-radius: 6px;
            background-color: #1976f2;
            color: white;
            text-decoration: none;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s ease;
            font-size: 15px;
        }
        .btn:hover {
            background-color: #155dc1;
        }
        @media (max-width: 600px) {
            .navbar {
                flex-direction: column;
                align-items: flex-start;
            }
            .navbar a {
                margin-left: 0;
                margin-top: 8px;
            }
            .container {
                margin: 20px 15px;
                padding: 20px;
            }
            th, td {
                font-size: 13px;
                padding: 10px 8px;
            }
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
    <h2>Propostas Enviadas</h2>

    <?php if (!empty($propostas)): ?>
        <?php foreach ($propostas as $proposta): ?>
            <div class="proposta-item">
                <h3>
                    Proposta #<?= htmlspecialchars($proposta['id_operacao']) ?> 
                    <small>(<?= date('d/m/Y', strtotime($proposta['data_operacao'])) ?>)</small>
                </h3>
                <p><strong>Prazo de Entrega:</strong> <?= htmlspecialchars($proposta['prazo_entrega'] ?: '-') ?></p>
                <p><strong>Valor Total:</strong> R$ <?= number_format($proposta['valor_total_compra'], 2, ',', '.') ?></p>
                
                <table>
                    <thead>
                        <tr>
                            <th>Produto</th>
                            <th>Quantidade</th>
                            <th>Valor Unitário</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $stmtProdutosProposta = $pdo->prepare("
                            SELECT p.descricao, op.quantidade, op.valor_unitario, op.valor_total_produtos
                            FROM operacao_produto op
                            JOIN produtos p ON op.id_produto = p.id_produto
                            WHERE op.id_operacao = :id_operacao
                        ");
                        $stmtProdutosProposta->execute([':id_operacao' => $proposta['id_operacao']]);
                        $produtosProposta = $stmtProdutosProposta->fetchAll(PDO::FETCH_ASSOC);
                        
                        foreach ($produtosProposta as $produto): ?>
                            <tr>
                                <td><?= htmlspecialchars($produto['descricao']) ?></td>
                                <td><?= (int)$produto['quantidade'] ?></td>
                                <td>R$ <?= number_format($produto['valor_unitario'], 2, ',', '.') ?></td>
                                <td>R$ <?= number_format($produto['valor_total_produtos'], 2, ',', '.') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <a href="preencher-proposta.php?id=<?= urlencode($proposta['id_operacao']) ?>" class="btn">Editar Proposta</a>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Nenhuma proposta enviada até o momento.</p>
    <?php endif; ?>

    <a href="area-fornecedor.php" class="btn">Voltar ao Perfil</a>
</div>

</body>
</html>

