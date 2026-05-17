<?php
namespace App\Services;

use App\Database\Database;

class AuthService {
    private ?\PDO $db;

    public function __construct(Database $database) {
        $this->db = $database->getConnection();
    }

    public function checkSession(): bool {
        if (!isset($_COOKIE['atk'])) {
            return false;
        }

        $token_cookie = $_COOKIE['atk'];
        $hash_busqueda = hash('sha256', $token_cookie);

        if ($this->db) {
            // Lógica real de base de datos
            /*
            $stmt = $this->db->prepare("SELECT user_id FROM sesiones_dispositivos WHERE rtk_hash = ?");
            $stmt->execute([$hash_busqueda]);
            $sesion_valida = $stmt->fetch();

            if ($sesion_valida) {
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }
                $_SESSION['usuario_autenticado'] = true;
                $_SESSION['user_id'] = $sesion_valida['user_id'];
                return true;
            } else {
                setcookie('atk', '', time() - 3600, "/");
                return false;
            }
            */
        }

        // MOCK temporal
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['usuario_autenticado'] = true;
        return true;
    }
}
