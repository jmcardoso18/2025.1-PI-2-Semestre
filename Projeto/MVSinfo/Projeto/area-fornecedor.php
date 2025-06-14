<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['tipoUsuario'] !== 'fornecedor') {

    header('Location: login_view.php');
  }
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MVS Info - Área do Fornecedor</title>
    <link rel="stylesheet" href="css/styles.css">
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

        label {
            font-weight: bold;
            display: block;
            margin-top: 10px;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            margin-top: 20px;
            cursor: pointer;
        }

        .btn-primary {
            background-color: #1976f2;
            color: white;
        }

        .btn-secondary {
            background: none;
            border: none;
            color: #1976f2;
            text-decoration: underline;
            cursor: pointer;
        }

        .right-button {
            float: right;
        }
    </style>
</head>

<body>

    <div class="navbar">
        <div><strong>MVS Info - Área do Fornecedor</strong></div>
        <div>
            <a href="#">Perfil</a>
            <a href="propostas.html">Propostas</a>
            <a href="status-pedido.html">Pedidos</a>
        </div>
    </div>
     <h1>
      Olá, <?= !empty($_SESSION['usuario']) ? $_SESSION['usuario'] : ''; ?>
    </h1>
    <div class="container">
        <h2>Seus dados</h2>
        <form>
            <label for="razao">Razão Social:</label>
            <input type="text" id="razao" name="razao" value="Fornecedor Ltda">

            <label for="cnpj">CNPJ:</label>
            <input type="text" id="cnpj" name="cnpj" value="00.000.000/0001-00">

            <label for="email">E-mail:</label>
            <input type="email" id="email" name="email" value="fornecedor@email.com">

            <label for="telefone">Telefone:</label>
            <input type="text" id="telefone" name="telefone" value="(11) 91234-5678">

            <label for="categoria">Categoria de Fornecimento:</label>
            <input type="text" id="categoria" name="categoria" value="Materiais Elétricos">

            <button type="submit" class="btn btn-primary">Atualizar Dados</button>

            <a href="propostas.html" class="btn btn-secondary right-button">Ir para Propostas</a>
        </form>
    </div>

</body>

</html>