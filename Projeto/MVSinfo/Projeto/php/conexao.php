<?php
class conexao {
    private $host = 'localhost';
    private $dbname = 'LojaSistema';
    private $user = 'root';
    private $pass = '';
    private $pdo;

    public function __construct(){
        try {
            $this->pdo = new PDO(
                "mysql:host={$this->host};dbname={$this->dbname}",
                $this->user,
                $this->pass
            );
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo 'Erro na conexão: ' . $e->getMessage();
            exit;
        }
    }

    public function getPdo() {
        return $this->pdo;
    }
}
?>
