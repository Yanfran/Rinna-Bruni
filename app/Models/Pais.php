<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pais extends Model
{
    use HasFactory, SoftDeletes;


    protected $fillable = [
        'nombre',
        'cod',
        'estatus',
    ];

    public function Estados(){

        return $this->hasMany(Estados::class)->withTrashed();
    }

    public function Municipios(){

        return $this->hasMany(Municipios::class)->withTrashed();
    }

    public function Localidad(){

        return $this->hasMany(Localidads::class)->withTrashed();
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

    public function getCod()
    {

        return $this->cod;

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


    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
        return $this;
    }

    public function setEstatus($estatus)
    {
        $this->estatus = $estatus;
        return $this;
    }

     public function setCod($cod)
    {
        $this->estatus = $estatus;
        return $this;
    }


}
