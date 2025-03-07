<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class StatutsCommandesSeeder extends Seeder
{
    public function run()
    {
        $statuts = ['Confirmée', 'En préparation', 'Livrée'];

        foreach ($statuts as $statut) {
            DB::table('statuts_commandes')->insert([
                'id' => Str::uuid(),
                'nom' => $statut,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}
