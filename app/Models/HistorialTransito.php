<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class HistorialTransito extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'historial_transito';

      /**
     * Los atributos que se pueden asignar masivamente.
     *
     * @var array
     */
    protected $fillable = [
        'placas',
        'recibe',
        'fecha_de_entrega',
        'quien_entrega',
        'tramite',
        'observaciones',
        'fecha_de_archivo',
        'archivo',
        'fecha_de_importacion',
    ];

      /**
     * Los atributos que deberían ser tratados como fechas.
     *
     * @var array
     */
    protected $dates = [
        'fecha_de_entrega',
        'fecha_de_archivo',
        'fecha_de_importacion',
        'deleted_at',
    ];

}
