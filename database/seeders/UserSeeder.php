<?php
namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::create([
            'name'              => 'Admin CharityHub',
            'email'             => 'admin@charityhub.com',
            'password'          => Hash::make('password'),
            'role'              => 'admin',
            'email_verified_at' => now(),
        ]);

        // Donors
        $donors = [
            ['name' => 'Ahmed Mohamed',    'email' => 'ahmed@example.com'],
            ['name' => 'Sara Hassan',      'email' => 'sara@example.com'],
            ['name' => 'Omar Khalil',      'email' => 'omar@example.com'],
            ['name' => 'Nour El-Din',      'email' => 'nour@example.com'],
            ['name' => 'Layla Ibrahim',    'email' => 'layla@example.com'],
            ['name' => 'Khaled Mansour',   'email' => 'khaled@example.com'],
            ['name' => 'Hana Youssef',     'email' => 'hana@example.com'],
            ['name' => 'Mostafa Ali',      'email' => 'mostafa@example.com'],
            ['name' => 'Dina Farouk',      'email' => 'dina@example.com'],
            ['name' => 'Youssef Nasser',   'email' => 'youssef@example.com'],
        ];

        foreach ($donors as $donor) {
            User::create([
                'name'              => $donor['name'],
                'email'             => $donor['email'],
                'password'          => Hash::make('password'),
                'role'              => 'donor',
                'email_verified_at' => now(),
            ]);
        }

        // Volunteers
        $volunteers = [
            ['name' => 'Mariam Saad',     'email' => 'mariam@example.com'],
            ['name' => 'Tarek Adel',      'email' => 'tarek@example.com'],
            ['name' => 'Rania Fouad',     'email' => 'rania@example.com'],
            ['name' => 'Hassan Zaki',     'email' => 'hassan@example.com'],
            ['name' => 'Fatma Saleh',     'email' => 'fatma@example.com'],
        ];

        foreach ($volunteers as $v) {
            User::create([
                'name'              => $v['name'],
                'email'             => $v['email'],
                'password'          => Hash::make('password'),
                'role'              => 'volunteer',
                'email_verified_at' => now(),
            ]);
        }
    }
}