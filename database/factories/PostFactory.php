<?php

namespace Database\Factories;

use App\Models\Topic;
use App\Models\User;
use App\Support\PostFixtures;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use SplFileInfo;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{

    public function definition(): array
    {
        $title = str(fake()->sentence())->beforeLast('.')->title();

        return [
            'user_id' => User::factory(),
            'topic_id' => Topic::factory(),
            'title' => $title,
            'slug' => Str::slug($title),
            'likes_count' => 0,
            'body' => Collection::times(4, fn() => fake()->realText(1250))->join(PHP_EOL . PHP_EOL)
        ];
    }

    public function withFixture()
    {
        return $this->sequence(...PostFixtures::getFixtures());
    }


}
