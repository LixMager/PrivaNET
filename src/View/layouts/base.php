<?php
// Plantilla base mínima. Variables esperadas: $page_title, $page_content
?><!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php echo htmlspecialchars($page_title ?? 'PrivaNET'); ?></title>
	<link rel="icon" type="image/svg+xml" href="/PrivaNet/public/favicon.svg">
	<link rel="stylesheet" href="/PrivaNet/public/assets/css/base.css?v=<?php echo time(); ?>">
	<link rel="stylesheet" href="/PrivaNet/public/assets/css/header.css?v=<?php echo time(); ?>">
	<link rel="stylesheet" href="/PrivaNet/public/assets/css/post.css?v=<?php echo time(); ?>">
	<link rel="stylesheet" href="/PrivaNet/public/assets/css/modal.css?v=<?php echo time(); ?>">
	<link rel="stylesheet" href="/PrivaNet/public/assets/css/lightbox.css?v=<?php echo time(); ?>">
	<link rel="stylesheet" href="/PrivaNet/public/assets/css/activity.css?v=<?php echo time(); ?>">
	<link rel="stylesheet" href="/PrivaNet/src/View/Homepage/homepage.css?v=<?php echo time(); ?>">
</head>
<body>
	<?php include_once __DIR__ . '/../components/header.php'; ?>

	<?php echo $page_content ?? ''; ?>

	<!-- Lightbox Modal for Thumbnails -->
	<div id="lightbox-modal" class="lightbox-overlay">
		<div class="lightbox-content">
			<button type="button" class="lightbox-close-btn" id="lightbox-close">✕</button>
			<div class="lightbox-media-container">
				<img id="lightbox-image" class="lightbox-img" src="" alt="Vista ampliada">
			</div>
			<div id="lightbox-post-info" class="lightbox-post-info">
				<header class="lightbox-post-header">
					<h3 id="lightbox-author">@username</h3>
				</header>
				<div class="lightbox-post-body">
					<div id="lightbox-post-text" class="lightbox-post-text"></div>
					<div id="lightbox-audio-container" class="lightbox-audio-container">
						<audio id="lightbox-audio" controls></audio>
					</div>
				</div>
				<footer id="lightbox-post-actions" class="lightbox-post-actions">
				</footer>
			</div>
		</div>
	</div>

	<script src="/PrivaNet/public/assets/js/timeformat.js?v=<?php echo time(); ?>"></script>
	<script src="/PrivaNet/public/assets/js/lightbox.js?v=<?php echo time(); ?>"></script>
	<?php echo $page_scripts ?? ''; ?>
</body>
</html>