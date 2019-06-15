@extends('layouts.app', [
    'title' => 'Tags',
])

@section('content')
    <section>
        <header class="mb-6">
            <h1 class="max-w-lg text-2xl md:text-3xl font-extrabold leading-tight mb-1 font-slab">
                Tags
            </h1>
        </header>
    </section>

    <ul class="list-disc">
        @foreach($tags as $tag)
            <li>
                <a href="{{ $tag->link }}">{!! $tag->name !!}</a>
            </li>
        @endforeach
    </ul>
@endsection
