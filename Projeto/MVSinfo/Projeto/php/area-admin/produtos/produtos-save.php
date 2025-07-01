<?php
session_start();
require_once '../../conexao.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['tipoUsuario'] != 3) {
    header('Location: ../usuario/login_view.php');
    exit;
}

$conexao = new Conexao();
$pdo = $conexao->getPDO();

// Receber dados do formulário
$descricao = trim($_POST['descricao'] ?? '');
$fk_categoria_id_categoria = (int)($_POST['fk_categoria_id_categoria'] ?? 0);
$ncm = trim($_POST['ncm'] ?? '');
$marca = trim($_POST['marca'] ?? '');
$unidade_medida = trim($_POST['unidade_medida'] ?? '');

// Validação básica
if (empty($descricao) || $fk_categoria_id_categoria <= 0) {
    die("Por favor, preencha os campos obrigatórios.");
}

try {
    $sql = "INSERT INTO produtos (descricao, fk_categoria_id_categoria, ncm, marca, unidade_medida)
            VALUES (:descricao, :fk_categoria_id_categoria, :ncm, :marca, :unidade_medida)";
    
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':descricao', $descricao);
    $stmt->bindValue(':fk_categoria_id_categoria', $fk_categoria_id_categoria, PDO::PARAM_INT);
    $stmt->bindValue(':ncm', $ncm);
    $stmt->bindValue(':marca', $marca);
    $stmt->bindValue(':unidade_medida', $unidade_medida);
    
    $stmt->execute();

    header('Location: produtos.php?status=adicionado');
    exit;

} catch (PDOException $e) {
    die("Erro ao salvar produto: " . $e->getMessage());
}
