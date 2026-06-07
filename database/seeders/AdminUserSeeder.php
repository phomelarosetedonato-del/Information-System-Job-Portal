<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {

        // Create default admin
        User::create([
            'name' => 'System Administrator',
            'email' => 'PWDadmin@alaminoscity.gov.ph',
            'password' => Hash::make('ADMIN_PWD_USER123'),
            'role' => 'admin',
            'phone' => '09123456789',
            'address' => 'Alaminos City Hall',
            'email_verified_at' => now(),
            'is_active' => true,
        ]);

        // Create new admin with requested email
        User::create([
            'name' => 'Admin User',
            'email' => '22ac0339_ms@psu.edu.ph',
            'password' => Hash::make('ADMIN_PWD_USER123'),
            'role' => 'admin',
            'phone' => null,
            'address' => null,
            'email_verified_at' => now(),
            'is_active' => true,
        ]);

        $this->command->info('New admin users created!');
        $this->command->info('Email: PWDadmin@alaminoscity.gov.ph | Password: ADMIN_PWD_USER123');
        $this->command->info('Email: 22ac0339_ms@psu.edu.ph | Password: ADMIN_PWD_USER123');
    }
}
