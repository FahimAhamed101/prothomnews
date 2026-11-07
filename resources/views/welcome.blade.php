@extends('user.body.app')

@section('title', __('Daily News | Latest Bengali News'))

@section('content')
    @php
        $imageUrl = fn ($path) => $path && str_starts_with($path, 'http') ? $path : ($path ? asset($path) : asset('no_image.jpg'));
    @endphp

    <div class="page-shell py-5 sm:py-8">
        @if ($banner?->home_one)
            <a href="#" class="mb-7 block overflow-hidden bg-gray-100" aria-label="{{ __('Advertisement') }}">
                <img src="{{ $imageUrl($banner->home_one) }}" alt="{{ __('Advertisement') }}" class="h-20 w-full object-cover sm:h-24">
            </a>
        @endif

        @if ($hero)
            <section class="grid gap-6 border-b border-gray-300 pb-8 lg:grid-cols-12">
                <aside class="order-2 lg:order-1 lg:col-span-3 lg:border-r lg:border-gray-200 lg:pr-5">
                    <h2 class="mb-4 border-b-2 border-news-ink pb-2 text-lg font-bold">{{ __('Latest') }}</h2>
                    <div class="divide-y divide-gray-200">
                        @foreach ($latestNews->take(6) as $item)
                            <article class="py-3 first:pt-0">
                                <a href="{{ route('news-detail', [$item->id, $item->slug]) }}" class="text-[15px] leading-6 font-semibold hover:text-news-red">{{ $item->title }}</a>
                                <p class="news-meta mt-1">{{ \App\Support\LocaleHelper::diffForHumans($item->created_at) }}</p>
                            </article>
                        @endforeach
                    </div>
                </aside>

                <div class="order-1 lg:order-2 lg:col-span-6">
                    <article class="border-b border-gray-200 pb-5">
                        <a href="{{ route('news-detail', [$hero->id, $hero->slug]) }}">
                            <img src="{{ $imageUrl($hero->image) }}" alt="{{ $hero->title }}" class="story-image aspect-[16/9]">
                            <p class="mt-4 text-sm font-semibold text-news-red">{{ \App\Support\LocaleHelper::categoryName($hero->category?->name) }}</p>
                            <h1 class="mt-1 text-3xl leading-[1.25] font-extrabold hover:text-news-red sm:text-4xl">{{ $hero->title }}</h1>
                        </a>
                        <p class="mt-3 text-[15px] leading-7 text-gray-600">{{ Str::limit($hero->details, 170) }}</p>
                    </article>

                    <div class="mt-5 grid gap-5 sm:grid-cols-2">
                        @foreach ($leadStories->take(2) as $item)
                            <article class="grid grid-cols-[1fr_110px] gap-3 sm:block">
                                <a href="{{ route('news-detail', [$item->id, $item->slug]) }}" class="contents sm:block">
                                    <div>
                                        <p class="mb-1 text-xs font-semibold text-news-red">{{ \App\Support\LocaleHelper::categoryName($item->category?->name) }}</p>
                                        <h2 class="news-card-title">{{ $item->title }}</h2>
                                    </div>
                                    <img src="{{ $imageUrl($item->image) }}" alt="{{ $item->title }}" class="story-image order-last aspect-[4/3] sm:mb-3 sm:aspect-[16/9]">
                                </a>
                            </article>
                        @endforeach
                    </div>
                </div>

                <aside class="order-3 lg:col-span-3 lg:border-l lg:border-gray-200 lg:pl-5">
                    <h2 class="mb-4 border-b-2 border-news-red pb-2 text-lg font-bold">{{ __('Popular') }}</h2>
                    <ol class="divide-y divide-gray-200">
                        @foreach ($popularNews as $item)
                            <li class="grid grid-cols-[32px_1fr] gap-3 py-3 first:pt-0">
                                <span class="text-3xl font-light text-gray-300">{{ \App\Support\LocaleHelper::number($loop->iteration) }}</span>
                                <div>
                                    <a href="{{ route('news-detail', [$item->id, $item->slug]) }}" class="text-[15px] leading-6 font-semibold hover:text-news-red">{{ $item->title }}</a>
                                    <p class="news-meta mt-1">{{ \App\Support\LocaleHelper::number($item->view_count) }} {{ __('reads') }}</p>
                                </div>
                            </li>
                        @endforeach
                    </ol>
                </aside>
            </section>
        @else
            <section class="border border-dashed border-gray-300 py-20 text-center">
                <h1 class="text-2xl font-bold">{{ __('No news published yet') }}</h1>
                <p class="mt-2 text-gray-500">{{ __('Publish the first news from the admin panel.') }}</p>
            </section>
        @endif
    </div>

    @if ($banner?->home_two)
        <div class="bg-news-paper py-8">
            <div class="page-shell"><img src="{{ $imageUrl($banner->home_two) }}" alt="{{ __('Advertisement') }}" class="mx-auto h-24 w-full max-w-4xl object-cover"></div>
        </div>
    @endif

    <div class="page-shell space-y-14 py-10">
        @foreach ($categorySections as $category)
            <section>
                <div class="mb-5 flex flex-wrap items-end justify-between gap-3">
                    <a href="{{ route('category-news', [$category->id, $category->slug]) }}" class="section-heading">{{ \App\Support\LocaleHelper::categoryName($category->name) }}</a>
                    <div class="flex gap-4 text-sm text-gray-500">
                        @foreach ($category->subcategories->take(4) as $subcategory)
                            <a href="{{ route('sub-category-news', [$subcategory->id, $subcategory->slug]) }}" class="hover:text-news-red">{{ \App\Support\LocaleHelper::categoryName($subcategory->name) }}</a>
                        @endforeach
                    </div>
                </div>

                <div class="grid gap-6 lg:grid-cols-12">
                    @php($featured = $category->news->first())
                    @if ($featured)
                        <article class="lg:col-span-6 lg:border-r lg:border-gray-200 lg:pr-6">
                            <a href="{{ route('news-detail', [$featured->id, $featured->slug]) }}">
                                <img src="{{ $imageUrl($featured->image) }}" alt="{{ $featured->title }}" class="story-image aspect-[16/9]">
                                <h2 class="mt-3 text-2xl leading-snug font-extrabold hover:text-news-red">{{ $featured->title }}</h2>
                            </a>
                            <p class="mt-2 text-sm leading-6 text-gray-600">{{ Str::limit($featured->details, 145) }}</p>
                        </article>
                    @endif

                    <div class="grid gap-5 sm:grid-cols-2 lg:col-span-6">
                        @foreach ($category->news->skip(1)->take(4) as $item)
                            <article class="border-b border-gray-200 pb-4">
                                <a href="{{ route('news-detail', [$item->id, $item->slug]) }}">
                                    <img src="{{ $imageUrl($item->image) }}" alt="{{ $item->title }}" class="story-image mb-3 aspect-[16/9]">
                                    <h3 class="news-card-title">{{ $item->title }}</h3>
                                </a>
                                <p class="news-meta mt-2">{{ \App\Support\LocaleHelper::diffForHumans($item->created_at) }}</p>
                            </article>
                        @endforeach
                    </div>
                </div>
            </section>

            @if ($loop->iteration === 2 && $banner?->home_three)
                <div class="bg-news-paper p-5"><img src="{{ $imageUrl($banner->home_three) }}" alt="{{ __('Advertisement') }}" class="mx-auto h-24 w-full max-w-4xl object-cover"></div>
            @endif
        @endforeach
    </div>

    @if ($photoGalleries->isNotEmpty())
        <section class="page-shell py-10">
            <div class="mb-5 flex items-end justify-between">
                <a href="{{ route('all-photo-gallery') }}" class="section-heading">{{ __('Photos') }}</a>
                <a href="{{ route('all-photo-gallery') }}" class="text-sm text-news-red font-semibold">{{ __('All Photos →') }}</a>
            </div>
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($photoGalleries as $photo)
                    <a href="{{ route('all-photo-gallery') }}" class="group relative overflow-hidden {{ $loop->first ? 'sm:col-span-2 sm:row-span-2' : '' }}">
                        <img src="{{ $imageUrl($photo->photo_gallery) }}" alt="{{ __('News Photo') }}" class="story-image h-full min-h-52 transition-transform duration-500 group-hover:scale-105 {{ $loop->first ? 'sm:min-h-[432px]' : '' }}">
                        <span class="absolute top-3 left-3 grid size-8 place-items-center rounded-full bg-news-red text-white">▣</span>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    @if ($videoNews->isNotEmpty() || $videoGalleries->isNotEmpty())
        <section class="mt-10 bg-[#252525] py-12 text-white">
            <div class="page-shell">
                <div class="mb-6 flex items-end justify-between">
                    <a href="{{ route('all-video-gallery') }}" class="flex items-center gap-2 text-2xl font-bold">{{ __('Videos') }} <span class="text-news-red">›</span></a>
                    <a href="{{ route('all-video-gallery') }}" class="text-sm text-gray-300 hover:text-white">{{ __('All Videos →') }}</a>
                </div>
                @if ($videoNews->isNotEmpty())
                    <div class="grid gap-6 lg:grid-cols-2">
                        @foreach ($videoNews as $item)
                            <a href="{{ route('news-detail', [$item->id, $item->slug]) }}" class="group relative overflow-hidden {{ $loop->first ? 'lg:row-span-2' : '' }}">
                                <img src="{{ $imageUrl($item->image) }}" alt="{{ $item->title }}" class="story-image {{ $loop->first ? 'aspect-[16/10]' : 'aspect-[16/7]' }} brightness-75 transition group-hover:brightness-50">
                                <span class="absolute top-4 left-4 grid size-11 place-items-center rounded-full bg-news-red text-lg">▶</span>
                                <h3 class="absolute right-5 bottom-5 left-5 text-xl leading-snug font-bold {{ $loop->first ? 'sm:text-3xl' : '' }}">{{ $item->title }}</h3>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach ($videoGalleries as $item)
                            <a href="{{ $item->url }}" target="_blank" rel="noopener" class="group">
                                <div class="relative"><img src="{{ $imageUrl($item->image) }}" alt="{{ $item->title }}" class="story-image aspect-video brightness-75"><span class="absolute top-3 left-3 grid size-9 place-items-center rounded-full bg-news-red">▶</span></div>
                                <h3 class="mt-3 font-bold group-hover:text-news-red">{{ $item->title }}</h3>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        </section>
    @endif
@endsection
