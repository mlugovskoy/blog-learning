<?php

namespace App\Http\Controllers;

use App\Http\Resources\CommentResource;
use App\Http\Resources\PostResource;
use App\Models\Comment;
use App\Models\Post;
use App\Policies\PostPolicy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function index()
    {
        return inertia("posts/Index", [
            'posts' => PostResource::collection(Post::query()->with('user')->latest()->latest('id')->paginate())
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Gate::authorize('create', Post::class);

        return inertia("posts/Create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'min:10', 'max:120'],
            'slug' => ['string', 'min:10', 'max:160'],
            'body' => ['required', 'string', 'min:30', 'max:10000']
        ]);

        $post = Post::query()->create([
            ...$data,
            'slug'=> Str::slug($data['title']),
            'user_id' => $request->user()->id,
        ]);

        $post->user()->associate($request->user())->save();

        return redirect($post->showRoute());
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Post $post)
    {
        if (!Str::contains($post->showRoute(), $request->path())) {
            return redirect($post->showRoute($request->query()));
        }

        $post->load('user');

        return inertia('posts/Show', [
            'post' => fn() => PostResource::make($post),
            'comments' => fn() => CommentResource::collection(
                $post->comments()->with('user')->latest()->latest('id')->paginate(10)
            )
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
