<?php
return [
    'apertura' => \App\Models\Configuracion::get('HORARIO_APERTURA', '11:00'),
    'cierre'   => \App\Models\Configuracion::get('HORARIO_CIERRE',   '22:00'),
];