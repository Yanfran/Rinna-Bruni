<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = ['pedido_id','titulo', 'mensaje', 'user_id', 'read'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function pedido()
    {
        return $this->belongsTo(Pedido::class, 'pedido_id');
    }

}
