<?php
require_once '../../Conexao.php';
$conexao = new Conexao();
$pdo = $conexao->getPdo();

$stmt = $pdo->query("SELECT * FROM categoria ORDER BY descricao ASC");
$categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <title>Categorias</title>
  <link rel="stylesheet" href="../css/styles.css" />
  <style>
    body { background-color: #f5f7fa; font-family: Arial, sans-serif; }
    .container { max-width: 700px; margin: 50px auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
    h2 { color: #1976f2; text-align: center; margin-bottom: 20px; }
    table { width: 100%; border-collapse: collapse; margin-top: 20px; }
    th, td { text-align: left; padding: 12px; border-bottom: 1px solid #ddd; }
    th { background-color: #1976f2; color: white; }
    .btn { padding: 8px 12px; border-radius: 5px; text-decoration: none; margin-right: 5px; }
    .btn-primary { background-color: #1976f2; color: white; }
    .icon-btn { background: none; border: none; cursor: pointer; font-size: 1.1rem; }
    .icon-btn:hover { color: #1976f2; }
  </style>
</head>
<body>
  <div class="container">
    <h2>Categorias</h2>
    <div style="display: flex; justify-content: space-between; margin-bottom: 15px;">
      <a href="categorias-add.php" class="btn btn-primary">+ Nova Categoria</a>
      <a href="../area-admin.php" class="btn btn-primary">Voltar</a>
    </div>
    <table>
      <thead>
        <tr>
          <th>Categoria</th>
          <th>Ações</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($categorias as $cat): ?>
          <tr>
            <td><?= htmlspecialchars($cat['descricao']) ?></td>
            <td>
              <a href="categorias-edit.php?id=<?= $cat['cod_categoria'] ?>" title="Editar">✏️</a>
              <a href="categorias-delete.php?id=<?= $cat['cod_categoria'] ?>" title="Excluir" onclick="return confirm('Confirma exclusão?')">🗑️</a>
            </td>
          </tr>
        <?php endforeach; ?>
        <?php if (empty($categorias)): ?>
          <tr><td colspan="2">Nenhuma categoria cadastrada.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</body>
</html>
