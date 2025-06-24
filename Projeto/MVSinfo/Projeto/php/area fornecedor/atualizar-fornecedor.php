<?php
session_start();
require_once '../Conexao.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['tipoUsuario'] != 2) {
    header('Location: /Projeto/MVSinfo/Projeto/usuario/login_view.php');
    exit;
}

$mensagem = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idUsuario = intval($_POST['id_usuario']);
    $razaoSocial = $_POST['razao_social'];
    $cnpj = $_POST['cnpj'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];

    $conexao = new conexao();
    $pdo = $conexao->getPdo();

    try {
        $sql = "UPDATE usuario SET 
                    razao_social = :razao_social,
                    cnpj = :cnpj,
                    email = :email,
                    telefone = :telefone
                WHERE id_usuario = :id";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':razao_social' => $razaoSocial,
            ':cnpj' => $cnpj,
            ':email' => $email,
            ':telefone' => $telefone,
            ':id' => $idUsuario
        ]);

        $mensagem = "Dados atualizados com sucesso!";

    } catch (PDOException $e) {
        $mensagem = "Erro ao atualizar: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>MVS Info - Atualização de Dados</title>
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
            text-align: center;
        }

        h2 {
            color: #1976f2;
            margin-bottom: 20px;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            margin-top: 20px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }

        .btn-primary {
            background-color: #1976f2;
            color: white;
        }
    </style>
</head>

<body>

    <div class="navbar">
        <div><strong>MVS Info - Área do Fornecedor</strong></div>
        <div>
            <a href="area-fornecedor.php">Perfil</a>
            <a href="propostas.html">Propostas</a>
            <a href="status-pedido.html">Pedidos</a>
            <a href="../logout.php">Sair</a>
        </div>
    </div>

    <div class="container">
        <h2>Atualização de Dados</h2>
        <p><?= htmlspecialchars($mensagem) ?></p>
        <a href="area-fornecedor.php" class="btn btn-primary">Voltar ao Perfil</a>
    </div>

</body>

</html>
