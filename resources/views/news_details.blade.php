@extends('user.body.app')
@section('title', $news->title . ' | ' . __('Daily News'))
@section('content')
    @php($imageUrl = fn ($path) => $path && str_starts_with($path, 'http') ? $path : ($path ? asset($path) : asset('no_image.jpg')))
    <div class="page-shell py-8 sm:py-12">
        @if ($banner?->news_details_one)
            <img src="{{ $imageUrl($banner->news_details_one) }}" alt="{{ __('Advertisement') }}" class="mb-10 h-24 w-full object-cover">
        @endif
        <div class="grid gap-12 lg:grid-cols-[minmax(0,1fr)_320px]">
            <article>
                <p class="mb-3 font-semibold text-news-red">{{ \App\Support\LocaleHelper::categoryName($news->category?->name) }}</p>
                <h1 id="articleTitle" class="text-3xl leading-[1.25] font-extrabold sm:text-5xl">{{ $news->title }}</h1>
                
                <div class="mt-5 flex flex-wrap items-center justify-between gap-x-4 gap-y-2 border-b border-gray-200 pb-5 text-sm text-gray-500">
                    <div class="flex flex-wrap gap-x-4 gap-y-2">
                        <span>{{ $news->admin?->name ?? __('Staff Reporter') }}</span>
                        <span>{{ \App\Support\LocaleHelper::formatDateTime($news->created_at) }}</span>
                        <span>{{ \App\Support\LocaleHelper::number($news->view_count) }} {{ __('reads') }}</span>
                    </div>

                    {{-- Article Language/Translation Switcher --}}
                    <div class="flex items-center gap-1.5">
                        <button type="button" id="toggleTranslateBtn" onclick="toggleArticleTranslate()" class="inline-flex items-center gap-1.5 rounded-full border border-gray-300 bg-gray-50 px-3 py-1 text-xs font-semibold text-gray-700 hover:bg-gray-100 hover:border-gray-400 transition" title="{{ __('Translate this article to English') }}">
                            <svg class="size-3.5 text-news-red" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20"/><path d="M2 12h20"/></svg>
                            <span id="translateStatusText">{{ app()->getLocale() === 'en' ? 'Translated (EN)' : __('Translate Article') }}</span>
                        </button>
                    </div>
                </div>

                @if ($news->video_type === 'youtube' && $news->videoEmbedUrl())
                    <div class="mt-7 aspect-video overflow-hidden bg-black"><iframe src="{{ $news->videoEmbedUrl() }}" title="{{ $news->title }}" class="size-full" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe></div>
                @elseif ($news->video_type === 'upload' && $news->video_path)
                    <video class="mt-7 aspect-video w-full bg-black" controls preload="metadata" poster="{{ $imageUrl($news->image) }}"><source src="{{ asset($news->video_path) }}">{{ __('Your browser cannot play the video.') }}</video>
                @elseif ($news->image)
                    <img src="{{ $imageUrl($news->image) }}" alt="{{ $news->title }}" class="story-image mt-7 aspect-[16/9]">
                @endif

                <div id="articleBody" class="mt-8 space-y-5 text-[18px] leading-9 text-gray-800">
                    @foreach (preg_split('/\r\n|\r|\n/', (string) $news->details) as $paragraph)
                        @if (trim($paragraph) !== '')
                            <p>{{ $paragraph }}</p>
                        @endif
                    @endforeach
                </div>

                @if ($tags_all)
                    <div class="mt-8 flex flex-wrap gap-2">
                        @foreach ($tags_all as $tag)
                            <span class="bg-news-paper px-3 py-1 text-sm font-medium">#{{ $tag }}</span>
                        @endforeach
                    </div>
                @endif

                <section class="mt-12 border-t border-gray-300 pt-7">
                    <h2 class="text-xl font-bold">{{ __('Leave a Comment') }}</h2>
                    @auth('web')
                        <form method="POST" action="{{ route('review-post') }}" class="mt-4">
                            @csrf
                            <input type="hidden" name="id" value="{{ $news->id }}">
                            <textarea name="comments" rows="4" class="field" placeholder="{{ __('Write your comment here...') }}" required></textarea>
                            <button class="button-primary mt-3">{{ __('Submit') }}</button>
                        </form>
                    @else
                        <p class="mt-3 text-gray-600"><a href="{{ route('user-login') }}" class="text-news-red font-semibold underline">{{ __('Login') }}</a> {{ __('Log in to comment.') }}</p>
                    @endauth
                </section>

                @if ($relatedNews->isNotEmpty())
                    <section class="mt-12">
                        <h2 class="section-heading mb-6">{{ __('Read More') }}</h2>
                        @include('partials.news-list', ['newsItems' => $relatedNews])
                    </section>
                @endif
            </article>

            @include('partials.news-sidebar', compact('newnewspost', 'newspopular'))
        </div>
    </div>

    <script>
        function toggleArticleTranslate() {
            const currentLang = '{{ app()->getLocale() }}';
            const targetLang = currentLang === 'bn' ? 'en' : 'bn';
            window.location.href = "{{ url('/language') }}/" + targetLang;
        }
    </script>
@endsection
