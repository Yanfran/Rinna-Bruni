<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Pedidos;
use App\Models\ProductosPedido;
use App\Models\Existencias;
class EliminarPedidos extends Command
{
    protected $signature = 'pedidos:eliminar';

    protected $description = 'Elimina los pedidos en estado 0 después de una semana.';

    public function handle()
    {
        $fechaLimite = now()->subWeek();

        $pedidos = Pedidos::where('estatus', 0)
            ->where('created_at', '<=', $fechaLimite)
            ->get();

        foreach ($pedidos as $pedido) {
            // Obtener los productos asociados al pedido
            $productosPedido = ProductosPedido::where('pedido_id', $pedido->id)->get();

            foreach ($productosPedido as $productoPedido) {
                $productoID = $productoPedido->product_id;
                $cantidadSolicitada = $productoPedido->cantidad_solicitada;
                $tiendaID = $productoPedido->user->tienda_id;

                // Incrementar la cantidad en el inventario
                $existencia = Existencias::where('product_id', $productoID)
                    ->where('tienda_id', $tiendaID)
                    ->first();

                if ($existencia) {
                    $existencia->cantidad += $cantidadSolicitada;
                    $existencia->save();
                }
            }

            // Eliminar los productos asociados al pedido
            ProductosPedido::where('pedido_id', $pedido->id)->delete();

            // Eliminar el pedido
            $pedido->delete();
        }

        $this->info('Se han eliminado los pedidos en estado 0 creados hace una semana o más.');
    }
}
