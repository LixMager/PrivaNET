# TRABAJO PRÁCTICO INTEGRADOR

**Alumno:** Elias Magallanes  
**Fecha de Entrega:** 24 de Mayo de 2026  
**Proyecto:** Aplicación Web "PrivaNET"

---

## 1. Guía de Instalación
La aplicación ha sido desarrollada sin la utilización de frameworks de terceros en el lado del servidor ni del cliente (arquitectura nativa basada en el patrón Modelo-Vista-Controlador). 

**Requisitos Técnicos:**
- Servidor web (Apache recomendado).
- Intérprete PHP (versión 8.0 o superior).
- Servidor de base de datos MySQL o MariaDB.
*(Se recomienda el uso de entornos como XAMPP o WAMP para ejecución local).*

**Procedimiento de Despliegue:**
1. Descomprimir o clonar el código fuente dentro del directorio de acceso público del servidor web (por ejemplo, `C:\xampp\htdocs\PrivaNet`).
2. Iniciar los servicios de Apache y MySQL.
3. Importar el script SQL provisto (`src/database/privanet.sql`) mediante el gestor de base de datos (por ejemplo, phpMyAdmin). Esto generará el esquema `privanet`, las tablas requeridas y cargará registros de prueba.
4. Si las credenciales del servidor MySQL local difieren de las estándar (usuario `root`, sin contraseña), se debe actualizar la cadena de conexión en el archivo `config/database.php`.
5. Acceder a la aplicación a través de la URL de localhost, especificando el directorio raíz (ej. `http://localhost/PrivaNet/`).

---

## 2. Detalle de Páginas Web Principales

La arquitectura de navegación consta de las siguientes interfaces fundamentales:

- **Inicio / Login (`index.php`):** Punto de entrada de la aplicación. Expone el formulario de autenticación para usuarios existentes y el formulario de alta para nuevos registros. Como vista pública, despliega un *feed* de sólo lectura para visitantes no autenticados.
- **Muro Principal (`index.php?action=home`):** Interfaz central post-autenticación. Consume y lista las publicaciones de la plataforma, dispone del formulario de creación de nuevos posteos (soporte multimedia) y provee los controles asíncronos para la interacción entre usuarios.
- **Panel de Control / Dashboard (`index.php?action=dashboard`):** Módulo de administración de contenido propio. Recupera exclusivamente los registros creados por el usuario en sesión, facilitando operaciones CRUD (actualización de contenido multimedia/textual y eliminación definitiva de registros).
- **Historial de Actividad (`index.php?action=activity`):** Interfaz de auditoría de interacciones. Visualiza de manera tabulada y categorizada el historial de reacciones del usuario ("Me gusta", "No me gusta" y "Favoritos").
- **Resultados de Búsqueda (`index.php?action=search`):** Salida del motor de búsqueda. Ejecuta consultas *Full-Text* sobre la base de datos para localizar cadenas de texto y renderizar las publicaciones coincidentes.

---

## 3. Manual del Usuario

A continuación se detalla la operatoria estándar para la interacción con la plataforma.

### 3.1. Acceso y Registro
Para operar el sistema, el individuo debe poseer credenciales válidas. Si es un nuevo usuario, debe completar el formulario "Registro de nuevos usuarios" con información personal obligatoria (Usuario, Email, Contraseña mayor a 8 caracteres, Fecha de nacimiento, País). Tras el alta, el ingreso se realiza desde el panel superior "Iniciar sesión".

> **[Insertar captura de pantalla de los formularios de Registro y Login]**

### 3.2. Publicación de Contenido
Una vez autenticado, el usuario visualiza la caja de publicación en el Muro Principal. Se provee un editor de texto enriquecido y botones para adjuntar recursos (imágenes o audios locales). Al ejecutar la acción "Publicar", el contenido se sube al servidor, se persiste en base de datos y se actualiza el muro.

> **[Insertar captura de pantalla del formulario de creación de publicación]**

### 3.3. Interacción con el Muro
El flujo principal consta de una lista de tarjetas (posteos). Cada tarjeta incluye los controles de reacción (Me gusta, No me gusta, Favoritos). Al accionar estos controles, el sistema registra la interacción internamente sin requerir la recarga de la página (comportamiento asíncrono). Los archivos multimedia anexos pueden visualizarse mediante *click* (modal Lightbox) o reproducirse in-situ.

> **[Insertar captura de pantalla de las publicaciones en el Feed Principal]**

### 3.4. Administración de Registros (Dashboard)
El autor puede gestionar su propio contenido. Accediendo al "Panel de Control" (esquina superior derecha), se visualiza el listado de publicaciones propias. Las opciones "✎ Editar" y "✕ Eliminar" permiten actualizar la base de datos o remover el contenido del servidor permanentemente.

> **[Insertar captura de pantalla del Panel de Control y sus herramientas de edición]**

---

## 4. Resumen de Actividades Realizadas

El ciclo de desarrollo de la aplicación web abarcó las siguientes etapas:

1. **Modelado y Arquitectura de Datos:** Diagramación Entidad-Relación, normalización de tablas y escritura de sentencias DDL (Data Definition Language) para MySQL.
2. **Desarrollo Backend (PHP):** Implementación del patrón MVC. Programación de Controladores para el ruteo HTTP, y clases *Repository* para la capa de acceso a datos utilizando PDO, garantizando la sanitización y el uso de sentencias preparadas contra Inyección SQL.
3. **Módulo de Seguridad:** Programación de autenticación persistente, manejo de sesiones nativas de PHP y cifrado de credenciales unidireccional empleando el algoritmo `password_hash`.
4. **Desarrollo Frontend:** Maquetación estructural mediante HTML5 semántico y diseño UI/UX mediante CSS3 (Vanilla), garantizando la adaptabilidad de la interfaz (*Responsive Web Design*).
5. **Comportamiento Asíncrono (JavaScript):** Programación de *endpoints* de backend consumidos por el cliente mediante el objeto XMLHttpRequest/Fetch, habilitando actualizaciones del DOM en tiempo real para las reacciones y componentes modales.

---

## 5. Script SQL (Base de Datos)

Se adjunta al paquete de entrega el archivo de base de datos requerido para la inicialización del sistema:
- **`privanet.sql`**: Este script contiene la totalidad de las sentencias estructurales (`CREATE TABLE`, claves foráneas, índices *Full-Text*) necesarias para levantar el esquema. Adicionalmente, incluye un *volcado* de registros iniciales (datos dummy) diseñados para agilizar el proceso de corrección y verificación funcional de la plataforma.
