<?php
session_start();
require_once '../Conexao.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['tipoUsuario'] != 2) {
    header('Location: /Projeto/MVSinfo/Projeto/usuario/login_view.php');
    exit;
}

$conexao = new conexao();
$pdo = $conexao->getPdo();

// Id do fornecedor logado
$fornecedorId = $_SESSION['id_usuario'] ?? null;

if (!$fornecedorId) {
    echo "Erro: Usuário não identificado.";
    exit;
}

// Buscar orçamentos pendentes para esse fornecedor (ou seja, onde ele ainda não enviou cotação)
$sql = "
    SELECT o.id_orcamento, u.razao_social, o.data_orcamento
    FROM orcamento o
    JOIN usuario u ON o.id_cliente = u.id_usuario
    WHERE o.id_orcamento NOT IN (
        SELECT c.id_cotacao FROM cotacao c WHERE c.id_fornecedor = :id_fornecedor
    )
    ORDER BY o.data_orcamento DESC
";
$stmt = $pdo->prepare($sql);
$stmt->execute([':id_fornecedor' => $fornecedorId]);
$orcamentos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MVS Info - Propostas</title>
    <link rel="stylesheet" href="/Projeto/MVSinfo/Projeto/css/styles.css">
    <style>
        body {
            background-color: #f5f7fa;
            font-family: Arial, sans-serif;
        }

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
            max-width: 900px;
            margin: 40px auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #1976f2;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th, td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #f0f0f0;
        }

        .btn {
            padding: 8px 14px;
            border: none;
            border-radius: 5px;
            background-color: #1976f2;
            color: white;
            text-decoration: none;
            cursor: pointer;
        }

        .btn:hover {
            background-color: #155dc1;
        }

        .btn-secondary {
            background-color: gray;
        }
    </style>
</head>

<body>

    <div class="navbar">
        <div><strong>MVS Info - Área do Fornecedor</strong></div>
        <div>
            <a href="area-fornecedor.php">Perfil</a>
            <a href="propostas.php">Propostas</a>
            <a href="status-pedido.php">Pedidos</a>
        </div>
    </div>

    <div class="container">
        <h2>Propostas Solicitadas</h2>

        <?php if (count($orcamentos) > 0) : ?>
            <table>
                <thead>
                    <tr>
                        <th>Empresa (Cliente)</th>
                        <th>Data da Solicitação</th>
                        <th>Ação</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orcamentos as $orc) : ?>
                        <tr>
                            <td><?= htmlspecialchars($orc['razao_social']) ?></td>
                            <td><?= date('d/m/Y', strtotime($orc['data_orcamento'])) ?></td>
                            <td>
                                <a href="preencher-proposta.php?id=<?= intval($orc['id_orcamento']) ?>" class="btn">Preencher Proposta</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else : ?>
            <p>Nenhuma proposta pendente para você no momento.</p>
        <?php endif; ?>

        <br>
        <a href="area-fornecedor.php" class="btn btn-primary">Voltar ao Perfil</a>
    </div>

</body>

</html>
