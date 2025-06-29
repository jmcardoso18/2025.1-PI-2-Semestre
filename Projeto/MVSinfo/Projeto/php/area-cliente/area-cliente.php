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
$sql = "SELECT 
            cnpj, razao_social, inscricao_estadual, contato, telefone, email, 
            cep, logradouro, numero, complemento, bairro, cidade, estado, login
        FROM usuario 
        WHERE id_usuario = :id";
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

        .btn-secondary {
            background-color: #555;
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
        <a href="minhas-operacoes.php">Minhas operações</a>
        <a href="../logout.php">Sair</a>
    </div>
    </div>

    <div class="container">
        <h2>Seus dados</h2>
        <form action="atualizar-cliente.php" method="POST">
            <input type="hidden" name="id_usuario" value="<?= htmlspecialchars($idUsuario) ?>">

            <label for="cnpj">CNPJ</label>
            <input type="text" id="cnpj" name="cnpj" maxlength="18" 
                value="<?= htmlspecialchars($usuario['cnpj']) ?>">

            <label for="razao_social">Razão Social *</label>
            <input type="text" id="razao_social" name="razao_social" required
                value="<?= htmlspecialchars($usuario['razao_social']) ?>">

            <label for="inscricao_estadual">Inscrição Estadual</label>
            <input type="text" id="inscricao_estadual" name="inscricao_estadual" maxlength="20"
                value="<?= htmlspecialchars($usuario['inscricao_estadual']) ?>">

            <label for="contato">Contato</label>
            <input type="text" id="contato" name="contato" maxlength="100"
                value="<?= htmlspecialchars($usuario['contato']) ?>">

            <label for="telefone">Telefone</label>
            <input type="text" id="telefone" name="telefone" maxlength="20"
                value="<?= htmlspecialchars($usuario['telefone']) ?>">

            <label for="email">E-mail *</label>
            <input type="email" id="email" name="email" required maxlength="100"
                value="<?= htmlspecialchars($usuario['email']) ?>">

            <label for="cep">CEP</label>
            <input type="text" id="cep" name="cep" maxlength="10"
                value="<?= htmlspecialchars($usuario['cep']) ?>">

            <label for="logradouro">Logradouro</label>
            <input type="text" id="logradouro" name="logradouro" maxlength="100"
                value="<?= htmlspecialchars($usuario['logradouro']) ?>">

            <label for="numero">Número</label>
            <input type="number" id="numero" name="numero" min="0"
                value="<?= htmlspecialchars($usuario['numero']) ?>">

            <label for="complemento">Complemento</label>
            <input type="text" id="complemento" name="complemento" maxlength="50"
                value="<?= htmlspecialchars($usuario['complemento']) ?>">

            <label for="bairro">Bairro</label>
            <input type="text" id="bairro" name="bairro" maxlength="50"
                value="<?= htmlspecialchars($usuario['bairro']) ?>">

            <label for="cidade">Cidade</label>
            <input type="text" id="cidade" name="cidade" maxlength="50"
                value="<?= htmlspecialchars($usuario['cidade']) ?>">

            <label for="estado">Estado</label>
            <input type="text" id="estado" name="estado" maxlength="2"
                value="<?= htmlspecialchars($usuario['estado']) ?>">

            <label for="login">Login</label>
            <input type="text" id="login" name="login" readonly
                value="<?= htmlspecialchars($usuario['login']) ?>">

            <button type="submit" class="btn btn-primary">Atualizar Dados</button>
        </form>
    </div>

</body>

</html>
