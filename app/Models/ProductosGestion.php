<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductosGestion extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'productos_gestion';
    protected $fillable = ['product_id', 'user_id', 'tienda_id', 'pedido_id', 'cantidad'];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function tienda()
    {
        return $this->belongsTo(Tiendas::class, 'tienda_id');
    }

    public function pedido()
    {
        return $this->belongsTo(Pedidos::class, 'pedido_id')->withTrashed();
    }
}
