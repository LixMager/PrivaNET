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
        if ($this->conn !== null) {
            return $this->conn;
        }

        try {
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
        } catch (PDOException $exception) {
            echo "Error de conexión: " . $exception->getMessage() . "<br>";
        }

        return $this->conn;
    }

    public function loadSchema(): void
    {
        try {
            // 1. Conectar a MySQL sin seleccionar base de datos para poder crearla si no existe
            $pdo = new PDO(
                "mysql:host={$this->host};port={$this->port};charset=utf8mb4",
                $this->username,
                $this->password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                ]
            );

            // 2. Crear la base de datos y seleccionarla
            $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$this->db_name}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
            $pdo->exec("USE `{$this->db_name}`;");

            // 3. Cargar el archivo schema.sql
            $schema_path = APP_PATH . '/Database/schema.sql';
            if (!file_exists($schema_path)) {
                echo "El archivo de esquema no existe en: {$schema_path}<br>";
                return;
            }

            $schema_sql = file_get_contents($schema_path);

            // Dividir el archivo en sentencias individuales por punto y coma
            $queries = explode(';', $schema_sql);

            foreach ($queries as $query) {
                $query = trim($query);
                if (empty($query)) {
                    continue;
                }

                try {
                    $pdo->exec($query);
                } catch (PDOException $e) {
                    // Ignorar advertencias benignas como 1050 (tabla ya existe), 1061 (índice ya existe) o "already exists"
                    $code = $e->getCode();
                    $msg = $e->getMessage();
                    if ($code != 1050 && strpos($msg, '1050') === false && $code != 1061 && strpos($msg, '1061') === false && strpos($msg, 'already exists') === false) {
                        echo "Advertencia al ejecutar consulta: " . $msg . "<br>";
                    }
                }
            }

            // Actualizar la conexión de la instancia para que quede lista para su uso normal
            $this->conn = $pdo;
            $this->conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            echo "Base de datos cargada correctamente";
        } catch (PDOException $e) {
            echo "Error al cargar el esquema: " . $e->getMessage() . "<br>";
        }
    }
}
?>