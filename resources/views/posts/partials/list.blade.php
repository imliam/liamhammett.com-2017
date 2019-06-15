@foreach($posts as $post)
    @component('posts.partials.post', [
        'post' => $post,
        'url' => $post->external_url ?: $post->url,
        'class' => 'mb-12 md:mb-24',
        'heading' => 'h2',
    ])
        {!! $post->formatted_excerpt !!}

        @unless($post->isTweet())
            <p class="mt-6">
                @if($post->external_url)
                    <a href="{{ $post->external_url }}">
                        Read more&hellip;
                    </a>
                    <span class="text-xs text-gray-700">[{{ $post->external_url_host }}]</span>
                @else
                    <a href="{{ $post->url }}">
                        Read more&hellip;
                    </a>
                @endif
            </p>
        @endunless
    @endcomponent
@endforeach
