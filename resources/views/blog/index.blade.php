@extends('layouts.app', [
    'title' => 'Blog',
])

@section('content')
    @include('posts.partials.list')

    {{ $posts->links() }}
@endsection
