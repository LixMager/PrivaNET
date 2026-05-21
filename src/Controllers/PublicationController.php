<?php
namespace App\Controllers;

use App\Database\Database;
use App\Helpers\UploadHelper;
use App\Models\Publication;
use App\Repositories\PublicationRepository;

class PublicationController {
    private PublicationRepository $repository;
    private ?\PDO $db;

    public function __construct(Database $database) {
        $this->repository = new PublicationRepository($database);
        $this->db = $database->getConnection();
    }

    public function store(array $postData, array $filesData): void {
        if (!isset($_COOKIE['atk'])) {
            header("Location: index.php");
            exit;
        }

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Buscar el ID del usuario logueado, priorizando la sesión segura de PHP
        $user_id = $_SESSION['user_id'] ?? null;
        if (!$user_id) {
            $username = $_COOKIE['user_name'] ?? '';
            $user_id = 1; // Fallback predeterminado si no se encuentra
            if ($this->db && !empty($username)) {
                $stmt = $this->db->prepare("SELECT id FROM users WHERE username = ?");
                $stmt->execute([$username]);
                $id = $stmt->fetchColumn();
                if ($id) {
                    $user_id = (int)$id;
                }
            }
        }

        $post_text = trim($postData['post_text'] ?? '');
        $post_id = time(); // ID único basado en tiempo para la carpeta de este posteo

        // Rutas base para los archivos de este posteo usando el ID dinámico del usuario
        $destinationDir = ROOT_PATH . '/public/assets/uploads/users/' . $user_id . '/posts/' . $post_id . '/';
        $relativeDir = 'public/assets/uploads/users/' . $user_id . '/posts/' . $post_id . '/';

        $imagePath = null;
        if (isset($filesData['post_image'])) {
            $imagePath = UploadHelper::upload($filesData['post_image'], $destinationDir, $relativeDir, 'img_');
        }

        $audioPath = null;
        if (isset($filesData['post_audio'])) {
            $audioPath = UploadHelper::upload($filesData['post_audio'], $destinationDir, $relativeDir, 'aud_');
        }

        if (!empty($post_text) || !empty($imagePath) || !empty($audioPath)) {
            // Pasamos null como ID porque la base de datos lo autogenera con AUTO_INCREMENT
            $publication = new Publication(null, $user_id, $post_text, $imagePath, $audioPath);
            $this->repository->save($publication);
        }

        header("Location: index.php");
        exit;
    }
}
