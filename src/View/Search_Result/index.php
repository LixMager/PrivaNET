<?php
$page_title = 'PrivaNET - Buscar publicaciones';
ob_start();
?>
<main class="container main-layout">
	<!-- <section class="post-card" style="margin-bottom: 30px;">
		<h2>Explorar y Buscar Publicaciones</h2>
		<form action="/PrivaNet/buscar" method="GET" class="search-form">
			<input type="text" name="q" placeholder="Escribe palabras clave, hashtags o @usuarios..." value="<?php echo htmlspecialchars($_GET['q'] ?? ''); ?>">
			<button type="submit">Buscar</button>
		</form>
	</section> -->

	<section class="results-section">
		<h3 class="muted">Resultados de la búsqueda</h3>
		<div id="search-results-container" class="posts-container">
			<?php if (!empty($_GET['q'])): ?>
				<div class="post-card" style="text-align: center; padding: 40px 20px;">
					<p>No se encontraron publicaciones exactas para "<strong><?php echo htmlspecialchars($_GET['q']); ?></strong>".</p>
					<span class="muted small">(Esta sección se conectará dinámicamente vía AJAX con el backend)</span>
				</div>
			<?php else: ?>
				<div class="post-card" style="text-align: center; padding: 40px 20px;">
					<p class="muted">Utiliza la barra de búsqueda superior para encontrar contenido de tu interés.</p>
				</div>
			<?php endif; ?>
		</div>
	</section>
</main>

<?php
$page_content = ob_get_clean();
include __DIR__ . '/../layouts/base.php';
?>
