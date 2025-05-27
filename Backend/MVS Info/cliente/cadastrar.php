<?php require_once '../conexao.php'; ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastrar Cliente</title>
</head>
<body>

    <h1>Cadastrar Cliente</h1>

    <form action="salvar.php" method="POST">

        <h3>Dados Pessoais</h3>
        CPF: <input type="text" name="cpf" required><br><br>
        Nome Completo: <input type="text" name="nomeCompleto" required><br><br>
        Data de Nascimento: <input type="date" name="dataNascimento"><br><br>
        Telefone: <input type="text" name="telefone"><br><br>
        Email: <input type="email" name="email"><br><br>

        <h3>Endereço</h3>
        CEP: <input type="text" name="cep"><br><br>
        Logradouro: <input type="text" name="logradouro"><br><br>
        Número: <input type="text" name="numero"><br><br>
        Complemento: <input type="text" name="complemento"><br><br>
        Bairro: <input type="text" name="bairro"><br><br>
        Cidade: <input type="text" name="cidade"><br><br>
        Estado: <input type="text" name="estado"><br><br>

        <h3>Dados Empresariais (se houver)</h3>
        Razão Social: <input type="text" name="razaoSocial"><br><br>
        Nome Fantasia: <input type="text" name="nomeFantasia"><br><br>
        CNPJ: <input type="text" name="cnpj"><br><br>
        Inscrição Estadual: <input type="text" name="inscricaoEstadual"><br><br>
        Nome do Responsável: <input type="text" name="nomeResponsavel"><br><br>

        <input type="submit" value="Salvar">

    </form>

    <br>
    <a href="index.php">Voltar para o Menu.</a>

</body>
</html>
