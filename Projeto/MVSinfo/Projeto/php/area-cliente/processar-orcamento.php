<?php
session_start();
require_once '../Conexao.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['tipoUsuario'] != 1) {
    header('Location: ../usuario/login_view.php');
    exit;
}

$conexao = new conexao();
$pdo = $conexao->getPdo();

$idUsuario = $_SESSION['id_usuario'] ?? null;

if (!$idUsuario) {
    echo "Usuário não identificado.";
    exit;
}

$produtos = $_POST['produtos'] ?? [];

if (empty($produtos)) {
    echo "Nenhum produto foi enviado.";
    exit;
}

try {
    $pdo->beginTransaction();

    // Buscar id_tipo_operacao do tipo "Orçamento"
    $stmtTipo = $pdo->prepare("SELECT id_tipo_operacao FROM tipo_operacao WHERE descricao = 'Orçamento' LIMIT 1");
    $stmtTipo->execute();
    $tipoOperacao = $stmtTipo->fetchColumn();

    if (!$tipoOperacao) {
        throw new Exception("Tipo de operação 'Orçamento' não encontrado no banco.");
    }

    // Inserir operação
    $stmtOperacao = $pdo->prepare("
        INSERT INTO operacao (fk_usuario_id_usuario, data_operacao, fk_tipo_operacao_id_tipo_operacao, valor_total_compra)
        VALUES (:usuario, NOW(), :tipoOperacao, 0)
    ");
    $stmtOperacao->execute([
        ':usuario' => $idUsuario,
        ':tipoOperacao' => $tipoOperacao,
    ]);

    $idOperacao = $pdo->lastInsertId();

    $valorTotalOperacao = 0;

    foreach ($produtos as $item) {
        $produtoId = $item['produto_id'] ?? null;
        $quantidade = $item['quantidade'] ?? 0;

        if (!$produtoId || $quantidade < 1) {
            throw new Exception("Produto inválido ou quantidade inválida.");
        }

        // Buscar preço unitário do produto (por ora, zero para orçamento)
        $valorUnitario = 0;
        $valorTotal = $valorUnitario * $quantidade;

        // Verifica se produto já existe para esta operação para evitar duplicação
        $stmtCheck = $pdo->prepare("SELECT quantidade FROM operacao_produto WHERE id_operacao = :idOperacao AND id_produto = :produtoId");
        $stmtCheck->execute([
            ':idOperacao' => $idOperacao,
            ':produtoId' => $produtoId
        ]);
        $produtoExistente = $stmtCheck->fetchColumn();

        if ($produtoExistente !== false) {
            // Atualiza quantidade somando a nova quantidade
            $novaQuantidade = $produtoExistente + $quantidade;
            $stmtUpdate = $pdo->prepare("
                UPDATE operacao_produto SET quantidade = :novaQtd WHERE id_operacao = :idOperacao AND id_produto = :produtoId
            ");
            $stmtUpdate->execute([
                ':novaQtd' => $novaQuantidade,
                ':idOperacao' => $idOperacao,
                ':produtoId' => $produtoId
            ]);
        } else {
            // Insere novo produto na operação
            $stmtItem = $pdo->prepare("
                INSERT INTO operacao_produto (id_operacao, id_produto, quantidade, valor_unitario, valor_total_produtos)
                VALUES (:idOperacao, :produtoId, :quantidade, :valorUnitario, :valorTotal)
            ");
            $stmtItem->execute([
                ':idOperacao' => $idOperacao,
                ':produtoId' => $produtoId,
                ':quantidade' => $quantidade,
                ':valorUnitario' => $valorUnitario,
                ':valorTotal' => $valorTotal
            ]);
        }

        $valorTotalOperacao += $valorTotal;
    }

    // Atualizar valor_total_compra na tabela operacao
    $stmtUpdateTotal = $pdo->prepare("UPDATE operacao SET valor_total_compra = :valorTotal WHERE id_operacao = :idOperacao");
    $stmtUpdateTotal->execute([
        ':valorTotal' => $valorTotalOperacao,
        ':idOperacao' => $idOperacao
    ]);

    $pdo->commit();

    header('Location: orcamento.php?success=1');
    exit;

} catch (Exception $e) {
    $pdo->rollBack();
    echo "Erro ao processar orçamento: " . $e->getMessage();
    exit;
}
?>
