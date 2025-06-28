<?php
session_start();
require_once '../../Conexao.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['tipoUsuario'] != 1) {
    header('Location: ../../usuario/login_view.php');
    exit;
}

$conexao = new conexao();
$pdo = $conexao->getPDO();

$idCliente = $_SESSION['id_usuario'] ?? null;
$transportadoras = $pdo->query("SELECT id_transportadora, descricao FROM transportadora")->fetchAll(PDO::FETCH_ASSOC);
$tipos = $pdo->query("SELECT id_tipo_operacao, descricao FROM tipo_operacao")->fetchAll(PDO::FETCH_ASSOC);

$erros = [];
$sucesso = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_transportadora = intval($_POST['id_transportadora'] ?? 0);
    $id_tipo_operacao = intval($_POST['id_tipo_operacao'] ?? 0);

    if ($id_transportadora <= 0) $erros[] = 'Selecione uma transportadora.';
    if ($id_tipo_operacao <= 0) $erros[] = 'Selecione o tipo de operação.';

    if (empty($erros)) {
        $stmt = $pdo->prepare("INSERT INTO operacao (id_usuario, id_transportadora, data_operacao, id_tipo_operacao, status_operacao, valor_total) VALUES (?, ?, NOW(), ?, 'pendente', 0)");
        $stmt->execute([$idCliente, $id_transportadora, $id_tipo_operacao]);
        $sucesso = true;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Nova Operação</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<header class="bg-primary text-white p-3">
  <h2 class="text-center">Solicitar Nova Operação</h2>
</header>
<main class="container mt-4">
  <?php if ($sucesso): ?>
    <div class="alert alert-success">Operação registrada com sucesso!</div>
  <?php endif; ?>
  <?php if (!empty($erros)): ?>
    <div class="alert alert-danger">
      <ul><?php foreach ($erros as $erro) echo "<li>" . htmlspecialchars($erro) . "</li>"; ?></ul>
    </div>
  <?php endif; ?>
  <form method="POST">
    <div class="mb-3">
      <label class="form-label">Tipo de Operação *</label>
      <select name="id_tipo_operacao" class="form-select" required>
        <option value="">Selecione</option>
        <?php foreach ($tipos as $tipo): ?>
          <option value="<?= $tipo['id_tipo_operacao'] ?>"><?= htmlspecialchars($tipo['descricao']) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="mb-3">
      <label class="form-label">Transportadora *</label>
      <select name="id_transportadora" class="form-select" required>
        <option value="">Selecione</option>
        <?php foreach ($transportadoras as $t): ?>
          <option value="<?= $t['id_transportadora'] ?>"><?= htmlspecialchars($t['descricao']) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="d-flex gap-2">
      <button type="submit" class="btn btn-primary">Enviar</button>
      <a href="../cliente/area-cliente.php" class="btn btn-secondary">Voltar</a>
    </div>
  </form>
</main>
</body>
</html>
