@extends('layouts.app')

@section('content')
    <section class="mb-12 md:mb-24 -mt-8 md:-mt-12 -mx-12 px-12 py-8 md:py-12 md:rounded-b-lg bg-gray-900 text-white">
        <header class="mb-6">
            <h1 class="max-w-lg text-3xl md:text-4xl font-extrabold leading-tight mb-1 font-slab">
                Hi, I'm Liam
            </h1>
        </header>
        <p class="leading-relaxed mb-6 text-lg">
            I'm a full-stack software developer that loves working with PHP, JavaScript, Laravel and Vue.
        </p>
        <p class="leading-relaxed mb-6 text-lg">
            Here you can find my latest blog posts as well as any useful tips &amp; tricks, opinions, or other miscellaneous things I might share.</p>
        </p>
    </section>

    @include('posts.partials.list')

    {{ $posts->links() }}
@endsection
