<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Localidads extends Model
{
    use HasFactory, SoftDeletes;



    protected $fillable = [
        'nombre',
        'estatus',
        'zona',
        'tipo',
        'cp'
    ];

    public function Pais()
    {
        return $this->belongsTo(Pais::class)->withTrashed();
    }

    public function Estado()
    {
        return $this->belongsTo(Estados::class)->withTrashed();
    }

    public function Municipio(){

        return $this->belongsTo(Municipios::class)->withTrashed();
    }

    public function Tiendas(){

        return $this->hasMany(Tiendas::class)->withTrashed();
    }
    public function Direcciones()
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
        return $this->estatus == 0 ? 'Inactivo' : 'Activo';
    }

    public function getEstatusValue()
    {
        return $this->estatus;
    }

    public function getCSS()
    {
        return $this->estatus == 0 ? 'badge light badge-danger' : 'badge light badge-success';
    }

    public function  getMunicipios()
    {
        $find = Municipios::where('estado_id', $this->estado_id)->get();
        if (!empty($find)) {
            return $find;
        }

        return new Municipios();

    }

}
