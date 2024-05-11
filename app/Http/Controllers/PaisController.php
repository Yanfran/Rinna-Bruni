<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pais;


class PaisController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        /* INSERT INTO `permissions`
         (`id`, `name`, `guard_name`, `created_at`, `updated_at`, `screen`)
         VALUES
         (NULL, 'pais-list', 'web', '2023-05-05 11:48:59', '2023-05-05 11:48:59', 'pais'),
         (NULL, 'pais-create', 'web', '2023-05-05 11:48:59', '2023-05-05 11:48:59', 'pais'),
         (NULL, 'pais-edit', 'web', '2023-05-05 11:48:59', '2023-05-05 11:48:59', 'pais'),
         (NULL, 'pais-delete', 'web', '2023-05-05 11:48:59', '2023-05-05 11:48:59', 'pais') */


         $this->middleware('permission:pais-list|pais-create|pais-edit|pais-delete', ['only' => ['index','show']]);
         $this->middleware('permission:pais-create', ['only' => ['create','store']]);
         $this->middleware('permission:pais-edit', ['only' => ['edit','update',]]);
         $this->middleware('permission:pais-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $pais = Pais::orderBy('nombre', 'ASC')->paginate(15);

        return view('pais.index', compact('pais'))
            ->with('i', (request()->input('page', 1) - 1) * 15);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pais.create');
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
            'nombre' => 'required|unique:pais',
        ]);
        $pais = New Pais();

        $pais->nombre = $request->nombre;
        $pais->estatus = $request->estatus;
        $pais->push();


        return redirect()->route('pais.index')
                        ->with('success','El pais se ha creado con éxito.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Pais  $pai
     * @return \Illuminate\Http\Response
     */
    public function show(Pais $pai)
    {
        return view('pais.show',compact('pai'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Pais  $pai
     * @return \Illuminate\Http\Response
     */
    public function edit(Pais $pai)
    {
        return view('pais.edit',compact('pai'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Pais  $pai
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Pais $pai)
    {
         request()->validate([
            'nombre' => 'required|unique:pais,nombre,'.$pai->id,
        ]);

        $pai->update($request->all());

        return redirect()->route('pais.index')
                        ->with('success','El pais se ha actualizado con éxito.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Pais  $data
     * @return \Illuminate\Http\Response
     */
    public function destroy(Pais $pai)
    {
        $pai->delete();

        return redirect()->route('pais.index')
                        ->with('success','El pais se ha borrado con éxito.');
    }
}
