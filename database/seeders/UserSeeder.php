<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            ['name' => 'Super Admin',  'email' => 'superadmin@tokroti.com', 'role' => 'super_admin'],
            ['name' => 'Admin Staff',  'email' => 'adminstaff@tokroti.com', 'role' => 'admin_staff'],
            ['name' => 'Staff',        'email' => 'staff@tokroti.com',      'role' => 'staff'],
        ];

        foreach ($users as $data) {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name'               => $data['name'],
                    'password'           => Hash::make('password'),
                    'email_verified_at'  => now(),
                    'is_active'          => true,
                ]
            );

            $user->syncRoles([$data['role']]);
        }
    }
}
