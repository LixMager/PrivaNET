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

        // Manejar la cookie 'ultimo_acceso' para registrar el acceso actual y recordar el anterior
        if (!isset($_SESSION['mostrar_ultimo_acceso'])) {
            if (isset($_COOKIE['ultimo_acceso'])) {
                $_SESSION['mostrar_ultimo_acceso'] = $_COOKIE['ultimo_acceso'];
            } else {
                $_SESSION['mostrar_ultimo_acceso'] = 'Esta es tu primera visita en este dispositivo';
            }
            // Actualizar la cookie con la fecha/hora actual
            setcookie('ultimo_acceso', date('d/m/Y H:i:s'), time() + (86400 * 365), "/", "", false, true);
        }

        // Si ya está establecido el ID de usuario en la sesión PHP, no es necesario consultar la base de datos
        if (isset($_SESSION['usuario_autenticado']) && $_SESSION['usuario_autenticado'] === true && isset($_SESSION['user_id'])) {
            return true;
        }

        if (!isset($_COOKIE['atk'])) {
            return false;
        }

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
                
                // Asegurar que la cookie de username coincida
                if (!isset($_COOKIE['user_name']) || $_COOKIE['user_name'] !== $sesion_valida['username']) {
                    setcookie('user_name', $sesion_valida['username'], time() + (86400 * 30), "/", "", false, true);
                }
                return true;
            } else {
                // Token inválido o caducado
                setcookie('atk', '', time() - 3600, "/");
                setcookie('user_name', '', time() - 3600, "/");
                return false;
            }
        }

        // MOCK temporal si no hay base de datos disponible
        $_SESSION['usuario_autenticado'] = true;
        $_SESSION['user_id'] = 1;
        return true;
    }
}
