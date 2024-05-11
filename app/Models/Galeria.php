<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Galeria extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',  
        'product_id',
        'ruta',
        'estatus'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}


