<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pedidos extends Model
{
    use HasFactory, SoftDeletes;
    // estaus 0 = abierto
    // estatus 1 = en revision
    // estatus 3 = pendiente de pago
    // estatus 4 = pagado
    // estatus 5 = enviado

    // estatus_pago 1 = pago en proceso
    // estatus_pago 2 = pago exitoso
    // estatus_pago 3 = pago fallido

    protected $fillable = [
        'id',
        'metodo_pago',
        'key_pago',
        'monto_total',
        'distribuidor_id',
        'vendedor_id',
        'creado_por',
        'estatus',
        'estatus_pago',
        'estatus_envio',
        'tipo_envio',
        'observacion',
        'vale',
        'cupon',
        'direccion_cliente',
        'total_cajas',
        'aceptar_terminos',
        'monto_cupon',
        'monto_vale',
        'monto_paqueteria',
        'monto_descuento_cliente',
        'mercadopago_id',
        'referencia_mercadopago',
        'external_folio',
        'external_transaccion_id'
    ];

    public function productosPedidos()
    {
        return $this->hasMany(ProductosPedido::class, 'pedido_id');
    }

    public function distribuidor()
    {
        return $this->belongsTo(User::class, 'distribuidor_id');
    }
    public function notifications()
    {
        return $this->hasMany(Notification::class, 'pedido_id');
    }

    public function usuarioCreado()
    {
        return $this->belongsTo(User::class, 'usuario_creado');
    }

    public function vendedor()
    {
        return $this->belongsTo(User::class, 'vendedor_id');
    }
    public function mercadopago()
    {
        return $this->hasOne(Mercadopago::class);
    }

    public function pedido_pagos()
    {
        return $this->hasOne(PedidoPago::class, 'pedido_id');
    }

    public function getID()
    {
        return $this->id;
    }
    public function getEstatus()
    {
        switch ($this->estatus) {
            case 0:
                return 'Abierto';
            case 1:
                return 'En revisión';
            case 3:
                return 'Pendiente de pago';
            case 4:
                return 'Pagado';
            case 5:
                return 'Enviado';
            default:
                return 'Desconocido';
        }
    }

    public function getCSS()
    {
        switch ($this->estatus) {
            case 0:
                return 'badge light badge-success'; // Rojo para Abierto
            case 1:
                return 'badge light badge-warning'; // Amarillo para En revisión
            case 3:
                return 'badge light badge-info'; // Azul para Pendiente de pago
            case 4:
                return 'badge light badge-success'; // Verde para Pagado
            case 5:
                return 'badge light badge-primary'; // Celeste para Enviado
            default:
                return 'badge light'; // Estilo predeterminado para estatus desconocido
        }
    }

    public function getEstatusValue()
    {

        return $this->estatus;
    }
    public function getMonto()
    {
        $montoFields = [
            'monto_total',
            'monto_cupon',
            'monto_vale',
            'monto_paqueteria',
            'monto_descuento_cliente',
        ];

        $monto = 0;

        foreach ($montoFields as $field) {
            if ($field === 'monto_vale' || $field === 'monto_cupon' || $field === 'monto_descuento_cliente') {
                $monto -= $this->$field;
            } else {
                $monto += $this->$field;
            }
        }

        return $monto;
    }

    public function getPropietario()
    {
        if ($this->distribuidor_id !== null) {
            return $this->distribuidor->numero_afiliacion.': '.$this->distribuidor->name.' '.$this->distribuidor->apellido_paterno.'<br>*Distribuidor';
        } elseif ($this->vendedor_id !== null) {
            return $this->vendedor->numero_afiliacion.': '.$this->vendedor->name.' '.$this->vendedor->apellido_paterno.'<br>*Vendedor Libre';
        } else {
            return 'Sin propietario asignado';
        }
    }

    public function getArticulosSolicitados()
    {
        return $this->productosPedidos->sum('cantidad_solicitada');
    }
}
