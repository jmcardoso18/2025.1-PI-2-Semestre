<?php
session_start();
require_once '../../conexao.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['tipoUsuario'] != 0) {
    header('Location: ../usuario/login_view.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: produtos.php');
    exit;
}

// Receber dados do form
$codigo_produto = (int)($_POST['codigo_produto'] ?? 0);
$descricao = trim($_POST['descricao'] ?? '');
$cod_categoria = (int)($_POST['cod_categoria'] ?? 0);
$ncm = trim($_POST['ncm'] ?? '');
$marca = trim($_POST['marca'] ?? '');
$unidade_medida = trim($_POST['unidade_medida'] ?? '');
$preco_custo_unidade = str_replace(',', '.', $_POST['preco_custo_unidade'] ?? '0');

if ($codigo_produto <= 0 || $descricao === '' || $cod_categoria <= 0 || $preco_custo_unidade <= 0) {
    header('Location: produtos-editar.php?id=' . $codigo_produto . '&status=erro');
    exit;
}

$conexao = new conexao();
$pdo = $conexao->getPDO();

try {
    $sql = "UPDATE produtos SET descricao = :descricao, cod_categoria = :cod_categoria, ncm = :ncm, marca = :marca, unidade_medida = :unidade_medida, preco_custo_unidade = :preco_custo_unidade WHERE codigo_produto = :codigo_produto";
    $stmt = $pdo->prepare($sql);

    $stmt->bindValue(':descricao', $descricao);
    $stmt->bindValue(':cod_categoria', $cod_categoria, PDO::PARAM_INT);
    $stmt->bindValue(':ncm', $ncm);
    $stmt->bindValue(':marca', $marca);
    $stmt->bindValue(':unidade_medida', $unidade_medida);
    $stmt->bindValue(':preco_custo_unidade', $preco_custo_unidade);
    $stmt->bindValue(':codigo_produto', $codigo_produto, PDO::PARAM_INT);

    $stmt->execute();

    header('Location: produtos.php?status=editado');
    exit;
} catch (PDOException $e) {
    // Para debugging, remova em produção:
    echo "Erro ao atualizar: " . $e->getMessage();
    //header('Location: produtos-editar.php?id=' . $codigo_produto . '&status=erro');
    exit;
}
