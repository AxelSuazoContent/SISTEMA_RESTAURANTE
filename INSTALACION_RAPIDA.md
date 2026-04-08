# 🚀 Guía de Instalación Rápida

## Requisitos previos
- XAMPP (PHP 8.2+, MySQL 10.5+)
- Composer
- Node.js (opcional, solo si modificas assets)

---

## Instalación en 6 pasos

### 1. Colocar el proyecto
```bash
# Copiar la carpeta restaurante-laravel dentro de:
C:\xampp\htdocs\restaurante-laravel
```

### 2. Instalar dependencias
```bash
cd C:\xampp\htdocs\restaurante-laravel
composer install
```

### 3. Configurar el entorno
```bash
# Copiar archivo de configuración
copy .env.example .env

# Editar .env con tus datos:
APP_NAME="Sistema Restaurante"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost

DB_DATABASE=restaurante
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Crear base de datos y tablas
```bash
# 1. Abrir phpMyAdmin (http://localhost/phpmyadmin)
# 2. Crear base de datos con nombre: restaurante
# 3. Volver a la terminal y ejecutar:

php artisan key:generate
php artisan migrate --seed
php artisan storage:link
```

### 5. Verificar permisos de storage
```bash
# En Windows esto no es necesario
# En Linux/Mac ejecutar:
chmod -R 775 storage bootstrap/cache
```

### 6. Iniciar servidor
```bash
php artisan serve
```

Abrir navegador: **http://127.0.0.1:8000**

---

## 🔑 Acceso inicial

> ⚠️ **IMPORTANTE:** Cambia estas credenciales inmediatamente después de entrar por primera vez.

| Email | Contraseña | Rol |
|-------|------------|-----|
| admin@restaurante.com | Admin2024# | Administrador |

### Primeros pasos al ingresar:
1. Entra con el usuario admin temporal
2. Ve a **Usuarios** → crea tu usuario real con datos seguros
3. Cierra sesión e inicia con tu nuevo usuario
4. Elimina el usuario `admin@restaurante.com`
5. Crea los usuarios de recepcionista y cocina que necesites

---

## ⚙️ Configuración inicial del sistema

Antes de usar el sistema completa estos pasos en el panel admin:

1. **Config. Factura** → ingresa nombre del negocio, RTN, dirección, teléfono, CAI y rangos de facturación
2. **Categorías** → crea las categorías de tu menú
3. **Productos** → agrega tus productos con precios e imágenes
4. **Mesas** → registra las mesas de tu restaurante
5. **Cierre de Caja** → abre la caja antes de comenzar operaciones

---

## 🔄 Flujo de operación diario

```
Admin abre caja → Recepcionista toma pedidos → Cocina prepara → Recepcionista cobra → Admin cierra caja
```

### Para el Administrador
- Abrir caja al inicio del día con el monto inicial
- Gestionar usuarios, productos, categorías y mesas
- Ver reportes de ventas y facturas
- Cerrar caja al final del día

### Para la Recepcionista (POS)
1. Seleccionar tipo de pedido: **Mesa / Para llevar / Domicilio**
2. Seleccionar mesa o ingresar datos del cliente
3. Agregar productos al ticket
4. Enviar pedido a cocina
5. Cobrar cuando el pedido esté listo
6. El sistema genera la factura automáticamente

### Para Cocina
1. Ver pedidos pendientes en tiempo real
2. Clic en **"Iniciar"** para comenzar preparación
3. Clic en **"Listo"** cuando el ítem esté terminado
4. El sistema notifica automáticamente cuando llegan pedidos nuevos

---

## 🌐 Landing Page de Reservaciones

Los clientes pueden hacer reservaciones en línea desde:
```
http://127.0.0.1:8000/reservar
```

Las reservaciones aparecen automáticamente en el POS con un badge amarillo sobre la mesa correspondiente.

---

## ⚠️ Solución de problemas comunes

### "composer no se reconoce"
Descargar e instalar Composer desde: https://getcomposer.org/download/
Reiniciar la terminal después de instalar.

### "Access denied for user"
- Verificar que XAMPP/MySQL esté corriendo
- Revisar usuario y contraseña en el archivo `.env`
- Asegurarse de que la base de datos `restaurante` exista en phpMyAdmin

### "No such file or directory (storage)"
```bash
php artisan storage:link
```

### "Las imágenes no se ven"
```bash
php artisan storage:link
```

### Página en blanco o error 500
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

### "Class not found" después de agregar archivos
```bash
composer dump-autoload
```

---

## 📁 Estructura de carpetas importante

```
restaurante-laravel/
├── app/
│   ├── Http/Controllers/    # Controladores del sistema
│   └── Models/              # Modelos de base de datos
├── database/
│   ├── migrations/          # Estructura de la base de datos
│   └── seeders/             # Datos iniciales
├── resources/views/         # Vistas HTML (Blade)
│   ├── admin/               # Vistas del panel admin
│   ├── cocina/              # Vista de cocina
│   ├── landing/             # Landing page pública
│   └── pos/                 # Vista del punto de venta
├── routes/
│   └── web.php              # Rutas de la aplicación
├── storage/app/public/      # Imágenes subidas
└── .env                     # Configuración del entorno
```

---

## 🔒 Roles y permisos

| Rol | POS | Cocina | Admin | Facturas |
|-----|-----|--------|-------|----------|
| Administrador | ✅ | ❌ | ✅ | ✅ |
| Recepcionista | ✅ | ❌ | ❌ | ✅ Solo imprimir |
| Cocina | ❌ | ✅ | ❌ | ❌ |

---

## 🆘 Comandos útiles de mantenimiento

```bash
# Limpiar toda la caché
php artisan optimize:clear

# Optimizar para producción
php artisan optimize

# Ver todas las rutas registradas
php artisan route:list

# Rehacer migraciones desde cero (⚠️ borra todos los datos)
php artisan migrate:fresh --seed
```