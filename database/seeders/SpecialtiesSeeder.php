<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class SpecialtiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('specialties')->insert([
        	['name' => 'Médico General'],
        	['name' => 'Pediatría'],
        	['name' => 'Ginecología'],
        	['name' => 'Cardiología'],
        	['name' => 'Obstetricía'],
        	['name' => 'Otorrinolaringolía'],
            ['name' => 'Urgencias']
        ]);
    }
}
