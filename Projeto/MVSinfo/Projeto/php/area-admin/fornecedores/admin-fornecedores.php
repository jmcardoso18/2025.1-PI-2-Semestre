<?php
session_start();
require_once '../../Conexao.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['tipoUsuario'] != 3) {
    header('Location: ../../usuario/login_view.php');
    exit;
}

$conexao = new conexao();
$pdo = $conexao->getPdo();

// Buscar fornecedores (tipo_usuario = 2)
$sql = "SELECT id_usuario, razao_social, email, telefone FROM usuario WHERE tipo_usuario = 2 ORDER BY razao_social ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$fornecedores = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Mensagens de status
$status = $_GET['status'] ?? '';
$mensagem = '';

switch ($status) {
    case 'excluido':
        $mensagem = '<p class="msg sucesso">✅ Fornecedor excluído com sucesso!</p>';
        break;
    case 'fornecedor_nao_encontrado':
        $mensagem = '<p class="msg erro">❌ Fornecedor não encontrado.</p>';
        break;
    case 'erro_id':
        $mensagem = '<p class="msg erro">❌ ID inválido.</p>';
        break;
    case 'erro_delete':
        $mensagem = '<p class="msg erro">❌ Erro ao excluir o fornecedor.</p>';
        break;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Fornecedores - Área Administrador</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f8;
            margin: 0;
        }

        header {
            background-color: #1976f2;
            color: white;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        header h1 {
            margin: 0;
            font-size: 1.6rem;
        }

        nav a {
            color: white;
            text-decoration: none;
            font-weight: bold;
            margin-left: 15px;
        }

        nav a:hover {
            text-decoration: underline;
        }

        main {
            max-width: 1000px;
            margin: 2rem auto;
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.08);
        }

        h2 {
            color: #1976f2;
            margin-bottom: 1rem;
            text-align: center;
        }

        .msg {
            padding: 0.8rem;
            border-radius: 6px;
            margin-bottom: 1rem;
            text-align: center;
            font-weight: bold;
        }

        .sucesso {
            background-color: #d4edda;
            color: #155724;
        }

        .erro {
            background-color: #f8d7da;
            color: #721c24;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 10px 12px;
            text-align: left;
        }

        th {
            background-color: #1976f2;
            color: white;
        }

        tr:hover {
            background-color: #f1f7ff;
        }

        .btn {
            background-color: #1976f2;
            color: white;
            padding: 6px 12px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: 600;
            transition: background-color 0.3s ease;
            display: inline-block;
        }

        .btn:hover {
            background-color: #155dc1;
        }

        .add-btn {
            background-color: #28a745;
            margin-bottom: 1rem;
            display: inline-block;
            padding: 8px 16px;
            border-radius: 6px;
            color: white;
            font-weight: 600;
            text-decoration: none;
        }

        .add-btn:hover {
            background-color: #218838;
        }

        .actions a {
            margin-right: 6px;
        }
    </style>
</head>

<body>
    <header>
        <h1>Área Administrador - Fornecedores</h1>
        <nav>
            <a href="../area-admin.php">Menu</a>
            <a href="admin-fornecedores-add.php">Adicionar Fornecedor</a>
            <a href="../../logout.php">Sair</a>
        </nav>
    </header>

    <main>
        <h2>Lista de Fornecedores</h2>

        <?php if ($mensagem): ?>
            <?= $mensagem ?>
        <?php endif; ?>

        <?php if (count($fornecedores) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Razão Social</th>
                        <th>Email</th>
                        <th>Telefone</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($fornecedores as $fornecedor): ?>
                        <tr>
                            <td><?= htmlspecialchars($fornecedor['id_usuario']) ?></td>
                            <td><?= htmlspecialchars($fornecedor['razao_social']) ?></td>
                            <td><?= htmlspecialchars($fornecedor['email']) ?></td>
                            <td><?= htmlspecialchars($fornecedor['telefone']) ?></td>
                            <td class="actions">
                                <a href="admin-fornecedores-edit.php?id=<?= $fornecedor['id_usuario'] ?>" class="btn">Editar</a>
                                <a href="admin-fornecedores-delete.php?id=<?= $fornecedor['id_usuario'] ?>" class="btn" onclick="return confirm('Confirma exclusão deste fornecedor?')">Excluir</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Nenhum fornecedor cadastrado.</p>
        <?php endif; ?>
    </main>
</body>

</html>
