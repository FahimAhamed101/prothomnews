@extends('user.body.app')
@section('title', __('Videos') . ' | ' . __('Daily News'))
@section('content')
    @php($imageUrl = fn ($path) => $path && str_starts_with($path, 'http') ? $path : ($path ? asset($path) : asset('no_image.jpg')))
    <div class="bg-[#252525] py-12 text-white">
        <div class="page-shell">
            <h1 class="mb-8 flex items-center gap-2 text-3xl font-bold">{{ __('Videos') }} <span class="text-news-red">›</span></h1>
            <div class="grid gap-7 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($videogalleries as $video)
                    <a href="{{ $video->url }}" target="_blank" rel="noopener" class="group">
                        <div class="relative">
                            <img src="{{ $imageUrl($video->image) }}" alt="{{ $video->title }}" class="story-image aspect-video brightness-75">
                            <span class="absolute top-4 left-4 grid size-10 place-items-center rounded-full bg-news-red">▶</span>
                        </div>
                        <h2 class="mt-3 text-lg font-bold group-hover:text-news-red">{{ $video->title }}</h2>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
@endsection
