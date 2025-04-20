<?php

use App\Http\Resources\PostResource;
use App\Models\Post;
use Inertia\Testing\AssertableInertia as Assert;

use function Pest\Laravel\get;

it('can show a post', function () {
    $post = Post::factory()->create();

    get(route('posts.show', $post))
        ->assertComponent('posts/Show');
});

it('passes a post to the view', function () {
    $post = Post::factory()->create();

    $post->load('user');

    get(route('posts.show', $post))
        ->assertHasResource('post', PostResource::make($post));
});
