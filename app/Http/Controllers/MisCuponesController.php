<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Auth;
use DB;
use Hash;
use PDF;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\View;

class MisCuponesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cupones = Auth::user()->cupones;        

        return view('misCupones.index', compact('cupones'));                                  
    }

    public function vales()
    {
        $vales = Auth::user()->vales;        

        return view('misVales.index', compact('vales'));                                  
    }
    

}
