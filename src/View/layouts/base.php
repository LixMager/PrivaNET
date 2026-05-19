<?php
// Plantilla base mínima. Variables esperadas: $page_title, $page_content
?><!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php echo htmlspecialchars($page_title ?? 'PrivaNET'); ?></title>
	<link rel="stylesheet" href="public/inicio.css?v=<?php echo time(); ?>">
	<link rel="stylesheet" href="public/layout.css?v=<?php echo time(); ?>">
	<link rel="stylesheet" href="src/view/Homepage/homepage.css?v=<?php echo time(); ?>">
</head>
<body>
	<?php include_once __DIR__ . '/../components/header.php'; ?>

	<?php echo $page_content ?? ''; ?>
</body>
</html>