<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tiendas extends Model
{
    use HasFactory, SoftDeletes;


    protected $fillable = [
        'nombre',
        'estado_id',
        'municipio_id',
        'localidad_id',
        'estatus',
        'calle_numero',
        'cp',
        'external_id',
    ];

    public function Pais()
    {

        return $this->belongsTo(Pais::class)->withTrashed();
    }


    public function Estado()
    {

        return $this->belongsTo(Estados::class)->withTrashed();
    }

    public function Municipio()
    {

        return $this->belongsTo(Municipios::class)->withTrashed();
    }

    public function Localidad()
    {
        return $this->belongsTo(Localidads::class)->withTrashed();
    }
    public function Usuarios()
    {
        return $this->hasMany(User::class);
    }

    public function Existencias()
    {
        return $this->hasMany(Existencias::class);
    }

    public function getID()
    {

        return $this->id;
    }

    public function getNombre()
    {

        return $this->nombre;
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
}
