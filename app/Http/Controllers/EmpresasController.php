<?php

namespace App\Http\Controllers;

use App\Models\Empresas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EmpresasController extends Controller
{

    function __construct()
    {
        /* INSERT INTO `permissions`
         (`id`, `name`, `guard_name`, `created_at`, `updated_at`)
         VALUES
         (NULL, 'ajustes-menu', 'web', '2023-05-05 11:48:59', '2023-05-05 11:48:59'),
         (NULL, 'ajustes-create', 'web', '2023-05-05 11:48:59', '2023-05-05 11:48:59'),
         (NULL, 'ajustes-edit', 'web', '2023-05-05 11:48:59', '2023-05-05 11:48:59'),
         (NULL, 'ajustes-list', 'web', '2023-05-05 11:48:59', '2023-05-05 11:48:59'),
         (NULL, 'ajustes-delete', 'web', '2023-05-05 11:48:59', '2023-05-05 11:48:59') */


         $this->middleware('permission:ajustes-list|ajustes-create|ajustes-edit|ajustes-delete', ['only' => ['index','show']]);
         $this->middleware('permission:ajustes-create', ['only' => ['create','store']]);
         $this->middleware('permission:ajustes-edit', ['only' => ['edit','update',]]);
         $this->middleware('permission:ajustes-delete', ['only' => ['destroy']]);
    }
    public function edit($id)
    {
        $empresas = Empresas::find($id);

        if (!empty($empresas)) {
            return view('empresas.nuevo', compact('empresas'));
        } else {
            return view('error.504');
        }
    }

    public function store(Request $r)
    {
        $empresas = new Empresas();

        if ($r->id != null) {
            $find = Empresas::find($r->id);
            if (!empty($find)) {
                $empresas = $find;
            }
        }

        if ($r->hasFile('logo')) {
            $dir = 'uploads/logos';
            $extension = strtolower($r->file('logo')->getClientOriginalExtension());
            $fileName = time() . '_.' . $extension;
            $r->file('logo')->move($dir, $fileName);
            $empresas->logo = $fileName;
        }

        $empresas->colorPrimario = $r->color_1;
        $empresas->colorSecundario = $r->color_2;
        $empresas->inactividad = $r->inactividad;
        $empresas->costo_paqueteria = $r->costo_paqueteria;
        // Aquí se guardan las claves de Mercado Pago
        $empresas->mp_public_key = $r->mp_public_key;
        $empresas->mp_access_token = $r->mp_access_token;


        $empresas->save();

        return redirect()->route('cofiguraciones', $empresas->id);
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'color_1' => 'required|string|max:255',
            'color_2' => 'required|string|max:255'
        ]);
    }
}
