<?php
// Plantilla base mínima. Variables esperadas: $page_title, $page_content
?><!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php echo htmlspecialchars($page_title ?? 'PrivaNET'); ?></title>
	<link rel="stylesheet" href="/PrivaNet/public/inicio.css?v=<?php echo time(); ?>">
	<link rel="stylesheet" href="/PrivaNet/public/layout.css?v=<?php echo time(); ?>">
	<link rel="stylesheet" href="/PrivaNet/src/View/Homepage/homepage.css?v=<?php echo time(); ?>">
</head>
<body>
	<?php include_once __DIR__ . '/../components/header.php'; ?>

	<?php echo $page_content ?? ''; ?>

	<!-- Lightbox Modal for Thumbnails -->
	<div id="lightbox-modal" class="lightbox-overlay">
		<div class="lightbox-content">
			<button type="button" class="lightbox-close-btn" id="lightbox-close">&times;</button>
			<img id="lightbox-image" class="lightbox-img" src="" alt="Vista ampliada">
			<div id="lightbox-caption" class="lightbox-caption"></div>
		</div>
	</div>

	<script src="/PrivaNet/public/assets/js/lightbox.js?v=<?php echo time(); ?>"></script>
	<?php echo $page_scripts ?? ''; ?>
</body>
</html>