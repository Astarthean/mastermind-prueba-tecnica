<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

## Descripción

El proyecto está construido en un entorno Windows usando WSL con Docker para contenerizar los servicios y hacer que pueda ser ejecutado en cualquier otro entorno.

### Tecnologías usadas:

-   Laravel
-   Docker
-   MySQL
-   PHPMyAdmin
-   Vite
-   TailwindCSS
-   JavaScript Vanilla
-   Axios

## Dependencias

```
composer install
npm install
```

## Levantar APP

### Levantar el servidor de back:

> 📌 **Importante:** Tener Docker ejecutándose

-   Windows

```bash
wsl

#Levantar contenedor docker en segundo plano
./vendor/bin/sail up -d

#Ejecutar las migraciones
./vendor/bin/sail artisan migrate
```

-   Linux

```bash
./vendor/bin/sail up -d
./vendor/bin/sail artisan migrate

```

> 📌 **Notas:**
>
> -   Si tienes el error: _No application encryption key has been specified_, necesitas generar una `APP_KEY` 🔑, ejecuta:
>
> ```sh
> ./vendor/bin/sail artisan key:generate
> ```
>
> -   Si tienes un alias para `sail`, úsalo en lugar del comando completo.
> -   De el `.ENV` modificar el usuario y contraseña si se desea

### Levantar el servidor de front:

```
npm run dev
```

## 🚀 Endpoints

### Frontend

-   **Aplicación principal:** [`localhost`](http://localhost)
-   **Tablero de partida:** [`localhost/tablero/{id}`](http://localhost/tablero/{id})

### Backend

-   **Listar partidas:**
    ```
    GET → localhost/api/partidas
    ```
-   **Obtener datos de una partida y sus jugadas:**
    ```
    GET → localhost/api/partidas/{id}
    ```
-   **Crear una partida:**
    ```
    POST → localhost/api/partidas
    ```
-   **Enviar una jugada a una partida:**
    ```
    POST → localhost/api/partidas/{id}
    ```
