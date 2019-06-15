<a href="{{ $url }}" class="flex rounded-lg p-4 border border-gray-400 font-sans text-sm bg-white hover:bg-gray-100 no-markup" target="_blank">
    @if ($imageUrl)
        <img src="{{ $imageUrl }}" class="mr-8 h-32" alt="{{ $siteName }}">
    @endif

    <div class="flex flex-col justify-center">
        @if ($title)
            <p class="text-black mb-2">{{ $title }}</p>
        @endif

        @if ($description)
            <p class="text-gray-700 mb-2">{{ $description }}</p>
        @endif

        @if ($domainName)
            <p class="text-gray-600 text-xs">{{ $domainName }}</p>
        @endif
    </div>
</a>
