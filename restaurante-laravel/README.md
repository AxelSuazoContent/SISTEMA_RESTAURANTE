# 🍽️ Sistema de Gestión de Restaurante

Sistema completo para la gestión de restaurantes desarrollado con **Laravel 10** y **PHP 8+**. Incluye módulos de Punto de Venta (POS), Vista de Cocina, Facturación SAR (Honduras) y Panel de Administración.

## 📋 Características

### 🖥️ Sistema POS (Punto de Venta)
- Toma de pedidos en mesa, para llevar o a domicilio
- Gestión visual de mesas con estados en tiempo real
- Búsqueda rápida de productos por categoría
- Ticket de venta con edición de cantidades y notas
- Proceso de cobro con múltiples métodos de pago
- Generación automática de facturas al cobrar

### 👨‍🍳 Vista de Cocina
- Visualización de pedidos pendientes en tiempo real
- Control de estados: Pendiente → En preparación → Listo
- Tiempo estimado de preparación por producto
- Alertas visuales para pedidos urgentes
- Actualización automática cada 10 segundos
- Sonido de notificación para nuevos pedidos

### 📊 Panel de Administración
- Dashboard con estadísticas del día
- Gestión de usuarios (Admin, Recepcionista, Cocina)
- Gestión de categorías y productos con imágenes
- Gestión de mesas con estados y reservas por fecha/hora
- Reporte de ventas con gráficas (por día, método de pago, top productos)
- Facturas con formato SAR (Honduras) — CAI, rango autorizado, ISV 15%
- Cierre de caja con apertura, conteo y diferencia

### 🧾 Facturación SAR Honduras
- Generación automática de facturas al cobrar
- Número correlativo (FAC-YYYY-NNNNN)
- Desglose de ISV 15% (precio ya incluye impuesto)
- CAI, rango autorizado y fecha límite de emisión configurables
- Vista de impresión tipo ticket

### 💰 Cierre de Caja
- Apertura de caja con monto inicial
- Cálculo automático de lo que debería haber en caja
- Cierre con conteo físico y cálculo de diferencia (sobrante/faltante)
- Botón para cerrar operaciones (poner todas las mesas inactivas)

---

## 🚀 Requisitos

- PHP >= 8.1
- Composer >= 2.0
- MySQL >= 5.7 o MariaDB >= 10.3
- Node.js >= 18.0 (opcional)

---

## ⚙️ Instalación paso a paso

### 1. Instalar dependencias

```bash
cd restaurante-laravel
composer install
```

### 2. Configurar el entorno

```bash
cp .env.example .env
```

Edita `.env` con tus datos:

```env
APP_NAME="Sistema Restaurante"
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=restaurante
DB_USERNAME=root
DB_PASSWORD=tu_password
```

### 3. Generar clave de la aplicación

```bash
php artisan key:generate
```

### 4. Crear la base de datos

En tu gestor MySQL (phpMyAdmin, TablePlus, etc.) crea una base de datos:

```sql
CREATE DATABASE restaurante CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 5. Ejecutar migraciones y seeders

```bash
php artisan migrate --seed
```

Esto crea todas las tablas incluyendo:
- `users`, `categorias`, `productos`, `mesas`, `pedidos`, `detalles_pedido`
- `pagos`, `facturas`, `config_factura`, `aperturas_caja`

> ⚠️ Si al migrar ves el error `Disk [public] does not have a configured driver`, asegúrate de que el archivo `config/filesystems.php` existe en tu proyecto.

### 6. Crear enlace simbólico para imágenes

```bash
php artisan storage:link
```

### 7. Limpiar caché de configuración

```bash
php artisan config:clear
php artisan cache:clear
```

### 8. Iniciar el servidor

```bash
php artisan serve
```

Visita: **http://127.0.0.1:8000**

---

## 👤 Usuarios de Prueba

| Rol | Email | Contraseña |
|-----|-------|------------|
| Administrador | admin@restaurante.com | password |
| Recepcionista | recepcion@restaurante.com | password |
| Cocina | cocina@restaurante.com | password |

---

## 📁 Estructura del Proyecto

```
restaurante-laravel/
├── app/
│   ├── Http/Controllers/
│   │   ├── AdminController.php      # Panel admin, reportes, facturas, caja
│   │   ├── AuthController.php       # Autenticación
│   │   ├── CocinaController.php     # Vista de cocina
│   │   └── POSController.php        # Punto de venta
│   └── Models/
│       ├── AperturaCaja.php         # Control de caja
│       ├── Categoria.php
│       ├── ConfigFactura.php        # Configuración SAR
│       ├── DetallePedido.php
│       ├── Factura.php
│       ├── Mesa.php
│       ├── Pago.php
│       ├── Pedido.php
│       ├── Producto.php
│       └── User.php
├── config/
│   └── filesystems.php              # ⚠️ Necesario para imágenes
├── database/
│   ├── migrations/
│   └── seeders/
├── resources/views/
│   ├── admin/
│   │   ├── cierre-caja.blade.php
│   │   ├── config-factura.blade.php
│   │   ├── dashboard.blade.php
│   │   ├── facturas/
│   │   │   ├── index.blade.php
│   │   │   └── imprimir.blade.php
│   │   ├── mesas/
│   │   ├── productos/
│   │   ├── reportes/
│   │   └── usuarios/
│   ├── auth/
│   ├── cocina/
│   ├── layouts/
│   │   ├── app.blade.php
│   │   └── sidebar.blade.php
│   └── pos/
└── routes/
    └── web.php
```

---

## 🔐 Roles y Permisos

| Módulo                 | Admin | Recepcionista | Cocina |
| ---------------------- | :---: | :-----------: | :----: |
| Dashboard              |   ✅   |       ❌       |    ❌   |
| Usuarios               |   ✅   |       ❌       |    ❌   |
| Categorías / Productos |   ✅   |       ❌       |    ❌   |
| Mesas                  |   ✅   |       ✅       |    ❌   |
| Reportes               |   ✅   |       ❌       |    ❌   |
| Facturas               |   ✅   |       ❌       |    ❌   |
| Configuración Factura  |   ✅   |       ❌       |    ❌   |
| Cierre de Caja         |   ✅   |       ❌       |    ❌   |
| POS                    |   ✅   |       ✅       |    ❌   |
| Cocina                 |   ✅   |       ❌       |    ✅   |


---

## 🔄 Estados del Sistema

### Mesas
| Estado | Descripción |
|--------|-------------|
| Disponible | Lista para nuevos clientes |
| Ocupada | Con pedido activo |
| Reservada | Reservada con fecha y hora |
| Inactiva | Fuera de servicio temporalmente |

### Pedidos
| Estado | Descripción |
|--------|-------------|
| Pendiente | Recién creado |
| Preparando | En cocina |
| Listo | Listo para entregar |
| Entregado | Entregado al cliente |
| Pagado | Completado y cobrado |
| Cancelado | Cancelado |

---

## 💰 Métodos de Pago
- 💵 Efectivo (con cálculo de cambio)
- 💳 Tarjeta
- 🏦 Transferencia
- 📋 Otro

---

## 🛠️ Comandos Útiles

```bash
# Limpiar caché
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear

# Reiniciar base de datos (⚠️ elimina todos los datos)
php artisan migrate:fresh --seed

# Ver todas las rutas
php artisan route:list

# Crear usuario admin desde consola
php artisan tinker
>>> \App\Models\User::create(['nombre' => 'Admin', 'email' => 'admin@nuevo.com', 'password' => bcrypt('password'), 'rol' => 'admin'])

# Agregar columna manualmente si falla la migración
php artisan tinker
>>> DB::statement('ALTER TABLE mesas ADD COLUMN hora_reserva TIME NULL AFTER estado');
```

---

## 🐛 Solución de Problemas Comunes

### "Disk [public] does not have a configured driver"
El archivo `config/filesystems.php` no existe. Créalo con el contenido estándar de Laravel que incluye el disco `public`.

### "Column not found: hora_reserva"
La migración corrió pero no aplicó. Ejecuta en tinker:
```bash
php artisan tinker
>>> DB::statement('ALTER TABLE mesas ADD COLUMN hora_reserva TIME NULL AFTER estado');
```

### Las imágenes no cargan
```bash
php artisan storage:link
```

### "Route not defined"
```bash
php artisan route:clear
php artisan cache:clear
```

### Error de permisos en storage (Linux/Mac)
```bash
chmod -R 755 storage
chmod -R 755 bootstrap/cache
```
### Optimizar antes de subir a producción
php artisan optimize
php artisan storage:link

### Las gráficas no aparecen
El layout usa `@yield('scripts')` — asegúrate de usar `@section('scripts')` en las vistas, no `@push('scripts')`.

---

## 📝 Notas Importantes

- Los precios de productos **ya incluyen el ISV 15%** — la factura hace el desglose interno
- La factura SAR se genera automáticamente al procesar un pago
- El cierre de caja **se resetea cada día** automáticamente
- Las reservas de mesas no permiten fechas u horas pasadas
- Las imágenes de productos se guardan en `storage/app/public/productos/`

---

## 📄 Licencia

Este proyecto es de uso libre para fines comerciales o personales.

---

**Desarrollado con ❤️ usando Laravel 10**