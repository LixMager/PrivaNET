<?php

namespace App\Services;

class Router
{
    private array $knownRoutes;
    private string $baseSubdir;

    public function __construct(array $knownRoutes = ['/', '/index.php', '/public/index.php', '/buscar', '/publicar', '/actividad', '/panel'], string $baseSubdir = '/PrivaNet')
    {
        $this->knownRoutes = $knownRoutes;
        $this->baseSubdir = $baseSubdir;
    }

    /**
     * Valida la ruta actual y despacha la vista correspondiente
     * según el estado de autenticación del usuario.
     */
    public function dispatch(bool $is_logged_in): void
    {
        $request_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $route = rtrim(str_replace($this->baseSubdir, '', $request_uri), '/');
        $route = $route ?: '/';

        // 1. Validación de Ruta Existente (404)
        if (!in_array($route, $this->knownRoutes)) {
            http_response_code(404);

            // Detección de petición AJAX o API
            if (strpos($route, '/api/') === 0 || (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest')) {
                header('Content-Type: application/json');
                echo json_encode(['error' => 'Endpoint no encontrado', 'code' => 404]);
            } else {
                require_once VIEW_PATH . '/404/index.php';
            }
            exit;
        }

        // 2. Despacho de Vistas para Rutas Conocidas
        if ($is_logged_in) {
            switch ($route) {
                case '/buscar':
                    require_once VIEW_PATH . '/Search_Result/index.php';
                    break;
                case '/publicar':
                    require_once VIEW_PATH . '/Publish/index.php';
                    break;
                case '/actividad':
                    require_once VIEW_PATH . '/Activity/index.php';
                    break;
                case '/panel':
                    require_once VIEW_PATH . '/Dashboard/index.php';
                    break;
                case '/':
                case '/index.php':
                case '/public/index.php':
                default:
                    require_once VIEW_PATH . '/Homepage/index.php';
                    break;
            }
        } else {
            // Usuario sin sesión → Entorno Público (Login/Registro)
            require_once VIEW_PATH . '/Login/index.php';
        }
    }
}
