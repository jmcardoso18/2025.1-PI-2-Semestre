<?php
session_start();
require_once '../../Conexao.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['tipoUsuario'] != 0) {
    header('Location: ../../usuario/login_view.php');
    exit;
}

$conexao = new conexao();
$pdo = $conexao->getPDO();

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
  <title>Lista de Transportadoras</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
  <header class="bg-primary text-white p-3">
    <h2 class="text-center">Transportadoras Cadastradas</h2>
  </header>
  <main class="container mt-4">
    <?= $mensagem ?>
    <div class="mb-3">
      <a href="admin-transportadora-add.php" class="btn btn-success">Nova Transportadora</a>
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
              <td><?= $item['id_transportadora'] ?></td>
              <td><?= htmlspecialchars($item['descricao']) ?></td>
              <td>R$ <?= number_format($item['valor'], 2, ',', '.') ?></td>
              <td><?= date('d/m/Y', strtotime($item['data_entrega'])) ?></td>
              <td><?= htmlspecialchars($item['tipo_transportadora']) ?></td>
              <td>
                <a href="admin-transportadora-edit.php?id=<?= $item['id_transportadora'] ?>" class="btn btn-sm btn-warning">Editar</a>
                <a href="admin-transportadora-delete.php?id=<?= $item['id_transportadora'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Confirma exclusão?')">Excluir</a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php else: ?>
      <p>Nenhuma transportadora cadastrada.</p>
    <?php endif; ?>
  </main>
</body>
</html>
