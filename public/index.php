<?php
// Cargar configuración principal y autoloader
require_once dirname(__DIR__) . '/config/app.php';

use App\Controllers\AuthController;
use App\Controllers\PublicationController;
use App\Database\Database;
use App\Services\AuthService;
use App\Services\Router;

/**
 * FRONT CONTROLLER - PRIVANET
 * Este archivo actúa como el punto de entrada principal y enrutador.
 */

// 1. Validación de Autenticación
$db = new Database($dbConfig);
#$db->getConnection();
//Lineas para carga de base de datos:
//$db->loadSchema();
//$db->loadData();//carga datos de prueba para presentacion
//exit;

$authService = new AuthService($db);
$is_logged_in = $authService->checkSession();

// 2. Procesar Peticiones POST (Login / Logout / Registro / Publicaciones)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    // Acción: Verificación AJAX de nombre de usuario
    if ($action === 'check_username') {
        (new AuthController($db))->checkUsername($_POST);
    }

    // Acción: Registro de Usuario
    if ($action === 'register') {
        (new AuthController($db))->register($_POST);
    }

    // Acción: Login
    if ($action === 'login' || isset($_POST['login-user'])) {
        (new AuthController($db))->login($_POST);
    }

    // Acción: Cerrar Sesión
    if ($action === 'logout') {
        (new AuthController($db))->logout();
    }

    // Acción: Crear Posteo
    if ($action === 'create_post') {
        (new PublicationController($db))->store($_POST, $_FILES);
    }

    // Acción: Dar Like (AJAX)
    if ($action === 'toggle_like') {
        header('Content-Type: application/json');
        if (!$is_logged_in || !isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'message' => 'Inicia sesión para interactuar.']);
            exit;
        }
        $post_id = (int)($_POST['post_id'] ?? 0);
        $repo = new \App\Repositories\PublicationRepository($db);
        $result = $repo->toggleLike($post_id, (int)$_SESSION['user_id']);
        echo json_encode($result);
        exit;
    }

    // Acción: Dar Dislike (AJAX)
    if ($action === 'toggle_dislike') {
        header('Content-Type: application/json');
        if (!$is_logged_in || !isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'message' => 'Inicia sesión para interactuar.']);
            exit;
        }
        $post_id = (int)($_POST['post_id'] ?? 0);
        $repo = new \App\Repositories\PublicationRepository($db);
        $result = $repo->toggleDislike($post_id, (int)$_SESSION['user_id']);
        echo json_encode($result);
        exit;
    }

    // Acción: Dar Favorito (AJAX)
    if ($action === 'toggle_favorite') {
        header('Content-Type: application/json');
        if (!$is_logged_in || !isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'message' => 'Inicia sesión para interactuar.']);
            exit;
        }
        $post_id = (int)($_POST['post_id'] ?? 0);
        $repo = new \App\Repositories\PublicationRepository($db);
        $result = $repo->toggleFavorite($post_id, (int)$_SESSION['user_id']);
        echo json_encode($result);
        exit;
    }
    // Acción: Actualizar Post (AJAX)
    if ($action === 'update_post') {
        header('Content-Type: application/json');
        if (!$is_logged_in || !isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'message' => 'Inicia sesión para realizar cambios.']);
            exit;
        }
        $post_id = (int)($_POST['post_id'] ?? 0);
        $post_text = $_POST['post_text'] ?? '';
        
        // Sanitizar el HTML del texto del posteo
        $post_text = \App\Helpers\SanitizerHelper::sanitize($post_text);
        
        // Extraer texto plano para validación de longitud y vaciedad
        $plainText = trim(strip_tags(html_entity_decode($post_text)));
        
        if (empty($plainText)) {
            echo json_encode(['success' => false, 'message' => 'El texto no puede estar vacío.']);
            exit;
        }
        
        if (mb_strlen($plainText) > 255) {
            echo json_encode(['success' => false, 'message' => 'El texto no puede superar los 255 caracteres.']);
            exit;
        }
        
        $repo = new \App\Repositories\PublicationRepository($db);
        $success = $repo->updateText($post_id, (int)$_SESSION['user_id'], $post_text);
        if ($success) {
            echo json_encode(['success' => true, 'text' => $post_text]);
        } else {
            echo json_encode(['success' => false, 'message' => 'No se pudo actualizar el post. Verifica que seas el autor.']);
        }
        exit;
    }

    // Acción: Eliminar Post (AJAX)
    if ($action === 'delete_post') {
        header('Content-Type: application/json');
        if (!$is_logged_in || !isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'message' => 'Inicia sesión para realizar cambios.']);
            exit;
        }
        $post_id = (int)($_POST['post_id'] ?? 0);
        $repo = new \App\Repositories\PublicationRepository($db);
        $success = $repo->delete($post_id, (int)$_SESSION['user_id']);
        if ($success) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'No se pudo eliminar el post. Verifica que seas el autor.']);
        }
        exit;
    }

    exit;
}

// 2.1 Procesar Petición GET AJAX para Actividad de Usuario
if (isset($_GET['action']) && $_GET['action'] === 'get_user_activity') {
    if (!$is_logged_in || !isset($_SESSION['user_id'])) {
        http_response_code(401);
        echo "Inicia sesión para ver tu actividad.";
        exit;
    }
    $type = $_GET['type'] ?? 'like';
    $userId = (int)$_SESSION['user_id'];
    $repo = new \App\Repositories\PublicationRepository($db);
    $publications = $repo->getPublicationsByInteraction($userId, $type);
    
    if (empty($publications)) {
        echo '<p class="no-posts" style="text-align: center; padding: 2rem; color: var(--text-muted, #888);">No hay publicaciones en esta sección.</p>';
    } else {
        foreach ($publications as $post) {
            include APP_PATH . '/View/components/post_card.php';
        }
    }
    exit;
}

// 3. Enrutador (Router) de Vistas
$router = new Router();
$router->dispatch($is_logged_in);