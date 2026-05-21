<?php
namespace App\Controllers;

use App\Database\Database;
use PDO;

class AuthController {
    private ?PDO $db;

    public function __construct(Database $database) {
        $this->db = $database->getConnection();
    }

    /**
     * Verificación AJAX en tiempo real de disponibilidad de nombre de usuario
     */
    public function checkUsername(array $postData): void {
        header('Content-Type: application/json');

        $username = trim($postData['username'] ?? '');

        if (empty($username)) {
            echo json_encode(['available' => false, 'message' => 'El nombre de usuario no puede estar vacío.']);
            exit;
        }

        if (!$this->db) {
            echo json_encode(['available' => false, 'message' => 'Error de conexión a la base de datos.']);
            exit;
        }

        $stmt = $this->db->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $count = $stmt->fetchColumn();

        if ($count > 0) {
            echo json_encode(['available' => false, 'message' => 'El nombre de usuario ya está en uso.']);
        } else {
            echo json_encode(['available' => true, 'message' => 'Nombre de usuario disponible.']);
        }
        exit;
    }

    /**
     * Procesar y validar el formulario de registro de usuario
     */
    public function register(array $postData): void {
        header('Content-Type: application/json');

        $username = trim($postData['register-user'] ?? '');
        $password = $postData['register-password'] ?? '';
        $email = trim($postData['register-email'] ?? '');
        $birthdate = trim($postData['register-birthdate'] ?? '');
        $country = trim($postData['register-country'] ?? '');

        // 1. Validar campos obligatorios
        if (empty($username) || empty($password) || empty($email) || empty($birthdate) || empty($country)) {
            echo json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios.']);
            exit;
        }

        // 2. Validar nombre de usuario único
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
        $stmt->execute([$username]);
        if ($stmt->fetchColumn() > 0) {
            echo json_encode(['success' => false, 'message' => 'El nombre de usuario ya está registrado.']);
            exit;
        }

        // 3. Validar correo electrónico único y formato
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['success' => false, 'message' => 'El formato del correo electrónico no es válido.']);
            exit;
        }
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetchColumn() > 0) {
            echo json_encode(['success' => false, 'message' => 'El correo electrónico ya está registrado.']);
            exit;
        }

        // 4. Validar contraseña: al menos 8 caracteres
        if (strlen($password) < 8) {
            echo json_encode(['success' => false, 'message' => 'La contraseña debe tener al menos 8 caracteres.']);
            exit;
        }

        // 5. Validar fecha de nacimiento: mayor de 13 años
        try {
            $bdate = new \DateTime($birthdate);
            $now = new \DateTime();
            $age = $now->diff($bdate)->y;

            if ($age <= 13) {
                echo json_encode(['success' => false, 'message' => 'Debes ser mayor de 13 años para poder registrarte.']);
                exit;
            }
        } catch (\Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Fecha de nacimiento no válida.']);
            exit;
        }

        // 6. Insertar usuario en la base de datos
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $this->db->prepare("INSERT INTO users (username, email, password_hash, birth_date, country) VALUES (?, ?, ?, ?, ?)");
        $inserted = $stmt->execute([$username, $email, $passwordHash, $birthdate, $country]);

        if ($inserted) {
            $userId = (int)$this->db->lastInsertId();

            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $_SESSION['usuario_autenticado'] = true;
            $_SESSION['user_id'] = $userId;

            $token_to_save = bin2hex(random_bytes(32));
            $token_hash = hash('sha256', $token_to_save);
            $expires_at = date('Y-m-d H:i:s', time() + (86400 * 30));

            $tokenStmt = $this->db->prepare("
                INSERT INTO remember_tokens (user_id, token_hash, expires_at, created_at)
                VALUES (?, ?, ?, NOW())
            ");
            $tokenStmt->execute([$userId, $token_hash, $expires_at]);

            setcookie('atk', $token_to_save, time() + (86400 * 30), "/", "", false, true);
            setcookie('user_name', $username, time() + (86400 * 30), "/", "", false, true);

            echo json_encode(['success' => true, 'message' => 'Registro exitoso. Iniciando sesión...']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Ocurrió un error al registrar el usuario.']);
        }
        exit;
    }

    public function login(array $postData): void {
        header('Content-Type: application/json');

        $username = trim($postData['login-user'] ?? '');
        $password = $postData['login-password'] ?? '';

        if (empty($username) || empty($password)) {
            echo json_encode(['success' => false, 'message' => 'Por favor, ingresa tu usuario y contraseña.']);
            exit;
        }

        if (!$this->db) {
            echo json_encode(['success' => false, 'message' => 'Error de conexión a la base de datos.']);
            exit;
        }

        $stmt = $this->db->prepare("SELECT id, username, password_hash FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $_SESSION['usuario_autenticado'] = true;
            $_SESSION['user_id'] = (int)$user['id'];

            // Generar token de sesión
            $token_to_save = bin2hex(random_bytes(32));
            $token_hash = hash('sha256', $token_to_save);
            $expires_at = date('Y-m-d H:i:s', time() + (86400 * 30));

            $tokenStmt = $this->db->prepare("
                INSERT INTO remember_tokens (user_id, token_hash, expires_at, created_at)
                VALUES (?, ?, ?, NOW())
            ");
            $tokenStmt->execute([$user['id'], $token_hash, $expires_at]);

            setcookie('atk', $token_to_save, time() + (86400 * 30), "/", "", false, true);
            setcookie('user_name', $user['username'], time() + (86400 * 30), "/", "", false, true);

            // Actualizar last_login_at en users
            $updateStmt = $this->db->prepare("UPDATE users SET last_login_at = NOW() WHERE id = ?");
            $updateStmt->execute([$user['id']]);

            echo json_encode(['success' => true, 'message' => 'Inicio de sesión exitoso. Redirigiendo...']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Usuario o contraseña incorrectos.']);
        }
        exit;
    }

    public function logout(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (isset($_COOKIE['atk']) && $this->db) {
            $token_hash = hash('sha256', $_COOKIE['atk']);
            $stmt = $this->db->prepare("DELETE FROM remember_tokens WHERE token_hash = ?");
            $stmt->execute([$token_hash]);
        }

        setcookie('atk', '', time() - 3600, "/");
        setcookie('user_name', '', time() - 3600, "/");

        // Limpiar la sesión PHP por completo
        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();

        header("Location: index.php");
        exit;
    }
}
