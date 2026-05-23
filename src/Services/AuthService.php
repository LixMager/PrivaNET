<?php
namespace App\Services;

use App\Database\Database;

class AuthService {
    private ?\PDO $db;

    public function __construct(Database $database) {
        $this->db = $database->getConnection();
    }

    public function checkSession(): bool {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $authenticated = false;
        $username = null;

        // Si ya está establecido el ID de usuario en la sesión PHP, recuperamos el username
        if (isset($_SESSION['usuario_autenticado']) && $_SESSION['usuario_autenticado'] === true && isset($_SESSION['user_id'])) {
            $authenticated = true;
            $username = $_SESSION['username'] ?? null;
        } elseif (isset($_COOKIE['atk'])) {
            $token_cookie = $_COOKIE['atk'];
            $hash_busqueda = hash('sha256', $token_cookie);

            if ($this->db) {
                $stmt = $this->db->prepare("
                    SELECT r.user_id, u.username 
                    FROM remember_tokens r
                    JOIN users u ON r.user_id = u.id
                    WHERE r.token_hash = ? AND r.expires_at > NOW()
                ");
                $stmt->execute([$hash_busqueda]);
                $sesion_valida = $stmt->fetch();

                if ($sesion_valida) {
                    $_SESSION['usuario_autenticado'] = true;
                    $_SESSION['user_id'] = (int)$sesion_valida['user_id'];
                    $username = $sesion_valida['username'];
                    
                    // Asegurar que la sesión de username coincida
                    if (!isset($_SESSION['username']) || $_SESSION['username'] !== $username) {
                        $_SESSION['username'] = $username;
                    }
                    $authenticated = true;
                } else {
                    setcookie('atk', '', time() - 3600, "/");
                }
            }
        }

        // Fallback si no hay base de datos disponible (mock)
        if (!$authenticated && !$this->db) {
            $_SESSION['usuario_autenticado'] = true;
            $_SESSION['user_id'] = 1;
            $username = 'usuario';
            $authenticated = true;
        }

        // Manejar la cookie única de accesos (formato JSON)
        if ($authenticated && $username !== null) {
            if (!isset($_SESSION['mostrar_ultimo_acceso'])) {
                $safe_username = preg_replace('/[^a-zA-Z0-9_]/', '', $username);
                $cookie_name = 'ultimo_acceso_' . $safe_username;
                
                if (isset($_COOKIE[$cookie_name])) {
                    $decoded = json_decode($_COOKIE[$cookie_name], true);
                    if (is_array($decoded) && isset($decoded['last_access'])) {
                        $_SESSION['mostrar_ultimo_acceso'] = $decoded['last_access'];
                    } else {
                        // Fallback por si la cookie vieja no era JSON
                        $_SESSION['mostrar_ultimo_acceso'] = $_COOKIE[$cookie_name];
                    }
                } else {
                    $_SESSION['mostrar_ultimo_acceso'] = 'Esta es tu primera visita en este dispositivo';
                }
                
                // Guardar la cookie como un JSON con el usuario y la fecha
                $cookie_data = [
                    'username' => $safe_username,
                    'last_access' => gmdate('Y-m-d\TH:i:s\Z')
                ];
                setcookie($cookie_name, json_encode($cookie_data), time() + (86400 * 365), "/", "", false, true);
            }
        }

        return $authenticated;
    }
}
