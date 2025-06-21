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

// Carregar lista de produtos do banco
$sqlProdutos = "SELECT codigo_produto, descricao FROM produtos";
$stmtProdutos = $pdo->query($sqlProdutos);
$produtos = $stmtProdutos->fetchAll(PDO::FETCH_ASSOC);

// Processar envio do orçamento
$mensagem = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $codigoProduto = intval($_POST['produto'] ?? 0);
    $quantidade = intval($_POST['quantidade'] ?? 0);

    if ($codigoProduto > 0 && $quantidade > 0 && $idCliente) {
        try {
            $pdo->beginTransaction();

            // Inserir na tabela compra
            $sqlCompra = "INSERT INTO compra (id_cliente, data_compra, status_pagamento) VALUES (:id_cliente, NOW(), 'pendente')";
            $stmtCompra = $pdo->prepare($sqlCompra);
            $stmtCompra->execute([':id_cliente' => $idCliente]);
            $idCompra = $pdo->lastInsertId();

            // Inserir na tabela produtocompra
            $sqlProdCompra = "INSERT INTO produtocompra (id_compra, codigo_produto, quantidade) VALUES (:id_compra, :codigo_produto, :quantidade)";
            $stmtProdCompra = $pdo->prepare($sqlProdCompra);
            $stmtProdCompra->execute([
                ':id_compra' => $idCompra,
                ':codigo_produto' => $codigoProduto,
                ':quantidade' => $quantidade
            ]);

            $pdo->commit();
            $mensagem = "Orçamento enviado com sucesso!";
        } catch (PDOException $e) {
            $pdo->rollBack();
            $mensagem = "Erro ao enviar orçamento: " . $e->getMessage();
        }
    } else {
        $mensagem = "Preencha todos os campos corretamente.";
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

        h2 {
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
    </style>
</head>

<body>

    <div class="navbar">
        <div><strong>MVS Info - Área do Cliente</strong></div>
        <div>
            <a href="area-cliente.php">Perfil</a>
            <a href="orcamento.php">Orçamento</a>
            <a href="pedido.php">Pedidos</a>
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

            <button type="submit" class="btn">Enviar Orçamento</button>
            <a href="area-cliente.php" class="btn">Voltar</a>
        </form>
    </div>

</body>

</html>
