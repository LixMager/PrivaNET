<?php
define('ROOT_PATH', __DIR__);
define('APP_PATH', __DIR__ . '/src');

require_once APP_PATH . '/Models/Publication.php';
require_once APP_PATH . '/Database/Database.php';

$config = [
    'host' => '127.0.0.1',
    'port' => 3306,
    'dbname' => 'privanet',
    'username' => 'root',
    'password' => ''
];
$dbInstance = new \App\Database\Database($config);
$db = $dbInstance->getConnection();

if (!$db) {
    die("No database connection.\n");
}

function copy_resource($filename, $userId, $postId, $type) {
    $src = ROOT_PATH . '/resources/' . ($type === 'audio' ? 'mp3sound/' : '') . $filename;
    $dstDir = ROOT_PATH . '/public/assets/uploads/users/' . $userId . '/posts/' . $postId;
    
    if (!is_dir($dstDir)) {
        mkdir($dstDir, 0777, true);
    }
    
    $extension = pathinfo($filename, PATHINFO_EXTENSION);
    $prefix = $type === 'audio' ? 'aud_' : 'img_';
    $dstName = $prefix . time() . '_' . rand(100, 999) . '.' . $extension;
    $dstPath = $dstDir . '/' . $dstName;
    
    if (file_exists($src)) {
        copy($src, $dstPath);
        return 'public/assets/uploads/users/' . $userId . '/posts/' . $postId . '/' . $dstName;
    }
    return null;
}

// 1. Create Users
$users = [
    [
        'username' => 'mariana_viajes',
        'email' => 'mariana@example.com',
        'password' => 'Pass1234',
        'country' => 'Argentina',
        'birth_date' => '1990-05-15'
    ],
    [
        'username' => 'dj_lucas',
        'email' => 'lucas@example.com',
        'password' => 'Pass1234',
        'country' => 'España',
        'birth_date' => '1995-10-20'
    ],
    [
        'username' => 'foto_natura',
        'email' => 'natura@example.com',
        'password' => 'Pass1234',
        'country' => 'Chile',
        'birth_date' => '1985-02-10'
    ]
];

$userIds = [];
foreach ($users as $u) {
    $stmt = $db->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->execute([$u['username']]);
    $id = $stmt->fetchColumn();
    
    if (!$id) {
        $stmt = $db->prepare("INSERT INTO users (username, email, password_hash, country, birth_date) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([
            $u['username'], 
            $u['email'], 
            password_hash($u['password'], PASSWORD_DEFAULT),
            $u['country'],
            $u['birth_date']
        ]);
        $id = $db->lastInsertId();
    }
    $userIds[$u['username']] = $id;
}

// 2. Create Posts
$postsData = [
    [
        'user' => 'mariana_viajes',
        'text' => '<p>¡Qué hermoso lugar! <strong><span style="color: rgb(41, 128, 185);">El Chaltén</span></strong> es increíble. Recomendado al 100%. <a href="https://es.wikipedia.org/wiki/El_Chalt%C3%A9n" target="_blank" rel="noopener noreferrer">Más info aquí</a>.</p>',
        'image' => 'el-chaltenjpeg.jpg',
        'audio' => null,
        'visibility' => 'public'
    ],
    [
        'user' => 'dj_lucas',
        'text' => '<p>Preparando el nuevo <em><span style="color: rgb(155, 89, 182);">set de RAVE</span></em> para esta noche. ¡Suban el volumen! 🎧🔥</p>',
        'image' => null,
        'audio' => 'RAVE.mp3',
        'visibility' => 'public'
    ],
    [
        'user' => 'foto_natura',
        'text' => '<p>Una mariposa perfecta captada esta mañana. <span style="color: rgb(241, 196, 15);">¡La naturaleza es sorprendente!</span> 🦋</p>',
        'image' => 'alexas_fotos-butterfly-10286598_1920.jpg',
        'audio' => 'alexzavesa-calm-elegant-logo-519008.mp3',
        'visibility' => 'public'
    ],
    [
        'user' => 'mariana_viajes',
        'text' => '<h2><span style="color: rgb(192, 57, 43);">Nepal 2026</span></h2><p>Llegamos a Nepal. La energía que se siente en los templos no se puede describir con palabras. 🧘‍♀️</p>',
        'image' => 'sightseer-nepal-9352723_1920.jpg',
        'audio' => null,
        'visibility' => 'public'
    ],
    [
        'user' => 'foto_natura',
        'text' => '<p>Un pequeño detalle de una margarita floreciendo 🌼. <u>La primavera ya llegó</u>.</p>',
        'image' => 'ruslansikunov-daisy-9920493_1920.jpg',
        'audio' => null,
        'visibility' => 'public'
    ]
];

foreach ($postsData as $idx => $p) {
    $uid = $userIds[$p['user']];
    $postId = time() + $idx; // unique folder
    
    $imgPath = $p['image'] ? copy_resource($p['image'], $uid, $postId, 'image') : null;
    $audPath = $p['audio'] ? copy_resource($p['audio'], $uid, $postId, 'audio') : null;
    
    $stmt = $db->prepare("
        INSERT INTO posts (user_id, text_content, image_path, audio_path, visibility, published_at) 
        VALUES (?, ?, ?, ?, ?, NOW())
    ");
    $stmt->execute([
        $uid,
        $p['text'],
        $imgPath,
        $audPath,
        $p['visibility']
    ]);
}

echo "Seeded successfully!\n";
