<?php
namespace App\Controllers;

use App\Database\Database;
use App\Helpers\UploadHelper;
use App\Models\Publication;
use App\Repositories\PublicationRepository;

class PublicationController {
    private PublicationRepository $repository;

    public function __construct(Database $database) {
        $this->repository = new PublicationRepository($database);
    }

    public function store(array $postData, array $filesData): void {
        if (!isset($_COOKIE['atk'])) {
            header("Location: index.php");
            exit;
        }

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $post_text = trim($postData['post_text'] ?? '');
        $post_id = time(); // ID simulado único basado en tiempo

        // Rutas base para los archivos de este posteo
        $destinationDir = ROOT_PATH . '/public/assets/uploads/users/1/posts/' . $post_id . '/';
        $relativeDir = 'assets/uploads/users/1/posts/' . $post_id . '/';

        $imagePath = null;
        if (isset($filesData['post_image'])) {
            $imagePath = UploadHelper::upload($filesData['post_image'], $destinationDir, $relativeDir, 'img_');
        }

        $audioPath = null;
        if (isset($filesData['post_audio'])) {
            $audioPath = UploadHelper::upload($filesData['post_audio'], $destinationDir, $relativeDir, 'aud_');
        }

        if (!empty($post_text) || !empty($imagePath) || !empty($audioPath)) {
            $publication = new Publication($post_id, $post_text, $imagePath, $audioPath);
            $this->repository->save($publication);
        }

        header("Location: index.php");
        exit;
    }
}
