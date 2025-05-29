<?php

namespace Database\Factories;

use App\Models\User;
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
    private static Collection $fixtures;

    public function definition(): array
    {
        $title = str(fake()->sentence())->beforeLast('.')->title();

        return [
            'user_id' => User::factory(),
            'title' => $title,
            'slug' => Str::slug($title),
            'body' => Collection::times(4, fn() => fake()->realText(1250))->join(PHP_EOL . PHP_EOL)
        ];
    }

    public function withFixture()
    {
        $posts = static::getFixtures()
            ->map(fn(string $contents) => str($contents)->explode("\n", 2))
            ->map(fn(Collection $parts) => [
                'title' => str($parts[0])->trim()->after('# '),
                'body' => str($parts[1])->trim()
            ]);

        return $this->sequence(...$posts);
    }

    private function getFixtures(): Collection
    {
        return self::$fixtures ??= collect(File::files(database_path('factories/articles')))
            ->map(fn(SplFileInfo $fileInfo) => $fileInfo->getContents());
    }
}
