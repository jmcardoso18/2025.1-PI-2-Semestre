<?php
session_start();
require_once '../../Conexao.php';

// Verifica se é admin
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['tipoUsuario'] != 3) {
    header('Location: ../../usuario/login_view.php');
    exit;
}

// Verifica se o ID do fornecedor foi enviado
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id <= 0) {
    header('Location: admin-fornecedores.php?status=erro_id');
    exit;
}

$conexao = new conexao();
$pdo = $conexao->getPDO();

try {
    $stmt = $pdo->prepare("DELETE FROM usuario WHERE id_usuario = :id AND tipo_usuario = 2");
    $stmt->execute([':id' => $id]);

    if ($stmt->rowCount() > 0) {
        header('Location: admin-fornecedores.php?status=excluido');
        exit;
    } else {
        header('Location: admin-fornecedores.php?status=fornecedor_nao_encontrado');
        exit;
    }

} catch (PDOException $e) {
    header('Location: admin-fornecedores.php?status=erro_delete');
    exit;
}
?>
