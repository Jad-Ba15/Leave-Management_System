<?php
namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create Admin User
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'department_id' => 1, // HR
            'email_verified_at' => now(),
        ]);

        // Create Employee Users
        $employees = [
            ['name' => 'John Doe', 'email' => 'john@example.com', 'department_id' => 2],
            ['name' => 'Jane Smith', 'email' => 'jane@example.com', 'department_id' => 3],
            ['name' => 'Mike Johnson', 'email' => 'mike@example.com', 'department_id' => 4],
        ];

        foreach ($employees as $employee) {
            User::create([
                'name' => $employee['name'],
                'email' => $employee['email'],
                'password' => Hash::make('password'),
                'role' => 'employee',
                'department_id' => $employee['department_id'],
                'email_verified_at' => now(),
            ]);
        }
    }
}