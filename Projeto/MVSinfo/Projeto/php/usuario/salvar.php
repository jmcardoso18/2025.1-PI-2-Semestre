<?php
require_once '../Conexao.php';
require_once 'user.php';
require_once '../login/db.php';

$conexao = new conexao();
$pdo = $conexao->getPdo();
$usuario = new user($pdo);
$userDB = new db($pdo);

// Coleta de dados do formulário
$login = trim($_POST['login'] ?? '');
$senha = $_POST['senha'] ?? '';
$confirmarSenha = $_POST['confirmarSenha'] ?? '';
$tipoUsuarioId = (int)($_POST['tipoUsuario'] ?? -1);

// Validação básica
if (empty($login) || empty($senha) || empty($confirmarSenha)) {
    die('Preencha todos os campos obrigatórios.');
}

if ($senha !== $confirmarSenha) {
    die('As senhas não conferem.');
}

if (!in_array($tipoUsuarioId, [0, 1, 2])) {
    die('Tipo de usuário inválido.');
}

// Verificar se login já existe
$usuariosDoTipo = $userDB->getUsersByTipoUsuarioId($tipoUsuarioId);
foreach ($usuariosDoTipo as $userExistente) {
    if ($userExistente['login'] === $login) {
        die('Este nome de usuário já está em uso para este tipo de usuário.');
    }
}

// Monta os dados para inserir
$dados = [
    'cnpj' => $_POST['cnpj_empresa'] ?? null,
    'razao_social' => $_POST['razaoSocial'] ?? null,
    'inscricao_estadual' => $_POST['inscricaoEstadual'] ?? null,
    'contato' => $_POST['nomeResponsavel'] ?? null,
    'telefone' => $_POST['telefone'] ?? null,
    'email' => $_POST['email'] ?? null,
    'tipo_usuario' => $tipoUsuarioId,
    'cep' => $_POST['cep'] ?? null,
    'logradouro' => $_POST['logradouro'] ?? null,
    'numero' => $_POST['numero'] ?? null,
    'complemento' => $_POST['complemento'] ?? null,
    'bairro' => $_POST['bairro'] ?? null,
    'cidade' => $_POST['cidade'] ?? null,
    'estado' => $_POST['estado'] ?? null,
    'login' => $login,
    'senha' => password_hash($senha, PASSWORD_DEFAULT)
];

// Inserir
try {
    $usuario->inserir($dados);
    header('Location: ../usuario/login_view.php');
    exit;
} catch (PDOException $e) {
    die("Erro ao inserir usuário: " . $e->getMessage());
}
?>
