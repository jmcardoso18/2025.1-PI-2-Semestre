<?php

class db {
    private $pdo;
    private $table = 'usuario';

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getUsers($tipoUsuario) {
        
        switch($tipoUsuario){
            case 'cliente':
                $tipoUsuarioId = 0;
                break;
            case 'fornecedor':
                $tipoUsuarioId = 1;
                break;
            case 'admin':
                $tipoUsuarioId = 2;
                break;
            default:
                return [];
        }

$sql = "SELECT login, senha 
                FROM {$this->table}
                WHERE tipo_usuario = :tipoUsuario";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':tipoUsuario', $tipoUsuarioId, PDO::PARAM_INT);

        if ($stmt->execute()) {
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    return [];

    }
}
?>
