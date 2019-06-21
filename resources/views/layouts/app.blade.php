<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="mobile-web-app-capable" content="yes">

        @isset($title)
            <title>{{ $title }} - Liam Hammett</title>
        @else
            <title>Liam Hammett</title>
        @endisset

        @include('feed::links')
        @include('layouts.partials.seo')

        @foreach(config('feed.feeds') as $feed)
            <link rel="alternate" type="application/rss+xml" title="{{ $feed['title'] }}" href="{{ url($feed['url']) }}">
        @endforeach

        <link rel="dns-prefetch" href="//fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
        <link href="https://fonts.googleapis.com/css?family=IBM+Plex+Mono:400|Merriweather:400,400i,700,700i|Zilla+Slab:300,300i,400,400i&display=swap" rel="stylesheet">

        <link href="{{ mix('css/app.css') }}" rel="stylesheet">

        <script src="{{ mix('js/app.js') }}" defer></script>
    </head>

    <body>
        <div class="font-sans text-gray-900">
            @include('layouts.partials.analytics')
            @include('layouts.partials.flash')

            <div class="max-w-xl md:max-w-6xl mx-auto">
                <div class="md:flex mx-2">
                    <header class="px-4 md:px-8 leading-tight mb-12">
                        <div class="sticky top-0 pt-4 md:pt-12 md:text-right">
                            <div class="md:border-r border-gray-200 md:pr-8">
                            <div class="flex md:block items-center">
                                    <h1 class="text-lg uppercase tracking-wider font-extrabold md:mb-8">
                                        <a href="/">Liam Hammett</a>
                                    </h1>
                                    <label for="mobile-menu-toggle" class="md:hidden bg-gray-700 text-white uppercase tracking-wider font-semibold p-2 ml-auto">
                                        Menu
                                    </label>
                                </div>
                                <nav class="leading-loose">
                                    <input class="hidden" type="checkbox" id="mobile-menu-toggle">
                                    <div class="mobile-menu md:block">
                                        <div class="px-2 md:mb-8">
                                            {{ Menu::primary()
                                                ->addClass('text-gray-700 md:mb-6')
                                                ->setActiveClass('font-bold text-black') }}
                                        </div>
                                        @include('layouts.partials.socialIcons')
                                    </div>
                                </div>
                            </nav>
                        </div>
                    </header>
                    <main class="my-8 md:mt-12 flex-1 min-w-0 px-4 md:px-12 lg:pl-24 lg:pr-16">
                        @yield('content')
                    </main>
                </div>
            </div>
        </div>
    </body>
</html>