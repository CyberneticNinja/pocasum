<?php

namespace Database\Seeders;

use App\Models\GroupLeader;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Group;
use Illuminate\Support\Facades\Hash;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
class GroupUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //roles
        $roleUser = Role::create(['name' => 'user']);
        $roleGroupLeader = Role::create(['name' => 'group-leader']);
        $roleAdmin = Role::create(['name' => 'admin']);

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

        $users = User::orderBy('id', 'asc')
            ->take(20)
            ->get();

        foreach ($users as $user)
        {
            $user->assignRole($roleUser);
        }

        DB::table('group_user')->insert($uniqueEntries);

        // Fetch the last 16 users
        $lastSixteenUsers = User::orderBy('id', 'desc')->take(16)->get();
        $groups = Group::all();

        // We assume there are enough groups; adjust logic if there might be more groups than users
        foreach ($groups as $index => $group) {
            if (isset($lastSixteenUsers[$index])) {
                $user = $lastSixteenUsers[$index];

                // Assign the role if not already assigned
                if (!$user->hasRole('group-leader')) {
                    $user->assignRole($roleGroupLeader);
                }

                // Create or update GroupLeader entry
                GroupLeader::updateOrCreate(
                    ['group_id' => $group->id],
                    ['user_id' => $user->id]
                );

                // Assign this user to this group in group_user pivot table
                $group->users()->syncWithoutDetaching([$user->id]);
            }
        }

        DB::table('users')->insert([
            ['name' => 'Gabriel Grey', 'email' => 'gabriel.grey@example.com', 'password' => Hash::make('password')],
        ]);

        $lastUser = User::where('email', 'gabriel.grey@example.com')->first();
        $lastUser->assignRole($roleAdmin);
    }
}
