<?php
if (isset($_POST['setor']) && !empty($_POST['setor'])) {
    $setor = $_POST['setor'];
    header("Location: " . $setor);
    exit;
} else {
    echo "Por favor, selecione um setor válido.";
    echo '<br><a href="index.php">Voltar</a>';
}
?>
