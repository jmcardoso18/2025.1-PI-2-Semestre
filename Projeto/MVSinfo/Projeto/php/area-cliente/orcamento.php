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

// Buscar orçamentos do cliente (tipo_operacao = Orçamento)
$sql = "
    SELECT 
        o.id_operacao,
        o.data_operacao,
        o.prazo_entrega,
        o.valor_total_compra,
        o.status_pagamento
    FROM operacao o
    WHERE o.fk_cliente_id_cliente = :idCliente
      AND o.fk_tipo_operacao_id_tipo_operacao = (
          SELECT id_tipo_operacao FROM tipo_operacao WHERE descricao = 'Orçamento' LIMIT 1
      )
    ORDER BY o.data_operacao DESC
";

$stmt = $pdo->prepare($sql);
$stmt->execute([':idCliente' => $idCliente]);
$orcamentos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meus Orçamentos - MVS Info</title>
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
    <div><strong>MVS Info - Área do Cliente</strong></div>
    <div>
        <a href="area-cliente.php">Perfil</a>
        <a href="orcamento.php">Orçamentos</a>
        <a href="../logout.php">Sair</a>
    </div>
</div>

<div class="container">
    <h2>Meus Orçamentos</h2>

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
                        <td><?= htmlspecialchars($orc['status_pagamento']) ?></td>
                        <td><?= number_format($orc['valor_total_compra'], 2, ',', '.') ?></td>
                        <td>
                            <a href="visualizar-orcamento.php?id=<?= $orc['id_operacao'] ?>" class="btn">Ver Detalhes</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Nenhum orçamento encontrado até o momento.</p>
    <?php endif; ?>

    <a href="area-cliente.php" class="btn">Voltar ao Perfil</a>
</div>

</body>
</html>
