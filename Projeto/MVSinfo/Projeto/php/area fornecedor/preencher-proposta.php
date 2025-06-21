<?php
session_start();
require_once '../Conexao.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['tipoUsuario'] != 2) {
    header('Location: /Projeto/MVSinfo/Projeto/usuario/login_view.php');
    exit;
}

$conexao = new conexao();
$pdo = $conexao->getPdo();

$fornecedorId = $_SESSION['id_usuario'] ?? null; // Assumindo que você guarda o id_usuario na sessão
if (!$fornecedorId) {
    echo "Erro: Usuário não identificado.";
    exit;
}

$idOrcamento = intval($_GET['id'] ?? 0);
if ($idOrcamento <= 0) {
    echo "Orçamento inválido.";
    exit;
}

// Se o formulário foi enviado, processa o POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $prazoEntrega = trim($_POST['prazo_entrega']);
    $produtos = $_POST['produtos'] ?? [];

    if (empty($prazoEntrega) || empty($produtos)) {
        $erro = "Preencha todos os campos.";
    } else {
        try {
            // Inicia transação
            $pdo->beginTransaction();

            // Insere na tabela cotacao
            $stmtCotacao = $pdo->prepare("INSERT INTO cotacao (id_fornecedor, data_cotacao, prazo_entrega) VALUES (:id_fornecedor, NOW(), :prazo_entrega)");
            $stmtCotacao->execute([
                ':id_fornecedor' => $fornecedorId,
                ':prazo_entrega' => $prazoEntrega
            ]);
            $idCotacao = $pdo->lastInsertId();

            // Insere os produtos na cotproduto
            $stmtCotProduto = $pdo->prepare("INSERT INTO cotproduto (id_cotacao, codigo_produto, quantidade, valor_unitario) VALUES (:id_cotacao, :codigo_produto, :quantidade, :valor_unitario)");

            foreach ($produtos as $codigo => $dadosProduto) {
                $quantidade = intval($dadosProduto['quantidade']);
                $valorUnitario = floatval(str_replace(',', '.', $dadosProduto['valor_unitario']));

                if ($quantidade <= 0 || $valorUnitario <= 0) {
                    throw new Exception("Quantidade e valor unitário devem ser maiores que zero.");
                }

                $stmtCotProduto->execute([
                    ':id_cotacao' => $idCotacao,
                    ':codigo_produto' => $codigo,
                    ':quantidade' => $quantidade,
                    ':valor_unitario' => $valorUnitario
                ]);
            }

            $pdo->commit();
            $sucesso = "Proposta enviada com sucesso!";
        } catch (Exception $e) {
            $pdo->rollBack();
            $erro = "Erro ao salvar proposta: " . $e->getMessage();
        }
    }
}

// Busca os produtos do orçamento para mostrar no formulário
$stmtProdutos = $pdo->prepare("
    SELECT op.codigo_produto, p.descricao, op.quantidade
    FROM orcamentoproduto op
    JOIN Produtos p ON op.codigo_produto = p.codigo_produto
    WHERE op.id_orcamento = :id_orcamento
");
$stmtProdutos->execute([':id_orcamento' => $idOrcamento]);
$produtosOrcamento = $stmtProdutos->fetchAll(PDO::FETCH_ASSOC);

if (!$produtosOrcamento) {
    echo "Nenhum produto encontrado para este orçamento.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8" />
    <title>Preencher Proposta - MVS Info</title>
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

        label {
            font-weight: bold;
            display: block;
            margin-top: 15px;
        }

        input[type="text"],
        input[type="number"] {
            width: 100%;
            padding: 8px 10px;
            margin-top: 5px;
            border-radius: 5px;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
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

        .btn {
            background-color: #1976f2;
            color: white;
            padding: 10px 18px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 20px;
            text-decoration: none;
            display: inline-block;
        }

        .btn:hover {
            background-color: #155dc1;
        }

        .alert {
            padding: 12px;
            margin-top: 15px;
            border-radius: 5px;
            font-weight: bold;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
        }

        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
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
        </div>
    </div>

    <div class="container">
        <h2>Preencher Proposta para Orçamento #<?= htmlspecialchars($idOrcamento) ?></h2>

        <?php if (!empty($erro)) : ?>
            <div class="alert alert-error"><?= htmlspecialchars($erro) ?></div>
        <?php endif; ?>

        <?php if (!empty($sucesso)) : ?>
            <div class="alert alert-success"><?= htmlspecialchars($sucesso) ?></div>
        <?php endif; ?>

        <form method="post" action="preencher-proposta.php?id=<?= htmlspecialchars($idOrcamento) ?>">
            <label for="prazo_entrega">Prazo de Entrega:</label>
            <input type="text" id="prazo_entrega" name="prazo_entrega" required placeholder="Ex: 10 dias">

            <table>
                <thead>
                    <tr>
                        <th>Produto</th>
                        <th>Quantidade Solicitada</th>
                        <th>Quantidade Ofertada</th>
                        <th>Valor Unitário (R$)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($produtosOrcamento as $produto) : ?>
                        <tr>
                            <td><?= htmlspecialchars($produto['descricao']) ?></td>
                            <td><?= intval($produto['quantidade']) ?></td>
                            <td>
                                <input type="number" name="produtos[<?= intval($produto['codigo_produto']) ?>][quantidade]" min="1" max="<?= intval($produto['quantidade']) ?>" required>
                            </td>
                            <td>
                                <input type="text" name="produtos[<?= intval($produto['codigo_produto']) ?>][valor_unitario]" pattern="^\d+(\.\d{1,2})?$" title="Formato: 0.00" required>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <button type="submit" class="btn">Enviar Proposta</button>
            <a href="propostas.php" class="btn" style="background-color:gray; margin-left: 10px;">Cancelar</a>
        </form>
    </div>
</body>

</html>
