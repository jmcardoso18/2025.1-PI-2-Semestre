<?php
require_once '../../Conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $descricao = trim($_POST['descricao'] ?? '');

    if (empty($descricao)) {
        echo "O campo descrição é obrigatório.";
        exit;
    }

    try {
        $conexao = new Conexao();
        $pdo = $conexao->getPDO();

        $sql = "INSERT INTO categoria (descricao) VALUES (:descricao)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':descricao', $descricao);
        $stmt->execute();

        header('Location: categorias.php?status=sucesso');
        exit;
    } catch (PDOException $e) {
        echo "Erro ao salvar categoria: " . $e->getMessage();
        exit;
    }
} else {
    echo "Método inválido.";
    exit;
}
