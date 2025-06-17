<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login - MVS Info</title>
    <link rel="stylesheet" href="css/login.css" />
    <script defer src="login.js"></script>
</head>

<body>
    <header class="top-bar">
        <div class="logo">MVS Info</div>
        <p class="slogan">Parceria em tecnologia, aliada em economia.</p>
    </header>

    <a href="logout.php">SAIR</a>

    <main class="login-container">
        <img src="img/logorenderizado.png" alt="Logo renderizaado" class="logo-renderizado" />

        <h2 class="login-titulo">Login</h2>

        <form id="loginForm" class="login-form" action="./php/login/login.php" method="POST">

            <div class="radio-group">
                <label><input type="radio" name="tipoUsuario" value="0" checked/> Cliente</label>
                <label><input type="radio" name="tipoUsuario" value="1"  /> Fornecedor</label>
                <label><input type="radio" name="tipoUsuario" value="2"  /> Administrador</label>
            </div>

            <label for="login">Login</label>
            <input type="text" id="login" name="login" required />

            <label for="senha">Senha</label>
            <input type="password" id="senha" name="senha" required />

            <button type="submit">Entrar</button>
        </form>

        <p id="mensagem" class="mensagem"></p>

        <button type="button" class="btn btn-secondary" onclick="history.back()">Voltar</button>
        <br>
        <br>
    <form action="cadastro.php" method="post">
        <button class="cadastro-link">Não possui cadastro? Clique aqui e cadastre-se</a> 
    </form>
    </main>
</body>

</html>