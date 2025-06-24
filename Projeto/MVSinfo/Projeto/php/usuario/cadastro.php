<?php require_once '../conexao.php'; ?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Cadastro</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
  <style>
    body {
      background-color: #f5f7fa;
    }

    .container {
      max-width: 800px;
      margin-top: 40px;
    }

    h2,
    h3 {
      color: #1976f2;
      margin-bottom: 20px;
    }

    .form-section {
      margin-bottom: 30px;
    }
  </style>
</head>

<body>
  <div class="container bg-white p-4 rounded shadow">
    <h2 class="text-center">Cadastro</h2>

    <form id="cadastroForm" action="salvar.php" method="POST">

      <div class="form-section text-center">
        <div class="form-check form-check-inline">
          <input class="form-check-input" type="radio" name="tipoUsuario" id="cliente" value="1" required checked>
          <label class="form-check-label" for="cliente">Cliente</label>
        </div>
        <div class="form-check form-check-inline">
          <input class="form-check-input" type="radio" name="tipoUsuario" id="fornecedor" value="2">
          <label class="form-check-label" for="fornecedor">Fornecedor</label>
        </div>
      </div>

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
          <input type="text" class="form-control" id="razaoSocial" name="razaoSocial">
        </div>
        <div class="col-md-6">
          <label for="nomeFantasia" class="form-label">Nome Fantasia</label>
          <input type="text" class="form-control" id="nomeFantasia" name="nomeFantasia">
        </div>
        <div class="col-md-6">
          <label for="cnpj_empresa" class="form-label">CNPJ</label>
          <input type="text" class="form-control" id="cnpj_empresa" name="cnpj_empresa">
        </div>
        <div class="col-md-6">
          <label for="inscricaoEstadual" class="form-label">Inscrição Estadual</label>
          <input type="text" class="form-control" id="inscricaoEstadual" name="inscricaoEstadual">
        </div>
        <div class="col-md-12">
          <label for="nomeResponsavel" class="form-label">Nome do Responsável</label>
          <input type="text" class="form-control" id="nomeResponsavel" name="nomeResponsavel">
        </div>
      </div>

      <h3>Endereço</h3>
      <div class="row g-3 form-section">
        <div class="col-md-4">
          <label for="cep" class="form-label">CEP</label>
          <input type="text" class="form-control" id="cep" name="cep">
        </div>
        <div class="col-md-8">
          <label for="logradouro" class="form-label">Logradouro</label>
          <input type="text" class="form-control" id="logradouro" name="logradouro">
        </div>
        <div class="col-md-4">
          <label for="numero" class="form-label">Número</label>
          <input type="text" class="form-control" id="numero" name="numero">
        </div>
        <div class="col-md-8">
          <label for="complemento" class="form-label">Complemento</label>
          <input type="text" class="form-control" id="complemento" name="complemento">
        </div>
        <div class="col-md-6">
          <label for="bairro" class="form-label">Bairro</label>
          <input type="text" class="form-control" id="bairro" name="bairro">
        </div>
        <div class="col-md-4">
          <label for="cidade" class="form-label">Cidade</label>
          <input type="text" class="form-control" id="cidade" name="cidade">
        </div>
        <div class="col-md-2">
          <label for="estado" class="form-label">Estado</label>
          <input type="text" class="form-control" id="estado" name="estado">
        </div>
      </div>

      <div class="text-center mt-4">
        <button type="submit" class="btn btn-primary px-4">Salvar</button>
        <button type="button" class="btn btn-primary ms-2 px-4" onclick="history.back()">Voltar</button>
      </div>
    </form>
  </div>

  <script>
    $(document).ready(function () {
      $('#cnpj_empresa').mask('00.000.000/0000-00');
      $('#telefone').mask('(00) 00000-0000');
      $('#cep').mask('00000-000');
    });
  </script>
</body>

</html>
