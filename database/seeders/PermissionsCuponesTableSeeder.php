<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use DB;

class PermissionsCuponesTableSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            [
                'name' => 'cupones-menu',
                'guard_name' => 'web',
                'created_at' => Carbon::parse('2023-05-05 11:48:59'),
                'updated_at' => Carbon::parse('2023-05-05 11:48:59'),
                'screen'     => 'cupones',
            ],
            // Puedes agregar más registros aquí si lo deseas
        ];

        DB::table('permissions')->insert($permissions);
    }
}
