<?php
session_start();
require_once '../../conexao.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['tipoUsuario'] != 3) {
    header('Location: ../usuario/login_view.php');
    exit;
}

// Validação do ID
$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
    header('Location: produtos.php?status=erro_id');
    exit;
}

$conexao = new Conexao();
$pdo = $conexao->getPDO();

$sql = "DELETE FROM produtos WHERE id_produto = :id";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':id', $id, PDO::PARAM_INT);

if ($stmt->execute()) {
    header('Location: produtos.php?status=excluido');
    exit;
} else {
    header('Location: produtos.php?status=erro_delete');
    exit;
}
