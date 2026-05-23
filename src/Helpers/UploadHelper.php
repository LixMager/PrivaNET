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
     * @throws \InvalidArgumentException Si la validación falla
     */
    public static function upload(array $file, string $destinationDir, string $relativeDir, string $prefix): ?string {
        if (!isset($file['error']) || $file['error'] === UPLOAD_ERR_NO_FILE) {
            return null;
        }

        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new \InvalidArgumentException("Error al subir el archivo: Código de error " . $file['error']);
        }

        // 1. Validaciones para imagen (JPEG, resolución <= 1600x1200)
        if ($prefix === 'img_') {
            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            if ($ext !== 'jpg' && $ext !== 'jpeg') {
                throw new \InvalidArgumentException("La imagen debe tener formato JPEG (.jpg o .jpeg).");
            }

            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_file($finfo, $file['tmp_name']);
            finfo_close($finfo);
            if ($mime !== 'image/jpeg') {
                throw new \InvalidArgumentException("El archivo subido no es una imagen JPEG válida.");
            }

            $size = @getimagesize($file['tmp_name']);
            if ($size === false) {
                throw new \InvalidArgumentException("El archivo de imagen no es válido.");
            }
            $width = $size[0];
            $height = $size[1];
            if ($width > 1600 || $height > 1200) {
                throw new \InvalidArgumentException("La resolución de la imagen no puede ser mayor a 1600 x 1200 píxeles.");
            }
        }

        // 2. Validaciones para audio (MP3, duración <= 30 segundos)
        if ($prefix === 'aud_') {
            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            if ($ext !== 'mp3') {
                throw new \InvalidArgumentException("El archivo de audio debe tener formato MP3 (.mp3).");
            }

            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_file($finfo, $file['tmp_name']);
            finfo_close($finfo);
            if ($mime !== 'audio/mpeg' && $mime !== 'audio/mp3') {
                throw new \InvalidArgumentException("El audio debe ser de tipo MIME audio/mpeg.");
            }

            // Validar la duración en el servidor
            $duration = self::getMp3Duration($file['tmp_name']);
            if ($duration > 30.5) { // Pequeño margen por diferencias de redondeo o metadatos
                throw new \InvalidArgumentException("El audio supera el límite de 30 segundos (duración: " . round($duration, 1) . " segundos).");
            }
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

    /**
     * Calcula la duración estimada de un archivo MP3 en segundos leyendo sus cabeceras.
     */
    public static function getMp3Duration(string $path): float {
        $fd = @fopen($path, "rb");
        if (!$fd) return 0.0;
        
        // Omitir tag ID3v2 si está presente
        $id3v2 = fread($fd, 10);
        if (substr($id3v2, 0, 3) === 'ID3') {
            $size = (ord($id3v2[6]) & 0x7F) << 21
                  | (ord($id3v2[7]) & 0x7F) << 14
                  | (ord($id3v2[8]) & 0x7F) << 7
                  | (ord($id3v2[9]) & 0x7F);
            fseek($fd, $size + 10, SEEK_SET);
        } else {
            fseek($fd, 0, SEEK_SET);
        }
        
        $duration = 0.0;
        $fileSize = filesize($path);
        
        while (!feof($fd)) {
            $pos = ftell($fd);
            if ($pos >= $fileSize) break;
            
            $byte1 = fread($fd, 1);
            if (ord($byte1) === 0xFF) {
                $byte2 = fread($fd, 1);
                if ((ord($byte2) & 0xE0) === 0xE0) {
                    $b3 = fread($fd, 1);
                    $b4 = fread($fd, 1);
                    $header = ord($byte1) << 24 | ord($byte2) << 16 | ord($b3) << 8 | ord($b4);
                    
                    $version = ($header >> 19) & 3;
                    $layer = ($header >> 17) & 3;
                    $bitrateIndex = ($header >> 12) & 15;
                    
                    $bitrateV1L3 = [0, 32, 40, 48, 56, 64, 80, 96, 112, 128, 160, 192, 224, 256, 320, 0];
                    $bitrateV2L3 = [0, 8, 16, 24, 32, 40, 48, 56, 64, 80, 96, 112, 128, 144, 160, 0];
                    
                    $bitrate = 0;
                    if ($version === 3) { // V1
                        if ($layer === 1) { // Layer 3
                            $bitrate = $bitrateV1L3[$bitrateIndex] ?? 0;
                        }
                    } else { // V2/V2.5
                        if ($layer === 1) { // Layer 3
                            $bitrate = $bitrateV2L3[$bitrateIndex] ?? 0;
                        }
                    }
                    
                    if ($bitrate > 0) {
                        $audioDataSize = $fileSize - $pos;
                        $duration = ($audioDataSize * 8) / ($bitrate * 1000);
                    }
                    break;
                }
            }
        }
        
        fclose($fd);
        return $duration;
    }
}
