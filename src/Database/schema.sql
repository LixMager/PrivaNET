CREATE DATABASE IF NOT EXISTS privanet
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE privanet;

-- =========================================================
-- USUARIOS
-- =========================================================

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,

    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(255) NOT NULL UNIQUE,

    password_hash VARCHAR(255) NOT NULL,

    birth_date DATE NOT NULL,
    country VARCHAR(100),

    profile_picture_path VARCHAR(255) NULL,
    biography VARCHAR(255) NULL,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    last_login_at TIMESTAMP NULL
);

-- =========================================================
-- POSTS
-- =========================================================

CREATE TABLE IF NOT EXISTS posts (
    id INT AUTO_INCREMENT PRIMARY KEY,

    user_id INT NOT NULL,

    text_content VARCHAR(255) NULL,

    image_path VARCHAR(255) NULL,
    audio_path VARCHAR(255) NULL,

    scheduled_at TIMESTAMP NULL,
    published_at TIMESTAMP NULL,

    visibility ENUM('public', 'private')
        DEFAULT 'public',

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_posts_user
        FOREIGN KEY (user_id)
        REFERENCES users(id)
        ON DELETE CASCADE,

    INDEX idx_posts_user_id (user_id),
    INDEX idx_posts_published_at (published_at),
    INDEX idx_posts_scheduled_at (scheduled_at)
);

-- =========================================================
-- LIKES DE POSTEOS
-- =========================================================
-- Permite trazabilidad para saber quien dio like a un post

CREATE TABLE IF NOT EXISTS post_likes (
    user_id INT NOT NULL,
    post_id INT NOT NULL,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY(user_id, post_id),

    CONSTRAINT fk_post_likes_user
        FOREIGN KEY (user_id)
        REFERENCES users(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_post_likes_post
        FOREIGN KEY (post_id)
        REFERENCES posts(id)
        ON DELETE CASCADE,

    INDEX idx_post_likes_post_id (post_id)
);

-- =========================================================
-- DISLIKES DE POSTEOS
-- =========================================================
-- Permite trazabilidad para saber quien le dio dislike a un posteo

CREATE TABLE IF NOT EXISTS post_dislikes (
    user_id INT NOT NULL,
    post_id INT NOT NULL,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY(user_id, post_id),

    CONSTRAINT fk_post_dislikes_user
        FOREIGN KEY (user_id)
        REFERENCES users(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_post_dislikes_post
        FOREIGN KEY (post_id)
        REFERENCES posts(id)
        ON DELETE CASCADE,

    INDEX idx_post_dislikes_post_id (post_id)
);

-- =========================================================
-- FAVORITES
-- =========================================================

CREATE TABLE IF NOT EXISTS favorites (
    user_id INT NOT NULL,
    post_id INT NOT NULL,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY(user_id, post_id),

    CONSTRAINT fk_favorites_user
        FOREIGN KEY (user_id)
        REFERENCES users(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_favorites_post
        FOREIGN KEY (post_id)
        REFERENCES posts(id)
        ON DELETE CASCADE,

    INDEX idx_favorites_post_id (post_id)
);

-- =========================================================
-- COMMENTS
-- =========================================================

CREATE TABLE IF NOT EXISTS comments (
    id INT AUTO_INCREMENT PRIMARY KEY,

    user_id INT NOT NULL,
    post_id INT NOT NULL,

    content VARCHAR(255) NOT NULL,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_comments_user
        FOREIGN KEY (user_id)
        REFERENCES users(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_comments_post
        FOREIGN KEY (post_id)
        REFERENCES posts(id)
        ON DELETE CASCADE,

    INDEX idx_comments_post_id (post_id),
    INDEX idx_comments_user_id (user_id)
);

-- =========================================================
-- REMEMBER TOKENS
-- =========================================================

CREATE TABLE IF NOT EXISTS remember_tokens (
    id INT AUTO_INCREMENT PRIMARY KEY,

    user_id INT NOT NULL,

    token_hash VARCHAR(255) NOT NULL,

    user_agent VARCHAR(255) NULL,
    ip_address VARCHAR(45) NULL,

    expires_at TIMESTAMP NOT NULL,
    last_used_at TIMESTAMP NULL,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_remember_tokens_user
        FOREIGN KEY (user_id)
        REFERENCES users(id)
        ON DELETE CASCADE,

    INDEX idx_remember_tokens_user_id (user_id),
    INDEX idx_remember_tokens_expires_at (expires_at)
);

-- =========================================================
-- FULLTEXT SEARCH
-- =========================================================

ALTER TABLE posts
ADD FULLTEXT INDEX ft_posts_text_content (text_content);

-- =========================================================
-- DEMO USER (OPTIONAL)
-- password: demo123
-- generar correctamente luego con password_hash()
-- =========================================================

-- INSERT INTO users (
--     username,
--     email,
--     password_hash,
--     birth_date,
--     country
-- )
-- VALUES (
--     'demo',
--     'demo@example.com',
--     '$2y$10$REEMPLAZAR_HASH_REAL',
--     '2000-01-01',
--     'Argentina'
-- );