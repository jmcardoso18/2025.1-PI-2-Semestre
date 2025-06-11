<?php
require_once '../conexao.php';
require_once 'usuario.php';

$dados = array(
     ':id_usuario' => $dados['id_usuario'],
     ':cnpj' => $dados['cnpj'],
     ':razao_social' => $dados['razao_social'],
     ':nome_fantasia' => $dados  ['nome_fantasia'],
     ':inscricao_estadual' => $dados['inscricao_estadual'],
     ':contato' => $dados['contato'],
     ':telefone' => $dados['telefone'],
     ':email' => $dados['email'],
     ':tipo_usuario' => $dados['tipo_usuario'],
     ':cep' => $dados['cep'],
     ':logradouro' => $dados['logradouro'],
     ':numero' => $dados['numero'],
     ':complemento' => $dados['complemento'],
     ':bairro' => $dados['bairro'],
     ':cidade' => $dados['cidade'],
      ':estado' => $dados['estado'],
      ':login' => $dados['login'],
      ':senha' => $dados['senha']
);

$cliente = new usuario($pdo);
$cliente->inserir($dados);

header('Location: listar.php');
exit;
?>
