<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Film;
use Illuminate\Database\Seeder;

class FilmSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $films = [
            [
                'title' => 'Avatar: The Way of Water',
                'description' => 'Jake Sully lives with his newfound family formed on the extrasolar moon Pandora. Once a familiar threat returns to finish what was previously started, Jake must work with Neytiri and the army of the Na\'vi race to protect their home.',
                'duration' => 192,
                'age_rating' => 'PG-13',
                'release_date' => '2024-12-14',
                'poster' => 'posters/avatar-way-of-water.jpg',
                'categories' => ['Action', 'Adventure', 'Sci-Fi'],
            ],
            [
                'title' => 'Avengers: Secret Wars',
                'description' => 'The Avengers face their ultimate challenge as they confront a threat that spans across multiple universes. The fate of reality itself hangs in the balance.',
                'duration' => 165,
                'age_rating' => 'PG-13',
                'release_date' => '2025-05-02',
                'poster' => 'posters/avengers-secret-wars.jpg',
                'categories' => ['Action', 'Adventure', 'Sci-Fi'],
            ],
            [
                'title' => 'The Batman 2',
                'description' => 'Batman continues his war on crime in Gotham City. A new villain emerges, forcing the Dark Knight to confront his own demons while protecting the innocent.',
                'duration' => 155,
                'age_rating' => 'PG-13',
                'release_date' => '2025-10-03',
                'poster' => 'posters/the-batman-2.jpg',
                'categories' => ['Action', 'Crime', 'Drama'],
            ],
            [
                'title' => 'Dune: Part Three',
                'description' => 'Paul Atreides continues his mythic journey as he leads the Fremen in their fight against the oppressive forces of the Empire.',
                'duration' => 168,
                'age_rating' => 'PG-13',
                'release_date' => '2025-11-03',
                'poster' => 'posters/dune-part-three.jpg',
                'categories' => ['Sci-Fi', 'Adventure', 'Drama'],
            ],
            [
                'title' => 'Oppenheimer',
                'description' => 'The story of American scientist J. Robert Oppenheimer and his role in the development of the atomic bomb during World War II.',
                'duration' => 180,
                'age_rating' => 'R',
                'release_date' => '2024-07-21',
                'poster' => 'posters/oppenheimer.jpg',
                'categories' => ['Drama', 'Thriller'],
            ],
            [
                'title' => 'Barbie',
                'description' => 'Barbie suffers a crisis that leads her to question her world and her existence. She embarks on a journey of self-discovery in the real world.',
                'duration' => 114,
                'age_rating' => 'PG-13',
                'release_date' => '2024-07-21',
                'poster' => 'posters/barbie.jpg',
                'categories' => ['Comedy', 'Adventure', 'Fantasy'],
            ],
            [
                'title' => 'Spider-Man: Across the Spider-Verse',
                'description' => 'Miles Morales catapults across the Multiverse, where he encounters a team of Spider-People charged with protecting its very existence.',
                'duration' => 140,
                'age_rating' => 'PG',
                'release_date' => '2024-06-02',
                'poster' => 'posters/spider-verse.jpg',
                'categories' => ['Animation', 'Action', 'Adventure'],
            ],
            [
                'title' => 'John Wick: Chapter 5',
                'description' => 'John Wick returns for another high-octane adventure. After surviving his most impossible challenge yet, he must face his deadliest enemy.',
                'duration' => 145,
                'age_rating' => 'R',
                'release_date' => '2025-05-23',
                'poster' => 'posters/john-wick-5.jpg',
                'categories' => ['Action', 'Thriller', 'Crime'],
            ],
            [
                'title' => 'The Conjuring: Last Rites',
                'description' => 'Ed and Lorraine Warren take on one last case that will test their faith and courage against the most terrifying evil they have ever faced.',
                'duration' => 115,
                'age_rating' => 'R',
                'release_date' => '2025-09-05',
                'poster' => 'posters/the-conjuring-last-rites.jpg',
                'categories' => ['Horror', 'Thriller'],
            ],
            [
                'title' => 'Frozen 3',
                'description' => 'Elsa and Anna embark on a new adventure that will take them beyond the kingdom of Arendelle. A magical journey filled with wonder and danger.',
                'duration' => 105,
                'age_rating' => 'PG',
                'release_date' => '2025-11-27',
                'poster' => 'posters/frozen-3.jpg',
                'categories' => ['Animation', 'Adventure', 'Fantasy'],
            ],
            [
                'title' => 'Mission: Impossible 8',
                'description' => 'Ethan Hunt and his IMF team return for their most dangerous mission yet. Racing against time to prevent a global catastrophe.',
                'duration' => 158,
                'age_rating' => 'PG-13',
                'release_date' => '2025-05-23',
                'poster' => 'posters/mission-impossible-8.jpg',
                'categories' => ['Action', 'Adventure', 'Thriller'],
            ],
            [
                'title' => 'A Quiet Place: Day One',
                'description' => 'Experience the day the world went quiet. A prequel to the hit horror franchise, showing the beginning of the alien invasion.',
                'duration' => 100,
                'age_rating' => 'PG-13',
                'release_date' => '2024-06-28',
                'poster' => 'posters/a-quiet-place-day-one.jpg',
                'categories' => ['Horror', 'Thriller', 'Sci-Fi'],
            ],
            [
                'title' => 'Inside Out 2',
                'description' => 'Riley enters puberty, and her emotions face new challenges. Joy, Sadness, Anger, Fear, and Disgust must navigate the complexities of growing up.',
                'duration' => 96,
                'age_rating' => 'PG',
                'release_date' => '2024-06-14',
                'poster' => 'posters/inside-out-2.jpg',
                'categories' => ['Animation', 'Comedy', 'Drama'],
            ],
            [
                'title' => 'Deadpool 3',
                'description' => 'Wade Wilson returns with his irreverent humor and deadly skills. This time, he teams up with Wolverine for an adventure across the multiverse.',
                'duration' => 127,
                'age_rating' => 'R',
                'release_date' => '2024-07-26',
                'poster' => 'posters/deadpool-3.jpg',
                'categories' => ['Action', 'Comedy', 'Sci-Fi'],
            ],
            [
                'title' => 'Gladiator 2',
                'description' => 'Years after witnessing the death of Maximus, Lucius must enter the Colosseum when his home is threatened by tyrannical forces.',
                'duration' => 148,
                'age_rating' => 'R',
                'release_date' => '2024-11-22',
                'poster' => 'posters/gladiator-2.jpg',
                'categories' => ['Action', 'Drama', 'Adventure'],
            ],
        ];

        foreach ($films as $filmData) {
            $categories = $filmData['categories'];
            unset($filmData['categories']);

            $film = Film::create($filmData);

            // Attach categories
            foreach ($categories as $categoryName) {
                $category = Category::where('name', $categoryName)->first();
                if ($category) {
                    $film->categories()->attach($category->id);
                }
            }
        }

        $this->command->info('✅ Films seeded successfully! (' . Film::count() . ' films created)');
    }
}
