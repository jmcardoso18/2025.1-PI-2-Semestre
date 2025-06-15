<?php

require('./db.php');
require('../Conexao.php');


$conexao = new conexao();
$pdo = $conexao->getPdo();

$tipoUsuarioId = $_POST['tipoUsuario'];

switch($tipoUsuarioId){
    case 0:
        $tipoUsuario = 'cliente';
        break;
    case 1:
        $tipoUsuario = 'fornecedor';
        break;
    case 2:
        $tipoUsuario = 'admin';
        break;
    default:
        return [];
        }

$db = new db($pdo);
$users = $db->getUsers($tipoUsuario);
$login = $_POST['login'] ?? '';
$senha = $_POST['senha'] ?? '';

$encontrado = false;

foreach ($users as $user) {

    if ($user['login'] === $login && $user['senha'] === $senha) {
        $encontrado = true;
        break;
    }
}

if ($encontrado) {
    session_start();
    echo "Login válido!";
    $_SESSION['tipoUsuario'] = $tipoUsuario;
    $_SESSION['usuario'] = $login;
    $_SESSION['loggedin'] = true;

    switch($tipoUsuarioId){
       case 0:
            header("Location: ../../area-cliente.php");
            exit;
        case 1:
            header("Location: ../../area-fornecedor.php");
            exit;
        case 2:
            header("Location: ../../area-admin.php");
            exit;
    }
} else {
    echo "Login ou senha inválidos!";
}
