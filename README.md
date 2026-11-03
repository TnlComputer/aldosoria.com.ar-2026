# aldosoria.com.ar — 2026

Sitio de **Soria Perfomance**, taller de electromecánica automotor: cursos online, chiptuning, mecánica, electrónica, representaciones y novedades.

Laravel 12 · PHP 8.2+ · Livewire 3 · Tailwind · MySQL

## Funciones

- **Cursos online**: catálogo, detalle y compra con MercadoPago o PayPal. Cada alumno ve solo los cursos que compró, con sus videos y archivos descargables.
- **Chiptuning**: listado de vehículos por marca con las mejoras de potencia y torque.
- **Páginas del taller**: mecánica, electrónica, representaciones, mantenimiento programado, novedades y ofertas.
- **Contacto** por formulario con aviso por correo.
- **Panel de administración**: cursos y temarios, pagos, usuarios, monedas, chiptuning, marcas, novedades y ofertas.
- Sitio adaptado a escritorio y celular, textos en español.

## Desarrollo local

```bash
composer install
npm install && npm run build
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

Para tener datos de prueba, crear `database/seeders/UsersTableSeeder.php` localmente (no se sube al repositorio); `DatabaseSeeder` lo ejecuta si existe.

Pruebas:

```bash
php vendor/bin/phpunit
```

## Configuración

Todas las claves (base de datos, correo, MercadoPago, PayPal, Turnstile) van en `.env`, que nunca se sube al repositorio. `.env.example` lista las variables necesarias sin valores reales.

## Seguridad

- Los pagos se verifican siempre contra la API de MercadoPago / PayPal; el precio sale de la base, nunca del navegador.
- El panel de administración exige rol de administrador.
- Login, registro, recuperación de clave, contacto y pagos tienen límite de intentos y protección contra bots.

Si encontrás un problema de seguridad, escribí a <info@aldosoria.com.ar> en lugar de abrir un issue público.

## Derechos

© Soria Perfomance. Todos los derechos reservados. El código se publica para consulta; los textos, imágenes, cursos y marcas pertenecen a sus respectivos dueños y no pueden reutilizarse sin autorización. Ver [LICENSE](LICENSE).
