@extends('user.body.app')

@section('title', \App\Support\LocaleHelper::categoryName($categoryName->name ?? __('Category')) . ' | ' . __('Daily News'))

@section('content')
    <div class="page-shell py-8 sm:py-12">
        @if ($banner?->news_category_one)
            <img src="{{ str_starts_with($banner->news_category_one, 'http') ? $banner->news_category_one : asset($banner->news_category_one) }}" alt="{{ __('Advertisement') }}" class="mb-10 h-24 w-full object-cover">
        @endif
        <h1 class="section-heading mb-8">{{ \App\Support\LocaleHelper::categoryName($categoryName->name ?? __('Category')) }}</h1>
        <div class="grid gap-10 lg:grid-cols-[1fr_300px]">
            <div>
                @include('partials.news-list', ['newsItems' => $otherNews])
                <div class="mt-10">{{ $otherNews->links() }}</div>
            </div>
            @include('partials.news-sidebar', compact('newnewspost', 'newspopular'))
        </div>
    </div>
@endsection
