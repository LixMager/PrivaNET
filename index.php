<?php
/**
 * FRONT CONTROLLER - PRIVANET
 * Este archivo actúa como el punto de entrada principal y enrutador.
 */

// 2. Procesar Peticiones POST (Login / Logout)
//No ejecuta el codigo pos linea 38 si se ingresa en este if
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Acción: Login
    if (isset($_POST['login-user'])) {
        // En la vida real aquí verificaríamos la contraseña contra la BD usando password_verify()
        // Si es correcta, generamos un token seguro único:
        
        // MOCK: Usaremos nuestro token estático para poder validarlo luego
        $token_to_save = bin2hex(random_bytes(32)); 
        
        // Creamos la cookie HttpOnly y Segura
        //atk=authentication token
        // Parámetros: nombre, valor, expiración (30 días), ruta, dominio, secure (false en localhost), httpOnly (true)
        setcookie('atk', $token_to_save, time() + (86400 * 30), "/", "", false, true);
        
        // Redirigir para limpiar el POST y recargar con la cookie
        header("Location: index.php");
        exit;
    }

    // Acción: Cerrar Sesión
    if (isset($_POST['action']) && $_POST['action'] === 'logout') {
        /*
        // --- LÓGICA DE BD PARA LOGOUT (A IMPLEMENTAR) ---
        // $token_cookie = $_COOKIE['atk'] ?? '';
        // DELETE FROM sesiones WHERE rtk_hash = hash('sha256', $token_cookie);
        */
        
        // Destruimos la cookie asignándole una expiración en el pasado
        setcookie('atk', '', time() - 3600, "/"); 
        
        // Redirigir al inicio público
        header("Location: index.php");
        exit;
    }

    // Acción: Crear Posteo (MOCK)
    if (isset($_POST['action']) && $_POST['action'] === 'create_post') {
        // Verificamos de forma rápida que el usuario tenga una cookie (simulación de seguridad)
        if (isset($_COOKIE['atk'])) {
            session_start();
            
            $post_text = trim($_POST['post_text'] ?? '');
            $post_id = time(); // ID simulado único basado en tiempo
            
            $new_post = [
                'id' => $post_id,
                'text' => $post_text,
                'image' => null,
                'audio' => null
            ];

            // Ruta base para este posteo (usuario 1, posteo nuevo)
            $dir = __DIR__ . '/assets/uploads/users/1/posts/' . $post_id . '/';
            $relative_dir = 'assets/uploads/users/1/posts/' . $post_id . '/';

            // Procesar imagen
            if (isset($_FILES['post_image']) && $_FILES['post_image']['error'] === UPLOAD_ERR_OK) {
                if (!is_dir($dir)) mkdir($dir, 0777, true);
                $ext = pathinfo($_FILES['post_image']['name'], PATHINFO_EXTENSION);
                $img_name = 'img_' . rand(1000, 9999) . '.' . $ext;
                if (move_uploaded_file($_FILES['post_image']['tmp_name'], $dir . $img_name)) {
                    $new_post['image'] = $relative_dir . $img_name;
                }
            }

            // Procesar audio
            if (isset($_FILES['post_audio']) && $_FILES['post_audio']['error'] === UPLOAD_ERR_OK) {
                if (!is_dir($dir)) mkdir($dir, 0777, true);
                $ext = pathinfo($_FILES['post_audio']['name'], PATHINFO_EXTENSION);
                $audio_name = 'aud_' . rand(1000, 9999) . '.' . $ext;
                if (move_uploaded_file($_FILES['post_audio']['tmp_name'], $dir . $audio_name)) {
                    $new_post['audio'] = $relative_dir . $audio_name;
                }
            }

            // Si el posteo no está completamente vacío, lo guardamos en sesión
            if (!empty($new_post['text']) || !empty($new_post['image']) || !empty($new_post['audio'])) {
                if (!isset($_SESSION['posteos'])) {
                    $_SESSION['posteos'] = [];
                }
                $_SESSION['posteos'][] = $new_post;
            }
        }
        
        header("Location: index.php");
        exit;
    }
}

// 3. Validación de Autenticación
$is_logged_in = false;

if (isset($_COOKIE['atk'])) {
    // 1. Obtenemos la llave plana del navegador
    $token_cookie = $_COOKIE['atk'];
    
    // 2. La hasheamos para buscarla de forma segura
    $hash_busqueda = hash('sha256', $token_cookie);

    /*
    // --- LÓGICA REAL DE BASE DE DATOS (Descomentar al implementar BD) ---
    
    // Preparamos la consulta (ejemplo con PDO)
    $stmt = $db->prepare("SELECT user_id FROM sesiones_dispositivos WHERE rtk_hash = ?");
    $stmt->execute([$hash_busqueda]);
    $sesion_valida = $stmt->fetch();
    
    if ($sesion_valida) {
        // ¡Match perfecto! El usuario existe y el token es válido
        session_start();
        $_SESSION['usuario_autenticado'] = true;
        $_SESSION['user_id'] = $sesion_valida['user_id'];
        $is_logged_in = true;
    } else {
        // Token revocado, inventado o expirado. Forzamos logout de seguridad
        setcookie('atk', '', time() - 3600, "/");
        $is_logged_in = false;
    }
    */

    // --- MOCK TEMPORAL (Borrar al conectar la BD) ---
    // Aceptamos cualquier token existente para mantener el flujo de pruebas
    session_start();
    $_SESSION['usuario_autenticado'] = true;
    $is_logged_in = true;
}

// 4. Enrutador (Router)
if ($is_logged_in) {
    // Si está logueado, mostramos el Entorno Privado (Feed)
    require_once __DIR__ . '/private/view/Homepage/index.php';
} else {
    // Si no está logueado, mostramos el Entorno Público (Login/Registro)
    require_once __DIR__ . '/public/view/Login/index.php';
}
