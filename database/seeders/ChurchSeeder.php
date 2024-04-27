<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ChurchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('churches')->insert([
            ['name' => 'Grace Church', 'description' => 'Located in downtown, focused on community services.'],
            ['name' => 'Sunrise Chapel', 'description' => 'A small community chapel on the east side.'],
            ['name' => 'Mountain View Church', 'description' => 'Offering great views and uplifting sermons.'],
            ['name' => 'Riverbank Church', 'description' => 'Family-friendly church near the river.'],
        ]);
    }
}
