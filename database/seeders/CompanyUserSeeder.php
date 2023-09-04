<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompanyUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 0; $i < 15; $i++) {
            DB::table('company_user')->insert([
                'user_id' => rand(1, 15),
                'company_id' => rand(1, 15),
            ]);
        }
    }
}
