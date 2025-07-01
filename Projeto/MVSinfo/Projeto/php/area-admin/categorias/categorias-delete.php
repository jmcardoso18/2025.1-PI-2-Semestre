<?php
require_once '../../Conexao.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if ($id) {
    try {
        $conexao = new Conexao();
        $pdo = $conexao->getPDO();

        $sql = "DELETE FROM categoria WHERE id_categoria = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        header('Location: categorias.php?status=excluido');
        exit;
    } catch (PDOException $e) {
        echo "Erro ao excluir: " . $e->getMessage();
    }
} else {
    echo "ID inválido.";
}
