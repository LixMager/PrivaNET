<?php
namespace App\Repositories;

use App\Database\Database;
use App\Models\Publication;

class PublicationRepository {
    private ?\PDO $db;

    public function __construct(Database $database) {
        $this->db = $database->getConnection();
    }

    public function save(Publication $publication): bool {
        if ($this->db) {
            $scheduledAt = $publication->getScheduledAt();
            $publishedAt = $scheduledAt !== null ? $scheduledAt : null;

            $stmt = $this->db->prepare("
                INSERT INTO posts (user_id, text_content, image_path, audio_path, scheduled_at, published_at, visibility) 
                VALUES (?, ?, ?, ?, ?, COALESCE(?, NOW()), 'public')
            ");
            return $stmt->execute([
                $publication->getUserId(),
                $publication->getText(),
                $publication->getImage(),
                $publication->getAudio(),
                $scheduledAt,
                $publishedAt
            ]);
        }

        // Fallback para pruebas con sesión si la base de datos no está disponible
        if (!isset($_SESSION['posteos'])) {
            $_SESSION['posteos'] = [];
        }
        $_SESSION['posteos'][] = $publication->toArray();
        return true;
    }

    public function getAll(): array {
        if ($this->db) {
            $stmt = $this->db->query("
                SELECT p.id, p.user_id, p.text_content as text, p.image_path as image, p.audio_path as audio, p.created_at, p.scheduled_at, p.published_at, u.username
                FROM posts p
                JOIN users u ON p.user_id = u.id
                ORDER BY p.created_at DESC
            ");
            $rows = $stmt->fetchAll();
            $publications = [];
            foreach ($rows as $row) {
                $publications[] = new Publication(
                    (int)$row['id'],
                    (int)$row['user_id'],
                    $row['text'] ?? '',
                    $row['image'],
                    $row['audio'],
                    $row['username'],
                    $row['created_at'],
                    $row['scheduled_at'] ?? null,
                    $row['published_at'] ?? null
                );
            }
            return $publications;
        }

        // Fallback para pruebas con sesión si la base de datos no está disponible
        $sessionPosts = $_SESSION['posteos'] ?? [];
        $publications = [];
        foreach ($sessionPosts as $post) {
            $publications[] = new Publication(
                $post['id'] ?? null,
                $post['user_id'] ?? 1,
                $post['text'] ?? '',
                $post['image'] ?? null,
                $post['audio'] ?? null,
                $post['username'] ?? 'usuario',
                $post['created_at'] ?? 'Hace un momento',
                $post['scheduled_at'] ?? null,
                $post['published_at'] ?? null
            );
        }
        return $publications;
    }

    public function getLatestPublic(int $limit = 10, ?int $currentUserId = null): array {
        if ($this->db) {
            $hasInteractions = false;
            if ($currentUserId !== null) {
                $stmtCheck = $this->db->prepare("
                    SELECT EXISTS(SELECT 1 FROM post_likes WHERE user_id = ?) OR EXISTS(SELECT 1 FROM favorites WHERE user_id = ?)
                ");
                $stmtCheck->execute([$currentUserId, $currentUserId]);
                $hasInteractions = (bool)$stmtCheck->fetchColumn();
            }

            if ($currentUserId !== null) {
                if ($hasInteractions) {
                    $stmt = $this->db->prepare("
                        SELECT p.id, p.user_id, p.text_content as text, p.image_path as image, p.audio_path as audio, p.created_at, p.scheduled_at, p.published_at, u.username,
                               (pl.user_id IS NOT NULL) as is_liked,
                               (pd.user_id IS NOT NULL) as is_disliked,
                               (f.user_id IS NOT NULL) as is_favorited
                        FROM posts p
                        JOIN users u ON p.user_id = u.id
                        LEFT JOIN post_likes pl ON p.id = pl.post_id AND pl.user_id = :current_user_id_like
                        LEFT JOIN post_dislikes pd ON p.id = pd.post_id AND pd.user_id = :current_user_id_dislike
                        LEFT JOIN favorites f ON p.id = f.post_id AND f.user_id = :current_user_id_fav
                        WHERE p.visibility = 'public' 
                          AND (p.published_at IS NULL OR p.published_at <= NOW())
                          AND (p.user_id = :current_user_id_self OR p.user_id IN (
                              SELECT DISTINCT p2.user_id 
                              FROM posts p2
                              WHERE p2.id IN (
                                  SELECT post_id FROM post_likes WHERE user_id = :current_user_id_int1
                                  UNION
                                  SELECT post_id FROM favorites WHERE user_id = :current_user_id_int2
                              )
                          ))
                        ORDER BY p.created_at DESC
                        LIMIT :limit
                    ");
                    $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
                    $stmt->bindValue(':current_user_id_like', $currentUserId, \PDO::PARAM_INT);
                    $stmt->bindValue(':current_user_id_dislike', $currentUserId, \PDO::PARAM_INT);
                    $stmt->bindValue(':current_user_id_fav', $currentUserId, \PDO::PARAM_INT);
                    $stmt->bindValue(':current_user_id_int1', $currentUserId, \PDO::PARAM_INT);
                    $stmt->bindValue(':current_user_id_int2', $currentUserId, \PDO::PARAM_INT);
                    $stmt->bindValue(':current_user_id_self', $currentUserId, \PDO::PARAM_INT);
                } else {
                    $stmt = $this->db->prepare("
                        SELECT p.id, p.user_id, p.text_content as text, p.image_path as image, p.audio_path as audio, p.created_at, p.scheduled_at, p.published_at, u.username,
                               (pl.user_id IS NOT NULL) as is_liked,
                               (pd.user_id IS NOT NULL) as is_disliked,
                               (f.user_id IS NOT NULL) as is_favorited
                        FROM posts p
                        JOIN users u ON p.user_id = u.id
                        LEFT JOIN post_likes pl ON p.id = pl.post_id AND pl.user_id = :current_user_id_like
                        LEFT JOIN post_dislikes pd ON p.id = pd.post_id AND pd.user_id = :current_user_id_dislike
                        LEFT JOIN favorites f ON p.id = f.post_id AND f.user_id = :current_user_id_fav
                        WHERE p.visibility = 'public' AND (p.published_at IS NULL OR p.published_at <= NOW())
                        ORDER BY p.created_at DESC
                        LIMIT :limit
                    ");
                    $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
                    $stmt->bindValue(':current_user_id_like', $currentUserId, \PDO::PARAM_INT);
                    $stmt->bindValue(':current_user_id_dislike', $currentUserId, \PDO::PARAM_INT);
                    $stmt->bindValue(':current_user_id_fav', $currentUserId, \PDO::PARAM_INT);
                }
            } else {
                $stmt = $this->db->prepare("
                    SELECT p.id, p.user_id, p.text_content as text, p.image_path as image, p.audio_path as audio, p.created_at, p.scheduled_at, p.published_at, u.username,
                           0 as is_liked,
                           0 as is_disliked,
                           0 as is_favorited
                    FROM posts p
                    JOIN users u ON p.user_id = u.id
                    WHERE p.visibility = 'public' AND (p.published_at IS NULL OR p.published_at <= NOW())
                    ORDER BY p.created_at DESC
                    LIMIT :limit
                ");
                $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
            }

            $stmt->execute();
            $rows = $stmt->fetchAll();
            $publications = [];
            foreach ($rows as $row) {
                $pub = new Publication(
                    (int)$row['id'],
                    (int)$row['user_id'],
                    $row['text'] ?? '',
                    $row['image'],
                    $row['audio'],
                    $row['username'],
                    $row['created_at'],
                    $row['scheduled_at'] ?? null,
                    $row['published_at'] ?? null
                );
                $pub->setIsLiked((bool)($row['is_liked'] ?? false));
                $pub->setIsDisliked((bool)($row['is_disliked'] ?? false));
                $pub->setIsFavorited((bool)($row['is_favorited'] ?? false));
                $publications[] = $pub;
            }
            return $publications;
        }

        // Fallback usando los posts de sesión (primeros 10)
        $all = $this->getAll();
        return array_slice($all, 0, $limit);
    }

    public function toggleLike(int $postId, int $userId): array {
        if (!$this->db) {
            return ['success' => false, 'message' => 'No hay conexión a la base de datos.'];
        }

        // Verificar si ya existe el like
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM post_likes WHERE user_id = ? AND post_id = ?");
        $stmt->execute([$userId, $postId]);
        $exists = $stmt->fetchColumn() > 0;

        if ($exists) {
            // Eliminar like
            $stmt = $this->db->prepare("DELETE FROM post_likes WHERE user_id = ? AND post_id = ?");
            $stmt->execute([$userId, $postId]);
            return ['success' => true, 'active' => false];
        } else {
            // Eliminar dislike si existe, ya que son excluyentes
            $stmt = $this->db->prepare("DELETE FROM post_dislikes WHERE user_id = ? AND post_id = ?");
            $stmt->execute([$userId, $postId]);

            // Insertar like
            $stmt = $this->db->prepare("INSERT INTO post_likes (user_id, post_id, created_at) VALUES (?, ?, NOW())");
            $stmt->execute([$userId, $postId]);
            return ['success' => true, 'active' => true];
        }
    }

    public function toggleDislike(int $postId, int $userId): array {
        if (!$this->db) {
            return ['success' => false, 'message' => 'No hay conexión a la base de datos.'];
        }

        // Verificar si ya existe el dislike
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM post_dislikes WHERE user_id = ? AND post_id = ?");
        $stmt->execute([$userId, $postId]);
        $exists = $stmt->fetchColumn() > 0;

        if ($exists) {
            // Eliminar dislike
            $stmt = $this->db->prepare("DELETE FROM post_dislikes WHERE user_id = ? AND post_id = ?");
            $stmt->execute([$userId, $postId]);
            return ['success' => true, 'active' => false];
        } else {
            // Eliminar like si existe, ya que son excluyentes
            $stmt = $this->db->prepare("DELETE FROM post_likes WHERE user_id = ? AND post_id = ?");
            $stmt->execute([$userId, $postId]);

            // Insertar dislike
            $stmt = $this->db->prepare("INSERT INTO post_dislikes (user_id, post_id, created_at) VALUES (?, ?, NOW())");
            $stmt->execute([$userId, $postId]);
            return ['success' => true, 'active' => true];
        }
    }

    public function toggleFavorite(int $postId, int $userId): array {
        if (!$this->db) {
            return ['success' => false, 'message' => 'No hay conexión a la base de datos.'];
        }

        // Verificar si ya existe en favoritos
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM favorites WHERE user_id = ? AND post_id = ?");
        $stmt->execute([$userId, $postId]);
        $exists = $stmt->fetchColumn() > 0;

        if ($exists) {
            // Eliminar favorito
            $stmt = $this->db->prepare("DELETE FROM favorites WHERE user_id = ? AND post_id = ?");
            $stmt->execute([$userId, $postId]);
            return ['success' => true, 'active' => false];
        } else {
            // Insertar favorito
            $stmt = $this->db->prepare("INSERT INTO favorites (user_id, post_id, created_at) VALUES (?, ?, NOW())");
            $stmt->execute([$userId, $postId]);
            return ['success' => true, 'active' => true];
        }
    }

    public function getPublicationsByInteraction(int $userId, string $type): array {
        if (!$this->db) {
            return [];
        }

        if ($type === 'like') {
            $sql = "
                SELECT p.id, p.user_id, p.text_content as text, p.image_path as image, p.audio_path as audio, p.created_at, p.scheduled_at, p.published_at, u.username,
                       1 as is_liked,
                       0 as is_disliked,
                       (f.user_id IS NOT NULL) as is_favorited
                FROM post_likes pl
                JOIN posts p ON pl.post_id = p.id
                JOIN users u ON p.user_id = u.id
                LEFT JOIN favorites f ON p.id = f.post_id AND f.user_id = :user_id_fav
                WHERE pl.user_id = :user_id_like AND (p.published_at IS NULL OR p.published_at <= NOW())
                ORDER BY pl.created_at DESC
            ";
        } elseif ($type === 'dislike') {
            $sql = "
                SELECT p.id, p.user_id, p.text_content as text, p.image_path as image, p.audio_path as audio, p.created_at, p.scheduled_at, p.published_at, u.username,
                       0 as is_liked,
                       1 as is_disliked,
                       (f.user_id IS NOT NULL) as is_favorited
                FROM post_dislikes pd
                JOIN posts p ON pd.post_id = p.id
                JOIN users u ON p.user_id = u.id
                LEFT JOIN favorites f ON p.id = f.post_id AND f.user_id = :user_id_fav
                WHERE pd.user_id = :user_id_dislike AND (p.published_at IS NULL OR p.published_at <= NOW())
                ORDER BY pd.created_at DESC
            ";
        } elseif ($type === 'favorite') {
            $sql = "
                SELECT p.id, p.user_id, p.text_content as text, p.image_path as image, p.audio_path as audio, p.created_at, p.scheduled_at, p.published_at, u.username,
                       (pl.user_id IS NOT NULL) as is_liked,
                       (pd.user_id IS NOT NULL) as is_disliked,
                       1 as is_favorited
                FROM favorites f
                JOIN posts p ON f.post_id = p.id
                JOIN users u ON p.user_id = u.id
                LEFT JOIN post_likes pl ON p.id = pl.post_id AND pl.user_id = :user_id_like
                LEFT JOIN post_dislikes pd ON p.id = pd.post_id AND pd.user_id = :user_id_dislike
                WHERE f.user_id = :user_id_fav AND (p.published_at IS NULL OR p.published_at <= NOW())
                ORDER BY f.created_at DESC
            ";
        } else {
            return [];
        }

        $stmt = $this->db->prepare($sql);
        if ($type === 'like') {
            $stmt->bindValue(':user_id_fav', $userId, \PDO::PARAM_INT);
            $stmt->bindValue(':user_id_like', $userId, \PDO::PARAM_INT);
        } elseif ($type === 'dislike') {
            $stmt->bindValue(':user_id_fav', $userId, \PDO::PARAM_INT);
            $stmt->bindValue(':user_id_dislike', $userId, \PDO::PARAM_INT);
        } elseif ($type === 'favorite') {
            $stmt->bindValue(':user_id_like', $userId, \PDO::PARAM_INT);
            $stmt->bindValue(':user_id_dislike', $userId, \PDO::PARAM_INT);
            $stmt->bindValue(':user_id_fav', $userId, \PDO::PARAM_INT);
        }
        $stmt->execute();
        $rows = $stmt->fetchAll();

        $publications = [];
        foreach ($rows as $row) {
            $pub = new Publication(
                (int)$row['id'],
                (int)$row['user_id'],
                $row['text'] ?? '',
                $row['image'],
                $row['audio'],
                $row['username'],
                $row['created_at'],
                $row['scheduled_at'] ?? null,
                $row['published_at'] ?? null
            );
            $pub->setIsLiked((bool)($row['is_liked'] ?? false));
            $pub->setIsDisliked((bool)($row['is_disliked'] ?? false));
            $pub->setIsFavorited((bool)($row['is_favorited'] ?? false));
            $publications[] = $pub;
        }

        return $publications;
    }

    public function getPublicationsByUser(int $userId): array {
        if (!$this->db) {
            return [];
        }

        $stmt = $this->db->prepare("
            SELECT p.id, p.user_id, p.text_content as text, p.image_path as image, p.audio_path as audio, p.created_at, p.scheduled_at, p.published_at, u.username,
                   (pl.user_id IS NOT NULL) as is_liked,
                   (pd.user_id IS NOT NULL) as is_disliked,
                   (f.user_id IS NOT NULL) as is_favorited,
                   (SELECT COUNT(*) FROM post_likes WHERE post_id = p.id) as likes_count,
                   (SELECT COUNT(*) FROM post_dislikes WHERE post_id = p.id) as dislikes_count
            FROM posts p
            JOIN users u ON p.user_id = u.id
            LEFT JOIN post_likes pl ON p.id = pl.post_id AND pl.user_id = :current_user_id_like
            LEFT JOIN post_dislikes pd ON p.id = pd.post_id AND pd.user_id = :current_user_id_dislike
            LEFT JOIN favorites f ON p.id = f.post_id AND f.user_id = :current_user_id_fav
            WHERE p.user_id = :user_id
            ORDER BY p.created_at DESC
        ");
        $stmt->bindValue(':current_user_id_like', $userId, \PDO::PARAM_INT);
        $stmt->bindValue(':current_user_id_dislike', $userId, \PDO::PARAM_INT);
        $stmt->bindValue(':current_user_id_fav', $userId, \PDO::PARAM_INT);
        $stmt->bindValue(':user_id', $userId, \PDO::PARAM_INT);
        $stmt->execute();
        $rows = $stmt->fetchAll();

        $publications = [];
        foreach ($rows as $row) {
            $pub = new Publication(
                (int)$row['id'],
                (int)$row['user_id'],
                $row['text'] ?? '',
                $row['image'],
                $row['audio'],
                $row['username'],
                $row['created_at'],
                $row['scheduled_at'] ?? null,
                $row['published_at'] ?? null
            );
            $pub->setIsLiked((bool)($row['is_liked'] ?? false));
            $pub->setIsDisliked((bool)($row['is_disliked'] ?? false));
            $pub->setIsFavorited((bool)($row['is_favorited'] ?? false));
            $pub->setLikesCount((int)($row['likes_count'] ?? 0));
            $pub->setDislikesCount((int)($row['dislikes_count'] ?? 0));
            $publications[] = $pub;
        }
        return $publications;
    }

    public function search(string $query, ?int $currentUserId = null): array {
        if (!$this->db) {
            return [];
        }

        $query = trim($query);
        if (empty($query)) {
            return [];
        }

        if ($currentUserId !== null) {
            $stmt = $this->db->prepare("
                SELECT p.id, p.user_id, p.text_content as text, p.image_path as image, p.audio_path as audio, p.created_at, p.scheduled_at, p.published_at, u.username,
                       (pl.user_id IS NOT NULL) as is_liked,
                       (pd.user_id IS NOT NULL) as is_disliked,
                       (f.user_id IS NOT NULL) as is_favorited
                FROM posts p
                JOIN users u ON p.user_id = u.id
                LEFT JOIN post_likes pl ON p.id = pl.post_id AND pl.user_id = :current_user_id_like
                LEFT JOIN post_dislikes pd ON p.id = pd.post_id AND pd.user_id = :current_user_id_dislike
                LEFT JOIN favorites f ON p.id = f.post_id AND f.user_id = :current_user_id_fav
                WHERE p.visibility = 'public' AND (p.published_at IS NULL OR p.published_at <= NOW())
                  AND MATCH(p.text_content) AGAINST(:search_query_where IN NATURAL LANGUAGE MODE)
                ORDER BY MATCH(p.text_content) AGAINST(:search_query_order IN NATURAL LANGUAGE MODE) DESC, p.created_at DESC
            ");
            $stmt->bindValue(':current_user_id_like', $currentUserId, \PDO::PARAM_INT);
            $stmt->bindValue(':current_user_id_dislike', $currentUserId, \PDO::PARAM_INT);
            $stmt->bindValue(':current_user_id_fav', $currentUserId, \PDO::PARAM_INT);
            $stmt->bindValue(':search_query_where', $query, \PDO::PARAM_STR);
            $stmt->bindValue(':search_query_order', $query, \PDO::PARAM_STR);
        } else {
            $stmt = $this->db->prepare("
                SELECT p.id, p.user_id, p.text_content as text, p.image_path as image, p.audio_path as audio, p.created_at, p.scheduled_at, p.published_at, u.username,
                       0 as is_liked,
                       0 as is_disliked,
                       0 as is_favorited
                FROM posts p
                JOIN users u ON p.user_id = u.id
                WHERE p.visibility = 'public' AND (p.published_at IS NULL OR p.published_at <= NOW())
                  AND MATCH(p.text_content) AGAINST(:search_query_where IN NATURAL LANGUAGE MODE)
                ORDER BY MATCH(p.text_content) AGAINST(:search_query_order IN NATURAL LANGUAGE MODE) DESC, p.created_at DESC
            ");
            $stmt->bindValue(':search_query_where', $query, \PDO::PARAM_STR);
            $stmt->bindValue(':search_query_order', $query, \PDO::PARAM_STR);
        }

        $stmt->execute();
        $rows = $stmt->fetchAll();

        $publications = [];
        foreach ($rows as $row) {
            $pub = new Publication(
                (int)$row['id'],
                (int)$row['user_id'],
                $row['text'] ?? '',
                $row['image'],
                $row['audio'],
                $row['username'],
                $row['created_at'],
                $row['scheduled_at'] ?? null,
                $row['published_at'] ?? null
            );
            $pub->setIsLiked((bool)($row['is_liked'] ?? false));
            $pub->setIsDisliked((bool)($row['is_disliked'] ?? false));
            $pub->setIsFavorited((bool)($row['is_favorited'] ?? false));
            $publications[] = $pub;
        }

        return $publications;
    }

    public function updateText(int $postId, int $userId, string $text): bool {
        if (!$this->db) {
            return false;
        }
        $stmt = $this->db->prepare("UPDATE posts SET text_content = ? WHERE id = ? AND user_id = ?");
        return $stmt->execute([$text, $postId, $userId]);
    }

    public function updatePost(int $postId, int $userId, string $text, ?string $imagePath, ?string $audioPath, bool $deleteImage, bool $deleteAudio): bool {
        if (!$this->db) {
            return false;
        }

        // Obtener la publicación actual para gestionar archivos
        $stmt = $this->db->prepare("SELECT image_path, audio_path FROM posts WHERE id = ? AND user_id = ?");
        $stmt->execute([$postId, $userId]);
        $current = $stmt->fetch();
        if (!$current) {
            return false;
        }

        $finalImage = $current['image_path'];
        $finalAudio = $current['audio_path'];

        // Gestionar borrado o reemplazo de imagen
        if ($deleteImage || $imagePath !== null) {
            if ($finalImage && file_exists(ROOT_PATH . '/' . $finalImage)) {
                @unlink(ROOT_PATH . '/' . $finalImage);
            }
            $finalImage = $imagePath;
        }

        // Gestionar borrado o reemplazo de audio
        if ($deleteAudio || $audioPath !== null) {
            if ($finalAudio && file_exists(ROOT_PATH . '/' . $finalAudio)) {
                @unlink(ROOT_PATH . '/' . $finalAudio);
            }
            $finalAudio = $audioPath;
        }

        $stmt = $this->db->prepare("
            UPDATE posts 
            SET text_content = ?, image_path = ?, audio_path = ?, updated_at = NOW() 
            WHERE id = ? AND user_id = ?
        ");
        return $stmt->execute([$text, $finalImage, $finalAudio, $postId, $userId]);
    }

    public function delete(int $postId, int $userId): bool {
        if (!$this->db) {
            return false;
        }
        $stmt = $this->db->prepare("DELETE FROM posts WHERE id = ? AND user_id = ?");
        return $stmt->execute([$postId, $userId]);
    }

    public function getById(int $postId, ?int $currentUserId = null): ?Publication {
        if ($this->db) {
            if ($currentUserId !== null) {
                $stmt = $this->db->prepare("
                    SELECT p.id, p.user_id, p.text_content as text, p.image_path as image, p.audio_path as audio, p.created_at, p.scheduled_at, p.published_at, u.username,
                           (pl.user_id IS NOT NULL) as is_liked,
                           (pd.user_id IS NOT NULL) as is_disliked,
                           (f.user_id IS NOT NULL) as is_favorited
                    FROM posts p
                    JOIN users u ON p.user_id = u.id
                    LEFT JOIN post_likes pl ON p.id = pl.post_id AND pl.user_id = :current_user_id_like
                    LEFT JOIN post_dislikes pd ON p.id = pd.post_id AND pd.user_id = :current_user_id_dislike
                    LEFT JOIN favorites f ON p.id = f.post_id AND f.user_id = :current_user_id_fav
                    WHERE p.id = :post_id
                ");
                $stmt->bindValue(':current_user_id_like', $currentUserId, \PDO::PARAM_INT);
                $stmt->bindValue(':current_user_id_dislike', $currentUserId, \PDO::PARAM_INT);
                $stmt->bindValue(':current_user_id_fav', $currentUserId, \PDO::PARAM_INT);
            } else {
                $stmt = $this->db->prepare("
                    SELECT p.id, p.user_id, p.text_content as text, p.image_path as image, p.audio_path as audio, p.created_at, p.scheduled_at, p.published_at, u.username,
                           0 as is_liked,
                           0 as is_disliked,
                           0 as is_favorited
                    FROM posts p
                    JOIN users u ON p.user_id = u.id
                    WHERE p.id = :post_id
                ");
            }
            $stmt->bindValue(':post_id', $postId, \PDO::PARAM_INT);
            $stmt->execute();
            $row = $stmt->fetch();
            if ($row) {
                $pub = new Publication(
                    (int)$row['id'],
                    (int)$row['user_id'],
                    $row['text'] ?? '',
                    $row['image'],
                    $row['audio'],
                    $row['username'],
                    $row['created_at'],
                    $row['scheduled_at'] ?? null,
                    $row['published_at'] ?? null
                );
                $pub->setIsLiked((bool)($row['is_liked'] ?? false));
                $pub->setIsDisliked((bool)($row['is_disliked'] ?? false));
                $pub->setIsFavorited((bool)($row['is_favorited'] ?? false));
                return $pub;
            }
        }
        return null;
    }

    public function getTotalLikesForUserPosts(int $userId): int {
        if ($this->db) {
            $stmt = $this->db->prepare("
                SELECT COUNT(*) 
                FROM post_likes pl 
                JOIN posts p ON pl.post_id = p.id 
                WHERE p.user_id = ?
            ");
            $stmt->execute([$userId]);
            return (int)$stmt->fetchColumn();
        }
        return 0;
    }
}

