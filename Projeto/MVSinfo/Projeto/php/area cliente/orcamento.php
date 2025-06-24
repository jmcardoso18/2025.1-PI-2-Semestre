<?php
session_start();
require_once '../Conexao.php';

// Verifica se é um cliente logado
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['tipoUsuario'] != 1) {
    header('Location: /Projeto/MVSinfo/Projeto/usuario/login_view.php');
    exit;
}

$conexao = new conexao();
$pdo = $conexao->getPdo();

$idCliente = $_SESSION['id_usuario'] ?? null;

if (!isset($_SESSION['orcamento'])) {
    $_SESSION['orcamento'] = [];
}

// Carrega produtos do banco
$sqlProdutos = "SELECT codigo_produto, descricao FROM produtos";
$stmtProdutos = $pdo->query($sqlProdutos);
$produtos = $stmtProdutos->fetchAll(PDO::FETCH_ASSOC);

// Processamento
$mensagem = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $acao = $_POST['acao'] ?? '';
    $codigoProduto = intval($_POST['produto'] ?? 0);
    $quantidade = intval($_POST['quantidade'] ?? 0);
    $remover = intval($_POST['remover'] ?? 0);

    if ($acao === 'adicionar') {
        if ($codigoProduto > 0 && $quantidade > 0) {
            $produtoExistente = false;
            foreach ($_SESSION['orcamento'] as &$item) {
                if ($item['codigo_produto'] === $codigoProduto) {
                    $item['quantidade'] += $quantidade;
                    $produtoExistente = true;
                    break;
                }
            }
            unset($item);

            if (!$produtoExistente) {
                $_SESSION['orcamento'][] = [
                    'codigo_produto' => $codigoProduto,
                    'quantidade' => $quantidade
                ];
            }

            $mensagem = "Produto adicionado à lista.";
        } else {
            $mensagem = "Preencha os campos corretamente.";
        }
    } elseif ($acao === 'enviar') {
        if (empty($_SESSION['orcamento'])) {
            $mensagem = "Lista vazia. Adicione produtos antes de enviar.";
        } elseif ($idCliente) {
            try {
                $pdo->beginTransaction();

                $stmtFornecedor = $pdo->query("SELECT id_usuario FROM usuario WHERE tipo_usuario = 2 LIMIT 1");
                $idFornecedor = $stmtFornecedor->fetchColumn();
                if (!$idFornecedor) {
                    throw new Exception("Nenhum fornecedor cadastrado.");
                }

                $stmtCompra = $pdo->prepare("INSERT INTO compra (id_fornecedor, data_compra, status_pagamento) VALUES (:id_fornecedor, NOW(), 'pendente')");
                $stmtCompra->execute([':id_fornecedor' => $idFornecedor]);
                $idCompra = $pdo->lastInsertId();

                $stmtProdCompra = $pdo->prepare("INSERT INTO produtocompra (id_compra, codigo_produto, quantidade) VALUES (:id_compra, :codigo_produto, :quantidade)");
                foreach ($_SESSION['orcamento'] as $item) {
                    $stmtProdCompra->execute([
                        ':id_compra' => $idCompra,
                        ':codigo_produto' => $item['codigo_produto'],
                        ':quantidade' => $item['quantidade']
                    ]);
                }

                $pdo->commit();
                $_SESSION['orcamento'] = [];
                $mensagem = "Orçamento enviado com sucesso!";
            } catch (Exception $e) {
                $pdo->rollBack();
                $mensagem = "Erro ao enviar orçamento: " . $e->getMessage();
            }
        }
    } elseif ($acao === 'limpar') {
        $_SESSION['orcamento'] = [];
        $mensagem = "Lista de produtos limpa.";
    } elseif ($acao === 'remover' && $remover > 0) {
        foreach ($_SESSION['orcamento'] as $index => $item) {
            if ($item['codigo_produto'] === $remover) {
                unset($_SESSION['orcamento'][$index]);
                $_SESSION['orcamento'] = array_values($_SESSION['orcamento']); // reorganiza
                $mensagem = "Produto removido da lista.";
                break;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Solicitar Orçamento - MVS Info</title>
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

        h2, h3 {
            color: #1976f2;
            margin-bottom: 20px;
        }

        label {
            font-weight: bold;
            display: block;
            margin-top: 10px;
        }

        select, input {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            margin-top: 20px;
            margin-right: 10px;
            cursor: pointer;
            background-color: #1976f2;
            color: white;
        }

        .btn:hover {
            background-color: #155dc1;
        }

        .mensagem {
            margin-top: 15px;
            font-weight: bold;
            color: green;
        }

        .erro {
            color: red;
        }

        ul {
            margin-top: 15px;
            list-style: none;
            padding: 0;
        }

        li {
            margin-bottom: 10px;
            background-color: #f0f0f0;
            padding: 8px;
            border-radius: 5px;
        }

        .remover-form {
            display: inline;
        }
    </style>
</head>
<body>

<div class="navbar">
    <div><strong>MVS Info - Área do Cliente</strong></div>
    <div>
        <a href="area-cliente.php">Perfil</a>
        <a href="orcamento.php">Orçamento</a>
        <a href="pedido.php">Pedidos</a>
        <a href="../logout.php">Sair</a>
    </div>
</div>

<div class="container">
    <h2>Solicitar Orçamento</h2>

    <?php if (!empty($mensagem)) : ?>
        <p class="mensagem <?= strpos($mensagem, 'Erro') !== false ? 'erro' : '' ?>"><?= $mensagem ?></p>
    <?php endif; ?>

    <form method="POST">
        <label for="produto">Produto:</label>
        <select name="produto" id="produto" required>
            <option value="">Selecione um produto</option>
            <?php foreach ($produtos as $produto) : ?>
                <option value="<?= $produto['codigo_produto'] ?>"><?= htmlspecialchars($produto['descricao']) ?></option>
            <?php endforeach; ?>
        </select>

        <label for="quantidade">Quantidade:</label>
        <input type="number" name="quantidade" id="quantidade" min="1" required>

        <button type="submit" name="acao" value="adicionar" class="btn">Adicionar à Lista</button>
        <a href="./area-cliente.php" class="btn">Voltar</a>
    </form>

    <?php if (!empty($_SESSION['orcamento'])) : ?>
        <h3>Produtos na Lista:</h3>
        <ul>
            <?php foreach ($_SESSION['orcamento'] as $item) : ?>
                <li>
                    <?php
                    $descricao = '';
                    foreach ($produtos as $p) {
                        if ($p['codigo_produto'] == $item['codigo_produto']) {
                            $descricao = $p['descricao'];
                            break;
                        }
                    }
                    ?>
                    <?= htmlspecialchars($descricao) ?> - Quantidade: <?= $item['quantidade'] ?>
                    <form method="POST" class="remover-form">
                        <input type="hidden" name="remover" value="<?= $item['codigo_produto'] ?>">
                        <button type="submit" name="acao" value="remover" class="btn" style="background-color: red;">Remover</button>
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>

        <form method="POST">
            <button type="submit" name="acao" value="enviar" class="btn">Enviar Lista</button>
            <button type="submit" name="acao" value="limpar" class="btn" style="background-color: gray;">Limpar Lista</button>
        </form>
    <?php endif; ?>
</div>

</body>
</html>
