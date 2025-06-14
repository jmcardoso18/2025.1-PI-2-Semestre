<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['tipoUsuario'] !== 'cliente') {
    header('Location: login_view.php');
  }
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Perfil do Cliente - MVS Info</title>
    <link rel="stylesheet" href="style.css" />
    <style>
        
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f8;
            margin: 0;
        }

        header {
            background-color: #1a73e8;
            color: white;
            padding: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        header h1 {
            margin: 0;
            font-size: 1.5rem;
        }

        .container {
            max-width: 900px;
            margin: 2rem auto;
            padding: 1.5rem;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
        }

        .container h2 {
            color: #1a73e8;
            margin-bottom: 1rem;
        }

        form {
            display: grid;
            gap: 1rem;
        }

        label {
            font-weight: bold;
            margin-bottom: 0.25rem;
            display: block;
        }

        input {
            width: 100%;
            padding: 0.6rem;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        .actions {
            display: flex;
            justify-content: space-between;
            margin-top: 2rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .btn {
            background-color: #1a73e8;
            color: white;
            border: none;
            padding: 0.7rem 1.5rem;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #155ec4;
        }

        @media (max-width: 600px) {
            .actions {
                flex-direction: column;
                align-items: stretch;
            }
        }
    </style>
</head>

<body>
    <header>
        <h1>MVS Info - Área do Cliente</h1>
        <nav>
            <a href="area-cliente.html" class="btn">Perfil</a>
            <a href="orcamento.html" class="btn">Pedir Orçamento</a>
            <a href="pedido.html" class="btn">Meus Pedidos</a>
        </nav>
    </header>

     <h1>
      Olá, <?= !empty($_SESSION['usuario']) ? $_SESSION['usuario'] : ''; ?>
    </h1>

    <main class="container">
        <h2>Seus dados</h2>
        <form id="formPerfil">
            <div>
                <label for="nome">Nome completo:</label>
                <input type="text" id="nome" name="nome" value="Maria da Silva" />
            </div>
            <div>
                <label for="email">E-mail:</label>
                <input type="email" id="email" name="email" value="maria@email.com" />
            </div>
            <div>
                <label for="telefone">Telefone:</label>
                <input type="tel" id="telefone" name="telefone" value="(11) 91234-5678" />
            </div>
            <div>
                <label for="empresa">Empresa:</label>
                <input type="text" id="empresa" name="empresa" value="Loja Maria Iluminação" />
            </div>

            <div class="actions">
                <button type="submit" class="btn">Atualizar Dados</button>
                <a href="orcamento.html" class="btn">Ir para Orçamento</a>
            </div>
        </form>
    </main>
</body>

</html>