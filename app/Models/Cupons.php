<?php

namespace App\Models;

use Carbon\Carbon;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cupons extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nombre',
        'codigo',
        'monto',
        'porcentaje',
        'cantidad_aplicacion',
        'tipo',
        'estatus',
        'fecha_inicio',
        'fecha_fin',
        'user_id',
        'cantidad_usos'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
        'fecha_inicio',
        'fecha_fin',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getTipoCuponAttribute()
    {
        if ($this->tipo === 1) {
            return 'Cupón de dinero';
        } elseif ($this->tipo === 2) {
            return 'Cupón de porcentaje aplicable';
        } elseif ($this->tipo === 3) {
            return 'Vale a favor';
        } else {
            return 'Tipo de cupón desconocido';
        }
    }
    public function getEstatus()
    {
        if ($this->estatus == 1) {
            return 'Activo';
        }
        return 'Inactivo';
    }

    public function getEstatusValue()
    {

        return $this->estatus;
    }

    public function getCSS()
    {
        if ($this->estatus == 1) {
            return 'badge light badge-success';
        }
        return 'badge light badge-success';
    }

    public function getFechaInicioAttribute($value)
    {
        return Carbon::parse($value);
    }

    public function getFechaFinAttribute($value)
    {
        return Carbon::parse($value);
    }
}
