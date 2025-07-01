<?php
session_start();
require_once '../../Conexao.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['tipoUsuario'] != 3) {
    header('Location: ../../usuario/login_view.php');
    exit;
}

$conexao = new Conexao();
$pdo = $conexao->getPdo();

$status = $_GET['status'] ?? '';
$mensagem = '';

if ($status === 'excluido') {
    $mensagem = '<div class="alert alert-success">Transportadora excluída com sucesso.</div>';
}

$lista = $pdo->query("SELECT * FROM transportadora ORDER BY descricao ASC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Transportadoras - Área Administrador</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    body {
      background-color: #f4f6f8;
      font-family: Arial, sans-serif;
    }
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
      max-width: 1000px;
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
    .acoes a.btn-warning,
    .acoes a.btn-danger {
      color: white !important; /* Força texto branco */
      font-weight: 600;
    }
    .acoes a.btn-warning:hover {
      background-color: #155dc1 !important;
      color: white !important;
    }
    .acoes a.btn-danger:hover {
      background-color: #b02a37 !important;
      color: white !important;
    }
    .top-actions {
      display: flex;
      justify-content: space-between;
      margin-bottom: 20px;
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
  <h2>Transportadoras</h2>

  <?= $mensagem ?>

  <div class="top-actions">
    <a href="admin-transportadora-add.php" class="btn btn-primary">+ Nova Transportadora</a>
    <a href="../area-admin.php" class="btn btn-primary">Voltar</a>
  </div>

  <?php if (count($lista) > 0): ?>
  <table class="table table-bordered">
    <thead class="table-primary">
      <tr>
        <th>ID</th>
        <th>Descrição</th>
        <th>Valor</th>
        <th>Data Entrega</th>
        <th>Tipo</th>
        <th>Ações</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($lista as $item): ?>
      <tr>
        <td><?= htmlspecialchars($item['id_transportadora']) ?></td>
        <td><?= htmlspecialchars($item['descricao']) ?></td>
        <td>R$ <?= number_format($item['valor'], 2, ',', '.') ?></td>
        <td><?= date('d/m/Y', strtotime($item['data_entrega'])) ?></td>
        <td><?= htmlspecialchars($item['tipo_transportadora']) ?></td>
        <td class="acoes">
          <a href="admin-transportadora-edit.php?id=<?= $item['id_transportadora'] ?>" class="btn btn-sm btn-warning text-white">Editar</a>
          <a href="admin-transportadora-delete.php?id=<?= $item['id_transportadora'] ?>" class="btn btn-sm btn-danger text-white" onclick="return confirm('Confirma exclusão?')">Excluir</a>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <?php else: ?>
  <p>Nenhuma transportadora cadastrada.</p>
  <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
