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

    // Dados extras
    $cnpj = $_POST['cnpj'] ?? '';
    $razao_social = $_POST['razao_social'] ?? '';
    $telefone = $_POST['telefone'] ?? '';
    $email = $_POST['email'] ?? '';
    $inscricao_estadual = $_POST['inscricao_estadual'] ?? '';
    $contato = $_POST['contato'] ?? '';
    $cep = $_POST['cep'] ?? '';
    $logradouro = $_POST['logradouro'] ?? '';
    $numero = $_POST['numero'] ?? '';
    $complemento = $_POST['complemento'] ?? '';
    $bairro = $_POST['bairro'] ?? '';
    $cidade = $_POST['cidade'] ?? '';
    $estado = $_POST['estado'] ?? '';

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
        $sql = "INSERT INTO usuario 
                (cnpj, razao_social, inscricao_estadual, contato, telefone, email, tipo_usuario, cep, logradouro, numero, complemento, bairro, cidade, estado, login, senha)
                VALUES (:cnpj, :razao_social, :inscricao_estadual, :contato, :telefone, :email, :tipo_usuario, :cep, :logradouro, :numero, :complemento, :bairro, :cidade, :estado, :login, :senha)";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':cnpj' => $cnpj,
            ':razao_social' => $razao_social,
            ':inscricao_estadual' => $inscricao_estadual,
            ':contato' => $contato,
            ':telefone' => $telefone,
            ':email' => $email,
            ':tipo_usuario' => 1,
            ':cep' => $cep,
            ':logradouro' => $logradouro,
            ':numero' => $numero,
            ':complemento' => $complemento,
            ':bairro' => $bairro,
            ':cidade' => $cidade,
            ':estado' => $estado,
            ':login' => $login,
            ':senha' => $senhaHash
        ]);

        header('Location: admin-clientes.php?status=adicionado');
        exit;

    } catch (PDOException $e) {
        // Melhorando a mensagem de erro para debug
        error_log("Erro ao salvar cliente: " . $e->getMessage());
        header('Location: admin-clientes-add.php?error=db');
        exit;
    }
} else {
    header('Location: admin-clientes-add.php');
    exit;
}
?>