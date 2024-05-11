<?php

namespace App\Http\Controllers;

use App;
use App\Models\Empresas;
use Auth;
use Config;
use Session;

class InicioLoginController extends Controller
{
    public function index()
    {
        if (!empty(Auth::user())) {
            if (!empty(Auth::user()->id)) {
                return redirect()->route('index')->withCookie(cookie('locale', Session::get('locale', App::getLocale()), Config::get('session.lifetime')));
            }
        }
        $class = new Empresas();

        $empresas = $class->EmpresasAll();
        $oficina = $class->EmpresaOficina();



        return redirect()->route('login', ['id' => 1]);

        //return view('login.index', compact('empresas', 'oficina'));


    }
}
