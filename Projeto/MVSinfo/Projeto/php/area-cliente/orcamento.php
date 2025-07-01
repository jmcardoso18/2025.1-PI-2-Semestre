<?php
session_start();
require_once '../Conexao.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['tipoUsuario'] != 1) {
    header('Location: ../usuario/login_view.php');
    exit;
}

$conexao = new conexao();
$pdo = $conexao->getPdo();

$idCliente = $_SESSION['id_usuario'] ?? null;
if (!$idCliente) {
    echo "Usuário não identificado.";
    exit;
}

// Filtros recebidos por GET
$dataInicio = $_GET['dataInicio'] ?? '';
$dataFim = $_GET['dataFim'] ?? '';
$status = $_GET['status'] ?? '';

// Montagem das condições SQL
$condicoes = "o.fk_usuario_id_usuario = :idCliente
    AND o.fk_tipo_operacao_id_tipo_operacao = (
        SELECT id_tipo_operacao FROM tipo_operacao WHERE descricao = 'Orçamento' LIMIT 1
    )";

$params = [':idCliente' => $idCliente];

if (!empty($dataInicio)) {
    $condicoes .= " AND o.data_operacao >= :dataInicio";
    $params[':dataInicio'] = $dataInicio;
}
if (!empty($dataFim)) {
    $condicoes .= " AND o.data_operacao <= :dataFim";
    $params[':dataFim'] = $dataFim;
}
if (!empty($status)) {
    $condicoes .= " AND o.status_pagamento = :status";
    $params[':status'] = $status;
}

// Consulta SQL
$sql = "
    SELECT 
        o.id_operacao,
        o.data_operacao,
        o.prazo_entrega,
        o.valor_total_compra,
        o.status_pagamento
    FROM operacao o
    WHERE $condicoes
    ORDER BY o.data_operacao DESC
";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$orcamentos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meus Orçamentos - MVS Info</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body { background-color: #f5f7fa; }
        .navbar { background-color: #1976f2; color: white; padding: 12px 20px; display: flex; justify-content: space-between; align-items: center; }
        .navbar a { color: white; margin-left: 15px; text-decoration: none; }
        .container { max-width: 1000px; margin: 40px auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        h2 { color: #1976f2; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { padding: 12px; border-bottom: 1px solid #ddd; text-align: left; }
        th { background-color: #f0f0f0; }
        .btn { padding: 8px 14px; border: none; border-radius: 5px; background-color: #1976f2; color: white; text-decoration: none; cursor: pointer; }
        .btn:hover { background-color: #155dc1; }
        .status-pago { color: green; font-weight: bold; }
        .status-pendente { color: orange; font-weight: bold; }
        .status-cancelado { color: red; font-weight: bold; }
    </style>
</head>
<body>

<div class="navbar">
    <div><strong>MVS Info - Área do Cliente</strong></div>
    <div>
        <a href="area-cliente.php">Perfil</a>
        <a href="../logout.php">Sair</a>
    </div>
</div>

<div class="container">
    <h2>Meus Orçamentos</h2>

    <!-- Filtros -->
    <div class="d-flex justify-content-between align-items-end mb-3">
  <form class="row g-3" method="GET" action="">
    <div class="col-md-3">
      <label for="dataInicio" class="form-label">Data Início</label>
      <input type="date" name="dataInicio" class="form-control" value="<?= htmlspecialchars($dataInicio) ?>">
    </div>
    <div class="col-md-3">
      <label for="dataFim" class="form-label">Data Fim</label>
      <input type="date" name="dataFim" class="form-control" value="<?= htmlspecialchars($dataFim) ?>">
    </div>
    <div class="col-md-3">
      <label for="status" class="form-label">Status</label>
      <select name="status" class="form-select">
        <option value="">Todos</option>
        <option value="Pago" <?= $status == 'Pago' ? 'selected' : '' ?>>Pago</option>
        <option value="Pendente" <?= $status == 'Pendente' ? 'selected' : '' ?>>Pendente</option>
        <option value="Cancelado" <?= $status == 'Cancelado' ? 'selected' : '' ?>>Cancelado</option>
      </select>
    </div>
    <div class="col-md-3 d-flex align-items-end">
      <button type="submit" class="btn btn-primary w-100">Filtrar</button>
    </div>
  </form>

  <a href="criar-orcamento.php" class="btn btn-success ms-4" style="height: 42px;">
    Novo Orçamento
  </a>
</div>


    <?php if (isset($_GET['success'])): ?>
        <div style="background-color:#d4edda; color:#155724; padding: 10px 15px; border-radius: 5px; margin-bottom: 15px;">
            Orçamento criado com sucesso!
        </div>
    <?php endif; ?>

    <?php if (count($orcamentos) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Data</th>
                    <th>Prazo de Entrega</th>
                    <th>Status</th>
                    <th>Valor Total (R$)</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orcamentos as $orc): ?>
                    <tr>
                        <td><?= htmlspecialchars($orc['id_operacao']) ?></td>
                        <td><?= date('d/m/Y', strtotime($orc['data_operacao'])) ?></td>
                        <td><?= htmlspecialchars($orc['prazo_entrega'] ?? '-') ?></td>
                        <td class="<?= 'status-' . strtolower($orc['status_pagamento']) ?>">
                            <?= htmlspecialchars($orc['status_pagamento']) ?>
                        </td>
                        <td><?= number_format($orc['valor_total_compra'], 2, ',', '.') ?></td>
                        <td>
                            <a href="visualizar-orcamento.php?id=<?= $orc['id_operacao'] ?>" class="btn btn-sm">Ver Detalhes</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Nenhum orçamento encontrado com os critérios selecionados.</p>
    <?php endif; ?>

    <a href="area-cliente.php" class="btn">Voltar ao Perfil</a>
</div>

</body>
</html>
