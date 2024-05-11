<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductosNegados;

class ProductosNegadosController extends Controller
{
    public function index()
    {
        $productosNegados = ProductosNegados::orderBy('created_at', 'asc')->get();

        return view('productosnegados.index', compact('productosNegados'));
    }
}
