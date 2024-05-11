<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductosGestion;

class ProductosGestionController extends Controller
{
    public function index()
    {
        $productosGestion = ProductosGestion::orderBy('created_at', 'asc')->get();

        return view('productosgestion.index', compact('productosGestion'));
    }
}
