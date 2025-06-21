<?php
session_start();
require_once '../Conexao.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['tipoUsuario'] != 0) {
    header('Location: ../usuario/login_view.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conexao = new conexao();
    $pdo = $conexao->getPdo();

    // Captura de campos
    $login = trim($_POST['login'] ?? '');
    $senha = $_POST['senha'] ?? '';
    $confirmarSenha = $_POST['confirmarSenha'] ?? '';

    // Dados extras
    $telefone = $_POST['telefone'] ?? '';
    $email = $_POST['email'] ?? '';
    $razao_social = $_POST['razao_social'] ?? '';
    $nome_fantasia = $_POST['nome_fantasia'] ?? '';
    $cnpj = $_POST['cnpj'] ?? '';
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
    if (empty($login) || empty($senha) || ($senha !== $confirmarSenha)) {
        header('Location: admin-clientes-add.php?error=senha');
        exit;
    }

    $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

    try {
        $sql = "INSERT INTO usuario 
        (login, senha, tipo_usuario, telefone, email, razao_social, nome_fantasia, cnpj, inscricao_estadual, contato, cep, logradouro, numero, complemento, bairro, cidade, estado)
        VALUES
        (:login, :senha, 1, :telefone, :email, :razao_social, :nome_fantasia, :cnpj, :inscricao_estadual, :contato, :cep, :logradouro, :numero, :complemento, :bairro, :cidade, :estado)";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':login' => $login,
            ':senha' => $senhaHash,
            ':telefone' => $telefone,
            ':email' => $email,
            ':razao_social' => $razao_social,
            ':nome_fantasia' => $nome_fantasia,
            ':cnpj' => $cnpj,
            ':inscricao_estadual' => $inscricao_estadual,
            ':contato' => $contato,
            ':cep' => $cep,
            ':logradouro' => $logradouro,
            ':numero' => $numero,
            ':complemento' => $complemento,
            ':bairro' => $bairro,
            ':cidade' => $cidade,
            ':estado' => $estado
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
