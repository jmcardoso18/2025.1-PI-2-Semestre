<?php
session_start();
session_unset();
session_destroy();
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Logout - MVS Info</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f1f5fb;
            font-family: 'Segoe UI', sans-serif;
        }

        header {
            background-color: #0a5ee5;
            color: white;
            text-align: center;
            padding: 1.5rem 0;
        }

        .logout-box {
            background-color: white;
            border: 2px solid #0a5ee5;
            border-radius: 10px;
            padding: 2rem;
            max-width: 500px;
            margin: 3rem auto;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .logout-box img {
            max-height: 120px;
            margin-bottom: 1rem;
        }

        .btn-primary {
            background-color: #0a5ee5;
            border: none;
        }

        .btn-primary:hover {
            background-color: #094fc4;
        }
    </style>
</head>

<body>
    <header>
        <h2><strong>MVS Info</strong></h2>
        <p>Parceria em tecnologia, aliada em economia.</p>
    </header>

    <div class="logout-box">
        <img src="../img/statuslogo.jpeg" alt="Logo MVS Info" class="img-fluid">
        <h3 class="text-primary mt-3">Você saiu com sucesso!</h3>
        <p>Obrigado por utilizar nosso sistema.</p>
        <a href="./login/login.php" class="btn btn-primary mt-3">Voltar para o Login</a>
    </div>

</body>

</html>