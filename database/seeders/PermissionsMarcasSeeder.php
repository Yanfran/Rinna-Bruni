<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionsMarcasSeeder extends Seeder
{

    protected $permissions = [
        //Marcas
        'marcas-list',
        'marcas-create',
        'marcas-edit',
        'marcas-delete',
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
