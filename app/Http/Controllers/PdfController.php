<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Pedidos;
use App\Models\ProductosPedido;
use App\Models\Product;
use App\Models\Direcciones;
use App\Models\Estados;
use App\Models\Municipios;
use App\Models\Localidads;
use Barryvdh\DomPDF\Facade\Pdf;

class PdfController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function reporte($id)
    {
        try {

            $pedido = Pedidos::where('id', $id)
                ->with('productosPedidos', function($query) {
                    $query->with('product', function ($query) {
                        $query->with('marca');
                    });
                })
                ->first();
            
            $distribuidorId = $pedido->distribuidor_id;
            $vendedorId = $pedido->vendedor_id;
                
            if(isset($distribuidorId)) {
                $direccion = Direcciones::where('user_id', $distribuidorId)->first();
                $distribuidor = User::where("distribuidor_id", $distribuidorId)->first();
            }elseif(isset($vendedorId)) {
                $direccion = Direcciones::where('user_id', $vendedorId)->first();
                $distribuidor = User::where("id", $vendedorId)->first();
            }

            $estado_id = $direccion->estado_id;
            $estado = Estados::where('id', $estado_id)->first();   
            
            $localidad_id = $direccion->localidad_id;
            $localidad = Localidads::where('id', $localidad_id)->first();  
            
            $municipio_id = $direccion->municipio_id;
            $municipio = Municipios::where('id', $municipio_id)->first();

            $data = [
                'pedido' => $pedido,
                'estado' => $estado,
                'localidad' => $localidad,
                'municipio' => $municipio,
                'direccion' => $direccion,
                'distribuidor' => $distribuidor
            ];

            //return $data; 
            //dd($pedido->productosPedidos);
            

            $pdf = Pdf::loadView('pdf.reporte', $data);
            
            $pdf->setPaper("letter","portrait");

            return $pdf->stream();

        }catch(\Exception $e) {
            return $e->getMessage();
            // return redirect()
            //     ->route('pedidos.index')
            //     ->with('error', 'Erro al generar reporte' . $e->getMessage());
        }        
        
    }
}
