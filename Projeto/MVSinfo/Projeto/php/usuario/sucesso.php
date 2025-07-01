<!-- sucesso.php -->
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Cadastro Realizado</title>
  <meta http-equiv="refresh" content="3;URL='../usuario/login_view.php'">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f5f7fa;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }
    .alert-container {
      max-width: 500px;
    }
  </style>
</head>
<body>
  <div class="alert-container text-center">
    <div class="alert alert-success shadow" role="alert">
      <h4 class="alert-heading">Cadastro realizado com sucesso!</h4>
      <p>Você será redirecionado em instantes para a página de login.</p>
      <hr>
      <small class="text-muted">Caso não seja redirecionado, <a href="../usuario/login_view.php">clique aqui</a>.</small>
    </div>
  </div>
</body>
</html>
