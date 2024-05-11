<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Direcciones;
use Spatie\Permission\Models\Role;
use App\Models\Tiendas;
use App\Models\Estados;
use App\Models\Municipios;
use App\Models\Localidads;
use DB;
use Hash;
use Auth;
use Illuminate\Support\Arr;

class ResetPasswordController extends Controller
{

    function __construct()
    {
         $this->middleware('permission:user-menu|user-list|user-create|user-edit|user-delete', ['only' => ['index','store']]);
         $this->middleware('permission:user-create', ['only' => ['create','store']]);
         $this->middleware('permission:user-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:user-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
     
    public function index($userId)
    {                         
        $data = User::where('id', $userId)->first(['id', 'password']);
               
        return view('resetPassword.index',compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {      
       

        return redirect()->route('users.index')
                        ->with('success','Usuario creado correctamente')
                        ->withInput($request->input());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {        

        $this->validate($request, [            
            'nuevo-password' => 'nullable|min:8|same:confirm-password',
            'password-actual-verificar' => 'required'          
        ]);


        $input = $request->all();
        $passwordActualVerificado = $input['password-actual-verificar'];
        $userId = $request->input('userId');
            
        $user = Auth::user();
        
                
        if (!Hash::check($passwordActualVerificado, $user->password)) {            
            return redirect()->route('resetPassword.index', ['userId' => $userId])
                    ->withErrors(['password-actual-verificar' => 'Clave anterior no coincide con la nueva']);
        } 

        $newPassword = $request->input('nuevo-password');
        $user->password = Hash::make($newPassword);
        $user->save();

        return redirect()->route('resetPassword.index', ['userId' => $userId])
                        ->with('success','Clave actualizada correctamente')
                        ->withInput($request->input());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {        
    }

}
