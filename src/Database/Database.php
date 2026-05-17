<?php
namespace App\Database;

use PDO;
use PDOException;

class Database
{
    private string $host;
    private int $port;
    private string $db_name;
    private string $username;
    private string $password;
    private ?PDO $conn = null;

    public function __construct(array $config)
    {
        $this->host = $config['host'] ?? 'localhost';
        $this->port = $config['port'] ?? 3306;
        $this->db_name = $config['dbname'] ?? 'privanet';
        $this->username = $config['user'] ?? 'root';
        $this->password = $config['password'] ?? '';
    }

    public function getConnection(): ?PDO
    {
        $this->conn = null;
        try {
            /*
            $this->conn = new PDO(
                "mysql:host={$this->host};port={$this->port};dbname={$this->db_name};charset=utf8mb4",
                $this->username,
                $this->password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]
            );
            */
        } catch (PDOException $exception) {
            // echo "Error de conexión: " . $exception->getMessage();
        }
        return $this->conn;
    }
}
?>