<?php
define('ROOT_PATH', __DIR__);
define('APP_PATH', __DIR__ . '/src');

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

// 1. Create Generic Users
$genericUsersCount = 15;
$genericUserIds = [];

for ($i = 1; $i <= $genericUsersCount; $i++) {
    $username = 'usuario_fantasma_' . $i;
    $email = 'fantasma' . $i . '@example.com';
    $passwordHash = password_hash('Pass1234', PASSWORD_DEFAULT);
    $country = 'Argentina';
    $birthDate = '199' . rand(0, 9) . '-0' . rand(1, 9) . '-1' . rand(0, 9);
    
    $stmt = $db->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $id = $stmt->fetchColumn();
    
    if (!$id) {
        $stmt = $db->prepare("INSERT INTO users (username, email, password_hash, country, birth_date) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$username, $email, $passwordHash, $country, $birthDate]);
        $id = $db->lastInsertId();
    }
    $genericUserIds[] = $id;
}

// 2. Fetch existing posts
$stmt = $db->query("SELECT id FROM posts");
$postIds = $stmt->fetchAll(PDO::FETCH_COLUMN);

if (empty($postIds)) {
    die("No posts found in database.\n");
}

// 3. Generate Interactions
// We will clear existing generic interactions first to avoid cluttering if run multiple times
$db->exec("DELETE FROM post_likes WHERE user_id IN (SELECT id FROM users WHERE username LIKE 'usuario_fantasma_%')");
$db->exec("DELETE FROM post_dislikes WHERE user_id IN (SELECT id FROM users WHERE username LIKE 'usuario_fantasma_%')");
$db->exec("DELETE FROM favorites WHERE user_id IN (SELECT id FROM users WHERE username LIKE 'usuario_fantasma_%')");

foreach ($genericUserIds as $userId) {
    // Randomly interact with some posts (e.g. 3 to 8 posts per user)
    $interactionsCount = rand(3, count($postIds));
    $shuffledPosts = $postIds;
    shuffle($shuffledPosts);
    $selectedPosts = array_slice($shuffledPosts, 0, $interactionsCount);
    
    foreach ($selectedPosts as $postId) {
        // Randomize what they do:
        // 70% chance to Like, 30% chance to Dislike
        $action = (rand(1, 10) <= 7) ? 'like' : 'dislike';
        
        // 40% chance to also favorite
        $favorite = (rand(1, 10) <= 4);
        
        if ($action === 'like') {
            $stmt = $db->prepare("INSERT IGNORE INTO post_likes (user_id, post_id, created_at) VALUES (?, ?, NOW())");
            $stmt->execute([$userId, $postId]);
        } else {
            $stmt = $db->prepare("INSERT IGNORE INTO post_dislikes (user_id, post_id, created_at) VALUES (?, ?, NOW())");
            $stmt->execute([$userId, $postId]);
        }
        
        if ($favorite) {
            $stmt = $db->prepare("INSERT IGNORE INTO favorites (user_id, post_id, created_at) VALUES (?, ?, NOW())");
            $stmt->execute([$userId, $postId]);
        }
    }
}

echo "Seeded " . count($genericUserIds) . " users with random interactions!\n";
