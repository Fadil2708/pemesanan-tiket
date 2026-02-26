<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info('🌱 Starting database seeding...');
        $this->command->info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');

        $this->call([
            UserSeeder::class,          // Must be first - creates admin & customer users
            CategorySeeder::class,      // Creates film categories
            StudioSeeder::class,        // Creates cinema studios
            SeatSeeder::class,          // Creates seats for each studio
            FilmSeeder::class,          // Creates films with categories
            ShowtimeSeeder::class,      // Creates showtimes (auto-creates showtime_seats)
            OrderSeeder::class,         // Creates sample orders with tickets & payments
        ]);

        $this->command->info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        $this->command->info('✅ Database seeding completed successfully!');
        $this->command->info('');
        $this->command->info('📊 Summary:');
        $this->command->info('   Users: ' . \App\Models\User::count());
        $this->command->info('   Categories: ' . \App\Models\Category::count());
        $this->command->info('   Studios: ' . \App\Models\Studio::count());
        $this->command->info('   Seats: ' . \App\Models\Seat::count());
        $this->command->info('   Films: ' . \App\Models\Film::count());
        $this->command->info('   Showtimes: ' . \App\Models\Showtime::count());
        $this->command->info('   Orders: ' . \App\Models\Order::count());
        $this->command->info('');
        $this->command->info('🔐 Login Credentials:');
        $this->command->info('   Admin:');
        $this->command->info('   Email: admin@bioskopapp.com');
        $this->command->info('   Password: admin123');
        $this->command->info('');
        $this->command->info('   Customer:');
        $this->command->info('   Email: john@example.com');
        $this->command->info('   Password: password123');
        $this->command->info('');
    }
}
