<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            'role',
            'product',
            'user',
            'pais',
            'localidad',
            'municipios',
            'estados',
            'tiendas',
            'cupons',
            'distribuidores',
            'vendedores',
            'pedidos',
            'existencias',
            'slider',
            'ajustes',
            'lineas',
            'temporadas',
            'catalogos',
            'marcas',
            'descripciones'
        ];

        foreach ($permissions as $permission) {
            Permission::create([
                'name' => $permission . '-list',
                'screen' => $permission
            ]);

            Permission::create([
                'name' => $permission . '-create',
                'screen' => $permission
            ]);

            Permission::create([
                'name' => $permission . '-edit',
                'screen' => $permission
            ]);

            Permission::create([
                'name' => $permission . '-delete',
                'screen' => $permission
            ]);
        }

        Permission::create([
            'name' => 'mis-cupones-list',
            'screen' => 'mis-cupones'
        ]);
    }
}