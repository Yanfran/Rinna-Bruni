<?php

namespace App\Http\Controllers\Catalogos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Catalogos\Linea;


class LineasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->query('perPage', 10);
        $sortBy = $request->query('sortBy', 'nombre');
        $sortOrder = $request->query('sortOrder', 'asc');
        $nombre = $request->query('nombre', '');
        $estatus = $request->query('estatus', '');

        $query = Linea::query();

        if ($nombre) {
            $query->where('nombre', 'like', '%' . $nombre . '%');
        }

        if ($estatus !== '') {
            $query->where('estatus', $estatus);
        }

        $lineas = $query->orderBy($sortBy, $sortOrder)->paginate($perPage);


        $lineas->appends([
            'perPage' => $perPage,
            'sortBy' => $sortBy,
            'sortOrder' => $sortOrder,
            'nombre' => $nombre,
            'estatus' => $estatus,
        ]);

        return view('catalogos.lineas.index', [
            'lineas' => $lineas,
            'i' => ($lineas->currentPage() - 1) * $lineas->perPage(),
            'perPage' => $perPage,
            'sortBy' => $sortBy,
            'sortOrder' => $sortOrder,
            'nombre' => $nombre,
            'estatus' => $estatus
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('catalogos.lineas.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        request()->validate([
            'nombre' => 'required|unique:lineas',
        ]);
        $linea = New Linea();

        $linea->nombre = $request->nombre;
        $linea->estatus = $request->estatus;
        $linea->save();

        return redirect()->route('lineas.index')
                        ->with('success','La linea se ha creado con éxito.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Linea $linea)
    {
        return view('catalogos.lineas.show',compact('linea'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Linea $linea)
    {
        return view('catalogos.lineas.edit',compact('linea'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Linea $linea)
    {
        request()->validate([
            'nombre' => 'required|unique:lineas,nombre,'.$linea->id,
        ]);

        $linea->update($request->all());

        return redirect()->route('lineas.index')
                        ->with('success','La linea se ha actualizado con éxito.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Linea $linea)
    {

        $linea->delete();

        return redirect()->route('lineas.index')
                        ->with('success','La linea se ha borrado con éxito.');
    }
}
