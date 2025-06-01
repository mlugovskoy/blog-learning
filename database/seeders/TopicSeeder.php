<?php

namespace Database\Seeders;

use App\Models\Topic;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TopicSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'slug' => 'general',
                'name' => 'General',
                'description' => 'Any and all things about films and movies.',
            ],
            [
                'slug' => 'reviews',
                'name' => 'Reviews',
                'description' => 'What\'s the census on that latest flick? Read all about it here!',
            ],
            [
                'slug' => 'questions',
                'name' => 'Questions',
                'description' => 'Didn\'t quite understand that one plot point? There are no stupid questions.',
            ],
            [
                'slug' => 'conspiracies',
                'name' => 'Conspiracies',
                'description' => 'Got a wild idea about how The Incredibles and Moster\'s Inc. are connection',
            ]
        ];

        Topic::query()->upsert($data, ['slug']);
    }
}
