<?php
define('ROOT_PATH', dirname(__DIR__));
define('CONFIG_PATH', ROOT_PATH . '/config');
define('APP_PATH', ROOT_PATH . '/src');
define('VIEW_PATH', APP_PATH . '/view');

// Cargar configuración de base de datos
require_once APP_PATH . '/Database/Database.php';
$dbConfig = require_once CONFIG_PATH . '/db_config.php';

//Loader de clases php, evita el uso de require y include en la carga de clases
// PSR-4 Autoloader para la carpeta src (namespace App\)
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $base_dir = APP_PATH . '/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }
    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});
