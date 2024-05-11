<?php

namespace App\Traits;

use Illuminate\Http\Request;
use App\Models\Pedidos;
use App\Models\PedidoPago;
use Illuminate\Support\Facades\Storage;

trait TraitPedidos {
    function saveComprobante(Request $request){

        $pedido = Pedidos::where('id', $request->pedido_id)->first();

        if ($pedido) {

            $fileComprobante = $request->file('vaucher');

            if ( ! is_null($fileComprobante) ) {

                $comprobanteImgExtension = $fileComprobante->getClientOriginalExtension();
                $comprobanteImgName = 'comprobante_pedido_' . $request->pedido_id . '.' . $comprobanteImgExtension;

                $carpeta = 'pedido_comprobantes/';

                Storage::disk('public')->putFileAs($carpeta, $fileComprobante, $comprobanteImgName);

                $numeroComprobantes = $pedido->pedido_pagos()->count();

                if($numeroComprobantes == 0) {

                    PedidoPago::create([
                        'pedido_id' => $request->pedido_id,
                        'img_comprobante' => $comprobanteImgName,
                    ]);

                }



            } //Si se cargo el archivo

        }//Si existe el pedido


    }
}
