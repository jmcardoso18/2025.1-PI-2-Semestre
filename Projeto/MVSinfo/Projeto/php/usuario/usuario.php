<?php
require_once '../conexao.php';

class Cliente {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function inserir($dados) {
        $sql = "INSERT INTO usuario (
            id_usuario, cnpj, razao_social, nome_fantasia,
            inscricao_estadual,
            contato, 
            telefone, email, tipo_usuario, cep, logradouro, numero, complemento, 
            bairro, cidade, estado, login, senha
        ) VALUES (
            :id_usuario, :cnpj, :razao_social, :nome_fantasia,
            :inscricao_estadual,
            :contato, 
            :telefone, :email, :tipo_usuario, :cep, :logradouro, :numero, :complemento, 
            :bairro, :cidade, :estado, :login, :senha
        )";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':id_usuario' => $dados['id_usuario'],
            ':cnpj' => $dados['cnpj'],
            ':razao_social' => $dados['razao_social'],
            ':nome_fantasia' => $dados['nome_fantasia'],
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
        ]);
    }
}
?>
