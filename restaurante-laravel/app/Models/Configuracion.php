<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class Configuracion extends Model {
    protected $fillable = ['clave', 'valor'];

    public static function get(string $clave, string $default = ''): string {
        try {
            return self::where('clave', $clave)->value('valor') ?? $default;
        } catch (\Exception $e) {
            return $default;
        }
    }

    public static function set(string $clave, string $valor): void {
        try {
            self::updateOrCreate(['clave' => $clave], ['valor' => $valor]);
        } catch (\Exception $e) {
            // tabla aún no existe
        }
    }
}