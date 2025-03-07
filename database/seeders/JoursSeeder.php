<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class JoursSeeder extends Seeder
{
    public function run()
    {
        $jours = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi'];

        foreach ($jours as $jour) {
            DB::table('jours')->insert([
                'id' => Str::uuid(),
                'nom' => $jour,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}
