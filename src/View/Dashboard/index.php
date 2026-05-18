<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PrivaNET - Panel de Control</title>
    <link rel="stylesheet" href="public/inicio.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="src/view/Homepage/homepage.css?v=<?php echo time(); ?>">
</head>

<body>

    <header class="main-header" style="padding: 12px 0;">
        <div class="header-content" style="display: flex; align-items: center; justify-content: space-between; gap: 20px; padding: 0 30px; width: 100%;">
            <!-- 1. Logo (Izquierda del todo) -->
            <div class="logo-container" style="flex-shrink: 0;">
                <h1 id="site-title" style="margin: 0;"><a href="/PrivaNet/" style="color: var(--header-text); text-decoration: none;">PrivaNET</a></h1>
            </div>

            <!-- 2. Buscador (A la derecha del logo, flexible con min y max) -->
            <div class="search-bar" style="flex: 1; min-width: 150px; max-width: 450px; margin: 0 10px;">
                <form action="/PrivaNet/buscar" method="GET" style="margin: 0; display: flex; width: 100%;">
                    <input type="text" name="q" placeholder="Buscar publicaciones, usuarios..."
                        style="width: 100%; background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); color: white; border-radius: 20px; padding: 8px 15px; outline: none;">
                </form>
            </div>

            <!-- 3. Navegación (Centro-Derecha) -->
            <nav class="main-nav" style="display: flex; align-items: center; gap: 15px; flex-shrink: 0;">
                <a href="/PrivaNet/publicar" style="color: var(--header-text); text-decoration: none; font-size: 0.95rem; font-weight: 500;">Realizar post</a>
                <span style="color: rgba(255,255,255,0.3);">│</span>
                <a href="/PrivaNet/actividad" style="color: var(--header-text); text-decoration: none; font-size: 0.95rem; font-weight: 500;">Mi actividad</a>
                <span style="color: rgba(255,255,255,0.3);">│</span>
                <a href="/PrivaNet/panel" style="color: var(--header-text); text-decoration: underline; font-size: 0.95rem; font-weight: 600;">Panel de control</a>
            </nav>

            <!-- 4. Usuario y Logout (Derecha del todo) -->
            <div class="user-menu" style="display: flex; flex-direction: column; align-items: flex-end; gap: 5px; flex-shrink: 0;">
                <span style="color: var(--header-text); font-weight: 500; font-size: 0.9rem;">Hola, @usuario_demo</span>
                <form method="POST" action="/PrivaNet/index.php" style="margin: 0;">
                    <input type="hidden" name="action" value="logout">
                    <button type="submit"
                        style="background: transparent; border: 1px solid rgba(255,255,255,0.5); color: var(--header-text); font-size: 0.8rem; padding: 4px 10px; border-radius: 6px; cursor: pointer; transition: background 0.2s;"
                        onmouseover="this.style.background='rgba(255,255,255,0.1)'"
                        onmouseout="this.style.background='transparent'">Cerrar sesión</button>
                </form>
            </div>
        </div>
    </header>

    <main class="container main-layout" style="grid-template-columns: 1fr; max-width: 900px; margin: 0 auto;">
        <div class="post-card" style="margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h2 style="font-size: 1.3rem; margin-bottom: 5px; color: var(--text-main);">Panel de Gestión de Publicaciones</h2>
                <p style="color: var(--text-muted); font-size: 0.9rem;">Administra tus posteos, revisa sus estadísticas y realiza modificaciones o bajas.</p>
            </div>
            <a href="/PrivaNet/publicar" style="background: var(--primary-color); color: white; text-decoration: none; padding: 8px 16px; border-radius: var(--radius); font-weight: 500; font-size: 0.9rem;">+ Nuevo Post</a>
        </div>

        <div class="post-card" style="padding: 0; overflow: hidden;">
            <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.95rem;">
                <thead>
                    <tr style="background: rgba(0,0,0,0.02); border-bottom: 1px solid var(--border-color);">
                        <th style="padding: 15px 20px; font-weight: 600; color: var(--text-main);">Publicación</th>
                        <th style="padding: 15px 20px; font-weight: 600; color: var(--text-main); text-align: center;">Me gusta</th>
                        <th style="padding: 15px 20px; font-weight: 600; color: var(--text-main); text-align: center;">No me gusta</th>
                        <th style="padding: 15px 20px; font-weight: 600; color: var(--text-main); text-align: center;">Fecha</th>
                        <th style="padding: 15px 20px; font-weight: 600; color: var(--text-main); text-align: right;">Acciones</th>
                    </tr>
                </thead>
                <tbody id="dashboard-posts-tbody">
                    <tr style="border-bottom: 1px solid var(--border-color);">
                        <td style="padding: 15px 20px;">
                            <p style="margin: 0; color: var(--text-main); max-width: 350px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">¡Increíble viaje por Trevelin! 🌷🏔️ Los campos de tulipanes...</p>
                        </td>
                        <td style="padding: 15px 20px; text-align: center;"><span style="color: var(--primary-color); font-weight: 600;">14</span></td>
                        <td style="padding: 15px 20px; text-align: center;"><span style="color: #ef4444; font-weight: 600;">2</span></td>
                        <td style="padding: 15px 20px; text-align: center; color: var(--text-muted); font-size: 0.85rem;">18 May 2026</td>
                        <td style="padding: 15px 20px; text-align: right;">
                            <button type="button" style="background: var(--bg-body); color: var(--text-main); padding: 5px 10px; font-size: 0.85rem; margin-right: 5px;">Editar</button>
                            <button type="button" style="background: #fee2e2; color: #ef4444; padding: 5px 10px; font-size: 0.85rem;">Eliminar</button>
                        </td>
                    </tr>
                    <tr style="border-bottom: 1px solid var(--border-color);">
                        <td style="padding: 15px 20px;">
                            <p style="margin: 0; color: var(--text-main); max-width: 350px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">Otro ejemplo de publicación visible para cualquier visitante...</p>
                        </td>
                        <td style="padding: 15px 20px; text-align: center;"><span style="color: var(--primary-color); font-weight: 600;">5</span></td>
                        <td style="padding: 15px 20px; text-align: center;"><span style="color: #ef4444; font-weight: 600;">0</span></td>
                        <td style="padding: 15px 20px; text-align: center; color: var(--text-muted); font-size: 0.85rem;">18 May 2026</td>
                        <td style="padding: 15px 20px; text-align: right;">
                            <button type="button" style="background: var(--bg-body); color: var(--text-main); padding: 5px 10px; font-size: 0.85rem; margin-right: 5px;">Editar</button>
                            <button type="button" style="background: #fee2e2; color: #ef4444; padding: 5px 10px; font-size: 0.85rem;">Eliminar</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </main>

</body>

</html>
