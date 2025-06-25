<?php
require_once '../../Conexao.php';

$id = $_GET['id'] ?? '';

if ($id) {
    try {
        $conexao = new Conexao();
        $pdo = $conexao->getPDO();

        $sql = "DELETE FROM categoria WHERE cod_categoria = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        header('Location: categorias.php');
        exit;
    } catch (PDOException $e) {
        echo "Erro ao excluir: " . $e->getMessage();
    }
} else {
    echo "ID inválido.";
}
?>
