<aside class="space-y-8">
    <section>
        <h2 class="mb-4 border-b-2 border-news-red pb-2 text-xl font-bold">{{ __('Latest') }}</h2>
        <div class="divide-y divide-gray-200">
            @foreach ($newnewspost as $item)
                <article class="py-3 first:pt-0">
                    <a href="{{ route('news-detail', [$item->id, $item->slug]) }}" class="font-semibold leading-6 hover:text-news-red">{{ $item->title }}</a>
                </article>
            @endforeach
        </div>
    </section>
    <section>
        <h2 class="mb-4 border-b-2 border-news-ink pb-2 text-xl font-bold">{{ __('Popular') }}</h2>
        <ol class="divide-y divide-gray-200">
            @foreach ($newspopular as $item)
                <li class="grid grid-cols-[28px_1fr] gap-3 py-3">
                    <span class="text-2xl text-gray-300">{{ \App\Support\LocaleHelper::number($loop->iteration) }}</span>
                    <a href="{{ route('news-detail', [$item->id, $item->slug]) }}" class="font-semibold leading-6 hover:text-news-red">{{ $item->title }}</a>
                </li>
            @endforeach
        </ol>
    </section>
</aside>
