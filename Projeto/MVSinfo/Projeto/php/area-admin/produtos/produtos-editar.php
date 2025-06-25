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

// Buscar dados do produto
$sql = "SELECT * FROM produtos WHERE codigo_produto = :id";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$produto = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$produto) {
    echo "Produto não encontrado.";
    exit;
}

// Buscar categorias para popular select
$sqlCategorias = "SELECT cod_categoria, descricao FROM categoria ORDER BY descricao ASC";
$stmtCat = $pdo->prepare($sqlCategorias);
$stmtCat->execute();
$categorias = $stmtCat->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Editar Produto</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
<style>
    body { background-color: #f5f7fa; }
    .container { max-width: 700px; margin: 40px auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1);}
    h2 { color: #1976f2; margin-bottom: 20px; text-align: center; }
</style>
</head>
<body>
<div class="container">
    <h2>Editar Produto</h2>
    <form action="produtos-update.php" method="POST">
        <input type="hidden" name="codigo_produto" value="<?= htmlspecialchars($produto['codigo_produto']) ?>" />
        
        <div class="mb-3">
            <label for="descricao" class="form-label">Descrição</label>
            <input type="text" name="descricao" id="descricao" class="form-control" required value="<?= htmlspecialchars($produto['descricao']) ?>" />
        </div>
        
        <div class="mb-3">
            <label for="cod_categoria" class="form-label">Categoria</label>
            <select name="cod_categoria" id="cod_categoria" class="form-select" required>
                <option value="">-- Selecione a categoria --</option>
                <?php foreach($categorias as $categoria): ?>
                    <option value="<?= $categoria['cod_categoria'] ?>" <?= ($categoria['cod_categoria'] == $produto['cod_categoria']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($categoria['descricao']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="mb-3">
            <label for="ncm" class="form-label">NCM</label>
            <input type="text" name="ncm" id="ncm" class="form-control" value="<?= htmlspecialchars($produto['ncm']) ?>" />
        </div>
        
        <div class="mb-3">
            <label for="marca" class="form-label">Marca</label>
            <input type="text" name="marca" id="marca" class="form-control" value="<?= htmlspecialchars($produto['marca']) ?>" />
        </div>
        
        <div class="mb-3">
            <label for="unidade_medida" class="form-label">Unidade de Medida</label>
            <input type="text" name="unidade_medida" id="unidade_medida" class="form-control" value="<?= htmlspecialchars($produto['unidade_medida']) ?>" />
        </div>
        
        <div class="mb-3">
            <label for="preco_custo_unidade" class="form-label">Preço de Custo por Unidade</label>
            <input type="number" step="0.01" name="preco_custo_unidade" id="preco_custo_unidade" class="form-control" required value="<?= htmlspecialchars($produto['preco_custo_unidade']) ?>" />
        </div>
        
        <div class="text-center">
            <button type="submit" class="btn btn-primary px-4">Salvar Alterações</button>
            <a href="produtos.php" class="btn btn-primary px-4">Voltar</a>
        </div>
    </form>
</div>
</body>
</html>
