<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Estados extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'id',
        'idEstado',
        'nombre',
        'cod',
        'estatus',
    ];


    public function Pais()
    {
        return $this->belongsTo(Pais::class)->withTrashed();
    }

     public function Municipios()
    {
        return $this->hasMany(Municipios::class)->withTrashed();
    }

    public function getTiendas(){

        return $this->hasMany(Tiendas::class)->withTrashed();
    }


    public function Localidad(){

        return $this->hasMany(Localidads::class)->withTrashed();
    }

    public function direcciones()
    {
        return $this->hasMany(Direcciones::class);
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


    public function ListaPais()
    {

       return  Pais::where('estatus', 1)->get();

    }
     public function ListaMunicipios()
    {

            return  Municipios::where('estatus', 1)->get();

    }

     public function getPaisNombre($id){

          return  Pais::withTrashed()->where('id', $id)->first()->getNombre();
    }

     public function getMunicipiosNombre($id){

          return Municipios::withTrashed()->where('id', $id)->first()->getNombre();
    }

    public function getMunicipios($id)
    {

        $find = Municipios::withTrashed()->where('pais_id', $id)->where('estatus', 1)->get();
        return $find;

    }



}
