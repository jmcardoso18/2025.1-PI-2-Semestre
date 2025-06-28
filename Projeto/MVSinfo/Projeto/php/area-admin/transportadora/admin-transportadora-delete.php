<?php
session_start();
require_once '../../Conexao.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['tipoUsuario'] != 0) {
    header('Location: ../../usuario/login_view.php');
    exit;
}

$conexao = new conexao();
$pdo = $conexao->getPDO();

$id = intval($_GET['id'] ?? 0);

if ($id > 0) {
    $stmt = $pdo->prepare("DELETE FROM transportadora WHERE id_transportadora = ?");
    $stmt->execute([$id]);
}

header('Location: admin-transportadora-list.php?status=excluido');
exit;
