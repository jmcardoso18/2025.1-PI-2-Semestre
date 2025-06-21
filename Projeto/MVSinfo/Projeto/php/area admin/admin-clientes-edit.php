<?php
session_start();
require_once '../Conexao.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['tipoUsuario'] != 0) {
    header('Location: ../usuario/login_view.php');
    exit;
}

$conexao = new conexao();
$pdo = $conexao->getPdo();

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
    header('Location: admin_clientes.php');
    exit;
}

// Busca dados atuais
$stmt = $pdo->prepare("SELECT * FROM usuario WHERE id_usuario = :id AND tipo_usuario = 1");
$stmt->execute([':id' => $id]);
$cliente = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$cliente) {
    header('Location: admin_clientes.php');
    exit;
}

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $razao_social = trim($_POST['razao_social'] ?? '');
    $nome_fantasia = trim($_POST['nome_fantasia'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $telefone = trim($_POST['telefone'] ?? '');
    $login = trim($_POST['login'] ?? '');

    if (!$razao_social) $errors[] = 'Razão Social é obrigatória.';
    if (!$email) $errors[] = 'Email é obrigatório.';
    if (!$login) $errors[] = 'Login é obrigatório.';

    // Verificar se email ou login já estão em uso por outro usuário
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM usuario WHERE (email = :email OR login = :login) AND id_usuario != :id");
    $stmt->execute([':email' => $email, ':login' => $login, ':id' => $id]);
    if ($stmt->fetchColumn() > 0) {
        $errors[] = 'Email ou login já estão em uso por outro usuário.';
    }

    if (empty($errors)) {
        $sql = "UPDATE usuario SET 
            razao_social = :razao_social,
            nome_fantasia = :nome_fantasia,
            email = :email,
            telefone = :telefone,
            login = :login
            WHERE id_usuario = :id";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':razao_social' => $razao_social,
            ':nome_fantasia' => $nome_fantasia,
            ':email' => $email,
            ':telefone' => $telefone,
            ':login' => $login,
            ':id' => $id
        ]);

        $success = true;

        // Atualiza a variável cliente para refletir as mudanças no formulário
        $cliente = array_merge($cliente, [
            'razao_social' => $razao_social,
            'nome_fantasia' => $nome_fantasia,
            'email' => $email,
            'telefone' => $telefone,
            'login' => $login,
        ]);
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Editar Cliente - Área Admin</title>
    <link rel="stylesheet" href="../css/styles.css" />
    <style>
        main {
            max-width: 600px;
            margin: 3rem auto;
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 0 10px rgb(0 0 0 / 0.1);
        }
        h2 {
            color: #1976f2;
            margin-bottom: 1.5rem;
            text-align: center;
        }
        form {
            display: grid;
            gap: 1rem;
        }
        label {
            font-weight: 600;
        }
        input {
            padding: 8px;
            font-size: 1rem;
            border: 1px solid #ddd;
            border-radius: 6px;
            width: 100%;
        }
        .btn {
            background-color: #1976f2;
            color: white;
            border: none;
            padding: 10px;
            font-weight: 700;
            cursor: pointer;
            border-radius: 6px;
            transition: background-color 0.3s ease;
        }
        .btn:hover {
            background-color: #155dc1;
        }
        .errors {
            color: #cc0000;
            margin-bottom: 1rem;
        }
        .success {
            color: green;
            margin-bottom: 1rem;
            font-weight: 700;
        }
        nav a {
            display: inline-block;
            margin-bottom: 1rem;
            color: #1976f2;
            font-weight: 600;
            text-decoration: none;
        }
        nav a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<header>
    <h1>Área Administrador - Clientes</h1>
    <nav>
        <a href="admin_clientes.php">&larr; Voltar à lista</a>
    </nav>
</header>

<main>
    <h2>Editar Cliente</h2>

    <?php if (!empty($errors)): ?>
        <div class="errors">
            <ul>
                <?php foreach($errors as $err): ?>
                    <li><?= htmlspecialchars($err) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="success">Dados atualizados com sucesso!</div>
    <?php endif; ?>

    <form method="POST" action="">
        <label for="razao_social">Razão Social *</label>
        <input type="text" id="razao_social" name="razao_social" required value="<?= htmlspecialchars($cliente['razao_social']) ?>" />

        <label for="nome_fantasia">Nome Fantasia</label>
        <input type="text" id="nome_fantasia" name="nome_fantasia" value="<?= htmlspecialchars($cliente['nome_fantasia']) ?>" />

        <label for="email">E-mail *</label>
        <input type="email" id="email" name="email" required value="<?= htmlspecialchars($cliente['email']) ?>" />

        <label for="telefone">Telefone</label>
        <input type="text" id="telefone" name="telefone" value="<?= htmlspecialchars($cliente['telefone']) ?>" />

        <label for="login">Login *</label>
        <input type="text" id="login" name="login" required value="<?= htmlspecialchars($cliente['login']) ?>" />

        <button type="submit" class="btn">Atualizar Dados</button>
    </form>
</main>
</body>
</html>
