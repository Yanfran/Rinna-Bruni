<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Municipios;
use App\Models\Estados;


class MunicipiosController extends Controller
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
         (NULL, 'municipios-menu', 'web', '2023-05-05 11:48:59', '2023-05-05 11:48:59'),
         (NULL, 'municipios-create', 'web', '2023-05-05 11:48:59', '2023-05-05 11:48:59'),
         (NULL, 'municipios-edit', 'web', '2023-05-05 11:48:59', '2023-05-05 11:48:59'),
         (NULL, 'municipios-list', 'web', '2023-05-05 11:48:59', '2023-05-05 11:48:59'),
         (NULL, 'municipios-delete', 'web', '2023-05-05 11:48:59', '2023-05-05 11:48:59') */


        $this->middleware('permission:municipios-list|municipios-create|municipios-edit|municipios-delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:municipios-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:municipios-edit', ['only' => ['edit', 'update',]]);
        $this->middleware('permission:municipios-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     public function index(Request $request)
    {
        $perPage = $request->query('perPage', 10);
        $sortBy = $request->query('sortBy', 'nombre');
        $sortOrder = $request->query('sortOrder', 'asc');
        $municipioNombre = $request->query('municipioNombre', '');
        $estadoId = $request->query('estadoId', '');
        $estados = Estados::all();

        $query = Municipios::query();

        if ($municipioNombre) {
            $query->where('nombre', 'like', '%' . $municipioNombre . '%');
        }

        if ($estadoId) {
            $query->where('estado_id', $estadoId);
        }

        $municipios = $query->with('Pais', 'Estado')->orderBy($sortBy, $sortOrder)->paginate($perPage);

        $municipios->appends([
            'perPage' => $perPage,
            'sortBy' => $sortBy,
            'sortOrder' => $sortOrder,
            'municipioNombre' => $municipioNombre,
            'estadoId' => $estadoId,
        ]);

        return view('municipios.index', [
            'municipios' => $municipios,
            'i' => ($municipios->currentPage() - 1) * $municipios->perPage(),
            'perPage' => $perPage,
            'sortBy' => $sortBy,
            'sortOrder' => $sortOrder,
            'municipioNombre' => $municipioNombre,
            'estadoId' => $estadoId,
            'estados' => $estados,
        ]);
    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $estados = Estados::all();
        return view('municipios.create', compact('estados'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $municipios = new Municipios();
        $municipios->nombre = $request->nombre;
        $municipios->estatus = $request->estatus;
        $municipios->pais_id = 1;
        $municipios->estado_id = $request->estado_id;
        $municipios->push();
        $municipios->idMunicipio = $municipios->id;
        $municipios->push();

        return redirect()->route('municipios.index')
            ->with('success', 'El municipio se ha creado con éxito.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Municipios  $municipio
     * @return \Illuminate\Http\Response
     */
    public function show(Municipios $municipio)
    {
        return view('municipios.show', compact('municipio'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Municipios  $municipio
     * @return \Illuminate\Http\Response
     */
    public function edit(Municipios $municipio)
    {
        $estados = Estados::all();
        return view('municipios.edit', compact('municipio', 'estados'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Municipios  $municipio
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Municipios $municipio)
    {

        $municipio->update($request->all());

        $municipio->estado_id = $request->estado_id;
        $municipio->push();

        return redirect()->route('municipios.index')
            ->with('success', 'El municipio se ha actualizado con éxito.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Municipios  $data
     * @return \Illuminate\Http\Response
     */
    public function destroy(Municipios $municipio)
    {
        $municipio->delete();

        return redirect()->route('municipios.index')
            ->with('success', 'El municipio se ha borrado con éxito.');
    }
}
