<?php
// Header reutilizable. Detecta la ruta y aplica clase .active a la nav correspondiente.
$req = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$base = '/PrivaNet';
$route = rtrim(str_replace($base, '', $req), '/') ?: '/';

function nav_active($path, $route) {
	// $path debe ser la parte que siga al base, p.ej. '/publicar' o '/actividad'
	if ($path === '/') {
		return ($route === '/' || $route === '/index.php' || $route === '/public/index.php') ? 'nav-link active' : 'nav-link';
	}
	return $route === $path ? 'nav-link active' : 'nav-link';
}
?>

<header class="main-header">
	<div class="header-content">
		<div class="logo-container">
			<h1 id="site-title"><a href="/PrivaNet/">PrivaNET</a></h1>
		</div>

		<?php if (!empty($_SESSION['username'])): ?>

			<div class="search-bar">
				<form action="/PrivaNet/buscar" method="GET">
					<input type="text" name="q" placeholder="Buscar publicaciones, usuarios..." value="<?php echo htmlspecialchars($_GET['q'] ?? ''); ?>">
				</form>
			</div>

			<nav class="main-nav">
				<a href="/PrivaNet/" class="<?php echo nav_active('/', $route); ?>">Inicio</a>
				<span class="nav-sep">│</span>
				<a href="/PrivaNet/publicar" class="<?php echo nav_active('/publicar', $route); ?>">Realizar post</a>
				<span class="nav-sep">│</span>
				<a href="/PrivaNet/actividad" class="<?php echo nav_active('/actividad', $route); ?>">Mi actividad</a>
				<span class="nav-sep">│</span>
				<a href="/PrivaNet/panel" class="<?php echo nav_active('/panel', $route); ?>">Panel de control</a>
			</nav>

			<div class="user-menu">
				<span>Hola, @<?php echo htmlspecialchars($_SESSION['username'] ?? 'usuario'); ?></span>
				<form method="POST" action="/PrivaNet/index.php">
					<input type="hidden" name="action" value="logout">
					<button type="submit" class="header-button">Cerrar sesión</button>
				</form>
			</div>

		<?php else: ?>

			<!-- Usuario no autenticado: header simplificado -->
			<div class="header-simple">
				<p class="muted">Bienvenido a PrivaNET</p>
			</div>

		<?php endif; ?>

	</div>
</header>
