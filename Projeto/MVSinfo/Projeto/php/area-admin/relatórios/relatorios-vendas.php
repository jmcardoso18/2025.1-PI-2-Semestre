<?php
session_start();
require_once '../../Conexao.php';

// Verifica autenticação e permissão (tipoUsuario = 3 administrador)
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['tipoUsuario'] != 3) {
    http_response_code(403);
    echo "Acesso negado.";
    exit;
}

$conexao = new Conexao();
$pdo = $conexao->getPDO();

$dataInicio = $_POST['dataInicio'] ?? null;
$dataFim = $_POST['dataFim'] ?? null;

// Preparar filtro de datas para SQL
$whereDatas = "";
$params = [];

if ($dataInicio) {
    $whereDatas .= " AND o.data_operacao >= :dataInicio ";
    $params[':dataInicio'] = $dataInicio;
}
if ($dataFim) {
    $whereDatas .= " AND o.data_operacao <= :dataFim ";
    $params[':dataFim'] = $dataFim;
}

// Buscar id_tipo_operacao da "Venda"
$stmtTipo = $pdo->prepare("SELECT id_tipo_operacao FROM tipo_operacao WHERE descricao = 'Venda' LIMIT 1");
$stmtTipo->execute();
$idTipoVenda = $stmtTipo->fetchColumn();

if (!$idTipoVenda) {
    echo "<p>Tipo de operação 'Venda' não configurado no sistema.</p>";
    exit;
}

// Consulta para buscar as vendas com detalhes, usando a view para facilitar
$sql = "
SELECT 
    o.id_operacao,
    o.data_operacao,
    u.razao_social AS cliente,
    t.descricao AS transportadora,
    SUM(op.quantidade) AS quantidade_total,
    SUM(op.valor_total_produtos) AS valor_total_compra,
    SUM(op.preco_venda * op.quantidade) AS valor_total_venda,
    SUM((op.preco_venda * op.quantidade) - op.valor_total_produtos) AS lucro_total
FROM operacao o
JOIN usuario u ON o.fk_usuario_id_usuario = u.id_usuario
LEFT JOIN transportadora t ON o.fk_transportadora_id_transportadora = t.id_transportadora
JOIN operacao_produto op ON op.id_operacao = o.id_operacao
WHERE o.fk_tipo_operacao_id_tipo_operacao = :idTipoVenda
  $whereDatas
GROUP BY o.id_operacao, o.data_operacao, u.razao_social, t.descricao
ORDER BY o.data_operacao DESC
";

$stmt = $pdo->prepare($sql);
$stmt->execute(array_merge([':idTipoVenda' => $idTipoVenda], $params));
$vendas = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!$vendas) {
    echo "<p>Nenhuma venda encontrada para o período selecionado.</p>";
    exit;
}

// Montar tabela HTML
echo '<table class="table table-striped">';
echo '<thead>';
echo '<tr>';
echo '<th>ID Venda</th>';
echo '<th>Data</th>';
echo '<th>Cliente</th>';
echo '<th>Transportadora</th>';
echo '<th>Qtd. Total</th>';
echo '<th>Valor Compra</th>';
echo '<th>Valor Venda</th>';
echo '<th>Lucro Total</th>';
echo '</tr>';
echo '</thead>';
echo '<tbody>';

foreach ($vendas as $venda) {
    echo '<tr>';
    echo '<td>' . htmlspecialchars($venda['id_operacao']) . '</td>';
    echo '<td>' . date('d/m/Y', strtotime($venda['data_operacao'])) . '</td>';
    echo '<td>' . htmlspecialchars($venda['cliente']) . '</td>';
    echo '<td>' . htmlspecialchars($venda['transportadora'] ?? '-') . '</td>';
    echo '<td>' . number_format($venda['quantidade_total'], 0, ',', '.') . '</td>';
    echo '<td>R$ ' . number_format($venda['valor_total_compra'], 2, ',', '.') . '</td>';
    echo '<td>R$ ' . number_format($venda['valor_total_venda'], 2, ',', '.') . '</td>';
    echo '<td>R$ ' . number_format($venda['lucro_total'], 2, ',', '.') . '</td>';
    echo '</tr>';
}

echo '</tbody>';
echo '</table>';
