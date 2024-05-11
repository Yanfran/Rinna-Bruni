<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Empresas;

class CreateEmpresaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Empresas::create([
            'nombre' => 'Rinna Bruni',
            'logo' => 'ripsqnS9_400x400.jpg',
            'email' => 'admin@localhost.com',
            'telefono_2' => '52 (656) 898-989',
            'telefono_1' => '58 (291) 641-5253',
            'colorPrimario' => '#e36262',
            'colorSecundario' => 'rgba(153,153,153,0.89)',
            'direccion' => 'Guadalajara Mexico',
            'estatus' => 1,
            'inactividad' => '3',
            'mp_public_key' => 'APP_USR-b8323856-3a24-415c-9221-8ca2e35f40ac',
            'mp_access_token' => 'APP_USR-3203299477526970-120618-e3a1748823eea78605544b305ed0ac00-1579846367',
            'costo_paqueteria' => 180.00
        ]);
    }
}
