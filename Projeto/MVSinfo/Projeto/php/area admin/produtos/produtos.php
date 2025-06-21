<?php
session_start();
require_once '../../conexao.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['tipoUsuario'] != 0) {
    header('Location: ../usuario/login_view.php');
    exit;
}

try {
    $conexao = new Conexao();
    $pdo = $conexao->getPDO();

    // Consulta com JOIN para trazer a descrição da categoria
    $sql = "SELECT p.codigo_produto, p.descricao, c.descricao AS categoria_nome, p.ncm, p.marca, p.unidade_medida, p.preco_custo_unidade
            FROM produtos p
            LEFT JOIN categoria c ON p.cod_categoria = c.cod_categoria
            ORDER BY p.descricao ASC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Erro de conexão ou consulta: " . $e->getMessage();
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Produtos</title>
<style>
    body { background-color: #f5f7fa; font-family: Arial, sans-serif; }
    .container { max-width: 1000px; margin: 50px auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1);}
    h2 { color: #1976f2; text-align: center; margin-bottom: 20px;}
    table { width: 100%; border-collapse: collapse; margin-top: 20px; }
    th, td { text-align: left; padding: 12px; border-bottom: 1px solid #ddd; }
    th { background-color: #1976f2; color: white; }
    .btn { padding: 10px 15px; border: none; border-radius: 5px; cursor: pointer; text-decoration: none; display: inline-block; margin-right: 10px; }
    .btn-primary { background-color: #1976f2; color: white; }
    .acoes { display: flex; gap: 10px; }
    .icon-btn { background: none; border: none; cursor: pointer; font-size: 1.1rem; }
    .icon-btn:hover { color: #1976f2; }
</style>
</head>
<body>
<div class="container">
    <h2>Produtos</h2>
    <div style="display:flex; justify-content:space-between; margin-bottom: 15px;">
        <a href="produtos-add.php" class="btn btn-primary">+ Novo Produto</a>
        <a href="../area-admin.php" class="btn btn-primary">Voltar</a>
    </div>
    <table>
        <thead>
            <tr>
                <th>Código</th>
                <th>Descrição</th>
                <th>Categoria</th>
                <th>NCM</th>
                <th>Marca</th>
                <th>Unidade</th>
                <th>Preço Custo</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if(count($produtos) === 0): ?>
                <tr><td colspan="8">Nenhum produto cadastrado.</td></tr>
            <?php else: ?>
                <?php foreach($produtos as $produto): ?>
                <tr>
                    <td><?= htmlspecialchars($produto['codigo_produto']) ?></td>
                    <td><?= htmlspecialchars($produto['descricao']) ?></td>
                    <td><?= htmlspecialchars($produto['categoria_nome'] ?? 'Sem categoria') ?></td>
                    <td><?= htmlspecialchars($produto['ncm']) ?></td>
                    <td><?= htmlspecialchars($produto['marca']) ?></td>
                    <td><?= htmlspecialchars($produto['unidade_medida']) ?></td>
                    <td>R$ <?= number_format($produto['preco_custo_unidade'], 2, ',', '.') ?></td>
                    <td class="acoes">
                        <a href="produtos-editar.php?id=<?= $produto['codigo_produto'] ?>" class="icon-btn" title="Editar">✏️</a>
                        <a href="produtos-excluir.php?id=<?= $produto['codigo_produto'] ?>" class="icon-btn" title="Excluir" onclick="return confirm('Confirma exclusão deste produto?')">🗑️</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>
