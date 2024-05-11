<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Localidads;
use Illuminate\Support\Facades\DB;

class addZeroToCpOnLocalidadsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $localidades = Localidads::where(DB::raw('LENGTH(cp)'), 4)->get();

        foreach ($localidades as $localidad) {
            $localidad->cp = '0' . $localidad->cp;
            $localidad->save();
        }
    }
}
