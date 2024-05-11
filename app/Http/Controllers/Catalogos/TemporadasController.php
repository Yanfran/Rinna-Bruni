<?php

namespace App\Http\Controllers\Catalogos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Catalogos\Temporada;

class TemporadasController extends Controller
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

        $query = Temporada::query();

        if ($nombre) {
            $query->where('nombre', 'like', '%' . $nombre . '%');
        }

        if ($estatus !== '') {
            $query->where('estatus', $estatus);
        }

        $temporadas = $query->orderBy($sortBy, $sortOrder)->paginate($perPage);


        $temporadas->appends([
            'perPage' => $perPage,
            'sortBy' => $sortBy,
            'sortOrder' => $sortOrder,
            'nombre' => $nombre,
            'estatus' => $estatus,
        ]);

        return view('catalogos.temporadas.index', [
            'temporadas' => $temporadas,
            'i' => ($temporadas->currentPage() - 1) * $temporadas->perPage(),
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
        return view('catalogos.temporadas.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        request()->validate([
            'nombre' => 'required|unique:temporadas',
        ]);
        $temporadas = New Temporada();

        $temporadas->nombre = $request->nombre;
        $temporadas->estatus = $request->estatus;
        $temporadas->save();

        return redirect()->route('temporadas.index')
                        ->with('success','La temporada se ha creado con éxito.');

    }

    /**
     * Display the specified resource.
     */
    public function show(Temporada $temporada)
    {
        return view('catalogos.temporadas.show',compact('temporada'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Temporada $temporada)
    {
        return view('catalogos.temporadas.edit',compact('temporada'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Temporada $temporada)
    {
        request()->validate([
            'nombre' => 'required|unique:temporadas,nombre,'.$temporada->id,
        ]);

        $temporada->update($request->all());

        return redirect()->route('temporadas.index')
                        ->with('success','La temporada se ha actualizado con éxito.');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Temporada $temporada)
    {

        $temporada->delete();

        return redirect()->route('temporadas.index')
                        ->with('success','La temporada se ha borrado con éxito.');
    }
}
