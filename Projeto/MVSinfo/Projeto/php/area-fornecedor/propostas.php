<?php
session_start();
require_once '../Conexao.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['tipoUsuario'] != 2) {
    header('Location: /Projeto/MVSinfo/Projeto/usuario/login_view.php');
    exit;
}

$conexao = new conexao();
$pdo = $conexao->getPdo();

$fornecedorId = $_SESSION['id_usuario'] ?? null;
if (!$fornecedorId) {
    echo "Erro: Usuário não identificado.";
    exit;
}

// Buscar todas as propostas (operacoes tipo 3) do fornecedor logado
$sqlPropostas = "
    SELECT 
        o.id_operacao,
        o.data_operacao,
        o.prazo_entrega,
        o.status_pagamento,
        o.valor_total_compra
    FROM operacao o
    WHERE o.fk_usuario_id_usuario = :fornecedorId
      AND o.fk_tipo_operacao_id_tipo_operacao = 3
    ORDER BY o.data_operacao DESC
";

$stmt = $pdo->prepare($sqlPropostas);
$stmt->execute([':fornecedorId' => $fornecedorId]);
$propostas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MVS Info - Propostas</title>
    <link rel="stylesheet" href="/Projeto/MVSinfo/Projeto/css/styles.css">
    <style>
        body { background-color: #f5f7fa; font-family: Arial, sans-serif; }
        .navbar { background-color: #1976f2; color: white; padding: 12px 20px; display: flex; justify-content: space-between; align-items: center; }
        .navbar a { color: white; margin-left: 15px; text-decoration: none; }
        .container { max-width: 1000px; margin: 40px auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        h2 { color: #1976f2; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { padding: 12px; border-bottom: 1px solid #ddd; text-align: left; }
        th { background-color: #f0f0f0; }
        .btn { padding: 8px 14px; border: none; border-radius: 5px; background-color: #1976f2; color: white; text-decoration: none; cursor: pointer; }
        .btn:hover { background-color: #155dc1; }
    </style>
</head>
<body>

<div class="navbar">
    <div><strong>MVS Info - Área do Fornecedor</strong></div>
    <div>
        <a href="area-fornecedor.php">Perfil</a>
        <a href="propostas.php">Propostas</a>
        <a href="status-pedido.php">Pedidos</a>
        <a href="../logout.php">Sair</a>
    </div>
</div>

<div class="container">
    <h2>Propostas Enviadas</h2>

    <?php if (count($propostas) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>ID da Proposta</th>
                    <th>Data da Proposta</th>
                    <th>Prazo de Entrega</th>
                    <th>Status</th>
                    <th>Valor Total (R$)</th>
                    <th>Ação</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($propostas as $proposta): ?>
                    <tr>
                        <td><?= htmlspecialchars($proposta['id_operacao']) ?></td>
                        <td><?= date('d/m/Y', strtotime($proposta['data_operacao'])) ?></td>
                        <td><?= htmlspecialchars($proposta['prazo_entrega'] ?: '-') ?></td>
                        <td><?= htmlspecialchars($proposta['status_pagamento']) ?></td>
                        <td><?= number_format($proposta['valor_total_compra'], 2, ',', '.') ?></td>
                        <td>
                            <a href="preencher-proposta.php?id=<?= intval($proposta['id_operacao']) ?>" class="btn">
                                <?= $proposta['valor_total_compra'] > 0 ? 'Editar Proposta' : 'Preencher Proposta' ?>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Nenhuma proposta enviada até o momento.</p>
    <?php endif; ?>

    <a href="area-fornecedor.php" class="btn">Voltar ao Perfil</a>
</div>

</body>
</html>
