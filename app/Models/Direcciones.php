<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Direcciones extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'alias',
        'estado_id',
        'municipio_id',
        'localidad_id',
        'colonia',
        'calle',
        'user_id',
        'tipo',
        'nombre_encargado',
        'celular',
        'telefono_fijo',
        'estatus',
        'cp',
        'apellido_paterno',
        'apellido_materno',
        'correo'
    ];

    public function Estado()
    {
        return $this->belongsTo(Estados::class);
    }

    public function Municipio()
    {
        return $this->belongsTo(Municipios::class);
    }

    public function Localidad()
    {
        return $this->belongsTo(Localidads::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Getters y Setters

    public function getAliasAttribute($value)
    {
        return ucfirst($value);
    }

    public function setAliasAttribute($value)
    {
        $this->attributes['alias'] = strtolower($value);
    }

    

    public function getEstatus()
    {
        if ($this->estatus == 0) {
            return 'Inactivo';
        }
        return 'Activo';
    }

    public function getEstatusValue()
    {

        return $this->estatus;
    }

    public function getCSS()
    {
        if ($this->estatus == 0) {
            return 'badge light badge-danger';
        }
        return 'badge light badge-success';
    }

    // Agrega aquí los getters y setters para los demás campos de la tabla
}
