<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Municipios;
use App\Models\Estados;
use App\Models\Localidads;


class LocalidadController extends Controller
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
         (NULL, 'localidad-menu', 'web', '2023-05-05 11:48:59', '2023-05-05 11:48:59'),
         (NULL, 'localidad-create', 'web', '2023-05-05 11:48:59', '2023-05-05 11:48:59'),
         (NULL, 'localidad-edit', 'web', '2023-05-05 11:48:59', '2023-05-05 11:48:59'),
         (NULL, 'localidad-list', 'web', '2023-05-05 11:48:59', '2023-05-05 11:48:59'),
         (NULL, 'localidad-delete', 'web', '2023-05-05 11:48:59', '2023-05-05 11:48:59') */


        $this->middleware('permission:localidad-list|localidad-create|localidad-edit|localidad-delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:localidad-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:localidad-edit', ['only' => ['edit', 'update',]]);
        $this->middleware('permission:localidad-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     public function index(Request $request)
    {

       // dd($request->all());
        $perPage = $request->query('perPage', 10);
        $sortBy = $request->query('sortBy', 'nombre');
        $sortOrder = $request->query('sortOrder', 'asc');
        $localidadNombre = $request->query('localidadNombre', '');
        $cp = $request->query('cp', '');
        $ciudad = $request->query('ciudad', '');
        $estadoId = $request->query('estadoId', '');
        $nombre = $request->query('nombre', '');
        $estados = Estados::all();

        $query = Localidads::query();

        if ($localidadNombre) {
            $query->where('nombre', 'like', '%' . $localidadNombre . '%');
        }

        if ($cp) {
            $query->where('cp', 'like', '%' . $cp . '%');
        }

        if ($ciudad) {
            $query->where('ciudad', 'like', '%' .$ciudad . '%');
        }

        if ($estadoId) {
            $query->where('estado_id', $estadoId);
        }

        if ($nombre) {
            $query->where('nombre', $nombre);
        }



        $localidades = $query->with('Pais', 'Estado', 'Municipio')->orderBy($sortBy, $sortOrder)->paginate($perPage);

        //dd($localidades);
        $localidades->appends([
            'perPage' => $perPage,
            'sortBy' => $sortBy,
            'sortOrder' => $sortOrder,
            'localidadNombre' => $localidadNombre,
            'cp' => $cp,
            'ciudad' => $ciudad,
            'estadoId' => $estadoId,
        ]);

        return view('localidad.index', [
            'localidades' => $localidades,
            'i' => ($localidades->currentPage() - 1) * $localidades->perPage(),
            'perPage' => $perPage,
            'sortBy' => $sortBy,
            'sortOrder' => $sortOrder,
            'localidadNombre' => $localidadNombre,
            'cp' => $cp,
            'ciudad' => $ciudad,
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
        return view('localidad.create', compact('estados'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $localidad = new Localidads();
        $localidad->nombre = $request->nombre;
        $localidad->estatus = $request->estatus;
        $localidad->pais_id = 1;
        $localidad->estado_id = $request->estado_id;
        $localidad->municipio_id = $request->municipio_id;
        $localidad->push();

        $localidad->push();

        return redirect()->route('localidad.index')
            ->with('success', 'La colonia se ha creado con éxito.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Municipios  $municipio
     * @return \Illuminate\Http\Response
     */
    public function show(Localidads $localidad)
    {


        return view('localidad.show', compact('localidad'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Municipios  $municipio
     * @return \Illuminate\Http\Response
     */
    public function edit(Localidads $localidad)
    {
        $estados = Estados::all();
        return view('localidad.edit', compact('localidad', 'estados'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Municipios  $municipio
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Localidads $localidad)
    {

        $localidad->update($request->all());

        $localidad->estado_id = $request->estado_id;
        $localidad->push();

        return redirect()->route('localidad.index')
            ->with('success', 'La colonia se ha actualizado con éxito.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Municipios  $data
     * @return \Illuminate\Http\Response
     */
    public function destroy(Localidads $localidad)
    {
        $localidad->delete();

        return redirect()->route('localidad.index')
            ->with('success', 'la colonia se ha borrado con éxito.');
    }
}
