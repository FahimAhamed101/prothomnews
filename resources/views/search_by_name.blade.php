@extends('user.body.app')
@section('title', __('Search') . ' | ' . __('Daily News'))
@section('content')
    <div class="page-shell py-10">
        <h1 class="section-heading mb-8">{{ __('Search Results for ":query"', ['query' => $item]) }}</h1>
        @include('partials.news-list', ['newsItems' => $news])
    </div>
@endsection
