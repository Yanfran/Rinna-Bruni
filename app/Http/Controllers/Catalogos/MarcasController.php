<?php

namespace App\Http\Controllers\Catalogos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Catalogos\Marca;

class MarcasController extends Controller
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

        $query = Marca::query();

        if ($nombre) {
            $query->where('nombre', 'like', '%' . $nombre . '%');
        }

        if ($estatus !== '') {
            $query->where('estatus', $estatus);
        }

        $marcas = $query->orderBy($sortBy, $sortOrder)->paginate($perPage);


        $marcas->appends([
            'perPage' => $perPage,
            'sortBy' => $sortBy,
            'sortOrder' => $sortOrder,
            'nombre' => $nombre,
            'estatus' => $estatus,
        ]);

        return view('products.catalogos.marcas.index', [
            'marcas' => $marcas,
            'i' => ($marcas->currentPage() - 1) * $marcas->perPage(),
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
        return view('products.catalogos.marcas.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        request()->validate([
            'nombre' => 'required|unique:marcas',
        ]);
        $marca = New Marca();

        $marca->nombre = $request->nombre;
        $marca->estatus = $request->estatus;
        $marca->save();

        return redirect()->route('marcas.index')
                        ->with('success','La marca se ha creado con éxito.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Marca $marca)
    {
        return view('products.catalogos.marcas.show',compact('marca'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Marca $marca)
    {
        return view('products.catalogos.marcas.edit',compact('marca'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Marca $marca)
    {
        request()->validate([
            'nombre' => 'required|unique:marcas,nombre,'.$marca->id,
        ]);

        $marca->update($request->all());

        return redirect()->route('marcas.index')
                        ->with('success','La marca se ha actualizado con éxito.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Marca $marca)
    {
        $marca->delete();

        return redirect()->route('marcas.index')
                        ->with('success','La marca se ha borrado con éxito.');
    }
}
