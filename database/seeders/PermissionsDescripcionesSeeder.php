<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;

class PermissionsDescripcionesSeeder extends Seeder
{
    protected $permissions = [
        //Descripciones
        'descripciones-list',
        'descripciones-create',
        'descripciones-edit',
        'descripciones-delete',
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

            DB::table('permissions')->where('name', 'clasificacion-list')->delete();
            DB::table('permissions')->where('name', 'clasificacion-edit')->delete();
            DB::table('permissions')->where('name', 'clasificacion-create')->delete();
            DB::table('permissions')->where('name', 'clasificacion-delete')->delete();
        }
    }
}
