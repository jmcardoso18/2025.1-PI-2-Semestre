<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Login - MVS Info</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f2f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
            text-align: center;
            width: 400px;
        }
        h2 {
            margin-bottom: 20px;
        }
        select, input[type="submit"] {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        input[type="submit"] {
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
        footer {
            margin-top: 15px;
            color: #777;
            font-size: 14px;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>Bem-vindo ao Sistema MVS Info</h2>
        <form method="POST" action="login.php">
            <label for="setor">Selecione o Setor:</label>
            <select name="setor" id="setor" required>
                <option value="">-- Escolha uma opção --</option>
                <option value="cliente/index.php">Clientes</option>
                <option value="produto/listar.php">Produtos</option>
                <option value="fornecedor/listar.php">Fornecedores</option>
                <option value="categoria/listar.php">Categorias</option>
                <option value="compra/listar.php">Compras</option>
                <option value="venda/listar.php">Vendas</option>
            </select>
            <input type="submit" value="Entrar">
        </form>
        <footer>&copy; <?php echo date("Y"); ?> MVS Info</footer>
    </div>

</body>
</html>
