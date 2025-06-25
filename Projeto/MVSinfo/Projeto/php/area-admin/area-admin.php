<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['tipoUsuario'] != 0) { 
    
    header('Location: ../usuario/login_view.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Área Administrador - MVS Info</title>
    <link rel="stylesheet" href="../css/styles.css" />
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f8;
            margin: 0;
        }

        header {
            background-color: #1976f2;
            color: white;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        header h1 {
            margin: 0;
            font-size: 1.8rem;
        }

        nav a {
            color: white;
            font-weight: 600;
            text-decoration: none;
            padding: 8px 16px;
            border-radius: 6px;
            background-color: #1565c0;
            transition: background-color 0.3s ease;
        }

        nav a:hover {
            background-color: #0d3d82;
            text-decoration: none;
        }

        main {
            max-width: 900px;
            margin: 3rem auto;
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        main h2 {
            color: #1976f2;
            margin-bottom: 2rem;
        }

        .menu-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 1.5rem;
        }

        .menu-item {
            background-color: #1976f2;
            color: white;
            padding: 1.5rem;
            border-radius: 8px;
            font-weight: 700;
            font-size: 1.1rem;
            text-decoration: none;
            transition: background-color 0.3s ease;
            user-select: none;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .menu-item:hover {
            background-color: #145ca8;
        }

        @media (max-width: 500px) {
            main {
                margin: 1.5rem;
                padding: 1rem;
            }
        }
    </style>
</head>

<body>
    <header>
        <h1>Área Administrador - MVS Info</h1>
        <nav>
            <a href="../usuario/login_view.php">Sair</a>
        </nav>
    </header>

    <main>
        <h2>Menu Principal</h2>
        <div class="menu-grid">
            <a href="./clientes/admin-clientes.php" class="menu-item">Clientes</a>
            <a href="./fornecedores/admin-fornecedores.php" class="menu-item">Fornecedores</a>
            <a href="./produtos/produtos.php" class="menu-item">Produtos</a>
            <a href="./categorias/categorias.php" class="menu-item">Categorias</a>
            <a href="admin-orcamentos.php" class="menu-item">Orçamentos</a>
            <a href="admin-compras.php" class="menu-item">Compras</a>
            <a href="admin-cotações.php" class="menu-item">Cotações</a>
            <a href="admin-vendas.php" class="menu-item">Vendas</a>
            <a href="admin-relatorios.php" class="menu-item">Relatórios</a>
        </div>
    </main>
</body>

</html>
