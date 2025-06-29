<?php
session_start();
require_once '../../conexao.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['tipoUsuario'] != 3) {
    header('Location: ../../usuario/login_view.php');
    exit;
}

$conexao = new conexao();
$pdo = $conexao->getPDO();

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
    header('Location: admin-fornecedores.php');
    exit;
}

// Busca dados atuais
$stmt = $pdo->prepare("SELECT * FROM usuario WHERE id_usuario = :id AND tipo_usuario = 2");
$stmt->execute([':id' => $id]);
$fornecedor = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$fornecedor) {
    header('Location: admin-fornecedores.php');
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

        // Atualiza a variável fornecedor para refletir as mudanças no formulário
        $fornecedor = array_merge($fornecedor, [
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
    <title>Editar fornecedor - Área Admin</title>
    <style>
        body {
            background-color: #f5f7fa;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        header {
            background-color: #1976f2;
            color: white;
            padding: 1rem 0;
            text-align: center;
            font-weight: 700;
            font-size: 1.5rem;
            box-shadow: 0 2px 5px rgb(0 0 0 / 0.1);
        }
        nav {
            max-width: 700px;
            margin: 20px auto 0;
            padding: 0 1rem;
        }
        nav a {
            color: #1976f2;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            margin-bottom: 1rem;
        }
        nav a:hover {
            text-decoration: underline;
        }
        main {
            max-width: 700px;
            background: white;
            margin: 1rem auto 3rem;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 0 10px rgb(0 0 0 / 0.1);
        }
        h2 {
            color: #1976f2;
            margin-bottom: 2rem;
            text-align: center;
            font-weight: 700;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }
        label {
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        input[type="text"],
        input[type="email"] {
            padding: 10px;
            font-size: 1rem;
            border: 1px solid #ddd;
            border-radius: 6px;
            width: 100%;
            box-sizing: border-box;
        }
        .btn {
            background-color: #1976f2;
            color: white;
            border: none;
            padding: 12px;
            font-weight: 700;
            cursor: pointer;
            border-radius: 6px;
            transition: background-color 0.3s ease;
            width: 100%;
        }
        .btn:hover {
            background-color: #155dc1;
        }
        .errors {
            background: #ffdddd;
            border: 1px solid #cc0000;
            color: #cc0000;
            padding: 1rem;
            border-radius: 6px;
            margin-bottom: 1rem;
        }
        .errors ul {
            margin: 0;
            padding-left: 1.2rem;
        }
        .success {
            background: #ddffdd;
            border: 1px solid green;
            color: green;
            padding: 1rem;
            border-radius: 6px;
            margin-bottom: 1rem;
            font-weight: 700;
            text-align: center;
        }
    </style>
</head>
<body>
<header>
    Área Administrador - Fornecedores
</header>

<main>
    <h2>Editar Fornecedor</h2>

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
    <input type="text" id="razao_social" name="razao_social" required value="<?= htmlspecialchars($fornecedor['razao_social']) ?>" />

    <label for="nome_fantasia">Nome Fantasia</label>
    <input type="text" id="nome_fantasia" name="nome_fantasia" value="<?= htmlspecialchars($fornecedor['nome_fantasia']) ?>" />

    <label for="email">E-mail *</label>
    <input type="email" id="email" name="email" required value="<?= htmlspecialchars($fornecedor['email']) ?>" />

    <label for="telefone">Telefone</label>
    <input type="text" id="telefone" name="telefone" value="<?= htmlspecialchars($fornecedor['telefone']) ?>" />

    <label for="login">Login *</label>
    <input type="text" id="login" name="login" required value="<?= htmlspecialchars($fornecedor['login']) ?>" />

    <div style="display: flex; gap: 10px; margin-top: 1rem;">
        <button type="submit" class="btn" style="flex: 1;">Atualizar Dados</button>
        <a href="admin-fornecedores.php" class="btn" style="flex: 1; text-align: center; line-height: 38px; text-decoration: none; color: white; border-radius: 6px;">Voltar</a>
    </div>
</form>
</main>
</body>
</html>
