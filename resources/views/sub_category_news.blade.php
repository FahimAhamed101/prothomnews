@extends('user.body.app')

@section('title', \App\Support\LocaleHelper::categoryName($subCategoryName->name ?? __('Subcategory')) . ' | ' . __('Daily News'))

@section('content')
    <div class="page-shell py-8 sm:py-12">
        <h1 class="section-heading mb-8">{{ \App\Support\LocaleHelper::categoryName($subCategoryName->name ?? __('Subcategory')) }}</h1>
        <div class="grid gap-10 lg:grid-cols-[1fr_300px]">
            <div>
                @include('partials.news-list', ['newsItems' => $otherNews])
                <div class="mt-10">{{ $otherNews->links() }}</div>
            </div>
            @include('partials.news-sidebar', compact('newnewspost', 'newspopular'))
        </div>
    </div>
@endsection
