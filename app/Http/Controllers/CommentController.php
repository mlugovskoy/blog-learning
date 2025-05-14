<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Session;

class CommentController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Post $post)
    {
        $data = $request->validate(['body' => ['required', 'string', 'max:2500']]);

        Comment::query()->create([
            ...$data,
            'post_id' => $post->id,
            'user_id' => $request->user()->id,
        ]);

        Session::flash('flash_message', 'Comment added');

        return to_route('posts.show', $post);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Comment $comment)
    {
        Gate::authorize('update', $comment);

        $data = $request->validate(['body' => ['required', 'string', 'max:2500']]);

        $comment->update($data);

        Session::flash('flash_message', 'Comment update');

        return to_route('posts.show', ['post' => $comment->post_id, 'page' => $request->query('page')]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Comment $comment)
    {
        Gate::authorize('delete', $comment);

        $comment->delete();

        Session::flash('flash_message', 'Comment delete');

        return to_route('posts.show', $comment->post_id);
    }
}
