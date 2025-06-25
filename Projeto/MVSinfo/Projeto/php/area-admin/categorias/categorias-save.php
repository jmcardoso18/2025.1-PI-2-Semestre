<?php
require_once '../../Conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $descricao = trim($_POST['descricao']);

    try {
        $conexao = new Conexao();
        $pdo = $conexao->getPDO();

        $sql = "INSERT INTO categoria (descricao) VALUES (:descricao)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':descricao', $descricao);
        $stmt->execute();

        header('Location: categorias.php');
        exit;
    } catch (PDOException $e) {
        echo "Erro ao salvar: " . $e->getMessage();
    }
} else {
    echo "Método inválido.";
}
?>
