<a href="{{ $url }}" class="flex flex-col md:flex-row rounded-lg p-4 border border-gray-400 font-sans text-sm bg-white hover:bg-gray-100 no-markup items-center" target="_blank">
    @if ($imageUrl)
        <img src="{{ $imageUrl }}" class="md:mr-8 mb-4 md:mb-0 h-32 object-contain" alt="{{ $siteName }}">
    @endif

    <div class="flex flex-col justify-center">
        @if ($title)
            <p class="text-black mb-2">{{ $title }}</p>
        @endif

        @if ($description)
            <p class="text-gray-700 mb-2">{{ $description }}</p>
        @endif

        @if ($domainName)
            <p class="text-gray-700 text-xs">{{ $domainName }}</p>
        @endif
    </div>
</a>
