# Respuestas a Preguntas de Defensa - PrivaNet
Alumno: Elías Magallanes Guerrero

## 1. BASE DE DATOS Y CONSULTAS SQL

**01.** Utilicé **MySQL/MariaDB**. Lo elegí porque es el motor relacional por excelencia, tiene excelente integración nativa con PHP a través de PDO, es fácil de mantener y ofrece búsquedas FULLTEXT, ideales para este proyecto.

**02.** La tabla `users` tiene una Primary Key (`id`), restricciones UNIQUE en `username` y `email`, y campos NOT NULL para datos obligatorios como `password_hash` y `birth_date`.

**03.** Modelé la tabla `posts` con campos separados y que permiten nulos (NULL) para cada tipo de contenido: `text_content` (VARCHAR), `image_path` (VARCHAR) y `audio_path` (VARCHAR). Un posteo puede tener los tres campos llenos simultáneamente.

**04.** Implementé la relación mediante **tablas intermedias** separadas: `post_likes` y `post_dislikes`. Esto permite llevar un registro de qué usuario interactuó con qué posteo, evitando redundancias.

**05.** Utilicé una tabla separada llamada `favorites`, con el mismo enfoque de tabla intermedia que vincula `user_id` y `post_id`.

**06.** La tabla `posts` incluye dos campos TIMESTAMP: `scheduled_at` (para guardar la hora programada originalmente) y `published_at`. El campo `published_at` es el que se usa en las consultas para decidir si el posteo es visible (`published_at <= NOW()`).

**07.** Utilizo un `SELECT` con `ORDER BY p.created_at DESC LIMIT 10`, aplicando en la cláusula `WHERE` la condición `(p.published_at IS NULL OR p.published_at <= NOW())` para que los programados a futuro no aparezcan.

**08.** Lo garantizo desde la lógica de PHP en `PublicationRepository`. Cuando un usuario hace "me gusta", el sistema ejecuta primero un `DELETE FROM post_dislikes`, y luego inserta el registro en `post_likes` (y viceversa).

**09.** Creé índices FK en `user_id` de los posteos e interacciones para optimizar los JOINs, índices B-Tree en `published_at` y `scheduled_at` para acelerar el filtrado de fechas, y un índice FULLTEXT en `text_content` para búsquedas eficientes de texto.

**10.** Utilizo búsquedas de texto completo nativas de MySQL. La consulta usa `MATCH(text_content) AGAINST(:query IN NATURAL LANGUAGE MODE)` aprovechando el índice FULLTEXT, lo cual es mucho más eficiente que un simple `LIKE`.

**11.** Se guarda hasheada. Utilicé la función nativa de PHP `password_hash()` con el algoritmo bcrypt.

**12.** Evito inyecciones utilizando la extensión **PDO** y **consultas preparadas** (prepared statements), pasando los valores dinámicos a través de parámetros de enlace (`?` o `:nombre`), nunca concatenando strings SQL.

**13.** La consulta usa una subquery en la cláusula `IN`: 
`SELECT DISTINCT p2.user_id FROM posts p2 WHERE p2.id IN (SELECT post_id FROM post_likes WHERE user_id = ?) OR p2.id IN (SELECT post_id FROM favorites WHERE user_id = ?)` y luego busco los posts de esos autores.

**14.** Sí, se verifica la unicidad mediante una restricción `UNIQUE` en la columna `username` en la base de datos, además de la validación en tiempo real por AJAX desde PHP.


## 2. SESIONES PHP, COOKIES Y AUTENTICACIÓN

**01.** Implementé un sistema de login basado en sesiones nativas. Al verificar la contraseña con `password_verify()`, guardo en la variable superglobal `$_SESSION` los datos del usuario autenticado.

**02.** Sí, uso `session_start()`. Está ubicado de forma centralizada al inicio de los archivos principales o scripts de bootstrap antes de que se envíe cualquier salida (headers) al navegador.

**03.** Guardo principalmente el `user_id` y el `username` de manera estrictamente segura del lado del servidor (evitando usar cookies manipulables para el nombre de usuario), además de un flag booleano `usuario_autenticado`.

**04.** Verifico la sesión mediante una condición centralizada. Si el usuario intenta entrar a una vista protegida y `!isset($_SESSION['user_id'])`, lo redirijo mediante la cabecera HTTP `Location: /PrivaNet/index.php`.

**05.** Utilizo tanto `session_unset()` para limpiar las variables de la sesión, como `session_destroy()` para eliminar físicamente el archivo de sesión en el servidor, además de invalidar la cookie si existe.

**06.** Es un ataque donde un tercero fuerza un ID de sesión. Lo prevengo usando `session_regenerate_id(true)` durante el login para generar un identificador completamente nuevo.

**07.** A través del encabezado `User-Agent` que llega en la variable `$_SERVER['HTTP_USER_AGENT']`, guardándolo junto con la IP en la tabla `remember_tokens` o analizándolo.

**08.** Sí, utilicé cookies para la funcionalidad de "Recordarme" (auto-login), almacenando un token seguro que se contrasta con la tabla `remember_tokens` en la BD.

**09.** Una cookie de sesión existe en la memoria del navegador y desaparece cuando este se cierra. Una cookie persistente tiene un atributo de expiración (Expires/Max-Age) y sobrevive a los reinicios del navegador.

**10.** Sí, el atributo HttpOnly impide que JavaScript acceda a la cookie (mitigando ataques XSS). El atributo Secure garantiza que la cookie solo viaje por conexiones encriptadas HTTPS.

**11.** La sesión expira de todos modos por inactividad del lado del servidor (garbage collection de PHP). Por defecto, esto suele ser tras 24 minutos (`session.gc_maxlifetime`), y la cookie de sesión del navegador se elimina al cerrarlo.

**12.** Para mostrarlo en la interfaz por dispositivo, implementé una cookie única por usuario (ej: `ultimo_acceso_elias`) cuyo valor es un objeto JSON codificado con el formato `{"username": "...", "last_access": "..."}` en UTC. Además, actualizo el campo `last_login_at` en la tabla `users` para control del servidor.


## 3. AJAX Y PETICIONES ASÍNCRONAS

**01.** Usé AJAX en el registro (para validar usuario disponible), login, likes, favorites, publicación y búsqueda. Lo elegí para evitar recargar toda la página y dar una experiencia fluida (Single Page Application feel).

**02.** Utilicé `XMLHttpRequest` clásico (nativo). No usé Fetch ni librerías como jQuery para demostrar dominio de las bases de JavaScript y cumplir de manera estricta con los requerimientos del proyecto.

**03.** Verifico el código de estado (`xhr.status === 200`). Si falla o cae en la clausula `catch` al parsear JSON, muestro un mensaje de error visual dentro del DOM (banners o textos en color rojo).

**04.** Mis scripts PHP retornan respuestas en formato **JSON** (`{"success": true, "message": "...", "data": ...}`).

**05.** Mediante AJAX envío el ID del posteo al servidor. Si PHP responde con éxito, JavaScript encuentra el elemento en el DOM usando `document.querySelector` y actualiza las clases CSS (para iluminar el botón) y el contador numérico.

**06.** Verifico al comienzo del script de procesamiento si el usuario tiene una sesión activa (`isset($_SESSION['user_id'])`). Si no es así, retorno un JSON con código de error (`401 Unauthorized`) o finalizo la ejecución.

**07.** Para este enfoque base nos apoyamos principalmente en la sesión nativa de PHP. *Nota: si se implementó un campo oculto, mencionarlo. De lo contrario, se menciona que se protege mediante la cookie de sesión HttpOnly y verificación de orígenes.*

**08.** Es una búsqueda asíncrona pero accionada por eventos: se carga al presionar "Buscar", y los resultados se inyectan en el DOM sin recargar la página.

**09.** Sí, antes de hacer `echo json_encode(...)`, configuro la cabecera `header('Content-Type: application/json');` en PHP para que el navegador interprete correctamente el paquete.

**10.** Deshabilito los botones temporalmente (`button.disabled = true`) o uso banderas (flags) booleanas en JS mientras la petición está en curso (readyState != 4), reactivándolos solo al recibir respuesta.


## 4. MANEJO DE MULTIMEDIA (IMÁGENES Y AUDIOS)

**01.** Utilizo `finfo_open(FILEINFO_MIME_TYPE)` o `mime_content_type()` en PHP para leer los *magic bytes* del archivo en el servidor, ignorando completamente la extensión reportada por el usuario en `$_FILES`.

**02.** Utilizo la función nativa `getimagesize()` de PHP. Esta me devuelve un array donde los índices 0 y 1 representan el ancho y alto en píxeles.

**03.** Los guardo dentro del directorio del proyecto (`public/assets/uploads/users/...`), pero estructurados por usuario y separados de los scripts PHP.

**04.** En el lado del cliente (JS), uso un elemento `<canvas>` temporal para redimensionar la imagen si supera las medidas máximas, comprimiéndola antes de enviarla. En el servidor uso CSS (object-fit) para ajustar el display como thumbnail.

**05.** Utilizo un sistema de **Lightbox Modal** customizado creado con CSS y JS. Al hacer clic en la imagen miniatura, abre el modal en pantalla completa mostrando la imagen o el audio.

**06.** Verifico el MIME type (audio/mpeg) en el backend. Para la duración, lo valido primariamente en el frontend leyendo `audio.duration` en la meta-data antes de subir, y si es necesario mediante librerías de lectura de cabeceras en PHP.

**07.** Uso la etiqueta estándar de HTML5 `<audio controls><source src="..." type="audio/mpeg"></audio>`.

**08.** Uso un prefijo único junto con un identificador o número aleatorio (ej. `img_XXXX.jpg` o `aud_XXXX.mp3`) combinado con marcas de tiempo (timestamp de la carpeta) para garantizar que nunca haya colisiones.

**09.** Sí, se valida verificando `$_FILES['archivo']['size']`. El límite en el servidor también está sujeto a las directivas `upload_max_filesize` y `post_max_size` de `php.ini`.

**10.** Validando estrictamente el MIME type real del contenido con `finfo` y asegurándome de no permitir extensiones .php, reescribiendo además el nombre del archivo al guardarlo.

**11.** Son accesibles directamente por URL, lo cual es normal para contenido estático público. La seguridad se garantiza no permitiendo que la carpeta ejecute scripts mediante reglas del servidor web (`.htaccess`).

**12.** El formulario HTML debe incluir `enctype="multipart/form-data"`. En PHP leo ambos desde el array superglobal `$_FILES`, procesándolos, validándolos y moviéndolos con `move_uploaded_file()` de a uno.


## 5. VARIABLES PHP Y SUPERGLOBALES

**01.** Utilicé: `$_POST`, `$_GET`, `$_SESSION`, `$_FILES` y `$_SERVER`.

**02.** `$_GET` viaja en la URL y se usa para solicitar datos (como IDs en consultas). `$_POST` viaja en el cuerpo HTTP y lo uso para enviar formularios sensibles (login, publicación) de forma segura o mutaciones de datos.

**03.** Contiene un array bidimensional con índices principales: `name` (nombre original), `type` (tipo MIME sugerido), `tmp_name` (ubicación temporal en el servidor), `error` (código de error si lo hay) y `size` (tamaño en bytes).

**04.** Contiene información del entorno del servidor y de ejecución. Usé por ejemplo `$_SERVER['REQUEST_METHOD']` para saber si la petición es un POST o un GET, y podría usar `REMOTE_ADDR` para la IP.

**05.** Uso `session_start()` al comienzo. Asigno valores a `$_SESSION['user_id'] = $id;` y luego, en otras páginas, compruebo `isset($_SESSION['user_id'])` para identificar que ese cliente ya ha sido autenticado.

**06.** Utilizo `setcookie()` para enviar instrucciones al navegador (crear, modificar, expirar). Utilizo directamente el array `$_COOKIE` solo para **leer** el valor en solicitudes entrantes.

**07.** Contiene el contenido combinado de `$_GET`, `$_POST` y `$_COOKIE`. Se desaconseja porque no es explícito sobre el origen de los datos, lo cual puede generar vulnerabilidades o comportamientos inesperados.

**08.** Es un array asociativo que contiene todas las variables de ámbito global. Se desaconseja en el desarrollo moderno y OOP; es preferible usar inyección de dependencias o propiedades de clase.

**09.** Utilizando funciones como `trim()`, validando formatos, o convirtiendo a enteros con `(int)`. Al insertarlos a la BD uso las consultas preparadas de PDO que actúan como principal barrera de escape.

**10.** Sí, resultan útiles para validar correos electrónicos (`FILTER_VALIDATE_EMAIL`) u otros datos específicos de manera estándar en lugar de escribir expresiones regulares a mano.

**11.** La interpolación permite incrustar variables dentro de strings encerrados en comillas dobles (ej. `"Hola $nombre"`). Con comillas simples, PHP interpreta el string literalmente sin evaluar variables ni caracteres de escape especiales.


## 6. PARADIGMA ORIENTADO A OBJETOS EN PHP

**01.** Separé el proyecto en capas: Modelos (`User`, `Publication`), Repositorios (`PublicationRepository`), Controladores/Handlers de peticiones, y utilicé un patrón arquitectónico similar a **MVC** (Modelo-Vista-Controlador).

**02.** La modelé definiendo propiedades encapsuladas (`id`, `username`, `email`, etc.). Los métodos incluyen constructores y funciones getter y setter para interactuar con esos datos.

**03.** Definí propiedades privadas o protegidas para el texto y las rutas de los archivos (image y audio), permitiendo nulos. Además, agregué propiedades booleanas (`is_liked`, `is_favorited`) para el renderizado del feed.

**04.** En la estructura principal no necesité jerarquías complejas de herencia, sino que prioricé la composición, aunque el framework subyacente o controladores base pueden hacer uso de `extends`.

**05.** Declarando las propiedades de mis clases como `private`. El acceso a ellas desde fuera de la clase ocurre exclusivamente a través de los métodos públicos (getters y setters).

**06.** Creé una clase manejadora `Database` que inicializa el objeto `PDO` de PHP centralizado. Todo repositorio recibe esa instancia de conexión por inyección de dependencias (constructor).

**07.** Una clase abstracta puede tener código implementado y atributos, mientras que una interfaz solo define contratos (firmas de métodos).

**08.** Sí, estructuré el proyecto con `src/Models`, `src/Repositories`, `src/View` y recursos públicos en `public/assets`. Esta organización clara sigue el estándar moderno de PHP.

**09.** Usé el estándar PSR-4 mediante Composer autoload. Es útil porque evita estar llenando el código de `require_once`, haciendo que PHP cargue los archivos de clase de manera automática solo cuando se instancian.

**10.** Usé bloques `try/catch` para capturar `PDOException` cuando se interactúa con la base de datos o surgen errores críticos, devolviendo mensajes amigables al cliente.


## 7. HTML5, CSS3 Y ESTRUCTURA DE PÁGINAS

**01.** Login (`/Login`), Dashboard (`/Dashboard`), Activity (`/Activity`) y Homepage (`/Homepage`). Cada una tiene una responsabilidad específica (autenticar, feed principal, posteos propios, interacciones previas).

**02.** Sí, uso `<header>` para las barras de navegación, `<main>` para los contenedores principales del feed, y `<article>` para delimitar contextualmente cada publicación individual.

**03.** Del lado cliente uso el atributo `required` de HTML5 y eventos de JS para advertir antes del submit. Del lado servidor (PHP) valido cada campo para garantizar la integridad, respondiendo con errores JSON vía AJAX.

**04.** Utilicé una expresión regular en JS (`/^[a-zA-Z0-9]+$/`) y comprobé `password.length >= 8`. Si falla, freno el evento submit de AJAX.

**05.** Utilicé la API nativa de `Date()` en JS, restando el año de nacimiento del actual y ajustando si el mes/día aún no había pasado en el año corriente, verificando que la diferencia sea `>= 13`.

**06.** **No utilicé frameworks CSS**. Preferí usar **CSS Vanilla** (CSS puro) empleando variables nativas (custom properties) y layout con Flexbox, para tener un control granular absoluto sobre el diseño.

**07.** Implementé **Media Queries** (`@media (max-width: ...)`) para ajustar los menús de navegación laterales en dispositivos móviles, pasándolos a la parte inferior o agrupándolos en hamburguesas.

**08.** Utilicé la librería de terceros **Quill.js**. La inicializo sobre un contenedor `div` y la librería abstrae el contenteditable para brindar herramientas de formato ricas y consistentes.

**09.** Quill genera el formato HTML y yo lo envío al backend vía POST. En la base de datos se guarda ese HTML directamente; y al momento de visualizar, la seguridad radica en que no se ejecutan scripts directamente (sin embargo, el HTML crudo debe ser estrictamente originado de la librería).

**10.** Utilicé vistas y layouts de PHP (`base.php` o `header.php`) que se incluyen en todas las páginas, manejando la navegación como módulos reutilizables y manteniendo el estado a través de la sesión de PHP.


## 8. JAVASCRIPT DEL LADO DEL CLIENTE

**01.** Creé la carpeta `public/assets/js/` con archivos `.js` externos modulares separados según propósito (`dashboard.js`, `interactions.js`, `timeformat.js`, etc.) y los importo con etiquetas `<script src="...">`.

**02.** *Respuesta estándar:* Si se guardan preferencias menores de UI (tema oscuro/claro) se puede usar localStorage. (Si no se usaron, se especifica que toda la persistencia se manejó en BD/Sesiones para mantener centralizado el estado).

**03.** El `localStorage` persiste indefinidamente hasta que el usuario borra los datos del navegador. El `sessionStorage` se limpia automáticamente en cuanto se cierra la pestaña o la ventana.

**04.** En la respuesta AJAX, tomo el botón accionado, y utilizo métodos como `classList.toggle('active')` para cambiar el estado visual, y extraigo el número del DOM, le sumo o resto 1, y reasigno con `innerHTML` o `textContent`.

**05.** Usé `document.addEventListener('DOMContentLoaded', ...)` porque este evento se dispara apenas el documento HTML se analizó, sin esperar a que carguen imágenes completas. `window.onload` espera a todos los recursos visuales, lo que hace percibir la carga como más lenta.

**06.** Aproveché la API nativa `URL.createObjectURL(file)`, creando una ruta local temporal en el navegador y asignándosela a una etiqueta `<img>` en el DOM antes de hacer la subida.

**07.** Para mantener compatibilidad total nativa según requerimientos de un rol Junior, utilicé **callbacks clásicos** atados al evento `xhr.onreadystatechange` dentro de XMLHttpRequest, sin Promises ni async/await.

**08.** Validé capturando el evento submit: verifiqué que el contenido de texto generado por el editor Quill no superara los caracteres permitidos y evalué los validadores del tamaño de archivo.

**09.** Además de la librería Quill.js mencionada anteriormente (para el Rich Text Editor), el resto del código es Vanilla Javascript puro. Por ejemplo, implementé un toggle interactivo (mostrar/ocultar contraseña con el ícono ◉/○) en el formulario de registro usando manipulaciones puras del DOM y sin jQuery.

**10.** La validación se debe realizar del lado del cliente revisando las fechas (calculando los ms de diferencia), pero es imperativo que también se valide en el lado del **servidor** (PHP) para evitar vulnerabilidades de peticiones forzadas.


## 9. FLUJO DE LA APLICACIÓN Y NAVEGACIÓN

**01.** El usuario entra a la ruta base, es redirigido o ve el index/login (`Login/index.php`). Crea la cuenta en el modal/página de Registro. Luego de loguearse exitosamente, el sistema carga el `Dashboard`, donde usa el formulario superior para crear el posteo.

**02.** El validador central en PHP detecta que la sesión no está definida, por lo que bloquea la renderización y despacha un `header("Location: /PrivaNet/")`, redirigiéndolo a la pantalla de acceso.

**03.** A nivel del repositorio PHP, se comprueba si el usuario hizo algún like/favote. Si sí, la consulta SQL utiliza la lógica para buscar los autores de esos posteos; de lo contrario, muestra una selección global de posteos ordenados cronológicamente.

**04.** En las páginas de login/registro se ejecuta una comprobación inversa: si `isset($_SESSION['user_id'])`, se le prohíbe permanecer ahí y se lo redirecciona instantáneamente al Dashboard.

**05.** El panel muestra los posteos, generalmente ordenados de más reciente a más viejo y limitados en número o divididos por una consulta de paginación o carga en scroll en caso de crecer sustancialmente.

**06.** Sí, al hacer click en "Editar", JS abre un Modal, carga dinámicamente el contenido anterior del posteo utilizando métodos como `dangerouslyPasteHTML` de Quill y prepara variables ocultas (ID del posteo) para hacer un UPDATE (vía AJAX) sobre el registro.

**07.** Al usar restricciones de llave foránea (`ON DELETE CASCADE`) en la base de datos (o la lógica del repositorio), si el post_id es eliminado, automáticamente se borran en cascada los likes, dislikes y favorites asociados.

**08.** En el método delete o update, el servidor primero ejecuta consultas `SELECT` para obtener las rutas locales a disco de los audios o imágenes, y luego utiliza la función `unlink()` de PHP para borrarlos físicamente del filesystem antes o después de la eliminación de base de datos.

**09.** El proceso puede resolverse validando `published_at` sobre la fecha en vivo al mostrar. El post se guarda; si llega la fecha estipulada, su `published_at` se vuelve menor o igual al tiempo de servidor (`NOW()`) y, naturalmente, entra en la zona de consulta "visible" sin requerir un cron externo.

**10.** Se guarda en la base de datos un `last_login_at` que se actualiza en la petición del login; al renderizar la vista, el PHP formatea y extrae este valor de los atributos de sesión para inyectarlo en el saludo inicial.


## 10. SEGURIDAD DE LA APLICACIÓN

**01.** Es la inyección de JavaScript malicioso por parte de usuarios. Se previene esterilizando o escapando cualquier output que el servidor haga de valores proporcionados por el cliente antes de escupirlos al navegador.

**02.** Efectivamente. Al imprimir datos sensibles en las vistas PHP usamos `htmlspecialchars()` u otro mecanismo de sanitización, impidiendo que el motor JS interprete scripts en los strings.

**03.** Consiste en manipular la entrada para alterar consultas de DB. La medida absoluta utilizada fue el uso de Consultas Preparadas con **PDO** parametrizando variables, anulando cualquier inserción maliciosa.

**04.** PHP usa la función criptográficamente segura `password_hash()` con constante algoritmo PASSWORD_BCRYPT o DEFAULT para guardar, y usa `password_verify()` en el login para cotejar una string limpia con el hash de BD.

**05.** Cross-Site Request Forgery es cuando un tercero fuerza el navegador a hacer requests no deseados. Se previene implementando Tokens CSRF en formularios de estado o delegando confianza a cookies seguras del mismo sitio.

**06.** No confío en la extensión. Se ha implementado el análisis de Mimetypes con herramientas subyacentes como `finfo` de PHP o leyendo datos directamente del bloque HTTP.

**07.** Sí, al crear arquitecturas limpias, las carpetas `uploads` suelen negarse de ejecución de PHP con ficheros `.htaccess` de Apache restringiendo engines y scripts para proteger el FileSystem general.

**08.** Dictamina a los navegadores si las cookies de sesión se deben adjuntar a requests de origines cruzados. El valor `Lax` o `Strict` ayuda a mitigar masivamente los ataques CSRF.

**09.** En cada Query de Update/Delete, el backend exige estrictamente la verificación de que el usuario emisor sea el dueño; es decir, la sentencia SQL incluye la cláusula `WHERE post_id = ? AND user_id = ?` (ID de usuario obtenido confiablemente de su `$_SESSION`).

**10.** No, en entornos de producción los detalles críticos y Stack Traces técnicos de bases de datos se enmascaran en `catch (\PDOException $e)`. El usuario solo recibe un mensaje genérico ("Error temporal").


## 11. LIBRERÍAS Y FRAMEWORKS UTILIZADOS

**01.** No utilicé frameworks como Laravel o CodeIgniter. Empleé únicamente Vanilla PHP (PHP Puro), para dominar la arquitectura básica subyacente y la creación de un sistema Model-View manual.

**02.** Utilicé Composer para implementar la estructura de autocarga PSR-4 de clases (`autoload`), lo cual profesionaliza mi manejo de namespaces.

**03.** No utilicé jQuery. Fue una decisión de diseño enfocada a usar únicamente ECMAScript moderno / Web APIs, lo que incluye DOM manipulado y peticiones a través de puro AJAX nativo.

**04.** Utilicé la librería **Quill.js**. Se importan sus scripts de núcleo CSS/JS, se instancia con `new Quill()` sobre un nodo y se exporta utilizando su estructura interna hacia texto o innerHTML durante el Submit.

**05.** No empleé librerías externas para la validación front, prioricé el uso de Atributos Formativos integrados de HTML5 (`required`, `pattern`) entrelazados con simples comprobaciones ad-hoc en mis scripts JS Vanilla.

**06.** Utilicé **PDO (PHP Data Objects)**. La ventaja fundamental es su enfoque Orientado a Objetos genérico, la posibilidad de Prepared Statements nativos y portabilidad a futuros diferentes motores SQL si fuera necesario.

**07.** Desarrollé de cero las hojas de estilo sin Bootstrap. Configuré una lista sólida de variables CSS raíz (`:root`), que definen todo el esquema de color y un diseño adaptable, logrando mucha personalización de inicio a fin.

**08.** Las librerías JavaScript Front-End, como Quill o iconos, en gran medida fueron importadas a través de redes CDN (Content Delivery Network), integrándose velozmente como `<script src="...">` en la cabecera.


## 12. DOCUMENTACIÓN Y ENTREGA

**01.** Las páginas comprenden un Entry Point (Index/Login), el Dashboard Global con feed de posteos, el Activity (perfil propio con logs de mis interacciones) y utilitarias como página `404 Not Found`.

**02.** Seguí un patrón estándar: la raíz del proyecto para archivos elementales o scripts front-controller, un directorio `src` encapsulando la lógica pesada (`Models`, `Repositories`, `View`), y `public/assets` para entregables estáticos (CSS, JS, uploads).

**03.** Sí, el script provisto (`privanet.sql`) tiene declaraciones automáticas (`CREATE TABLE`) definiendo las constraints precisas, e incrusta una tanda de volcado de datos (INSERTs con usuarios y testeos de posteos).

**04.** Evalué el comportamiento de los Likes frente a Dislikes y cómo interactuar. Decidí programar el servidor con operaciones de exclusión mutua directa al hacer Toggle entre ellos sin lanzar alertas, y la restricción a AJAX obligatorio me llevó a reestructurar la manera de subir archivos (`FormData` nativos).

**05.** El formato exigido o el material expuesto de la aplicación debería detallar todos esos pasos de modo descriptivo y gráfico.

**06.** La metodología fue puramente ágil incremental: configuré primero la BD (la capa basal), construí las Clases/Repositorios abstractos, seguí con el diseño estático, para finalmente unirlos con JS vía Peticiones Asíncronas (AJAX), refactorizando componentes a lo largo del proceso.
