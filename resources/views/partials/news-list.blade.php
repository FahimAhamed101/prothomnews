@php($imageUrl = fn ($path) => $path && str_starts_with($path, 'http') ? $path : ($path ? asset($path) : 'https://placehold.co/640x400/f1f1f1/777?text=News'))
@if ($newsItems->count())
    <div class="grid gap-x-6 gap-y-9 sm:grid-cols-2 lg:grid-cols-3">
        @foreach ($newsItems as $item)
            <article>
                <a href="{{ route('news-detail', [$item->id, $item->slug]) }}">
                    <div class="relative">
                        <img src="{{ $imageUrl($item->image) }}" alt="{{ $item->title }}" class="story-image aspect-[16/10]">
                        @if ($item->video_type)<span class="absolute top-3 left-3 grid size-9 place-items-center rounded-full bg-news-red text-xs text-white">▶</span>@endif
                    </div>
                    <h2 class="news-card-title mt-3">{{ $item->title }}</h2>
                </a>
                <p class="mt-2 text-sm leading-6 text-gray-600">{{ Str::limit($item->details, 105) }}</p>
                <p class="news-meta mt-2">{{ \App\Support\LocaleHelper::diffForHumans($item->created_at) }}</p>
            </article>
        @endforeach
    </div>
@else
    <div class="border border-dashed border-gray-300 py-16 text-center text-gray-500">{{ __('No news found.') }}</div>
@endif
