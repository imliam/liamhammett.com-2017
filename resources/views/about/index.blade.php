@extends('layouts.app', [
    'title' => 'About me',
])

@section('content')
    <div class="markup mb-6">
        <h1 class="font-slab">About me</h1>
    </div>
    <div class="markup">
        <p>
            Hi, I'm Liam. I'm a full-stack web developer from the beautiful South-West of England.
        </p>
        <p>
            I love working with PHP and JavaScript, with a particular affection for the Laravel and Vue frameworks respectively.
        </p>
        <p>
            I work at <a href="https://futureplc.com/" class="font-bold">Future Publishing</a> developing software to deliver great media content across the globe. In the past, I've worked on SaaS platforms, built utilities for gaming companies, worked on clinical software for the NHS and more.
        </p>
        <p>
            I maintain a handful of <a href="https://github.com/imliam" class="font-bold">open source projects on GitHub</a>, share what I learn <a href="https://twitter.com/liamhammett" class="font-bold">on Twitter</a> and occasionally <a href="https://dribbble.com/liamhammett" class="font-bold">design things</a>.
        </p>
    </div>
@endsection
