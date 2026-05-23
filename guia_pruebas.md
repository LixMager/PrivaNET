# Flujo de Pruebas - Defensa PrivaNET

Sigue este flujo paso a paso durante tu defensa para demostrar todas las funcionalidades requeridas sin trabarte ni olvidar nada:

## 1. Vista de Visitante (Sin sesión)
1. Abrí el navegador (preferentemente en ventana de Incógnito) y entrá a `http://localhost/PrivaNet/`.
2. **Mostrá el inicio:** Hacé scroll para que vean los últimos 10 posteos cronológicos.
3. Hacé clic en botones como "Me gusta"; demostrá que **no hace nada** o ignora la acción por no estar logueado.

## 2. Registro de Usuario (Validaciones)
1. Clic en **Registrarse**.
2. **Probá errores a propósito:**
   - Intentá registrar el usuario "elias" (ya existe) para mostrar la validación AJAX.
   - Ingresá una contraseña menor a 8 caracteres o sin números/letras.
   - Ingresá una fecha de nacimiento del año actual (para que rechace por < 13 años).
3. **Registro exitoso:** Completá el formulario con datos válidos (ej: `profe_tester`).

## 3. Login y Mensaje de Bienvenida
1. Clic en **Iniciar Sesión**. Ingresá con la cuenta nueva.
2. Ya logueado, entrá a la página de **Inicio**.
3. **Mostrá el mensaje de bienvenida:**
   - Mostrá el texto de "Tu último login fue el: ...".
   - Hacé notar el mensaje *"Tus publicaciones no han recibido calificaciones 'me gusta' aún"*.
   - Destacá que, al ser nuevo, estás viendo el feed global (los últimos 10) porque aún no interactuaste con nadie.

## 4. Crear Posteos (El Editor y Multimedia)
1. Andá al **Dashboard (Panel de Control)** o usá la caja superior en Inicio.
2. **Posteo Multimedia:**
   - Escribí texto usando el editor Quill (poné algo en **negrita**, *cursiva* y un enlace).
   - Subí una imagen. Si pesa/mide mucho, mencioná que tu JS la comprime.
   - Subí un archivo de audio MP3 (menor a 30s).
   - Publicá el post.
3. **Posteo Programado:**
   - Creá otro posteo solo de texto.
   - Clic en el checkbox de **"Programar"**.
   - Elegí una fecha para mañana.
   - Mostrá que el post **aparece en tu panel**, pero **no en la portada global** ni en la de los demás.

## 5. Búsqueda y Navegación
1. Usá la barra de **Búsqueda** superior y poné una palabra que sepas que está en otro posteo.
2. Presioná Enter. En los resultados, hacé clic sobre el botón "Ir al post" o en la tarjeta.
3. **Mostrá las interacciones:** Dentro del detalle de ese post, dale a **Me Gusta** y al botón de **Favorito**.

## 6. Listas de Actividad
1. Navegá a **Mi Actividad** (icono de estrella/corazón en el menú).
2. Hacé clic en las 3 pestañas (Likes, Dislikes, Favoritos) para demostrar que carga por AJAX sin recargar la página.
3. Mostrá que allí aparece el posteo con el que acabás de interactuar.

## 7. Feed Dinámico
1. Volvé al **Inicio**.
2. Como le diste "Me Gusta" a un autor, mostrá cómo **el feed inteligente cambió** y ahora prioriza posteos de los usuarios con los que interactuaste (mostrando el post al que diste Like u otros del mismo autor).

## 8. Modificar y Eliminar
1. Andá a **Mis Posteos** (Dashboard).
2. **Modificar:** Hacé clic en el lápiz amarillo de tu primer post. Cambiale el texto o quitale la imagen y guardá. Mostrá que se actualiza sin recargar la pantalla.
3. **Eliminar:** Hacé clic en el botón rojo de basura. Mostrá cómo la tarjeta desaparece con la animación (Fade-out).

## 9. Cierre: Conteo de Likes
1. Cerrá sesión y logueate con otra cuenta tuya (ej. tu usuario principal "elias").
2. Dale "Me Gusta" al posteo de `profe_tester`.
3. Logueate de nuevo como `profe_tester`.
4. Mostrá el Inicio: El mensaje de bienvenida ahora debe decir **"Tus publicaciones han recibido 1 'me gusta' en total."**
