<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderStatusSeeder extends Seeder
{
    public function run()
    {
        DB::table('order_statuses')->insert([
            ['id' => Str::uuid(), 'name' => 'confirmed'],
            ['id' => Str::uuid(), 'name' => 'preparing'],
            ['id' => Str::uuid(), 'name' => 'delivered']
        ]);
    }
}