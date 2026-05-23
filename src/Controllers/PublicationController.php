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

        unset($_SESSION['post_error']);

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

        $isScheduled = isset($postData['is_scheduled']) && $postData['is_scheduled'] === 'on';
        $scheduledAt = null;

        if ($isScheduled) {
            $scheduledDateStr = $postData['scheduled_date_utc'] ?? '';
            if (empty($scheduledDateStr)) {
                $scheduledDateStr = $postData['scheduled_date'] ?? '';
            }

            if (empty($scheduledDateStr)) {
                $_SESSION['post_text_draft'] = $postData['post_text'] ?? '';
                $_SESSION['post_error'] = "Debe seleccionar una fecha y hora para programar la publicación.";
                header("Location: /PrivaNet/publicar");
                exit;
            }

            $scheduledTimestamp = strtotime($scheduledDateStr);
            if ($scheduledTimestamp === false) {
                $_SESSION['post_text_draft'] = $postData['post_text'] ?? '';
                $_SESSION['post_error'] = "La fecha y hora de programación ingresada no es válida.";
                header("Location: /PrivaNet/publicar");
                exit;
            }

            $currentTimestamp = time();
            $maxFutureTimestamp = $currentTimestamp + 3 * 86400;

            // Al menos 1 minuto en el futuro (toleramos hasta 50 segundos del actual por desfase de envío)
            if ($scheduledTimestamp < ($currentTimestamp + 50)) {
                $_SESSION['post_text_draft'] = $postData['post_text'] ?? '';
                $_SESSION['post_error'] = "La fecha y hora de programación debe ser al menos 1 minuto en el futuro.";
                header("Location: /PrivaNet/publicar");
                exit;
            }

            if ($scheduledTimestamp > $maxFutureTimestamp) {
                $_SESSION['post_text_draft'] = $postData['post_text'] ?? '';
                $_SESSION['post_error'] = "La publicación no se puede programar con más de 3 días de anticipación.";
                header("Location: /PrivaNet/publicar");
                exit;
            }

            $scheduledAt = date('Y-m-d H:i:s', $scheduledTimestamp);
        }

        $post_text = $postData['post_text'] ?? '';
        $post_text = \App\Helpers\SanitizerHelper::sanitize($post_text);
        
        // Extraer texto plano para validaciones de longitud y vaciedad real (Quill genera tags como <p><br></p>)
        $plainText = trim(strip_tags(html_entity_decode($post_text)));
        
        if (mb_strlen($plainText) > 255) {
            $_SESSION['post_text_draft'] = $postData['post_text'] ?? '';
            $_SESSION['post_error'] = "El texto de la publicación no puede superar los 255 caracteres.";
            header("Location: /PrivaNet/publicar");
            exit;
        }

        // Si no hay texto real, lo seteamos como vacío para que no guarde tags HTML vacíos
        if (empty($plainText)) {
            $post_text = '';
        }

        $post_id = time(); // ID único basado en tiempo para la carpeta de este posteo

        // Rutas base para los archivos de este posteo usando el ID dinámico del usuario
        $destinationDir = ROOT_PATH . '/public/assets/uploads/users/' . $user_id . '/posts/' . $post_id . '/';
        $relativeDir = 'public/assets/uploads/users/' . $user_id . '/posts/' . $post_id . '/';

        $imagePath = null;
        $audioPath = null;

        try {
            if (isset($filesData['post_image'])) {
                $imagePath = UploadHelper::upload($filesData['post_image'], $destinationDir, $relativeDir, 'img_');
            }

            if (isset($filesData['post_audio'])) {
                $audioPath = UploadHelper::upload($filesData['post_audio'], $destinationDir, $relativeDir, 'aud_');
            }
        } catch (\InvalidArgumentException $e) {
            $_SESSION['post_text_draft'] = $postData['post_text'] ?? '';
            $_SESSION['post_error'] = $e->getMessage();
            header("Location: /PrivaNet/publicar");
            exit;
        }

        if (!empty($post_text) || !empty($imagePath) || !empty($audioPath)) {
            // Pasamos null como ID porque la base de datos lo autogenera con AUTO_INCREMENT
            $publication = new Publication(null, $user_id, $post_text, $imagePath, $audioPath, null, null, $scheduledAt, $scheduledAt);
            $this->repository->save($publication);
        }

        header("Location: index.php");
        exit;
    }
}
