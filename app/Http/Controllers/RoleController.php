<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use DB;
//use Spatie\Html\Elements\Form;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
         $this->middleware('permission:role-list|role-create|role-edit|role-delete', ['only' => ['index','store']]);
         $this->middleware('permission:role-create', ['only' => ['create','store']]);
         $this->middleware('permission:role-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:role-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $roles = Role::orderBy('id','DESC')->paginate(5);
        return view('roles.index',compact('roles'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permission = Permission::get();
        $screen = array();
        foreach($permission as $valor){
            $validar = false;
            foreach($screen as $x){
                if ($x == $valor->screen) {
                    $validar = true;
                }
            }

            if (!$validar) {
                array_push($screen, $valor->screen);
            }
        }
        $screenFinal = array();
        $validarFinal = true;
        foreach($screen as $x){
            $screenDetail = array();
            $validarFinal = false;
            foreach($permission as $valor){
                if ($x == $valor->screen) {
                    $validar = true;
                    $arrech = array(
                        'name' => $valor->name,
                        'id' => $valor->id
                    );
                    array_push($screenDetail, $arrech);
                }
            }
            $arrFi = array(
                'name' => $x,
                'details' => $screenDetail
            );
            array_push($screenFinal, $arrFi);
        }
        return view('roles.create',compact('permission','screenFinal'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:roles,name',
            'permission' => 'required',
        ]);

        $role = Role::create(['name' => $request->input('name')]);
        $role->syncPermissions($request->input('permission'));

        return redirect()->route('roles.index')
                        ->with('success','El rol se ha creado');
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $role = Role::find($id);
        $permission = Permission::get();
        $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id",$id)
            ->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')
            ->all();

        $screen = array();
        foreach($permission as $valor){
            $validar = false;
            foreach($screen as $x){
                if ($x == $valor->screen) {
                    $validar = true;
                }
            }

            if (!$validar) {
                array_push($screen, $valor->screen);
            }
        }
        $screenFinal = array();
        $validarFinal = true;
        foreach($screen as $x){
            $screenDetail = array();
            $validarFinal = false;
            foreach($permission as $valor){
                if ($x == $valor->screen) {
                    $validar = true;
                    $arrech = array(
                        'name' => $valor->name,
                        'id' => $valor->id
                    );
                    array_push($screenDetail, $arrech);
                }
            }
            $arrFi = array(
                'name' => $x,
                'details' => $screenDetail
            );
            array_push($screenFinal, $arrFi);
        }


        return view('roles.show',compact('role','rolePermissions','screenFinal'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $role = Role::find($id);
        $permission = Permission::get();
        $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id",$id)
            ->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')
            ->all();


        $screen = array();
        foreach($permission as $valor){
            $validar = false;
            foreach($screen as $x){
                if ($x == $valor->screen) {
                    $validar = true;
                }
            }

            if (!$validar) {
                array_push($screen, $valor->screen);
            }
        }
        $screenFinal = array();
        $validarFinal = true;
        foreach($screen as $x){
            $screenDetail = array();
            $validarFinal = false;
            foreach($permission as $valor){
                if ($x == $valor->screen) {
                    $validar = true;
                    $arrech = array(
                        'name' => $valor->name,
                        'id' => $valor->id
                    );
                    array_push($screenDetail, $arrech);
                }
            }
            $arrFi = array(
                'name' => $x,
                'details' => $screenDetail
            );
            array_push($screenFinal, $arrFi);
        }

        return view('roles.edit',compact('role','permission','rolePermissions','screenFinal'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'permission' => 'required',
        ]);

        $role = Role::find($id);
        $role->name = $request->input('name');
        $role->save();

        $role->syncPermissions($request->input('permission'));

        return redirect()->route('roles.index')
                        ->with('success','El rol se ha actualizado correctamente');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if($id == 1 OR $id == 2 OR $id == 3 OR $id == 6)

        return redirect()->route('roles.index')
                        ->with('warning','Los roles Distribuidor, Vendedor, Cajero y Administrador no pueden ser eliminados');
       else{
        DB::table("roles")->where('id',$id)->delete();
        return redirect()->route('roles.index')
                        ->with('success','El rol ha sido borrao sactifactoriamente');

       }
    }
}
