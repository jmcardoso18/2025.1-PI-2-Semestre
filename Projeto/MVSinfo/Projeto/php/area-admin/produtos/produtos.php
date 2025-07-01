<?php
session_start();
require_once '../../Conexao.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['tipoUsuario'] != 3) {
    header('Location: ../../usuario/login_view.php');
    exit;
}

try {
    $conexao = new Conexao();
    $pdo = $conexao->getPDO();

    $sql = "SELECT p.id_produto, p.descricao, c.descricao AS categoria_nome, p.ncm, p.marca, p.unidade_medida
            FROM produtos p
            LEFT JOIN categoria c ON p.fk_categoria_id_categoria = c.id_categoria
            ORDER BY p.descricao ASC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Erro de conexão ou consulta: " . $e->getMessage();
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Produtos - Área Administrador</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body {
            background-color: #f4f6f8;
            font-family: Arial, sans-serif;
        }
        .navbar {
            background-color: #1976f2;
            padding: 15px;
        }
        .navbar .nav-link {
            color: white;
            font-weight: bold;
            margin-right: 15px;
        }
        .navbar .nav-link:hover {
            text-decoration: underline;
        }
        .container {
            max-width: 1000px;
            margin: 40px auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 12px rgba(0,0,0,0.1);
        }
        h2 {
            color: #1976f2;
            text-align: center;
            margin-bottom: 25px;
            font-weight: 700;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            padding: 12px 15px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #1976f2;
            color: white;
            font-weight: 600;
        }
        tr:hover {
            background-color: #f1f7ff;
        }
        .btn-primary {
            background-color: #1976f2;
            border: none;
        }
        .btn-primary:hover {
            background-color: #155dc1;
        }
        .top-actions {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .actions {
            display: flex;
            gap: 8px;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg">
    <div class="container-fluid">
        <a class="navbar-brand text-white" href="#">Área do Administrador</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon text-white"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <div class="navbar-nav">
                <a class="nav-link" href="../area-admin.php">Menu</a>
                <a class="nav-link" href="../../logout.php">Sair</a>
            </div>
        </div>
    </div>
</nav>

<div class="container">
    <h2>Produtos</h2>
    <div class="top-actions">
        <a href="produtos-add.php" class="btn btn-primary">+ Novo Produto</a>
        <a href="../area-admin.php" class="btn btn-secondary">Voltar</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>Código</th>
                <th>Descrição</th>
                <th>Categoria</th>
                <th>NCM</th>
                <th>Marca</th>
                <th>Unidade</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($produtos) === 0): ?>
                <tr>
                    <td colspan="7" class="text-center">Nenhum produto cadastrado.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($produtos as $produto): ?>
                    <tr>
                        <td><?= htmlspecialchars($produto['id_produto']) ?></td>
                        <td><?= htmlspecialchars($produto['descricao']) ?></td>
                        <td><?= htmlspecialchars($produto['categoria_nome'] ?? 'Sem categoria') ?></td>
                        <td><?= htmlspecialchars($produto['ncm']) ?></td>
                        <td><?= htmlspecialchars($produto['marca']) ?></td>
                        <td><?= htmlspecialchars($produto['unidade_medida']) ?></td>
                        <td class="actions">
                            <a href="produtos-editar.php?id=<?= $produto['id_produto'] ?>" class="btn btn-warning btn-sm text-white">Editar</a>
                            <a href="produtos-excluir.php?id=<?= $produto['id_produto'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Deseja realmente excluir este produto?')">Excluir</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>
