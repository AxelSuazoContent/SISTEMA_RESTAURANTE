# 🚀 Guía de Instalación Rápida

## Instalación en 5 pasos

### 1. Descargar y preparar
```bash
cd C:\xampp\htdocs
# Copiar la carpeta restaurante-laravel
```

### 2. Instalar dependencias
```bash
cd restaurante-laravel
composer install
```

### 3. Configurar base de datos
```bash
# Copiar archivo de configuración
copy .env.example .env

# Editar .env con tus datos:
DB_DATABASE=restaurante
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Crear base de datos y tablas
```bash
# Crear base de datos en phpMyAdmin (nombre: restaurante)

# Ejecutar migraciones
php artisan migrate --seed

# Generar clave
php artisan key:generate

# Crear enlace de storage
php artisan storage:link
```

### 5. Iniciar servidor
```bash
php artisan serve
```

Abrir navegador: **http://127.0.0.1:8000**

---

## 🔑 Accesos

| Usuario | Contraseña | Rol |
|---------|------------|-----|
| admin@restaurante.com | password | Administrador |
| recepcion@restaurante.com | password | Recepcionista |
| cocina@restaurante.com | password | Cocina |

---

## ⚠️ Solución de problemas comunes

### "composer no se reconoce"
- Descargar Composer de: https://getcomposer.org/download/
- Reiniciar la terminal después de instalar

### "Access denied for user"
- Verificar que XAMPP/MySQL esté corriendo
- Revisar usuario y contraseña en archivo .env
- Crear la base de datos manualmente en phpMyAdmin

### "No such file or directory (storage)"
```bash
php artisan storage:link
```

### Página en blanco
```bash
php artisan cache:clear
php artisan config:clear
```

---

## 📁 Estructura de carpetas importante

```
restaurante-laravel/
├── app/Models/          # Modelos de base de datos
├── app/Http/Controllers/# Controladores
├── database/seeders/    # Datos de prueba
├── resources/views/     # Vistas HTML
└── routes/web.php       # Rutas de la aplicación
```

---

## 🎯 Uso del sistema

### Para Recepcionista (POS)
1. Iniciar sesión con: recepcion@restaurante.com
2. Seleccionar tipo de pedido (Mesa / Para llevar / Domicilio)
3. Seleccionar mesa o ingresar datos del cliente
4. Agregar productos al ticket
5. Enviar pedido a cocina
6. Cobrar cuando el pedido esté listo

### Para Cocina
1. Iniciar sesión con: cocina@restaurante.com
2. Ver pedidos pendientes en tiempo real
3. Click en "Iniciar" para comenzar preparación
4. Click en "Listo" cuando el item esté terminado
5. El sistema notifica cuando hay pedidos nuevos

### Para Administrador
1. Iniciar sesión con: admin@restaurante.com
2. Acceso completo a:
   - Dashboard con estadísticas
   - Gestión de usuarios
   - Gestión de productos y categorías
   - Gestión de mesas
   - Reportes de ventas
   - POS y Cocina

---

## 🆘 Ayuda adicional

Si tienes problemas, revisa el archivo **README.md** completo o contacta al administrador del sistema.
