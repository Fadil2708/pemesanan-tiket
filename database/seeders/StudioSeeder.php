<?php

namespace Database\Seeders;

use App\Models\Studio;
use Illuminate\Database\Seeder;

class StudioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $studios = [
            ['name' => 'Studio 1', 'type' => '2D'],
            ['name' => 'Studio 2', 'type' => '2D'],
            ['name' => 'Studio 3', 'type' => '3D'],
            ['name' => 'Studio 4', 'type' => 'IMAX'],
            ['name' => 'Studio 5', 'type' => 'Dolby'],
            ['name' => 'Studio 6', 'type' => '2D'],
            ['name' => 'Studio 7', 'type' => '3D'],
            ['name' => 'Studio 8', 'type' => 'IMAX'],
        ];

        foreach ($studios as $studio) {
            Studio::firstOrCreate(
                ['name' => $studio['name']],
                ['type' => $studio['type']]
            );
        }

        $this->command->info('✅ Studios seeded successfully!');
    }
}
