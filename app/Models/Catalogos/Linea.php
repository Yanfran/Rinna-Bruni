<?php

namespace App\Models\Catalogos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class Linea extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'nombre',
        'estatus',
        'external_id',
    ];

    public function products()
    {
        $this->hasMany(Product::class);
    }


    public function getEstatusValue()
    {
        return $this->estatus;
    }


    public function getEstatus()
    {
        if ($this->estatus == 0) {
            return 'Inactivo';
        }
        return 'Activo';
    }

    public function getCSS()
    {
        if ($this->estatus == 0) {
            return 'badge light badge-danger';
        }
        return 'badge light badge-success';
    }

}
