<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Nova Categoria</title>
  <link rel="stylesheet" href="../css/styles.css">
  <style>
    body { background-color: #f5f7fa; font-family: Arial, sans-serif; }
    .container { max-width: 500px; margin: 50px auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
    label { display: block; margin-bottom: 8px; }
    input[type="text"] { width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 5px; }
    .btn { padding: 8px 16px; border-radius: 5px; text-decoration: none; }
    .btn-primary { background-color: #1976f2; color: white; }
    .btn-secondary { background-color: #6c757d; color: white; }
  </style>
</head>
<body>
  <div class="container">
    <h2>Nova Categoria</h2>
    <form action="categorias-save.php" method="POST">
      <label for="descricao">Nome da Categoria:</label>
      <input type="text" name="descricao" id="descricao" required>
      <button type="submit" class="btn btn-primary">Salvar</button>
      <a href="categorias.php" class="btn btn-secondary">Cancelar</a>
    </form>
  </div>
</body>
</html>
