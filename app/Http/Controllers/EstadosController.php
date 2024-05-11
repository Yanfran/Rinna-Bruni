<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Estados;


class EstadosController extends Controller
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
         (NULL, 'estados-menu', 'web', '2023-05-05 11:48:59', '2023-05-05 11:48:59'),
         (NULL, 'estados-create', 'web', '2023-05-05 11:48:59', '2023-05-05 11:48:59'),
         (NULL, 'estados-edit', 'web', '2023-05-05 11:48:59', '2023-05-05 11:48:59'),
         (NULL, 'estados-list', 'web', '2023-05-05 11:48:59', '2023-05-05 11:48:59'),
         (NULL, 'estados-delete', 'web', '2023-05-05 11:48:59', '2023-05-05 11:48:59') */


         $this->middleware('permission:estados-list|estados-create|estados-edit|estados-delete', ['only' => ['index','show']]);
         $this->middleware('permission:estados-create', ['only' => ['create','store']]);
         $this->middleware('permission:estados-edit', ['only' => ['edit','update',]]);
         $this->middleware('permission:estados-delete', ['only' => ['destroy']]);
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
        $estadoNombre = $request->query('estadoNombre', '');
        $estatus = $request->query('estatus', '');

        $query = Estados::query();

        if ($estadoNombre) {
            $query->where('nombre', 'like', '%' . $estadoNombre . '%');
        }

        if ($estatus !== '') {
            $query->where('estatus', $estatus);
        }

        $estados = $query->orderBy($sortBy, $sortOrder)->paginate($perPage);


        $estados->appends([
            'perPage' => $perPage,
            'sortBy' => $sortBy,
            'sortOrder' => $sortOrder,
            'estadoNombre' => $estadoNombre,
            'estatus' => $estatus,
        ]);

        return view('estados.index', [
            'estados' => $estados,
            'i' => ($estados->currentPage() - 1) * $estados->perPage(),
            'perPage' => $perPage,
            'sortBy' => $sortBy,
            'sortOrder' => $sortOrder,
            'estadoNombre' => $estadoNombre,
            'estatus' => $estatus
        ]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('estados.create');
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
            'nombre' => 'required|unique:estados',
        ]);
        $estados = New Estados();

        $estados->nombre = $request->nombre;
        $estados->estatus = $request->estatus;
        $estados->pais_id = 1;
        $estados->push();
        $estados->idEstado = $estados->id;
        $estados->push();

        return redirect()->route('estados.index')
                        ->with('success','El estado se ha creado con éxito.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Estados  $estado
     * @return \Illuminate\Http\Response
     */
    public function show(Estados $estado)
    {
        return view('estados.show',compact('estado'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Estados  $estado
     * @return \Illuminate\Http\Response
     */
    public function edit(Estados $estado)
    {
        return view('estados.edit',compact('estado'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Estados  $estado
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Estados $estado)
    {
         request()->validate([
            'nombre' => 'required|unique:estados,nombre,'.$estado->id,
        ]);

        $estado->update($request->all());

        return redirect()->route('estados.index')
                        ->with('success','El estado se ha actualizado con éxito.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Estados  $data
     * @return \Illuminate\Http\Response
     */
    public function destroy(Estados $estado)
    {
        $estado->delete();

        return redirect()->route('estados.index')
                        ->with('success','El estado se ha borrado con éxito.');
    }
}
