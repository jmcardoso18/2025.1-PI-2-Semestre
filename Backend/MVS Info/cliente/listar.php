<?php require_once("../conexao.php"); ?>

<h2>Lista de Clientes</h2>

<table border="1">
    <tr>
        <th>CPF</th><th>Nome</th><th>Telefone</th><th>Email</th><th>Ações</th>
    </tr>

<?php
$sql = "SELECT * FROM clientes";
foreach ($pdo->query($sql) as $row) {
    echo "<tr>";
    echo "<td>".$row['cpf']."</td>";
    echo "<td>".$row['nomeCompleto']."</td>";
    echo "<td>".$row['telefone']."</td>";
    echo "<td>".$row['email']."</td>";
    echo "<td>
        <a href='editar.php?cpf=".$row['cpf']."'>Editar</a> | 
        <a href='deletar.php?cpf=".$row['cpf']."'>Deletar</a> | 
        <a href='index.php?cpf=".$row['cpf']."'>Voltar ao Menu</a>
    </td>";
    echo "</tr>";
}
?>
</table>
