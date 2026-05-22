-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 22-05-2026 a las 02:10:22
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `privanet`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `favorites`
--

CREATE TABLE `favorites` (
  `user_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `favorites`
--

INSERT INTO `favorites` (`user_id`, `post_id`, `created_at`) VALUES
(5, 2, '2026-05-21 22:30:13'),
(5, 3, '2026-05-21 20:57:48'),
(5, 4, '2026-05-21 22:30:12');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `text_content` varchar(255) DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `audio_path` varchar(255) DEFAULT NULL,
  `scheduled_at` timestamp NULL DEFAULT NULL,
  `published_at` timestamp NULL DEFAULT NULL,
  `visibility` enum('public','private') DEFAULT 'public',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `posts`
--

INSERT INTO `posts` (`id`, `user_id`, `text_content`, `image_path`, `audio_path`, `scheduled_at`, `published_at`, `visibility`, `created_at`, `updated_at`) VALUES
(1, 3, '¡Increíble viaje por Trevelin! 🌷🏔️ Los campos de tulipanes en la Patagonia son verdaderamente un paraíso terrenal. Totalmente recomendado.', 'public/posts/1/trevelin.jpg', NULL, NULL, '2026-05-21 11:22:43', 'public', '2026-05-21 11:22:43', '2026-05-21 11:22:43'),
(2, 4, 'Otro ejemplo de publicación visible para cualquier visitante no registrado.', NULL, NULL, NULL, '2026-05-21 11:22:43', 'public', '2026-05-21 11:22:43', '2026-05-21 11:22:43'),
(3, 2, 'Hoy tuve una visita inesperada', 'public/assets/uploads/users/2/posts/1779362751/img_8987.webp', 'public/assets/uploads/users/2/posts/1779362751/aud_2682.mp3', NULL, '2026-05-21 11:25:51', 'public', '2026-05-21 11:25:51', '2026-05-21 11:27:56'),
(4, 2, 'Así esta quedando mi setup... ¿Qué opinan?', 'public/assets/uploads/users/2/posts/1779393794/img_5802.png', NULL, NULL, '2026-05-21 20:03:14', 'public', '2026-05-21 20:03:14', '2026-05-21 20:03:14'),
(5, 2, 'Dicen que en la catedral hace mucho frío en invierno... Pero eso no me quita las ganas de ir a esquiar!!', 'public/assets/uploads/users/2/posts/1779394165/img_5779.jpg', 'public/assets/uploads/users/2/posts/1779394165/aud_2132.mp3', NULL, '2026-05-21 20:09:25', 'public', '2026-05-21 20:09:25', '2026-05-21 20:09:25'),
(6, 5, 'Me llego un aviso de visita de un paquete. Me comunique al siguiente día y ya lo devolvieron al emisor. Pésimo servicio!', 'public/assets/uploads/users/5/posts/1779399832/img_1855.png', NULL, NULL, '2026-05-21 21:43:52', 'public', '2026-05-21 21:43:52', '2026-05-21 21:43:52');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `post_dislikes`
--

CREATE TABLE `post_dislikes` (
  `user_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `post_dislikes`
--

INSERT INTO `post_dislikes` (`user_id`, `post_id`, `created_at`) VALUES
(5, 4, '2026-05-21 22:30:11'),
(5, 5, '2026-05-21 21:35:46');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `post_likes`
--

CREATE TABLE `post_likes` (
  `user_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `post_likes`
--

INSERT INTO `post_likes` (`user_id`, `post_id`, `created_at`) VALUES
(5, 3, '2026-05-21 22:30:26');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `remember_tokens`
--

CREATE TABLE `remember_tokens` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `token_hash` varchar(255) NOT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `expires_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `remember_tokens`
--

INSERT INTO `remember_tokens` (`id`, `user_id`, `token_hash`, `user_agent`, `ip_address`, `expires_at`, `last_used_at`, `created_at`) VALUES
(4, 5, 'ff6e51523326a91f2e560de4f97185073bd224039484393fd2ee3d860206ef30', NULL, NULL, '2026-06-21 03:29:47', NULL, '2026-05-21 22:29:47');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `birth_date` date NOT NULL,
  `country` varchar(100) DEFAULT NULL,
  `profile_picture_path` varchar(255) DEFAULT NULL,
  `biography` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `last_login_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password_hash`, `birth_date`, `country`, `profile_picture_path`, `biography`, `created_at`, `updated_at`, `last_login_at`) VALUES
(1, 'Pepe', 'pepe@gmail.com', '$2y$10$1ohoYGfdAGA6DCXDPXAEduSh3JZ34UawELqXnqR42ZpTKxpCD/CJC', '2005-01-13', 'Argentina', NULL, NULL, '2026-05-18 18:58:03', '2026-05-18 18:58:03', NULL),
(2, 'nestor', 'nestor@gmail.com', '$2y$10$2gmiXJuJeCptUoNlyktWj.k7jo6cZa3xOoRSykJ1KSqrGSW2MTtEy', '1975-07-18', 'Argentina', NULL, NULL, '2026-05-18 19:07:59', '2026-05-21 20:24:09', '2026-05-21 20:24:09'),
(3, 'usuario_demo', 'demo@privanet.com', '$2y$10$VMdWch.jZ9aLcXGrRfORHuBvdpWCydFJhKIqgg9IB0aggomeg43yG', '1995-05-15', 'Argentina', NULL, NULL, '2026-05-21 11:22:43', '2026-05-21 11:22:43', NULL),
(4, 'otro_usuario', 'otro@privanet.com', '$2y$10$DEeH7kztldsrjmp.0x6Ss.NXF.4j.hNgjfTmj.0Qk5cfdaX5BipnK', '1998-08-20', 'Argentina', NULL, NULL, '2026-05-21 11:22:43', '2026-05-21 11:22:43', NULL),
(5, 'elias', 'eliasm@gmail.com', '$2y$10$Mkervu9C2s6kXvK/ZCwg0eV86G4wVNq0uVArsIsJcFeJaho2X0CY6', '2001-04-08', 'Arg', NULL, NULL, '2026-05-21 20:56:37', '2026-05-21 22:29:47', '2026-05-21 22:29:47');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `favorites`
--
ALTER TABLE `favorites`
  ADD PRIMARY KEY (`user_id`,`post_id`),
  ADD KEY `idx_favorites_post_id` (`post_id`);

--
-- Indices de la tabla `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_posts_user_id` (`user_id`),
  ADD KEY `idx_posts_published_at` (`published_at`),
  ADD KEY `idx_posts_scheduled_at` (`scheduled_at`);
ALTER TABLE `posts` ADD FULLTEXT KEY `ft_posts_text_content` (`text_content`);

--
-- Indices de la tabla `post_dislikes`
--
ALTER TABLE `post_dislikes`
  ADD PRIMARY KEY (`user_id`,`post_id`),
  ADD KEY `idx_post_dislikes_post_id` (`post_id`);

--
-- Indices de la tabla `post_likes`
--
ALTER TABLE `post_likes`
  ADD PRIMARY KEY (`user_id`,`post_id`),
  ADD KEY `idx_post_likes_post_id` (`post_id`);

--
-- Indices de la tabla `remember_tokens`
--
ALTER TABLE `remember_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_remember_tokens_user_id` (`user_id`),
  ADD KEY `idx_remember_tokens_expires_at` (`expires_at`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `remember_tokens`
--
ALTER TABLE `remember_tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `favorites`
--
ALTER TABLE `favorites`
  ADD CONSTRAINT `fk_favorites_post` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_favorites_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `fk_posts_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `post_dislikes`
--
ALTER TABLE `post_dislikes`
  ADD CONSTRAINT `fk_post_dislikes_post` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_post_dislikes_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `post_likes`
--
ALTER TABLE `post_likes`
  ADD CONSTRAINT `fk_post_likes_post` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_post_likes_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `remember_tokens`
--
ALTER TABLE `remember_tokens`
  ADD CONSTRAINT `fk_remember_tokens_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
