<?php
$page_title = 'PrivaNET - Panel de Control';
ob_start();
?>
<main class="container main-layout">
    <div class="post-card" style="margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h2 style="font-size: 1.3rem; margin-bottom: 5px; color: var(--text-main);">Panel de Gestión de Publicaciones</h2>
            <p style="color: var(--text-muted); font-size: 0.9rem;">Administra tus posteos, revisa sus estadísticas y realiza modificaciones o bajas.</p>
        </div>
    </div>

    <div class="post-card" style="padding: 0; overflow: hidden;">
        <table class="dashboard-table">
            <thead>
                <tr>
                    <th>Publicación</th>
                    <th>Me gusta</th>
                    <th>No me gusta</th>
                    <th>Fecha</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="dashboard-posts-tbody">
                <tr>
                    <td>
                        <p class="truncate">¡Increíble viaje por Trevelin! 🌷🏔️ Los campos de tulipanes...</p>
                    </td>
                    <td class="center"><span class="count positive">14</span></td>
                    <td class="center"><span class="count negative">2</span></td>
                    <td class="center small">18 May 2026</td>
                    <td class="right">
                        <button type="button" class="muted-btn">Editar</button>
                        <button type="button" class="danger-btn">Eliminar</button>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p class="truncate">Otro ejemplo de publicación visible para cualquier visitante...</p>
                    </td>
                    <td class="center"><span class="count positive">5</span></td>
                    <td class="center"><span class="count negative">0</span></td>
                    <td class="center small">18 May 2026</td>
                    <td class="right">
                        <button type="button" class="muted-btn">Editar</button>
                        <button type="button" class="danger-btn">Eliminar</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</main>

<?php
$page_content = ob_get_clean();
include __DIR__ . '/../layouts/base.php';
?>
