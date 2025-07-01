<?php
session_start();
require_once '../../Conexao.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['tipoUsuario'] != 3) {
    header('Location: ../usuario/login_view.php');
    exit;
}

$conexao = new Conexao();
$pdo = $conexao->getPdo();

$idOperacao = intval($_GET['id'] ?? 0);
if ($idOperacao <= 0) {
    echo "Operação inválida.";
    exit;
}

$erro = '';
$sucesso = '';

// Buscar dados da operação
$stmtOp = $pdo->prepare("SELECT * FROM operacao WHERE id_operacao = :id");
$stmtOp->execute([':id' => $idOperacao]);
$operacao = $stmtOp->fetch(PDO::FETCH_ASSOC);

if (!$operacao) {
    echo "Operação não encontrada.";
    exit;
}

// Buscar produtos dessa operação (sem preco_venda)
$stmtProdutos = $pdo->prepare("
    SELECT op.id_produto, p.descricao, op.quantidade, op.valor_unitario, op.valor_total_produtos,
           op.margem_lucro, op.imposto
    FROM operacao_produto op
    JOIN produtos p ON op.id_produto = p.id_produto
    WHERE op.id_operacao = :id_operacao
");
$stmtProdutos->execute([':id_operacao' => $idOperacao]);
$produtos = $stmtProdutos->fetchAll(PDO::FETCH_ASSOC);

// Processar o POST para atualizar valores
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dadosProdutos = $_POST['produtos'] ?? [];

    try {
        $pdo->beginTransaction();

        foreach ($dadosProdutos as $idProd => $campos) {
            $margemLucro = floatval(str_replace(',', '.', $campos['margem_lucro']));
            $imposto = floatval(str_replace(',', '.', $campos['imposto']));

            // Atualizar os valores no banco (sem preco_venda)
            $stmtUpdate = $pdo->prepare("
                UPDATE operacao_produto SET
                    margem_lucro = :margem_lucro,
                    imposto = :imposto
                WHERE id_operacao = :id_operacao AND id_produto = :id_produto
            ");
            $stmtUpdate->execute([
                ':margem_lucro' => $margemLucro,
                ':imposto' => $imposto,
                ':id_operacao' => $idOperacao,
                ':id_produto' => $idProd
            ]);
        }

        // Recalcular valor_total_compra somando somente valor_total_produtos + margem_lucro + imposto
        $stmtSoma = $pdo->prepare("
            SELECT 
                SUM(valor_total_produtos + margem_lucro + imposto) AS total_calculado
            FROM operacao_produto
            WHERE id_operacao = :id_operacao
        ");
        $stmtSoma->execute([':id_operacao' => $idOperacao]);
        $totalCalculado = $stmtSoma->fetchColumn() ?: 0;

        // Atualizar valor total da operação
        $stmtAtualizaOp = $pdo->prepare("
            UPDATE operacao SET valor_total_compra = :total WHERE id_operacao = :id_operacao
        ");
        $stmtAtualizaOp->execute([
            ':total' => $totalCalculado,
            ':id_operacao' => $idOperacao
        ]);

        $pdo->commit();
        $sucesso = "Valores atualizados com sucesso!";

        // Recarregar os produtos com os novos dados
        $stmtProdutos->execute([':id_operacao' => $idOperacao]);
        $produtos = $stmtProdutos->fetchAll(PDO::FETCH_ASSOC);

    } catch (Exception $e) {
        $pdo->rollBack();
        $erro = "Erro ao atualizar os dados: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <title>Editar Produtos da Operação #<?= htmlspecialchars($idOperacao) ?></title>
    <link rel="stylesheet" href="../css/styles.css" />
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
            max-width: 900px;
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
            border: 1px solid #ddd;
            text-align: left;
        }
        input[type="text"], input[type="number"] {
            width: 100%;
            padding: 6px 8px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
        }
        .btn {
            background-color: #1976f2;
            color: white;
            padding: 10px 18px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 600;
            font-size: 16px;
            transition: background-color 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }
        .btn:hover {
            background-color: #155db2;
        }
        .btn-voltar {
            background-color: #555;
            margin-left: 15px;
        }
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 15px;
            border: 1px solid #c3e6cb;
        }
        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 15px;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <strong>Admin - Editar Produtos da Operação #<?= htmlspecialchars($idOperacao) ?></strong>
        <div>
            <a href="../area-admin.php">Menu</a>
            <a href="../../logout.php">Sair</a>
        </div>
    </div>

    <div class="container">
        <h2>Editar Produtos da Operação #<?= htmlspecialchars($idOperacao) ?></h2>

        <?php if ($erro): ?>
            <div class="alert-error"><?= htmlspecialchars($erro) ?></div>
        <?php endif; ?>
        <?php if ($sucesso): ?>
            <div class="alert-success"><?= htmlspecialchars($sucesso) ?></div>
        <?php endif; ?>

        <form method="post" action="">
            <table>
                <thead>
                    <tr>
                        <th>Produto</th>
                        <th>Quantidade</th>
                        <th>Valor Unitário (R$)</th>
                        <th>Valor Total Produtos (R$)</th>
                        <th>Margem Lucro (R$)</th>
                        <th>Imposto (R$)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($produtos as $p): ?>
                        <tr>
                            <td><?= htmlspecialchars($p['descricao']) ?></td>
                            <td><?= htmlspecialchars($p['quantidade']) ?></td>
                            <td><?= number_format($p['valor_unitario'], 2, ',', '.') ?></td>
                            <td><?= number_format($p['valor_total_produtos'], 2, ',', '.') ?></td>
                            <td>
                                <input type="text" name="produtos[<?= $p['id_produto'] ?>][margem_lucro]" value="<?= number_format($p['margem_lucro'], 2, ',', '.') ?>">
                            </td>
                            <td>
                                <input type="text" name="produtos[<?= $p['id_produto'] ?>][imposto]" value="<?= number_format($p['imposto'], 2, ',', '.') ?>">
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <button type="submit" class="btn">Salvar Alterações</button>
            <a href="admin-operacoes.php?tipo=<?= htmlspecialchars(strtolower($operacao['descricao'] ?? 'compra')) ?>" class="btn btn-voltar">Voltar</a>
        </form>
    </div>
</body>
</html>
