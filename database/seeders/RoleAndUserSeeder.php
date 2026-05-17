<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class RoleAndUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'patient']);

        $adminUser = User::create([
            'name' => 'Admin Podiatrix',
            'email' => 'admin@podiatrix.com',
            'password' => Hash::make('admin123'),
        ]);

        $patientUser = User::create([
            'name' => 'Patient User',
            'email' => 'user@podiatrix.com',
            'password' => Hash::make('user1234'),
        ]);

        $adminUser->assignRole('admin');
        $patientUser->assignRole('patient');
    }
}
