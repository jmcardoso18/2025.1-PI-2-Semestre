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
    echo "Erro: Usuário não identificado.";
    exit;
}

$idOperacao = intval($_GET['id'] ?? 0);
if ($idOperacao <= 0) {
    echo "Proposta inválida.";
    exit;
}

$erro = '';
$sucesso = '';

// Buscar dados da proposta (operacao) APENAS pelo id_operacao
$stmtOp = $pdo->prepare("SELECT * FROM operacao WHERE id_operacao = :id");
$stmtOp->execute([':id' => $idOperacao]);
$operacao = $stmtOp->fetch(PDO::FETCH_ASSOC);

if (!$operacao) {
    echo "Proposta não encontrada.";
    exit;
}

// Buscar transportadoras para o select
$stmtTransportadoras = $pdo->query("SELECT id_transportadora, descricao, data_entrega, valor, tipo_transportadora FROM transportadora ORDER BY descricao");
$transportadoras = $stmtTransportadoras->fetchAll(PDO::FETCH_ASSOC);

// Buscar produtos da proposta em operacao_produto
$stmtProdutosProposta = $pdo->prepare("
    SELECT op.id_produto, p.descricao, op.quantidade, op.valor_unitario
    FROM operacao_produto op
    JOIN produtos p ON op.id_produto = p.id_produto
    WHERE op.id_operacao = :id_operacao
");
$stmtProdutosProposta->execute([':id_operacao' => $idOperacao]);
$produtosProposta = [];
while ($row = $stmtProdutosProposta->fetch(PDO::FETCH_ASSOC)) {
    $produtosProposta[$row['id_produto']] = $row;
}

// Buscar produtos da categoria do fornecedor (para listar e permitir alteração)
$stmtProdutosDisponiveis = $pdo->prepare("
    SELECT p.id_produto, p.descricao
    FROM produtos p
    JOIN fornecedor_categoria fc ON p.fk_categoria_id_categoria = fc.id_categoria
    WHERE fc.id_fornecedor = :fornecedor
    ORDER BY p.descricao
");
$stmtProdutosDisponiveis->execute([':fornecedor' => $fornecedorId]);
$produtosDisponiveis = $stmtProdutosDisponiveis->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $prazoEntrega = trim($_POST['prazo_entrega'] ?? '');
    $dataOperacao = trim($_POST['data_operacao'] ?? '');
    $idTransportadora = intval($_POST['fk_transportadora_id_transportadora'] ?? 0);
    $produtos = $_POST['produtos'] ?? [];

    if (empty($prazoEntrega) || empty($dataOperacao) || $idTransportadora <= 0 || empty($produtos)) {
        $erro = "Preencha todos os campos corretamente.";
    } else {
        try {
            $pdo->beginTransaction();

            // Atualizar dados da operacao: prazo_entrega, data_operacao e transportadora
            $stmtUpdateOp = $pdo->prepare("
                UPDATE operacao 
                SET prazo_entrega = :prazo, data_operacao = :data_operacao, fk_transportadora_id_transportadora = :transportadora 
                WHERE id_operacao = :id
            ");
            $stmtUpdateOp->execute([
                ':prazo' => $prazoEntrega,
                ':data_operacao' => $dataOperacao,
                ':transportadora' => $idTransportadora,
                ':id' => $idOperacao
            ]);

            // Preparar statements para update e insert produtos
            $stmtCheckProd = $pdo->prepare("SELECT COUNT(*) FROM operacao_produto WHERE id_operacao = :id_operacao AND id_produto = :id_produto");
            $stmtUpdateProd = $pdo->prepare("
                UPDATE operacao_produto
                SET quantidade = :quantidade, valor_unitario = :valor_unitario,
                    valor_total_produtos = :valor_total_produtos
                WHERE id_operacao = :id_operacao AND id_produto = :id_produto
            ");
            $stmtInsertProd = $pdo->prepare("
                INSERT INTO operacao_produto (id_operacao, id_produto, quantidade, valor_unitario, valor_total_produtos, margem_lucro, imposto, preco_venda)
                VALUES (:id_operacao, :id_produto, :quantidade, :valor_unitario, :valor_total_produtos, 0, 0, 0)
            ");

            foreach ($produtos as $idProd => $dados) {
                $quantidade = intval($dados['quantidade']);
                // Ajuste para aceitar vírgula e ponto
                $valorUnitario = floatval(str_replace(',', '.', $dados['valor_unitario']));
                $valorTotalProd = $quantidade * $valorUnitario;

                if ($quantidade <= 0 || $valorUnitario <= 0) {
                    continue;
                }

                $stmtCheckProd->execute([':id_operacao' => $idOperacao, ':id_produto' => $idProd]);
                $exists = $stmtCheckProd->fetchColumn();

                if ($exists) {
                    $stmtUpdateProd->execute([
                        ':quantidade' => $quantidade,
                        ':valor_unitario' => $valorUnitario,
                        ':valor_total_produtos' => $valorTotalProd,
                        ':id_operacao' => $idOperacao,
                        ':id_produto' => $idProd
                    ]);
                } else {
                    $stmtInsertProd->execute([
                        ':id_operacao' => $idOperacao,
                        ':id_produto' => $idProd,
                        ':quantidade' => $quantidade,
                        ':valor_unitario' => $valorUnitario,
                        ':valor_total_produtos' => $valorTotalProd
                    ]);
                }
            }

            // Atualizar valor_total_compra da operacao somando os produtos
            $stmtSum = $pdo->prepare("
                SELECT SUM(valor_total_produtos) FROM operacao_produto WHERE id_operacao = :id_operacao
            ");
            $stmtSum->execute([':id_operacao' => $idOperacao]);
            $valorTotal = $stmtSum->fetchColumn() ?: 0;

            $stmtValorTotal = $pdo->prepare("UPDATE operacao SET valor_total_compra = :valor WHERE id_operacao = :id");
            $stmtValorTotal->execute([':valor' => $valorTotal, ':id' => $idOperacao]);

            $pdo->commit();
            $sucesso = "Proposta atualizada com sucesso!";

            header("Location: preencher-proposta.php?id=$idOperacao&success=1");
            exit;

        } catch (Exception $e) {
            $pdo->rollBack();
            $erro = "Erro ao salvar proposta: " . $e->getMessage();
        }
    }
}

// Recarregar dados após POST para refletir alterações
$stmtOp->execute([':id' => $idOperacao]);
$operacao = $stmtOp->fetch(PDO::FETCH_ASSOC);

$stmtProdutosProposta->execute([':id_operacao' => $idOperacao]);
$produtosProposta = [];
while ($row = $stmtProdutosProposta->fetch(PDO::FETCH_ASSOC)) {
    $produtosProposta[$row['id_produto']] = $row;
}

if (isset($_GET['success'])) {
    $sucesso = "Proposta atualizada com sucesso!";
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <title>Editar Proposta - MVS Info</title>
    <link rel="stylesheet" href="/Projeto/MVSinfo/Projeto/css/styles.css" />
    <style>
        body { background-color: #f5f7fa; font-family: Arial, sans-serif; }
        .navbar { background-color: #1976f2; color: white; padding: 12px 20px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap;}
        .navbar a { color: white; margin-left: 15px; text-decoration: none; font-weight: 600; }
        .navbar a:hover { color: #cce0ff; }
        .container { max-width: 900px; margin: 40px auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        h2 { color: #1976f2; margin-bottom: 20px; }
        label { font-weight: bold; display: block; margin-top: 15px; }
        input[type="text"], input[type="number"], input[type="date"], select {
            width: 100%; padding: 8px 10px; margin-top: 5px; border-radius: 5px; border: 1px solid #ccc; box-sizing: border-box;
        }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; border-bottom: 1px solid #ddd; text-align: left; }
        th { background-color: #f0f0f0; }
        .btn { background-color: #1976f2; color: white; padding: 10px 18px; border: none; border-radius: 5px; cursor: pointer; margin-top: 20px; text-decoration: none; display: inline-block; font-weight: 600; }
        .btn:hover { background-color: #155dc1; }
        .alert { padding: 12px; margin-top: 15px; border-radius: 5px; font-weight: bold; }
        .alert-success { background-color: #d4edda; color: #155724; }
        .alert-error { background-color: #f8d7da; color: #721c24; }
        @media (max-width: 600px) {
            .navbar { flex-direction: column; align-items: flex-start; }
            .navbar a { margin-left: 0; margin-top: 8px; }
            .container { margin: 20px 15px; padding: 20px; }
            th, td { font-size: 13px; padding: 10px 8px; }
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
        <h2>Editar Proposta #<?= htmlspecialchars($idOperacao) ?></h2>

        <?php if ($erro): ?>
            <div class="alert alert-error"><?= htmlspecialchars($erro) ?></div>
        <?php endif; ?>

        <?php if ($sucesso): ?>
            <div class="alert alert-success"><?= htmlspecialchars($sucesso) ?></div>
        <?php endif; ?>

        <form method="post" action="preencher-proposta.php?id=<?= htmlspecialchars($idOperacao) ?>">
            <label for="data_operacao">Data da Operação:</label>
            <input type="date" id="data_operacao" name="data_operacao" required value="<?= htmlspecialchars(date('Y-m-d', strtotime($operacao['data_operacao']))) ?>">

            <label for="prazo_entrega">Prazo de Entrega:</label>
            <input type="text" id="prazo_entrega" name="prazo_entrega" required placeholder="Ex: 10 dias" value="<?= htmlspecialchars($operacao['prazo_entrega']) ?>">

            <label for="fk_transportadora_id_transportadora">Transportadora:</label>
            <select id="fk_transportadora_id_transportadora" name="fk_transportadora_id_transportadora" required>
                <option value="">-- Selecione a Transportadora --</option>
                <?php foreach ($transportadoras as $t): ?>
                    <option value="<?= $t['id_transportadora'] ?>" <?= ($operacao['fk_transportadora_id_transportadora'] == $t['id_transportadora']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($t['descricao']) ?> - Entrega: <?= date('d/m/Y', strtotime($t['data_entrega'])) ?> - R$ <?= number_format($t['valor'], 2, ',', '.') ?> - Tipo: <?= htmlspecialchars($t['tipo_transportadora']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <table>
                <thead>
                    <tr>
                        <th>Produto</th>
                        <th>Quantidade Ofertada</th>
                        <th>Valor Unitário (R$)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($produtosDisponiveis as $produto):
                        $idProd = $produto['id_produto'];
                        $quantidade = $produtosProposta[$idProd]['quantidade'] ?? '';
                        $valorUnitario = $produtosProposta[$idProd]['valor_unitario'] ?? '';
                    ?>
                        <tr>
                            <td><?= htmlspecialchars($produto['descricao']) ?></td>
                            <td>
                                <input type="number" min="0" name="produtos[<?= $idProd ?>][quantidade]" value="<?= htmlspecialchars($quantidade) ?>">
                            </td>
                            <td>
                                <input type="text" name="produtos[<?= $idProd ?>][valor_unitario]" pattern="^\d+([.,]\d{1,2})?$" title="Formato: 0.00" step="0.01" value="<?= htmlspecialchars($valorUnitario) ?>">
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <button type="submit" class="btn">Salvar Proposta</button>
            <a href="propostas.php" class="btn" style="margin-left:10px;">Cancelar</a>
        </form>
    </div>
</body>
</html>
