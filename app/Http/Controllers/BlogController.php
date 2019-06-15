<?php

namespace App\Http\Controllers;

use App\Models\Post;

class BlogController
{
    public function __invoke()
    {
        $posts = Post::query()
            ->published()
            ->blogContent()
            ->simplePaginate(20);

        return view('blog.index', compact('posts'));
    }
}
