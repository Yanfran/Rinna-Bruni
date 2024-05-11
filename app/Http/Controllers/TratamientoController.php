<?php
namespace App\Http\Controllers;
set_time_limit(6000);
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Pais;
use App\Models\Estados;
use App\Models\Municipios;
use App\Models\Sepomex;
use App\Models\Localidads;
use Spatie\Permission\Models\Role;
use DB;
use Hash;
use Illuminate\Support\Arr;

class TratamientoController extends Controller
{

    public function index()
    {


       /* $estados = Sepomex::select('idEstado', 'estado')
            ->groupBy('idEstado', 'estado')
            ->orderBy('idEstado', 'ASC')
            ->get();*/


       /* foreach ($estados as $r) {

            $buscar = Estados::where('idEstado', $r->idEstado)->first();

           /* if (empty($buscar)) {

                $estado = new Estados();
                $estado->idEstado = $r->idEstado;
                $estado->nombre = $r->estado;
                $estado->pais_id = 1;
                $estado->status = 1;
                $estado->push();
            }
        }*/


        /*  $estados = Estados::all();
       foreach($estados as $estado){



        $municipios = Sepomex::select('idEstado', 'idMunicipio', 'municipio')
        ->where('idEstado', $estado->idEstado)
        ->groupBy('idEstado', 'idMunicipio', 'municipio')
        ->orderBy('municipio', 'ASC')
        ->get();

        //dd($municipios);

        foreach($municipios as $municipio){


            //dd($municipio->municipio);
            $buscarmun = Municipios::where('estado_id', $estado->id)
            ->where('nombre', $municipio->municipio)
            ->where('idMunicipio', $municipio->idMunicipio)
            ->first();

            if(empty($buscarmun)){

                $mun = new Municipios();
                $mun->idMunicipio = $municipio->idMunicipio;
                $mun->nombre = $municipio->municipio;
                $mun->estado_id = $estado->id;
                $mun->pais_id = 1;
                $mun->status = 1;
                $mun->push();

            }

        }


       }*/


        $estados = Estados::where('id', '>=', 29)->where('id', '<=', 32)->get();
        foreach ($estados as $estado) {

            $municipios = Municipios::where('estado_id', $estado->id)->get();

            foreach ($municipios  as $municipio) {

                $localidad = Sepomex::where('idEstado', $estado->idEstado)
                    ->where('idMunicipio', $municipio->idMunicipio)
                    ->where('municipio', $municipio->nombre)
                    ->orderBy('municipio', 'ASC')
                    ->get();

                foreach ($localidad as $key) {

                    $buscarloc = Localidads::where('estado_id', $estado->id)
                        ->where('municipio_id', $municipio->id)
                        ->where('cp', $key->cp)
                        ->where('nombre', $key->asentamiento)
                        ->where('zona', $key->zona)
                        ->first();

                    if (empty($buscarloc)) {

                        $mun = new Localidads();
                        $mun->nombre = $key->asentamiento;
                        $mun->ciudad = $key->ciudad;
                        $mun->tipo = $key->tipo;
                        $mun->zona = $key->zona;
                        $mun->cp = $key->cp;
                        $mun->estado_id = $estado->id;
                        $mun->municipio_id = $municipio->id;
                        $mun->pais_id = 1;
                        $mun->estatus = 1;
                        $mun->push();
                    }
                }
            }
        }
    }
}
