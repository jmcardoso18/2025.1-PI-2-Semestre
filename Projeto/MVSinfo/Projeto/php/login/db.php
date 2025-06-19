<?php

class db {
    private $pdo;
    private $table = 'usuario';

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Busca usuários pelo id do tipo_usuario (numérico).
     *
     * @param int $tipoUsuarioId
     * @return array Array associativo com os usuários encontrados (login, senha).
     */
    public function getUsersByTipoUsuarioId(int $tipoUsuarioId): array {
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
