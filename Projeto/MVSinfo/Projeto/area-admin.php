<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['tipoUsuario'] !== 'admin') {

    header('Location: login_view.php');
  }

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>MVS Info - Área do Admin</title>
  <link rel="stylesheet" href="css/styles.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
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
      max-width: 1000px;
      margin: 40px auto;
      background: white;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }

    h2 {
      color: #1976f2;
      margin-bottom: 20px;
    }

    .dropdown {
      margin-bottom: 30px;
    }

    .dropdown select {
      padding: 10px;
      border-radius: 5px;
      border: 1px solid #ccc;
      width: 100%;
      max-width: 300px;
    }

    .btn {
      padding: 10px 20px;
      background-color: #1976f2;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      text-decoration: none;
      display: inline-block;
      margin-right: 10px;
    }

    .btn:hover {
      background-color: #155dc1;
    }

    .btn-secondary {
      background-color: #6c757d;
    }

    .btn-secondary:hover {
      background-color: #5a6268;
    }

    .btn-danger {
      background-color: #dc3545;
    }

    .btn-danger:hover {
      background-color: #bd2130;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }

    th, td {
      padding: 12px;
      border: 1px solid #ccc;
      text-align: left;
    }

    th {
      background-color: #e9f0fb;
    }

    .actions i {
      margin: 0 8px;
      cursor: pointer;
    }

    .section-title {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-top: 40px;
    }

    .section-title h3 {
      margin: 0;
      color: #1976f2;
    }
  </style>
</head>
<body>
    <h1>
      Olá, <?= !empty($_SESSION['usuario']) ? $_SESSION['usuario'] : ''; ?>
    </h1>
  <div class="navbar">
    <div><strong>MVS Info - Área do Administrador</strong></div>
    <div>
      <a href="area-admin.html">Início</a>
      <a href="orcamento.html">Orçamentos</a>
      <a href="status-pedido.html">Pedidos</a>
    </div>
  </div>

  <div class="container">
    <h2>Gerenciamento MVS INFO</h2>

    <!-- Dropdown -->
    <div class="dropdown">
      <label for="admin-sections"><strong>Acessar:</strong></label>
      <select id="admin-sections" onchange="navigateToSection(this.value)">
        <option value="">Selecione uma opção</option>
        <option value="clientes.html">Clientes</option>
        <option value="fornecedores.html">Fornecedores</option>
        <option value="produtos.html">Produtos</option>
        <option value="categorias.html">Categorias</option>
      </select>
    </div>

    <!-- Lista de Clientes -->
    <div class="section-title">
      <h3>Lista de Clientes</h3>
      <a href="cadastro-cliente.html" class="btn">+ Novo Cadastro</a>
    </div>

    <table>
      <thead>
        <tr>
          <th>Razão Social</th>
          <th>CNPJ</th>
          <th>Email</th>
          <th>Ações</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>Loja Maria Iluminação</td>
          <td>00.000.000/0001-00</td>
          <td>maria@loja.com</td>
          <td class="actions">
            <i class="fas fa-edit" title="Editar"></i>
            <i class="fas fa-trash-alt" title="Excluir" style="color: red;"></i>
          </td>
        </tr>
        <tr>
          <td>Eletrônica Silva</td>
          <td>11.111.111/0001-11</td>
          <td>silva@eletronica.com</td>
          <td class="actions">
            <i class="fas fa-edit" title="Editar"></i>
            <i class="fas fa-trash-alt" title="Excluir" style="color: red;"></i>
          </td>
        </tr>
      </tbody>
    </table>

    <!-- Botões de Acesso aos Orçamentos -->
    <div class="section-title">
      <h3>Orçamentos e Pedidos</h3>
    </div>
    <a href="orcamento.html" class="btn">Ver Orçamentos</a>
    <a href="status-pedido.html" class="btn btn-secondary">Ver Pedidos</a>
  </div>

  <script>
    function navigateToSection(value) {
      if (value) {
        window.location.href = value;
      }
    }
  </script>

</body>
</html>
