<?php
session_start();
require_once '../../conexao.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['tipoUsuario'] != 3) {
    header('Location: ../usuario/login_view.php');
    exit;
}

$conexao = new Conexao();
$pdo = $conexao->getPDO();

// Buscar categorias para popular o select
$stmtCat = $pdo->prepare("SELECT id_categoria, descricao FROM categoria ORDER BY descricao");
$stmtCat->execute();
$categorias = $stmtCat->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Cadastrar Produto</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
<style>
    body { background-color: #f5f7fa; }
    .container { max-width: 700px; margin: 40px auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
    h2 { color: #1976f2; margin-bottom: 20px; text-align: center; }
</style>
</head>
<body>
<div class="container">
    <h2>Adicionar Produto</h2>
    <form action="produtos-save.php" method="POST">
        <div class="mb-3">
            <label for="descricao" class="form-label">Descrição</label>
            <input type="text" name="descricao" id="descricao" class="form-control" required />
        </div>
        <div class="mb-3">
            <label for="fk_categoria_id_categoria" class="form-label">Categoria</label>
            <select name="fk_categoria_id_categoria" id="fk_categoria_id_categoria" class="form-control" required>
                <option value="">Selecione uma categoria</option>
                <?php foreach ($categorias as $cat): ?>
                    <option value="<?= $cat['id_categoria'] ?>"><?= htmlspecialchars($cat['descricao']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="ncm" class="form-label">NCM</label>
            <input type="text" name="ncm" id="ncm" class="form-control" />
        </div>
        <div class="mb-3">
            <label for="marca" class="form-label">Marca</label>
            <input type="text" name="marca" id="marca" class="form-control" />
        </div>
        <div class="mb-3">
            <label for="unidade_medida" class="form-label">Unidade de Medida</label>
            <input type="text" name="unidade_medida" id="unidade_medida" class="form-control" />
        </div>
        <div class="text-center">
            <button type="submit" class="btn btn-primary px-4">Salvar Produto</button>
            <a href="produtos.php" class="btn btn-primary px-4">Voltar</a>
        </div>
    </form>
</div>
</body>
</html>
