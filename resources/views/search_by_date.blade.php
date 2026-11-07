@extends('user.body.app')
@section('title', __('News on :date', ['date' => $formatDate]) . ' | ' . __('Daily News'))
@section('content')
    <div class="page-shell py-10">
        <h1 class="section-heading mb-8">{{ __('News on :date', ['date' => \App\Support\LocaleHelper::number($formatDate)]) }}</h1>
        @include('partials.news-list', ['newsItems' => $news])
    </div>
@endsection
