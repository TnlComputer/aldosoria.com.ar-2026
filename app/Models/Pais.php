<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;

class Pais extends Model
{
    use HasFactory;

    /** Fila "Seleccione un pais" de la tabla: no es un pais real */
    public const SIN_PAIS = 240;

    /** Paises de donde vienen la mayoria de los alumnos: van primero en la lista */
    public const FRECUENTES = ['AR', 'ES', 'CL', 'UY', 'MX', 'PE', 'CO', 'BO', 'PY', 'BR', 'EC', 'VE'];

    /** Regla de validacion: un pais real de la tabla */
    public static function regla(): array
    {
        return ['required', 'integer', Rule::exists('pais', 'id')->whereNot('id', self::SIN_PAIS)];
    }

    /**
     * Lista para los formularios: ['frecuentes' => [...], 'resto' => [...]], sin la fila de relleno.
     *
     * @return array{frecuentes: Collection, resto: Collection}
     */
    public static function paraElegir(): array
    {
        $todos = self::where('id', '!=', self::SIN_PAIS)->orderBy('nombre')->get(['id', 'nombre', 'codigo_2']);
        $orden = array_flip(self::FRECUENTES);

        return [
            'frecuentes' => $todos->filter(fn ($p) => isset($orden[$p->codigo_2]))->sortBy(fn ($p) => $orden[$p->codigo_2])->values(),
            'resto' => $todos->reject(fn ($p) => isset($orden[$p->codigo_2]))->values(),
        ];
    }
}
