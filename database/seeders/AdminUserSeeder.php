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


        // Create new admin
        User::create([
            'name' => 'System Administrator',
            'email' => 'PWDadmin@alaminoscity.gov.ph',
            'password' => Hash::make('ADMIN_PWD_USER123'),
            'role' => 'admin',
            'phone' => '09123456789',
            'address' => 'Alaminos City Hall',
            'email_verified_at' => now(),
        ]);

        $this->command->info('New admin user created!');
        $this->command->info('Email: PWDadmin@alaminoscity.gov.ph');
        $this->command->info('Password: ADMIN_PWD_USER123');
    }
}
