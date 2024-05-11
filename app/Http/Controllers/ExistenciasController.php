<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Existencias;
use App\Models\Tiendas;
use App\Models\Product;
use DB;

class ExistenciasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        /* INSERT INTO `permissions`
         (`id`, `name`, `guard_name`, `created_at`, `updated_at`)
         VALUES
         (NULL, 'existencias-menu', 'web', '2023-05-05 11:48:59', '2023-05-05 11:48:59'),
         (NULL, 'existencias-create', 'web', '2023-05-05 11:48:59', '2023-05-05 11:48:59'),
         (NULL, 'existencias-edit', 'web', '2023-05-05 11:48:59', '2023-05-05 11:48:59'),
         (NULL, 'existencias-list', 'web', '2023-05-05 11:48:59', '2023-05-05 11:48:59'),
         (NULL, 'existencias-delete', 'web', '2023-05-05 11:48:59', '2023-05-05 11:48:59') */


        $this->middleware('permission:existencias-list|existencias-create|existencias-edit|existencias-delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:existencias-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:existencias-edit', ['only' => ['edit', 'update',]]);
        $this->middleware('permission:existencias-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $existencias = Existencias::select('product_id', DB::raw('SUM(cantidad) as total_cantidad'))
            ->groupBy('product_id')
            ->paginate(15);

        return view('existencias.index', compact('existencias'))
            ->with('i', (request()->input('page', 1) - 1) * 15);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $tiendas = Tiendas::all();
        $products = Product::all();
        return view('existencias.create', compact('tiendas', 'products'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {     
        
         // Validar que no se seleccionen tiendas duplicadas
         $unique_tienda_ids = array_unique($request->input('tienda_id'));
         if (count($unique_tienda_ids) !== count($request->input('tienda_id'))) {
             return redirect()->back()->withErrors(['tienda_id' => 'No se pueden seleccionar tiendas duplicadas.'])->withInput();
         }    
 
        
        $this->validate($request, [
            'product_id' => 'required',
            'tienda_id' => 'required|array',
            'tienda_id.*' => 'required', 
            'cantidad' => 'required|array',
            'cantidad.*' => 'required|integer|min:0',
        ]);        

        $product_id = $request->input('product_id');
        $tiendas = $request->input('tienda_id');
        $cantidades = $request->input('cantidad');
                    

        foreach ($tiendas as $key => $tienda) {
            // Verificar si tienda y cantidad no son nulos
            if ($tienda !== null && $cantidades[$key] !== null && $cantidades[$key] > 0) {
                $existencias = new Existencias();
                $existencias->product_id = $product_id;
                $existencias->tienda_id = $tienda;
                $existencias->cantidad = $cantidades[$key];
                $existencias->push();
            }
        }

        // foreach ($request->tienda_id as $key => $tienda) {
        //     $existencias = new Existencias();
        //     $existencias->product_id = $request->product_id;
        //     $existencias->tienda_id = $tienda;
        //     $existencias->cantidad = $request->cantidad[$key];
        //     $existencias->push();
        // }



        return redirect()->route('existencias.index')
            ->with('success', 'El existencias se han creado con éxito.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Existencias  $existencia
     * @return \Illuminate\Http\Response
     */
    public function show($product_id)
    {
        $existencias = Existencias::where('product_id', $product_id)->get();
        $tiendas = Tiendas::all();
        $products = Product::all();
        return view('existencias.show', compact('tiendas', 'products', 'existencias'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Existencias  $existencia
     * @return \Illuminate\Http\Response
     */
    public function edit($product_id)
    {
        $existencias = Existencias::where('product_id', $product_id)->get();        
        $tiendas = Tiendas::all();
        $products = Product::all();
        return view('existencias.edit', compact('tiendas', 'products', 'existencias'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Existencias  $existencia
     * @return \Illuminate\Http\Response
     */
    public function updateMultiple(Request $request)
    {        

        // Validar que no se seleccionen tiendas duplicadas
        
        if (($unique_tienda_ids = array_unique($request->input('tienda_id'))) && $request->input('tienda_id_two')) {            
            $unique_tienda_ids_two = array_unique($request->input('tienda_id_two'));        
            $common_tienda_ids = array_intersect($unique_tienda_ids, $unique_tienda_ids_two);
            if (!empty($common_tienda_ids)) {
                return redirect()->back()->withErrors(['tienda_id' => 'No se pueden seleccionar tiendas duplicadas.'])->withInput();
            }
        }
    

        
       

        $this->validate($request, [       
            'id' => 'required|array',
            'id.*' => 'exists:existencias,id',     
            'tienda_id' => 'required|array',
            'tienda_id.*' => 'required', 
            'cantidad' => 'required|array',
            'cantidad.*' => 'required|integer|min:0',
        ]);        

        foreach ($request->input('id') as $key => $id) {            
            if ($id) {
                $existencia = Existencias::find($id);
                if ($existencia) {                    
                    $existencia->tienda_id = $request->input('tienda_id')[$key];
                    $existencia->cantidad = $request->input('cantidad')[$key];
                    $existencia->save();
                }
            }             
        }

        if ($request->input('agregar')) {

            $product_id = $request->input('product_id');
            $tiendas = $request->input('tienda_id_two');
            $cantidades = $request->input('cantidad_two');
                        

            foreach ($tiendas as $key => $tienda) {                
                if ($tienda !== null && $cantidades[$key] !== null) {
                    $existencias = new Existencias();
                    $existencias->product_id = $product_id;
                    $existencias->tienda_id = $tienda;
                    $existencias->cantidad = $cantidades[$key];
                    $existencias->push();
                }
            }
        }       
               

        return redirect()->route('existencias.index')
            ->with('success', 'La existencias se ha actualizado con éxito.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Existencias  $data
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $existencia = Existencias::find($id);
        if ($existencia) {
            $existencia->delete();
            
            // return redirect()->route('existencias.index')
            // ->with('success', 'Existencia eliminada exitosamente.');

            return response()->json(['message' => 'Existencia eliminada exitosamente.']);
        } else {
            return response()->json(['message' => 'No se encontró la existencia.'], 404);
        }
    }
}
