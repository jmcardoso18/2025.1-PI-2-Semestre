<?php
require_once '../conexao.php';
require_once 'Cliente.php';

$dados = array(
    'cpf' => $_POST['cpf'],
    'nomeCompleto' => $_POST['nomeCompleto'],
    'dataNascimento' => $_POST['dataNascimento'],
    'telefone' => $_POST['telefone'],
    'email' => $_POST['email'],
    'cep' => $_POST['cep'],
    'logradouro' => $_POST['logradouro'],
    'numero' => $_POST['numero'],
    'complemento' => $_POST['complemento'],
    'bairro' => $_POST['bairro'],
    'cidade' => $_POST['cidade'],
    'estado' => $_POST['estado'],
    'razaoSocial' => $_POST['razaoSocial'],
    'nomeFantasia' => $_POST['nomeFantasia'],
    'cnpj' => $_POST['cnpj'],
    'inscricaoEstadual' => $_POST['inscricaoEstadual'],
    'nomeResponsavel' => $_POST['nomeResponsavel']
);

$cliente = new Cliente($pdo);
$cliente->inserir($dados);

header('Location: listar.php');
exit;
?>
