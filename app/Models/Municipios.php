<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Municipios extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nombre',
        'estatus',
    ];


    public function Pais()
    {
        return $this->belongsTo(Pais::class)->withTrashed();
    }

     public function Estado()
    {
        return $this->belongsTo(Estados::class)->withTrashed();
    }

    public function Localidad(){

        return $this->hasMany(Localidads::class)->withTrashed();
    }
    public function Direcciones()
    {
        return $this->hasMany(Direcciones::class);
    }

    public function Tiendas(){

        return $this->hasMany(Tiendas::class)->withTrashed();
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
