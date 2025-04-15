<?php

use Inertia\Testing\AssertableInertia as Assert;

use function Pest\Laravel\get;

it('should return the correct component', function () {
    get(route('posts.index'))
        ->assertInertia(fn(Assert $inertia) => $inertia
            ->component('posts/Index', true)
        );
});

it('passes posts to the view', function () {
    get(route('posts.index'))
        ->assertInertia(fn(Assert $inertia) => $inertia
            ->has('posts')
        );
});
