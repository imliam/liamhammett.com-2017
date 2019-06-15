<?php

return [

    'feeds' => [
        'main' => [
            'url' => '/feed',
            'title' => 'Liam Hammett - All blog posts and tips',
            'items' => \App\Models\Post::class . '@getFeedItems',
        ],

        'blog' => [
            'url' => '/feed/blog',
            'title' => 'Liam Hammett - All blog posts',
            'items' => \App\Models\Post::class . '@getBlogContentFeedItems',
        ],
    ],

];
