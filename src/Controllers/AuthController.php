<?php
namespace App\Controllers;

class AuthController {
    public function login(array $postData): void {
        if (isset($postData['login-user'])) {
            // MOCK temporal: token estático
            $token_to_save = bin2hex(random_bytes(32));
            setcookie('atk', $token_to_save, time() + (86400 * 30), "/", "", false, true);

            header("Location: index.php");
            exit;
        }
    }

    public function logout(): void {
        setcookie('atk', '', time() - 3600, "/");
        header("Location: index.php");
        exit;
    }
}
