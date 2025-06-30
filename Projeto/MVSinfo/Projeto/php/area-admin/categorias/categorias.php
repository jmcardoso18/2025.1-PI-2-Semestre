<?php
session_start();
require_once '../../Conexao.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['tipoUsuario'] != 3) {
    header('Location: ../usuario/login_view.php');
    exit;
}

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
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    body { background-color: #f5f7fa; font-family: Arial, sans-serif; }
    .container { max-width: 700px; margin: 50px auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
    h2 { color: #1976f2; text-align: center; margin-bottom: 20px; }
    table { width: 100%; border-collapse: collapse; margin-top: 20px; }
    th, td { text-align: left; padding: 12px; border-bottom: 1px solid #ddd; }
    th { background-color: #1976f2; color: white; }
    .btn { padding: 6px 12px; border-radius: 5px; font-size: 0.9rem; }
    .btn-primary { background-color: #1976f2; color: white; }
    .btn-warning { background-color: #f0ad4e; color: white; }
    .btn-danger { background-color: #d9534f; color: white; }
    .actions { display: flex; gap: 5px; }
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
          <th style="width: 160px;">Ações</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($categorias)): ?>
          <tr><td colspan="2">Nenhuma categoria cadastrada.</td></tr>
        <?php else: ?>
          <?php foreach ($categorias as $cat): ?>
            <tr>
              <td><?= htmlspecialchars($cat['descricao']) ?></td>
              <td class="actions">
                <a href="categorias-edit.php?id=<?= $cat['id_categoria'] ?>" class="btn btn-warning btn-sm">Editar</a>
                <a href="categorias-delete.php?id=<?= $cat['id_categoria'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Confirma exclusão?')">Excluir</a>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</body>
</html>
