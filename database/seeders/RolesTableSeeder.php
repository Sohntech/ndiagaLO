<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('roles')->insert([
            ['id' => 1, 'libelle' => 'ADMIN'],
            ['id' => 2, 'libelle' => 'MANAGER'],
            ['id' => 3, 'libelle' => 'CM'],
            ['id' => 4, 'libelle' => 'COACH'],
            ['id' => 5, 'libelle' => 'APPRENANT'],
        ]);
    }
}
