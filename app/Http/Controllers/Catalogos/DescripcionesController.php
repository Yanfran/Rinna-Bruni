<?php

namespace App\Http\Controllers\Catalogos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Catalogos\Descripcion;

class DescripcionesController extends Controller
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

        $query = Descripcion::query();

        if ($nombre) {
            $query->where('nombre', 'like', '%' . $nombre . '%');
        }

        if ($estatus !== '') {
            $query->where('estatus', $estatus);
        }

        $descripciones = $query->orderBy($sortBy, $sortOrder)->paginate($perPage);


        $descripciones->appends([
            'perPage' => $perPage,
            'sortBy' => $sortBy,
            'sortOrder' => $sortOrder,
            'nombre' => $nombre,
            'estatus' => $estatus,
        ]);

        return view('catalogos.descripciones.index', [
            'descripciones' => $descripciones,
            'i' => ($descripciones->currentPage() - 1) * $descripciones->perPage(),
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
        return view('catalogos.descripciones.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        request()->validate([
            'nombre' => 'required|unique:descripciones',
        ]);
        $descripcion = New Descripcion();

        $descripcion->nombre = $request->nombre;
        $descripcion->estatus = $request->estatus;
        $descripcion->save();

        return redirect()->route('descripciones.index')
                        ->with('success','La Descripción se ha creado con éxito.');

    }

    /**
     * Display the specified resource.
     */
    public function show(Descripcion $descripcion)
    {
        return view('catalogos.descripciones.show',compact('descripcion'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Descripcion $descripcione)
    {
        return view('catalogos.descripciones.edit',compact('descripcione'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Descripcion $descripcione)
    {
        request()->validate([
            'nombre' => 'required|unique:descripciones,nombre,'.$descripcione->id,
        ]);

        $descripcione->update($request->all());

        return redirect()->route('descripciones.index')
                        ->with('success','La descripcion se ha actualizado con éxito.');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Descripcion $descripcione)
    {

        $descripcione->delete();

        return redirect()->route('descripciones.index')
                        ->with('success','La descripción se ha borrado con éxito.');
    }
}
