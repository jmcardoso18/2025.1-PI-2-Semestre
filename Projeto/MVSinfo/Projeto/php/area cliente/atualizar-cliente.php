<?php
session_start();
require_once '../Conexao.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['tipoUsuario'] != 1) {
    header('Location: /Projeto/MVSinfo/Projeto/usuario/login_view.php');
    exit;
}

$conexao = new conexao();
$pdo = $conexao->getPDO();

// Pega o ID do cliente logado
$idUsuario = $_SESSION['id_usuario'] ?? null;

if (!$idUsuario) {
    echo "Erro: Usuário não identificado.";
    exit;
}

// Captura os dados do formulário
$nome = trim($_POST['nome'] ?? '');
$email = trim($_POST['email'] ?? '');
$telefone = trim($_POST['telefone'] ?? '');
$empresa = trim($_POST['empresa'] ?? '');

if (empty($nome) || empty($email) || empty($telefone) || empty($empresa)) {
    echo "<div style='color:red; font-family: Arial;'>Por favor, preencha todos os campos.</div>";
    echo "<a href='area-cliente.php' style='display:inline-block; margin-top:10px; padding:10px 20px; background:#1976f2; color:white; text-decoration:none; border-radius:5px;'>Voltar</a>";
    exit;
}

try {
    $sql = "UPDATE usuario SET 
                razao_social = :nome,
                email = :email,
                telefone = :telefone,
                nome_fantasia = :empresa
            WHERE id_usuario = :id";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':nome' => $nome,
        ':email' => $email,
        ':telefone' => $telefone,
        ':empresa' => $empresa,
        ':id' => $idUsuario
    ]);

    echo "<div style='color:green; font-family: Arial;'>Dados atualizados com sucesso!</div>";
    echo "<a href='area-cliente.php' style='display:inline-block; margin-top:10px; padding:10px 20px; background:#1976f2; color:white; text-decoration:none; border-radius:5px;'>Voltar ao Perfil</a>";

} catch (PDOException $e) {
    echo "<div style='color:red; font-family: Arial;'>Erro ao atualizar: " . $e->getMessage() . "</div>";
    echo "<a href='area-cliente.php' style='display:inline-block; margin-top:10px; padding:10px 20px; background:#1976f2; color:white; text-decoration:none; border-radius:5px;'>Voltar</a>";
}
?>
