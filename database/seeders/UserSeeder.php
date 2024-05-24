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
            ['name' => 'Ricky James', 'email' => 'rjames@example.com', 'password' => Hash::make('password')],
            ['name' => 'Jane Smith', 'email' => 'jane.smith@example.com', 'password' => Hash::make('password')],
            ['name' => 'Alex Johnson', 'email' => 'alex.johnson@example.com', 'password' => Hash::make('password')],
            ['name' => 'Bob Brown', 'email' => 'bob.brown@example.com', 'password' => Hash::make('password')],
            ['name' => 'Charlie Davis', 'email' => 'charlie.davis@example.com', 'password' => Hash::make('password')],
            ['name' => 'Diana Ross', 'email' => 'diana.ross@example.com', 'password' => Hash::make('password')],
            ['name' => 'Edwin Norton', 'email' => 'edwin.norton@example.com', 'password' => Hash::make('password')],
            ['name' => 'Fiona Apple', 'email' => 'fiona.apple@example.com', 'password' => Hash::make('password')],
            ['name' => 'George Clooney', 'email' => 'george.clooney@example.com', 'password' => Hash::make('password')],
            ['name' => 'Hannah Montana', 'email' => 'hannah.montana@example.com', 'password' => Hash::make('password')],
            ['name' => 'Ian Somerhalder', 'email' => 'ian.somerhalder@example.com', 'password' => Hash::make('password')],
            ['name' => 'Jessica Alba', 'email' => 'jessica.alba@example.com', 'password' => Hash::make('password')],
            ['name' => 'Chris Reese', 'email' => 'Chrids.reese@example.com', 'password' => Hash::make('password')],
            ['name' => 'Diana', 'email' => 'diana.palmer@example.com', 'password' => Hash::make('password')],
            ['name' => 'Mike Ross', 'email' => 'mike.ross@example.com', 'password' => Hash::make('password')],
            ['name' => 'Nina Dobrev', 'email' => 'nina.dobrev@example.com', 'password' => Hash::make('password')]
        ]);
    }
}
