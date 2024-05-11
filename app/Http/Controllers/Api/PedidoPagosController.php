<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pedidos;
use App\Models\PedidoPago;
use Illuminate\Support\Facades\Storage;

class PedidoPagosController extends Controller
{
    public function saveComprobante(Request $request)
    {
        try {

            $request->validate([
                "pedido_id" => "required",
                "img_comprobante" => "required|image|mimes:jpg,png,jpg,gif|max:100"
            ],[
                "pedido_id.required" => "El id del pedido es requerido",
                "img_comprobante.required" => "La imagen es requerida",
                "img_comprobante.image" => "El archivo debe ser de tipo imagen",
                "img_comprobante.mimes" => "Formato no valido, admitidos: jpg,png,jpg,gif",
                "img_comprobante.max" => "Archivo demasiado grande, max 100 KB"
            ]);

            $pedido = Pedidos::where('id', $request->input('pedido_id'))->first();

            if(!isset($pedido)) {
                return response()->json([
                    "estatus" => "error",
                    "code" => 500,
                    "message" => "Pedido no encontrado"
                ], 500);
            }
            
            //validar si sea cargado un comprobante
            $numeroComprobantes = $pedido->pedido_pagos()->count();
            if($numeroComprobantes > 0) {
                return response()->json([
                    "estatus" => "error",
                    "code" => 500,
                    "message" => "Ya se ha cargado un comprobante"
                ], 500);
            }

            //procesar la imagen
            $image = $request->file('img_comprobante');

            // Definir la carpeta donde se guardarán los archivos
            $carpeta = 'pedido_comprobantes';

            // Verificar si la carpeta no existe y crearla si es necesario
            if (!Storage::exists($carpeta)) {
                Storage::makeDirectory($carpeta);
            }

            $rutaImage = $carpeta . '/' . 'comprobante_pedido_' . $pedido->id . '.' . $image->getClientOriginalExtension();
            //$image->move(public_path('pedido_comprobantes'), $imageName);

            Storage::put($rutaImage, file_get_contents($image));

            PedidoPago::create([
                'pedido_id' => $request->input('pedido_id'),
                'img_comprobante' => $rutaImage,
            ]);

            return response()->json([
                "estatus" => "success",
                "code" => 200,
                "message" => "Comprobante guardado con exito",
            ], 200);
            
        } catch(\Exception $e) {
            return response()->json([
                "estatus" => "error",
                "code" => 500,
                "message" => "Error al cargar comprobante". $e->getMessage()
            ], 500);
        }
    }
}
