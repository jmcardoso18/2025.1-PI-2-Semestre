<?php
session_start();
require_once '../Conexao.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['tipoUsuario'] != 1) {
    header('Location: ../usuario/login_view.php');
    exit;
}

$conexao = new Conexao();
$pdo = $conexao->getPdo();

$idUsuario = $_SESSION['id_usuario'] ?? null;

if (!$idUsuario) {
    echo "Usuário não identificado.";
    exit;
}

// Atualização via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idOperacao = intval($_POST['id_operacao'] ?? 0);
    $statusPagamento = $_POST['status_pagamento'] ?? '';
    $dataPagamento = $_POST['data_pagamento'] ?? '';

    if ($idOperacao > 0 && in_array($statusPagamento, ['Aprovado', 'Rejeitado', 'Pendente'])) {
        $sqlUpdate = "UPDATE operacao SET status_pagamento = :status, data_pagamento = :data WHERE id_operacao = :id AND fk_usuario_id_usuario = :idUsuario";
        $stmtUpdate = $pdo->prepare($sqlUpdate);
        $stmtUpdate->execute([
            ':status' => $statusPagamento,
            ':data' => $dataPagamento ?: null,
            ':id' => $idOperacao,
            ':idUsuario' => $idUsuario
        ]);
        $msgSucesso = "Operação atualizada com sucesso!";
    } else {
        $msgErro = "Dados inválidos para atualização.";
    }
}

// Buscar orçamentos pendentes
$sql = "SELECT id_operacao, data_operacao, valor_total_compra, status_pagamento, data_pagamento 
        FROM operacao
        WHERE fk_usuario_id_usuario = :idUsuario
          AND fk_tipo_operacao_id_tipo_operacao = 3
          AND (status_pagamento IS NULL OR status_pagamento = '' OR data_pagamento IS NULL OR data_pagamento = '')";

$stmt = $pdo->prepare($sql);
$stmt->execute([':idUsuario' => $idUsuario]);
$operacoesPendentes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <title>Minhas Propostas Pendentes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body { background-color: #f5f7fa; }
        .navbar {
            background-color: #1976f2;
            color: white;
            padding: 12px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .navbar a {
            color: white;
            margin-left: 15px;
            text-decoration: none;
        }
        .container {
            max-width: 1000px;
            margin: 40px auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h2 {
            color: #1976f2;
            margin-bottom: 25px;
        }
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
    <h2>Minhas Propostas Pendentes</h2>

    <?php if (!empty($msgSucesso)): ?>
        <div class="alert alert-success"><?= htmlspecialchars($msgSucesso) ?></div>
    <?php endif; ?>
    <?php if (!empty($msgErro)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($msgErro) ?></div>
    <?php endif; ?>

    <?php if (count($operacoesPendentes) === 0): ?>
        <p>Não há propostas pendentes para aprovação.</p>
    <?php else: ?>
        <form method="post" action="">
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Data da Operação</th>
                            <th>Valor Total (R$)</th>
                            <th>Status Pagamento</th>
                            <th>Data Pagamento</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($operacoesPendentes as $op): ?>
                            <tr>
                                <td><?= htmlspecialchars($op['id_operacao']) ?></td>
                                <td><?= htmlspecialchars(date('d/m/Y', strtotime($op['data_operacao']))) ?></td>
                                <td><?= number_format($op['valor_total_compra'], 2, ',', '.') ?></td>
                                <td>
                                    <select name="status_pagamento" class="form-select">
                                        <option value="Pendente" <?= $op['status_pagamento'] === 'Pendente' ? 'selected' : '' ?>>Pendente</option>
                                        <option value="Aprovado" <?= $op['status_pagamento'] === 'Aprovado' ? 'selected' : '' ?>>Aprovado</option>
                                        <option value="Rejeitado" <?= $op['status_pagamento'] === 'Rejeitado' ? 'selected' : '' ?>>Rejeitado</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="date" name="data_pagamento" class="form-control" value="<?= $op['data_pagamento'] ? htmlspecialchars($op['data_pagamento']) : '' ?>">
                                </td>
                                <td>
                                    <input type="hidden" name="id_operacao" value="<?= htmlspecialchars($op['id_operacao']) ?>">
                                    <button type="submit" class="btn btn-primary btn-sm">Salvar</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </form>
    <?php endif; ?>

    <a href="area-cliente.php" class="btn btn-secondary mt-3">Voltar ao Perfil</a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
