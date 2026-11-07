@extends('user.body.app')
@section('title', __('Photos') . ' | ' . __('Daily News'))
@section('content')
    @php($imageUrl = fn ($path) => str_starts_with($path, 'http') ? $path : asset($path))
    <div class="page-shell py-10">
        <h1 class="section-heading mb-8">{{ __('Photos') }}</h1>
        <div class="columns-1 gap-5 sm:columns-2 lg:columns-3">
            @foreach ($photogalleries as $photo)
                <figure class="mb-5 break-inside-avoid overflow-hidden bg-news-paper">
                    <img src="{{ $imageUrl($photo->photo_gallery) }}" alt="{{ __('News Photo') }}" class="w-full">
                    <figcaption class="p-3 text-xs text-gray-500">{{ \App\Support\LocaleHelper::number($photo->post_date) }}</figcaption>
                </figure>
            @endforeach
        </div>
    </div>
@endsection
