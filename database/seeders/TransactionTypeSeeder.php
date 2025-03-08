<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TransactionTypeSeeder extends Seeder
{
    public function run()
    {
        DB::table('transaction_types')->insert([
            ['id' => Str::uuid(), 'name' => 'deposit'],
            ['id' => Str::uuid(), 'name' => 'withdrawal'],
            ['id' => Str::uuid(), 'name' => 'payment']
        ]);
    }
}