<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            ['name' => 'John Doe', 'email' => 'john.doe@example.com', 'password' => Hash::make('password')],
            ['name' => 'Jane Doe', 'email' => 'jane.doe@example.com', 'password' => Hash::make('password')],
            ['name' => 'Alice Johnson', 'email' => 'alice.johnson@example.com', 'password' => Hash::make('password')],
            ['name' => 'Bob Smith', 'email' => 'bob.smith@example.com', 'password' => Hash::make('password')],
            ['name' => 'Charlie Brown', 'email' => 'charlie.brown@example.com', 'password' => Hash::make('password')],
            ['name' => 'Daisy Hill', 'email' => 'daisy.hill@example.com', 'password' => Hash::make('password')],
            ['name' => 'Edward Norton', 'email' => 'edward.norton@example.com', 'password' => Hash::make('password')],
            ['name' => 'Fiona Gallagher', 'email' => 'fiona.gallagher@example.com', 'password' => Hash::make('password')],
            ['name' => 'George King', 'email' => 'george.king@example.com', 'password' => Hash::make('password')],
            ['name' => 'Hannah Abbott', 'email' => 'hannah.abbott@example.com', 'password' => Hash::make('password')],
            ['name' => 'Ian Malcolm', 'email' => 'ian.malcolm@example.com', 'password' => Hash::make('password')],
            ['name' => 'Jessica Jones', 'email' => 'jessica.jones@example.com', 'password' => Hash::make('password')],
            ['name' => 'Kyle Reese', 'email' => 'kyle.reese@example.com', 'password' => Hash::make('password')],
            ['name' => 'Laura Palmer', 'email' => 'laura.palmer@example.com', 'password' => Hash::make('password')],
            ['name' => 'Mike Wheeler', 'email' => 'mike.wheeler@example.com', 'password' => Hash::make('password')],
            ['name' => 'Nina Zenik', 'email' => 'nina.zenik@example.com', 'password' => Hash::make('password')],
            ['name' => 'Oscar Wilde', 'email' => 'oscar.wilde@example.com', 'password' => Hash::make('password')],
            ['name' => 'Piper Chapman', 'email' => 'piper.chapman@example.com', 'password' => Hash::make('password')],
            ['name' => 'Quentin Coldwater', 'email' => 'quentin.coldwater@example.com', 'password' => Hash::make('password')],
            ['name' => 'Rachel Green', 'email' => 'rachel.green@example.com', 'password' => Hash::make('password')],
        ]);
    }
}
