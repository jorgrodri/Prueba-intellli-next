# Prueba Tecnica - Laravel 5.8 Backend API - Gestión de Libros y Autores 📚

> **🚀 API Desplegada en:** [prueba.miagentedigital-online](http://prueba.miagentedigital.online)

Esta es una API REST desarrollada con Laravel 5.8 para la gestión de libros y autores. Incluye autenticación mediante JWT (JSON Web Tokens) y funcionalidades de exportación de datos a Excel.

## 🛠️ Tecnologías y Requisitos

*   **Framework:** Laravel 5.8
*   **Lenguaje:** PHP 7.4 (con extensiones gd, zip, bcmath, pdo_sqlite)
*   **Base de Datos:** SQLite
*   **Autenticación:** JWT (Tymon JWT-Auth)
*   **Servidor:** Apache (vía Docker)

---

## 🐳 Instalación con Docker

Si deseas correr este proyecto localmente usando Docker:

1.  **Clonar el repositorio:**

    ```bash
    git clone https://github.com/jorgrodri/Prueba-intellli-next.git
    cd Prueba-intellli-next
    ```

2.  **Construir y levantar el contenedor:**

    ```bash
    docker build -t prueba-backend .
    docker run -d -p 8000:80 --name backend-container prueba-backend
    ```

3.  **Configuración inicial (dentro del contenedor):**

    **Configuración de la Base de Datos (Crucial):** Dado que se utiliza SQLite, es necesario inicializar el archivo de la base de datos y otorgar los permisos correctos para evitar errores de escritura (Error 500).

    Ejecuta estos comandos dentro del contenedor:

    ```bash
    # Entrar al contenedor
    docker exec -it backend-container bash
    
    # 1. Crear el archivo físico de la base de datos
    touch database/database.sqlite
    
    # 2. Asignar permisos de escritura a las carpetas clave
    chmod -R 777 database storage bootstrap/cache
    
    # 3. Ejecutar las migraciones
    php artisan migrate --force
    ```

4.  **Configuración de Variables de Entorno (.env):**

    ```env
    APP CONFIG
    APP_NAME="Backend Prueba"
    APP_ENV=local
    APP_KEY=base64:QuTibrPOHWnD7slsJO2uXkL3xqxP6dSkVDxzvaRtpq4=

    DATABASE CONFIG (SQLite)
    DB_CONNECTION=sqlite
    # Ruta absoluta necesaria para el contenedor Docker
    DB_DATABASE=/var/www/html/database/database.sqlite

    JWT CONFIG
    # Secret usado para firmar los tokens
    JWT_SECRET=comiPt9vOmSzzwiI0cBrKWI5HNlchvzANtL5Ahk4CJoFDI3cJfOLnBHmrYLoHxAE
    ```

---

## 💻 Instalación Local (Sin Docker)

Sigue estos pasos para correr la API directamente en tu sistema operativo:

### Requisitos del Sistema
Asegúrate de tener instalados los siguientes componentes:
*   **PHP:** Versión 7.4 (Recomendada) u 8.x.
*   **Extensiones PHP obligatorias:** `php-sqlite3`, `php-zip`, `php-gd`, `php-mbstring`, `php-xml`.
*   **Composer:** [Descargar aquí](https://getcomposer.org/).

### Preparación del Proyecto

1.  **Clonar el repositorio:**

    ```bash
    git clone https://github.com/jorgrodri/Prueba-intellli-next.git
    cd Prueba-intellli-next
    ```

2.  **Instalar dependencias de PHP:**

    ```bash
    composer install
    ```

3.  **Crear el archivo de configuración:**

    ```bash
    cp .env.example .env
    ```

4.  **Configuración del archivo .env:**
    Abre el archivo `.env` y edita las siguientes líneas para usar SQLite localmente:

    ```env
    DB_CONNECTION=sqlite
    
    # En Windows/Mac local, Laravel buscará por defecto el archivo en la carpeta database
    # Puedes dejar DB_DATABASE vacío o poner la ruta absoluta
    DB_DATABASE=C:\ruta\al\proyecto\database\database.sqlite
    ```

5.  **Inicialización de la Aplicación:**
    Ejecuta estos comandos en tu terminal para preparar la base de datos y las llaves de seguridad:

    ```bash
    # 1. Crear el archivo físico de SQLite (si no existe)
    # En Windows (PowerShell): 
    New-Item database/database.sqlite
    # En Linux/Mac: 
    touch database/database.sqlite
    
    # 2. Generar llaves de seguridad
    php artisan key:generate
    php artisan jwt:secret
    
    # 3. Ejecutar las tablas
    php artisan migrate --force
    ```

6.  **Iniciar el Servidor:**
    Laravel incluye un servidor de desarrollo integrado:

    ```bash
    php artisan serve
    ```
    La API estará disponible en: [http://127.0.0.1:8000](http://127.0.0.1:8000)

---

## 🔐 Autenticación (Públicos)

### Registrar Usuario
*   **Endpoint:** `POST /api/auth/register`
*   **Body:**
    ```json
    {
      "name": "Simón Bolívar",
      "email": "libertador@ejemplo.com",
      "password": "123456",
      "password_confirmation": "123456"
    }
    ```

### Login
*   **Endpoint:** `POST /api/auth/login`
*   **Body:**
    ```json
    {
      "email": "libertador@ejemplo.com",
      "password": "123456"
    }
    ```

### Perfil (Me)
*   **Endpoint:** `POST /api/auth/me`
*   **Requisitos:** Requiere Token

### Logout
*   **Endpoint:** `POST /api/auth/logout`
*   **Requisitos:** Requiere Token

---

## ✍️ Autores (Requieren Token)

### Listar Autores
*   **Endpoint:** `GET /api/authors`

### Crear Autor
*   **Endpoint:** `POST /api/authors`
*   **Body:**
    ```json
    {
      "name": "Simón",
      "last_name": "Bolívar"
    }
    ```

### Ver Autor
*   **Endpoint:** `GET /api/authors/1`

### Editar Autor
*   **Endpoint:** `PUT /api/authors/1`
*   **Body:**
    ```json
    {
      "name": "Simón José Antonio"
    }
    ```

### Eliminar Autor
*   **Endpoint:** `DELETE /api/authors/1`

---

## 📖 Libros (Requieren Token)

### Listar Libros
*   **Endpoint:** `GET /api/books`

### Crear Libro
*   **Endpoint:** `POST /api/books`
*   **Body:**
    ```json
    {
      "title": "Manifiesto de Cartagena",
      "description": "Escrito político",
      "publish_date": "1812-12-15",
      "author_id": 1
    }
    ```

### Editar Libro
*   **Endpoint:** `PUT /api/books/1`
*   **Body:**
    ```json
    {
      "title": "Discurso de Angostura"
    }
    ```

### Eliminar Libro
*   **Endpoint:** `DELETE /api/books/1`

---

## 👥 Usuarios CRUD (Requieren Token)

### Listar Usuarios
*   **Endpoint:** `GET /api/users`

### Ver Usuario
*   **Endpoint:** `GET /api/users/1`

### Editar Usuario
*   **Endpoint:** `PUT /api/users/1`
*   **Body:**
    ```json
    {
      "name": "Simón Actualizado",
      "email": "bolivar_nuevo@ejemplo.com"
    }
    ```

### Eliminar Usuario
*   **Endpoint:** `DELETE /api/users/1`

---

## 📊 Reportes (Requiere Token)

### Exportar Excel
*   **Endpoint:** `GET /api/export/library`
*   **Descripción:** Descarga directa del archivo .xlsx

---

## 💡 Recordatorio de Headers

Para todas las rutas que no sean **Registro** o **Login**, debes incluir en tu cliente (Postman) los siguientes headers:

*   **Authorization:** `Bearer TU_TOKEN_AQUI`
*   **Accept:** `application/json`

---

## 🧪 Ejecutar Pruebas (Tests)

Este proyecto incluye pruebas automatizadas (Feature y Unit tests) para asegurar el funcionamiento correcto de la API.

### Para ejecutar las pruebas:

1.  **Asegúrate de estar en la raíz del proyecto.**
2.  **Ejecuta el siguiente comando:**

    ```bash
    ./vendor/bin/phpunit
    ```

    O si tienes `phpunit` instalado globalmente:

    ```bash
    phpunit
    ```

### Estructura de Pruebas:
*   **Feature Tests:** Pruebas de integración para los endpoints de la API (Autores, Libros, Autenticación).
*   **Unit Tests:** Pruebas unitarias para lógica específica.
