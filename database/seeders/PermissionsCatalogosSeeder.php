<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionsCatalogosSeeder extends Seeder
{

    protected $permissions = [
        //Lineas
        'lineas-list',
        'lineas-create',
        'lineas-edit',
        'lineas-delete',
        //Temporadas
        'temporadas-list',
        'temporadas-create',
        'temporadas-edit',
        'temporadas-delete',
        //Clasificacion
        'clasificacion-list',
        'clasificacion-create',
        'clasificacion-edit',
        'clasificacion-delete',
        //Catalogos
        'catalogos-list',
        'catalogos-create',
        'catalogos-edit',
        'catalogos-delete',
     ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach ($this->permissions as $permission)
        {
            $screen = explode( '-', $permission );
            Permission::create([
                'name' => $permission,
                'screen' => $screen[0],
            ]);
        }
    }
}
