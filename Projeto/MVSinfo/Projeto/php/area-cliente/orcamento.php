<?php
session_start();
require_once '../Conexao.php';

// Verifica se usuário está logado e é cliente
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

// Busca os produtos disponíveis para orçar
$sqlProdutos = "SELECT id_produto, descricao FROM produtos ORDER BY descricao";
$stmtProdutos = $pdo->query($sqlProdutos);
$produtos = $stmtProdutos->fetchAll(PDO::FETCH_ASSOC);

// Busca os orçamentos feitos pelo cliente (operacao com tipo 'Orçamento')
$sqlOrcamentos = "
    SELECT o.id_operacao, o.data_operacao, op.quantidade, p.descricao AS produto
    FROM operacao o
    JOIN operacao_produto op ON o.id_operacao = op.id_operacao
    JOIN produtos p ON op.id_produto = p.id_produto
    JOIN tipo_operacao tp ON o.fk_tipo_operacao_id_tipo_operacao = tp.id_tipo_operacao
    WHERE o.fk_usuario_id_usuario = :idUsuario
      AND tp.descricao = 'Orçamento'
    ORDER BY o.data_operacao DESC
";
$stmtOrc = $pdo->prepare($sqlOrcamentos);
$stmtOrc->execute([':idUsuario' => $idUsuario]);
$orcamentos = $stmtOrc->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Fazer Orçamento - MVS Info</title>
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

        select,
        input[type=number] {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .produtos-group {
            margin-bottom: 20px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            margin-top: 15px;
            cursor: pointer;
        }

        .btn-primary {
            background-color: #1976f2;
            color: white;
        }

        .btn-secondary {
            background-color: #555;
            color: white;
        }

        .btn-add {
            background-color: #28a745;
            color: white;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
        }

        table,
        th,
        td {
            border: 1px solid #ccc;
        }

        th,
        td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #1976f2;
            color: white;
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
            <a href="minhas-operacoes.php">Minhas operações</a>
            <a href="../logout.php">Sair</a>
        </div>
    </div>

    <div class="container">
        <h2>Faça seu Orçamento</h2>

        <form id="form-orcamento" action="processar-orcamento.php" method="POST">
            <div id="produtos-container">

                <div class="produtos-group">
                    <label for="produto_id_1">Produto:</label>
                    <select id="produto_id_1" name="produtos[0][produto_id]" required>
                        <option value="">Selecione o produto</option>
                        <?php foreach ($produtos as $produto): ?>
                            <option value="<?= $produto['id_produto'] ?>"><?= htmlspecialchars($produto['descricao']) ?></option>
                        <?php endforeach; ?>
                    </select>

                    <label for="quantidade_1">Quantidade:</label>
                    <input type="number" id="quantidade_1" name="produtos[0][quantidade]" min="1" required>
                </div>

            </div>

            <button type="button" class="btn btn-add" id="add-produto-btn">+ Adicionar mais produtos</button>

            <button type="submit" class="btn btn-primary">Enviar Orçamento</button>
        </form>

        <h3>Seus Orçamentos Recentes</h3>
        <?php if (count($orcamentos) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Produto</th>
                        <th>Quantidade</th>
                        <th>Data</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orcamentos as $orc): ?>
                        <tr>
                            <td><?= $orc['id_operacao'] ?></td>
                            <td><?= htmlspecialchars($orc['produto']) ?></td>
                            <td><?= $orc['quantidade'] ?></td>
                            <td><?= date('d/m/Y', strtotime($orc['data_operacao'])) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Você ainda não fez nenhum orçamento.</p>
        <?php endif; ?>
    </div>

    <script>
        let produtoIndex = 1;

        document.getElementById('add-produto-btn').addEventListener('click', () => {
            produtoIndex++;

            const container = document.getElementById('produtos-container');

            const div = document.createElement('div');
            div.classList.add('produtos-group');

            div.innerHTML = `
                <label for="produto_id_${produtoIndex}">Produto:</label>
                <select id="produto_id_${produtoIndex}" name="produtos[${produtoIndex - 1}][produto_id]" required>
                    <option value="">Selecione o produto</option>
                    <?php foreach ($produtos as $produto): ?>
                        <option value="<?= $produto['id_produto'] ?>"><?= htmlspecialchars($produto['descricao']) ?></option>
                    <?php endforeach; ?>
                </select>

                <label for="quantidade_${produtoIndex}">Quantidade:</label>
                <input type="number" id="quantidade_${produtoIndex}" name="produtos[${produtoIndex - 1}][quantidade]" min="1" required>
            `;

            container.appendChild(div);
        });
    </script>
</body>

</html>
