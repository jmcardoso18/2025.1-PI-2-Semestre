<?php
session_start();
require_once '../Conexao.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['tipoUsuario'] != 2) {
    header('Location: /Projeto/MVSinfo/Projeto/usuario/login_view.php');
    exit;
}

$conexao = new conexao();
$pdo = $conexao->getPDO();
$idUsuario = $_SESSION['id_usuario'];

// Buscar dados do fornecedor
$stmt = $pdo->prepare("SELECT * FROM usuario WHERE id_usuario = :id");
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
  <meta charset="UTF-8" />
  <title>MVS Info - Área do Fornecedor</title>
  <link rel="stylesheet" href="/Projeto/MVSinfo/Projeto/css/styles.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
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
    <div><strong>MVS Info - Área do Fornecedor</strong></div>
    <div>
        <a href="area-fornecedor.php">Perfil</a>
        <a href="propostas.php">Propostas</a>
        <a href="status-pedido.php">Pedidos</a>
        <a href="../logout.php">Sair</a>
    </div>
</div>

<div class="container">
    <h2>Seus Dados</h2>
    <form action="atualizar-fornecedor.php" method="POST">
        <input type="hidden" name="id_usuario" value="<?= $idUsuario ?>">

        <label for="login">Login</label>
        <input type="text" id="login" name="login" value="<?= htmlspecialchars($usuario['login']) ?>" readonly>

        <label for="razao_social">Razão Social</label>
        <input type="text" id="razao_social" name="razao_social" value="<?= htmlspecialchars($usuario['razao_social']) ?>">

        <label for="cnpj">CNPJ</label>
        <input type="text" id="cnpj" name="cnpj" value="<?= htmlspecialchars($usuario['cnpj']) ?>">

        <label for="inscricao_estadual">Inscrição Estadual</label>
        <input type="text" id="inscricao_estadual" name="inscricao_estadual" value="<?= htmlspecialchars($usuario['inscricao_estadual']) ?>">

        <label for="contato">Nome do Responsável</label>
        <input type="text" id="contato" name="contato" value="<?= htmlspecialchars($usuario['contato']) ?>">

        <label for="telefone">Telefone</label>
        <input type="text" id="telefone" name="telefone" value="<?= htmlspecialchars($usuario['telefone']) ?>">

        <label for="email">Email</label>
        <input type="email" id="email" name="email" value="<?= htmlspecialchars($usuario['email']) ?>">

        <label for="cep">CEP</label>
        <input type="text" id="cep" name="cep" value="<?= htmlspecialchars($usuario['cep']) ?>">

        <label for="logradouro">Logradouro</label>
        <input type="text" id="logradouro" name="logradouro" value="<?= htmlspecialchars($usuario['logradouro']) ?>">

        <label for="numero">Número</label>
        <input type="text" id="numero" name="numero" value="<?= htmlspecialchars($usuario['numero']) ?>">

        <label for="complemento">Complemento</label>
        <input type="text" id="complemento" name="complemento" value="<?= htmlspecialchars($usuario['complemento']) ?>">

        <label for="bairro">Bairro</label>
        <input type="text" id="bairro" name="bairro" value="<?= htmlspecialchars($usuario['bairro']) ?>">

        <label for="cidade">Cidade</label>
        <input type="text" id="cidade" name="cidade" value="<?= htmlspecialchars($usuario['cidade']) ?>">

        <label for="estado">Estado</label>
        <input type="text" id="estado" name="estado" maxlength="2" value="<?= htmlspecialchars($usuario['estado']) ?>">

        <button type="submit" class="btn btn-primary">Atualizar Dados</button>
    </form>
</div>

</body>
</html>
