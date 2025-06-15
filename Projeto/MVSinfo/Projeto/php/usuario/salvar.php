<?php
require_once '../Conexao.php';
require_once 'user.php';
require_once '../login/db.php';

$conexao = new conexao();
$pdo = $conexao->getPdo(); 
$usuario = new user($pdo);

$userDB = new db($pdo);
$tipoUsuario = $_POST['tipoUsuario'] === 0 ? 'cliente' : 'fornecedor';
$users = $userDB->getUsers($tipoUsuario);

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

$tipoUsuario = $_POST['tipoUsuario'];
$confirmarSenha = isset($_POST['confirmarSenha']) ? $_POST['confirmarSenha'] : null;
$senha = isset($_POST['senha']) ? $_POST['senha'] : null;



if($confirmarSenha !== $senha || ($senha === null || $confirmarSenha === null)){
      header('Location: ../../cadastro.php');
}
      $dados['cnpj'] = $_POST['cnpj_empresa'];
      $dados['razao_social'] = $_POST['razaoSocial'];
      $dados['nome_fantasia'] = $_POST ['nomeFantasia'];
      $dados['inscricao_estadual'] = $_POST['inscricaoEstadual'];
      $dados['contato'] = $_POST['nomeResponsavel'];
      $dados['telefone'] = $_POST['telefone'];
      $dados['email'] = $_POST['email'];
      $dados['tipo_usuario'] = $_POST['tipoUsuario']; // 0 para Cliente, 1 para Fornecedor e 2 para Admin
      $dados['cep'] = $_POST['cep'];
      $dados['logradouro'] = $_POST['logradouro'];
      $dados['numero'] = $_POST['numero'];
      $dados['complemento'] = $_POST['complemento'];
      $dados['bairro'] = $_POST['bairro'];
      $dados['cidade'] = $_POST['cidade'];
      $dados['estado'] = $_POST['estado'];
      $dados['login'] = $_POST['login'];
      $dados['senha'] = $_POST['senha'];

$usuario->inserir($dados);
header('Location: listar.php');
exit;

?>
