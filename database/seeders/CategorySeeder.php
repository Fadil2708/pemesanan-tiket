<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Action'],
            ['name' => 'Drama'],
            ['name' => 'Comedy'],
            ['name' => 'Horror'],
            ['name' => 'Sci-Fi'],
            ['name' => 'Romance'],
            ['name' => 'Thriller'],
            ['name' => 'Animation'],
            ['name' => 'Documentary'],
            ['name' => 'Fantasy'],
            ['name' => 'Adventure'],
            ['name' => 'Crime'],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(['name' => $category['name']]);
        }

        $this->command->info('✅ Categories seeded successfully!');
    }
}
