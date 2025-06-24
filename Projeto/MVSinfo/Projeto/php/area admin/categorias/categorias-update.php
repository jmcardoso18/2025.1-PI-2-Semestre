<?php
require_once '../../Conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $descricao = trim($_POST['descricao']);

    try {
        $conexao = new Conexao();
        $pdo = $conexao->getPDO();

        $sql = "UPDATE categoria SET descricao = :descricao WHERE cod_categoria = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':descricao', $descricao);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        header('Location: categorias.php');
        exit;
    } catch (PDOException $e) {
        echo "Erro ao atualizar: " . $e->getMessage();
    }
} else {
    echo "Método inválido.";
}
?>
