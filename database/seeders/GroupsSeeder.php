<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GroupsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('groups')->insert([
            ['name' => 'Youth Fellowship', 'church_id' => 1, 'color' => '#FF5733'],
            ['name' => 'Young couples Ministry', 'church_id' => 1, 'color' => '#4CAF50'],
            ['name' => 'Older parents group', 'church_id' => 2, 'color' => '#FFC107'],
            ['name' => 'Sunday School', 'church_id' => 2, 'color' => '#00BCD4'],
            ['name' => 'Choir', 'church_id' => 3, 'color' => '#9C27B0'],
            ['name' => 'Bible Study Group', 'church_id' => 3, 'color' => '#F44336'],
            ['name' => 'Mission Outreach', 'church_id' => 4, 'color' => '#3F51B5'],
            ['name' => 'Prayer Team', 'church_id' => 4, 'color' => '#FFEB3B'],
        ]);
    }
}
