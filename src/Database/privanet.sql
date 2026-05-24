-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 25-05-2026 a las 00:45:49
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
(5, 33, '2026-05-24 04:13:45'),
(5, 34, '2026-05-24 04:13:46'),
(5, 37, '2026-05-24 04:13:36'),
(5, 41, '2026-05-24 04:13:43'),
(6, 2, '2026-05-22 03:20:13'),
(6, 6, '2026-05-22 19:05:22'),
(8, 5, '2026-05-23 18:46:35'),
(8, 6, '2026-05-23 18:46:34'),
(16, 4, '2026-05-24 01:49:08'),
(16, 5, '2026-05-24 01:49:08'),
(16, 13, '2026-05-24 01:49:08'),
(16, 26, '2026-05-24 01:49:08'),
(16, 30, '2026-05-24 01:49:08'),
(16, 32, '2026-05-24 01:49:08'),
(16, 35, '2026-05-24 01:49:08'),
(17, 3, '2026-05-24 01:49:08'),
(17, 17, '2026-05-24 01:49:08'),
(17, 31, '2026-05-24 01:49:08'),
(18, 3, '2026-05-24 01:49:08'),
(18, 14, '2026-05-24 01:49:08'),
(18, 19, '2026-05-24 01:49:08'),
(18, 29, '2026-05-24 01:49:08'),
(18, 32, '2026-05-24 01:49:08'),
(19, 2, '2026-05-24 01:49:08'),
(19, 4, '2026-05-24 01:49:09'),
(19, 28, '2026-05-24 01:49:08'),
(19, 30, '2026-05-24 01:49:08'),
(19, 32, '2026-05-24 01:49:08'),
(19, 37, '2026-05-24 01:49:09'),
(19, 39, '2026-05-24 01:49:08'),
(20, 28, '2026-05-24 01:49:09'),
(21, 3, '2026-05-24 01:49:09'),
(21, 41, '2026-05-24 01:49:09'),
(22, 2, '2026-05-24 01:49:09'),
(22, 5, '2026-05-24 01:49:09'),
(22, 25, '2026-05-24 01:49:09'),
(22, 30, '2026-05-24 01:49:09'),
(22, 32, '2026-05-24 01:49:09'),
(22, 38, '2026-05-24 01:49:09'),
(23, 5, '2026-05-24 01:49:09'),
(23, 26, '2026-05-24 01:49:09'),
(23, 34, '2026-05-24 01:49:09'),
(23, 37, '2026-05-24 01:49:09'),
(23, 39, '2026-05-24 01:49:09'),
(23, 40, '2026-05-24 01:49:09'),
(24, 2, '2026-05-24 01:49:09'),
(24, 3, '2026-05-24 01:49:09'),
(24, 4, '2026-05-24 01:49:09'),
(24, 6, '2026-05-24 01:49:09'),
(24, 19, '2026-05-24 01:49:09'),
(24, 26, '2026-05-24 01:49:09'),
(24, 29, '2026-05-24 01:49:09'),
(24, 30, '2026-05-24 01:49:09'),
(24, 32, '2026-05-24 01:49:09'),
(24, 33, '2026-05-24 01:49:09'),
(24, 34, '2026-05-24 01:49:09'),
(24, 37, '2026-05-24 01:49:09'),
(24, 38, '2026-05-24 01:49:09'),
(24, 39, '2026-05-24 01:49:09'),
(24, 40, '2026-05-24 01:49:09'),
(25, 2, '2026-05-24 01:49:09'),
(25, 4, '2026-05-24 01:49:09'),
(25, 5, '2026-05-24 01:49:09'),
(25, 7, '2026-05-24 01:49:09'),
(25, 13, '2026-05-24 01:49:09'),
(25, 17, '2026-05-24 01:49:09'),
(25, 32, '2026-05-24 01:49:09'),
(25, 34, '2026-05-24 01:49:09'),
(25, 36, '2026-05-24 01:49:09'),
(25, 37, '2026-05-24 01:49:09'),
(25, 38, '2026-05-24 01:49:09'),
(25, 39, '2026-05-24 01:49:09'),
(25, 41, '2026-05-24 01:49:09'),
(26, 2, '2026-05-24 01:49:09'),
(26, 4, '2026-05-24 01:49:09'),
(26, 13, '2026-05-24 01:49:09'),
(26, 18, '2026-05-24 01:49:09'),
(26, 19, '2026-05-24 01:49:09'),
(26, 28, '2026-05-24 01:49:09'),
(26, 29, '2026-05-24 01:49:09'),
(26, 36, '2026-05-24 01:49:09'),
(27, 2, '2026-05-24 01:49:09'),
(27, 3, '2026-05-24 01:49:09'),
(27, 6, '2026-05-24 01:49:09'),
(27, 7, '2026-05-24 01:49:09'),
(27, 28, '2026-05-24 01:49:09'),
(27, 32, '2026-05-24 01:49:09'),
(27, 33, '2026-05-24 01:49:09'),
(27, 34, '2026-05-24 01:49:09'),
(27, 38, '2026-05-24 01:49:09'),
(28, 14, '2026-05-24 01:49:09'),
(28, 29, '2026-05-24 01:49:09'),
(28, 35, '2026-05-24 01:49:09'),
(29, 6, '2026-05-24 01:49:09'),
(29, 7, '2026-05-24 01:49:09'),
(29, 17, '2026-05-24 01:49:09'),
(29, 26, '2026-05-24 01:49:09'),
(29, 28, '2026-05-24 01:49:09'),
(29, 31, '2026-05-24 01:49:09'),
(30, 13, '2026-05-24 01:49:09'),
(30, 28, '2026-05-24 01:49:09'),
(30, 29, '2026-05-24 01:49:09'),
(30, 33, '2026-05-24 01:49:09'),
(30, 34, '2026-05-24 01:49:09'),
(30, 35, '2026-05-24 01:49:09'),
(30, 37, '2026-05-24 01:49:09'),
(30, 39, '2026-05-24 01:49:09');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `text_content` text DEFAULT NULL,
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
(2, 4, 'Otro <strong>ejemplo</strong> de publicación <i>visible</i> para cualquier visitante no registrado.', NULL, NULL, NULL, '2026-05-21 11:22:43', 'public', '2026-05-21 11:22:43', '2026-05-22 19:22:37'),
(3, 2, '<p>Hoy tuve una <strong>visita inesperada </strong><span style=\"color: #ff5555\">sorprendente</span>.</p>', 'public/assets/uploads/users/2/posts/3/img_6414.jpg', 'public/assets/uploads/users/2/posts/1779362751/aud_2682.mp3', NULL, '2026-05-21 11:25:51', 'public', '2026-05-21 11:25:51', '2026-05-23 19:23:05'),
(4, 2, 'Así está quedando mi <strong>setup</strong>... ¿Qué <a href=\"https://ejemplo.com\">opinan</a>?', 'public/assets/uploads/users/2/posts/1779393794/img_5802.png', NULL, NULL, '2026-05-21 20:03:14', 'public', '2026-05-21 20:03:14', '2026-05-22 19:22:37'),
(5, 2, 'Dicen que en la <i>catedral</i> hace <b>mucho frío</b> en invierno... Pero eso no me quita las ganas de ir a esquiar!!', 'public/assets/uploads/users/2/posts/1779394165/img_5779.jpg', 'public/assets/uploads/users/2/posts/1779394165/aud_2132.mp3', NULL, '2026-05-21 20:09:25', 'public', '2026-05-21 20:09:25', '2026-05-22 19:22:37'),
(6, 5, '<p>Me llegó un <em>aviso de visita</em> de un paquete. Me comuniqué al siguiente día y ya lo devolvieron al emisor. <strong>Pésimo servicio!!!</strong></p>', 'public/assets/uploads/users/5/posts/1779399832/img_1855.png', NULL, NULL, '2026-05-21 21:43:52', 'public', '2026-05-21 21:43:52', '2026-05-24 00:30:38'),
(7, 6, '“Tal vez la noche sea la <i>vida</i> y el sol la <b>muerte</b>.<br>Tal vez la noche es nada y las conjeturas sobre ella nada y los seres que la viven nada.”', NULL, 'public/assets/uploads/users/6/posts/1779420224/aud_8890.mp3', NULL, '2026-05-22 03:23:44', 'public', '2026-05-22 03:23:44', '2026-05-23 01:48:29'),
(13, 5, '<p><a href=\"https://ar.pinterest.com/\" target=\"_blank\" rel=\"noopener noreferrer\">Esta</a> pagina es<span style=\"color: rgb(230, 0, 0);\"> genial!</span> Se las comparto para que también puedan buscar ideas para sus creaciones!!</p>', NULL, NULL, NULL, '2026-05-22 16:53:18', 'public', '2026-05-22 16:53:18', '2026-05-22 16:53:18'),
(14, 5, '<p><span style=\"color: rgb(255, 153, 0);\">HOla!!!</span></p>', NULL, NULL, NULL, '2026-05-22 17:03:05', 'public', '2026-05-22 17:03:05', '2026-05-22 17:03:05'),
(17, 5, '<p>aaaaa</p>', NULL, NULL, '2026-05-22 22:10:00', '2026-05-22 22:10:00', 'public', '2026-05-22 17:07:01', '2026-05-22 17:07:01'),
(18, 5, '<p>publi <span style=\"color: rgb(153, 51, 255);\">programada!!!!</span></p>', NULL, NULL, '2026-05-22 22:12:00', '2026-05-22 22:12:00', 'public', '2026-05-22 17:10:15', '2026-05-22 17:10:15'),
(19, 7, '<p>Hola! Soy nuevo en esta plataforma. Espero no sea muy <strong><em>difícil </em></strong>utilizarla.</p>', NULL, NULL, NULL, '2026-05-23 02:08:35', 'public', '2026-05-23 02:08:35', '2026-05-23 02:08:35'),
(25, 5, '<p>Lorem ip<em>sum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget do</em>lor. Aenean <strong>massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus</strong> mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis,</p>', 'public/assets/uploads/users/5/posts/25/img_7357.jpg', NULL, NULL, '2026-05-23 18:51:21', 'public', '2026-05-23 18:51:21', '2026-05-24 01:25:09'),
(26, 8, '', NULL, 'public/assets/uploads/users/8/posts/1779580811/aud_5179.mp3', NULL, '2026-05-24 00:00:11', 'public', '2026-05-24 00:00:11', '2026-05-24 00:00:11'),
(28, 8, '<p>Si!! Ya pude iniciar sesion!!</p>', NULL, NULL, NULL, '2026-05-24 00:25:13', 'public', '2026-05-24 00:25:13', '2026-05-24 00:25:13'),
(29, 8, '<p>Hoy me di cuenta que mi gato <strong>no da para más</strong>, ¿<span style=\"color: rgb(0, 102, 204);\">Recomendaciones</span> para que baje de peso?</p>', 'public/assets/uploads/users/8/posts/1779582534/img_8235.jpg', NULL, NULL, '2026-05-24 00:28:54', 'public', '2026-05-24 00:28:54', '2026-05-24 00:29:03'),
(30, 8, '<p>Esta página es horrible, los colores aburridos, todo limitado. <strong style=\"color: rgb(230, 0, 0);\"><em>¡Me voy!</em></strong></p>', NULL, NULL, NULL, '2026-05-24 00:33:54', 'public', '2026-05-24 00:33:54', '2026-05-24 00:45:44'),
(31, 5, '<p>No se pierdan la<strong style=\"color: rgb(230, 0, 0);\"><em> </em></strong>mejor fiesta<strong style=\"color: rgb(230, 0, 0);\"> </strong><strong style=\"color: rgb(0, 102, 204);\">ELEC</strong><strong style=\"color: rgb(107, 36, 178);\">TRONI</strong><strong style=\"color: rgb(0, 102, 204);\">CA</strong>. Encarguen sus anticipadas en <a href=\"https://es.ra.co/events/2443117\" target=\"_blank\" rel=\"noopener noreferrer\" style=\"color: rgb(230, 0, 0);\">este link</a>.</p>', 'public/assets/uploads/users/5/posts/31/img_7686.jpg', 'public/assets/uploads/users/5/posts/31/aud_2968.mp3', NULL, '2026-05-24 01:03:23', 'public', '2026-05-24 01:03:23', '2026-05-24 01:18:57'),
(32, 10, '<p>¡Qué hermoso lugar! <strong><span style=\"color: rgb(41, 128, 185);\">El Chaltén</span></strong> es increíble. Recomendado al 100%. <a href=\"https://es.wikipedia.org/wiki/El_Chalt%C3%A9n\" target=\"_blank\" rel=\"noopener noreferrer\">Más info aquí</a>.</p>', 'public/assets/uploads/users/10/posts/1779586849/img_1779586849_144.jpg', NULL, NULL, '2026-05-24 01:40:49', 'public', '2026-05-24 01:40:49', '2026-05-24 01:40:49'),
(33, 11, '<p>Preparando el nuevo <em style=\"color: rgb(155, 89, 182);\">set de RAVE</em> para esta noche. ¡Suban el volumen! 🎧🔥</p>', NULL, 'public/assets/uploads/users/11/posts/33/aud_7066.mp3', NULL, '2026-05-24 01:40:49', 'public', '2026-05-24 01:40:49', '2026-05-24 01:56:01'),
(34, 12, '<p>Una mariposa perfecta captada esta mañana. <span style=\"color: rgb(241, 196, 15);\">¡La naturaleza es sorprendente!</span> 🦋</p>', 'public/assets/uploads/users/12/posts/1779586851/img_1779586849_243.jpg', 'public/assets/uploads/users/12/posts/1779586851/aud_1779586849_237.mp3', NULL, '2026-05-24 01:40:49', 'public', '2026-05-24 01:40:49', '2026-05-24 01:40:49'),
(35, 10, '<p><strong style=\"color: rgb(192, 57, 43);\">Nepal 2026</strong></p><p>Llegamos a Nepal. La energía que se siente en los templos no se puede describir con palabras. 🧘‍♀️</p>', 'public/assets/uploads/users/10/posts/1779586852/img_1779586849_702.jpg', NULL, NULL, '2026-05-24 01:40:49', 'public', '2026-05-24 01:40:49', '2026-05-24 01:54:41'),
(36, 12, '<p>Un pequeño detalle de una margarita floreciendo 🌼. <u>La primavera ya llegó</u>.</p>', 'public/assets/uploads/users/12/posts/1779586853/img_1779586849_705.jpg', NULL, NULL, '2026-05-24 01:40:49', 'public', '2026-05-24 01:40:49', '2026-05-24 01:40:49'),
(37, 13, '<p><strong style=\"color: rgb(255, 194, 102);\">ARTE DE MEDIO ORIENTE</strong></p><p>La mezquita es una verdadera obra de arte. <strong>El nivel de detalle</strong> en su construcción me deja sin palabras.</p><p><br></p><p><span style=\"color: rgb(127, 140, 141);\">#Arquitectura #Viajes</span></p>', 'public/assets/uploads/users/13/posts/1779587178/img_1779587078_594.jpg', NULL, NULL, '2026-05-24 01:44:38', 'public', '2026-05-24 01:44:38', '2026-05-24 01:53:46'),
(38, 14, '<p>Arrancando el día con buena música inspiradora. 🚀 <span style=\"color: rgb(41, 128, 185);\">Programando el futuro...</span></p>', 'public/assets/uploads/users/14/posts/1779587179/img_1779587078_700.jpg', 'public/assets/uploads/users/14/posts/1779587179/aud_1779587078_593.mp3', NULL, '2026-05-24 01:44:38', 'public', '2026-05-24 01:44:38', '2026-05-24 01:44:38'),
(39, 13, '<p>Paisaje urbano al atardecer. 🏙️ <span style=\"color: rgb(230, 126, 34);\">Los colores que regala la ciudad a esta hora son mágicos.</span></p>', 'public/assets/uploads/users/13/posts/1779587180/img_1779587078_771.jpg', NULL, NULL, '2026-05-24 01:44:38', 'public', '2026-05-24 01:44:38', '2026-05-24 01:44:38'),
(40, 15, '<p>Solo necesito mi guitarra y un buen rato a solas. 🎸 <br><em><span style=\"color: rgb(39, 174, 96);\">Relaxing loop para todos ustedes.</span></em></p>', NULL, 'public/assets/uploads/users/15/posts/1779587181/aud_1779587078_214.mp3', NULL, '2026-05-24 01:44:38', 'public', '2026-05-24 01:44:38', '2026-05-24 01:44:38'),
(41, 15, '<p><span style=\"color: rgb(142, 68, 173);\"><strong>Perspectiva diferente.</strong></span> A veces hay que cambiar el ángulo desde el que vemos las cosas. 🙃</p>', 'public/assets/uploads/users/15/posts/1779587182/img_1779587078_603.jpg', NULL, NULL, '2026-05-24 01:44:38', 'public', '2026-05-24 01:44:38', '2026-05-24 01:44:38');

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
(2, 6, '2026-05-24 07:53:44'),
(5, 13, '2026-05-23 01:45:10'),
(5, 32, '2026-05-24 04:13:44'),
(5, 33, '2026-05-24 04:13:45'),
(5, 34, '2026-05-24 04:13:46'),
(5, 38, '2026-05-24 04:13:37'),
(5, 39, '2026-05-24 04:13:41'),
(5, 41, '2026-05-24 04:13:43'),
(6, 4, '2026-05-22 03:25:56'),
(8, 7, '2026-05-23 18:46:22'),
(8, 13, '2026-05-23 18:46:23'),
(16, 2, '2026-05-24 01:49:08'),
(16, 13, '2026-05-24 01:49:08'),
(16, 18, '2026-05-24 01:49:08'),
(16, 26, '2026-05-24 01:49:08'),
(16, 31, '2026-05-24 01:49:08'),
(16, 35, '2026-05-24 01:49:08'),
(16, 39, '2026-05-24 01:49:08'),
(16, 41, '2026-05-24 01:49:08'),
(17, 31, '2026-05-24 01:49:08'),
(18, 13, '2026-05-24 01:49:08'),
(18, 17, '2026-05-24 01:49:08'),
(18, 29, '2026-05-24 01:49:08'),
(18, 35, '2026-05-24 01:49:08'),
(19, 3, '2026-05-24 01:49:09'),
(19, 7, '2026-05-24 01:49:09'),
(19, 17, '2026-05-24 01:49:08'),
(19, 25, '2026-05-24 01:49:09'),
(19, 28, '2026-05-24 01:49:08'),
(19, 35, '2026-05-24 01:49:08'),
(20, 28, '2026-05-24 01:49:09'),
(20, 37, '2026-05-24 01:49:09'),
(21, 3, '2026-05-24 01:49:09'),
(21, 29, '2026-05-24 01:49:09'),
(21, 35, '2026-05-24 01:49:09'),
(22, 2, '2026-05-24 01:49:09'),
(22, 6, '2026-05-24 01:49:09'),
(22, 13, '2026-05-24 01:49:09'),
(22, 26, '2026-05-24 01:49:09'),
(22, 30, '2026-05-24 01:49:09'),
(22, 35, '2026-05-24 01:49:09'),
(22, 41, '2026-05-24 01:49:09'),
(23, 3, '2026-05-24 01:49:09'),
(23, 5, '2026-05-24 01:49:09'),
(23, 25, '2026-05-24 01:49:09'),
(23, 30, '2026-05-24 01:49:09'),
(23, 33, '2026-05-24 01:49:09'),
(23, 39, '2026-05-24 01:49:09'),
(24, 2, '2026-05-24 01:49:09'),
(24, 3, '2026-05-24 01:49:09'),
(24, 4, '2026-05-24 01:49:09'),
(24, 5, '2026-05-24 01:49:09'),
(24, 17, '2026-05-24 01:49:09'),
(24, 19, '2026-05-24 01:49:09'),
(24, 28, '2026-05-24 01:49:09'),
(24, 32, '2026-05-24 01:49:09'),
(24, 35, '2026-05-24 01:49:09'),
(24, 39, '2026-05-24 01:49:09'),
(25, 2, '2026-05-24 01:49:09'),
(25, 3, '2026-05-24 01:49:09'),
(25, 5, '2026-05-24 01:49:09'),
(25, 19, '2026-05-24 01:49:09'),
(25, 31, '2026-05-24 01:49:09'),
(25, 36, '2026-05-24 01:49:09'),
(25, 41, '2026-05-24 01:49:09'),
(26, 2, '2026-05-24 01:49:09'),
(26, 3, '2026-05-24 01:49:09'),
(26, 6, '2026-05-24 01:49:09'),
(26, 7, '2026-05-24 01:49:09'),
(26, 13, '2026-05-24 01:49:09'),
(26, 14, '2026-05-24 01:49:09'),
(26, 18, '2026-05-24 01:49:09'),
(26, 19, '2026-05-24 01:49:09'),
(26, 28, '2026-05-24 01:49:09'),
(26, 30, '2026-05-24 01:49:09'),
(26, 31, '2026-05-24 01:49:09'),
(26, 38, '2026-05-24 01:49:09'),
(27, 3, '2026-05-24 01:49:09'),
(27, 7, '2026-05-24 01:49:09'),
(27, 14, '2026-05-24 01:49:09'),
(27, 19, '2026-05-24 01:49:09'),
(27, 25, '2026-05-24 01:49:09'),
(27, 29, '2026-05-24 01:49:09'),
(27, 34, '2026-05-24 01:49:09'),
(27, 37, '2026-05-24 01:49:09'),
(27, 39, '2026-05-24 01:49:09'),
(28, 6, '2026-05-24 01:49:09'),
(28, 14, '2026-05-24 01:49:09'),
(28, 29, '2026-05-24 01:49:09'),
(29, 13, '2026-05-24 01:49:09'),
(29, 26, '2026-05-24 01:49:09'),
(29, 40, '2026-05-24 01:49:09'),
(30, 3, '2026-05-24 01:49:09'),
(30, 6, '2026-05-24 01:49:09'),
(30, 7, '2026-05-24 01:49:09'),
(30, 13, '2026-05-24 01:49:09'),
(30, 28, '2026-05-24 01:49:09'),
(30, 30, '2026-05-24 01:49:09'),
(30, 34, '2026-05-24 01:49:09'),
(30, 36, '2026-05-24 01:49:09'),
(30, 37, '2026-05-24 01:49:09'),
(30, 40, '2026-05-24 01:49:09'),
(31, 32, '2026-05-24 07:54:55'),
(31, 35, '2026-05-24 07:54:57'),
(31, 40, '2026-05-24 07:54:53'),
(31, 41, '2026-05-24 07:54:54');

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
(5, 37, '2026-05-24 04:13:35'),
(5, 40, '2026-05-24 04:13:42'),
(6, 6, '2026-05-23 23:16:14'),
(6, 13, '2026-05-23 02:01:17'),
(8, 5, '2026-05-23 18:46:18'),
(8, 6, '2026-05-23 18:46:20'),
(8, 19, '2026-05-23 18:46:27'),
(16, 3, '2026-05-24 01:49:08'),
(16, 4, '2026-05-24 01:49:08'),
(16, 5, '2026-05-24 01:49:08'),
(16, 6, '2026-05-24 01:49:08'),
(16, 14, '2026-05-24 01:49:08'),
(16, 17, '2026-05-24 01:49:08'),
(16, 19, '2026-05-24 01:49:08'),
(16, 25, '2026-05-24 01:49:08'),
(16, 28, '2026-05-24 01:49:08'),
(16, 29, '2026-05-24 01:49:08'),
(16, 30, '2026-05-24 01:49:08'),
(16, 32, '2026-05-24 01:49:08'),
(16, 33, '2026-05-24 01:49:08'),
(16, 34, '2026-05-24 01:49:08'),
(16, 36, '2026-05-24 01:49:08'),
(16, 37, '2026-05-24 01:49:08'),
(16, 38, '2026-05-24 01:49:08'),
(16, 40, '2026-05-24 01:49:08'),
(17, 3, '2026-05-24 01:49:08'),
(17, 17, '2026-05-24 01:49:08'),
(17, 36, '2026-05-24 01:49:08'),
(17, 39, '2026-05-24 01:49:08'),
(18, 3, '2026-05-24 01:49:08'),
(18, 4, '2026-05-24 01:49:08'),
(18, 7, '2026-05-24 01:49:08'),
(18, 14, '2026-05-24 01:49:08'),
(18, 19, '2026-05-24 01:49:08'),
(18, 25, '2026-05-24 01:49:08'),
(18, 32, '2026-05-24 01:49:08'),
(18, 34, '2026-05-24 01:49:08'),
(18, 37, '2026-05-24 01:49:08'),
(18, 39, '2026-05-24 01:49:08'),
(18, 40, '2026-05-24 01:49:08'),
(18, 41, '2026-05-24 01:49:08'),
(19, 2, '2026-05-24 01:49:08'),
(19, 4, '2026-05-24 01:49:09'),
(19, 6, '2026-05-24 01:49:08'),
(19, 13, '2026-05-24 01:49:09'),
(19, 19, '2026-05-24 01:49:09'),
(19, 30, '2026-05-24 01:49:08'),
(19, 32, '2026-05-24 01:49:08'),
(19, 37, '2026-05-24 01:49:09'),
(19, 39, '2026-05-24 01:49:08'),
(20, 13, '2026-05-24 01:49:09'),
(21, 4, '2026-05-24 01:49:09'),
(21, 19, '2026-05-24 01:49:09'),
(21, 25, '2026-05-24 01:49:09'),
(21, 33, '2026-05-24 01:49:09'),
(21, 36, '2026-05-24 01:49:09'),
(21, 41, '2026-05-24 01:49:09'),
(22, 5, '2026-05-24 01:49:09'),
(22, 7, '2026-05-24 01:49:09'),
(22, 14, '2026-05-24 01:49:09'),
(22, 17, '2026-05-24 01:49:09'),
(22, 25, '2026-05-24 01:49:09'),
(22, 31, '2026-05-24 01:49:09'),
(22, 32, '2026-05-24 01:49:09'),
(22, 34, '2026-05-24 01:49:09'),
(22, 38, '2026-05-24 01:49:09'),
(22, 40, '2026-05-24 01:49:09'),
(23, 2, '2026-05-24 01:49:09'),
(23, 4, '2026-05-24 01:49:09'),
(23, 6, '2026-05-24 01:49:09'),
(23, 7, '2026-05-24 01:49:09'),
(23, 13, '2026-05-24 01:49:09'),
(23, 17, '2026-05-24 01:49:09'),
(23, 18, '2026-05-24 01:49:09'),
(23, 19, '2026-05-24 01:49:09'),
(23, 26, '2026-05-24 01:49:09'),
(23, 28, '2026-05-24 01:49:09'),
(23, 31, '2026-05-24 01:49:09'),
(23, 34, '2026-05-24 01:49:09'),
(23, 35, '2026-05-24 01:49:09'),
(23, 36, '2026-05-24 01:49:09'),
(23, 37, '2026-05-24 01:49:09'),
(23, 38, '2026-05-24 01:49:09'),
(23, 40, '2026-05-24 01:49:09'),
(23, 41, '2026-05-24 01:49:09'),
(24, 6, '2026-05-24 01:49:09'),
(24, 7, '2026-05-24 01:49:09'),
(24, 14, '2026-05-24 01:49:09'),
(24, 18, '2026-05-24 01:49:09'),
(24, 25, '2026-05-24 01:49:09'),
(24, 26, '2026-05-24 01:49:09'),
(24, 29, '2026-05-24 01:49:09'),
(24, 30, '2026-05-24 01:49:09'),
(24, 31, '2026-05-24 01:49:09'),
(24, 33, '2026-05-24 01:49:09'),
(24, 34, '2026-05-24 01:49:09'),
(24, 36, '2026-05-24 01:49:09'),
(24, 37, '2026-05-24 01:49:09'),
(24, 38, '2026-05-24 01:49:09'),
(24, 40, '2026-05-24 01:49:09'),
(24, 41, '2026-05-24 01:49:09'),
(25, 4, '2026-05-24 01:49:09'),
(25, 6, '2026-05-24 01:49:09'),
(25, 7, '2026-05-24 01:49:09'),
(25, 13, '2026-05-24 01:49:09'),
(25, 14, '2026-05-24 01:49:09'),
(25, 17, '2026-05-24 01:49:09'),
(25, 18, '2026-05-24 01:49:09'),
(25, 25, '2026-05-24 01:49:09'),
(25, 26, '2026-05-24 01:49:09'),
(25, 28, '2026-05-24 01:49:09'),
(25, 29, '2026-05-24 01:49:09'),
(25, 30, '2026-05-24 01:49:09'),
(25, 32, '2026-05-24 01:49:09'),
(25, 33, '2026-05-24 01:49:09'),
(25, 34, '2026-05-24 01:49:09'),
(25, 37, '2026-05-24 01:49:09'),
(25, 38, '2026-05-24 01:49:09'),
(25, 39, '2026-05-24 01:49:09'),
(25, 40, '2026-05-24 01:49:09'),
(26, 4, '2026-05-24 01:49:09'),
(26, 5, '2026-05-24 01:49:09'),
(26, 17, '2026-05-24 01:49:09'),
(26, 25, '2026-05-24 01:49:09'),
(26, 26, '2026-05-24 01:49:09'),
(26, 29, '2026-05-24 01:49:09'),
(26, 32, '2026-05-24 01:49:09'),
(26, 33, '2026-05-24 01:49:09'),
(26, 34, '2026-05-24 01:49:09'),
(26, 35, '2026-05-24 01:49:09'),
(26, 36, '2026-05-24 01:49:09'),
(26, 37, '2026-05-24 01:49:09'),
(26, 39, '2026-05-24 01:49:09'),
(26, 40, '2026-05-24 01:49:09'),
(26, 41, '2026-05-24 01:49:09'),
(27, 2, '2026-05-24 01:49:09'),
(27, 4, '2026-05-24 01:49:09'),
(27, 6, '2026-05-24 01:49:09'),
(27, 13, '2026-05-24 01:49:09'),
(27, 17, '2026-05-24 01:49:09'),
(27, 18, '2026-05-24 01:49:09'),
(27, 26, '2026-05-24 01:49:09'),
(27, 28, '2026-05-24 01:49:09'),
(27, 30, '2026-05-24 01:49:09'),
(27, 31, '2026-05-24 01:49:09'),
(27, 32, '2026-05-24 01:49:09'),
(27, 33, '2026-05-24 01:49:09'),
(27, 36, '2026-05-24 01:49:09'),
(27, 38, '2026-05-24 01:49:09'),
(27, 40, '2026-05-24 01:49:09'),
(27, 41, '2026-05-24 01:49:09'),
(28, 28, '2026-05-24 01:49:09'),
(28, 35, '2026-05-24 01:49:09'),
(28, 37, '2026-05-24 01:49:09'),
(28, 38, '2026-05-24 01:49:09'),
(29, 2, '2026-05-24 01:49:09'),
(29, 3, '2026-05-24 01:49:09'),
(29, 6, '2026-05-24 01:49:09'),
(29, 7, '2026-05-24 01:49:09'),
(29, 17, '2026-05-24 01:49:09'),
(29, 18, '2026-05-24 01:49:09'),
(29, 28, '2026-05-24 01:49:09'),
(29, 29, '2026-05-24 01:49:09'),
(29, 31, '2026-05-24 01:49:09'),
(29, 34, '2026-05-24 01:49:09'),
(29, 35, '2026-05-24 01:49:09'),
(29, 41, '2026-05-24 01:49:09'),
(30, 4, '2026-05-24 01:49:09'),
(30, 19, '2026-05-24 01:49:09'),
(30, 25, '2026-05-24 01:49:09'),
(30, 29, '2026-05-24 01:49:09'),
(30, 31, '2026-05-24 01:49:09'),
(30, 33, '2026-05-24 01:49:09'),
(30, 35, '2026-05-24 01:49:09'),
(30, 38, '2026-05-24 01:49:09'),
(30, 39, '2026-05-24 01:49:09');

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
(17, 5, 'aeaef12e39b95803d05382d1e13ea193c2a977d681a44bc5d42345daa93f92e9', NULL, NULL, '2026-06-22 01:28:55', NULL, '2026-05-22 20:28:55'),
(63, 5, 'b06df313653e58cbe08716cc630144e37574fc031bbd9f53212922d16180aa13', NULL, NULL, '2026-06-23 04:15:40', NULL, '2026-05-24 04:15:40'),
(65, 8, 'cb9c6984a5fb31d3bf43031a28a06aa0a9a6653eae80b0853d826476b4dc63ab', NULL, NULL, '2026-06-23 04:16:19', NULL, '2026-05-24 04:16:19'),
(67, 5, 'b1a01a2689c95acf5f52c8413209ddcf92b3e8664b9b02c9345d2a8c3fdbe0b3', NULL, NULL, '2026-06-23 04:16:37', NULL, '2026-05-24 04:16:37');

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
(2, 'jose', 'nestor@gmail.com', '$2y$10$DBaAJ49S8FnZe2.F1/IeS.xMgmkxzVmN58duHclMmVl25KqSqL.ie', '1975-07-18', 'Argentina', NULL, NULL, '2026-05-18 19:07:59', '2026-05-24 07:46:22', '2026-05-24 07:46:22'),
(3, 'usuario_demo', 'demo@privanet.com', '$2y$10$VMdWch.jZ9aLcXGrRfORHuBvdpWCydFJhKIqgg9IB0aggomeg43yG', '1995-05-15', 'Argentina', NULL, NULL, '2026-05-21 11:22:43', '2026-05-21 11:22:43', NULL),
(4, 'otro_usuario', 'otro@privanet.com', '$2y$10$DEeH7kztldsrjmp.0x6Ss.NXF.4j.hNgjfTmj.0Qk5cfdaX5BipnK', '1998-08-20', 'Argentina', NULL, NULL, '2026-05-21 11:22:43', '2026-05-21 11:22:43', NULL),
(5, 'elias', 'eliasm@gmail.com', '$2y$10$Mkervu9C2s6kXvK/ZCwg0eV86G4wVNq0uVArsIsJcFeJaho2X0CY6', '2001-04-08', 'Argentina', NULL, NULL, '2026-05-21 20:56:37', '2026-05-24 22:41:28', '2026-05-24 22:41:28'),
(6, 'pepe', 'pepe@gmail.com', '$2y$10$.DNEXuE2VJNvfjBWDozrxuUf5vbjYzY107M4WUVX1MPNzBlnBFpKW', '1995-10-08', 'Argentina', NULL, NULL, '2026-05-22 03:19:53', '2026-05-23 20:05:05', '2026-05-23 20:05:05'),
(7, 'Carlos', 'carlos@gmail.com', '$2y$10$NKdmorMYJMtPuQvRikqCB.bhJ3Uqad7JSYHxvgNw1plRWXdyChpuC', '1990-02-10', 'Argentina', NULL, NULL, '2026-05-23 02:07:46', '2026-05-23 19:36:37', NULL),
(8, 'Thomas', 'thomas@gmail.com', '$2y$10$OalmLopKUVa44001gZCBhOQBN3uDUOTMXjgbRdC2mH.8D5mM/Wg.a', '2004-01-06', 'Argentina', NULL, NULL, '2026-05-23 18:40:49', '2026-05-24 22:21:31', '2026-05-24 22:21:31'),
(9, 'Cri', 'cri@gmail.com', '$2y$10$LwFiWsv3.bTysrhFB6/1HOH3D8KbBc9UNxR5vUhubajL.5pDcLoHy', '1200-02-12', 'Alemania', NULL, NULL, '2026-05-24 01:35:04', '2026-05-24 01:35:04', NULL),
(10, 'mariana_viajes', 'mariana@example.com', '$2y$10$Py8jttSdpZt8lVxmm7yPnus9Eu/zyJiLRIfKwoVsz8HVacNmCs68W', '1990-05-15', 'Argentina', NULL, NULL, '2026-05-24 01:40:49', '2026-05-24 01:54:07', '2026-05-24 01:54:07'),
(11, 'dj_lucas', 'lucas@example.com', '$2y$10$POQM6FXs/OqT9RAYs.nl1.FxnOfhLRogJsmdO8p0HwLVV6KBeB1Ne', '1995-10-20', 'España', NULL, NULL, '2026-05-24 01:40:49', '2026-05-24 01:55:46', '2026-05-24 01:55:46'),
(12, 'foto_natura', 'natura@example.com', '$2y$10$CXWLPqL3JrvPbpSRDd.1lO/tAm.LRl87S76wpIfn9FSF.GnNQwx5K', '1985-02-10', 'Chile', NULL, NULL, '2026-05-24 01:40:49', '2026-05-24 01:40:49', NULL),
(13, 'arquitectura_top', 'arq@example.com', '$2y$10$B1QfJPHrMPve5az7x6t7WOkK23pnlJohiqdne4D3Avb1zf7b16s0K', '1988-03-22', 'Colombia', NULL, NULL, '2026-05-24 01:44:38', '2026-05-24 01:52:23', '2026-05-24 01:52:23'),
(14, 'tech_guru', 'tech@example.com', '$2y$10$2QX1y/4ThHUn8yxjglJaPeI3l1Rl0dqOqOjWxmdT2xMgDAqyZkbIO', '1992-11-05', 'México', NULL, NULL, '2026-05-24 01:44:38', '2026-05-24 01:44:38', NULL),
(15, 'chill_vibes', 'chill@example.com', '$2y$10$4.3W.SBgLJGLwfHcY/F1/.RkblwMMCfZHe.uSSlo1T7ODhTChaOSq', '1998-07-12', 'Uruguay', NULL, NULL, '2026-05-24 01:44:38', '2026-05-24 01:44:38', NULL),
(16, 'usuario_fantasma_1', 'fantasma1@example.com', '$2y$10$Jyp4mWCg1Ucv9750woxHx.JyRFEz0c4gEVnf3t1CIg4F6X6Dgqkmi', '1991-01-18', 'Argentina', NULL, NULL, '2026-05-24 01:49:08', '2026-05-24 01:49:08', NULL),
(17, 'usuario_fantasma_2', 'fantasma2@example.com', '$2y$10$b5M7wvSNKdXp6twrlMLqFOOhiLh9YN20jiaFod9L1aFNVmfU49L96', '1996-05-10', 'Argentina', NULL, NULL, '2026-05-24 01:49:08', '2026-05-24 01:49:08', NULL),
(18, 'usuario_fantasma_3', 'fantasma3@example.com', '$2y$10$pqkVUD69CNLOy/rsNFbgi.pwxLKFFNUe6PGTC52mJFgYqHc4UDMWS', '1993-02-16', 'Argentina', NULL, NULL, '2026-05-24 01:49:08', '2026-05-24 01:49:08', NULL),
(19, 'usuario_fantasma_4', 'fantasma4@example.com', '$2y$10$WgKkoZbjpSDWgz5uqzoenuXud6Di9j4ZMN2l75BJ36GZcUaMml3zK', '1995-05-12', 'Argentina', NULL, NULL, '2026-05-24 01:49:08', '2026-05-24 01:49:08', NULL),
(20, 'usuario_fantasma_5', 'fantasma5@example.com', '$2y$10$9Ke8llfCUnOzebGln45Xu.RehsxO6zkXQ69ksL9mg8ZTDFan8uLES', '1990-03-19', 'Argentina', NULL, NULL, '2026-05-24 01:49:08', '2026-05-24 01:49:08', NULL),
(21, 'usuario_fantasma_6', 'fantasma6@example.com', '$2y$10$06C9/H31jLEa1Ko8XhxqqOmiy0wvKYxpomVsctvqiLzstkTLDf94.', '1993-04-18', 'Argentina', NULL, NULL, '2026-05-24 01:49:08', '2026-05-24 01:49:08', NULL),
(22, 'usuario_fantasma_7', 'fantasma7@example.com', '$2y$10$yPQR.LE7mJqKRwfFdVykr.L.Y/qNixDI8pq3qnLXRj9MUBYYnXwAS', '1996-02-14', 'Argentina', NULL, NULL, '2026-05-24 01:49:08', '2026-05-24 01:49:08', NULL),
(23, 'usuario_fantasma_8', 'fantasma8@example.com', '$2y$10$b0gLohuox5o5g9OFDPtTNu4CP9/Ap6SNc8a8egYtXzb5YSiqNTrWG', '1995-03-16', 'Argentina', NULL, NULL, '2026-05-24 01:49:08', '2026-05-24 01:49:08', NULL),
(24, 'usuario_fantasma_9', 'fantasma9@example.com', '$2y$10$9Zx5LfAJobW5Qh25LTWqrebAVil6uf1w9CEA89drn.Kl4VGs5JmhW', '1991-04-18', 'Argentina', NULL, NULL, '2026-05-24 01:49:08', '2026-05-24 01:49:08', NULL),
(25, 'usuario_fantasma_10', 'fantasma10@example.com', '$2y$10$r8VgKuWjgq9UySRCGiHDS.w8cVf0IxeMaloWHQXF1DowB22JpVko6', '1993-08-19', 'Argentina', NULL, NULL, '2026-05-24 01:49:08', '2026-05-24 01:49:08', NULL),
(26, 'usuario_fantasma_11', 'fantasma11@example.com', '$2y$10$aJtuBw5/6yJxgGjecbhpC.TC85y/RzWcbevhML0d5BdUsrdY2WpwO', '1997-01-13', 'Argentina', NULL, NULL, '2026-05-24 01:49:08', '2026-05-24 01:49:08', NULL),
(27, 'usuario_fantasma_12', 'fantasma12@example.com', '$2y$10$HLVDXKaesupOHbYpJdYsQOxCttR6MKXhZHVXuwX7TMnr6X0ypkOTa', '1996-07-11', 'Argentina', NULL, NULL, '2026-05-24 01:49:08', '2026-05-24 01:49:08', NULL),
(28, 'usuario_fantasma_13', 'fantasma13@example.com', '$2y$10$5h7SKGLGNR6hJHqYwfxdWOTmbuYTx7q4gKhIJwi6.E5VIeSbLcviO', '1991-06-11', 'Argentina', NULL, NULL, '2026-05-24 01:49:08', '2026-05-24 01:49:08', NULL),
(29, 'usuario_fantasma_14', 'fantasma14@example.com', '$2y$10$qdoENbuzK8xx5M9NmQB60uZdWzIEw.zDXkEJa0TtwnzYoactibjvW', '1990-08-19', 'Argentina', NULL, NULL, '2026-05-24 01:49:08', '2026-05-24 01:49:08', NULL),
(30, 'usuario_fantasma_15', 'fantasma15@example.com', '$2y$10$fTpQGeWkfncZMcezTaGDaO6K78Y6O04Za014ftN/iu1CCPrm6jMay', '1995-04-17', 'Argentina', NULL, NULL, '2026-05-24 01:49:08', '2026-05-24 01:49:08', NULL),
(31, 'dislike', 'dislike@gmail.com', '$2y$10$Yq6v0jccbpkM7Srmt30LvecK1DFj2rN8jH1ctMXH.Bc0XQ8eElqrq', '2002-01-01', 'Argentina', NULL, NULL, '2026-05-24 07:54:47', '2026-05-24 22:40:12', '2026-05-24 22:40:12');

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
ALTER TABLE `users` ADD FULLTEXT KEY `ft_users_username` (`username`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT de la tabla `remember_tokens`
--
ALTER TABLE `remember_tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

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
