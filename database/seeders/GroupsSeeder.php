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
            ['name' => 'Youth Fellowship', 'church_id' => 1],
            ['name' => 'Young couples Ministry', 'church_id' => 1],
            ['name' => 'Older parents group', 'church_id' => 2],
            ['name' => 'Sunday School', 'church_id' => 2],
            ['name' => 'Choir', 'church_id' => 3],
            ['name' => 'Bible Study Group', 'church_id' => 3],
            ['name' => 'Mission Outreach', 'church_id' => 4],
            ['name' => 'Prayer Team', 'church_id' => 4],
        ]);
    }
}
