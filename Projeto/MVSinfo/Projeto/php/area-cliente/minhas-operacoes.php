<?php
session_start();
require_once '../Conexao.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['tipoUsuario'] != 1) {
    header('Location: ../usuario/login_view.php');
    exit;
}

$conexao = new conexao();
$pdo = $conexao->getPDO();
$idUsuario = $_SESSION['id_usuario'];

$sql = "SELECT o.*, tp.descricao AS tipo, t.descricao AS transportadora
        FROM operacao o
        JOIN tipo_operacao tp ON tp.id_tipo_operacao = o.id_tipo_operacao
        JOIN transportadora t ON t.id_transportadora = o.id_transportadora
        WHERE o.id_usuario = ?
        ORDER BY o.data_operacao DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute([$idUsuario]);
$operacoes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Minhas Operações</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
  <h2 class="mb-4">Histórico de Operações</h2>
  <?php if (count($operacoes) > 0): ?>
    <table class="table table-bordered">
      <thead class="table-primary">
        <tr>
          <th>ID</th>
          <th>Tipo</th>
          <th>Transportadora</th>
          <th>Data</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($operacoes as $op): ?>
          <tr>
            <td><?= $op['id_operacao'] ?></td>
            <td><?= htmlspecialchars($op['tipo']) ?></td>
            <td><?= htmlspecialchars($op['transportadora']) ?></td>
            <td><?= date('d/m/Y H:i', strtotime($op['data_operacao'])) ?></td>
            <td><?= ucfirst($op['status_operacao']) ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php else: ?>
    <p>Você ainda não fez nenhuma operação.</p>
  <?php endif; ?>
  <a href="area-cliente.php" class="btn btn-primary mt-3">Voltar</a>
</div>
</body>
</html>
