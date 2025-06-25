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

// Validação de tipo de usuário permitido (somente Admin=0, Cliente=1 ou Fornecedor=2)
if (!in_array($tipoUsuarioId, [0,1,2])) {
    header("Location: ../usuario/login_view.php?error=tipo_usuario");
    exit;
}

// Validação de campos obrigatórios
if (empty($login) || empty($senha)) {
    header("Location: ../usuario/login_view.php?error=login_vazio");
    exit;
}

// Buscar usuário específico por login e tipo de usuário
$stmt = $pdo->prepare("SELECT id_usuario, login, senha, tipo_usuario FROM usuario WHERE login = :login AND tipo_usuario = :tipo");
$stmt->execute([
    ':login' => $login,
    ':tipo' => $tipoUsuarioId
]);

$usuarioEncontrado = $stmt->fetch(PDO::FETCH_ASSOC);

if ($usuarioEncontrado) {
    // Verificar senha
    if (password_verify($senha, $usuarioEncontrado['senha'])) {
    
        // Salvar informações importantes na sessão
        $_SESSION['loggedin'] = true;
        $_SESSION['usuario'] = $usuarioEncontrado['login'];
        $_SESSION['tipoUsuario'] = $usuarioEncontrado['tipo_usuario'];
        $_SESSION['id_usuario'] = $usuarioEncontrado['id_usuario'];

        // Redireciona por tipo de usuário
        switch ($usuarioEncontrado['tipo_usuario']) {
            case 0: // Admin
                header("Location: ../area-admin/area-admin.php");
                break;
            case 1: // Cliente
                header("Location: ../area-cliente/area-cliente.php");
                break;
            case 2: // Fornecedor
                header("Location: ../area-fornecedor/area-fornecedor.php");
                break;
        }
        exit;

    } else {
        // Senha incorreta
        header("Location: ../usuario/login_view.php?error=senha_incorreta");
        exit;
    }
} else {
    // Usuário não encontrado
    header("Location: ../usuario/login_view.php?error=usuario_nao_encontrado");
    exit;
}
?>
