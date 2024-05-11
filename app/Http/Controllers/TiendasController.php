<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tiendas;
use App\Models\Estados;
use App\Models\Municipios;
use App\Models\Localidads;


class TiendasController extends Controller
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
         (NULL, 'tiendas-menu', 'web', '2023-05-05 11:48:59', '2023-05-05 11:48:59'),
         (NULL, 'tiendas-create', 'web', '2023-05-05 11:48:59', '2023-05-05 11:48:59'),
         (NULL, 'tiendas-edit', 'web', '2023-05-05 11:48:59', '2023-05-05 11:48:59'),
         (NULL, 'tiendas-list', 'web', '2023-05-05 11:48:59', '2023-05-05 11:48:59'),
         (NULL, 'tiendas-delete', 'web', '2023-05-05 11:48:59', '2023-05-05 11:48:59') */


         $this->middleware('permission:tiendas-list|tiendas-create|tiendas-edit|tiendas-delete', ['only' => ['index','show']]);
         $this->middleware('permission:tiendas-create', ['only' => ['create','store']]);
         $this->middleware('permission:tiendas-edit', ['only' => ['edit','update',]]);
         $this->middleware('permission:tiendas-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $tiendas = Tiendas::orderBy('nombre', 'ASC')->paginate(15);

        return view('tiendas.index', compact('tiendas'))
            ->with('i', (request()->input('page', 1) - 1) * 15);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $estados = Estados::all();
        return view('tiendas.create', compact('estados'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate([
            'nombre' => 'required|unique:tiendas',
            'external_id' => 'numeric|min:2',
        ]);
        $tiendas = New Tiendas();

        $tiendas->nombre = $request->nombre;
        $tiendas->codigo = $request->codigo;
        $tiendas->pais_id = $request->pais_id;
        $tiendas->estado_id = $request->estado_id;
        $tiendas->municipio_id = $request->municipio_id;
        $tiendas->localidad_id = $request->localidad_id;
        $tiendas->estatus = $request->estatus;
        $tiendas->calle_numero = $request->calle_numero;
        $tiendas->cp = $request->cp;
        $tiendas->external_id = $request->external_id;
        $tiendas->push();


        return redirect()->route('tiendas.index')
                        ->with('success','La tienda se ha creado con éxito');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Tiendas  $tienda
     * @return \Illuminate\Http\Response
     */
    public function show(Tiendas $tienda)
    {
        return view('tiendas.show',compact('tienda'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Tiendas  $tienda
     * @return \Illuminate\Http\Response
     */
    public function edit(Tiendas $tienda)
    {
        $estados = Estados::all();
        $municipios = Municipios::where('estado_id', $tienda->estado_id)->get();
        $localidads = Localidads::where('municipio_id', $tienda->municipio_id)->get();


        return view('tiendas.edit', compact('tienda', 'estados', 'municipios', 'localidads'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Tiendas  $tienda
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tiendas $tienda)
    {
         request()->validate([
            'nombre' => 'required|unique:tiendas,nombre,'.$tienda->id,
            'external_id' => 'numeric|min:2',
        ]);

        $tienda->update($request->all());

        return redirect()->route('tiendas.index')
                        ->with('success','La tienda se ha actualizado con éxito.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Tiendas  $data
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tiendas $tienda)
    {
        $tienda->delete();

        return redirect()->route('tiendas.index')
                        ->with('success','La tienda se ha borrado con éxito.');
    }
}
