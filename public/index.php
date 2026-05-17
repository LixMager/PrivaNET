<?php
// Cargar configuración principal y autoloader
require_once dirname(__DIR__) . '/config/app.php';

use App\Controllers\AuthController;
use App\Controllers\PublicationController;
use App\Database\Database;
use App\Services\AuthService;

/**
 * FRONT CONTROLLER - PRIVANET
 * Este archivo actúa como el punto de entrada principal y enrutador.
 */

// 1. Validación de Autenticación
$db = new Database($dbConfig);
$authService = new AuthService($db);
$is_logged_in = $authService->checkSession();

// 2. Procesar Peticiones POST (Login / Logout / Publicaciones)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    // Acción: Login
    if (isset($_POST['login-user'])) {
        (new AuthController())->login($_POST);
    }

    // Acción: Cerrar Sesión
    if ($action === 'logout') {
        (new AuthController())->logout();
    }

    // Acción: Crear Posteo
    if ($action === 'create_post') {
        (new PublicationController($db))->store($_POST, $_FILES);
    }

    exit;
}

// 3. Enrutador (Router) de Vistas
if ($is_logged_in) {
    // Si está logueado, mostramos el Entorno Privado (Feed)
    require_once VIEW_PATH . '/Homepage/index.php';
} else {
    // Si no está logueado, mostramos el Entorno Público (Login/Registro)
    require_once VIEW_PATH . '/Login/index.php';
}