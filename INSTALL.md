# Manual de Instalación

Este documento te guiará a través del proceso de instalación y configuración del proyecto.

## Requisitos

Antes de instalar el proyecto, asegúrate de tener las siguientes herramientas y versiones mínimas instaladas en tu entorno:

| Requisito                  | Versión Mínima | Comando de Verificación      |
| -------------------------- | -------------- | ---------------------------- |
| PHP                        | 8.1            | `php -v`                     |
| Composer                   | 2.1            | `composer -v`                |
| MySQL                      | 5.7            | `mysql --version`            |
| Node.js                    | 16.x           | `node -v`                    |
| npm (Node Package Manager) | 8.x            | `npm -v`                     |
| Vite                       | 4.x            | Incluido en las dependencias |

## Instalación

Sigue estos pasos para instalar y configurar el proyecto en tu entorno local:

### 1. Clonar el repositorio

Primero, clona el repositorio del proyecto desde GitHub:

```bash
git clone git@github.com:DarkAstaroth/mawawaki-web.git
cd mawawaki-web
```

### 2. Instalar dependencias de PHP con Composer

Ejecuta el siguiente comando para instalar las dependencias de Laravel y otros paquetes de PHP:

```bash
composer install
```

### 3. Instalar dependencias de Node.js

Usamos `npm` para manejar las dependencias del frontend, como Vue.js, Tailwind CSS, y Vite. Para instalarlas, ejecuta:

```bash
npm install
```

### 4. Configurar las variables de entorno

Copia el archivo .env.example y renómbralo a .env:

```bash
cp .env.example .env
```

### Variables de entorno

A continuación se detallan las principales variables de entorno que deben ser configuradas en el archivo `.env`:

| Variable                   | Descripción                                                 | Valor por defecto     |
| -------------------------- | ----------------------------------------------------------- | --------------------- |
| `APP_NAME`                 | Nombre de la aplicación                                     | Laravel               |
| `APP_ENV`                  | Entorno de la aplicación (local, production, staging, etc.) | local                 |
| `APP_KEY`                  | Llave única para la encriptación de Laravel                 |                       |
| `APP_DEBUG`                | Activa o desactiva el modo debug                            | true                  |
| `APP_URL`                  | URL base de la aplicación                                   | http://localhost      |
| `LOG_CHANNEL`              | Canal de logs utilizado                                     | stack                 |
| `LOG_DEPRECATIONS_CHANNEL` | Canal para logs de deprecaciones                            | null                  |
| `LOG_LEVEL`                | Nivel de logs (debug, info, warning, error)                 | debug                 |
| `DB_CONNECTION`            | Tipo de base de datos (mysql, sqlite, etc.)                 | mysql                 |
| `DB_HOST`                  | Dirección del servidor de la base de datos                  | 127.0.0.1             |
| `DB_PORT`                  | Puerto de conexión a la base de datos                       | 3306                  |
| `DB_DATABASE`              | Nombre de la base de datos                                  | laravel               |
| `DB_USERNAME`              | Usuario de la base de datos                                 | root                  |
| `DB_PASSWORD`              | Contraseña del usuario de la base de datos                  |                       |
| `BROADCAST_DRIVER`         | Driver para el broadcasting                                 | log                   |
| `CACHE_DRIVER`             | Driver para caché                                           | file                  |
| `FILESYSTEM_DISK`          | Sistema de archivos predeterminado                          | local                 |
| `QUEUE_CONNECTION`         | Conexión para la cola de tareas                             | sync                  |
| `SESSION_DRIVER`           | Driver para el manejo de sesiones                           | database              |
| `SESSION_LIFETIME`         | Duración de las sesiones (en minutos)                       | 120                   |
| `MAIL_MAILER`              | Controlador para envío de correos                           | smtp                  |
| `MAIL_HOST`                | Dirección del servidor de correo                            | mailpit               |
| `MAIL_PORT`                | Puerto del servidor de correo                               | 1025                  |
| `MAIL_USERNAME`            | Usuario para el servidor de correo                          | null                  |
| `MAIL_PASSWORD`            | Contraseña para el servidor de correo                       | null                  |
| `MAIL_ENCRYPTION`          | Tipo de encriptación para el correo                         | null                  |
| `MAIL_FROM_ADDRESS`        | Dirección de correo del remitente                           | hello@example.com     |
| `MAIL_FROM_NAME`           | Nombre del remitente en los correos enviados                | ${APP_NAME}           |
| `VITE_PUSHER_APP_KEY`      | Llave de la aplicación Pusher para Vite                     | ${PUSHER_APP_KEY}     |
| `VITE_PUSHER_HOST`         | Host del servidor Pusher para Vite                          | ${PUSHER_HOST}        |
| `VITE_PUSHER_PORT`         | Puerto del servidor Pusher para Vite                        | ${PUSHER_PORT}        |
| `VITE_PUSHER_SCHEME`       | Esquema de conexión de Pusher para Vite                     | ${PUSHER_SCHEME}      |
| `VITE_PUSHER_APP_CLUSTER`  | Clúster de la aplicación Pusher para Vite                   | ${PUSHER_APP_CLUSTER} |
| `GOOGLE_CLIENT_ID`         | ID del cliente de Google OAuth                              |                       |
| `GOOGLE_CLIENT_SECRET`     | Secreto del cliente de Google OAuth                         |                       |
| `VUE_APP_BASE_URL`         | URL base para las peticiones en Vue.js                      |                       |

### 5. Generar la clave de la aplicación

Genera la clave de la aplicación Laravel con el siguiente comando:

```bash
php artisan key:generate
```

### 6. Migrar la base de datos

Ejecuta las migraciones para crear las tablas necesarias en la base de datos:

```bash
php artisan migrate
```

A continuación, ejecuta los seeders para poblar la base de datos con datos iniciales:

```bash
php artisan db:seed --class=DatabaseSeeder
php artisan db:seed --class=TipoDocumentoSeeder
```

### 7. Ejecutar el servidor de desarrollo

Para iniciar el servidor de desarrollo de Laravel, ejecuta el siguiente comando:

```bash
php artisan serve
```

Este comando levantará el servidor en http://localhost:8000.

### 8. Ejecutar el entorno frontend

Para compilar los assets del frontend (Vue.js, Tailwind CSS), ejecuta Vite con el siguiente comando:

```bash
# para ambientes de desarrollo
npm run dev
```

Esto levantará el servidor de Vite, usualmente accesible en http://localhost:3000. Abre tu navegador en esta dirección para ver el proyecto en ejecución.

Para generar una versión optimizada para producción, ejecuta el siguiente comando:

```bash
# para ambientes de producción
npm run build
```

Este comando compilará y optimizará los assets para el entorno de producción, creando los archivos en la carpeta dist que podrás servir con tu servidor web.
