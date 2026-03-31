
 
<?php $__env->startSection('title', 'Mesas'); ?>
 
<?php $__env->startSection('styles'); ?>
<style>
.badge-estado {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: 12px;
    font-weight: 500;
    padding: 4px 10px;
    border-radius: 99px;
    white-space: nowrap;
}
.badge-estado::before {
    content: '';
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: currentColor;
    flex-shrink: 0;
}
.estado-disponible { background: #EAF3DE; color: #3B6D11; }
.estado-ocupada    { background: #FCEBEB; color: #A32D2D; }
.estado-reservada  { background: #FAEEDA; color: #854F0B; }
.estado-inactiva   { background: #f0f0f0; color: #5a5a5a; }

.table thead th {
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: #6c757d;
    background: #f8f9fa;
    padding: 10px 16px;
    border-bottom: 1px solid #e9ecef;
}
.table tbody td {
    padding: 12px 16px;
    vertical-align: middle;
    border-color: #f0f0f0;
}
.table-hover tbody tr:hover { background-color: #fafafa; }
.table strong { font-size: 15px; }

.card {
    border: 1px solid #e9ecef;
    border-radius: 12px;
    box-shadow: 0 1px 6px rgba(0,0,0,.05);
}
.border-bottom { border-color: #e9ecef !important; }

.badge-libre-pronto {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    font-size: 11px;
    font-weight: 500;
    padding: 2px 8px;
    border-radius: 99px;
    background: #fff3cd;
    color: #7d5a00;
    margin-left: 6px;
}
</style>
<?php $__env->stopSection(); ?>
 
<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="bi bi-grid-3x3"></i> Mesas</h1>
    <div class="d-flex gap-2">
        <?php if(auth()->user()->esAdmin()): ?>
        <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalHorario">
            <i class="bi bi-clock"></i> Horario Laboral
        </button>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCrear">
            <i class="bi bi-plus-lg"></i> Nueva Mesa
        </button>
        <?php endif; ?>
    </div>
</div>


<?php if(auth()->user()->esAdmin()): ?>
<div class="alert alert-light border d-flex align-items-center gap-2 mb-3" style="font-size:13px">
    <i class="bi bi-clock text-primary"></i>
    <span>
        Horario laboral: 
        <strong><?php echo e(config('horario.apertura')); ?></strong> — 
        <strong><?php echo e(config('horario.cierre')); ?></strong>
    </span>
</div>
<?php endif; ?>
 
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Número</th>
                        <th>Capacidad</th>
                        <th>Ubicación</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $mesas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mesa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        $libreProto = false;
                        if ($mesa->estado === 'reservada' && $mesa->hora_reserva) {
                            $ahora   = now();
                            $reserva = \Carbon\Carbon::parse($mesa->hora_reserva);
                            $diff    = $ahora->diffInMinutes($reserva, false);
                            $libreProto = $diff > 0 && $diff <= 30;
                        }
                    ?>
                    <tr>
                        <td><strong>#<?php echo e($mesa->numero); ?></strong></td>
                        <td><?php echo e($mesa->capacidad); ?> personas</td>
                        <td><?php echo e($mesa->ubicacion ?: '-'); ?></td>
                        <td>
                            <span class="badge-estado estado-<?php echo e($mesa->estado); ?>">
                                <?php echo e($mesa->estado_formateado); ?>

                                <?php if($mesa->estado === 'reservada' && $mesa->hora_reserva): ?>
                                    · <?php echo e(\Carbon\Carbon::parse($mesa->hora_reserva)->format('d/m H:i')); ?>

                                <?php endif; ?>
                            </span>
                            <?php if($libreProto): ?>
                                <span class="badge-libre-pronto">
                                    <i class="bi bi-clock" style="font-size:10px"></i> libre pronto
                                </span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if(auth()->user()->esAdmin()): ?>
                            <button type="button" class="btn btn-sm btn-outline-secondary me-1"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalEstado<?php echo e($mesa->id); ?>"
                                    title="Cambiar estado">
                                <i class="bi bi-arrow-repeat"></i>
                            </button>
                            <?php endif; ?>

                            <button type="button" class="btn btn-sm btn-outline-warning me-1"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalReservar<?php echo e($mesa->id); ?>"
                                    title="Reservar mesa">
                                <i class="bi bi-calendar-check"></i>
                            </button>

                            <?php if(auth()->user()->esAdmin()): ?>
                            <button type="button" class="btn btn-sm btn-warning me-1"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalEditar<?php echo e($mesa->id); ?>"
                                    title="Editar mesa">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <form action="<?php echo e(route('admin.mesas.destroy', $mesa)); ?>" method="POST" class="d-inline"
                                onsubmit="return confirm('¿Estás seguro de eliminar esta mesa?')">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn btn-sm btn-danger" title="Eliminar">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">
                            No hay mesas registradas
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php if($mesas->hasPages()): ?>
    <div class="card-footer">
        <?php echo e($mesas->links()); ?>

    </div>
    <?php endif; ?>
</div>



<div class="modal fade" id="modalHorario" tabindex="-1">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" action="<?php echo e(route('admin.horario.update')); ?>">
                <?php echo csrf_field(); ?>
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-clock me-1"></i> Horario Laboral
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted" style="font-size:13px">
                        Define las horas en que el local opera. Las reservaciones solo se podrán hacer dentro de este rango.
                    </p>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            <i class="bi bi-sunrise me-1 text-warning"></i> Apertura
                        </label>
                        <input type="time" class="form-control" name="apertura"
                               value="<?php echo e(config('horario.apertura')); ?>" required>
                    </div>
                    <div class="mb-1">
                        <label class="form-label fw-semibold">
                            <i class="bi bi-sunset me-1 text-danger"></i> Cierre
                        </label>
                        <input type="time" class="form-control" name="cierre"
                               value="<?php echo e(config('horario.cierre')); ?>" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="bi bi-check-lg me-1"></i> Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>



<?php $__currentLoopData = $mesas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mesa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<div class="modal fade" id="modalEstado<?php echo e($mesa->id); ?>" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <form method="POST" action="<?php echo e(route('admin.mesas.estado', $mesa)); ?>">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PATCH'); ?>
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-arrow-repeat me-1"></i> Estado — Mesa #<?php echo e($mesa->numero); ?>

                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <label class="form-label fw-semibold">Estado</label>
                    <div class="d-flex flex-column gap-2">
                        <?php $__currentLoopData = [
                            'disponible' => ['label'=>'Disponible', 'icon'=>'bi-check-circle',  'color'=>'success'],
                            'ocupada'    => ['label'=>'Ocupada',    'icon'=>'bi-person-fill',    'color'=>'danger'],
                            'inactiva'   => ['label'=>'Inactiva',   'icon'=>'bi-slash-circle',   'color'=>'secondary'],
                        ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val => $cfg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <label class="d-flex align-items-center gap-2 border rounded p-2" style="cursor:pointer">
                            <input type="radio" name="estado" value="<?php echo e($val); ?>"
                                   <?php echo e($mesa->estado === $val ? 'checked' : ''); ?>>
                            <i class="bi <?php echo e($cfg['icon']); ?> text-<?php echo e($cfg['color']); ?>"></i>
                            <span><?php echo e($cfg['label']); ?></span>
                        </label>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary btn-sm">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>



<?php $__currentLoopData = $mesas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mesa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<div class="modal fade" id="modalReservar<?php echo e($mesa->id); ?>" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <form method="POST" action="<?php echo e(route('admin.mesas.estado', $mesa)); ?>"
                  onsubmit="return validarReserva(this)">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PATCH'); ?>
                <input type="hidden" name="estado" value="reservada">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-calendar-check me-1"></i> Reservar Mesa #<?php echo e($mesa->numero); ?>

                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            <i class="bi bi-calendar me-1"></i> Fecha de reserva
                        </label>
                        <input type="date"
                               class="form-control"
                               name="fecha_reserva"
                               id="fechaReserva<?php echo e($mesa->id); ?>"
                               min="<?php echo e(now()->format('Y-m-d')); ?>"
                               value="<?php echo e(now()->format('Y-m-d')); ?>"
                               required
                               onchange="actualizarMinHora(this, <?php echo e($mesa->id); ?>)">
                    </div>

                    <div class="mb-2">
                        <label class="form-label fw-semibold">
                            <i class="bi bi-clock me-1"></i> Hora de reserva
                        </label>
                        <input type="time"
                               class="form-control"
                               name="hora_reserva"
                               id="horaReserva<?php echo e($mesa->id); ?>"
                               min="<?php echo e(config('horario.apertura')); ?>"
                               max="<?php echo e(config('horario.cierre')); ?>"
                               value="<?php echo e(now()->addHour()->format('H:i')); ?>"
                               required>
                        <div class="form-text">
                            <i class="bi bi-info-circle"></i>
                            Horario: <?php echo e(config('horario.apertura')); ?> — <?php echo e(config('horario.cierre')); ?>

                        </div>
                    </div>

                    <div id="errorReserva<?php echo e($mesa->id); ?>" class="alert alert-danger py-2 mt-2 d-none" style="font-size:13px">
                        <i class="bi bi-exclamation-triangle me-1"></i>
                        <span></span>
                    </div>

                    <div class="mb-2 mt-3">
                        <label class="form-label fw-semibold">
                            <i class="bi bi-person me-1"></i> Nombre del cliente
                        </label>
                        <input type="text" class="form-control"
                               name="cliente_nombre"
                               placeholder="Nombre (opcional)">
                    </div>

                    <div class="mb-2">
                        <label class="form-label fw-semibold">
                            <i class="bi bi-telephone me-1"></i> Teléfono
                        </label>
                        <input type="tel" class="form-control"
                               name="cliente_telefono"
                               placeholder="Teléfono (opcional)"
                               inputmode="numeric"
                               maxlength="8"
                               minlength="8"
                               oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 8)">
                    </div>

                    <div class="mb-2">
                        <label class="form-label fw-semibold">
                            <i class="bi bi-chat-left-text me-1"></i> Notas
                        </label>
                        <textarea class="form-control" name="notas"
                                  rows="2"
                                  placeholder="Alguna indicación especial..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-warning btn-sm">
                        <i class="bi bi-calendar-check me-1"></i> Confirmar reserva
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>



<div class="modal fade" id="modalCrear" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="<?php echo e(route('admin.mesas.store')); ?>">
                <?php echo csrf_field(); ?>
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-plus-lg me-1"></i> Nueva Mesa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="numero" class="form-label">Número *</label>
                        <input type="number" class="form-control" id="numero" name="numero" required min="1">
                    </div>
                    <div class="mb-3">
                        <label for="capacidad" class="form-label">Capacidad (personas) *</label>
                        <input type="number" class="form-control" id="capacidad" name="capacidad" required min="1" value="4">
                    </div>
                    <div class="mb-3">
                        <label for="ubicacion" class="form-label">Ubicación</label>
                        <select class="form-select" id="ubicacion" name="ubicacion">
                            <option value="">Sin ubicación</option>
                            <?php $__currentLoopData = ['Interior','Terraza','Jardín','Bar','Privado','Salón VIP']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ub): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option><?php echo e($ub); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>



<?php $__currentLoopData = $mesas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mesa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<div class="modal fade" id="modalEditar<?php echo e($mesa->id); ?>" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="<?php echo e(route('admin.mesas.update', $mesa)); ?>">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>
                <div class="modal-header">
                    <h5 class="modal-title">Editar Mesa #<?php echo e($mesa->numero); ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="numero<?php echo e($mesa->id); ?>" class="form-label">Número *</label>
                        <input type="number" class="form-control" id="numero<?php echo e($mesa->id); ?>"
                               name="numero" value="<?php echo e($mesa->numero); ?>" required min="1">
                    </div>
                    <div class="mb-3">
                        <label for="capacidad<?php echo e($mesa->id); ?>" class="form-label">Capacidad (personas) *</label>
                        <input type="number" class="form-control" id="capacidad<?php echo e($mesa->id); ?>"
                               name="capacidad" value="<?php echo e($mesa->capacidad); ?>" required min="1">
                    </div>
                    <div class="mb-3">
                        <label for="ubicacion<?php echo e($mesa->id); ?>" class="form-label">Ubicación</label>
                        <select class="form-select" id="ubicacion<?php echo e($mesa->id); ?>" name="ubicacion">
                            <option value="">Sin ubicación</option>
                            <?php $__currentLoopData = ['Interior','Terraza','Jardín','Bar','Privado','Salón VIP']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ub): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option <?php echo e($mesa->ubicacion === $ub ? 'selected' : ''); ?>><?php echo e($ub); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Actualizar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
const HORARIO_APERTURA = '<?php echo e(config('horario.apertura')); ?>';
const HORARIO_CIERRE   = '<?php echo e(config('horario.cierre')); ?>';

function actualizarMinHora(fechaInput, mesaId) {
    const horaInput = document.getElementById('horaReserva' + mesaId);
    const hoy       = new Date().toISOString().split('T')[0];

    if (fechaInput.value === hoy) {
        const ahora = new Date();
        const hh    = String(ahora.getHours()).padStart(2, '0');
        const mm    = String(ahora.getMinutes()).padStart(2, '0');
        // El mínimo es el mayor entre ahora y la apertura
        horaInput.min = HORARIO_APERTURA;
    } else {
        horaInput.min = HORARIO_APERTURA;
    }
    horaInput.max   = HORARIO_CIERRE;
    horaInput.value = '';
}

function validarReserva(form) {
    const fechaInput = form.querySelector('input[name="fecha_reserva"]');
    const horaInput  = form.querySelector('input[name="hora_reserva"]');
    const mesaId     = horaInput.id.replace('horaReserva', '');
    const errorDiv   = document.getElementById('errorReserva' + mesaId);
    const errorSpan  = errorDiv.querySelector('span');

    const ahora  = new Date();
    const dtSel  = new Date(fechaInput.value + 'T' + horaInput.value + ':00');

    if (dtSel <= ahora) {
        errorSpan.textContent = 'No puedes reservar en una fecha u hora pasada.';
        errorDiv.classList.remove('d-none');
        return false;
    }

    // Validar que esté dentro del horario laboral
    if (horaInput.value < HORARIO_APERTURA || horaInput.value > HORARIO_CIERRE) {
        errorSpan.textContent = `La hora debe estar entre ${HORARIO_APERTURA} y ${HORARIO_CIERRE}.`;
        errorDiv.classList.remove('d-none');
        return false;
    }

    errorDiv.classList.add('d-none');
    return true;
}

document.querySelectorAll('[id^="modalReservar"]').forEach(function(modal) {
    modal.addEventListener('show.bs.modal', function() {
        const mesaId     = this.id.replace('modalReservar', '');
        const fechaInput = document.getElementById('fechaReserva' + mesaId);
        const horaInput  = document.getElementById('horaReserva' + mesaId);

        const hoy = new Date().toISOString().split('T')[0];
        fechaInput.min   = hoy;
        fechaInput.value = hoy;

        const ahora = new Date();
        ahora.setMinutes(ahora.getMinutes() + 15);
        const hh = String(ahora.getHours()).padStart(2, '0');
        const mm = String(ahora.getMinutes()).padStart(2, '0');
        const horaActual = hh + ':' + mm;

        horaInput.min   = HORARIO_APERTURA;
        horaInput.max   = HORARIO_CIERRE;
        horaInput.value = horaInput.min;
    });
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Ryzen Gaming\Documents\aaa\SISTEMA_RESTAURANTE\restaurante-laravel\resources\views/admin/mesas/index.blade.php ENDPATH**/ ?>