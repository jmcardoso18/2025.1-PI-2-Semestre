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
$idOperacao = intval($_POST['id_operacao'] ?? 0);
$statusPagamento = $_POST['status_pagamento'] ?? '';
$dataPagamento = $_POST['data_pagamento'] ?? null;

if (!$idUsuario || $idOperacao <= 0) {
    echo "Dados inválidos.";
    exit;
}

// Validar status_pagamento - só aceitar valores permitidos
$valoresPermitidos = ['Pendente', 'Aprovado', 'Rejeitado'];
if (!in_array($statusPagamento, $valoresPermitidos)) {
    echo "Status de pagamento inválido.";
    exit;
}

// Validar data_pagamento (opcional)
// Aceitar data no formato yyyy-mm-dd ou vazio/null
if ($dataPagamento) {
    $dateObj = DateTime::createFromFormat('Y-m-d', $dataPagamento);
    if (!$dateObj || $dateObj->format('Y-m-d') !== $dataPagamento) {
        echo "Data de pagamento inválida.";
        exit;
    }
} else {
    $dataPagamento = null; // Para gravar NULL no banco
}

try {
    // Verificar se a operação pertence ao usuário e é do tipo orçamento (tipo 3)
    $stmtCheck = $pdo->prepare("
        SELECT COUNT(*) FROM operacao 
        WHERE id_operacao = :idOperacao AND fk_usuario_id_usuario = :idUsuario AND fk_tipo_operacao_id_tipo_operacao = (
            SELECT id_tipo_operacao FROM tipo_operacao WHERE descricao = 'Orçamento' LIMIT 1
        )
    ");
    $stmtCheck->execute([
        ':idOperacao' => $idOperacao,
        ':idUsuario' => $idUsuario
    ]);
    if ($stmtCheck->fetchColumn() == 0) {
        echo "Operação não encontrada ou não autorizada.";
        exit;
    }

    // Atualizar os campos status_pagamento e data_pagamento
    $stmtUpdate = $pdo->prepare("
        UPDATE operacao 
        SET status_pagamento = :statusPagamento, data_pagamento = :dataPagamento
        WHERE id_operacao = :idOperacao
    ");
    $stmtUpdate->execute([
        ':statusPagamento' => $statusPagamento,
        ':dataPagamento' => $dataPagamento,
        ':idOperacao' => $idOperacao
    ]);

    header("Location: minhas-operacoes.php?msg=pagamento_atualizado");
    exit;

} catch (Exception $e) {
    echo "Erro ao atualizar pagamento: " . $e->getMessage();
    exit;
}
