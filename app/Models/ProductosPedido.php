<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductosPedido extends Model
{
    use HasFactory;

    protected $table = 'productos_pedidos';

    protected $fillable = [
        'pedido_id',
        'user_id',
        'product_id',
        'cantidad_solicitada',
        'cantidad_pendiente',
        'monto',
        'descuento',
        'neto',
        'precio_unitario',
        'external_id'
    ];

    public function pedido()
    {
        return $this->belongsTo(Pedidos::class, 'pedido_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
