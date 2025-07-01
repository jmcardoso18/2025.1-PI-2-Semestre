<?php
require_once '../../Conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $descricao = trim($_POST['descricao'] ?? '');

    if (empty($id) || empty($descricao)) {
        echo "Dados inválidos. Verifique e tente novamente.";
        exit;
    }

    try {
        $conexao = new Conexao();
        $pdo = $conexao->getPDO();

        $sql = "UPDATE categoria SET descricao = :descricao WHERE id_categoria = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':descricao', $descricao);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        header('Location: categorias.php?status=editado');
        exit;
    } catch (PDOException $e) {
        echo "Erro ao atualizar: " . $e->getMessage();
        exit;
    }
} else {
    echo "Método inválido.";
    exit;
}
