<?php
session_start();
require_once '../../conexao.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['tipoUsuario'] != 0) {
    header('Location: ../usuario/login_view.php');
    exit;
}

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
    header('Location: produtos.php?status=erro_id');
    exit;
}

$conexao = new conexao();
$pdo = $conexao->getPDO();

$sql = "DELETE FROM produtos WHERE codigo_produto = :id";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':id', $id);

if ($stmt->execute()) {
    header('Location: produtos.php?status=excluido');
    exit;
} else {
    header('Location: produtos.php?status=erro_delete');
    exit;
}
