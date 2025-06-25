<?php
require_once '../../Conexao.php';
$conexao = new Conexao();
$pdo = $conexao->getPDO();

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if ($id === false || $id === null) {
    echo "ID inválido.";
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM categoria WHERE cod_categoria = :id");
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$categoria = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$categoria) {
    echo "Categoria não encontrada.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Editar Categoria</title>
    <link rel="stylesheet" href="../css/styles.css">
    <style>
        body {
            background-color: #f5f7fa;
            font-family: Arial, sans-serif;
        }

        .container {
            max-width: 600px;
            margin: 50px auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #1976f2;
            text-align: center;
            margin-bottom: 20px;
        }

        form label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        form input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .btn {
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            margin-right: 10px;
        }

        .btn-primary {
            background-color: #1976f2;
            color: white;
        }

        .btn-primary:hover {
            background-color: #155dc1;
        }
        
    </style>
</head>
<body>
    <div class="container">
        <h2>Editar Categoria</h2>
        <form action="categorias-update.php" method="POST">
            <input type="hidden" name="id" value="<?= htmlspecialchars($categoria['cod_categoria']) ?>">

            <label for="descricao">Nome da Categoria:</label>
            <input type="text" name="descricao" id="descricao" required value="<?= htmlspecialchars($categoria['descricao']) ?>">

            <button type="submit" class="btn btn-primary">Salvar Alterações</button>
            <a href="categorias.php" class="btn btn-primary">Cancelar</a>
        </form>
    </div>
</body>
</html>
