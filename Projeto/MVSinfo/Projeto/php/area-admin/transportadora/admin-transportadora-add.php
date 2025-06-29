<?php
session_start();
require_once '../../Conexao.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['tipoUsuario'] != 3) {
    header('Location: ../../usuario/login_view.php');
    exit;
}

$conexao = new conexao();
$pdo = $conexao->getPDO();

$erros = [];
$sucesso = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $descricao = trim($_POST['descricao'] ?? '');
    $valor = floatval($_POST['valor'] ?? 0);
    $data_entrega = $_POST['data_entrega'] ?? '';
    $tipo = trim($_POST['tipo_transportadora'] ?? '');

    if (!$descricao) $erros[] = 'Descrição é obrigatória.';
    if ($valor <= 0) $erros[] = 'Valor deve ser maior que 0.';
    if (!$data_entrega) $erros[] = 'Data de entrega é obrigatória.';
    if (!$tipo) $erros[] = 'Tipo de transportadora é obrigatório.';

    if (empty($erros)) {
        $stmt = $pdo->prepare("INSERT INTO transportadora (descricao, valor, data_entrega, tipo_transportadora) VALUES (?, ?, ?, ?)");
        $stmt->execute([$descricao, $valor, $data_entrega, $tipo]);
        $sucesso = true;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <title>Nova Transportadora</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
  <header class="bg-primary text-white p-3">
    <h2 class="text-center">Cadastrar Transportadora</h2>
  </header>
  <main class="container mt-4">
    <?php if ($sucesso): ?>
      <div class="alert alert-success">Transportadora cadastrada com sucesso!</div>
    <?php endif; ?>
    <?php if (!empty($erros)): ?>
      <div class="alert alert-danger">
        <ul>
          <?php foreach ($erros as $e) echo "<li>" . htmlspecialchars($e) . "</li>"; ?>
        </ul>
      </div>
    <?php endif; ?>
    <form method="POST">
      <div class="mb-3">
        <label class="form-label">Descrição *</label>
        <input type="text" class="form-control" name="descricao" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Valor (R$) *</label>
        <input type="number" class="form-control" name="valor" step="0.01" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Data de Entrega *</label>
        <input type="date" class="form-control" name="data_entrega" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Tipo de Transportadora *</label>
        <input type="text" class="form-control" name="tipo_transportadora" required>
      </div>
      <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">Salvar</button>
        <a href="admin-transportadora-list.php" class="btn btn-secondary">Voltar</a>
      </div>
    </form>
  </main>
</body>
</html>
