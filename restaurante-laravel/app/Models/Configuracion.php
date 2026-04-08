<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Configuracion extends Model {
    protected $fillable = ['clave', 'valor'];

    public static function get(string $clave, string $default = ''): string {
        return self::where('clave', $clave)->value('valor') ?? $default;
    }

    public static function set(string $clave, string $valor): void {
        self::updateOrCreate(['clave' => $clave], ['valor' => $valor]);
    }
}