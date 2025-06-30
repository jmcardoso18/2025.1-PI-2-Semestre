<?php
session_start();
require_once '../../Conexao.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['tipoUsuario'] != 3) {
    header('Location: ../../usuario/login_view.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conexao = new conexao();
    $pdo = $conexao->getPdo();

    // Captura de campos do formulário
    $login = trim($_POST['login'] ?? '');
    $senha = $_POST['senha'] ?? '';
    $confirmarSenha = $_POST['confirmarSenha'] ?? '';
    $telefone = trim($_POST['telefone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $razao_social = trim($_POST['razao_social'] ?? '');

    // Validação básica
    if (empty($login) || empty($senha) || empty($confirmarSenha) || empty($email) || empty($razao_social)) {
        header('Location: admin-clientes-add.php?error=campos_obrigatorios');
        exit;
    }

    if ($senha !== $confirmarSenha) {
        header('Location: admin-clientes-add.php?error=senha_incorreta');
        exit;
    }

    // Verificar se login ou email já existe
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM usuario WHERE login = :login OR email = :email");
    $stmt->execute([':login' => $login, ':email' => $email]);
    if ($stmt->fetchColumn() > 0) {
        header('Location: admin-clientes-add.php?error=login_email_existente');
        exit;
    }

    $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

    try {
        $sql = "INSERT INTO usuario (login, senha, tipo_usuario, telefone, email, razao_social)
                VALUES (:login, :senha, 1, :telefone, :email, :razao_social)";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':login' => $login,
            ':senha' => $senhaHash,
            ':telefone' => $telefone,
            ':email' => $email,
            ':razao_social' => $razao_social
        ]);

        header('Location: admin-clientes.php?status=adicionado');
        exit;

    } catch (PDOException $e) {
        echo "Erro ao salvar cliente: " . $e->getMessage();
    }
} else {
    echo "Método inválido.";
    exit;
}
?>
