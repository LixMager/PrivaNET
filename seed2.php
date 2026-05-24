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

// 1. Create More Users
$users = [
    [
        'username' => 'arquitectura_top',
        'email' => 'arq@example.com',
        'password' => 'Pass1234',
        'country' => 'Colombia',
        'birth_date' => '1988-03-22'
    ],
    [
        'username' => 'tech_guru',
        'email' => 'tech@example.com',
        'password' => 'Pass1234',
        'country' => 'México',
        'birth_date' => '1992-11-05'
    ],
    [
        'username' => 'chill_vibes',
        'email' => 'chill@example.com',
        'password' => 'Pass1234',
        'country' => 'Uruguay',
        'birth_date' => '1998-07-12'
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

// 2. Create More Posts
$postsData = [
    [
        'user' => 'arquitectura_top',
        'text' => '<h3><span style="color: rgb(44, 62, 80);">Arte Islámico</span></h3><p>La mezquita es una verdadera obra de arte. <strong>El nivel de detalle</strong> en su construcción me deja sin palabras. <br><br><span style="color: rgb(127, 140, 141);">#Arquitectura #Viajes</span></p>',
        'image' => 'derweg-mosque-4902814_1920.jpg',
        'audio' => null,
        'visibility' => 'public'
    ],
    [
        'user' => 'tech_guru',
        'text' => '<p>Arrancando el día con buena música inspiradora. 🚀 <span style="color: rgb(41, 128, 185);">Programando el futuro...</span></p>',
        'image' => 'notas_kidriotaudioalert-header.jpg',
        'audio' => 'alexzavesa-calm-inspiring-technology-logo-short-version-518993.mp3',
        'visibility' => 'public'
    ],
    [
        'user' => 'arquitectura_top',
        'text' => '<p>Paisaje urbano al atardecer. 🏙️ <span style="color: rgb(230, 126, 34);">Los colores que regala la ciudad a esta hora son mágicos.</span></p>',
        'image' => 'nguyen_may-cityscape-9944334_1920.jpg',
        'audio' => null,
        'visibility' => 'public'
    ],
    [
        'user' => 'chill_vibes',
        'text' => '<p>Solo necesito mi guitarra y un buen rato a solas. 🎸 <br><em><span style="color: rgb(39, 174, 96);">Relaxing loop para todos ustedes.</span></em></p>',
        'image' => null,
        'audio' => 'idoberg-relaxing-guitar-loop-v5-245859.mp3',
        'visibility' => 'public'
    ],
    [
        'user' => 'chill_vibes',
        'text' => '<p><span style="color: rgb(142, 68, 173);"><strong>Perspectiva diferente.</strong></span> A veces hay que cambiar el ángulo desde el que vemos las cosas. 🙃</p>',
        'image' => '840_560.jpg',
        'audio' => null,
        'visibility' => 'public'
    ]
];

foreach ($postsData as $idx => $p) {
    $uid = $userIds[$p['user']];
    $postId = time() + $idx + 100; // unique folder
    
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

echo "More seeded successfully!\n";
