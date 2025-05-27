<?php
require_once '../conexao.php';

class Cliente {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function inserir($dados) {
        $sql = "INSERT INTO clientes (
            cpf, nomeCompleto, dataNascimento, telefone, email, 
            cep, logradouro, numero, complemento, bairro, cidade, estado, 
            razaoSocial, nomeFantasia, cnpj, inscricaoEstadual, nomeResponsavel
        ) VALUES (
            :cpf, :nomeCompleto, :dataNascimento, :telefone, :email, 
            :cep, :logradouro, :numero, :complemento, :bairro, :cidade, :estado, 
            :razaoSocial, :nomeFantasia, :cnpj, :inscricaoEstadual, :nomeResponsavel
        )";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':cpf' => $dados['cpf'],
            ':nomeCompleto' => $dados['nomeCompleto'],
            ':dataNascimento' => $dados['dataNascimento'],
            ':telefone' => $dados['telefone'],
            ':email' => $dados['email'],
            ':cep' => $dados['cep'],
            ':logradouro' => $dados['logradouro'],
            ':numero' => $dados['numero'],
            ':complemento' => $dados['complemento'],
            ':bairro' => $dados['bairro'],
            ':cidade' => $dados['cidade'],
            ':estado' => $dados['estado'],
            ':razaoSocial' => $dados['razaoSocial'],
            ':nomeFantasia' => $dados['nomeFantasia'],
            ':cnpj' => $dados['cnpj'],
            ':inscricaoEstadual' => $dados['inscricaoEstadual'],
            ':nomeResponsavel' => $dados['nomeResponsavel']
        ]);
    }

    // Demais métodos (alterar, listar, deletar) aqui...
}
?>
