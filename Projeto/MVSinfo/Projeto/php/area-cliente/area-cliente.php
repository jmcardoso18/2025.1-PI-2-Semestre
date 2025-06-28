<?php
session_start();
require_once '../Conexao.php';

// Verifica se o usuário está logado como cliente
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['tipoUsuario'] != 1) {
    header('Location: /Projeto/MVSinfo/Projeto/usuario/login_view.php');
    exit;
}

$conexao = new conexao();
$pdo = $conexao->getPdo();

$idUsuario = $_SESSION['id_usuario'] ?? null;

if (!$idUsuario) {
    echo "Erro: Usuário não identificado.";
    exit;
}

// Busca os dados atuais do cliente
$sql = "SELECT razao_social, email, telefone, nome_fantasia FROM usuario WHERE id_usuario = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute([':id' => $idUsuario]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$usuario) {
    echo "Erro: Dados do cliente não encontrados.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Perfil do Cliente - MVS Info</title>
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

        .right-button {
            float: right;
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
       <!-- trecho já autenticado no início -->
<h2>Seus dados</h2>
    <form action="atualizar-cliente.php" method="POST">
      <input type="hidden" name="id_usuario" value="<?= $idUsuario ?>">

      <label for="razao_social">Razão Social *</label>
      <input type="text" id="razao_social" name="razao_social" required
           value="<?= htmlspecialchars($usuario['razao_social']) ?>">

      <label for="email">E-mail *</label>
      <input type="email" id="email" name="email" required
           value="<?= htmlspecialchars($usuario['email']) ?>">

      <label for="telefone">Telefone</label>
      <input type="text" id="telefone" name="telefone"
           value="<?= htmlspecialchars($usuario['telefone']) ?>">

      <label for="nome_fantasia">Nome Fantasia (empresa)</label>
      <input type="text" id="nome_fantasia" name="nome_fantasia"
           value="<?= htmlspecialchars($usuario['nome_fantasia']) ?>">

      <button type="submit" class="btn btn-primary">Atualizar Dados</button>
      <a href="orcamento.php" class="btn btn-primary right-button">Ir para Orçamento</a><br><br>
      <a href="minhas-operacoes.php" class="btn btn-secondary">Ver Histórico de Operações</a>
    </form>

    </div>

</body>

</html>
