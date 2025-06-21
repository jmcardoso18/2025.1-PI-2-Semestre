<?php
session_start();
require_once '../Conexao.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['tipoUsuario'] != 2) {
    header('Location: /Projeto/MVSinfo/Projeto/usuario/login_view.php');
    exit;
}

$conexao = new conexao();
$pdo = $conexao->getPdo();

$idUsuario = $_SESSION['id_usuario'];

// Buscar dados do fornecedor
$stmt = $pdo->prepare("SELECT razao_social, cnpj, email, telefone FROM usuario WHERE id_usuario = :id");
$stmt->execute([':id' => $idUsuario]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$usuario) {
    echo "Erro: Usuário não encontrado.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>MVS Info - Área do Fornecedor</title>
    <link rel="stylesheet" href="/Projeto/MVSinfo/Projeto/css/styles.css">
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

        input {
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
        }

        .btn-primary {
            background-color: #1976f2;
            color: white;
        }

        .btn-secondary {
            background-color: #1976f2;
            color: white;
        }

        .right-button {
            float: right;
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

    <h1>
        Olá, <?= htmlspecialchars($_SESSION['usuario']) ?>
    </h1>

    <div class="container">
        <h2>Seus dados</h2>
        <form action="atualizar-fornecedor.php" method="post">
            <input type="hidden" name="id_usuario" value="<?= $idUsuario ?>">

            <label for="razao">Razão Social:</label>
            <input type="text" id="razao" name="razao_social" value="<?= htmlspecialchars($usuario['razao_social']) ?>">

            <label for="cnpj">CNPJ:</label>
            <input type="text" id="cnpj" name="cnpj" value="<?= htmlspecialchars($usuario['cnpj']) ?>">

            <label for="email">E-mail:</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($usuario['email']) ?>">

            <label for="telefone">Telefone:</label>
            <input type="text" id="telefone" name="telefone" value="<?= htmlspecialchars($usuario['telefone']) ?>">

            <div style="display: flex; justify-content: space-between; margin-top: 20px;">
                <button type="submit" class="btn btn-primary">Atualizar Dados</button>
                <a href="propostas.php" class="btn btn-primary" style="text-decoration: none; display: inline-block; text-align: center; line-height: 38px;">Ir para Propostas</a>
            </div>
        </form>
    </div>

</body>

</html>
