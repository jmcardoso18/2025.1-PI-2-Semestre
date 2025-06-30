<?php
session_start();
require_once '../../Conexao.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['tipoUsuario'] != 3) {
    header('Location: ../usuario/login_view.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: produtos.php');
    exit;
}

// Receber dados do formulário
$id_produto = (int)($_POST['id_produto'] ?? 0);
$descricao = trim($_POST['descricao'] ?? '');
$fk_categoria_id_categoria = (int)($_POST['fk_categoria_id_categoria'] ?? 0);
$ncm = trim($_POST['ncm'] ?? '');
$marca = trim($_POST['marca'] ?? '');
$unidade_medida = trim($_POST['unidade_medida'] ?? '');

// Validação básica (preço custo não está no seu esquema, então removi)
// Caso tenha, reintroduza aqui.

if ($id_produto <= 0 || $descricao === '' || $fk_categoria_id_categoria <= 0) {
    header('Location: produtos-editar.php?id=' . $id_produto . '&status=erro');
    exit;
}

$conexao = new Conexao();
$pdo = $conexao->getPDO();

try {
    $sql = "UPDATE produtos SET 
                descricao = :descricao,
                fk_categoria_id_categoria = :fk_categoria_id_categoria,
                ncm = :ncm,
                marca = :marca,
                unidade_medida = :unidade_medida
            WHERE id_produto = :id_produto";

    $stmt = $pdo->prepare($sql);

    $stmt->bindValue(':descricao', $descricao);
    $stmt->bindValue(':fk_categoria_id_categoria', $fk_categoria_id_categoria, PDO::PARAM_INT);
    $stmt->bindValue(':ncm', $ncm);
    $stmt->bindValue(':marca', $marca);
    $stmt->bindValue(':unidade_medida', $unidade_medida);
    $stmt->bindValue(':id_produto', $id_produto, PDO::PARAM_INT);

    $stmt->execute();

    header('Location: produtos.php?status=editado');
    exit;

} catch (PDOException $e) {
    // Para debug, pode mostrar o erro (remova em produção)
    echo "Erro ao atualizar produto: " . $e->getMessage();
    // header('Location: produtos-editar.php?id=' . $id_produto . '&status=erro');
    exit;
}
