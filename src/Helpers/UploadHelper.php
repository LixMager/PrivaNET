<?php
namespace App\Helpers;

class UploadHelper {
    /**
     * Sube un archivo a un directorio específico
     * @param array $file Archivo de $_FILES
     * @param string $destinationDir Directorio destino absoluto
     * @param string $relativeDir Directorio relativo para guardar en la BD/modelo
     * @param string $prefix Prefijo para el nombre del archivo (ej. img_, aud_)
     * @return string|null Devuelve la ruta relativa si tuvo éxito, o null
     */
    public static function upload(array $file, string $destinationDir, string $relativeDir, string $prefix): ?string {
        if (!isset($file['error']) || $file['error'] !== UPLOAD_ERR_OK) {
            return null;
        }

        if (!is_dir($destinationDir)) {
            mkdir($destinationDir, 0777, true);
        }

        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $fileName = $prefix . rand(1000, 9999) . '.' . $ext;
        $fullPath = $destinationDir . $fileName;

        if (move_uploaded_file($file['tmp_name'], $fullPath)) {
            return $relativeDir . $fileName;
        }

        return null;
    }
}
