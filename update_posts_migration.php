<?php
require_once __DIR__ . '/config/app.php';

try {
    $dbTemp = new \App\Database\Database($dbConfig);
    $connTemp = $dbTemp->getConnection();
    if ($connTemp) {
        $updates = [
            2 => 'Otro <strong>ejemplo</strong> de publicación <i>visible</i> para cualquier visitante no registrado.',
            3 => 'Hoy tuve una <b>visita inesperada</b> <span style="color: #ff5555">sorprendente</span>.',
            4 => 'Así está quedando mi <strong>setup</strong>... ¿Qué <a href="https://ejemplo.com">opinan</a>?',
            5 => 'Dicen que en la <i>catedral</i> hace <b>mucho frío</b> en invierno... Pero eso no me quita las ganas de ir a esquiar!!',
            6 => 'Me llegó un <i>aviso de visita</i> de un paquete. Me comuniqué al siguiente día y ya lo devolvieron al emisor. <strong>Pésimo servicio!</strong>',
            7 => '“Tal vez la noche sea la <i>vida</i> y el sol la <b>muerte</b>.<br>Tal vez la noche es nada y las conjeturas sobre ella nada y los seres que la viven nada.”'
        ];
        foreach ($updates as $id => $text) {
            $stmt = $connTemp->prepare("SELECT COUNT(*) FROM posts WHERE id = ?");
            $stmt->execute([$id]);
            if ($stmt->fetchColumn() > 0) {
                $stmt = $connTemp->prepare("UPDATE posts SET text_content = ? WHERE id = ?");
                $stmt->execute([$text, $id]);
                echo "Post $id updated successfully.\n";
            } else {
                echo "Post $id not found.\n";
            }
        }
    } else {
        echo "Could not connect to database.\n";
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
