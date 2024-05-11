<?php

namespace App\Http\Controllers;

use App\Models\Direcciones;
use App\Models\Estados;
use App\Models\User;
use App\Models\Tiendas;
use Illuminate\Http\Request;

class SucursalesController extends Controller
{
    public function index(Request $request, User $user)
    {

        $limite_cuentas_creadas = $user->cuentas_restantes;        
        $tienda_id = $user->tienda_id;
        // dd($tienda_id);
        $tiendas = Tiendas::find($tienda_id);                
        $estatusTienda = $tiendas->estatus;

        $perPage = $request->query('perPage', 10);
        $sortBy = $request->query('sortBy', 'alias');
        $sortOrder = $request->query('sortOrder', 'asc');
        $alias = $request->query('alias', '');

        $sucursales = Direcciones::where('user_id', $user->id)->where('tipo', 2)->paginate(10);

        return view('sucursales.index', [
            'sucursales' => $sucursales,
            'i' => ($sucursales->currentPage() - 1) * $sucursales->perPage(),
            'perPage' => $perPage,
            'sortBy' => $sortBy,
            'sortOrder' => $sortOrder,
            'alias' => $alias,
            'user' => $user,
            'limite_cuentas_creadas' => $limite_cuentas_creadas,
            'estatusTienda' => $estatusTienda
        ]);

    }

    public function create(User $user)
    {
        $estados = Estados::all();
        return view('sucursales.create',compact('estados', 'user'));
    }

    public function store(Request $request)
    {

        // dd($request);
        $request->validate([
            'alias' => 'required',
            'estado_id' => 'required',
            'municipio_id' => 'required',
            'localidad_id' => 'required',
            'calle' => 'required',
            'user_id' => 'required',
            'cp' => 'required',            
            'apellido_paterno' => 'required',
            // 'apellido_materno' => 'min:2',
            'correo' => 'required'
        ]);

        if ($request->has('estatus')) {
            $request->merge(['estatus' => 1]);
        } else {
            $request->merge(['estatus' => 0]);
        }

        Direcciones::create($request->all());

        return redirect()->route('sucursales.index', $request->user_id)
            ->with('success', 'La sucursal se ha creado correctamente.');
    }

    public function edit(Direcciones $sucursal)
    {
        $estados = Estados::all();
        return view('sucursales.edit', compact('sucursal', 'estados'));
    }

    public function update(Request $request, Direcciones $sucursal)
    {
        $request->validate([
            'alias' => 'required',
            'estado_id' => 'required',
            'municipio_id' => 'required',
            'localidad_id' => 'required',
            'calle' => 'required',
            'user_id' => 'required',
            'cp' => 'required',            
            'apellido_paterno' => 'required',
            // 'apellido_materno' => 'min:2',
            'correo' => 'required'
        ]);

        if ($request->has('estatus')) {
            $request->merge(['estatus' => 1]);
        } else {
            $request->merge(['estatus' => 0]);
        }

        $sucursal->update($request->all());

        return redirect()->route('sucursales.index', $sucursal->user_id)
            ->with('success', 'La sucursal se ha actualizado correctamente.');
    }

     /**
     * Display the specified resource.
     *
     * @param  \App\Estados  $estado
     * @return \Illuminate\Http\Response
     */
    public function show(Direcciones $sucursal)
    {
        return view('sucursales.show',compact('sucursal'));
    }

    public function destroy(Direcciones $direccion)
    {
        $direccion->delete();

        return redirect()->route('sucursales.index')
            ->with('success', 'La sucursal se ha eliminado correctamente.');
    }
}
