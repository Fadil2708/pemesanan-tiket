<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin Users
        $admins = [
            [
                'name' => 'Admin Utama',
                'email' => 'admin@bioskopapp.com',
                'phone' => '081234567890',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
            ],
            [
                'name' => 'Admin Bioskop',
                'email' => 'admin2@bioskopapp.com',
                'phone' => '081234567891',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
            ],
        ];

        foreach ($admins as $admin) {
            User::firstOrCreate(
                ['email' => $admin['email']],
                $admin
            );
        }

        // Create Customer Users
        $customers = [
            [
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'phone' => '081234567892',
                'password' => Hash::make('password123'),
                'role' => 'customer',
            ],
            [
                'name' => 'Jane Smith',
                'email' => 'jane@example.com',
                'phone' => '081234567893',
                'password' => Hash::make('password123'),
                'role' => 'customer',
            ],
            [
                'name' => 'Bob Wilson',
                'email' => 'bob@example.com',
                'phone' => '081234567894',
                'password' => Hash::make('password123'),
                'role' => 'customer',
            ],
            [
                'name' => 'Alice Brown',
                'email' => 'alice@example.com',
                'phone' => '081234567895',
                'password' => Hash::make('password123'),
                'role' => 'customer',
            ],
            [
                'name' => 'Charlie Davis',
                'email' => 'charlie@example.com',
                'phone' => '081234567896',
                'password' => Hash::make('password123'),
                'role' => 'customer',
            ],
        ];

        foreach ($customers as $customer) {
            User::firstOrCreate(
                ['email' => $customer['email']],
                $customer
            );
        }

        $this->command->info('✅ Users seeded successfully!');
        $this->command->info('   Admin: admin@bioskopapp.com / admin123');
        $this->command->info('   Customer: john@example.com / password123');
    }
}
