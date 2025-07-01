<?php
session_start();
require_once '../../Conexao.php';

// Verifica se o usuário está logado e é administrador (tipo 3)
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['tipoUsuario'] != 3) {
    header('Location: ../../usuario/login_view.php');
    exit;
}

// Valida o ID do cliente
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    header('Location: admin-clientes.php?status=erro_id');
    exit;
}

try {
    $conexao = new Conexao();
    $pdo = $conexao->getPdo();

    // Verifica se o cliente realmente existe com tipo_usuario = 1
    $verifica = $pdo->prepare("SELECT * FROM usuario WHERE id_usuario = :id AND tipo_usuario = 1");
    $verifica->execute([':id' => $id]);

    if ($verifica->rowCount() === 0) {
        header('Location: admin-clientes.php?status=cliente_nao_encontrado');
        exit;
    }

    // Tenta excluir o cliente
    $stmt = $pdo->prepare("DELETE FROM usuario WHERE id_usuario = :id AND tipo_usuario = 1");
    $stmt->execute([':id' => $id]);

    if ($stmt->rowCount() > 0) {
        header('Location: admin-clientes.php?status=excluido');
        exit;
    } else {
        // Algo impediu a exclusão (possivelmente dependências)
        header('Location: admin-clientes.php?status=erro_delete');
        exit;
    }

} catch (PDOException $e) {
    // Caso haja erro de integridade referencial (FK), capturar e redirecionar
    header('Location: admin-clientes.php?status=erro_delete');
    exit;
}
