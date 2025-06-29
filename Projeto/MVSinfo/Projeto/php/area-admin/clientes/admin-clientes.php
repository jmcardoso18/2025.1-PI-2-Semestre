<?php
session_start();
require_once '../../Conexao.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['tipoUsuario'] != 3) {
    header('Location: ../../usuario/login_view.php');
    exit;
}

$conexao = new conexao();
$pdo = $conexao->getPdo();

// Buscar clientes (tipo_usuario = 1)
$sql = "SELECT id_usuario, razao_social, nome_fantasia, email, telefone FROM usuario WHERE tipo_usuario = 1 ORDER BY razao_social ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Mensagens de status
$status = $_GET['status'] ?? '';
$mensagem = '';

switch ($status) {
    case 'excluido':
        $mensagem = '<p class="msg sucesso">✅ Cliente excluído com sucesso!</p>';
        break;
    case 'cliente_nao_encontrado':
        $mensagem = '<p class="msg erro">❌ Cliente não encontrado.</p>';
        break;
    case 'erro_id':
        $mensagem = '<p class="msg erro">❌ ID inválido.</p>';
        break;
    case 'erro_delete':
        $mensagem = '<p class="msg erro">❌ Erro ao excluir o cliente.</p>';
        break;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Clientes - Área Administrador</title>
    <link rel="stylesheet" href="../css/styles.css">
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
        <h1>Área Administrador - Clientes</h1>
        <nav>
            <a href="../area-admin.php"> Menu</a>
            <a href="admin-clientes-add.php"> Adicionar Cliente</a>
            <a href="../../logout.php"> Sair</a>
        </nav>
    </header>

    <main>
        <h2>Lista de Clientes</h2>

        <?php if ($mensagem): ?>
            <?= $mensagem ?>
        <?php endif; ?>

        <?php if (count($clientes) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Razão Social</th>
                        <th>Nome Fantasia</th>
                        <th>Email</th>
                        <th>Telefone</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($clientes as $cliente): ?>
                        <tr>
                            <td><?= htmlspecialchars($cliente['id_usuario']) ?></td>
                            <td><?= htmlspecialchars($cliente['razao_social']) ?></td>
                            <td><?= htmlspecialchars($cliente['nome_fantasia']) ?></td>
                            <td><?= htmlspecialchars($cliente['email']) ?></td>
                            <td><?= htmlspecialchars($cliente['telefone']) ?></td>
                            <td class="actions">
                                <a href="admin-clientes-edit.php?id=<?= $cliente['id_usuario'] ?>" class="btn"> Editar</a>
                                <a href="admin-clientes-delete.php?id=<?= $cliente['id_usuario'] ?>" class="btn" onclick="return confirm('Confirma exclusão deste cliente?')"> Excluir</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Nenhum cliente cadastrado.</p>
        <?php endif; ?>
    </main>
</body>

</html>
