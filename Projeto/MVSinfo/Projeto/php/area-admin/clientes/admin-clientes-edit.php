<?php
session_start();
require_once '../../conexao.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['tipoUsuario'] != 3) {
    header('Location: ../../usuario/login_view.php');
    exit;
}

$conexao = new conexao();
$pdo = $conexao->getPdo();

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
    header('Location: admin-clientes.php');
    exit;
}

$stmt = $pdo->prepare("SELECT id_usuario, razao_social, email, telefone, login FROM usuario WHERE id_usuario = :id AND tipo_usuario = 1");
$stmt->execute([':id' => $id]);
$cliente = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$cliente) {
    header('Location: admin-clientes.php');
    exit;
}

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $razao_social = trim($_POST['razao_social'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $telefone = trim($_POST['telefone'] ?? '');
    $login = trim($_POST['login'] ?? '');

    if (!$razao_social) $errors[] = 'Razão Social é obrigatória.';
    if (!$email) $errors[] = 'Email é obrigatório.';
    if (!$login) $errors[] = 'Login é obrigatório.';

    // Verifica se email ou login já existe para outro usuário
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM usuario WHERE (email = :email OR login = :login) AND id_usuario != :id");
    $stmt->execute([':email' => $email, ':login' => $login, ':id' => $id]);
    if ($stmt->fetchColumn() > 0) {
        $errors[] = 'Email ou login já estão em uso por outro usuário.';
    }

    if (empty($errors)) {
        $sql = "UPDATE usuario SET 
            razao_social = :razao_social,
            email = :email,
            telefone = :telefone,
            login = :login
            WHERE id_usuario = :id";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':razao_social' => $razao_social,
            ':email' => $email,
            ':telefone' => $telefone,
            ':login' => $login,
            ':id' => $id
        ]);

        $success = true;
        $cliente = array_merge($cliente, [
            'razao_social' => $razao_social,
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
            margin-left: 1rem;
            text-decoration: none;
            font-weight: 600;
        }

        nav a:hover {
            text-decoration: underline;
        }

        main {
            max-width: 700px;
            margin: 2rem auto;
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.08);
        }

        h2 {
            color: #1976f2;
            margin-bottom: 1.5rem;
            text-align: center;
        }

        label {
            font-weight: 600;
            display: block;
            margin-bottom: 0.5rem;
        }

        input[type="text"],
        input[type="email"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
            margin-bottom: 1rem;
        }

        .btn {
            background-color: #1976f2;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 6px;
            font-weight: 600;
            text-align: center;
            cursor: pointer;
            transition: background-color 0.3s ease;
            display: inline-block;
            width: 100%;
            text-decoration: none;
        }

        .btn:hover {
            background-color: #145ca8;
        }

        .alert {
            padding: 0.8rem 1rem;
            margin-bottom: 1rem;
            border-radius: 6px;
            font-weight: 600;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            text-align: center;
        }

        .form-actions {
            display: flex;
            gap: 10px;
            margin-top: 1.5rem;
        }

        .form-actions .btn {
            flex: 1;
        }
    </style>
</head>

<body>
    <header>
        <h1>Área Administrador - Clientes</h1>
        <nav>
            <a href="../area-admin.php">Menu</a>
            <a href="admin-clientes.php">Clientes</a>
            <a href="../../logout.php">Sair</a>
        </nav>
    </header>

    <main>
        <h2>Editar Cliente</h2>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <ul>
                    <?php foreach ($errors as $erro): ?>
                        <li><?= htmlspecialchars($erro) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success">✅ Cliente atualizado com sucesso!</div>
        <?php endif; ?>

        <form method="POST">
            <label for="razao_social">Razão Social *</label>
            <input type="text" id="razao_social" name="razao_social" required value="<?= htmlspecialchars($cliente['razao_social']) ?>">

            <label for="email">E-mail *</label>
            <input type="email" id="email" name="email" required value="<?= htmlspecialchars($cliente['email']) ?>">

            <label for="telefone">Telefone</label>
            <input type="text" id="telefone" name="telefone" value="<?= htmlspecialchars($cliente['telefone']) ?>">

            <label for="login">Login *</label>
            <input type="text" id="login" name="login" required value="<?= htmlspecialchars($cliente['login']) ?>">

            <div class="form-actions">
                <button type="submit" class="btn">Atualizar Dados</button>
                <a href="admin-clientes.php" class="btn">Voltar</a>
            </div>
        </form>
    </main>
</body>

</html>
