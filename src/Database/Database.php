<?php
class Database
{
    private string $host = 'localhost';
    private string $db_name = 'privanet';
    private string $username = 'root';
    private string $password = '';
    private ?PDO $conn = null;

    public function getConnection(): ?PDO
    {
        $this->conn = null;

        try {
            // Descomentar y ajustar cuando la base de datos esté creada
            /*
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8mb4",
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