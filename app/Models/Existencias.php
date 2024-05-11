<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Existencias extends Model
{
    use HasFactory, SoftDeletes;


    protected $fillable = [
        'id',
        'product_id',
        'tienda_id',
        'cantidad'
    ];

    public function Tiendas()
    {
        return $this->belongsTo(Tiendas::class)->withTrashed();
    }


    public function getProduct()
    {

        return Product::where('id', $this->product_id)
            ->first();
    }

    public function getSuma()
    {
        return $this->where('product_id', $this->product_id)
            ->sum('cantidad');
    }

    public function getID()
    {
        return $this->where('product_id', $this->product_id)->first();
    }

    public function getTienda($id)
    {
        return Tiendas::where('id', $id)->first();
    }
}
