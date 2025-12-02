<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $usersData = [
            [
                'firstname' => 'Admin',
                'middlename' => 'A',
                'lastname' => 'User',
                'phone' => '09171234567',
                'email' => 'admin@example.com',
                'address' => '123 Admin Street',
                'bio' => 'System administrator',
                'is_active' => true,
                'password' => 'password', 
                'role' => 'administrator',
            ],
            [
                'firstname' => 'Project',
                'middlename' => 'M',
                'lastname' => 'Manager',
                'phone' => '09181234567',
                'email' => 'pm@example.com',
                'address' => '456 Manager Ave',
                'bio' => 'Project Manager',
                'is_active' => true,
                'password' => 'password',
                'role' => 'project_manager',
            ],
            [
                'firstname' => 'Regular',
                'middlename' => 'R',
                'lastname' => 'Member',
                'phone' => '09191234567',
                'email' => 'member@example.com',
                'address' => '789 Member Road',
                'bio' => 'Member of the team',
                'is_active' => true,
                'password' => 'password',
                'role' => 'member',
            ],
            [
                'firstname' => 'Client',
                'middlename' => 'C',
                'lastname' => 'User',
                'phone' => '09201234567',
                'email' => 'client@example.com',
                'address' => '321 Client Blvd',
                'bio' => 'Client user',
                'is_active' => true,
                'password' => 'password',
                'role' => 'client',
            ],
        ];

        foreach ($usersData as $userData) {
            $userData['password'] = Hash::make($userData['password']);
            $roleName = $userData['role'];
            unset($userData['role']);
            $user = User::create($userData);
            $user->assignRole($roleName);
        }
    }
}
