<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RoleSeeder extends Seeder
{
    public function run()
    {
        DB::table('roles')->insert([
            ['id' => Str::uuid(), 'name' => 'admin'],
            ['id' => Str::uuid(), 'name' => 'employee'],
            ['id' => Str::uuid(), 'name' => 'restaurant_owner']
        ]);
    }
}