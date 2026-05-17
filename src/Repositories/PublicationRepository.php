<?php
namespace App\Repositories;

use App\Models\Publication;
use Database;

class PublicationRepository {
    private ?\PDO $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function save(Publication $publication): bool {
        if ($this->db) {
            // Lógica futura de base de datos
            /*
            $stmt = $this->db->prepare("INSERT INTO publicaciones (id, text, image, audio) VALUES (?, ?, ?, ?)");
            return $stmt->execute([
                $publication->getId(),
                $publication->getText(),
                $publication->getImage(),
                $publication->getAudio()
            ]);
            */
        }

        // MOCK temporal con $_SESSION
        if (!isset($_SESSION['posteos'])) {
            $_SESSION['posteos'] = [];
        }

        $_SESSION['posteos'][] = $publication->toArray();
        return true;
    }

    public function getAll(): array {
        if ($this->db) {
            // Lógica futura de base de datos
            /*
            $stmt = $this->db->query("SELECT * FROM publicaciones ORDER BY id DESC");
            return $stmt->fetchAll();
            */
        }

        // MOCK temporal con $_SESSION
        return $_SESSION['posteos'] ?? [];
    }
}
