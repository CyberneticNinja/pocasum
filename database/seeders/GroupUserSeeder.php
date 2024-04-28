<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GroupUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $usersCount = 20; // Total number of users
        $groupsCount = 16; // Total number of groups, adjust this as needed
        $totalEntries = 60; // Total number of group-user entries

        $entries = [];

        // Ensure each user is in at least one group
        for ($userId = 1; $userId <= $usersCount; $userId++) {
            $entries[] = ['group_id' => rand(1, $groupsCount), 'user_id' => $userId];
        }

        // Additional random assignments until reaching 50 entries
        while (count($entries) < $totalEntries) {
            $entries[] = [
                'group_id' => rand(1, $groupsCount),
                'user_id' => rand(1, $usersCount)
            ];
        }

        // Remove duplicates if any, assuming ('group_id', 'user_id') should be unique
        $uniqueEntries = array_unique($entries, SORT_REGULAR);

        // If duplicates removed caused less than required entries, add more
        while (count($uniqueEntries) < $totalEntries) {
            $additionalEntry = [
                'group_id' => rand(1, $groupsCount),
                'user_id' => rand(1, $usersCount)
            ];
            if (!in_array($additionalEntry, $uniqueEntries, true)) {
                $uniqueEntries[] = $additionalEntry;
            }
        }

        DB::table('group_user')->insert($uniqueEntries);
    }
}
