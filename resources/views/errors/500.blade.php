@extends('layouts.app', [
    'title' => 'Server error',
])

@section('content')
    <div class="font-sans text-black leading-none text-center md:text-left">
        <h1 class="text-5xl font-extrabold mb-8 font-slab">Something Went Wrong!</h1>
        <p class="text-xl text-gray-700 mb-4">
            Whoops! An internal server error occurred. Please try again later.
        </p>
        <p class="text-xl text-gray-700 mb-4">
            <a href="{{ url('/') }}" class="link">Go to the home page</a> to continue browsing the site.
        </p>
    </div>
@endsection
