<?php
session_start();
require_once '../../Conexao.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['tipoUsuario'] != 3) {
    header('Location: ../../usuario/login_view.php');
    exit;
}

$conexao = new conexao();
$pdo = $conexao->getPdo();

$sql = "SELECT id_usuario, razao_social, contato, email, telefone FROM usuario WHERE tipo_usuario = 2 ORDER BY razao_social ASC";
// Buscar fornecedores (tipo_usuario = 2)
$sql = "SELECT id_usuario, razao_social, email, telefone FROM usuario WHERE tipo_usuario = 2 ORDER BY razao_social ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$fornecedores = $stmt->fetchAll(PDO::FETCH_ASSOC);

$status = $_GET['status'] ?? '';
$mensagem = '';

switch ($status) {
    case 'excluido':
        $mensagem = '<p class="msg sucesso">✅ Fornecedor excluído com sucesso!</p>';
        break;
    case 'fornecedor_nao_encontrado':
        $mensagem = '<p class="msg erro">❌ Fornecedor não encontrado.</p>';
        break;
    case 'erro_id':
        $mensagem = '<p class="msg erro">❌ ID inválido.</p>';
        break;
    case 'erro_delete':
        $mensagem = '<p class="msg erro">❌ Erro ao excluir o fornecedor.</p>';
        break;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Fornecedores - Área Administrador</title>
    <link rel="stylesheet" href="../css/styles.css">
    <style>
         /* Estrutura Principal */
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            color: #333;
            line-height: 1.6;
        }
        
        header {
            background: linear-gradient(135deg, #1976f2, #0d5bba);
            color: white;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        main {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 2rem;
            background: white;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0,0,0,0.05);
        }
        
        /* Navegação */
        nav {
            display: flex;
            gap: 1.5rem;
        }
        
        nav a {
            color: white;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            padding: 0.5rem;
            border-radius: 4px;
        }
        
        nav a:hover {
            background: rgba(255,255,255,0.2);
            transform: translateY(-2px);
        }
        
        /* Títulos */
        h1, h2 {
            margin: 0;
            font-weight: 600;
        }
        
        h1 {
            font-size: 1.8rem;
        }
        
        h2 {
            color: #1976f2;
            margin-bottom: 1.5rem;
            text-align: center;
            font-size: 1.5rem;
        }
        
        /* Mensagens */
        .msg {
            padding: 1rem;
            border-radius: 6px;
            margin-bottom: 1.5rem;
            text-align: center;
            font-weight: 500;
        }
        
        .sucesso {
            background-color: #e6f7ee;
            color: #28a745;
            border-left: 4px solid #28a745;
        }
        
        .erro {
            background-color: #fdecea;
            color: #dc3545;
            border-left: 4px solid #dc3545;
        }
        
        /* Tabela */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1.5rem;
            font-size: 0.95rem;
        }
        
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #e0e0e0;
        }
        
        th {
            background-color: #f5f7fa;
            color: #1976f2;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
        }
        
        tr:hover {
            background-color: #f5f9ff;
        }
        
        /* Botões */
        .btn {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 4px;
            font-weight: 500;
            text-align: center;
            transition: all 0.3s ease;
            text-decoration: none;
            font-size: 0.9rem;
            cursor: pointer;
        }
        
        .btn-primary {
            background-color: #1976f2;
            color: white;
            border: none;
        }
        
        .btn-primary:hover {
            background-color: #1565c0;
            transform: translateY(-1px);
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .btn-danger {
            background-color: #dc3545;
            color: white;
            border: none;
        }
        
        .btn-danger:hover {
            background-color: #c82333;
            transform: translateY(-1px);
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .btn-success {
            background-color: #28a745;
            color: white;
            border: none;
        }
        
        .btn-success:hover {
            background-color: #218838;
            transform: translateY(-1px);
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        /* Ações */
        .actions {
            display: flex;
            gap: 0.5rem;
        }
        
        /* Estado vazio */
        .empty-state {
            text-align: center;
            padding: 2rem;
            color: #6c757d;
        }
        
        /* Responsividade */
        @media (max-width: 768px) {
            header {
                flex-direction: column;
                padding: 1rem;
            }
            
            nav {
                margin-top: 1rem;
                flex-wrap: wrap;
                justify-content: center;
            }
            
            main {
                padding: 1rem;
                margin: 1rem;
            }
            
            table {
                display: block;
                overflow-x: auto;
            }
        }
    </style>
</head>
<body>
    <header>
        <h1>Área Administrador - Fornecedores</h1>
        <nav>
            <a href="../area-admin.php">Menu</a>
            <a href="admin-fornecedores-add.php">Adicionar Fornecedor</a> 
            <a href="../../logout.php">Sair</a>
        </nav>
    </header>

    <main>
        <h2>Lista de Fornecedores</h2>

        <?= $mensagem ?>
        <?php if (count($fornecedores) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Razão Social</th>
                        <th>Contato</th>
                        <th>Email</th>
                        <th>Telefone</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($fornecedores as $fornecedor): ?>
                        <tr>
                            <td><?= htmlspecialchars($fornecedor['id_usuario']) ?></td>
                            <td><?= htmlspecialchars($fornecedor['razao_social']) ?></td>
                            <td><?= !empty($fornecedor['contato']) ? htmlspecialchars($fornecedor['contato']) : 'Não informado' ?></td>
                            <td><?= htmlspecialchars($fornecedor['email']) ?></td>
                            <td><?= htmlspecialchars($fornecedor['telefone']) ?></td>
                            <td class="actions">
                               <a href="admin-fornecedores-edit.php?id=<?= $fornecedor['id_usuario'] ?>" class="btn btn-primary">Editar</a>
                                <a href="admin-fornecedores-delete.php?id=<?= $fornecedor['id_usuario'] ?>" class="btn btn-danger" onclick="return confirm('Tem certeza que deseja excluir este fornecedor?')">Excluir</a>
                            </td>
                        </tr>

                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Nenhum fornecedor cadastrado.</p>
            <a href="admin-fornecedores-add.php" class="btn add-btn">Adicionar Fornecedor</a> <!-- Mantido no empty state -->
        <?php endif; ?>
    </main>
</body>
</html>