<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cupons;
use Illuminate\Support\Str;
use App\Helpers\NotificationHelper;


class CuponesController extends Controller
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
         (NULL, 'mis-cupones-menu', 'web', '2023-05-05 11:48:59', '2023-05-05 11:48:59')*/

        $this->middleware('permission:mis-cupones-list', ['only' => ['index', 'show']]);

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $cupons = Cupons::orderBy('nombre', 'ASC')->paginate(15);

        return view('misCupones.index', compact('cupons'))
            ->with('i', (request()->input('page', 1) - 1) * 15);
    }



    /**
     * Display the specified resource.
     *
     * @param  \App\Cupons  $cupon
     * @return \Illuminate\Http\Response
     */
    public function show(Cupons $cupon)
    {
        return view('misCupones.show', compact('cupon'));
    }


}
