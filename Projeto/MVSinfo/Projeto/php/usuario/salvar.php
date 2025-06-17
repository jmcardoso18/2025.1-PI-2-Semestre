<?php
require_once '../Conexao.php';
require_once 'user.php';
require_once '../login/db.php';

$conexao = new conexao();
$pdo = $conexao->getPdo(); 
$usuario = new user($pdo);
$userDB = new db($pdo);

$login = $_POST['login'];
$senha = isset($_POST['senha']) ? $_POST['senha'] : null;
$confirmarSenha = isset($_POST['confirmarSenha']) ? $_POST['confirmarSenha'] : null;

// Validar senhas
if($confirmarSenha !== $senha || $senha === null || $confirmarSenha === null){
    header('Location: cadastro.php');
    exit;
}

// Verificar se login já existe
$tipoUsuarioForm = $_POST['tipoUsuario'];

$tipoUsuarioTexto = $tipoUsuarioForm === 0 ? 'cliente' : 'fornecedor';
$users = $userDB->getUsers($tipoUsuarioTexto);

$encontrado = false;
if($users){
    foreach ($users as $user) {
        if ($user['login'] === $login && $user['senha'] === $senha) {
            $encontrado = true;
            break;
        }
    }
}

if($encontrado){
    echo 'Este nome de usuário já está em uso, por favor escolha outro!';
    die;
}

// Mapear o tipoUsuarioForm para o ID do banco
switch ($tipoUsuarioForm) {
    case 0:
        $dados['tipo_usuario'] = 1; // Cliente
        break;
    case 1:
        $dados['tipo_usuario'] = 2; // Fornecedor
        break;
    default:
        die('Tipo de usuário inválido.');
}

// Montar os dados
$dados['cnpj'] = $_POST['cnpj_empresa'];
$dados['razao_social'] = $_POST['razaoSocial'];
$dados['nome_fantasia'] = $_POST['nomeFantasia'];
$dados['inscricao_estadual'] = $_POST['inscricaoEstadual'];
$dados['contato'] = $_POST['nomeResponsavel'];
$dados['telefone'] = $_POST['telefone'];
$dados['email'] = $_POST['email'];
$dados['cep'] = $_POST['cep'];
$dados['logradouro'] = $_POST['logradouro'];
$dados['numero'] = $_POST['numero'];
$dados['complemento'] = $_POST['complemento'];
$dados['bairro'] = $_POST['bairro'];
$dados['cidade'] = $_POST['cidade'];
$dados['estado'] = $_POST['estado'];
$dados['login'] = $login;
$dados['senha'] = $senha;

// Inserir no banco
$usuario->inserir($dados);

header('Location: login_view.php');
exit;
?>
