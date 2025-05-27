<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Menu Cliente - MVS Info</title>
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
        a {
            display: block;
            background-color: #007bff;
            color: white;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            text-decoration: none;
        }
        a:hover {
            background-color: #0056b3;
        }
        .back {
            background-color: #6c757d;
        }
        .back:hover {
            background-color: #565e64;
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
        <h2>Menu - Gestão de Clientes</h2>

        <a href="cadastrar.php">Cadastrar Cliente</a>
        <a href="listar.php">Listar / Editar / Excluir Clientes</a>
        <a href="../index.php" class="back">Voltar ao Menu Principal</a>

        <footer>&copy; <?php echo date("Y"); ?> MVS Info</footer>
    </div>

</body>
</html>
