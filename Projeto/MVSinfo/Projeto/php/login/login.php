<?php
session_start();

require_once './db.php';
require_once '../Conexao.php';

$conexao = new conexao();
$pdo = $conexao->getPdo();
$db = new db($pdo);

$tipoUsuarioId = (int)($_POST['tipoUsuario'] ?? -1);
$login = trim($_POST['login'] ?? '');
$senha = $_POST['senha'] ?? '';

if (!in_array($tipoUsuarioId, [0,1,2])) {
    header("Location: ../usuario/login_view.php?error=tipo_usuario");
    exit;
}

if (empty($login) || empty($senha)) {
    header("Location: ../usuario/login_view.php?error=login_vazio");
    exit;
}

// Buscar usuários daquele tipo
$users = $db->getUsersByTipoUsuarioId($tipoUsuarioId);

$usuarioEncontrado = null;
foreach ($users as $user) {
    if ($user['login'] === $login) {
        $usuarioEncontrado = $user;
        break;
    }
}

if ($usuarioEncontrado) {
    if (password_verify($senha, $usuarioEncontrado['senha'])) {
        $_SESSION['loggedin'] = true;
        $_SESSION['usuario'] = $login;
        $_SESSION['tipoUsuario'] = $tipoUsuarioId;

        switch ($tipoUsuarioId) {
            case 0:
                header("Location: /Projeto/MVSinfo/Projeto/area-admin.php");
                break;
            case 1:
                header("Location: /Projeto/MVSinfo/Projeto/area-cliente.php");
                break;
            case 2:
                header("Location: /Projeto/MVSinfo/Projeto/area-fornecedor.php");
                break;
        }
        exit;
    } else {
        header("Location: ../usuario/login_view.php?error=senha_incorreta");
        exit;
    }
} else {
    header("Location: ../usuario/login_view.php?error=usuario_nao_encontrado");
    exit;
}
?>
