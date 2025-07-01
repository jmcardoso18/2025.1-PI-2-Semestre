<?php
session_start();
require_once '../Conexao.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['tipoUsuario'] != 1) {
    header("Location: ../usuario/login_view.php");
    exit;
}

$conexao = new conexao();
$pdo = $conexao->getPdo();

// Buscar produtos disponíveis
$stmt = $pdo->query("SELECT id_produto, descricao FROM produtos ORDER BY descricao");
$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <title>Criar Orçamento - MVS Info</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
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
        .navbar a:hover {
            text-decoration: underline;
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
            margin-bottom: 30px;
        }
        label {
            font-weight: 600;
        }
        .btn-primary {
            background-color: #1976f2;
            border-color: #1976f2;
        }
        .btn-primary:hover {
            background-color: #155dc1;
            border-color: #155dc1;
        }
        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
        }
        .btn-secondary:hover {
            background-color: #565e64;
            border-color: #565e64;
        }
    </style>
</head>
<body>

<nav class="navbar">
    <div><strong>MVS Info - Área do Cliente</strong></div>
    <div>
        <a href="area-cliente.php">Perfil</a>
        <a href="../logout.php">Sair</a>
    </div>
</nav>

<div class="container mt-3">
    <h2>Criar Novo Orçamento</h2>
    <form action="processar-orcamento.php" method="POST">
        <div id="produtosContainer">
            <div class="row mb-3 produto-item align-items-end">
                <div class="col-md-7">
                    <label for="produto_0">Produto:</label>
                    <select id="produto_0" name="produtos[0][produto_id]" class="form-select" required>
                        <option value="">Selecione</option>
                        <?php foreach ($produtos as $p): ?>
                            <option value="<?= $p['id_produto'] ?>"><?= htmlspecialchars($p['descricao']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="quantidade_0">Quantidade:</label>
                    <input id="quantidade_0" type="number" name="produtos[0][quantidade]" class="form-control" min="1" required>
                </div>
            </div>
        </div>

        <button type="button" class="btn btn-primary mb-3" id="addProduto">+ Adicionar Produto</button>
        <br>
        <button type="submit" class="btn btn-primary">Criar Orçamento</button>
        <a href="orcamento.php" class="btn btn-primary ms-2">Cancelar</a>
    </form>
</div>

<script>
let contador = 1;
document.getElementById('addProduto').addEventListener('click', function () {
    const container = document.getElementById('produtosContainer');
    const item = document.createElement('div');
    item.classList.add('row', 'mb-3', 'produto-item', 'align-items-end');
    item.innerHTML = `
        <div class="col-md-7">
            <select id="produto_${contador}" name="produtos[${contador}][produto_id]" class="form-select" required>
                <option value="">Selecione</option>
                <?php foreach ($produtos as $p): ?>
                    <option value="<?= $p['id_produto'] ?>"><?= htmlspecialchars($p['descricao']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-3">
            <input id="quantidade_${contador}" type="number" name="produtos[${contador}][quantidade]" class="form-control" min="1" required>
        </div>
    `;
    container.appendChild(item);
    contador++;
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
