<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Tag;

class TagController
{
    public function index()
    {
        return view('tags.index', [
            'tags' => Tag::all(),
        ]);
    }

    public function show(Tag $tag)
    {
        $posts = Post::query()
            ->published()
            ->withAnyTags([$tag])
            ->simplePaginate(20);

        return view('tags.show', [
            'tag' => $tag,
            'posts' => $posts,
        ]);
    }
}
