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
  <title>Categorias - Área Administrador</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    body { background-color: #f5f7fa; font-family: Arial, sans-serif; }
    .navbar {
        background-color: #1976f2;
        padding: 15px;
    }
    .navbar .nav-link {
        color: white;
        font-weight: bold;
        margin-right: 15px;
    }
    .navbar .nav-link:hover {
        text-decoration: underline;
    }
    .container {
        max-width: 800px;
        margin: 40px auto;
        background: white;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 0 12px rgba(0,0,0,0.1);
    }
    h2 {
        color: #1976f2;
        text-align: center;
        margin-bottom: 25px;
        font-weight: 700;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }
    th, td {
        text-align: left;
        padding: 12px;
        border-bottom: 1px solid #ddd;
    }
    th {
        background-color: #1976f2;
        color: white;
    }
    .btn {
        padding: 6px 12px;
        border-radius: 5px;
        font-size: 0.9rem;
    }
    .btn-primary {
        background-color: #1976f2;
        color: white;
    }
    .btn-primary:hover {
        background-color: #155dc1;
    }
    .btn-warning {
        background-color: #f0ad4e;
        color: white;
    }
    .btn-danger {
        background-color: #d9534f;
        color: white;
    }
    .actions {
        display: flex;
        gap: 8px;
    }
    .top-actions {
        display: flex;
        justify-content: space-between;
        margin-bottom: 15px;
    }
  </style>
</head>
<body>

<nav class="navbar navbar-expand-lg">
  <div class="container-fluid">
    <a class="navbar-brand text-white" href="#">Área do Administrador</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon text-white"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
      <div class="navbar-nav">
        <a class="nav-link" href="../area-admin.php">Menu</a>
        <a class="nav-link" href="../../logout.php">Sair</a>
      </div>
    </div>
  </div>
</nav>

<div class="container">
  <h2>Categorias</h2>
  <div class="top-actions">
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
