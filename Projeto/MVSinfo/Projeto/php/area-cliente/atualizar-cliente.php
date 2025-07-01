<?php
session_start();
require_once '../Conexao.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['tipoUsuario'] != 1) {
    header('Location: /Projeto/MVSinfo/Projeto/usuario/login_view.php');
    exit;
}

$conexao = new conexao();
$pdo = $conexao->getPdo(); // Corrigi para getPdo() - sensível a maiúsculas/minúsculas

// Pega o ID do cliente logado
$idUsuario = $_SESSION['id_usuario'] ?? null;

if (!$idUsuario) {
    echo "Erro: Usuário não identificado.";
    exit;
}

// Captura os dados do formulário
$razao_social = trim($_POST['razao_social'] ?? '');
$email = trim($_POST['email'] ?? '');
$telefone = trim($_POST['telefone'] ?? '');
$contato = trim($_POST['contato'] ?? ''); // Substituindo "empresa" pelo campo correto contato

// Validação dos campos essenciais
if (empty($razao_social) || empty($email)) {
    echo "<div style='color:red; font-family: Arial;'>Por favor, preencha os campos Razão Social e E-mail.</div>";
    echo "<a href='area-cliente.php' style='display:inline-block; margin-top:10px; padding:10px 20px; background:#1976f2; color:white; text-decoration:none; border-radius:5px;'>Voltar</a>";
    exit;
}

try {
    $sql = "UPDATE usuario SET 
                razao_social = :razao_social,
                email = :email,
                telefone = :telefone,
                contato = :contato
            WHERE id_usuario = :id";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':razao_social' => $razao_social,
        ':email' => $email,
        ':telefone' => $telefone,
        ':contato' => $contato,
        ':id' => $idUsuario
    ]);

    echo "<div style='color:green; font-family: Arial;'>Dados atualizados com sucesso!</div>";
    echo "<a href='area-cliente.php' style='display:inline-block; margin-top:10px; padding:10px 20px; background:#1976f2; color:white; text-decoration:none; border-radius:5px;'>Voltar ao Perfil</a>";

} catch (PDOException $e) {
    echo "<div style='color:red; font-family: Arial;'>Erro ao atualizar: " . htmlspecialchars($e->getMessage()) . "</div>";
    echo "<a href='area-cliente.php' style='display:inline-block; margin-top:10px; padding:10px 20px; background:#1976f2; color:white; text-decoration:none; border-radius:5px;'>Voltar</a>";
}
?>
