<?php require_once("../conexao.php"); ?>
<?php
$cpf = $_GET['cpf'];
$sql = "DELETE FROM clientes WHERE cpf=?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$cpf]);
header("Location: listar.php");
?>
