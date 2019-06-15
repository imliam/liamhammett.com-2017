@extends('layouts.app', [
    'title' => $post->title,
])

@section('content')
    @component('posts.partials.post', [
        'post' => $post,
        'class' => 'mb-8',
    ])
        @if ($post->isBlog() && $post->publish_date < now()->subYears(2))
            <div class="-mx-4 sm:mx-0 p-4 sm:p-6 md:p-8 bg-red-100 text-sm text-gray-700 rounded font-sans">
                <p class="font-extrabold leading-tight mb-4 text-black">
                    This is an old post!
                </p>
                <p>
                    This post was written over 2 years ago, so its content may not be completely up-to-date. Please take this into consideration when reading it.
                </p>
            </div>
        @endif

        {!! $post->formatted_text !!}

        @unless($post->isTweet())
            @if($post->external_url)
                <p class="mt-6">
                    <a href="{{ $post->external_url }}">Read more&hellip;</a>
                    <span class="text-xs text-gray-700">[{{ $post->external_url_host }}]</span>
                </p>
            @endif
        @endunless
    @endcomponent

    @if($post->styles)
        <style>{!! $post->styles !!}</style>
    @endif

    @if($post->scripts)
        <script>{!! $post->scripts !!}</script>
    @endif

    <footer class="flex flex-row my-16 items-center">
        <img src="https://res.cloudinary.com/liam/image/upload/v1560564187/liamhammett.com/avatar.jpg" class="rounded-full mr-4 w-16 h-16" alt="Photo of Liam Hammett">
        <div>
            <p class="text-gray-900">Liam Hammett</p>
            <p class="text-sm text-gray-700">Full-stack software developer that loves working with PHP, Laravel and Vue.</>
        </div>
    </footer>

    @component('components.lazy')
        @include('posts.partials.disqus')
    @endcomponent
@endsection

@section('seo')
    <meta property="og:title" content="{{ $post->title }} | Liam Hammett"/>
    <meta property="og:description" content="{{ $post->excerpt }}"/>

    @foreach($post->tags as $tag)
        <meta property="article:tag" content="{{ $tag->name }}"/>
    @endforeach
    <meta property="article:published_time" content="{{ optional($post->publish_date)->toIso8601String() }}"/>
    <meta property="og:updated_time" content="{{ $post->updated_at->toIso8601String() }}"/>

    <meta name="twitter:card" content="summary_large_image"/>
    <meta name="twitter:description" content="{{ $post->excerpt }}"/>
    <meta name="twitter:title" content="{{ $post->title }} | Liam Hammett"/>
    <meta name="twitter:site" content="@liamhammett"/>
    <meta name="twitter:creator" content="@liamhammett"/>
@endsection
