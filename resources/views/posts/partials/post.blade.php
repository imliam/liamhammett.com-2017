<article class="{{ $class ?? '' }}">
    <header class="mb-6">
        <{{ $heading ?? 'h1' }} class="max-w-xl leading-tight mb-2 font-slab {{ $heading ?? 'h1' === 'h2' ? 'font-extrabold text-2xl md:text-3xl' : 'text-3xl md:text-4xl' }}">
            @isset($url)
                <a href="{{ $url }}">{{ $post->title }}</a>
            @else
                {{ $post->title }}
            @endisset
        </{{ $heading ?? 'h1' }}>

        <p class="text-sm text-gray-700">
            <time datetime="{{ optional($post->publish_date)->format(DateTime::ATOM) }}">
                {{ optional($post->publish_date)->format('M jS Y') }}
            </time>
            @if($post->external_url)
                &middot;
                <a href="{{ $post->external_url }}">
                    {{ $post->external_url_host }}</a>
            @elseif($post->isBlog())
                by {{ $post->author }}
                &middot; {{ $post->reading_time }} minute read
            @endif

            @if(!$post->tags->isEmpty())
                &middot;
            @endif
            @foreach($post->tags as $tag)
                <a href="{{ $tag->link }}" class="bg-gray-200 hover:bg-gray-300 p-1 rounded">#{{ $tag->name }}</a>
            @endforeach

            @auth
                &middot;
                <a target="_blank" href="/nova/resources/posts/{{ $post->id }}/edit">
                    Edit</a>
            @endauth
        </p>
    </header>
    <div class="markup leading-relaxed">
        {{ $slot }}
    </div>
</article>
