<?php require_once("../conexao.php"); ?>

<?php
$cpf = $_GET['cpf'];

if ($_POST) {
    $sql = "UPDATE clientes SET 
        nomeCompleto=?, 
        dataNascimento=?, 
        telefone=?, 
        email=?, 
        cep=?, 
        logradouro=?, 
        numero=?, 
        complemento=?, 
        bairro=?, 
        cidade=?, 
        estado=?, 
        razaoSocial=?, 
        nomeFantasia=?, 
        cnpj=?, 
        inscricaoEstadual=?, 
        nomeResponsavel=? 
        WHERE cpf=?";
    
    $stmt = $pdo->prepare($sql);
    if ($stmt->execute([
        $_POST['nomeCompleto'],
        $_POST['dataNascimento'],
        $_POST['telefone'],
        $_POST['email'],
        $_POST['cep'],
        $_POST['logradouro'],
        $_POST['numero'],
        $_POST['complemento'],
        $_POST['bairro'],
        $_POST['cidade'],
        $_POST['estado'],
        $_POST['razaoSocial'],
        $_POST['nomeFantasia'],
        $_POST['cnpj'],
        $_POST['inscricaoEstadual'],
        $_POST['nomeResponsavel'],
        $cpf
    ])) {
        echo "Atualizado com sucesso!";
    } else {
        echo "Erro ao atualizar!";
    }
}

$sql = "SELECT * FROM clientes WHERE cpf=?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$cpf]);
$cliente = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<h2>Editar Cliente</h2>
<form method="POST">
    Nome Completo: <input type="text" name="nomeCompleto" value="<?= $cliente['nomeCompleto'] ?>"><br>
    Data Nascimento: <input type="date" name="dataNascimento" value="<?= $cliente['dataNascimento'] ?>"><br>
    Telefone: <input type="text" name="telefone" value="<?= $cliente['telefone'] ?>"><br>
    Email: <input type="email" name="email" value="<?= $cliente['email'] ?>"><br>
    CEP: <input type="text" name="cep" value="<?= $cliente['cep'] ?>"><br>
    Logradouro: <input type="text" name="logradouro" value="<?= $cliente['logradouro'] ?>"><br>
    Número: <input type="text" name="numero" value="<?= $cliente['numero'] ?>"><br>
    Complemento: <input type="text" name="complemento" value="<?= $cliente['complemento'] ?>"><br>
    Bairro: <input type="text" name="bairro" value="<?= $cliente['bairro'] ?>"><br>
    Cidade: <input type="text" name="cidade" value="<?= $cliente['cidade'] ?>"><br>
    Estado: <input type="text" name="estado" value="<?= $cliente['estado'] ?>"><br>
    Razão Social: <input type="text" name="razaoSocial" value="<?= $cliente['razaoSocial'] ?>"><br>
    Nome Fantasia: <input type="text" name="nomeFantasia" value="<?= $cliente['nomeFantasia'] ?>"><br>
    CNPJ: <input type="text" name="cnpj" value="<?= $cliente['cnpj'] ?>"><br>
    Inscrição Estadual: <input type="text" name="inscricaoEstadual" value="<?= $cliente['inscricaoEstadual'] ?>"><br>
    Nome do Responsável: <input type="text" name="nomeResponsavel" value="<?= $cliente['nomeResponsavel'] ?>"><br><br>

    <input type="submit" value="Salvar">
</form>
