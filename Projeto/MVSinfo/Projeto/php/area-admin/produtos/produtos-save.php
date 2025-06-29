<?php
session_start();
require_once '../../conexao.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['tipoUsuario'] != 3) {
    header('Location: ../usuario/login_view.php');
    exit;
}

$conexao = new conexao();
$pdo = $conexao->getPDO();

// Receber dados do formulário
$descricao = $_POST['descricao'] ?? '';
$cod_categoria = $_POST['cod_categoria'] ?? null;
$ncm = $_POST['ncm'] ?? null;
$marca = $_POST['marca'] ?? null;
$unidade_medida = $_POST['unidade_medida'] ?? null;
$preco_custo_unidade = $_POST['preco_custo_unidade'] ?? 0;

// Validar obrigatoriedade
if (empty($descricao) || empty($cod_categoria) || empty($preco_custo_unidade)) {
    die("Por favor, preencha os campos obrigatórios.");
}

// Inserir no banco
try {
    $stmt = $pdo->prepare("INSERT INTO produtos (descricao, cod_categoria, ncm, marca, unidade_medida, preco_custo_unidade) VALUES (:descricao, :cod_categoria, :ncm, :marca, :unidade_medida, :preco_custo_unidade)");
    $stmt->bindValue(':descricao', $descricao);
    $stmt->bindValue(':cod_categoria', $cod_categoria, PDO::PARAM_INT);
    $stmt->bindValue(':ncm', $ncm);
    $stmt->bindValue(':marca', $marca);
    $stmt->bindValue(':unidade_medida', $unidade_medida);
    $stmt->bindValue(':preco_custo_unidade', $preco_custo_unidade);
    $stmt->execute();

    header('Location: produtos.php?msg=Produto cadastrado com sucesso!');
    exit;
} catch (PDOException $e) {
    die("Erro ao salvar produto: " . $e->getMessage());
}
