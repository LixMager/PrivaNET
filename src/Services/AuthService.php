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
            $username = $_COOKIE['user_name'] ?? null;
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
                    
                    // Asegurar que la cookie de username coincida
                    if (!isset($_COOKIE['user_name']) || $_COOKIE['user_name'] !== $username) {
                        setcookie('user_name', $username, time() + (86400 * 30), "/", "", false, true);
                    }
                    $authenticated = true;
                } else {
                    // Token inválido o caducado
                    setcookie('atk', '', time() - 3600, "/");
                    setcookie('user_name', '', time() - 3600, "/");
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

        // Manejar la cookie 'ultimo_acceso' específica del usuario
        if ($authenticated && $username !== null) {
            // Nombre de cookie seguro basado en el nombre de usuario
            $cookie_name = 'ultimo_acceso_' . preg_replace('/[^a-zA-Z0-9_]/', '', $username);

            if (!isset($_SESSION['mostrar_ultimo_acceso'])) {
                if (isset($_COOKIE[$cookie_name])) {
                    $_SESSION['mostrar_ultimo_acceso'] = $_COOKIE[$cookie_name];
                } else {
                    $_SESSION['mostrar_ultimo_acceso'] = 'Esta es tu primera visita en este dispositivo';
                }
                // Actualizar la cookie con la fecha/hora actual
                setcookie($cookie_name, date('d/m/Y H:i:s'), time() + (86400 * 365), "/", "", false, true);
            }
        }

        return $authenticated;
    }
}
