# Tareas Pendientes y Requerimientos Faltantes - PrivaNET

Este archivo contiene la lista de control de las funcionalidades del enunciado del examen final que aún faltan implementar, corregir o completar en el proyecto.

---

## 🔐 1. Registro e Inicio de Sesión
- [x] **Validación alfanumérica de contraseña**:
  - Modificar la validación en [register.js](file:///c:/xampp/htdocs/PrivaNet/src/View/Login/register.js) y [AuthController.php](file:///c:/xampp/htdocs/PrivaNet/src/Controllers/AuthController.php) para asegurar que la contraseña contenga únicamente caracteres alfanuméricos (letras y números sin espacios ni caracteres especiales), además de mantener la longitud mínima de 8 caracteres.
- [x] **Banner de Bienvenida dinámico en Inicio**:
  - [x] Ocultar o condicionar el banner de bienvenida en [index.php (Homepage)](file:///c:/xampp/htdocs/PrivaNet/src/View/Homepage/index.php) para que solo se muestre a usuarios registrados.
  - [x] Implementar la consulta para contar el número total de calificaciones "Me gusta" recibidas en todos los posteos propios del usuario logueado y mostrarlo en dicho mensaje de bienvenida.

---

## 📝 2. Creación de Publicaciones (Posteos)
- [x] **Programación / Planificación de publicaciones**:
  - Agregar controles (fecha y hora) en el formulario de creación ([create_post_form.php](file:///c:/xampp/htdocs/PrivaNet/src/View/components/create_post_form.php)) para programar publicaciones en el futuro (límite máximo de 3 días a partir del momento de carga).
  - Modificar el controlador ([PublicationController.php](file:///c:/xampp/htdocs/PrivaNet/src/Controllers/PublicationController.php)) y repositorio para guardar la fecha en la columna `scheduled_at` y el valor condicional para `published_at`.
- [x] **Filtro de publicaciones programadas**:
  - Ajustar las consultas en [PublicationRepository.php](file:///c:/xampp/htdocs/PrivaNet/src/Repositories/PublicationRepository.php) para excluir todas las publicaciones cuya fecha `published_at` sea en el futuro.
- [x] **Validación de longitud de texto**:
  - Validar en el servidor que el texto de la publicación no exceda los 255 caracteres (`mb_strlen($text) <= 255`).
- [x] **Soporte de formato en textos**:
  - Permitir de forma segura que el texto del posteo incluya negrita (`<b>` o `<strong>`), cursiva (`<i>` o `<em>`), colores (`<span style="color: ...">`) e hipervínculos (`<a href="...">`). Reemplazar la llamada directa a `htmlspecialchars()` en [post_card.php](file:///c:/xampp/htdocs/PrivaNet/src/View/components/post_card.php) por un método de escape seguro/sanitización selectiva.
- [x] **Validaciones de imágenes**:
  - [x] Validar en el servidor ([UploadHelper.php](file:///c:/xampp/htdocs/PrivaNet/src/Helpers/UploadHelper.php)) que el archivo subido sea únicamente de formato JPEG (`.jpg` / `.jpeg` y tipo MIME `image/jpeg`).
  - [x] Validar mediante `getimagesize()` que la resolución de la imagen no sea mayor a 1600 x 1200 píxeles.
- [x] **Validaciones de audios**:
  - [x] Validar en el servidor que el archivo de audio subido sea de formato MP3 (`.mp3` y tipo MIME `audio/mpeg`).
  - [x] Validar (en cliente antes de la subida o en el servidor analizando la duración) que el audio tenga una duración máxima de 30 segundos.

---

## 🏠 3. Feed de la Página de Inicio
- [x] **Filtrado del Feed por interacciones del usuario**:
  - [x] Corregir el método `getLatestPublic` en [PublicationRepository.php](file:///c:/xampp/htdocs/PrivaNet/src/Repositories/PublicationRepository.php) para que, si el usuario está logueado y tiene posteos marcados como "Favorito" o con "Me gusta", la página de inicio muestre **únicamente** los posteos recientes de los autores de dichas publicaciones.
  - [x] Implementar el fallback para que se muestren las últimas 10 publicaciones de usuarios registrados solo en caso de que el usuario no posea ningún post guardado como favorito o con me gusta.

---

## 🔍 4. Búsqueda y Detalle de Publicaciones
- [x] **Vista Detalle de Publicación**:
  - [x] Implementar una página de detalle dedicada (por ejemplo, ruta `/post?id=XXX` en [Router.php](file:///c:/xampp/htdocs/PrivaNet/src/Services/Router.php)) para que los usuarios puedan "acceder a un posteo específico para verlo completamente".
  - [x] Añadir enlaces en las tarjetas que se muestran en el resultado de búsqueda ([Search_Result/index.php](file:///c:/xampp/htdocs/PrivaNet/src/View/Search_Result/index.php)) para poder navegar a dicha vista completa.

---

## 🛠️ 5. Panel de Control de Publicaciones
- [x] **Modificación completa de multimedia**:
  - [x] Ampliar el modal de edición en [Dashboard/index.php](file:///c:/xampp/htdocs/PrivaNet/src/View/Dashboard/index.php) y el script en [dashboard.js](file:///c:/xampp/htdocs/PrivaNet/public/assets/js/dashboard.js) para permitir reemplazar o eliminar la imagen y/o el archivo de audio asignado actualmente a la publicación, bajo las mismas restricciones del alta.
- [x] **Visualización de Estadísticas en el Panel**:
  - [x] Mostrar claramente la cantidad de "Me gusta" y "No me gusta" que ha recibido cada uno de los posteos listados en el panel de control del usuario (esto se puede mostrar en el pie de las tarjetas renderizadas en el panel).
