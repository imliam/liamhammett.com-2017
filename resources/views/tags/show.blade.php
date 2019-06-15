@extends('layouts.app', [
    'title' => '#' . $tag->name,
])

@section('content')
    <section>
        <header class="mb-6">
            <h1 class="max-w-lg text-2xl md:text-3xl font-extrabold leading-tight mb-1 font-slab">
                Posts tagged #{{ $tag->name }}
            </h1>
        </header>
    </section>

    @include('posts.partials.list')

    {{ $posts->links() }}
@endsection
