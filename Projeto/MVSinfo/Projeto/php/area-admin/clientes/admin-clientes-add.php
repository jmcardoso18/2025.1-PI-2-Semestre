<?php
session_start();
require_once '../../Conexao.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['tipoUsuario'] != 3) {
    header('Location: ../../usuario/login_view.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Adicionar Cliente - Área Administrador</title>

    <!-- Bootstrap e jQuery -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>

    <!-- Estilo customizado igual às outras páginas -->
    <link rel="stylesheet" href="../css/styles.css">

    <style>
        body {
            background-color: #f4f6f8;
            font-family: Arial, sans-serif;
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
            margin-left: 1rem;
            text-decoration: none;
            font-weight: 600;
        }

        nav a:hover {
            text-decoration: underline;
        }

        .container {
            max-width: 900px;
            margin: 2rem auto;
            background-color: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.08);
        }

        h2, h3 {
            color: #1976f2;
            margin-bottom: 1rem;
        }

        .form-section {
            margin-bottom: 2rem;
        }

        .btn-primary {
            background-color: #1976f2;
            border-color: #1976f2;
        }

        .btn-primary:hover {
            background-color: #145ca8;
        }
    </style>
</head>

<body>

    <header>
        <h1>Área Administrador - Adicionar Cliente</h1>
        <nav>
            <a href="../area-admin.php">Menu</a>
            <a href="admin-clientes.php">Clientes</a>
            <a href="../../logout.php">Sair</a>
        </nav>
    </header>

    <div class="container">
        <h2 class="text-center">Cadastro de Novo Cliente</h2>

        <form id="cadastroForm" action="admin-clientes-save.php" method="POST">
            <input type="hidden" name="tipoUsuario" value="1">

            <!-- Dados de Login -->
            <div class="row g-3 form-section">
                <div class="col-md-6">
                    <label for="login" class="form-label">Nome de Usuário</label>
                    <input type="text" class="form-control" id="login" name="login" required>
                </div>
                <div class="col-md-6">
                    <label for="senha" class="form-label">Senha</label>
                    <input type="password" class="form-control" id="senha" name="senha" required>
                </div>
                <div class="col-md-12">
                    <label for="confirmarSenha" class="form-label">Confirmar Senha</label>
                    <input type="password" class="form-control" id="confirmarSenha" name="confirmarSenha" required>
                </div>
            </div>

            <!-- Dados Empresariais -->
            <h3>Dados Empresariais</h3>
            <div class="row g-3 form-section">
                <div class="col-md-6">
                    <label for="telefone" class="form-label">Telefone</label>
                    <input type="text" class="form-control" id="telefone" name="telefone">
                </div>
                <div class="col-md-6">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email">
                </div>
                <div class="col-md-6">
                    <label for="razaoSocial" class="form-label">Razão Social</label>
                    <input type="text" class="form-control" id="razaoSocial" name="razao_social">
                </div>
                <div class="col-md-6">
                    <label for="inscricaoEstadual" class="form-label">Inscrição Estadual</label>
                    <input type="text" class="form-control" id="inscricaoEstadual" name="inscricao_estadual">
                </div>
                <div class="col-md-12">
                    <label for="nomeResponsavel" class="form-label">Nome do Responsável</label>
                    <input type="text" class="form-control" id="nomeResponsavel" name="contato">
                </div>
            </div>

            <div class="text-center mt-4">
                <button type="submit" class="btn btn-primary px-4">Salvar Cliente</button>
                <button type="button" class="btn btn-secondary ms-2 px-4" onclick="history.back()">Voltar</button>
            </div>
        </form>
    </div>

    <script>
        $(document).ready(function () {
            $('#telefone').mask('(00) 00000-0000');

            // Validação de senha
            $('#cadastroForm').on('submit', function (e) {
                const senha = $('#senha').val();
                const confirmar = $('#confirmarSenha').val();
                if (senha !== confirmar) {
                    e.preventDefault();
                    alert('❌ As senhas não coincidem!');
                }
            });
        });
    </script>
</body>
</html>
