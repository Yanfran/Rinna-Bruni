<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cupons;
use Illuminate\Support\Str;
use App\Helpers\NotificationHelper;


class CuponsController extends Controller
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
         (NULL, 'cupons-menu', 'web', '2023-05-05 11:48:59', '2023-05-05 11:48:59'),
         (NULL, 'cupons-create', 'web', '2023-05-05 11:48:59', '2023-05-05 11:48:59'),
         (NULL, 'cupons-edit', 'web', '2023-05-05 11:48:59', '2023-05-05 11:48:59'),
         (NULL, 'cupons-list', 'web', '2023-05-05 11:48:59', '2023-05-05 11:48:59'),
         (NULL, 'cupons-delete', 'web', '2023-05-05 11:48:59', '2023-05-05 11:48:59') */        


        $this->middleware('permission:cupons-list|cupons-create|cupons-edit|cupons-delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:cupons-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:cupons-edit', ['only' => ['edit', 'update',]]);
        $this->middleware('permission:cupons-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $cupons = Cupons::orderBy('nombre', 'ASC')->paginate(15);

        return view('cupons.index', compact('cupons'))
            ->with('i', (request()->input('page', 1) - 1) * 15);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('cupons.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required',
        ],[
            'nombre.required' => 'El campo nombre es obligatorio.',
        ]);


        

        $cupon = new Cupons();
        $cupon->nombre = $request->nombre;
        $cupon->estatus = $request->estatus;

        $codigo = strtoupper(Str::random(12));

        // Validar que el código generado sea único
        while (Cupons::where('codigo', $codigo)->exists()) {
            $codigo = strtoupper(Str::random(12));
        }

        $cupon->codigo = $codigo;

        // Comprueba si se ha seleccionado un usuario específico
        if ($request->has('aplicar_usuario') && $request->aplicar_usuario) {
            $request->validate([
                'user_id' => 'required|exists:users,id',
            ],[
                'user_id.required' => 'El campo usuario es obligatorio.',
            ]);
            $cupon->user_id = $request->user_id;
        }

        // Establece el tipo y monto/porcentaje dependiendo del tipo de cupón
        $tipoCupon = $request->tipo;
        switch ($tipoCupon) {
            case '3':
                $cupon->cantidad_usos = 1; // Establece cantidad_usos a 1 si el tipo es 3
                break;
            default:
                $cupon->cantidad_usos = $request->cantidad_usos; // Guarda el valor de cantidad_usos en otros casos
                break;
        }
        switch ($tipoCupon) {
            case '1':
                $cupon->tipo = 1; // Cupón de dinero
                $cupon->monto = $request->monto;
                $cupon->porcentaje = 0;
                break;
            case '2':
                $cupon->tipo = 2; // Cupón de porcentaje aplicable
                $cupon->porcentaje = $request->porcentaje;
                $cupon->monto = 0;
                break;
            case '3':
                $cupon->tipo = 3; // Vale a favor
                $request->validate([
                    'user_id' => 'required|exists:users,id',
                ],[
                    'user_id.required' => 'El campo usuario es obligatorio.',
                ]);
                $cupon->monto = $request->monto;
                $cupon->user_id = $request->user_id;
                $cupon->porcentaje = 0;
                break;
        }

        $cupon->fecha_inicio = $request->fecha_inicio;
        $cupon->fecha_fin = $request->fecha_fin;


        if ($request->user_id != null) {

            if ($request->cantidad_usos > 1) {
                $msg = 'Nuevo cupon disponible codigo: ' . $codigo . ' Aprovecha solo tiene ' . $request->cantidad_usos . ' usos';
                NotificationHelper::notificacionUsuarioCupon($request->user_id, $msg);
            } else {
                $msg = 'Nuevo cupon disponible codigo: ' . $codigo;
                NotificationHelper::notificacionUsuarioCupon($request->user_id, $msg);
            }
        } else {
            if ($request->cantidad_usos > 1) {
                $msg = 'Nuevo cupon disponible codigo: ' . $codigo . ' Aprovecha solo tiene ' . $request->cantidad_usos . ' usos';
                NotificationHelper::notificacionUsuarioAll( $msg);
            } else {
                $msg = 'Nuevo cupon disponible codigo: ' . $codigo;
                NotificationHelper::notificacionUsuarioAll($msg);
            }
        }
        NotificationHelper::ejecutarNotificaciones();

        $cupon->save();

        return redirect()->route('cupons.index')
            ->with('success', 'El cupón se ha creado con éxito.')
            ->withInput($request->input());
    }




    /**
     * Display the specified resource.
     *
     * @param  \App\Cupons  $cupon
     * @return \Illuminate\Http\Response
     */
    public function show(Cupons $cupon)
    {
        return view('cupons.show', compact('cupon'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Cupons  $cupon
     * @return \Illuminate\Http\Response
     */
    public function edit(Cupons $cupon)
    {
        
        return view('cupons.edit', compact('cupon'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Cupons  $cupon
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cupons $cupon)
    {
        $request->validate([
            'nombre' => 'required',
            'cantidad_usos' => 'required',
        ]);

        $cupon->nombre = $request->nombre;
        $cupon->estatus = $request->estatus;

        // Establece la cantidad de usos según el tipo de cupón
        $tipoCupon = $request->tipo;
        switch ($tipoCupon) {
            case '3':
                $cupon->cantidad_usos = 1; // Establece cantidad_usos a 1 si el tipo es 3
                break;
            default:
                $cupon->cantidad_usos = $request->cantidad_usos; // Guarda el valor de cantidad_usos en otros casos
                break;
        }

        // Comprueba si se ha seleccionado un usuario específico
        if ($request->has('aplicar_usuario') && $request->aplicar_usuario) {
            $request->validate([
                'user_id' => 'required|exists:users,id',
            ]);
            $cupon->user_id = $request->user_id;
        } else {
            $cupon->user_id = null; // Establece el usuario a null si no se selecciona
        }

        // Establece el tipo y monto/porcentaje dependiendo del tipo de cupón
        switch ($tipoCupon) {
            case '1':
                $cupon->tipo = 1; // Cupón de dinero
                $cupon->monto = $request->monto;
                $cupon->porcentaje = 0; // Reinicia el porcentaje a null
                break;
            case '2':
                $cupon->tipo = 2; // Cupón de porcentaje aplicable
                $cupon->porcentaje = $request->porcentaje;
                $cupon->monto = 0; // Reinicia el monto a null
                break;
            case '3':
                $cupon->tipo = 3; // Vale a favor
                $request->validate([
                    'user_id' => 'required|exists:users,id',
                ]);
                $cupon->monto = $request->monto;
                $cupon->user_id = $request->user_id;
                $cupon->porcentaje = 0;
                break;
        }

        $cupon->fecha_inicio = $request->fecha_inicio;
        $cupon->fecha_fin = $request->fecha_fin;

        $cupon->save();

        return redirect()->route('cupons.index')
            ->with('success', 'El cupón se ha actualizado con éxito.');
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Cupons  $data
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cupons $cupon)
    {
        $cupon->delete();

        return redirect()->route('cupons.index')
            ->with('success', 'El cupons se ha borrado con éxito.');
    }
}
